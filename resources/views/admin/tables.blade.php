@extends('layouts.admin')

@section('title', 'Tables — South Tiffins')
@section('page-title', 'Tables & QR Codes')

@section('content')

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;">
    <div style="font-size:14px;color:var(--muted);">Manage tables and download QR codes</div>
    <button class="btn-primary" onclick="openAddTable()" style="padding:10px 20px;font-size:14px;">
        <i class="fas fa-plus"></i> Add Table
    </button>
</div>

<div id="tablesGrid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:16px;">
    <div style="text-align:center;color:var(--muted);padding:40px;grid-column:1/-1;">Loading...</div>
</div>

<div class="modal-overlay" id="addTableModal">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-title">Add New Table</div>
            <button class="modal-close" onclick="closeModal('addTableModal')">✕</button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label class="form-label">Table Number</label>
                <input type="text" id="newTableNumber" placeholder="e.g. 6 or A1">
            </div>
            <div id="addTableError" style="display:none;background:rgba(198,40,40,0.08);border:1px solid rgba(198,40,40,0.2);color:#C62828;padding:10px 14px;border-radius:8px;font-size:13px;margin-top:8px;"></div>
        </div>
        <div class="modal-footer">
            <button class="btn-sm btn-sm-outline" onclick="closeModal('addTableModal')">Cancel</button>
            <button class="btn-sm btn-sm-primary" id="addTableBtn" onclick="addTable()">Add Table</button>
        </div>
    </div>
</div>

<div class="modal-overlay" id="qrModal">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-title" id="qrModalTitle">Table QR Code</div>
            <button class="modal-close" onclick="closeModal('qrModal')">✕</button>
        </div>
        <div class="modal-body" style="text-align:center;">
            <div style="margin-bottom:12px;">
                <img id="qrModalImg" src="" alt="QR Code" style="width:220px;height:220px;border-radius:12px;border:1.5px solid var(--border);">
            </div>
            <div style="font-size:13px;color:var(--muted);margin-bottom:8px;">Scan to order from this table</div>
            <div id="qrModalUrl" style="font-size:11px;color:var(--primary);word-break:break-all;padding:8px;background:var(--primary-light);border-radius:8px;"></div>
        </div>
        <div class="modal-footer">
            <button class="btn-sm btn-sm-outline" onclick="copyQRUrl()">
                <i class="fas fa-copy"></i> Copy Link
            </button>
            <a id="qrDownloadBtn" href="" target="_blank" class="btn-sm btn-sm-primary">
                <i class="fas fa-download"></i> Download QR
            </a>
        </div>
    </div>
</div>

<div class="modal-overlay" id="deleteModal">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-title">Delete Table</div>
            <button class="modal-close" onclick="closeModal('deleteModal')">✕</button>
        </div>
        <div class="modal-body">
            <p style="color:var(--muted);font-size:14px;">Are you sure you want to delete this table? This cannot be undone.</p>
            <div id="deleteTableError" style="display:none;background:rgba(198,40,40,0.08);border:1px solid rgba(198,40,40,0.2);color:#C62828;padding:10px 14px;border-radius:8px;font-size:13px;margin-top:8px;"></div>
        </div>
        <div class="modal-footer">
            <button class="btn-sm btn-sm-outline" onclick="closeModal('deleteModal')">Cancel</button>
            <button class="btn-sm btn-sm-danger" id="confirmDeleteBtn" onclick="confirmDelete()">Delete</button>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
let deleteTableId = null;
let currentQRUrl  = '';

// ── Use local IP so phone can scan and open the menu ──
const BASE_URL = 'http://10.11.210.164:8000';

function getQRImageUrl(menuUrl) {
    return 'https://api.qrserver.com/v1/create-qr-code/?size=220x220&margin=10&data=' + encodeURIComponent(menuUrl);
}

async function loadTables() {
    try {
        const res  = await fetch('/api/tables', {
            credentials: 'same-origin',
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
        });
        const text = await res.text();

        let data;
        try { data = JSON.parse(text); } catch (e) {
            showGridError('Server error. Check console.');
            return;
        }

        const grid = document.getElementById('tablesGrid');

        if (!data.success) {
            showGridError(data.message || 'Failed to load tables');
            return;
        }

        if (!data.tables || data.tables.length === 0) {
            grid.innerHTML = '<div style="text-align:center;color:var(--muted);padding:40px;grid-column:1/-1;">No tables added yet. Click Add Table to get started.</div>';
            return;
        }

        grid.innerHTML = data.tables.map(table => {
            const menuUrl  = BASE_URL + '/menu?table=' + table.id;
            const qrImgUrl = getQRImageUrl(menuUrl);

            return `
                <div class="admin-panel" style="padding:0;overflow:hidden;">
                    <div style="padding:20px;text-align:center;border-bottom:1px solid var(--border);">
                        <div style="font-size:11px;color:var(--muted);letter-spacing:0.06em;text-transform:uppercase;margin-bottom:4px;">Table</div>
                        <div style="font-family:var(--font-display);font-weight:700;font-size:36px;color:var(--dark);">${table.table_number}</div>
                        <div style="margin-top:8px;">
                            <span class="${table.is_active ? 'badge-served' : 'badge-unpaid'}">${table.is_active ? 'Active' : 'Inactive'}</span>
                        </div>
                    </div>
                    <div style="padding:16px;text-align:center;border-bottom:1px solid var(--border);">
                        <img
                            src="${qrImgUrl}"
                            style="width:100px;height:100px;border-radius:8px;border:1px solid var(--border);"
                            alt="QR Table ${table.table_number}"
                            onerror="this.style.display='none'"
                        >
                    </div>
                    <div style="padding:12px;display:flex;gap:8px;flex-wrap:wrap;">
                        <button class="btn-sm btn-sm-outline" style="flex:1;" onclick="viewQR(${table.id}, '${table.table_number}', '${menuUrl}')">
                            <i class="fas fa-qrcode"></i> QR
                        </button>
                        <button class="btn-sm btn-sm-outline" style="flex:1;" onclick="toggleTable(${table.id})">
                            ${table.is_active
                                ? '<i class="fas fa-toggle-on" style="color:var(--green);font-size:16px;"></i>'
                                : '<i class="fas fa-toggle-off" style="color:var(--muted);font-size:16px;"></i>'
                            }
                        </button>
                        <button class="btn-sm btn-sm-danger" onclick="openDelete(${table.id})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            `;
        }).join('');

    } catch (err) {
        console.error('Load tables error:', err);
        showGridError('Failed to load tables: ' + err.message);
    }
}

