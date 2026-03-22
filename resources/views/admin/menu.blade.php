@extends('layouts.admin')

@section('title', 'Menu — South Tiffins')
@section('page-title', 'Menu Management')

@section('content')

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;">
    <div style="display:flex;gap:8px;">
        <button class="btn-sm btn-sm-primary" onclick="openAddItem()">
            <i class="fas fa-plus"></i> Add Item
        </button>
        <button class="btn-sm btn-sm-outline" onclick="openAddCategory()">
            <i class="fas fa-folder-plus"></i> Add Category
        </button>
    </div>
</div>

<div id="menuContainer">
    <div style="text-align:center;color:var(--muted);padding:40px;">Loading...</div>
</div>

<div class="modal-overlay" id="itemModal">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-title" id="itemModalTitle">Add Menu Item</div>
            <button class="modal-close" onclick="closeModal('itemModal')">✕</button>
        </div>
        <div class="modal-body">
            <input type="hidden" id="itemId">
            <div class="form-group">
                <label class="form-label">Category</label>
                <select id="itemCategory"></select>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                <div class="form-group">
                    <label class="form-label">Name (English)</label>
                    <input type="text" id="itemNameEn" placeholder="Masala Dosa">
                </div>
                <div class="form-group">
                    <label class="form-label">Name (Telugu)</label>
                    <input type="text" id="itemNameTe" placeholder="మసాలా దోశ">
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                <div class="form-group">
                    <label class="form-label">Description (English)</label>
                    <textarea id="itemDescEn" rows="2" placeholder="Description..."></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Description (Telugu)</label>
                    <textarea id="itemDescTe" rows="2" placeholder="వివరణ..."></textarea>
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                <div class="form-group">
                    <label class="form-label">Price (₹)</label>
                    <input type="number" id="itemPrice" placeholder="80">
                </div>
                <div class="form-group">
                    <label class="form-label">Type</label>
                    <select id="itemIsVeg">
                        <option value="1">Veg</option>
                        <option value="0">Non-Veg</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Image</label>
                <div style="display:flex;gap:8px;margin-bottom:8px;">
                    <input type="text" id="itemImageUrl" placeholder="Paste image URL or search Unsplash">
                    <button class="btn-sm btn-sm-outline" onclick="searchUnsplash()" style="white-space:nowrap;">
                        Search
                    </button>
                </div>
                <div id="unsplashGrid" class="unsplash-grid" style="display:none;"></div>
                <div id="imagePreview" style="margin-top:8px;display:none;">
                    <img id="previewImg" src="" style="width:80px;height:80px;border-radius:8px;object-fit:cover;">
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-sm btn-sm-outline" onclick="closeModal('itemModal')">Cancel</button>
            <button class="btn-sm btn-sm-primary" onclick="saveItem()">Save Item</button>
        </div>
    </div>
</div>

<div class="modal-overlay" id="categoryModal">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-title">Add Category</div>
            <button class="modal-close" onclick="closeModal('categoryModal')">✕</button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label class="form-label">Name (English)</label>
                <input type="text" id="catNameEn" placeholder="Breakfast">
            </div>
            <div class="form-group">
                <label class="form-label">Name (Telugu)</label>
                <input type="text" id="catNameTe" placeholder="అల్పాహారం">
            </div>
            <div class="form-group">
                <label class="form-label">Sort Order</label>
                <input type="number" id="catSortOrder" placeholder="1">
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-sm btn-sm-outline" onclick="closeModal('categoryModal')">Cancel</button>
            <button class="btn-sm btn-sm-primary" onclick="saveCategory()">Save Category</button>
        </div>
    </div>
</div>

<div class="modal-overlay" id="deleteModal">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-title">Delete Item</div>
            <button class="modal-close" onclick="closeModal('deleteModal')">✕</button>
        </div>
        <div class="modal-body">
            <p style="color:var(--muted);">Are you sure you want to delete this item? This cannot be undone.</p>
        </div>
        <div class="modal-footer">
            <button class="btn-sm btn-sm-outline" onclick="closeModal('deleteModal')">Cancel</button>
            <button class="btn-sm btn-sm-danger" onclick="confirmDelete()">Delete</button>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
let categories  = [];
let deleteItemId = null;

async function loadMenu() {
    const res  = await fetch('/api/menu/all');
    const data = await res.json();

    if (!data.success) return;

    categories = data.categories;
    renderMenu(data.categories);
    populateCategorySelect(data.categories);
}

