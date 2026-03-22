<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #1A1A1A; margin: 0; padding: 20px; max-width: 300px; }
        .header { text-align: center; margin-bottom: 16px; border-bottom: 2px solid #FF6B35; padding-bottom: 12px; }
        .header h1 { font-size: 18px; color: #FF6B35; margin: 0 0 4px; }
        .header p { color: #666; margin: 0; font-size: 10px; }
        .bill-info { margin-bottom: 12px; font-size: 11px; }
        .bill-info table { width: 100%; }
        .bill-info td { padding: 3px 0; }
        .bill-info .label { color: #666; }
        .bill-info .value { font-weight: bold; text-align: right; }
        .items-table { width: 100%; border-collapse: collapse; margin: 12px 0; }
        .items-table th { border-bottom: 1px solid #EEEEEE; padding: 6px 4px; text-align: left; font-size: 10px; color: #666; }
        .items-table td { padding: 6px 4px; border-bottom: 1px solid #FAFAFA; font-size: 11px; }
        .total-row { border-top: 2px solid #FF6B35; padding-top: 10px; margin-top: 4px; }
        .total-label { font-size: 13px; font-weight: bold; }
        .total-amount { font-size: 18px; font-weight: bold; color: #FF6B35; text-align: right; }
        .footer { text-align: center; margin-top: 16px; color: #999; font-size: 10px; border-top: 1px solid #EEEEEE; padding-top: 10px; }
        .paid-stamp { text-align: center; margin: 12px 0; }
        .paid-stamp span { border: 2px solid #2D6A4F; color: #2D6A4F; font-weight: bold; font-size: 14px; padding: 4px 16px; border-radius: 4px; }
    </style>
</head>
<body>

<div class="header">
    <h1>South Tiffins</h1>
    <p>{{ date('d M Y, h:i A', strtotime($order->created_at)) }}</p>
</div>

<div class="bill-info">
    <table>
        <tr>
            <td class="label">Bill No</td>
            <td class="value">{{ $order->bill->bill_number ?? '—' }}</td>
        </tr>
        <tr>
            <td class="label">Order No</td>
            <td class="value">{{ $order->order_number }}</td>
        </tr>
        <tr>
            <td class="label">Type</td>
            <td class="value">
                @if($order->order_type === 'DINEIN')
                    Dine In — Table {{ $order->table->table_number ?? '' }}
                @elseif($order->order_type === 'PARCEL')
                    Parcel — {{ $order->customer_name }}
                @else
                    Walk In
                @endif
            </td>
        </tr>
        <tr>
            <td class="label">Payment</td>
            <td class="value">{{ $order->payment_method }}</td>
        </tr>
    </table>
</div>

<table class="items-table">
    <thead>
        <tr>
            <th>Item</th>
            <th style="text-align:center;">Qty</th>
            <th style="text-align:right;">Amount</th>
        </tr>
    </thead>
    <tbody>
        @foreach($order->items as $item)
        <tr>
            <td>{{ $item->name_en }}</td>
            <td style="text-align:center;">{{ $item->quantity }}</td>
            <td style="text-align:right;">₹{{ number_format($item->subtotal, 2) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<table class="total-row" style="width:100%;">
    <tr>
        <td class="total-label">Total</td>
        <td class="total-amount">₹{{ number_format($order->total_amount, 2) }}</td>
    </tr>
</table>

@if($order->payment_status === 'PAID')
<div class="paid-stamp">
    <span>✓ PAID</span>
</div>
@endif

<div class="footer">
    Thank you for visiting South Tiffins!<br>
    Come again 🙏
</div>

</body>
</html>