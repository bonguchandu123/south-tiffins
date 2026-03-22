<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;

class CounterController extends Controller
{
    public function showLogin()
    {
        if (session('counter_access')) {
            return redirect()->route('counter.index');
        }
        return view('counter.login');
    }

    public function login(Request $request)
    {
        $pin = trim($request->input('pin'));

        if (!$pin) {
            return back()->with('error', 'Please enter PIN.');
        }

        $counter = DB::table('counter_access')->first();

        if (!$counter || $counter->pin !== $pin) {
            return back()->with('error', 'Invalid PIN. Please try again.');
        }

        session(['counter_access' => true]);

        return redirect()->route('counter.index');
    }

    public function logout()
    {
        session()->forget('counter_access');
        return redirect()->route('counter.login');
    }

    public function index()
    {
        return view('counter.index');
    }

    public function liveOrders()
    {
        $orders = Order::with(['items', 'table', 'bill'])
            ->whereIn('status', ['PENDING', 'PREPARING', 'READY'])
            ->whereDate('created_at', today())
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($order) {
                return [
                    'id'             => $order->id,
                    'order_number'   => $order->order_number,
                    'bill_number'    => $order->bill ? $order->bill->bill_number : null,
                    'order_type'     => $order->order_type,
                    'order_source'   => $order->order_source,
                    'status'         => $order->status,
                    'payment_method' => $order->payment_method,
                    'payment_status' => $order->payment_status,
                    'total_amount'   => $order->total_amount,
                    'table_number'   => $order->table ? $order->table->table_number : null,
                    'customer_name'  => $order->customer_name,
                    'customer_phone' => $order->customer_phone,
                    'created_at'     => $order->created_at->format('h:i A'),
                    'next_status'    => $order->getNextStatus(),
                    'items'          => $order->items->map(function ($item) {
                        return [
                            'id'        => $item->id,
                            'name_en'   => $item->name_en,
                            'name_te'   => $item->name_te,
                            'quantity'  => $item->quantity,
                            'price'     => $item->price,
                            'subtotal'  => $item->subtotal,
                            'image_url' => $item->image_url
                        ];
                    })
                ];
            });

        return response()->json([
            'success' => true,
            'orders'  => $orders,
            'count'   => $orders->count()
        ]);
    }
}