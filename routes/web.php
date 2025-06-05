<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvoiceMail;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/stripe', [StripeController::class, 'index']);
Route::post('/stripe/checkout', [StripeController::class, 'checkout'])->name('stripe.checkout');
Route::get('/stripe/success', [StripeController::class, 'success'])->name('stripe.success');
Route::get('/stripe/cancel', [StripeController::class, 'cancel'])->name('stripe.cancel');
Route::post('/stripe/webhook', [WebhookController::class, 'handleWebhook']);

// Test route for email template - Only accessible in local environment
if (app()->environment('local')) {
    Route::get('/test-invoice-email', function () {
        // Create a mock charge object similar to what Stripe would return
        $charge = new \stdClass();
        $charge->id = 'ch_test_' . uniqid();
        $charge->amount = 2000; // $20.00 in cents
        $charge->description = 'Test Product';
        $charge->created = time();
        $charge->payment_method_details = new \stdClass();
        $charge->payment_method_details->type = 'card';
        $charge->receipt_url = 'https://dashboard.stripe.com/test/payments';
        $charge->customer_name = 'Test Customer';
        
        // Send the test email - with log driver this will be in laravel log
        Mail::to('test@example.com')->send(new InvoiceMail($charge));
        
        return "Test invoice email sent! Check the Laravel logs to see the email content.";
    });
    
    // Test route to trigger the webhook handler directly
    Route::get('/test-webhook', function () {
        // Create a mock session object similar to what Stripe webhook would provide
        $session = new \stdClass();
        $session->id = 'cs_test_' . uniqid();
        $session->payment_intent = 'pi_test_' . uniqid();
        $session->customer_details = new \stdClass();
        $session->customer_details->email = 'test@example.com';
        $session->customer_details->name = 'Test Customer';
        
        // Call the webhook handler method directly
        app()->make(WebhookController::class)->sendInvoiceEmail($session);
        
        return "Test webhook handler executed! Check the Laravel logs for details.";
    });
}
