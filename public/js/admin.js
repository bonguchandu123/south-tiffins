let hourlyChart = null;

async function loadDashboard() {
    try {
        const res  = await fetch('/api/dashboard/today');
        const data = await res.json();

        if (!data.success) return;

        document.getElementById('totalOrders').textContent   = data.total_orders;
        document.getElementById('totalRevenue').textContent  = '₹' + Number(data.total_revenue).toLocaleString('en-IN');
        document.getElementById('pendingOrders').textContent = data.pending_orders;
        document.getElementById('parcelOrders').textContent  = data.parcel_orders;
        document.getElementById('cashRevenue').textContent   = '₹' + Number(data.cash_revenue).toLocaleString('en-IN');
        document.getElementById('onlineRevenue').textContent = '₹' + Number(data.online_revenue).toLocaleString('en-IN');

        renderHourlyChart(data.hourly);
        renderRecentOrders(data.recent_orders);

    } catch (err) {
        console.error('Dashboard load error:', err);
    }
}

async function loadTopItems() {
    try {
        const res  = await fetch('/api/dashboard/top-items');
        const data = await res.json();

        if (!data.success) return;

        const container = document.getElementById('topItems');

        if (data.items.length === 0) {
            container.innerHTML = '<div style="text-align:center;color:var(--muted);padding:20px;">No orders today</div>';
            return;
        }

        container.innerHTML = data.items.map((item, i) => `
            <div class="top-item">
                <div class="top-item-rank">${i + 1}</div>
                ${item.image_url
                    ? `<img src="${item.image_url}" class="top-item-img" alt="${item.name_en}">`
                    : `<div class="top-item-img" style="display:flex;align-items:center;justify-content:center;font-size:18px;">🍽️</div>`
                }
                <div class="top-item-name">${item.name_en}</div>
                <div class="top-item-qty">${item.total_qty}x</div>
            </div>
        `).join('');

    } catch (err) {
        console.error('Top items error:', err);
    }
}

function renderHourlyChart(hourly) {
    const labels = [];
    const values = [];

    for (let h = 6; h <= 22; h++) {
        const hour = hourly.find(x => parseInt(x.hour) === h);
        labels.push(h + ':00');
        values.push(hour ? parseFloat(hour.revenue) : 0);
    }

    const ctx = document.getElementById('hourlyChart').getContext('2d');

    if (hourlyChart) hourlyChart.destroy();

    hourlyChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Revenue (₹)',
                data: values,
                backgroundColor: 'rgba(255,107,53,0.15)',
                borderColor: '#FF6B35',
                borderWidth: 2,
                borderRadius: 6,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: {
                        font: { family: 'Outfit', size: 11 },
                        color: '#666666'
                    }
                },
                y: {
                    grid: { color: '#EEEEEE' },
                    ticks: {
                        font: { family: 'Outfit', size: 11 },
                        color: '#666666',
                        callback: v => '₹' + v
                    }
                }
            }
        }
    });
}

function renderRecentOrders(orders) {
    const tbody = document.getElementById('recentOrdersBody');

    if (!orders || orders.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;color:var(--muted);padding:20px;">No orders today</td></tr>';
        return;
    }

    tbody.innerHTML = orders.map(order => {
        const typeIcon  = order.order_type === 'DINEIN' ? '🪑'
                        : order.order_type === 'PARCEL' ? '📦' : '🚶';

        const statusMap = {
            PENDING:   '<span class="badge-pending">Pending</span>',
            PREPARING: '<span class="badge-preparing">Preparing</span>',
            READY:     '<span class="badge-ready">Ready</span>',
            SERVED:    '<span class="badge-served">Served</span>',
            PICKEDUP:  '<span class="badge-served">Picked Up</span>',
            CANCELLED: '<span style="color:var(--muted);font-size:11px;">Cancelled</span>'
        };

        const paidBadge = order.payment_status === 'PAID'
            ? '<span class="badge-paid">Paid</span>'
            : '<span class="badge-unpaid">Unpaid</span>';

        const title = order.order_type === 'DINEIN'
            ? 'Table ' + (order.table?.table_number || '—')
            : order.customer_name || 'Walk In';

        return `
            <tr>
                <td>
                    <div style="font-weight:600;font-size:13px;">${order.order_number}</div>
                    <div style="font-size:11px;color:var(--muted);">${title}</div>
                </td>
                <td>${typeIcon} ${order.order_type}</td>
                <td>${order.items?.length || 0} items</td>
                <td style="font-weight:600;color:var(--primary);">₹${order.total_amount}</td>
                <td>${statusMap[order.status] || order.status}</td>
                <td>${paidBadge}</td>
            </tr>
        `;
    }).join('');
}

loadDashboard();
loadTopItems();
setInterval(loadDashboard, 30000);
