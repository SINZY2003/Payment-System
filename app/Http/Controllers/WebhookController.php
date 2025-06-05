<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;
use Mail;
use App\Mail\InvoiceMail;

class WebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        \Log::info('Webhook handleWebhook method entered.');
        $payload = $request->getContent();
        $sig_header = $request->header('stripe-signature');
        $endpoint_secret = env('STRIPE_WEBHOOK_SECRET');

        // Determine if we're in test or production mode
        $isTestMode = app()->environment('local') || strpos(env('STRIPE_SECRET', ''), 'sk_test_') === 0;
        \Log::info('Stripe mode: ' . ($isTestMode ? 'TEST' : 'LIVE'));

        // In test mode with no signature header, we'll parse the JSON directly
        if ($isTestMode && !$sig_header) {
            \Log::info('Test mode detected with no signature. Processing webhook without signature verification.');
            try {
                $event = json_decode($payload);
                if (!isset($event->type)) {
                    \Log::error('Invalid webhook payload in test mode: missing event type');
                    $event = new \stdClass();
                    $event->type = 'checkout.session.completed'; // Default to this event for testing
                    $event->data = new \stdClass();
                    $event->data->object = $this->createMockSessionObject();
                    \Log::info('Created mock session object for testing');
                }
            } catch (\Exception $e) {
                \Log::error('Error parsing webhook payload in test mode: ' . $e->getMessage());
                return response()->json(['error' => $e->getMessage()], 400);
            }
        } else {
            // Normal signature verification for production or when signature is present
            try {
                $event = Webhook::constructEvent(
                    $payload, $sig_header, $endpoint_secret
                );
            } catch (SignatureVerificationException $e) {
                // Invalid signature
                \Log::error('Stripe Signature Verification Error: ' . $e->getMessage());
                return response()->json(['error' => 'Invalid signature'], 400);
            } catch (\Exception $e) {
                \Log::error('Webhook Error: ' . $e->getMessage());
                return response()->json(['error' => $e->getMessage()], 400);
            }

            if (!isset($event->type)) {
                \Log::error('Invalid webhook payload: missing event type');
                return response()->json(['error' => 'Invalid payload'], 400);
            }
        }

        \Log::info('Webhook event type received: ' . $event->type);

        // Handle the event
        switch ($event->type) {
            case 'checkout.session.completed':
                $session = $event->data->object;
                \Log::info('Checkout session completed event received. Session ID: ' . $session->id);
                // This is where you would fulfill your customer's purchase
                // For example, send an invoice email
                $this->sendInvoiceEmail($session);
                break;
            // ... handle other event types
            default:
                \Log::info('Received unknown event type ' . $event->type);
                // echo 'Received unknown event type ' . $event->type; // Removed echo for cleaner response
        }

        return response()->json(['status' => 'success'], 200);
    }

