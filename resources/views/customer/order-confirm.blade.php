@extends('layouts.app')

@section('title', 'Order Confirmed — South Tiffins')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/customer.css') }}">
<style>
    body { background: var(--bg); min-height: 100vh; }

    .confirm-wrap {
        max-width: 480px;
        margin: 0 auto;
        padding: 40px 16px;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .confirm-anim {
        width: 100px;
        height: 100px;
        background: var(--green-light);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 24px;
        font-size: 48px;
        animation: scaleIn 0.5s cubic-bezier(0.34,1.56,0.64,1) forwards;
    }

    .confirm-title {
        font-family: var(--font-display);
        font-weight: 900;
        font-style: italic;
        font-size: 32px;
        color: var(--dark);
        margin-bottom: 8px;
        letter-spacing: -0.02em;
    }

    .confirm-subtitle {
        font-size: 14px;
        color: var(--muted);
        margin-bottom: 32px;
        font-weight: 300;
    }

    .confirm-card {
        background: white;
        border-radius: var(--radius-xl);
        padding: 24px;
        width: 100%;
        border: 1.5px solid var(--border);
        box-shadow: var(--shadow-md);
        margin-bottom: 20px;
        text-align: left;
    }

    .confirm-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid var(--border);
    }

    .confirm-row:last-child { border-bottom: none; }

    .confirm-row-label {
        font-size: 13px;
        color: var(--muted);
        font-weight: 400;
    }

    .confirm-row-value {
        font-size: 14px;
        font-weight: 600;
        color: var(--dark);
    }

    .confirm-row-value.primary {
        color: var(--primary);
        font-family: var(--font-display);
        font-size: 16px;
    }

    .confirm-total {
        background: var(--primary-light);
        border-radius: var(--radius-lg);
        padding: 16px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
        margin-bottom: 24px;
    }

    .confirm-total-label  { font-size: 14px; font-weight: 600; color: var(--dark); }
    .confirm-total-amount {
        font-family: var(--font-display);
        font-weight: 900;
        font-style: italic;
        font-size: 28px;
        color: var(--primary);
    }

    .confirm-actions { display: flex; flex-direction: column; gap: 12px; width: 100%; }

    .confirm-items-title {
        font-size: 12px;
        font-weight: 600;
        color: var(--muted);
        letter-spacing: 0.06em;
        text-transform: uppercase;
        margin-bottom: 12px;
    }

    .confirm-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 0;
        border-bottom: 1px solid var(--border);
    }

    .confirm-item:last-child { border-bottom: none; }

    .confirm-item-img {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        background: var(--primary-light);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
        overflow: hidden;
    }

    .confirm-item-img img { width: 44px; height: 44px; border-radius: 10px; object-fit: cover; }
    .confirm-item-name  { flex: 1; font-size: 14px; font-weight: 500; color: var(--dark); }
    .confirm-item-qty   { font-size: 12px; color: var(--muted); }
    .confirm-item-price { font-size: 14px; font-weight: 600; color: var(--primary); }

    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: var(--radius-full);
        font-size: 12px;
        font-weight: 600;
    }

    .status-pill.paid   { background: var(--green-light); color: var(--green); }
    .status-pill.unpaid { background: rgba(255,107,53,0.1); color: var(--primary); }

    /* ── Stripe Payment Card ── */
    .stripe-card {
        background: white;
        border-radius: var(--radius-xl);
        padding: 24px;
        width: 100%;
        border: 1.5px solid var(--border);
        box-shadow: var(--shadow-md);
        margin-bottom: 20px;
        text-align: left;
    }

    .stripe-card-title {
        font-family: var(--font-display);
        font-weight: 700;
        font-size: 18px;
        color: var(--dark);
        margin-bottom: 4px;
    }

    .stripe-card-sub {
        font-size: 13px;
        color: var(--muted);
        margin-bottom: 20px;
    }

    #stripe-element {
        padding: 14px 16px;
        border: 1.5px solid var(--border);
        border-radius: var(--radius-md);
        background: white;
        transition: border-color 0.2s;
    }

    #stripe-element.StripeElement--focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(255,107,53,0.1);
    }

    #stripe-error {
        color: #C62828;
        font-size: 13px;
        margin-top: 10px;
        display: none;
        background: rgba(198,40,40,0.07);
        border: 1px solid rgba(198,40,40,0.2);
        padding: 10px 14px;
        border-radius: var(--radius-md);
    }

    .pay-btn {
        width: 100%;
        padding: 15px;
        background: #635BFF;
        color: white;
        border: none;
        border-radius: var(--radius-full);
        font-family: var(--font-body);
        font-weight: 700;
        font-size: 15px;
        cursor: pointer;
        transition: var(--transition);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        margin-top: 16px;
        box-shadow: 0 4px 16px rgba(99,91,255,0.3);
    }

    .pay-btn:hover   { background: #4F46E5; transform: translateY(-2px); }
    .pay-btn:disabled{ opacity: 0.6; cursor: not-allowed; transform: none; }

    .stripe-badge {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        margin-top: 12px;
        font-size: 11px;
        color: var(--muted);
    }

    .stripe-badge img { height: 18px; }

    .payment-success-banner {
        background: var(--green-light);
        border: 1.5px solid rgba(45,106,79,0.2);
        border-radius: var(--radius-lg);
        padding: 16px 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        width: 100%;
        margin-bottom: 20px;
    }

    .payment-success-icon { font-size: 24px; }

    .payment-success-text strong { display: block; font-size: 14px; font-weight: 700; color: var(--green); }
    .payment-success-text span   { font-size: 12px; color: var(--muted); }
</style>
@endsection

@section('content')

<div class="confirm-wrap">

    <div class="confirm-anim">✅</div>

    <h1 class="confirm-title">Order Placed!</h1>
    <p class="confirm-subtitle">Your order has been received. We are preparing it now.</p>

    <div class="confirm-card">
        <div class="confirm-row">
            <span class="confirm-row-label">Order Number</span>
            <span class="confirm-row-value primary">{{ $order->order_number }}</span>
        </div>
        @if($order->bill)
        <div class="confirm-row">
            <span class="confirm-row-label">Bill Number</span>
            <span class="confirm-row-value">{{ $order->bill->bill_number }}</span>
        </div>
        @endif
        <div class="confirm-row">
            <span class="confirm-row-label">Order Type</span>
            <span class="confirm-row-value">
                @if($order->order_type === 'DINEIN')  🪑 Dine In — Table {{ $order->table->table_number ?? '' }}
                @elseif($order->order_type === 'PARCEL') 📦 Parcel
                @else 🚶 Walk In
                @endif
            </span>
        </div>
        <div class="confirm-row">
            <span class="confirm-row-label">Payment</span>
            <span class="confirm-row-value">{{ $order->payment_method === 'CASH' ? 'Cash' : 'Online' }}</span>
        </div>
        <div class="confirm-row">
            <span class="confirm-row-label">Status</span>
            <span class="status-pill {{ $order->payment_status === 'PAID' ? 'paid' : 'unpaid' }}">
                {{ $order->payment_status === 'PAID' ? '✓ Paid' : 'Pay at Counter' }}
            </span>
        </div>
    </div>

    {{-- Items --}}
    <div class="confirm-card" style="text-align:left;">
        <div class="confirm-items-title">Items Ordered</div>
        @foreach($order->items as $item)
        <div class="confirm-item">
            <div class="confirm-item-img">
                @if($item->image_url)
                    <img src="{{ $item->image_url }}" alt="{{ $item->name_en }}">
                @else 🍽️
                @endif
            </div>
            <div class="confirm-item-name">{{ $item->name_en }}</div>
            <span class="confirm-item-qty">×{{ $item->quantity }}</span>
            <span class="confirm-item-price">₹{{ $item->subtotal }}</span>
        </div>
        @endforeach
    </div>

    <div class="confirm-total">
        <span class="confirm-total-label">Total Amount</span>
        <span class="confirm-total-amount">₹{{ $order->total_amount }}</span>
    </div>

    {{-- Stripe Payment Block (only for unpaid online orders) --}}
    @if($order->payment_method === 'ONLINE' && $order->payment_status !== 'PAID')
    <div class="stripe-card" id="stripePaymentCard">
        <div class="stripe-card-title">Pay Online</div>
        <div class="stripe-card-sub">Complete your payment securely via Stripe</div>
        <div id="stripe-element"></div>
        <div id="stripe-error"></div>
        <button class="pay-btn" id="payBtn" onclick="handleStripePayment()">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
            Pay ₹{{ $order->total_amount }}
        </button>
        <div class="stripe-badge">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#aaa" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            Secured by Stripe
        </div>
    </div>
    @endif

    {{-- Payment success banner (shown after payment) --}}
    <div class="payment-success-banner" id="paymentSuccessBanner" style="display:none;">
        <div class="payment-success-icon">✅</div>
        <div class="payment-success-text">
            <strong>Payment Successful!</strong>
            <span>Your payment has been confirmed.</span>
        </div>
    </div>

    <div class="confirm-actions">
        <a href="/menu/order-status?order_id={{ $order->id }}" class="btn-primary" style="justify-content:center;">
            🕐 Track Order
        </a>
        <a href="/menu?table={{ $order->table_id }}" class="btn-outline" style="justify-content:center;">
            Order More
        </a>
    </div>

</div>

@endsection

@section('scripts')
@if($order->payment_method === 'ONLINE' && $order->payment_status !== 'PAID')
<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripe  = Stripe('{{ env("STRIPE_KEY") }}');
    const elements = stripe.elements();

    const cardElement = elements.create('card', {
        style: {
            base: {
                fontFamily: "'Outfit', sans-serif",
                fontSize: '15px',
                color: '#1A1A1A',
                '::placeholder': { color: '#AAAAAA' }
            },
            invalid: { color: '#C62828' }
        }
    });

    cardElement.mount('#stripe-element');

    let clientSecret = null;

    async function initStripe() {
        try {
            const res  = await fetch('/api/payments/create-intent', {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    order_id: {{ $order->id }},
                    amount:   {{ $order->total_amount }}
                })
            });

            const data = await res.json();
            if (data.success) {
                clientSecret = data.client_secret;
            } else {
                showError(data.message || 'Failed to initialize payment');
            }
        } catch (err) {
            showError('Network error. Please try again.');
        }
    }

    async function handleStripePayment() {
        if (!clientSecret) {
            showError('Payment not initialized. Please refresh.');
            return;
        }

        const btn = document.getElementById('payBtn');
        btn.disabled    = true;
        btn.innerHTML   = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="animation:spin 1s linear infinite"><path d="M21 12a9 9 0 11-18 0 9 9 0 0118 0"/></svg> Processing...';

        const { paymentIntent, error } = await stripe.confirmCardPayment(clientSecret, {
            payment_method: { card: cardElement }
        });

        if (error) {
            showError(error.message);
            btn.disabled  = false;
            btn.innerHTML = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg> Pay ₹{{ $order->total_amount }}';
            return;
        }

        if (paymentIntent.status === 'succeeded') {
            try {
                const res  = await fetch('/api/payments/verify', {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        payment_intent_id: paymentIntent.id,
                        order_id: {{ $order->id }}
                    })
                });

                const data = await res.json();

                if (data.success) {
                    document.getElementById('stripePaymentCard').style.display = 'none';
                    document.getElementById('paymentSuccessBanner').style.display = 'flex';
                    document.querySelector('.status-pill').className = 'status-pill paid';
                    document.querySelector('.status-pill').textContent = '✓ Paid';
                } else {
                    showError(data.message || 'Verification failed');
                }
            } catch (err) {
                showError('Verification error. Contact support.');
            }
        }
    }

    function showError(msg) {
        const el = document.getElementById('stripe-error');
        el.textContent   = msg;
        el.style.display = 'block';
        setTimeout(() => { el.style.display = 'none'; }, 5000);
    }

    initStripe();
</script>
<style>
    @keyframes spin { to { transform: rotate(360deg); } }
</style>
@endif
@endsection