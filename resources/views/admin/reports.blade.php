@extends('layouts.admin')

@section('title', 'Reports — South Tiffins')
@section('page-title', 'Reports')

@section('styles')
<style>
    .reports-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 20px;
    }

    .report-card {
        background: white;
        border-radius: var(--radius-xl);
        border: 1.5px solid var(--border);
        overflow: hidden;
        transition: var(--transition);
    }

    .report-card:hover {
        border-color: var(--primary);
        box-shadow: var(--shadow-md);
    }

    .report-card-head {
        background: var(--primary-light);
        border-bottom: 1.5px solid rgba(255,107,53,0.15);
        padding: 16px 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .report-card-icon {
        width: 36px;
        height: 36px;
        background: var(--primary);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 15px;
        flex-shrink: 0;
    }

    .report-card-title {
        font-family: var(--font-display);
        font-weight: 700;
        font-size: 15px;
        color: var(--dark);
    }

    .report-card-body {
        padding: 18px 20px;
    }

    .report-card-desc {
        font-size: 13px;
        color: var(--muted);
        margin-bottom: 16px;
        line-height: 1.5;
    }

    .monthly-card {
        background: white;
        border-radius: var(--radius-xl);
        border: 1.5px solid var(--border);
        overflow: hidden;
        max-width: 560px;
    }

    .monthly-card:hover {
        border-color: var(--primary);
        box-shadow: var(--shadow-md);
    }

    /* ── REPORT PREVIEW MODAL ── */
    .report-overlay {
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

    .report-overlay.open { display: flex; }

    .report-modal {
        background: white;
        border-radius: 20px;
        width: 100%;
        max-width: 520px;
        max-height: 90vh;
        overflow-y: auto;
        animation: reportIn 0.3s cubic-bezier(0.34,1.56,0.64,1) forwards;
        box-shadow: 0 20px 60px rgba(0,0,0,0.2);
    }

    @keyframes reportIn {
        from { opacity:0; transform:scale(0.94) translateY(12px); }
        to   { opacity:1; transform:scale(1) translateY(0); }
    }

    .report-modal-header {
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

    .report-modal-title {
        font-family: var(--font-display);
        font-weight: 700;
        font-size: 18px;
        color: var(--dark);
    }

    .report-close-btn {
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

    .report-close-btn:hover {
        background: var(--primary-light);
        color: var(--primary);
        border-color: var(--primary);
    }

    /* Loading state */
    .report-loading {
        padding: 48px 24px;
        text-align: center;
    }

    .report-loading .spinner {
        width: 36px;
        height: 36px;
        border: 3px solid var(--border);
        border-top-color: var(--primary);
        border-radius: 50%;
        animation: spin 0.7s linear infinite;
        margin: 0 auto 14px;
    }

    @keyframes spin { to { transform: rotate(360deg); } }

    .report-loading p {
        font-size: 13px;
        color: var(--muted);
    }

    /* Report preview content */
    .report-preview {
        padding: 28px 32px;
    }

    .rp-head {
        text-align: center;
        padding-bottom: 16px;
        border-bottom: 1px dashed var(--border);
        margin-bottom: 20px;
    }

    .rp-parlour {
        font-family: var(--font-display);
        font-weight: 900;
        font-style: italic;
        font-size: 24px;
        color: var(--primary);
        margin-bottom: 3px;
    }

    .rp-report-type {
        font-size: 13px;
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 2px;
    }

    .rp-date {
        font-size: 12px;
        color: var(--muted);
    }

    /* Summary stats */
    .rp-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
        margin-bottom: 20px;
    }

    .rp-stat {
        background: var(--bg);
        border-radius: var(--radius-md);
        padding: 12px;
        text-align: center;
    }

    .rp-stat-val {
        font-family: var(--font-display);
        font-weight: 700;
        font-size: 20px;
        color: var(--primary);
        line-height: 1;
        margin-bottom: 3px;
    }

    .rp-stat-lbl {
        font-size: 10px;
        font-weight: 600;
        color: var(--muted);
        letter-spacing: 0.06em;
        text-transform: uppercase;
    }

    /* Orders table */
    .rp-section-title {
        font-size: 11px;
        font-weight: 700;
        color: var(--muted);
        letter-spacing: 0.1em;
        text-transform: uppercase;
        margin-bottom: 10px;
        padding-bottom: 6px;
        border-bottom: 1px solid var(--border);
    }

    .rp-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
        margin-bottom: 16px;
    }

    .rp-table th {
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.07em;
        text-transform: uppercase;
        color: var(--muted);
        padding: 6px 0;
        border-bottom: 1px solid var(--border);
        text-align: left;
    }

    .rp-table th:not(:first-child),
    .rp-table td:not(:first-child) { text-align: right; }

    .rp-table td {
        padding: 7px 0;
        color: var(--dark);
        border-bottom: 1px solid var(--bg);
        font-size: 12px;
    }

    .rp-table tr:last-child td { border-bottom: none; }

    .rp-total-row {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-top: 2px solid var(--border);
        margin-top: 4px;
    }

    .rp-total-lbl {
        font-family: var(--font-display);
        font-weight: 700;
        font-size: 16px;
        color: var(--dark);
    }

    .rp-total-val {
        font-family: var(--font-display);
        font-weight: 900;
        font-size: 24px;
        color: var(--primary);
    }

    .rp-footer {
        text-align: center;
        padding-top: 14px;
        border-top: 1px dashed var(--border);
        font-size: 12px;
        color: var(--muted);
        margin-top: 14px;
    }

    /* Download bar */
    .report-download-bar {
        padding: 16px 24px 20px;
        border-top: 1px solid var(--border);
        display: flex;
        gap: 10px;
    }

    .report-download-bar .btn-primary {
        flex: 1;
        justify-content: center;
        padding: 12px;
        font-size: 14px;
    }

    .report-download-bar .btn-outline {
        padding: 12px 20px;
        font-size: 14px;
    }

    @media (max-width: 768px) {
        .reports-grid { grid-template-columns: 1fr; }
        .report-overlay { padding: 0; align-items: flex-end; }
        .report-modal  { border-radius: 20px 20px 0 0; max-height: 95vh; max-width: 100%; }
        .rp-stats      { grid-template-columns: repeat(3, 1fr); }
        .report-preview{ padding: 20px; }
    }

    @media (max-width: 480px) {
        .rp-stats { grid-template-columns: 1fr 1fr; }
    }
</style>
@endsection

@section('content')

<div class="reports-grid">

    <div class="report-card">
        <div class="report-card-head">
            <div class="report-card-icon"><i class="fas fa-calendar-day"></i></div>
            <div class="report-card-title">Today's Report</div>
        </div>
        <div class="report-card-body">
            <p class="report-card-desc">View and download today's complete sales report.</p>
            <button class="btn-primary" style="width:100%;justify-content:center;" onclick="previewReport('today')">
                <i class="fas fa-eye"></i> Preview Report
            </button>
        </div>
    </div>

    <div class="report-card">
        <div class="report-card-head">
            <div class="report-card-icon"><i class="fas fa-history"></i></div>
            <div class="report-card-title">Yesterday's Report</div>
        </div>
        <div class="report-card-body">
            <p class="report-card-desc">View and download yesterday's complete sales report.</p>
            <button class="btn-primary" style="width:100%;justify-content:center;" onclick="previewReport('yesterday')">
                <i class="fas fa-eye"></i> Preview Report
            </button>
        </div>
    </div>

    <div class="report-card">
        <div class="report-card-head">
            <div class="report-card-icon"><i class="fas fa-calendar-alt"></i></div>
            <div class="report-card-title">Custom Date</div>
        </div>
        <div class="report-card-body">
            <div class="form-group" style="margin-bottom:14px;">
                <label class="form-label">Select Date</label>
                <input type="date" id="customDate" value="{{ date('Y-m-d') }}">
            </div>
            <button class="btn-primary" style="width:100%;justify-content:center;" onclick="previewReport('custom')">
                <i class="fas fa-eye"></i> Preview Report
            </button>
        </div>
    </div>

</div>

<div class="monthly-card">
    <div class="report-card-head">
        <div class="report-card-icon"><i class="fas fa-chart-bar"></i></div>
        <div class="report-card-title">Monthly Report</div>
    </div>
    <div class="report-card-body" style="padding:18px 20px;">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px;">
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Month</label>
                <select id="reportMonth">
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $m == date('m') ? 'selected' : '' }}>
                            {{ date('F', mktime(0,0,0,$m,1)) }}
                        </option>
                    @endfor
                </select>
            </div>
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Year</label>
                <select id="reportYear">
                    @for($y = date('Y'); $y >= date('Y') - 2; $y--)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endfor
                </select>
            </div>
        </div>
        <button class="btn-primary" style="width:100%;justify-content:center;" onclick="previewReport('monthly')">
            <i class="fas fa-eye"></i> Preview Monthly Report
        </button>
    </div>
