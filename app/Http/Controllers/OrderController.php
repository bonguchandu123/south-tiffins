<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Bill;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function orderConfirm(Request $request)
    {
        $orderId = $request->query('order_id');
        $order   = Order::with(['items', 'table', 'bill'])->find($orderId);

        if (!$order) {
            return redirect('/');
        }

        return view('customer.order-confirm', compact('order'));
    }

    public function orderStatus(Request $request)
    {
        $orderId = $request->query('order_id');
        $order   = Order::with(['items', 'table'])->find($orderId);

        if (!$order) {
            return redirect('/');
        }

        return view('customer.order-status', compact('order'));
    }

    public function adminOrders()
    {
        return view('admin.orders');
    }

    public function placeOrder(Request $request)
    {
        $items                  = $request->input('items', []);
        $orderType              = $request->input('order_type');
        $orderSource            = $request->input('order_source');
        $paymentMethod          = $request->input('payment_method');
        $tableId                = $request->input('table_id');
        $customerName           = $request->input('customer_name');
        $customerPhone          = $request->input('customer_phone');
        $notes                  = $request->input('notes');
        $stripePaymentIntentId  = $request->input('stripe_payment_intent_id');

        if (empty($items)) {
            return response()->json([
                'success' => false,
                'message' => 'No items in order'
            ]);
        }

        if (!$orderType || !$orderSource || !$paymentMethod) {
            return response()->json([
                'success' => false,
                'message' => 'Missing required fields'
            ]);
        }

        DB::beginTransaction();

        try {
            $total = 0;
            foreach ($items as $item) {
                $total += floatval($item['price']) * intval($item['quantity']);
            }

            $orderNumber = 'ST' . date('ymd') . mt_rand(1000, 9999);
            while (Order::where('order_number', $orderNumber)->exists()) {
                $orderNumber = 'ST' . date('ymd') . mt_rand(1000, 9999);
            }

            // If stripe_payment_intent_id is provided, payment is already done
            $paymentStatus = ($stripePaymentIntentId && $paymentMethod === 'ONLINE')
                ? 'PAID'
                : 'UNPAID';

            $order = Order::create([
                'order_number'             => $orderNumber,
                'table_id'                 => $tableId ?: null,
                'customer_name'            => $customerName ?: null,
                'customer_phone'           => $customerPhone ?: null,
                'order_type'               => $orderType,
                'order_source'             => $orderSource,
                'status'                   => 'PENDING',
                'payment_method'           => $paymentMethod,
                'payment_status'           => $paymentStatus,
                'total_amount'             => $total,
                'notes'                    => $notes ?: null,
                'stripe_payment_intent_id' => $stripePaymentIntentId ?: null,
            ]);

            foreach ($items as $item) {
                OrderItem::create([
                    'order_id'     => $order->id,
                    'menu_item_id' => intval($item['id']),
                    'name_en'      => $item['name_en'],
                    'name_te'      => $item['name_te'] ?? $item['name_en'],
                    'quantity'     => intval($item['quantity']),
                    'price'        => floatval($item['price']),
                    'subtotal'     => floatval($item['price']) * intval($item['quantity']),
                    'image_url'    => $item['image_url'] ?? null
                ]);
            }

            Payment::create([
                'order_id'                 => $order->id,
                'amount'                   => $total,
                'payment_method'           => $paymentMethod,
                'payment_status'           => $paymentStatus,
                'stripe_payment_intent_id' => $stripePaymentIntentId ?: null,
                'paid_at'                  => $paymentStatus === 'PAID' ? now() : null,
            ]);

            $billNumber = 'BILL' . date('ymd') . mt_rand(100, 999);
            while (Bill::where('bill_number', $billNumber)->exists()) {
                $billNumber = 'BILL' . date('ymd') . mt_rand(100, 999);
            }

            $bill = Bill::create([
                'bill_number'    => $billNumber,
                'order_id'       => $order->id,
                'total_amount'   => $total,
                'payment_method' => $paymentMethod,
                'payment_status' => $paymentStatus,
                'paid_at'        => $paymentStatus === 'PAID' ? now() : null,
            ]);

            DB::commit();

            return response()->json([
                'success'      => true,
                'message'      => 'Order placed successfully',
                'order_id'     => $order->id,
                'order_number' => $order->order_number,
                'bill_number'  => $bill->bill_number,
                'total'        => $total,
                'payment_status' => $paymentStatus,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Order placement failed', [
                'message' => $e->getMessage(),
                'line'    => $e->getLine(),
                'file'    => $e->getFile()
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function updateStatus(Request $request)
    {
        $order = Order::find($request->order_id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ]);
        }

        $order->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Status updated',
            'status'  => $request->status
        ]);
    }

    public function cancelOrder(Request $request)
    {
        $order = Order::find($request->order_id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ]);
        }

        $order->update(['status' => 'CANCELLED']);

        return response()->json([
            'success' => true,
            'message' => 'Order cancelled'
        ]);
    }

    public function todayOrders()
    {
        $orders = Order::with(['items', 'table', 'bill'])
            ->whereDate('created_at', today())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'orders'  => $orders
        ]);
    }

    public function getOrder(Request $request)
    {
        $order = Order::with(['items', 'table', 'bill', 'payment'])
            ->find($request->query('id'));

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ]);
        }

        return response()->json([
            'success' => true,
            'order'   => $order
        ]);
    }
}