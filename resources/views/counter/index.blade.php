@extends('layouts.app')

@section('title', 'Counter — South Tiffins')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/counter.css') }}">
@endsection

@section('content')

<div class="counter-wrap">

    <header class="counter-header">
        <div class="counter-header-left">
            <div class="counter-logo-wrap">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="counter-logo">
            </div>
            <div>
                <div class="counter-title">South Tiffins</div>
                <div class="counter-subtitle">Counter View</div>
            </div>
        </div>
        <div class="counter-header-right">
            <div class="live-indicator">
                <div class="live-dot"></div>
                <span>Live</span>
            </div>
            <div class="pending-badge">
                <i class="fas fa-fire"></i>
                <span id="pendingNum">0</span> Pending
            </div>
            <div class="counter-time" id="counterTime"></div>
            <a href="{{ route('counter.logout') }}" class="counter-logout" title="Logout">
                <i class="fas fa-sign-out-alt"></i>
            </a>
        </div>
    </header>

    <div class="counter-body">

        <div class="order-section">
            <div class="section-head">
                <div class="section-head-title">
                    <div class="title-icon">🔔</div>
                    Active Orders
                </div>
                <span class="section-count" id="activeCount">0</span>
            </div>
            <div class="orders-list" id="activeOrders">
                <div class="empty-state" id="activeEmpty">
                    <div class="empty-icon">🍽️</div>
                    <div class="empty-text">No active orders right now</div>
                </div>
            </div>
        </div>

        <div class="order-section">
            <div class="section-head">
                <div class="section-head-title">
                    <div class="title-icon">📦</div>
                    Ready for Pickup
                </div>
                <span class="section-count" id="readyCount">0</span>
            </div>
            <div class="orders-list" id="readyOrders">
                <div class="empty-state" id="readyEmpty">
                    <div class="empty-icon">📦</div>
                    <div class="empty-text">No orders ready yet</div>
                </div>
            </div>
        </div>

    </div>

</div>

{{-- ORDER DETAIL MODAL --}}
<div class="detail-overlay" id="detailOverlay">
    <div class="detail-modal" id="detailModal">
        <div class="detail-modal-inner" id="detailModalInner"></div>
    </div>
</div>

<div class="new-order-alert" id="newOrderAlert">
    <div class="alert-icon">🔔</div>
    <span>New Order Received!</span>
</div>

<audio id="buzzer" preload="auto">
    <source src="{{ asset('sounds/buzzer.mp3') }}" type="audio/mpeg">
</audio>

@endsection

@section('scripts')
<script src="{{ asset('js/counter.js') }}"></script>
@endsection