</div>

{{-- REPORT PREVIEW MODAL --}}
<div class="report-overlay" id="reportOverlay">
    <div class="report-modal" id="reportModal">
        <div class="report-modal-header">
            <div class="report-modal-title" id="reportModalTitle">Report Preview</div>
            <button class="report-close-btn" onclick="closeReport()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div id="reportPreviewContent">
            <div class="report-loading">
                <div class="spinner"></div>
                <p>Loading report...</p>
            </div>
        </div>
        <div class="report-download-bar">
            <a id="reportDownloadBtn" href="#" target="_blank" class="btn-primary">
                <i class="fas fa-download"></i> Download PDF
            </a>
            <button class="btn-outline" onclick="closeReport()">Close</button>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
let currentDownloadUrl = '';

async function previewReport(type) {
    let apiUrl   = '';
    let pdfUrl   = '';
    let title    = '';

    if (type === 'today') {
        apiUrl  = '/api/dashboard/today';
        pdfUrl  = '/api/reports/daily';
        title   = "Today's Report — {{ date('d M Y') }}";
    } else if (type === 'yesterday') {
        const y = new Date(); y.setDate(y.getDate() - 1);
        const d = y.toISOString().split('T')[0];
        apiUrl  = '/api/dashboard/today'; // use today endpoint, date filtered server-side
        pdfUrl  = '/api/reports/yesterday';
        title   = "Yesterday's Report";
    } else if (type === 'custom') {
        const date = document.getElementById('customDate').value;
        if (!date) { alert('Please select a date'); return; }
        apiUrl  = '/api/dashboard/today';
        pdfUrl  = '/api/reports/daily?date=' + date;
        title   = 'Report — ' + new Date(date).toLocaleDateString('en-IN', { day:'2-digit', month:'short', year:'numeric' });
    } else if (type === 'monthly') {
        const month = document.getElementById('reportMonth').value;
        const year  = document.getElementById('reportYear').value;
        apiUrl  = '/api/dashboard/monthly?month=' + month + '&year=' + year;
        pdfUrl  = '/api/reports/monthly?month=' + month + '&year=' + year;
        const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        title   = 'Monthly Report — ' + months[month - 1] + ' ' + year;
    }

    currentDownloadUrl = pdfUrl;

    document.getElementById('reportModalTitle').textContent  = title;
    document.getElementById('reportDownloadBtn').href        = pdfUrl;
    document.getElementById('reportPreviewContent').innerHTML = `
        <div class="report-loading">
            <div class="spinner"></div>
            <p>Loading report...</p>
        </div>
    `;

    document.getElementById('reportOverlay').classList.add('open');
    document.body.style.overflow = 'hidden';

    try {
        const res  = await fetch(apiUrl, { credentials: 'same-origin' });
        const data = await res.json();

        if (!data.success) {
            document.getElementById('reportPreviewContent').innerHTML = `
                <div class="report-loading"><p style="color:#C62828;">Failed to load report data.</p></div>
            `;
            return;
        }

        renderReportPreview(data, title, type);
    } catch (err) {
        document.getElementById('reportPreviewContent').innerHTML = `
            <div class="report-loading"><p style="color:#C62828;">Error: ${err.message}</p></div>
        `;
    }
}

