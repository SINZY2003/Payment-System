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
        $payload = $request->getContent();
        $sig_header = $request->header('stripe-signature');
        $endpoint_secret = env('STRIPE_WEBHOOK_SECRET'); // You'll need to set this in your .env

        try {
            $event = Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch (SignatureVerificationException $e) {
            // Invalid signature
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // Handle the event
        switch ($event->type) {
            case 'checkout.session.completed':
                $session = $event->data->object;
                // This is where you would fulfill your customer's purchase
                // For example, send an invoice email
                $this->sendInvoiceEmail($session);
                break;
            // ... handle other event types
            default:
                echo 'Received unknown event type ' . $event->type;
        }

        return response()->json(['status' => 'success'], 200);
    }

    protected function sendInvoiceEmail($session)
    {
        // Assuming you store customer email in metadata or can retrieve it from the session
        // For simplicity, let's assume the email is directly available or can be fetched
        $customerEmail = $session->customer_details->email ?? null; // Adjust based on your Stripe setup

        if ($customerEmail) {
            // You might need to fetch more details about the charge/payment intent
            // For checkout.session.completed, the payment_intent is usually available
            $paymentIntentId = $session->payment_intent;
            if ($paymentIntentId) {
                \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
                $paymentIntent = \Stripe\PaymentIntent::retrieve($paymentIntentId);
                $charge = $paymentIntent->charges->data[0] ?? null;

                if ($charge) {
                    Mail::to($customerEmail)->send(new InvoiceMail($charge));
                    \Log::info('Invoice email sent to: ' . $customerEmail);
                } else {
                    \Log::error('Charge object not found for payment intent: ' . $paymentIntentId);
                }
            } else {
                \Log::error('Payment Intent ID not found for checkout session: ' . $session->id);
            }
        } else {
            \Log::error('Customer email not found for checkout session: ' . $session->id);
        }
    }
}