function showGridError(msg) {
    document.getElementById('tablesGrid').innerHTML =
        `<div style="text-align:center;color:#C62828;padding:40px;grid-column:1/-1;">${msg}</div>`;
}

function viewQR(id, number, menuUrl) {
    currentQRUrl = menuUrl;
    const qrImgUrl = getQRImageUrl(menuUrl);
    document.getElementById('qrModalTitle').textContent = 'Table ' + number + ' — QR Code';
    document.getElementById('qrModalImg').src           = qrImgUrl;
    document.getElementById('qrDownloadBtn').href       = qrImgUrl;
    document.getElementById('qrModalUrl').textContent   = menuUrl;
    document.getElementById('qrModal').classList.add('open');
}

function copyQRUrl() {
    navigator.clipboard.writeText(currentQRUrl).then(() => {
        const btn = document.querySelector('[onclick="copyQRUrl()"]');
        const original = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check"></i> Copied!';
        btn.style.color = 'var(--green)';
        setTimeout(() => { btn.innerHTML = original; btn.style.color = ''; }, 2000);
    });
}

function openAddTable() {
    document.getElementById('newTableNumber').value        = '';
    document.getElementById('addTableError').style.display = 'none';
    document.getElementById('addTableError').textContent   = '';
    document.getElementById('addTableModal').classList.add('open');
    setTimeout(() => document.getElementById('newTableNumber').focus(), 100);
}

function openDelete(id) {
    deleteTableId = id;
    document.getElementById('deleteTableError').style.display = 'none';
    document.getElementById('deleteModal').classList.add('open');
}

function closeModal(id) {
    document.getElementById(id).classList.remove('open');
}

async function addTable() {
    const number  = document.getElementById('newTableNumber').value.trim();
    const errorEl = document.getElementById('addTableError');
    const btn     = document.getElementById('addTableBtn');

    errorEl.style.display = 'none';

    if (!number) {
        errorEl.textContent   = 'Please enter a table number';
        errorEl.style.display = 'block';
        return;
    }

    btn.textContent = 'Adding...';
    btn.disabled    = true;

    try {
        const res  = await fetch('/api/tables/add', {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ table_number: number })
        });

        const text = await res.text();
        let data;
        try { data = JSON.parse(text); } catch (e) {
            errorEl.textContent   = 'Server error: ' + text.substring(0, 150);
            errorEl.style.display = 'block';
            return;
        }

        if (data.success) {
            closeModal('addTableModal');
            loadTables();
        } else {
            errorEl.textContent   = data.message || 'Failed to add table';
            errorEl.style.display = 'block';
        }

    } catch (err) {
        errorEl.textContent   = 'Network error: ' + err.message;
        errorEl.style.display = 'block';
    } finally {
        btn.textContent = 'Add Table';
        btn.disabled    = false;
    }
}

async function toggleTable(id) {
    try {
        const res  = await fetch('/api/tables/toggle', {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ id })
        });
        const data = await res.json();
        if (data.success) { loadTables(); } else { alert(data.message || 'Failed to toggle table'); }
    } catch (err) { console.error('Toggle error:', err); }
}

async function confirmDelete() {
    if (!deleteTableId) return;

    const errorEl = document.getElementById('deleteTableError');
    const btn     = document.getElementById('confirmDeleteBtn');

    btn.textContent       = 'Deleting...';
    btn.disabled          = true;
    errorEl.style.display = 'none';

    try {
        const res  = await fetch('/api/tables/delete', {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ id: deleteTableId })
        });
        const data = await res.json();
        if (data.success) {
            closeModal('deleteModal');
            loadTables();
        } else {
            errorEl.textContent   = data.message || 'Failed to delete table';
            errorEl.style.display = 'block';
        }
    } catch (err) {
        errorEl.textContent   = 'Network error: ' + err.message;
        errorEl.style.display = 'block';
    } finally {
        btn.textContent = 'Delete';
        btn.disabled    = false;
    }
}

document.getElementById('newTableNumber').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') addTable();
});

loadTables();
</script>
@endsection