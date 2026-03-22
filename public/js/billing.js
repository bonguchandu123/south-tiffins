let billingCart    = [];
let billingPayment = 'CASH';
let billingType    = 'DINEIN';
let menuData       = [];

async function loadBilling() {
    try {
        const res  = await fetch('/api/dashboard/billing');
        const data = await res.json();

        if (!data.success) return;

        renderActiveOrders(data.active_orders);
        renderUnpaidBills(data.unpaid_bills);

    } catch (err) {
        console.error('Billing load error:', err);
    }
}

function renderActiveOrders(orders) {
    const container = document.getElementById('activeOrders');

    if (!orders || orders.length === 0) {
        container.innerHTML = '<div style="text-align:center;color:var(--muted);padding:20px;">No active orders</div>';
        return;
    }

    container.innerHTML = orders.map(order => {
        const title = order.order_type === 'DINEIN'
            ? 'Table ' + (order.table?.table_number || '—')
            : order.customer_name || 'Walk In';

        const statusMap = {
            PENDING:   '<span class="badge-pending">Pending</span>',
            PREPARING: '<span class="badge-preparing">Preparing</span>',
            READY:     '<span class="badge-ready">Ready</span>',
            SERVED:    '<span class="badge-served">Served</span>'
        };

        return `
            <div style="border:1.5px solid var(--border);border-radius:var(--radius-md);padding:16px;margin-bottom:12px;">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:10px;">
                    <div>
                        <div style="font-weight:700;font-size:15px;">${title}</div>
                        <div style="font-size:11px;color:var(--muted);">${order.order_number}</div>
                    </div>
                    <div style="text-align:right;">
                        ${statusMap[order.status] || order.status}
                        <div style="font-family:var(--font-display);font-weight:700;font-size:16px;color:var(--primary);margin-top:4px;">₹${order.total_amount}</div>
                    </div>
                </div>
                <div style="font-size:12px;color:var(--muted);margin-bottom:12px;">
                    ${order.items?.map(i => i.name_en + ' ×' + i.quantity).join(', ')}
                </div>
                <div style="display:flex;gap:8px;flex-wrap:wrap;">
                    ${order.payment_status === 'UNPAID' && order.payment_method === 'CASH'
                        ? `<button class="btn-sm btn-sm-primary" onclick="markPaid(${order.id})">Mark Paid</button>`
                        : '<span class="badge-paid">Paid</span>'
                    }
                    <a href="/api/reports/bill?order_id=${order.id}" class="btn-sm btn-sm-outline" target="_blank">
                        <i class="fas fa-print"></i> Bill
                    </a>
                    ${order.next_status
                        ? `<button class="btn-sm btn-sm-outline" onclick="updateStatus(${order.id}, '${order.next_status}')">→ ${order.next_status}</button>`
                        : ''
                    }
                </div>
            </div>
        `;
    }).join('');
}

function renderUnpaidBills(bills) {
    const container = document.getElementById('unpaidBills');

    if (!bills || bills.length === 0) {
        container.innerHTML = '<div style="text-align:center;color:var(--muted);padding:20px;">No unpaid bills</div>';
        return;
    }

    container.innerHTML = bills.map(bill => `
        <div style="border:1.5px solid rgba(198,40,40,0.2);border-radius:var(--radius-md);padding:16px;margin-bottom:12px;background:rgba(198,40,40,0.03);">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
                <div>
                    <div style="font-weight:700;">${bill.bill_number}</div>
                    <div style="font-size:11px;color:var(--muted);">${bill.order?.order_number}</div>
                </div>
                <div style="font-family:var(--font-display);font-weight:700;font-size:16px;color:var(--primary);">₹${bill.total_amount}</div>
            </div>
            <div style="display:flex;gap:8px;">
                <button class="btn-sm btn-sm-primary" onclick="markPaid(${bill.order_id})">Mark Paid</button>
                <a href="/api/reports/bill?order_id=${bill.order_id}" class="btn-sm btn-sm-outline" target="_blank">
                    <i class="fas fa-print"></i> Print
                </a>
            </div>
        </div>
    `).join('');
}

async function markPaid(orderId) {
    const res  = await fetch('/api/payments/cash-paid', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ order_id: orderId })
    });

    const data = await res.json();
    if (data.success) loadBilling();
}

async function updateStatus(orderId, status) {
    const res  = await fetch('/api/orders/update-status', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ order_id: orderId, status })
    });

    const data = await res.json();
    if (data.success) loadBilling();
}

async function openNewOrder(type) {
    billingType    = type;
    billingCart    = [];
    billingPayment = 'CASH';

    document.getElementById('newOrderTitle').textContent = type === 'DINEIN' ? 'New Dine In Order'
        : type === 'PARCEL' ? 'New Parcel Order' : 'New Walk In Order';

    document.getElementById('tableSelectWrap').style.display = type === 'DINEIN' ? 'block' : 'none';
    document.getElementById('customerWrap').style.display    = type !== 'DINEIN' ? 'block' : 'none';

    updateBillingTotal();
    await loadMenuForBilling();

    if (type === 'DINEIN') {
        await loadTablesForBilling();
    }

    document.getElementById('newOrderModal').classList.add('open');
}

