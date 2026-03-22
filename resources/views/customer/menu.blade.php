@extends('layouts.app')

@section('title', 'Menu — South Tiffins')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/customer.css') }}">
<style>
    /* ── Stripe Payment Sheet ── */
    .stripe-sheet-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.5);
        z-index: 500;
        display: none;
        align-items: flex-end;
        justify-content: center;
        backdrop-filter: blur(3px);
    }
    .stripe-sheet-overlay.open { display: flex; }

    .stripe-sheet {
        background: white;
        border-radius: 24px 24px 0 0;
        width: 100%;
        max-width: 560px;
        padding: 0 0 32px;
        animation: slideUp 0.3s cubic-bezier(0.34,1.56,0.64,1) forwards;
    }

    .stripe-sheet-handle {
        width: 40px; height: 4px;
        background: var(--border);
        border-radius: 2px;
        margin: 12px auto 0;
    }

    .stripe-sheet-head {
        padding: 20px 24px 16px;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .stripe-sheet-title {
        font-family: var(--font-display);
        font-weight: 700;
        font-size: 20px;
        color: var(--dark);
    }

    .stripe-sheet-close {
        width: 32px; height: 32px;
        background: var(--bg);
        border: 1px solid var(--border);
        border-radius: 50%;
        font-size: 13px;
        cursor: pointer;
        color: var(--muted);
        display: flex; align-items: center; justify-content: center;
        transition: var(--transition);
    }
    .stripe-sheet-close:hover { background: var(--primary-light); color: var(--primary); }

    .stripe-sheet-body { padding: 20px 24px; }

    .stripe-order-summary {
        background: var(--bg);
        border-radius: var(--radius-lg);
        padding: 14px 16px;
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .stripe-order-summary-label { font-size: 13px; color: var(--muted); }
    .stripe-order-summary-amount {
        font-family: var(--font-display);
        font-weight: 700;
        font-size: 22px;
        color: var(--primary);
    }

    .stripe-field-label {
        font-size: 12px;
        font-weight: 600;
        color: var(--muted);
        letter-spacing: 0.08em;
        text-transform: uppercase;
        margin-bottom: 8px;
        display: block;
    }

    #card-element {
        padding: 14px 16px;
        border: 1.5px solid var(--border);
        border-radius: var(--radius-md);
        background: white;
        transition: border-color 0.2s;
        margin-bottom: 8px;
    }

    #card-element.StripeElement--focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(255,107,53,0.1);
    }

    #card-error {
        color: #C62828;
        font-size: 13px;
        margin-bottom: 16px;
        display: none;
        background: rgba(198,40,40,0.07);
        border: 1px solid rgba(198,40,40,0.15);
        padding: 10px 14px;
        border-radius: var(--radius-md);
    }

    .stripe-pay-btn {
        width: 100%;
        height: 52px;
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
        box-shadow: 0 4px 16px rgba(99,91,255,0.3);
        margin-top: 16px;
    }
    .stripe-pay-btn:hover   { background: #4F46E5; transform: translateY(-2px); }
    .stripe-pay-btn:disabled{ opacity: 0.6; cursor: not-allowed; transform: none; }

    .stripe-secure {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        margin-top: 12px;
        font-size: 11px;
        color: var(--muted);
    }

    .test-card-hint {
        background: rgba(99,91,255,0.06);
        border: 1px solid rgba(99,91,255,0.15);
        border-radius: var(--radius-md);
        padding: 10px 14px;
        font-size: 12px;
        color: #4F46E5;
        margin-bottom: 16px;
    }
    .test-card-hint strong { display: block; margin-bottom: 2px; }

    @keyframes spin { to { transform: rotate(360deg); } }
</style>
@endsection

@section('content')

<div class="menu-wrap">

    <header class="menu-header">
        <div class="menu-header-inner">
            <div class="menu-logo-wrap">
                <img src="{{ asset('images/logo.png') }}" alt="South Tiffins" class="menu-logo">
            </div>
            <div class="menu-header-info">
                <div class="menu-parlour-name">South Tiffins</div>
                @if($table)
                    <div class="menu-table-badge"><i class="fas fa-chair"></i> Table {{ $table->table_number }}</div>
                @else
                    <div class="menu-table-badge parcel-badge"><i class="fas fa-box"></i> Parcel Order</div>
                @endif
            </div>
            <button class="lang-toggle" onclick="toggleLanguage()">
                <span id="langLabel">తె</span>
            </button>
        </div>
        <div class="category-tabs" id="categoryTabs"></div>
    </header>

    <div class="menu-layout">
        <aside class="menu-sidebar" id="menuSidebar">
            <span class="sidebar-label">Categories</span>
            <div id="sidebarTabs"></div>
        </aside>
        <div class="menu-body" id="menuBody">
            <div class="skeleton-grid">
                <div class="skeleton" style="height:220px;border-radius:16px;"></div>
                <div class="skeleton" style="height:220px;border-radius:16px;"></div>
                <div class="skeleton" style="height:220px;border-radius:16px;"></div>
                <div class="skeleton" style="height:220px;border-radius:16px;"></div>
                <div class="skeleton" style="height:220px;border-radius:16px;"></div>
                <div class="skeleton" style="height:220px;border-radius:16px;"></div>
                <div class="skeleton" style="height:220px;border-radius:16px;"></div>
                <div class="skeleton" style="height:220px;border-radius:16px;"></div>
            </div>
        </div>
    </div>

    <div class="cart-bar" id="cartBar" style="display:none;" onclick="openCart()">
        <div class="cart-bar-left">
            <div class="cart-count-badge" id="cartCountBadge">0</div>
            <div>
                <div class="cart-items-label" id="cartItemsLabel">items</div>
                <div class="cart-total" id="cartTotal">₹0</div>
            </div>
        </div>
        <div class="cart-btn"><i class="fas fa-shopping-bag"></i> View Cart</div>
    </div>

</div>

{{-- Item Detail Sheet --}}
<div class="sheet-overlay" id="itemDetailOverlay" onclick="closeItemDetail(event)">
    <div class="bottom-sheet">
        <div class="sheet-handle"></div>
        <div class="item-detail-img-wrap" id="itemDetailImgWrap"></div>
        <div class="item-detail-body">
            <div class="item-detail-veg"   id="itemDetailVeg"></div>
            <div class="item-detail-name"  id="itemDetailName"></div>
            <div class="item-detail-price" id="itemDetailPrice"></div>
            <div class="item-detail-desc"  id="itemDetailDesc"></div>
            <div class="item-detail-actions">
                <div class="qty-control">
                    <button onclick="changeDetailQty(-1)">−</button>
                    <span id="detailQtyNum">1</span>
                    <button onclick="changeDetailQty(1)">+</button>
                </div>
                <button class="add-to-cart-btn" onclick="addFromDetail()">
                    Add — <span id="detailAddPrice">₹0</span>
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Cart Sheet --}}
<div class="sheet-overlay" id="cartOverlay" onclick="closeCart(event)">
    <div class="bottom-sheet cart-sheet">
        <div class="sheet-handle"></div>
        <div class="cart-sheet-header">
            <div class="cart-sheet-title">Your Order</div>
            <button class="cart-close" onclick="document.getElementById('cartOverlay').classList.remove('open');document.body.style.overflow='';">✕</button>
        </div>
        <div class="cart-items" id="cartItems"></div>
        <div class="cart-sheet-footer">
            <div class="order-type-selector">
                @if($table)
                <button class="order-type-btn active" data-type="DINEIN" onclick="selectOrderType('DINEIN',this)">
                    <i class="fas fa-chair"></i> Dine In
                </button>
                @endif
                <button class="order-type-btn {{ !$table ? 'active' : '' }}" data-type="PARCEL" onclick="selectOrderType('PARCEL',this)">
                    <i class="fas fa-box"></i> Parcel
                </button>
            </div>
            <div class="customer-details" id="customerDetails" style="{{ $table ? 'display:none;' : 'display:flex;' }}">
                <input type="text" id="customerName"  placeholder="Your name">
                <input type="tel"  id="customerPhone" placeholder="Phone number" maxlength="10" inputmode="numeric">
            </div>
            <div class="payment-section">
                <div class="payment-label">Payment Method</div>
                <div class="payment-options">
                    <button class="payment-btn active" data-method="CASH" onclick="selectPayment('CASH',this)">
                        <i class="fas fa-money-bill-wave"></i> Cash
                    </button>
                    <button class="payment-btn" data-method="ONLINE" onclick="selectPayment('ONLINE',this)">
                        <i class="fas fa-credit-card"></i> Online
                    </button>
                </div>
            </div>
            <div class="cart-total-row">
                <span>Total</span>
                <span id="cartTotalFinal">₹0</span>
            </div>
            <button class="place-order-btn" id="placeOrderBtn" onclick="placeOrder()">
                <i class="fas fa-check-circle"></i> Place Order
            </button>
        </div>
    </div>