function renderReportPreview(data, title, type) {
    const isMonthly = type === 'monthly';

    const totalRevenue  = data.total_revenue  || data.total  || 0;
    const totalOrders   = data.total_orders   || (data.data ? data.data.reduce((s,d) => s + d.orders, 0) : 0);
    const cashRevenue   = data.cash_revenue   || 0;
    const onlineRevenue = data.online_revenue || 0;
    const paidOrders    = data.paid_orders    || 0;
    const unpaidOrders  = data.unpaid_orders  || 0;

    const now     = new Date();
    const dateStr = now.toLocaleDateString('en-IN', { day:'2-digit', month:'short', year:'numeric' })
                  + ', ' + now.toLocaleTimeString('en-IN', { hour:'2-digit', minute:'2-digit' });

    // Daily breakdown rows
    let tableHtml = '';
    if (isMonthly && data.data && data.data.length > 0) {
        tableHtml = `
            <div class="rp-section-title">Daily Breakdown</div>
            <table class="rp-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Orders</th>
                        <th>Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    ${data.data.map(row => `
                        <tr>
                            <td>${new Date(row.date).toLocaleDateString('en-IN', { day:'2-digit', month:'short' })}</td>
                            <td>${row.orders}</td>
                            <td>₹${parseFloat(row.revenue).toFixed(2)}</td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
        `;
    } else if (data.hourly && data.hourly.length > 0) {
        tableHtml = `
            <div class="rp-section-title">Hourly Breakdown</div>
            <table class="rp-table">
                <thead>
                    <tr>
                        <th>Hour</th>
                        <th>Orders</th>
                        <th>Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    ${data.hourly.map(row => {
                        const h = row.hour;
                        const label = h === 0 ? '12 AM' : h < 12 ? h + ' AM' : h === 12 ? '12 PM' : (h-12) + ' PM';
                        return `
                            <tr>
                                <td>${label}</td>
                                <td>${row.orders}</td>
                                <td>₹${parseFloat(row.revenue).toFixed(2)}</td>
                            </tr>
                        `;
                    }).join('')}
                </tbody>
            </table>
        `;
    }

    // Order type breakdown
    const typeBreakdown = (data.dinein_orders !== undefined) ? `
        <div class="rp-section-title" style="margin-top:12px;">Order Types</div>
        <table class="rp-table">
            <thead><tr><th>Type</th><th>Count</th></tr></thead>
            <tbody>
                <tr><td>🪑 Dine In</td><td>${data.dinein_orders || 0}</td></tr>
                <tr><td>📦 Parcel</td><td>${data.parcel_orders || 0}</td></tr>
                <tr><td>🚶 Walk In</td><td>${data.walkin_orders || 0}</td></tr>
            </tbody>
        </table>
    ` : '';

    document.getElementById('reportPreviewContent').innerHTML = `
        <div class="report-preview">
            <div class="rp-head">
                <div class="rp-parlour">South Tiffins</div>
                <div class="rp-report-type">${title}</div>
                <div class="rp-date">Generated: ${dateStr}</div>
            </div>

            <div class="rp-stats">
                <div class="rp-stat">
                    <div class="rp-stat-val">₹${parseFloat(totalRevenue).toFixed(0)}</div>
                    <div class="rp-stat-lbl">Revenue</div>
                </div>
                <div class="rp-stat">
                    <div class="rp-stat-val">${totalOrders}</div>
                    <div class="rp-stat-lbl">Orders</div>
                </div>
                <div class="rp-stat">
                    <div class="rp-stat-val">₹${parseFloat(cashRevenue).toFixed(0)}</div>
                    <div class="rp-stat-lbl">Cash</div>
                </div>
                ${onlineRevenue > 0 ? `
                <div class="rp-stat">
                    <div class="rp-stat-val">₹${parseFloat(onlineRevenue).toFixed(0)}</div>
                    <div class="rp-stat-lbl">Online</div>
                </div>` : ''}
                ${paidOrders > 0 ? `
                <div class="rp-stat">
                    <div class="rp-stat-val">${paidOrders}</div>
                    <div class="rp-stat-lbl">Paid</div>
                </div>` : ''}
                ${unpaidOrders > 0 ? `
                <div class="rp-stat">
                    <div class="rp-stat-val">${unpaidOrders}</div>
                    <div class="rp-stat-lbl">Unpaid</div>
                </div>` : ''}
            </div>

            ${tableHtml}
            ${typeBreakdown}

            <div class="rp-total-row">
                <span class="rp-total-lbl">Total Revenue</span>
                <span class="rp-total-val">₹${parseFloat(totalRevenue).toFixed(2)}</span>
            </div>

            <div class="rp-footer">
                South Tiffins — Authentic | Traditional | Flavors<br>
                Report generated on ${dateStr}
            </div>
        </div>
    `;
}

function closeReport() {
    document.getElementById('reportOverlay').classList.remove('open');
    document.body.style.overflow = '';
}

// Backdrop click
document.getElementById('reportOverlay').addEventListener('click', function(e) {
    if (e.target === this) closeReport();
});

// ESC
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeReport();
});
</script>
@endsection