async function loadMenuForBilling() {
    const res  = await fetch('/api/menu');
    const data = await res.json();

    if (!data.success) return;

    menuData = data.categories;

    const container = document.getElementById('menuItemsList');
    container.innerHTML = data.categories.map(cat => `
        <div style="margin-bottom:8px;">
            <div style="font-size:11px;font-weight:600;color:var(--muted);letter-spacing:0.06em;text-transform:uppercase;padding:4px 0;">${cat.name_en}</div>
            ${cat.menu_items.map(item => `
                <div style="display:flex;align-items:center;justify-content:space-between;padding:8px 4px;border-bottom:1px solid var(--border);">
                    <div>
                        <div style="font-size:13px;font-weight:500;">${item.name_en}</div>
                        <div style="font-size:12px;color:var(--primary);">₹${item.price}</div>
                    </div>
                    <button class="btn-sm btn-sm-primary" onclick="addToBillingCart(${item.id}, '${item.name_en}', '${item.name_te}', ${item.price}, '${item.image_url || ''}')">
                        + Add
                    </button>
                </div>
            `).join('')}
        </div>
    `).join('');
}

async function loadTablesForBilling() {
    const res  = await fetch('/api/tables/active');
    const data = await res.json();

    const select = document.getElementById('newOrderTable');
    select.innerHTML = data.tables?.map(t => `<option value="${t.id}">Table ${t.table_number}</option>`).join('') || '';
}

function addToBillingCart(id, nameEn, nameTe, price, imageUrl) {
    const existing = billingCart.find(c => c.id === id);
    if (existing) {
        existing.quantity++;
    } else {
        billingCart.push({ id, name_en: nameEn, name_te: nameTe, price, image_url: imageUrl, quantity: 1 });
    }
    updateBillingTotal();
    renderBillingCart();
}

function renderBillingCart() {
    const container = document.getElementById('selectedItemsList');

    if (billingCart.length === 0) {
        container.innerHTML = '';
        return;
    }

    container.innerHTML = `
        <div style="border:1.5px solid var(--border);border-radius:var(--radius-md);padding:12px;">
            <div style="font-size:11px;font-weight:600;color:var(--muted);letter-spacing:0.06em;text-transform:uppercase;margin-bottom:8px;">Selected Items</div>
            ${billingCart.map(item => `
                <div style="display:flex;align-items:center;justify-content:space-between;padding:6px 0;border-bottom:1px solid var(--border);">
                    <span style="font-size:13px;">${item.name_en}</span>
                    <div style="display:flex;align-items:center;gap:8px;">
                        <span style="font-size:12px;color:var(--primary);">₹${item.price * item.quantity}</span>
                        <div style="display:flex;align-items:center;gap:4px;">
                            <button onclick="changeBillingQty(${item.id}, -1)" style="width:24px;height:24px;background:var(--bg);border:1px solid var(--border);border-radius:4px;cursor:pointer;">−</button>
                            <span style="font-size:13px;font-weight:600;min-width:20px;text-align:center;">${item.quantity}</span>
                            <button onclick="changeBillingQty(${item.id}, 1)" style="width:24px;height:24px;background:var(--bg);border:1px solid var(--border);border-radius:4px;cursor:pointer;">+</button>
                        </div>
                    </div>
                </div>
            `).join('')}
        </div>
    `;
}

function changeBillingQty(id, delta) {
    const item = billingCart.find(c => c.id === id);
    if (!item) return;
    item.quantity += delta;
    if (item.quantity <= 0) billingCart = billingCart.filter(c => c.id !== id);
    updateBillingTotal();
    renderBillingCart();
}

function updateBillingTotal() {
    const total = billingCart.reduce((sum, c) => sum + c.price * c.quantity, 0);
    document.getElementById('newOrderTotal').textContent = '₹' + total;
}

function setPayMethod(method) {
    billingPayment = method;
    document.getElementById('payMethodCash').style.background   = method === 'CASH' ? 'var(--primary-light)' : 'white';
    document.getElementById('payMethodCash').style.borderColor  = method === 'CASH' ? 'var(--primary)' : 'var(--border)';
    document.getElementById('payMethodCash').style.color        = method === 'CASH' ? 'var(--primary)' : 'var(--muted)';
    document.getElementById('payMethodOnline').style.background = method === 'ONLINE' ? 'var(--primary-light)' : 'white';
    document.getElementById('payMethodOnline').style.borderColor= method === 'ONLINE' ? 'var(--primary)' : 'var(--border)';
    document.getElementById('payMethodOnline').style.color      = method === 'ONLINE' ? 'var(--primary)' : 'var(--muted)';
}

async function submitNewOrder() {
    if (billingCart.length === 0) { alert('Add items to order'); return; }

    const tableId      = document.getElementById('newOrderTable')?.value;
    const customerName = document.getElementById('newCustomerName')?.value;
    const customerPhone= document.getElementById('newCustomerPhone')?.value;

    const payload = {
        items:          billingCart,
        order_type:     billingType,
        order_source:   'BILLING',
        payment_method: billingPayment,
        table_id:       billingType === 'DINEIN' ? tableId : null,
        customer_name:  customerName || null,
        customer_phone: customerPhone || null
    };

    const res  = await fetch('/api/orders/place', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify(payload)
    });

    const data = await res.json();

    if (data.success) {
        closeModal('newOrderModal');
        loadBilling();
    } else {
        alert(data.message || 'Failed to place order');
    }
}

function closeModal(id) {
    document.getElementById(id).classList.remove('open');
}

loadBilling();
setInterval(loadBilling, 15000);