</div>

{{-- Stripe Payment Sheet --}}
<div class="stripe-sheet-overlay" id="stripeOverlay">
    <div class="stripe-sheet">
        <div class="stripe-sheet-handle"></div>
        <div class="stripe-sheet-head">
            <div class="stripe-sheet-title">Complete Payment</div>
            <button class="stripe-sheet-close" onclick="closeStripeSheet()">✕</button>
        </div>
        <div class="stripe-sheet-body">
            <div class="stripe-order-summary">
                <div>
                    <div class="stripe-order-summary-label">Amount to Pay</div>
                </div>
                <div class="stripe-order-summary-amount" id="stripeAmount">₹0</div>
            </div>

            <div class="test-card-hint">
                <strong>🧪 Test Mode</strong>
                Use card: 4242 4242 4242 4242 — any future date — any CVC
            </div>

            <span class="stripe-field-label">Card Details</span>
            <div id="card-element"></div>
            <div id="card-error"></div>

            <button class="stripe-pay-btn" id="stripePayBtn" onclick="confirmStripePayment()">
                <i class="fas fa-lock"></i>
                <span id="stripePayBtnText">Pay Now</span>
            </button>

            <div class="stripe-secure">
                <i class="fas fa-shield-alt" style="font-size:11px;"></i>
                Secured by Stripe — your card details are never stored
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="tableId"     value="{{ $table ? $table->id : '' }}">
<input type="hidden" id="tableNumber" value="{{ $table ? $table->table_number : '' }}">
<input type="hidden" id="stripePublishableKey" value="{{ env('STRIPE_KEY') }}">

@endsection

@section('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script src="{{ asset('js/customer.js') }}"></script>
@endsection