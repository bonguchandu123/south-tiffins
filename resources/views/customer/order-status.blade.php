@extends('layouts.app')

@section('title', 'Order Status — South Tiffins')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/customer.css') }}">
<style>
    body {
        background: var(--bg);
        min-height: 100vh;
    }

    .status-wrap {
        max-width: 480px;
        margin: 0 auto;
        padding: 32px 16px 40px;
    }

    .status-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 32px;
    }

    .status-header-logo {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        object-fit: contain;
    }

    .status-header-info {}

    .status-header-title {
        font-family: var(--font-display);
        font-weight: 700;
        font-style: italic;
        font-size: 18px;
        color: var(--primary);
    }

    .status-header-sub {
        font-size: 12px;
        color: var(--muted);
    }

    .status-card {
        background: white;
        border-radius: var(--radius-xl);
        padding: 24px;
        border: 1.5px solid var(--border);
        box-shadow: var(--shadow-md);
        margin-bottom: 20px;
    }

    .status-order-num {
        font-size: 12px;
        color: var(--muted);
        font-weight: 300;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        margin-bottom: 4px;
    }

    .status-order-id {
        font-family: var(--font-display);
        font-weight: 700;
        font-size: 22px;
        color: var(--primary);
        margin-bottom: 20px;
    }

    .status-steps {
        display: flex;
        flex-direction: column;
        gap: 0;
    }

    .status-step {
        display: flex;
        gap: 16px;
        align-items: flex-start;
        position: relative;
    }

    .status-step:not(:last-child)::before {
        content: '';
        position: absolute;
        left: 16px;
        top: 36px;
        width: 2px;
        height: calc(100% - 8px);
        background: var(--border);
    }

    .status-step.done::before {
        background: var(--primary);
    }

    .status-step-icon {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        border: 2px solid var(--border);
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        flex-shrink: 0;
        z-index: 1;
        transition: all 0.3s ease;
    }

    .status-step.done .status-step-icon {
        background: var(--primary);
        border-color: var(--primary);
        color: white;
    }

    .status-step.active .status-step-icon {
        border-color: var(--primary);
        color: var(--primary);
        animation: pulse 1.5s ease infinite;
    }

    .status-step-info {
        padding: 6px 0 24px;
    }

    .status-step-title {
        font-size: 14px;
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 2px;
    }

    .status-step.done .status-step-title {
        color: var(--primary);
    }

    .status-step-desc {
        font-size: 12px;
        color: var(--muted);
    }

    .refresh-note {
        text-align: center;
        font-size: 12px;
        color: var(--muted);
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .refresh-dot {
        width: 6px;
        height: 6px;
        background: var(--green);
        border-radius: 50%;
        animation: pulse 1.5s ease infinite;
    }
</style>
@endsection

@section('content')

<div class="status-wrap">

    <div class="status-header">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="status-header-logo">
        <div class="status-header-info">
            <div class="status-header-title">South Tiffins</div>
            <div class="status-header-sub">Order Tracking</div>
        </div>
    </div>

    <div class="refresh-note">
        <div class="refresh-dot"></div>
        Auto refreshes every 10 seconds
    </div>

    <div class="status-card" id="statusCard">
        <div class="status-order-num">Order Number</div>
        <div class="status-order-id">{{ $order->order_number }}</div>

        <div class="status-steps" id="statusSteps">
            @php
                $status = $order->status;
                $type   = $order->order_type;

                $dineSteps = [
                    ['key' => 'PENDING',   'icon' => '🕐', 'title' => 'Order Received',  'desc' => 'Your order has been placed'],
                    ['key' => 'PREPARING', 'icon' => '👨‍🍳', 'title' => 'Preparing',       'desc' => 'Kitchen is preparing your food'],
                    ['key' => 'SERVED',    'icon' => '✓',  'title' => 'Served',           'desc' => 'Enjoy your meal!']
                ];

                $parcelSteps = [
                    ['key' => 'PENDING',   'icon' => '🕐', 'title' => 'Order Received',   'desc' => 'Your order has been placed'],
                    ['key' => 'PREPARING', 'icon' => '👨‍🍳', 'title' => 'Preparing',        'desc' => 'Kitchen is preparing your order'],
                    ['key' => 'READY',     'icon' => '📦', 'title' => 'Ready for Pickup', 'desc' => 'Come collect your parcel'],
                    ['key' => 'PICKEDUP',  'icon' => '✓',  'title' => 'Picked Up',        'desc' => 'Enjoy your food!']
                ];

                $statusOrder = ['PENDING', 'PREPARING', 'READY', 'SERVED', 'PICKEDUP'];
                $currentIdx  = array_search($status, $statusOrder);
                $steps       = $type === 'PARCEL' ? $parcelSteps : $dineSteps;
            @endphp

            @foreach($steps as $step)
                @php
                    $stepIdx  = array_search($step['key'], $statusOrder);
                    $isDone   = $stepIdx < $currentIdx || $status === $step['key'] && in_array($status, ['SERVED', 'PICKEDUP']);
                    $isActive = $status === $step['key'] && !in_array($status, ['SERVED', 'PICKEDUP']);
                    $class    = $isDone ? 'done' : ($isActive ? 'active' : '');
                @endphp
                <div class="status-step {{ $class }}">
                    <div class="status-step-icon">
                        @if($isDone) ✓ @else {{ $step['icon'] }} @endif
                    </div>
                    <div class="status-step-info">
                        <div class="status-step-title">{{ $step['title'] }}</div>
                        <div class="status-step-desc">{{ $step['desc'] }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="status-card">
        <div class="confirm-items-title" style="font-size:12px;font-weight:600;color:var(--muted);letter-spacing:0.06em;text-transform:uppercase;margin-bottom:12px;">
            Items
        </div>
        @foreach($order->items as $item)
        <div class="confirm-item" style="display:flex;align-items:center;gap:12px;padding:10px 0;border-bottom:1px solid var(--border);">
            <div class="confirm-item-img" style="width:40px;height:40px;border-radius:8px;background:var(--primary-light);display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0;">
                @if($item->image_url)
                    <img src="{{ $item->image_url }}" style="width:40px;height:40px;border-radius:8px;object-fit:cover;">
                @else
                    🍽️
                @endif
            </div>
            <div style="flex:1;font-size:14px;font-weight:500;color:var(--dark);">{{ $item->name_en }}</div>
            <span style="font-size:12px;color:var(--muted);">×{{ $item->quantity }}</span>
            <span style="font-size:14px;font-weight:600;color:var(--primary);">₹{{ $item->subtotal }}</span>
        </div>
        @endforeach
    </div>

    <a href="/menu?table={{ $order->table_id }}" class="btn-outline" style="width:100%;justify-content:center;margin-top:8px;">
        Order More
    </a>

</div>

@endsection

@section('scripts')
<script>
    const orderId = {{ $order->id }};

    async function refreshStatus() {
        try {
            const res  = await fetch('/api/orders/get?id=' + orderId);
            const data = await res.json();

            if (!data.success) return;

            const status = data.order.status;

            if (['SERVED', 'PICKEDUP', 'CANCELLED'].includes(status)) {
                clearInterval(refreshInterval);
            }

            if (status !== '{{ $order->status }}') {
                window.location.reload();
            }

        } catch (err) {
            console.error('Refresh error:', err);
        }
    }

    const refreshInterval = setInterval(refreshStatus, 10000);
</script>
@endsection
