let lastOrderIds   = [];
let isFirstLoad    = true;
let renderedOrders = {};
let ordersData     = {};
const buzzer       = document.getElementById('buzzer');

async function pollOrders() {
    try {
        const res  = await fetch('/api/orders/live', { credentials: 'same-origin' });
        const data = await res.json();
        if (!data.success) return;

        const orders = data.orders;

        if (!isFirstLoad) {
            const newOrders = orders.filter(o => !lastOrderIds.includes(o.id));
            if (newOrders.length > 0) { playBuzzer(); showAlert(); }
        }

        lastOrderIds = orders.map(o => o.id);
        isFirstLoad  = false;

        // Cache full order data for detail view
        orders.forEach(o => { ordersData[o.id] = o; });

        smartRenderOrders(orders);
        updateCounts(orders);
    } catch (err) {
        console.error('Poll error:', err);
    }
}

function smartRenderOrders(orders) {
    smartRenderSection('activeOrders', 'activeEmpty',
        orders.filter(o => o.status === 'PENDING' || o.status === 'PREPARING'));
    smartRenderSection('readyOrders', 'readyEmpty',
        orders.filter(o => o.status === 'READY'));
}

function smartRenderSection(containerId, emptyId, orders) {
    const container = document.getElementById(containerId);
    const empty     = document.getElementById(emptyId);

    if (orders.length === 0) {
        container.querySelectorAll('.order-row').forEach(el => el.remove());
        empty.style.display = 'block';
        return;
    }

    empty.style.display = 'none';
    const incomingIds   = orders.map(o => String(o.id));

    // Remove gone cards
    container.querySelectorAll('.order-row').forEach(el => {
        if (!incomingIds.includes(el.dataset.id)) {
            el.style.opacity   = '0';
            el.style.transform = 'translateX(-12px)';
            el.style.transition = 'all 0.25s ease';
            setTimeout(() => el.remove(), 250);
        }
    });

    // Add or update
    orders.forEach(order => {
        const rowId    = 'row-' + order.id;
        const existing = document.getElementById(rowId);
        const sig      = getSignature(order);

        if (!existing) {
            container.appendChild(buildRow(order));
            renderedOrders[order.id] = sig;
        } else if (renderedOrders[order.id] !== sig) {
            updateRow(existing, order);
            renderedOrders[order.id] = sig;
        }
    });
}

function getSignature(order) {
    return [order.status, order.payment_status, order.next_status].join('|');
}

// ── BUILD ROW CARD (horizontal: images left, bill summary right) ──
function buildRow(order) {
    const row         = document.createElement('div');
    row.className     = 'order-row' + (!lastOrderIds.includes(order.id) ? ' new-order' : '');
    row.id            = 'row-' + order.id;
    row.dataset.id    = String(order.id);
    row.onclick       = () => openDetail(order.id);

    const tableLabel  = order.table_number
        ? `Table ${order.table_number}`
        : (order.customer_name || 'Walk In');

    const typeLabel   = order.order_type === 'DINEIN' ? '🪑 Dine In'
                      : order.order_type === 'PARCEL' ? '📦 Parcel'
                      : '🚶 Walk In';

    const statusClass = getStatusClass(order.status);
    const statusLabel = order.status.charAt(0) + order.status.slice(1).toLowerCase();

    // Images layout class
    const imgCount   = order.items.length;
    const imgClass   = imgCount === 1 ? 'single'
                     : imgCount === 2 ? 'two-cols'
                     : imgCount === 3 ? 'three'
                     : 'two-cols';  // 4+ items: 2x2 grid

    const imagesHtml = order.items.slice(0, 4).map(item => `
        <div class="order-row-img">
            ${item.image_url
                ? `<img src="${item.image_url}" alt="${item.name_en}" loading="lazy">`
                : `<div class="img-placeholder">🍽️</div>`
            }
            <div class="order-row-img-label">
                <div class="order-row-img-name">${item.name_en}</div>
                <div class="order-row-img-qty">×${item.quantity}</div>
            </div>
        </div>
    `).join('');

    // Items summary text
    const itemsSummary = order.items
        .map(i => `${i.name_en} ×${i.quantity}`)
        .join(' · ');

    const payTag  = order.payment_method === 'CASH'
        ? `<span class="payment-tag tag-cash">Cash</span>`
        : `<span class="payment-tag tag-online">Online</span>`;

    const paidTag = order.payment_status === 'PAID'
        ? `<span class="payment-tag tag-paid">✓ Paid</span>`
        : `<span class="payment-tag tag-unpaid">Unpaid</span>`;

    row.innerHTML = `
        <div class="order-row-images ${imgClass}">
            ${imagesHtml}
        </div>
        <div class="order-row-bill">
            <div class="order-row-bill-top">
                <div>
                    <div class="order-row-table">${tableLabel}</div>
                    <div class="order-row-num">${order.order_number}${order.bill_number ? ' · ' + order.bill_number : ''}</div>
                </div>
                <div class="order-row-badges">
                    <span class="order-type-pill">${typeLabel}</span>
                    <span class="order-status-pill ${statusClass}">${statusLabel}</span>
                </div>
            </div>
            <div class="order-row-items-summary">${itemsSummary}</div>
            <div class="order-row-bill-bottom">
                <div class="order-row-amount">₹${parseFloat(order.total_amount).toFixed(2)}</div>
                <div class="order-row-tags" id="tags-${order.id}">${payTag}${paidTag}</div>
            </div>
            <div class="order-row-time">
                <i class="fas fa-clock" style="font-size:10px;"></i>
                ${order.created_at}
                <span style="margin-left:auto;" class="tap-hint"><i class="fas fa-expand-alt" style="font-size:9px;"></i> Tap for details</span>
            </div>
        </div>
    `;

    return row;
}

