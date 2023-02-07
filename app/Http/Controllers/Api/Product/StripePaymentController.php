<?php

namespace App\Http\Controllers\Api\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Stripe;
use Stripe\StripeClient;

class StripePaymentController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function stripePost(Request $request)
    {
        $stripe = new StripeClient(
            env('STRIPE_SECRET'));

        $stripe->charges->create([
            'amount' => 2000,
            'currency' => 'usd',
            'source' => 'tok_visa',
            'description' => 'My First Test Charge (created for API docs at https://www.stripe.com/docs/api)',
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Charged successfully.',
        ]);
    }
}

//{
//    "name": "Test",
//    "number": "4242424242424242",
//    "cvc": "123",
//    "expiration_month": "12",
//    "expiration_year": "2028"
//}
