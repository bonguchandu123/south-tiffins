<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Bill;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class PaymentController extends Controller
{
    // Step 1 — Create Stripe PaymentIntent (before order is placed)
    public function createOrder(Request $request)
    {
        try {
            Stripe::setApiKey(env('STRIPE_SECRET'));

            $paymentIntent = PaymentIntent::create([
                'amount'   => $request->amount * 100,
                'currency' => 'inr',
                'metadata' => ['source' => 'south_tiffins']
            ]);

            return response()->json([
                'success'       => true,
                'client_secret' => $paymentIntent->client_secret,
                'payment_intent_id' => $paymentIntent->id,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Step 2 — Verify payment after Stripe confirms (called after order is placed)
    public function verifyPayment(Request $request)
    {
        try {
            Stripe::setApiKey(env('STRIPE_SECRET'));

            $paymentIntent = PaymentIntent::retrieve($request->payment_intent_id);

            if ($paymentIntent->status !== 'succeeded') {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment not completed'
                ]);
            }

            $order = Order::find($request->order_id);

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found'
                ]);
            }

            $order->update([
                'payment_status'           => 'PAID',
                'stripe_payment_intent_id' => $paymentIntent->id
            ]);

            Payment::where('order_id', $order->id)->update([
                'payment_status'           => 'PAID',
                'stripe_payment_intent_id' => $paymentIntent->id,
                'paid_at'                  => now()
            ]);

            Bill::where('order_id', $order->id)->update([
                'payment_status' => 'PAID',
                'paid_at'        => now()
            ]);

            return response()->json([
                'success'  => true,
                'message'  => 'Payment verified',
                'order_id' => $order->id
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Payment verification failed: ' . $e->getMessage()
            ], 500);
        }
    }

    // Cash payment — mark as paid
    public function markCashPaid(Request $request)
    {
        $order = Order::find($request->order_id);

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Order not found']);
        }

        $order->update(['payment_status' => 'PAID']);

        Payment::where('order_id', $order->id)->update([
            'payment_status' => 'PAID',
            'paid_at'        => now()
        ]);

        Bill::where('order_id', $order->id)->update([
            'payment_status' => 'PAID',
            'paid_at'        => now()
        ]);

        return response()->json(['success' => true, 'message' => 'Marked as paid']);
    }
}