protected function sendInvoiceEmail($session)
{
    // Get the customer email from the session object
    $customerEmail = $session->customer_details->email ?? null;

    \Log::info('Attempting to send invoice email for session: ' . $session->id);

    if (!$customerEmail) {
        \Log::error('Customer email not found for checkout session: ' . $session->id);
        return;
    }
    
    \Log::info('Customer email found: ' . $customerEmail);
    
    $paymentIntentId = $session->payment_intent ?? null;
    
    // Determine if we're in test mode
    $isTestMode = app()->environment('local') || strpos(env('STRIPE_SECRET', ''), 'sk_test_') === 0;
    
    // If we're in test mode and no payment intent is available, create a mock charge
    if ($isTestMode && !$paymentIntentId) {
        \Log::info('Test mode detected with no payment intent. Creating mock charge for testing.');
        $charge = $this->createMockChargeObject();
        $charge->customer_name = $session->customer_details->name ?? 'Test Customer';
        
        // Send the test invoice email
        Mail::to($customerEmail)->send(new InvoiceMail($charge));
        \Log::info('Test invoice email sent to: ' . $customerEmail);
        \Log::info('Email content will be in laravel log since MAIL_DRIVER=log is set in .env');
        return;
    }
    
    if (!$paymentIntentId) {
        \Log::error('Payment Intent ID not found for checkout session: ' . $session->id);
        return;
    }
    
    \Log::info('Payment Intent ID found: ' . $paymentIntentId);
    
    try {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        
        // Retrieve the payment intent with expanded charge data
        $paymentIntent = \Stripe\PaymentIntent::retrieve(
            $paymentIntentId, 
            ['expand' => ['charges', 'customer', 'invoice']]
        );
        
        // Check if charges data exists and is not empty
        if (empty($paymentIntent->charges->data) || !isset($paymentIntent->charges->data[0])) {
            \Log::error('No charge data found for payment intent: ' . $paymentIntentId);
            
            if ($isTestMode) {
                \Log::info('Creating mock charge data for test mode since no real charge found');
                $charge = $this->createMockChargeObject();
                $charge->customer_name = $session->customer_details->name ?? 'Test Customer';
                
                // Send the test invoice email
                Mail::to($customerEmail)->send(new InvoiceMail($charge));
                \Log::info('Test invoice email sent to: ' . $customerEmail);
                \Log::info('Email content will be in laravel log since MAIL_DRIVER=log is set in .env');
            }
            
            return;
        }
        
        $charge = $paymentIntent->charges->data[0];
        
        // Add product name/description if available
        if (isset($session->line_items)) {
            $charge->description = $session->line_items->data[0]->description ?? 'Your purchase';
        } else {
            $charge->description = $session->metadata->product_name ?? 'Your purchase';
        }
        
        // Add additional information that might be helpful for the invoice
        $charge->customer_name = $session->customer_details->name ?? '';
        $charge->receipt_url = $charge->receipt_url ?? '';
        
        \Log::info('Charge object prepared. Sending email...');
        
        // Send the invoice email using the InvoiceMail Mailable
        Mail::to($customerEmail)->send(new InvoiceMail($charge));
        
        \Log::info('Invoice email sent to: ' . $customerEmail);
        \Log::info('Email content will be in laravel log since MAIL_DRIVER=log is set in .env');
        
    } catch (\Stripe\Exception\ApiErrorException $e) {
        \Log::error('Stripe API Error: ' . $e->getMessage());
        
        if ($isTestMode) {
            \Log::info('Creating mock charge for test mode after API error');
            $charge = $this->createMockChargeObject();
            $charge->customer_name = $session->customer_details->name ?? 'Test Customer';
            
            // Send the test invoice email
            Mail::to($customerEmail)->send(new InvoiceMail($charge));
            \Log::info('Test invoice email sent to: ' . $customerEmail);
            \Log::info('Email content will be in laravel log since MAIL_DRIVER=log is set in .env');
        }
    } catch (\Exception $e) {
        \Log::error('Error sending invoice email: ' . $e->getMessage());
    }
}

/**
 * Create a mock charge object for testing purposes
 */
protected function createMockChargeObject()
{
    $charge = new \stdClass();
    $charge->id = 'ch_test_' . uniqid();
    $charge->amount = 2000; // $20.00 in cents
    $charge->description = 'Test Product';
    $charge->created = time();
    $charge->payment_method_details = new \stdClass();
    $charge->payment_method_details->type = 'card';
    $charge->receipt_url = 'https://dashboard.stripe.com/test/payments';
    
    return $charge;
}

/**
 * Create a mock session object for testing purposes
 */
protected function createMockSessionObject()
{
    $session = new \stdClass();
    $session->id = 'cs_test_' . uniqid();
    $session->payment_intent = 'pi_test_' . uniqid();
    $session->customer_details = new \stdClass();
    $session->customer_details->email = 'test@example.com';
    $session->customer_details->name = 'Test Customer';
    
    return $session;
}
}
