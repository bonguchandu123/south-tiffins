<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Bill;

class BillingController extends Controller
{
    public function index()
    {
        return view('admin.billing');
    }

    public function billingSummary()
    {
        if (!session('admin_id')) {
            return response()->json(['success' => false], 401);
        }

        $activeOrders = Order::with(['items', 'table', 'bill'])
            ->whereDate('created_at', today())
            ->whereIn('status', ['PENDING', 'PREPARING', 'READY', 'SERVED'])
            ->orderBy('created_at', 'desc')
            ->get();

        $unpaidBills = Bill::with(['order.table', 'order.items'])
            ->where('payment_status', 'UNPAID')
            ->whereDate('created_at', today())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success'       => true,
            'active_orders' => $activeOrders,
            'unpaid_bills'  => $unpaidBills
        ]);
    }
}