function updateRow(rowEl, order) {
    const statusClass = getStatusClass(order.status);
    const statusLabel = order.status.charAt(0) + order.status.slice(1).toLowerCase();

    const statusPill = rowEl.querySelector('.order-status-pill');
    if (statusPill) {
        statusPill.className  = 'order-status-pill ' + statusClass;
        statusPill.textContent = statusLabel;
    }

    const tagsEl = rowEl.querySelector('.order-row-tags');
    if (tagsEl) {
        const payTag  = order.payment_method === 'CASH'
            ? `<span class="payment-tag tag-cash">Cash</span>`
            : `<span class="payment-tag tag-online">Online</span>`;
        const paidTag = order.payment_status === 'PAID'
            ? `<span class="payment-tag tag-paid">✓ Paid</span>`
            : `<span class="payment-tag tag-unpaid">Unpaid</span>`;
        tagsEl.innerHTML = payTag + paidTag;
    }

    // Update cached data
    ordersData[order.id] = order;

    // Refresh detail modal if it's open for this order
    const modal = document.getElementById('detailModal');
    if (modal && modal.dataset.orderId == order.id) {
        renderDetailContent(order);
    }
}

// ── DETAIL MODAL ──
function openDetail(orderId) {
    const order = ordersData[orderId];
    if (!order) return;

    const modal   = document.getElementById('detailModal');
    const overlay = document.getElementById('detailOverlay');
    modal.dataset.orderId = orderId;

    renderDetailContent(order);

    // On mobile, snap to bottom sheet with safe top padding
    if (window.innerWidth <= 768) {
        overlay.style.alignItems = 'flex-end';
        overlay.style.padding    = '0';
    } else {
        overlay.style.alignItems = 'center';
        overlay.style.padding    = '24px';
    }

    overlay.classList.add('open');
    document.body.style.overflow = 'hidden';

    // Scroll modal to top so header is always visible
    setTimeout(() => { modal.scrollTop = 0; }, 50);
}

function renderDetailContent(order) {
    const tableLabel = order.table_number
        ? `Table ${order.table_number}`
        : (order.customer_name || 'Walk In');

    const typeLabel  = order.order_type === 'DINEIN'  ? '🪑 Dine In'
                     : order.order_type === 'PARCEL'  ? '📦 Parcel'
                     : '🚶 Walk In';

    // All images (no limit)
    const imagesHtml = order.items.map(item => `
        <div class="dm-item-img">
            ${item.image_url
                ? `<img src="${item.image_url}" alt="${item.name_en}" loading="lazy">`
                : `<div class="img-placeholder">🍽️</div>`
            }
            <div class="dm-item-overlay">
                <div class="dm-item-name">${item.name_en}</div>
                <div class="dm-item-detail">
                    <span>×${item.quantity}</span>
                    <span>₹${parseFloat(item.subtotal).toFixed(2)}</span>
                </div>
            </div>
        </div>
    `).join('');

    // Items table rows
    const itemRowsHtml = order.items.map(item => `
        <tr>
            <td>${item.name_en}</td>
            <td>${item.quantity}</td>
            <td>₹${parseFloat(item.price).toFixed(2)}</td>
            <td>₹${parseFloat(item.subtotal).toFixed(2)}</td>
        </tr>
    `).join('');

    const payTag  = order.payment_method === 'CASH'
        ? `<span class="payment-tag tag-cash">Cash</span>`
        : `<span class="payment-tag tag-online">Online</span>`;
    const paidTag = order.payment_status === 'PAID'
        ? `<span class="payment-tag tag-paid">✓ Paid</span>`
        : `<span class="payment-tag tag-unpaid">Unpaid</span>`;

    const markPaidBtn = order.payment_status === 'UNPAID' && order.payment_method === 'CASH'
        ? `<button class="btn-status btn-mark-paid" onclick="markPaid(${order.id}, this)">
               <i class="fas fa-check"></i> Mark Paid
           </button>`
        : '';

    const nextBtn = order.next_status
        ? `<button class="btn-status btn-next-status" onclick="updateStatus(${order.id}, '${order.next_status}', this)">
               <i class="fas fa-arrow-right"></i>
               ${order.next_status.charAt(0) + order.next_status.slice(1).toLowerCase()}
           </button>`
        : '';

    const billNo  = order.bill_number || '—';
    const now     = new Date();
    const dateStr = now.toLocaleDateString('en-IN', { day: '2-digit', month: 'short', year: 'numeric' })
        + ', ' + now.toLocaleTimeString('en-IN', { hour: '2-digit', minute: '2-digit' });

    const typeDetail = order.order_type === 'DINEIN'
        ? 'Dine In — Table ' + (order.table_number || '')
        : order.order_type === 'PARCEL'
        ? 'Parcel — ' + (order.customer_name || '')
        : 'Walk In';

    document.getElementById('detailModalInner').innerHTML = `
        <div class="dm-header">
            <div class="dm-header-left">
                <div class="dm-title">${tableLabel}</div>
                <div class="dm-subtitle">${typeLabel} · ${order.created_at}</div>
            </div>
            <button class="dm-close" onclick="closeDetail()"><i class="fas fa-times"></i></button>
        </div>

        <div class="dm-images">${imagesHtml}</div>

        <div class="dm-bill">
            <div class="dm-bill-header">
                <div class="dm-parlour">South Tiffins</div>
                <div class="dm-datetime">${dateStr}</div>
            </div>

            <div class="dm-meta">
                <div class="dm-meta-item">
                    <div class="dm-meta-label">Bill No</div>
                    <div class="dm-meta-value orange">${billNo}</div>
                </div>
                <div class="dm-meta-item">
                    <div class="dm-meta-label">Order No</div>
                    <div class="dm-meta-value">${order.order_number}</div>
                </div>
                <div class="dm-meta-item">
                    <div class="dm-meta-label">Type</div>
                    <div class="dm-meta-value">${typeDetail}</div>
                </div>
                <div class="dm-meta-item">
                    <div class="dm-meta-label">Payment</div>
                    <div class="dm-meta-value">${order.payment_method}</div>
                </div>
            </div>

            <table class="dm-items-table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>${itemRowsHtml}</tbody>
            </table>

            <div class="dm-total-row">
                <span class="dm-total-label">Total</span>
                <span class="dm-total-amount">₹${parseFloat(order.total_amount).toFixed(2)}</span>
            </div>

            <div class="dm-tags">${payTag}${paidTag}</div>
        </div>

        <div class="dm-actions">
            ${markPaidBtn}
            ${nextBtn}
        </div>
    `;
}

