@extends('layouts.admin')

@section('title', 'Dashboard — South Tiffins')
@section('page-title', 'Dashboard')

@section('styles')
<style>
    .dash-wrap { display:flex; flex-direction:column; gap:20px; }

    .kpi-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 14px;
    }

    .kpi-card {
        background: white;
        border-radius: var(--radius-xl);
        border: 1.5px solid var(--border);
        padding: 20px;
        position: relative;
        overflow: hidden;
        transition: var(--transition);
    }

    .kpi-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }

    .kpi-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 3px;
    }

    .kpi-card.c1::before { background: var(--primary); }
    .kpi-card.c2::before { background: #2D6A4F; }
    .kpi-card.c3::before { background: #1565C0; }
    .kpi-card.c4::before { background: #E65100; }

    .kpi-top { display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:14px; }

    .kpi-icon {
        width: 40px; height: 40px;
        border-radius: var(--radius-md);
        display: flex; align-items: center; justify-content: center;
        font-size: 16px; flex-shrink: 0;
    }

    .kpi-icon.i1 { background: var(--primary-light); color: var(--primary); }
    .kpi-icon.i2 { background: var(--green-light);   color: var(--green); }
    .kpi-icon.i3 { background: rgba(33,150,243,0.1); color: #1565C0; }
    .kpi-icon.i4 { background: rgba(255,152,0,0.1);  color: #E65100; }

    .kpi-label { font-size: 12px; font-weight: 500; color: var(--muted); margin-bottom: 4px; }
    .kpi-value { font-family: var(--font-display); font-weight: 700; font-size: 28px; color: var(--dark); letter-spacing: -0.02em; line-height: 1; }
    .kpi-value.ov { color: var(--primary); }
    .kpi-sub   { font-size: 11px; color: var(--muted); margin-top: 6px; }

    .rev-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }

    .rev-card {
        background: white;
        border-radius: var(--radius-xl);
        border: 1.5px solid var(--border);
        padding: 18px 20px;
        display: flex; align-items: center; gap: 14px;
    }

    .rev-icon { width:44px; height:44px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:18px; flex-shrink:0; }
    .rev-icon.cash   { background: rgba(255,193,7,0.12); }
    .rev-icon.online { background: rgba(33,150,243,0.12); }
    .rev-label { font-size: 12px; color: var(--muted); margin-bottom: 2px; }
    .rev-val   { font-family: var(--font-display); font-weight: 700; font-size: 22px; color: var(--dark); }

    .mid-row { display: grid; grid-template-columns: 1.6fr 1fr; gap: 14px; }

    .type-pills { display: flex; gap: 10px; }
    .type-pill  { flex:1; background:var(--bg); border-radius:var(--radius-lg); padding:14px; border:1.5px solid var(--border); text-align:center; }
    .type-pill-icon { font-size: 20px; margin-bottom: 5px; }
    .type-pill-val  { font-family: var(--font-display); font-weight: 700; font-size: 20px; color: var(--dark); }
    .type-pill-lbl  { font-size: 11px; color: var(--muted); margin-top: 2px; }

    .top-item-row { display:flex; align-items:center; gap:12px; padding:10px 0; border-bottom:1px solid var(--border); }
    .top-item-row:last-child { border-bottom: none; }
    .top-item-img { width:40px; height:40px; border-radius:var(--radius-md); background:var(--primary-light); display:flex; align-items:center; justify-content:center; font-size:18px; flex-shrink:0; overflow:hidden; }
    .top-item-img img { width:40px; height:40px; object-fit:cover; border-radius:var(--radius-md); }
    .top-item-name { flex:1; font-size:13px; font-weight:600; color:var(--dark); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; min-width:0; }
    .top-item-bar-wrap { width:60px; height:4px; background:var(--border); border-radius:2px; overflow:hidden; }
    .top-item-bar { height:100%; background:var(--primary); border-radius:2px; transition:width 0.8s ease; }
    .top-item-rev { font-family:var(--font-display); font-weight:700; font-size:14px; color:var(--primary); white-space:nowrap; }
    .top-item-qty { font-size:11px; color:var(--muted); text-align:right; }

    @media (max-width: 1024px) {
        .kpi-row { grid-template-columns: repeat(2,1fr); }
        .mid-row { grid-template-columns: 1fr; }
    }

    @media (max-width: 768px) {
        .kpi-row     { grid-template-columns: repeat(2,1fr); gap:10px; }
        .rev-row     { grid-template-columns: 1fr 1fr; gap:10px; }
        .kpi-value   { font-size: 22px; }
        .type-pills  { flex-wrap: wrap; }
        .type-pill   { min-width: calc(50% - 5px); }
    }

    @media (max-width: 480px) {
        .kpi-row     { gap: 8px; }
        .kpi-card    { padding: 14px; }
        .kpi-value   { font-size: 20px; }
        .rev-row     { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')
<div class="dash-wrap">

    <div class="kpi-row">
        <div class="kpi-card c1">
            <div class="kpi-top">
                <div>
                    <div class="kpi-label">Total Orders</div>
                    <div class="kpi-value" id="totalOrders">—</div>
                    <div class="kpi-sub">Today</div>
                </div>
                <div class="kpi-icon i1"><i class="fas fa-receipt"></i></div>
            </div>
        </div>
        <div class="kpi-card c2">
            <div class="kpi-top">
                <div>
                    <div class="kpi-label">Revenue</div>
                    <div class="kpi-value ov" id="totalRevenue">—</div>
                    <div class="kpi-sub">Paid today</div>
                </div>
                <div class="kpi-icon i2"><i class="fas fa-rupee-sign"></i></div>
            </div>
        </div>
        <div class="kpi-card c3">
            <div class="kpi-top">
                <div>
                    <div class="kpi-label">Active</div>
                    <div class="kpi-value" id="pendingOrders">—</div>
                    <div class="kpi-sub">Pending now</div>
                </div>
                <div class="kpi-icon i3"><i class="fas fa-fire"></i></div>
            </div>
        </div>
        <div class="kpi-card c4">
            <div class="kpi-top">
                <div>
                    <div class="kpi-label">Parcels</div>
                    <div class="kpi-value" id="parcelOrders">—</div>
                    <div class="kpi-sub">Today</div>
                </div>
                <div class="kpi-icon i4"><i class="fas fa-box"></i></div>
            </div>
        </div>
    </div>

    <div class="rev-row">
        <div class="rev-card">
            <div class="rev-icon cash">💵</div>
            <div>
                <div class="rev-label">Cash Revenue</div>
                <div class="rev-val" id="cashRevenue">—</div>
            </div>
        </div>
        <div class="rev-card">
            <div class="rev-icon online">💳</div>
            <div>
                <div class="rev-label">Online Revenue</div>
                <div class="rev-val" id="onlineRevenue">—</div>
            </div>
        </div>
    </div>

    <div class="mid-row">

        <div class="admin-panel">
            <div class="admin-panel-header">
                <div class="admin-panel-title">Hourly Revenue</div>
                <span style="font-size:11px;color:var(--muted);">Today</span>
            </div>
            <div class="admin-panel-body">
                <div style="position:relative;width:100%;height:220px;">
                    <canvas id="hourlyChart"></canvas>
                </div>
            </div>
        </div>

        <div style="display:flex;flex-direction:column;gap:14px;">

            <div class="admin-panel">
                <div class="admin-panel-header">
                    <div class="admin-panel-title">Order Types</div>
                </div>
                <div class="admin-panel-body">
                    <div class="type-pills">
                        <div class="type-pill">
                            <div class="type-pill-icon">🪑</div>
                            <div class="type-pill-val" id="dineinOrders">—</div>
                            <div class="type-pill-lbl">Dine In</div>
                        </div>
                        <div class="type-pill">
                            <div class="type-pill-icon">📦</div>
                            <div class="type-pill-val" id="parcelOrders2">—</div>
                            <div class="type-pill-lbl">Parcel</div>
                        </div>
                        <div class="type-pill">
                            <div class="type-pill-icon">🚶</div>
                            <div class="type-pill-val" id="walkinOrders">—</div>
                            <div class="type-pill-lbl">Walk In</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="admin-panel" style="flex:1;">
                <div class="admin-panel-header">
                    <div class="admin-panel-title">Top Items</div>
                </div>
                <div class="admin-panel-body" id="topItems">
                    <div style="text-align:center;color:var(--muted);padding:16px;font-size:13px;">Loading...</div>
                </div>
            </div>

        </div>

    </div>

    <div class="admin-panel">
        <div class="admin-panel-header">
            <div class="admin-panel-title">Recent Orders</div>
            <a href="{{ route('admin.orders') }}" class="btn-sm btn-sm-outline">View All</a>
        </div>
        <div class="admin-panel-body" style="padding:0;overflow-x:auto;">
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
                    </tr>
                </thead>
                <tbody id="recentOrdersBody">
                    <tr><td colspan="7" style="text-align:center;color:var(--muted);padding:20px;">Loading...</td></tr>
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let hourlyChart = null;

async function loadDashboard() {
    try {
        const res  = await fetch('/api/dashboard/today', { credentials: 'same-origin' });
        const data = await res.json();
        if (!data.success) return;

        document.getElementById('totalOrders').textContent   = data.total_orders;
        document.getElementById('totalRevenue').textContent  = '₹' + Math.round(data.total_revenue);
        document.getElementById('pendingOrders').textContent = data.pending_orders;
        document.getElementById('parcelOrders').textContent  = data.parcel_orders;
        document.getElementById('parcelOrders2').textContent = data.parcel_orders;
        document.getElementById('cashRevenue').textContent   = '₹' + Math.round(data.cash_revenue);
        document.getElementById('onlineRevenue').textContent = '₹' + Math.round(data.online_revenue);
        document.getElementById('dineinOrders').textContent  = data.dinein_orders;
        document.getElementById('walkinOrders').textContent  = data.walkin_orders;

        renderHourlyChart(data.hourly || []);
        renderRecentOrders(data.recent_orders || []);
    } catch (err) { console.error(err); }
}

async function loadTopItems() {
    try {
        const res  = await fetch('/api/dashboard/top-items', { credentials: 'same-origin' });
        const data = await res.json();
        if (!data.success || !data.items.length) {
            document.getElementById('topItems').innerHTML = '<div style="text-align:center;color:var(--muted);padding:16px;font-size:13px;">No data yet today</div>';
            return;
        }
        const maxQty = Math.max(...data.items.map(i => i.total_qty));
        document.getElementById('topItems').innerHTML = data.items.map(item => `
            <div class="top-item-row">
                <div class="top-item-img">
                    ${item.image_url ? `<img src="${item.image_url}" alt="${item.name_en}">` : '🍽️'}
                </div>
                <div style="flex:1;min-width:0;">
                    <div class="top-item-name">${item.name_en}</div>
                    <div class="top-item-bar-wrap" style="margin-top:5px;">
                        <div class="top-item-bar" style="width:${Math.round((item.total_qty/maxQty)*100)}%"></div>
                    </div>
                </div>
                <div style="text-align:right;">
                    <div class="top-item-rev">₹${Math.round(item.total_revenue)}</div>
                    <div class="top-item-qty">${item.total_qty} sold</div>
                </div>
            </div>
        `).join('');
    } catch (err) { console.error(err); }
}

function renderHourlyChart(hourly) {
    const labels = [];
    const values = [];
    for (let h = 6; h <= 22; h++) {
        const entry = hourly.find(r => r.hour == h);
        labels.push(h === 12 ? '12PM' : h < 12 ? h+'AM' : (h-12)+'PM');
        values.push(entry ? Math.round(parseFloat(entry.revenue)) : 0);
    }
    if (hourlyChart) hourlyChart.destroy();
    hourlyChart = new Chart(document.getElementById('hourlyChart'), {
        type: 'bar',
        data: {
            labels,
            datasets: [{
                data: values,
                backgroundColor: values.map(v => v > 0 ? 'rgba(255,107,53,0.85)' : 'rgba(238,238,238,0.8)'),
                borderRadius: 5,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: { callbacks: { label: ctx => '₹' + Math.round(ctx.raw) } }
            },
            scales: {
                x: { grid: { display: false }, ticks: { font: { size: 10 }, color: '#999', maxRotation: 0 } },
                y: { grid: { color: 'rgba(0,0,0,0.04)' }, ticks: { font: { size: 10 }, color: '#999', callback: v => '₹' + Math.round(v) }, beginAtZero: true }
            }
        }
    });
}

function renderRecentOrders(orders) {
    const tbody = document.getElementById('recentOrdersBody');
    if (!orders.length) {
        tbody.innerHTML = '<tr><td colspan="7" style="text-align:center;color:var(--muted);padding:20px;">No orders yet today</td></tr>';
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
        const typeIcon  = order.order_type === 'DINEIN' ? '🪑' : order.order_type === 'PARCEL' ? '📦' : '🚶';
        const title     = order.order_type === 'DINEIN' ? 'Table ' + (order.table?.table_number || '—') : (order.customer_name || 'Walk In');
        const paidBadge = order.payment_status === 'PAID' ? '<span class="badge-paid">Paid</span>' : '<span class="badge-unpaid">Unpaid</span>';
        const time      = new Date(order.created_at).toLocaleTimeString('en-IN', { hour:'2-digit', minute:'2-digit' });
        return `
            <tr>
                <td><div style="font-weight:600;font-size:13px;">${order.order_number}</div><div style="font-size:11px;color:var(--muted);">${time}</div></td>
                <td>${typeIcon} ${order.order_type}</td>
                <td>${title}</td>
                <td>${order.items?.length || 0} items</td>
                <td style="font-weight:700;color:var(--primary);font-family:var(--font-display);">₹${order.total_amount}</td>
                <td>${statusMap[order.status] || order.status}</td>
                <td><div style="font-size:12px;">${order.payment_method}</div><div style="margin-top:2px;">${paidBadge}</div></td>
            </tr>
        `;
    }).join('');
}

loadDashboard();
loadTopItems();
setInterval(loadDashboard, 30000);
setInterval(loadTopItems,  60000);
</script>
@endsection