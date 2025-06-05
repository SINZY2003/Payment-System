<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session; // Add this line
use Stripe\Customer; // Add this line

class StripeController extends Controller
{
    public function index(Request $request)
    {
        return view('stripe');
    }

    public function checkout(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        // Create a new customer or retrieve an existing one
        $customer = Customer::create([
            'email' => $request->input('email'), // Assuming email is passed from frontend
            'name' => $request->input('name'),   // Assuming name is passed from frontend
        ]);

        $session = Session::create([
            'customer' => $customer->id, // Associate the session with the customer
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'Your Product Name',
                    ],
                    'unit_amount' => 2000, // Amount in cents (e.g., $20.00)
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('stripe.success'),
            'cancel_url' => route('stripe.cancel'),
        ]);

        return redirect()->away($session->url);
    }

    public function success()
    {
        return view('payment-success', [
            'message' => 'Payment successful! You will receive an email receipt shortly.',
            'title' => 'Payment Successful'
        ]);
    }

    public function cancel()
    {
        return "Payment cancelled.";
    }
}
