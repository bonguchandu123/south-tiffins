<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    public function today()
    {
        $today = today();

        $orders = Order::whereDate('created_at', $today)->get();

        $totalOrders   = $orders->count();
        $totalRevenue  = $orders->where('payment_status', 'PAID')->sum('total_amount');
        $cashRevenue   = $orders->where('payment_method', 'CASH')->where('payment_status', 'PAID')->sum('total_amount');
        $onlineRevenue = $orders->where('payment_method', 'ONLINE')->where('payment_status', 'PAID')->sum('total_amount');
        $dineinOrders  = $orders->where('order_type', 'DINEIN')->count();
        $parcelOrders  = $orders->where('order_type', 'PARCEL')->count();
        $walkinOrders  = $orders->where('order_type', 'WALKIN')->count();
        $pendingOrders = $orders->whereIn('status', ['PENDING', 'PREPARING', 'READY'])->count();
        $paidOrders    = $orders->where('payment_status', 'PAID')->count();
        $unpaidOrders  = $orders->where('payment_status', 'UNPAID')->count();

        $hourly = Order::whereDate('created_at', $today)
            ->where('payment_status', 'PAID')
            ->select(
                DB::raw('HOUR(created_at) as hour'),
                DB::raw('SUM(total_amount) as revenue'),
                DB::raw('COUNT(*) as orders')
            )
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();

        $recentOrders = Order::with(['table', 'items'])
            ->whereDate('created_at', $today)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return response()->json([
            'success'        => true,
            'total_orders'   => $totalOrders,
            'total_revenue'  => $totalRevenue,
            'cash_revenue'   => $cashRevenue,
            'online_revenue' => $onlineRevenue,
            'dinein_orders'  => $dineinOrders,
            'parcel_orders'  => $parcelOrders,
            'walkin_orders'  => $walkinOrders,
            'pending_orders' => $pendingOrders,
            'paid_orders'    => $paidOrders,
            'unpaid_orders'  => $unpaidOrders,
            'hourly'         => $hourly,
            'recent_orders'  => $recentOrders
        ]);
    }

    public function weekly()
    {
        $data = Order::where('created_at', '>=', now()->subDays(7))
            ->where('payment_status', 'PAID')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as revenue'),
                DB::raw('COUNT(*) as orders')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $data
        ]);
    }

    public function monthly()
    {
        $month = request('month', date('m'));
        $year  = request('year', date('Y'));

        $data = Order::whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->where('payment_status', 'PAID')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as revenue'),
                DB::raw('COUNT(*) as orders')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $total = $data->sum('revenue');

        return response()->json([
            'success' => true,
            'data'    => $data,
            'total'   => $total
        ]);
    }

    public function topItems()
    {
        $items = OrderItem::whereHas('order', function ($q) {
                $q->whereDate('created_at', today());
            })
            ->select(
                'name_en',
                'image_url',
                DB::raw('SUM(quantity) as total_qty'),
                DB::raw('SUM(subtotal) as total_revenue')
            )
            ->groupBy('name_en', 'image_url')
            ->orderBy('total_qty', 'desc')
            ->take(5)
            ->get();

        return response()->json([
            'success' => true,
            'items'   => $items
        ]);
    }
}