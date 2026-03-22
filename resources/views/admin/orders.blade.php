@extends('layouts.admin')

@section('title', 'Orders — South Tiffins')
@section('page-title', 'All Orders')

@section('styles')
<style>
    .bill-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.5);
        z-index: 1000;
        display: none;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(3px);
        padding: 24px;
    }

    .bill-overlay.open { display: flex; }

    .bill-modal {
        background: white;
        border-radius: 20px;
        width: 100%;
        max-width: 480px;
        max-height: 90vh;
        overflow-y: auto;
        animation: billIn 0.3s cubic-bezier(0.34,1.56,0.64,1) forwards;
        box-shadow: 0 20px 60px rgba(0,0,0,0.2);
    }

    @keyframes billIn {
        from { opacity:0; transform: scale(0.94) translateY(12px); }
        to   { opacity:1; transform: scale(1) translateY(0); }
    }

    .bill-modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 18px 24px;
        border-bottom: 1px solid var(--border);
        position: sticky;
        top: 0;
        background: white;
        z-index: 1;
        border-radius: 20px 20px 0 0;
    }

    .bill-modal-title {
        font-family: var(--font-display);
        font-weight: 700;
        font-size: 18px;
        color: var(--dark);
    }

    .bill-modal-actions {
        display: flex;
        gap: 8px;
        align-items: center;
    }

    .bill-close-btn {
        width: 32px;
        height: 32px;
        background: var(--bg);
        border: 1px solid var(--border);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: var(--muted);
        font-size: 13px;
        transition: var(--transition);
    }

    .bill-close-btn:hover {
        background: var(--primary-light);
        color: var(--primary);
        border-color: var(--primary);
    }

    /* ── BILL RECEIPT PREVIEW ── */
    .bill-preview {
        padding: 28px 32px;
        font-family: var(--font-body);
    }

    .bill-preview-head {
        text-align: center;
        margin-bottom: 20px;
        padding-bottom: 16px;
        border-bottom: 1px dashed var(--border);
    }

    .bill-preview-parlour {
        font-family: var(--font-display);
        font-weight: 900;
        font-style: italic;
        font-size: 24px;
        color: var(--primary);
        margin-bottom: 4px;
    }

    .bill-preview-datetime {
        font-size: 12px;
        color: var(--muted);
    }

    .bill-preview-meta {
        margin-bottom: 16px;
    }

    .bill-preview-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 6px 0;
        font-size: 13px;
        border-bottom: 1px solid var(--bg);
    }

    .bill-preview-row:last-child { border-bottom: none; }

    .bill-preview-label { color: var(--muted); }
    .bill-preview-val   { font-weight: 600; color: var(--dark); }
    .bill-preview-val.orange { color: var(--primary); font-weight: 700; }

    .bill-items-head {
        display: grid;
        grid-template-columns: 1fr 40px 70px;
        gap: 8px;
        padding: 8px 0;
        border-top: 1px solid var(--border);
        border-bottom: 1px solid var(--border);
        font-size: 11px;
        font-weight: 700;
        color: var(--muted);
        letter-spacing: 0.08em;
        text-transform: uppercase;
        margin-bottom: 4px;
    }

    .bill-items-head span:not(:first-child),
    .bill-item-row span:not(:first-child) { text-align: right; }

    .bill-item-row {
        display: grid;
        grid-template-columns: 1fr 40px 70px;
        gap: 8px;
        padding: 7px 0;
        font-size: 13px;
        color: var(--dark);
        border-bottom: 1px solid var(--bg);
    }

    .bill-item-row:last-child { border-bottom: none; }

    .bill-preview-total {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 14px 0 0;
        border-top: 2px solid var(--border);
        margin-top: 8px;
    }

    .bill-total-lbl {
        font-family: var(--font-display);
        font-weight: 700;
        font-size: 18px;
        color: var(--dark);
    }

    .bill-total-val {
        font-family: var(--font-display);
        font-weight: 900;
        font-size: 28px;
        color: var(--primary);
    }

    .bill-preview-footer {
        text-align: center;
        padding-top: 16px;
        margin-top: 16px;
        border-top: 1px dashed var(--border);
        font-size: 12px;
        color: var(--muted);
        line-height: 1.7;
    }

    .bill-download-bar {
        padding: 16px 24px 20px;
        border-top: 1px solid var(--border);
        display: flex;
        gap: 10px;
    }

    .bill-download-bar .btn-primary {
        flex: 1;
        justify-content: center;
        padding: 12px;
        font-size: 14px;
    }

    .bill-download-bar .btn-outline {
        padding: 12px 20px;
        font-size: 14px;
    }

    @media (max-width: 600px) {
        .bill-overlay { padding: 0; align-items: flex-end; }
        .bill-modal   { border-radius: 20px 20px 0 0; max-height: 95vh; max-width: 100%; }
    }
