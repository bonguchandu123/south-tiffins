<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.reports');
    }

    public function daily()
    {
        if (!session('admin_id')) {
            return redirect()->route('login');
        }

        $date   = request('date', date('Y-m-d'));
        $orders = Order::with(['items', 'table'])
            ->whereDate('created_at', $date)
            ->orderBy('created_at')
            ->get();

        $summary = [
            'date'           => $date,
            'total_orders'   => $orders->count(),
            'total_revenue'  => $orders->where('payment_status', 'PAID')->sum('total_amount'),
            'cash_revenue'   => $orders->where('payment_method', 'CASH')->where('payment_status', 'PAID')->sum('total_amount'),
            'online_revenue' => $orders->where('payment_method', 'ONLINE')->where('payment_status', 'PAID')->sum('total_amount'),
            'dinein_orders'  => $orders->where('order_type', 'DINEIN')->count(),
            'parcel_orders'  => $orders->where('order_type', 'PARCEL')->count(),
            'walkin_orders'  => $orders->where('order_type', 'WALKIN')->count(),
        ];

        $pdf = Pdf::loadView('reports.daily', compact('orders', 'summary'));

        return $pdf->download('south-tiffins-report-' . $date . '.pdf');
    }

    public function yesterday()
    {
        if (!session('admin_id')) {
            return redirect()->route('login');
        }

        $date   = date('Y-m-d', strtotime('-1 day'));
        $orders = Order::with(['items', 'table'])
            ->whereDate('created_at', $date)
            ->orderBy('created_at')
            ->get();

        $summary = [
            'date'           => $date,
            'total_orders'   => $orders->count(),
            'total_revenue'  => $orders->where('payment_status', 'PAID')->sum('total_amount'),
            'cash_revenue'   => $orders->where('payment_method', 'CASH')->where('payment_status', 'PAID')->sum('total_amount'),
            'online_revenue' => $orders->where('payment_method', 'ONLINE')->where('payment_status', 'PAID')->sum('total_amount'),
            'dinein_orders'  => $orders->where('order_type', 'DINEIN')->count(),
            'parcel_orders'  => $orders->where('order_type', 'PARCEL')->count(),
            'walkin_orders'  => $orders->where('order_type', 'WALKIN')->count(),
        ];

        $pdf = Pdf::loadView('reports.daily', compact('orders', 'summary'));

        return $pdf->download('south-tiffins-report-yesterday.pdf');
    }

    public function monthly()
    {
        if (!session('admin_id')) {
            return redirect()->route('login');
        }

        $month  = request('month', date('m'));
        $year   = request('year', date('Y'));

        $orders = Order::with(['items', 'table'])
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->orderBy('created_at')
            ->get();

        $summary = [
            'month'          => date('F Y', mktime(0, 0, 0, $month, 1, $year)),
            'total_orders'   => $orders->count(),
            'total_revenue'  => $orders->where('payment_status', 'PAID')->sum('total_amount'),
            'cash_revenue'   => $orders->where('payment_method', 'CASH')->where('payment_status', 'PAID')->sum('total_amount'),
            'online_revenue' => $orders->where('payment_method', 'ONLINE')->where('payment_status', 'PAID')->sum('total_amount'),
            'dinein_orders'  => $orders->where('order_type', 'DINEIN')->count(),
            'parcel_orders'  => $orders->where('order_type', 'PARCEL')->count(),
            'walkin_orders'  => $orders->where('order_type', 'WALKIN')->count(),
        ];

        $pdf = Pdf::loadView('reports.monthly', compact('orders', 'summary'));

        return $pdf->download('south-tiffins-report-' . $month . '-' . $year . '.pdf');
    }

    public function downloadBill()
    {
        if (!session('admin_id') && !session('counter_access')) {
            return redirect()->route('login');
        }

        $order = Order::with(['items', 'table', 'bill'])
            ->find(request('order_id'));

        if (!$order) {
            abort(404);
        }

        $pdf = Pdf::loadView('reports.bill', compact('order'));

        return $pdf->download('bill-' . $order->bill->bill_number . '.pdf');
    }
}