function closeDetail(e) {
    // If called with event, only close if clicked on the backdrop itself
    if (e && e.target !== document.getElementById('detailOverlay')) return;
    document.getElementById('detailOverlay').classList.remove('open');
    document.body.style.overflow = '';
}

// Backdrop click — only close when clicking the dark background, not the modal
document.getElementById('detailOverlay').addEventListener('click', function(e) {
    if (e.target === this) {
        this.classList.remove('open');
        document.body.style.overflow = '';
    }
});

// Close on ESC key
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        document.getElementById('detailOverlay').classList.remove('open');
        document.body.style.overflow = '';
    }
});

function getStatusClass(status) {
    return status === 'PENDING'   ? 'status-pending'
         : status === 'PREPARING' ? 'status-preparing'
         : 'status-ready';
}

function updateCounts(orders) {
    document.getElementById('pendingNum').textContent  = orders.filter(o => o.status === 'PENDING').length;
    document.getElementById('activeCount').textContent = orders.filter(o => o.status === 'PENDING' || o.status === 'PREPARING').length;
    document.getElementById('readyCount').textContent  = orders.filter(o => o.status === 'READY').length;
}

async function updateStatus(orderId, nextStatus, btn) {
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
    }

    try {
        const res  = await fetch('/api/orders/update-status', {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ order_id: orderId, status: nextStatus })
        });
        const data = await res.json();
        if (data.success) {
            document.getElementById('detailOverlay').classList.remove('open');
            document.body.style.overflow = '';
            pollOrders();
        } else {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-arrow-right"></i> ' + nextStatus.charAt(0) + nextStatus.slice(1).toLowerCase();
            }
        }
    } catch (err) {
        console.error(err);
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-arrow-right"></i> ' + nextStatus.charAt(0) + nextStatus.slice(1).toLowerCase();
        }
    }
}

async function markPaid(orderId, btn) {
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Marking...';
    }

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
        if (data.success) {
            pollOrders();
        } else {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-check"></i> Mark Paid';
            }
        }
    } catch (err) {
        console.error(err);
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-check"></i> Mark Paid';
        }
    }
}

function playBuzzer() {
    try { buzzer.currentTime = 0; buzzer.play(); } catch (e) {}
    document.title = '🔔 New Order! — Counter';
    setTimeout(() => { document.title = 'Counter — South Tiffins'; }, 3000);
}

function showAlert() {
    const el = document.getElementById('newOrderAlert');
    el.classList.add('show');
    setTimeout(() => el.classList.remove('show'), 3000);
}

function updateTime() {
    const now = new Date();
    document.getElementById('counterTime').textContent =
        String(now.getHours()).padStart(2,'0') + ':' + String(now.getMinutes()).padStart(2,'0');
}

updateTime();
setInterval(updateTime, 1000);
pollOrders();
setInterval(pollOrders, 2000);