</style>
@endsection

@section('content')

<div style="display:flex;gap:8px;margin-bottom:24px;flex-wrap:wrap;">
    <button class="btn-sm btn-sm-primary"  onclick="filterOrders('ALL')"    id="filterAll">All</button>
    <button class="btn-sm btn-sm-outline"  onclick="filterOrders('DINEIN')" id="filterDINEIN">Dine In</button>
    <button class="btn-sm btn-sm-outline"  onclick="filterOrders('PARCEL')" id="filterPARCEL">Parcel</button>
    <button class="btn-sm btn-sm-outline"  onclick="filterOrders('WALKIN')" id="filterWALKIN">Walk In</button>
</div>

<div class="admin-panel">
    <div class="admin-panel-body" style="padding:0;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Type</th>
                    <th>Table / Customer</th>
                    <th>Items</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Payment</th>
                    <th>Time</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="ordersBody">
                <tr>
                    <td colspan="9" style="text-align:center;color:var(--muted);padding:20px;">Loading...</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

{{-- BILL PREVIEW MODAL --}}
<div class="bill-overlay" id="billOverlay">
    <div class="bill-modal" id="billModal">
        <div class="bill-modal-header">
            <div class="bill-modal-title">Bill Preview</div>
            <div class="bill-modal-actions">
                <button class="bill-close-btn" onclick="closeBill()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <div id="billPreviewContent"></div>
        <div class="bill-download-bar">
            <a id="billDownloadBtn" href="#" target="_blank" class="btn-primary">
                <i class="fas fa-download"></i> Download PDF
            </a>
            <button class="btn-outline" onclick="closeBill()">Close</button>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
let allOrders     = [];
let currentFilter = 'ALL';

async function loadOrders() {
    try {
        const res  = await fetch('/api/orders/today', { credentials: 'same-origin' });
        const data = await res.json();
        if (!data.success) return;
        allOrders = data.orders;
        renderOrders(allOrders);
    } catch (err) { console.error(err); }
}

function filterOrders(type) {
    currentFilter = type;
    document.querySelectorAll('[id^="filter"]').forEach(btn => {
        btn.className = 'btn-sm btn-sm-outline';
    });
    document.getElementById('filter' + type).className = 'btn-sm btn-sm-primary';
    const filtered = type === 'ALL' ? allOrders : allOrders.filter(o => o.order_type === type);
    renderOrders(filtered);
}

function renderOrders(orders) {
    const tbody = document.getElementById('ordersBody');

    if (!orders || orders.length === 0) {
        tbody.innerHTML = '<tr><td colspan="9" style="text-align:center;color:var(--muted);padding:20px;">No orders found</td></tr>';
        return;
    }

    const statusMap = {
        PENDING:   '<span class="badge-pending">Pending</span>',
        PREPARING: '<span class="badge-preparing">Preparing</span>',
        READY:     '<span class="badge-ready">Ready</span>',
        SERVED:    '<span class="badge-served">Served</span>',
        PICKEDUP:  '<span class="badge-served">Picked Up</span>',
        CANCELLED: '<span style="color:var(--muted);font-size:11px;">Cancelled</span>'
    };

    tbody.innerHTML = orders.map(order => {
        const typeIcon = order.order_type === 'DINEIN' ? '🪑'
                       : order.order_type === 'PARCEL' ? '📦' : '🚶';

        const title = order.order_type === 'DINEIN'
            ? 'Table ' + (order.table?.table_number || '—')
            : (order.customer_name || 'Walk In');

        const paidBadge = order.payment_status === 'PAID'
            ? '<span class="badge-paid">Paid</span>'
            : '<span class="badge-unpaid">Unpaid</span>';

        const time = new Date(order.created_at).toLocaleTimeString('en-IN', {
            hour: '2-digit', minute: '2-digit'
        });

        return `
            <tr>
                <td>
                    <div style="font-weight:600;font-size:13px;">${order.order_number}</div>
                    ${order.bill ? `<div style="font-size:11px;color:var(--muted);">${order.bill.bill_number}</div>` : ''}
                </td>
                <td>${typeIcon} ${order.order_type}</td>
                <td>${title}</td>
                <td>${order.items?.length || 0} items</td>
                <td style="font-weight:700;color:var(--primary);">₹${order.total_amount}</td>
                <td>${statusMap[order.status] || order.status}</td>
                <td>
                    <div style="font-size:12px;">${order.payment_method}</div>
                    <div style="margin-top:2px;">${paidBadge}</div>
                </td>
                <td style="font-size:12px;color:var(--muted);">${time}</td>
                <td>
                    <div style="display:flex;gap:6px;flex-wrap:wrap;">
                        ${order.payment_status === 'UNPAID' && order.payment_method === 'CASH'
                            ? `<button class="btn-sm btn-sm-primary" onclick="markPaid(${order.id}, this)">Paid</button>`
                            : ''
                        }
                        <button class="btn-sm btn-sm-outline" onclick="previewBill(${order.id})">
                            <i class="fas fa-receipt"></i> Bill
                        </button>
                    </div>
                </td>
            </tr>
        `;
    }).join('');
}

