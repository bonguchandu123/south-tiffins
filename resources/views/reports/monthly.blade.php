<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #1A1A1A; margin: 0; padding: 20px; }
        .header { text-align: center; border-bottom: 2px solid #FF6B35; padding-bottom: 16px; margin-bottom: 20px; }
        .header h1 { font-size: 22px; color: #FF6B35; margin: 0 0 4px; }
        .header p { color: #666; margin: 0; font-size: 11px; }
        .summary-grid { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .summary-grid td { padding: 8px 12px; border: 1px solid #EEEEEE; font-size: 12px; }
        .summary-grid .label { color: #666; width: 50%; }
        .summary-grid .value { font-weight: bold; }
        .summary-grid .value.primary { color: #FF6B35; }
        .orders-table { width: 100%; border-collapse: collapse; }
        .orders-table th { background: #FF6B35; color: white; padding: 8px 10px; text-align: left; font-size: 11px; }
        .orders-table td { padding: 8px 10px; border-bottom: 1px solid #EEEEEE; font-size: 11px; }
        .orders-table tr:nth-child(even) td { background: #FAFAFA; }
        .section-title { font-size: 14px; font-weight: bold; margin: 16px 0 8px; padding-bottom: 4px; border-bottom: 1px solid #EEEEEE; }
        .footer { margin-top: 24px; text-align: center; color: #999; font-size: 10px; border-top: 1px solid #EEEEEE; padding-top: 12px; }
    </style>
</head>
<body>

<div class="header">
    <h1>South Tiffins</h1>
    <p>Monthly Sales Report — {{ $summary['month'] }}</p>
</div>

<div class="section-title">Summary</div>

<table class="summary-grid">
    <tr>
        <td class="label">Total Orders</td>
        <td class="value">{{ $summary['total_orders'] }}</td>
        <td class="label">Total Revenue</td>
        <td class="value primary">₹{{ number_format($summary['total_revenue'], 2) }}</td>
    </tr>
    <tr>
        <td class="label">Cash Revenue</td>
        <td class="value">₹{{ number_format($summary['cash_revenue'], 2) }}</td>
        <td class="label">Online Revenue</td>
        <td class="value">₹{{ number_format($summary['online_revenue'], 2) }}</td>
    </tr>
    <tr>
        <td class="label">Dine In</td>
        <td class="value">{{ $summary['dinein_orders'] }}</td>
        <td class="label">Parcel</td>
        <td class="value">{{ $summary['parcel_orders'] }}</td>
    </tr>
</table>

<div class="section-title">All Orders</div>

<table class="orders-table">
    <thead>
        <tr>
            <th>Date</th>
            <th>Order No</th>
            <th>Type</th>
            <th>Amount</th>
            <th>Payment</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @forelse($orders as $order)
        <tr>
            <td>{{ $order->created_at->format('d M') }}</td>
            <td>{{ $order->order_number }}</td>
            <td>{{ $order->order_type }}</td>
            <td>₹{{ number_format($order->total_amount, 2) }}</td>
            <td>{{ $order->payment_method }} / {{ $order->payment_status }}</td>
            <td>{{ $order->status }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="6" style="text-align:center;color:#999;">No orders</td>
        </tr>
        @endforelse
    </tbody>
</table>

<div class="footer">South Tiffins — Generated on {{ date('d M Y h:i A') }}</div>

</body>
</html>