function renderMenu(cats) {
    const container = document.getElementById('menuContainer');

    if (cats.length === 0) {
        container.innerHTML = '<div style="text-align:center;color:var(--muted);padding:40px;">No menu items yet</div>';
        return;
    }

    container.innerHTML = cats.map(cat => `
        <div class="admin-panel" style="margin-bottom:20px;">
            <div class="admin-panel-header">
                <div class="admin-panel-title">${cat.name_en} <span style="font-size:13px;color:var(--muted);font-family:var(--font-body);">(${cat.name_te})</span></div>
                <span style="font-size:12px;color:var(--muted);">${cat.menu_items.length} items</span>
            </div>
            <div style="padding:0;">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Type</th>
                            <th>Available</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${cat.menu_items.map(item => `
                            <tr>
                                <td>
                                    ${item.image_url
                                        ? `<img src="${item.image_url}" style="width:40px;height:40px;border-radius:8px;object-fit:cover;">`
                                        : '<div style="width:40px;height:40px;background:var(--primary-light);border-radius:8px;display:flex;align-items:center;justify-content:center;">🍽️</div>'
                                    }
                                </td>
                                <td>
                                    <div style="font-weight:600;">${item.name_en}</div>
                                    <div style="font-size:11px;color:var(--muted);">${item.name_te}</div>
                                </td>
                                <td style="font-weight:600;color:var(--primary);">₹${item.price}</td>
                                <td>
                                    ${item.is_veg
                                        ? '<span style="color:var(--green);font-size:12px;font-weight:600;">● Veg</span>'
                                        : '<span style="color:#e53935;font-size:12px;font-weight:600;">● Non-Veg</span>'
                                    }
                                </td>
                                <td>
                                    <label class="toggle-switch">
                                        <input type="checkbox" ${item.is_available ? 'checked' : ''} onchange="toggleItem(${item.id})">
                                        <span class="toggle-slider"></span>
                                    </label>
                                </td>
                                <td>
                                    <div style="display:flex;gap:6px;">
                                        <button class="btn-sm btn-sm-outline" onclick="editItem(${JSON.stringify(item).replace(/"/g, '&quot;')})">Edit</button>
                                        <button class="btn-sm btn-sm-danger" onclick="openDelete(${item.id})">Delete</button>
                                    </div>
                                </td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            </div>
        </div>
    `).join('');
}

function populateCategorySelect(cats) {
    const select = document.getElementById('itemCategory');
    select.innerHTML = cats.map(c => `<option value="${c.id}">${c.name_en}</option>`).join('');
}

function openAddItem() {
    document.getElementById('itemId').value        = '';
    document.getElementById('itemModalTitle').textContent = 'Add Menu Item';
    document.getElementById('itemNameEn').value    = '';
    document.getElementById('itemNameTe').value    = '';
    document.getElementById('itemDescEn').value    = '';
    document.getElementById('itemDescTe').value    = '';
    document.getElementById('itemPrice').value     = '';
    document.getElementById('itemIsVeg').value     = '1';
    document.getElementById('itemImageUrl').value  = '';
    document.getElementById('unsplashGrid').style.display = 'none';
    document.getElementById('imagePreview').style.display = 'none';
    document.getElementById('itemModal').classList.add('open');
}

function editItem(item) {
    document.getElementById('itemId').value        = item.id;
    document.getElementById('itemModalTitle').textContent = 'Edit Menu Item';
    document.getElementById('itemCategory').value  = item.category_id;
    document.getElementById('itemNameEn').value    = item.name_en;
    document.getElementById('itemNameTe').value    = item.name_te;
    document.getElementById('itemDescEn').value    = item.description_en || '';
    document.getElementById('itemDescTe').value    = item.description_te || '';
    document.getElementById('itemPrice').value     = item.price;
    document.getElementById('itemIsVeg').value     = item.is_veg ? '1' : '0';
    document.getElementById('itemImageUrl').value  = item.image_url || '';

    if (item.image_url) {
        document.getElementById('previewImg').src          = item.image_url;
        document.getElementById('imagePreview').style.display = 'block';
    }

    document.getElementById('itemModal').classList.add('open');
}

async function saveItem() {
    const id  = document.getElementById('itemId').value;
    const url = id ? '/api/menu/update' : '/api/menu/add';

    const payload = {
        id:             id || undefined,
        category_id:   document.getElementById('itemCategory').value,
        name_en:        document.getElementById('itemNameEn').value,
        name_te:        document.getElementById('itemNameTe').value,
        description_en: document.getElementById('itemDescEn').value,
        description_te: document.getElementById('itemDescTe').value,
        price:          document.getElementById('itemPrice').value,
        is_veg:         document.getElementById('itemIsVeg').value,
        image_url:      document.getElementById('itemImageUrl').value
    };

    const res  = await fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify(payload)
    });

    const data = await res.json();
    if (data.success) { closeModal('itemModal'); loadMenu(); }
    else alert(data.message);
}

function openDelete(id) {
    deleteItemId = id;
    document.getElementById('deleteModal').classList.add('open');
}

async function confirmDelete() {
    const res  = await fetch('/api/menu/delete', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ id: deleteItemId })
    });

    const data = await res.json();
    if (data.success) { closeModal('deleteModal'); loadMenu(); }
}

async function toggleItem(id) {
    await fetch('/api/menu/toggle', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ id })
    });
}

async function searchUnsplash() {
    const query = document.getElementById('itemNameEn').value || 'south indian food';
    const res   = await fetch('/api/unsplash/search?query=' + encodeURIComponent(query));
    const data  = await res.json();

    const grid = document.getElementById('unsplashGrid');
    grid.style.display = 'grid';

    grid.innerHTML = data.images.map(img => `
        <img
            src="${img.thumb}"
            class="unsplash-img"
            alt="${img.alt}"
            onclick="selectUnsplashImage('${img.small}', this)"
        >
    `).join('');
}

function selectUnsplashImage(url, el) {
    document.querySelectorAll('.unsplash-img').forEach(i => i.classList.remove('selected'));
    el.classList.add('selected');
    document.getElementById('itemImageUrl').value  = url;
    document.getElementById('previewImg').src      = url;
    document.getElementById('imagePreview').style.display = 'block';
}

function openAddCategory() {
    document.getElementById('catNameEn').value    = '';
    document.getElementById('catNameTe').value    = '';
    document.getElementById('catSortOrder').value = '';
    document.getElementById('categoryModal').classList.add('open');
}

async function saveCategory() {
    const res  = await fetch('/api/categories/add', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({
            name_en:    document.getElementById('catNameEn').value,
            name_te:    document.getElementById('catNameTe').value,
            sort_order: document.getElementById('catSortOrder').value
        })
    });

    const data = await res.json();
    if (data.success) { closeModal('categoryModal'); loadMenu(); }
    else alert(data.message);
}

function closeModal(id) {
    document.getElementById(id).classList.remove('open');
}

loadMenu();
</script>
@endsection