// ── BILL PREVIEW ──
function previewBill(orderId) {
    const order = allOrders.find(o => o.id === orderId);
    if (!order) return;

    const now      = new Date(order.created_at);
    const dateStr  = now.toLocaleDateString('en-IN', { day: '2-digit', month: 'short', year: 'numeric' })
                   + ', ' + now.toLocaleTimeString('en-IN', { hour: '2-digit', minute: '2-digit' });

    const typeDetail = order.order_type === 'DINEIN'
        ? 'Dine In — Table ' + (order.table?.table_number || '—')
        : order.order_type === 'PARCEL'
        ? 'Parcel — ' + (order.customer_name || '') + (order.customer_phone ? ' · ' + order.customer_phone : '')
        : 'Walk In';

    const itemRowsHtml = (order.items || []).map(item => `
        <div class="bill-item-row">
            <span>${item.name_en}</span>
            <span>${item.quantity}</span>
            <span>₹${parseFloat(item.subtotal).toFixed(2)}</span>
        </div>
    `).join('');

    const payStatus = order.payment_status === 'PAID'
        ? '<span style="color:var(--green);font-weight:700;">✓ Paid</span>'
        : '<span style="color:#C62828;font-weight:700;">Unpaid</span>';

    document.getElementById('billPreviewContent').innerHTML = `
        <div class="bill-preview">
            <div class="bill-preview-head">
                <div class="bill-preview-parlour">South Tiffins</div>
                <div class="bill-preview-datetime">${dateStr}</div>
            </div>

            <div class="bill-preview-meta">
                <div class="bill-preview-row">
                    <span class="bill-preview-label">Bill No</span>
                    <span class="bill-preview-val orange">${order.bill?.bill_number || '—'}</span>
                </div>
                <div class="bill-preview-row">
                    <span class="bill-preview-label">Order No</span>
                    <span class="bill-preview-val">${order.order_number}</span>
                </div>
                <div class="bill-preview-row">
                    <span class="bill-preview-label">Type</span>
                    <span class="bill-preview-val">${typeDetail}</span>
                </div>
                <div class="bill-preview-row">
                    <span class="bill-preview-label">Payment</span>
                    <span class="bill-preview-val">${order.payment_method}</span>
                </div>
                <div class="bill-preview-row">
                    <span class="bill-preview-label">Status</span>
                    <span class="bill-preview-val">${payStatus}</span>
                </div>
            </div>

            <div class="bill-items-head">
                <span>Item</span>
                <span>Qty</span>
                <span>Amount</span>
            </div>
            ${itemRowsHtml}

            <div class="bill-preview-total">
                <span class="bill-total-lbl">Total</span>
                <span class="bill-total-val">₹${parseFloat(order.total_amount).toFixed(2)}</span>
            </div>

            <div class="bill-preview-footer">
                Thank you for visiting South Tiffins!<br>Come again 🙏
            </div>
        </div>
    `;

    // Set download link
    document.getElementById('billDownloadBtn').href = '/api/reports/bill?order_id=' + orderId;

    // Open modal
    document.getElementById('billOverlay').classList.add('open');
    document.body.style.overflow = 'hidden';
}

function closeBill() {
    document.getElementById('billOverlay').classList.remove('open');
    document.body.style.overflow = '';
}

// Close on backdrop click
document.getElementById('billOverlay').addEventListener('click', function(e) {
    if (e.target === this) closeBill();
});

// Close on ESC
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeBill();
});

async function markPaid(orderId, btn) {
    if (btn) { btn.disabled = true; btn.textContent = '...'; }
    try {
        const res  = await fetch('/api/payments/cash-paid', {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ order_id: orderId })
        });
        const data = await res.json();
        if (data.success) loadOrders();
        else if (btn) { btn.disabled = false; btn.textContent = 'Paid'; }
    } catch (err) {
        if (btn) { btn.disabled = false; btn.textContent = 'Paid'; }
    }
}

loadOrders();
setInterval(loadOrders, 30000);
</script>
@endsection