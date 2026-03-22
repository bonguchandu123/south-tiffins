@extends('layouts.admin')

@section('title', 'Billing — South Tiffins')
@section('page-title', 'Billing Panel')

@section('content')

<div style="display:flex;gap:12px;margin-bottom:24px;">
    <button class="btn-primary" onclick="openNewOrder('DINEIN')" style="padding:10px 20px;font-size:14px;">
        <i class="fas fa-chair"></i> New Dine In
    </button>
    <button class="btn-outline" onclick="openNewOrder('PARCEL')" style="padding:10px 20px;font-size:14px;">
        <i class="fas fa-box"></i> New Parcel
    </button>
    <button class="btn-dark" onclick="openNewOrder('WALKIN')" style="padding:10px 20px;font-size:14px;">
        <i class="fas fa-walking"></i> Walk In
    </button>
</div>

<div class="admin-grid-2">
    <div class="admin-panel">
        <div class="admin-panel-header">
            <div class="admin-panel-title">Active Orders</div>
            <button onclick="loadBilling()" style="background:none;border:none;color:var(--muted);cursor:pointer;">
                <i class="fas fa-sync-alt"></i>
            </button>
        </div>
        <div id="activeOrders" style="padding:16px;">
            <div style="text-align:center;color:var(--muted);">Loading...</div>
        </div>
    </div>

    <div class="admin-panel">
        <div class="admin-panel-header">
            <div class="admin-panel-title">Unpaid Bills</div>
        </div>
        <div id="unpaidBills" style="padding:16px;">
            <div style="text-align:center;color:var(--muted);">Loading...</div>
        </div>
    </div>
</div>

<div class="modal-overlay" id="newOrderModal">
    <div class="modal" style="max-width:560px;">
        <div class="modal-header">
            <div class="modal-title" id="newOrderTitle">New Order</div>
            <button class="modal-close" onclick="closeModal('newOrderModal')">✕</button>
        </div>
        <div class="modal-body">
            <input type="hidden" id="newOrderType">

            <div id="tableSelectWrap" class="form-group">
                <label class="form-label">Select Table</label>
                <select id="newOrderTable"></select>
            </div>

            <div id="customerWrap" style="display:none;">
                <div class="form-group">
                    <label class="form-label">Customer Name</label>
                    <input type="text" id="newCustomerName" placeholder="Customer name">
                </div>
                <div class="form-group">
                    <label class="form-label">Phone Number</label>
                    <input type="tel" id="newCustomerPhone" placeholder="10 digit phone" maxlength="10">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Payment Method</label>
                <div style="display:flex;gap:8px;">
                    <button class="order-type-btn active" id="payMethodCash" onclick="setPayMethod('CASH')" style="flex:1;padding:10px;border:1.5px solid var(--border);border-radius:var(--radius-md);background:var(--primary-light);border-color:var(--primary);color:var(--primary);font-family:var(--font-body);font-size:13px;font-weight:600;cursor:pointer;">
                        Cash
                    </button>
                    <button class="order-type-btn" id="payMethodOnline" onclick="setPayMethod('ONLINE')" style="flex:1;padding:10px;border:1.5px solid var(--border);border-radius:var(--radius-md);background:white;color:var(--muted);font-family:var(--font-body);font-size:13px;font-weight:500;cursor:pointer;">
                        Online
                    </button>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Select Items</label>
                <div id="menuItemsList" style="max-height:300px;overflow-y:auto;border:1.5px solid var(--border);border-radius:var(--radius-md);padding:8px;">
                    <div style="text-align:center;color:var(--muted);padding:20px;">Loading menu...</div>
                </div>
            </div>

            <div id="selectedItemsList" style="margin-top:12px;"></div>

            <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 0;border-top:1.5px solid var(--border);margin-top:12px;">
                <span style="font-weight:600;">Total</span>
                <span id="newOrderTotal" style="font-family:var(--font-display);font-weight:700;font-size:20px;color:var(--primary);">₹0</span>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-sm btn-sm-outline" onclick="closeModal('newOrderModal')">Cancel</button>
            <button class="btn-sm btn-sm-primary" onclick="submitNewOrder()">Place Order</button>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="{{ asset('js/billing.js') }}"></script>
@endsection