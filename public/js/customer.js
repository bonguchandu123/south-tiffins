let cart          = [];
let lang          = 'en';
let menuData      = [];
let currentItem   = null;
let detailQty     = 1;
let orderType     = document.querySelector('.order-type-btn.active')?.dataset.type || 'DINEIN';
let paymentMethod = 'CASH';
let stripeInstance = null;
let cardElement    = null;
let stripeClientSecret = null;
let pendingOrderPayload = null;

const tableId     = document.getElementById('tableId').value;
const tableNumber = document.getElementById('tableNumber').value;

// Init Stripe
const stripeKey = document.getElementById('stripePublishableKey').value;
if (stripeKey) {
    stripeInstance = Stripe(stripeKey);
    const elements = stripeInstance.elements();
    cardElement = elements.create('card', {
        style: {
            base: {
                fontFamily: "'Outfit', sans-serif",
                fontSize: '15px',
                color: '#1A1A1A',
                '::placeholder': { color: '#AAAAAA' }
            },
            invalid: { color: '#C62828' }
        }
    });
    cardElement.mount('#card-element');
}

async function loadMenu() {
    try {
        const res  = await fetch('/api/menu');
        const data = await res.json();
        if (!data.success) return;
        menuData = data.categories;
        renderCategoryTabs(data.categories);
        renderSidebarTabs(data.categories);
        renderMenu(data.categories);
        initScrollSpy();
    } catch (err) {
        console.error('Menu load error:', err);
    }
}

function renderCategoryTabs(categories) {
    const tabs = document.getElementById('categoryTabs');
    tabs.innerHTML = '';
    categories.forEach((cat, index) => {
        const btn       = document.createElement('button');
        btn.className   = 'cat-tab' + (index === 0 ? ' active' : '');
        btn.textContent = lang === 'en' ? cat.name_en : cat.name_te;
        btn.onclick     = () => scrollToCategory(cat.id, btn, 'tab');
        tabs.appendChild(btn);
    });
}

function renderSidebarTabs(categories) {
    const sidebar = document.getElementById('sidebarTabs');
    if (!sidebar) return;
    sidebar.innerHTML = '';
    categories.forEach((cat, index) => {
        const btn         = document.createElement('button');
        btn.className     = 'sidebar-tab' + (index === 0 ? ' active' : '');
        btn.dataset.catId = cat.id;
        btn.innerHTML     = `${lang === 'en' ? cat.name_en : cat.name_te}<span class="sidebar-tab-count">${cat.menu_items.length}</span>`;
        btn.onclick       = () => scrollToCategory(cat.id, btn, 'sidebar');
        sidebar.appendChild(btn);
    });
}

function renderMenu(categories) {
    const body = document.getElementById('menuBody');
    body.innerHTML = '';
    categories.forEach(cat => {
        const section     = document.createElement('div');
        section.className = 'category-section';
        section.id        = 'cat-' + cat.id;

        const title       = document.createElement('div');
        title.className   = 'category-section-title';
        title.textContent = lang === 'en' ? cat.name_en : cat.name_te;
        section.appendChild(title);

        const grid        = document.createElement('div');
        grid.className    = 'menu-grid';
        cat.menu_items.forEach((item, index) => grid.appendChild(buildMenuCard(item, index)));
        section.appendChild(grid);
        body.appendChild(section);
    });
}

function buildMenuCard(item, index) {
    const card          = document.createElement('div');
    card.className      = 'menu-card' + (!item.is_available ? ' menu-unavailable' : '');
    card.style.animationDelay = (index * 0.04) + 's';
    card.onclick        = () => openItemDetail(item);

    const name    = lang === 'en' ? item.name_en : item.name_te;
    const desc    = lang === 'en' ? (item.description_en || '') : (item.description_te || '');
    const vegDot  = item.is_veg ? `<div class="veg-dot"></div>` : `<div class="nonveg-dot"></div>`;
    const imgHtml = item.image_url ? `<img src="${item.image_url}" alt="${name}" loading="lazy">` : '🍽️';

    card.innerHTML = `
        <div class="menu-card-img">${imgHtml}</div>
        <div class="menu-card-body">
            <div class="menu-card-top">${vegDot}<div class="menu-card-name">${name}</div></div>
            <div class="menu-card-price">₹${item.price}</div>
            ${desc ? `<div class="menu-card-desc">${desc}</div>` : ''}
            <button class="menu-card-add" onclick="quickAdd(event,${item.id})">+ Add</button>
        </div>
    `;
    return card;
}

function quickAdd(e, itemId) {
    e.stopPropagation();
    const item = findItem(itemId);
    if (item) addToCart(item, 1);
}

function openItemDetail(item) {
    currentItem = item;
    detailQty   = 1;

    const imgWrap = document.getElementById('itemDetailImgWrap');
    const name    = lang === 'en' ? item.name_en : item.name_te;
    const desc    = lang === 'en' ? (item.description_en || '') : (item.description_te || '');

    imgWrap.innerHTML = item.image_url ? `<img src="${item.image_url}" alt="${name}">` : '🍽️';

    document.getElementById('itemDetailVeg').innerHTML    = item.is_veg ? `<div class="veg-dot"></div>` : `<div class="nonveg-dot"></div>`;
    document.getElementById('itemDetailName').textContent  = name;
    document.getElementById('itemDetailPrice').textContent = `₹${item.price}`;
    document.getElementById('itemDetailDesc').textContent  = desc;
    document.getElementById('detailQtyNum').textContent    = detailQty;
    document.getElementById('detailAddPrice').textContent  = `₹${item.price * detailQty}`;

    document.getElementById('itemDetailOverlay').classList.add('open');
    document.body.style.overflow = 'hidden';
}

function closeItemDetail(e) {
    if (e && e.target !== document.getElementById('itemDetailOverlay')) return;
    document.getElementById('itemDetailOverlay').classList.remove('open');
    document.body.style.overflow = '';
}

function changeDetailQty(delta) {
    detailQty = Math.max(1, detailQty + delta);
    document.getElementById('detailQtyNum').textContent   = detailQty;
    document.getElementById('detailAddPrice').textContent = `₹${currentItem.price * detailQty}`;
}

function addFromDetail() {
    if (!currentItem) return;
    addToCart(currentItem, detailQty);
    document.getElementById('itemDetailOverlay').classList.remove('open');
    document.body.style.overflow = '';
}

function addToCart(item, qty) {
    const existing = cart.find(c => c.id === item.id);
    if (existing) {
        existing.quantity += qty;
    } else {
        cart.push({ id: item.id, name_en: item.name_en, name_te: item.name_te, price: parseFloat(item.price), image_url: item.image_url, quantity: qty });
    }
    updateCartBar();
}

function removeFromCart(itemId) {
    const existing = cart.find(c => c.id === itemId);
    if (!existing) return;
    if (existing.quantity > 1) { existing.quantity--; } else { cart = cart.filter(c => c.id !== itemId); }
    updateCartBar();
    renderCartItems();
}

function updateCartBar() {
    const total   = cart.reduce((sum, c) => sum + c.price * c.quantity, 0);
    const count   = cart.reduce((sum, c) => sum + c.quantity, 0);
    const cartBar = document.getElementById('cartBar');
    cartBar.style.display = cart.length > 0 ? 'flex' : 'none';
    document.getElementById('cartCountBadge').textContent = count;
    document.getElementById('cartItemsLabel').textContent  = count + ' item' + (count > 1 ? 's' : '');
    document.getElementById('cartTotal').textContent       = '₹' + total;
    document.getElementById('cartTotalFinal').textContent  = '₹' + total;
}

function openCart() {
    renderCartItems();
    document.getElementById('cartOverlay').classList.add('open');
    document.body.style.overflow = 'hidden';
}

function closeCart(e) {
    if (e && e.target !== document.getElementById('cartOverlay')) return;
    document.getElementById('cartOverlay').classList.remove('open');
    document.body.style.overflow = '';
}

function renderCartItems() {
    const container = document.getElementById('cartItems');
    container.innerHTML = '';
    if (cart.length === 0) {
        container.innerHTML = '<div style="text-align:center;padding:40px;color:var(--muted);font-size:14px;">Your cart is empty</div>';
        return;
    }
    cart.forEach(item => {
        const name = lang === 'en' ? item.name_en : item.name_te;
        const div  = document.createElement('div');
        div.className = 'cart-item';
        div.innerHTML = `
            <div class="cart-item-img">${item.image_url ? `<img src="${item.image_url}" alt="${name}">` : '🍽️'}</div>
            <div class="cart-item-info">
                <div class="cart-item-name">${name}</div>
                <div class="cart-item-price">₹${(item.price * item.quantity).toFixed(0)}</div>
            </div>
            <div class="cart-item-qty">
                <button onclick="removeFromCart(${item.id})">−</button>
                <span>${item.quantity}</span>
                <button onclick="addToCart({id:${item.id},name_en:'${item.name_en}',name_te:'${item.name_te}',price:${item.price},image_url:'${item.image_url || ''}'},1)">+</button>
            </div>
        `;
        container.appendChild(div);
    });
    updateCartBar();
}

function selectOrderType(type, btn) {
    orderType = type;
    document.querySelectorAll('.order-type-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('customerDetails').style.display = type === 'PARCEL' ? 'flex' : 'none';
}

function selectPayment(method, btn) {
    paymentMethod = method;
    document.querySelectorAll('.payment-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
}

// ══ PLACE ORDER — forks based on payment method ══
async function placeOrder() {
    if (cart.length === 0) { alert('Please add items to cart'); return; }

    const customerName  = document.getElementById('customerName')?.value.trim();
    const customerPhone = document.getElementById('customerPhone')?.value.trim();

    if (orderType === 'PARCEL' && !customerName)  { alert('Please enter your name'); return; }
    if (orderType === 'PARCEL' && !customerPhone) { alert('Please enter your phone number'); return; }

    // Build the order payload — used by both cash and online
    pendingOrderPayload = {
        items:          cart,
        order_type:     orderType,
        order_source:   tableId ? 'QR_SCAN' : 'ONLINE',
        payment_method: paymentMethod,
        table_id:       tableId || null,
        customer_name:  customerName  || null,
        customer_phone: customerPhone || null
    };

    if (paymentMethod === 'ONLINE') {
        // Close cart and open Stripe sheet
        document.getElementById('cartOverlay').classList.remove('open');
        await openStripeSheet();
    } else {
        // Cash — place order directly
        await submitOrder(pendingOrderPayload);
    }
}

// ══ STRIPE FLOW ══
async function openStripeSheet() {
    const total = cart.reduce((sum, c) => sum + c.price * c.quantity, 0);
    document.getElementById('stripeAmount').textContent  = '₹' + total;
    document.getElementById('stripePayBtnText').textContent = 'Pay ₹' + total;
    document.getElementById('card-error').style.display = 'none';
    document.getElementById('stripeOverlay').classList.add('open');
    document.body.style.overflow = 'hidden';

    // Create payment intent
    try {
        const res  = await fetch('/api/payments/create-intent', {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ amount: total })
        });
        const data = await res.json();
        if (data.success) {
            stripeClientSecret = data.client_secret;
        } else {
            showCardError(data.message || 'Failed to initialize payment');
        }
    } catch (err) {
        showCardError('Network error. Please try again.');
    }
}

function closeStripeSheet() {
    document.getElementById('stripeOverlay').classList.remove('open');
    document.body.style.overflow = '';
    stripeClientSecret  = null;
    pendingOrderPayload = null;
}

async function confirmStripePayment() {
    if (!stripeClientSecret) { showCardError('Payment not ready. Please try again.'); return; }

    const btn     = document.getElementById('stripePayBtn');
    const btnText = document.getElementById('stripePayBtnText');
    btn.disabled  = true;
    btnText.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';

    const { paymentIntent, error } = await stripeInstance.confirmCardPayment(stripeClientSecret, {
        payment_method: { card: cardElement }
    });

    if (error) {
        showCardError(error.message);
        btn.disabled  = false;
        const total   = cart.reduce((sum, c) => sum + c.price * c.quantity, 0);
        btnText.textContent = 'Pay ₹' + total;
        return;
    }

    if (paymentIntent.status === 'succeeded') {
        // Payment done — now place the order
        btnText.innerHTML = '<i class="fas fa-check"></i> Payment Successful! Placing Order...';
        pendingOrderPayload.stripe_payment_intent_id = paymentIntent.id;
        await submitOrder(pendingOrderPayload);
    }
}

// ══ SUBMIT ORDER (both cash and online after payment) ══
async function submitOrder(payload) {
    const btn     = document.getElementById('placeOrderBtn');
    btn.disabled  = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Placing Order...';

    try {
        const res  = await fetch('/api/orders/place', {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(payload)
        });
        const data = await res.json();

        if (data.success) {
            cart = [];
            updateCartBar();
            document.getElementById('stripeOverlay').classList.remove('open');
            window.location.href = '/menu/order-confirm?order_id=' + data.order_id;
        } else {
            alert(data.message || 'Failed to place order');
            btn.disabled  = false;
            btn.innerHTML = '<i class="fas fa-check-circle"></i> Place Order';
            const stripeBtn = document.getElementById('stripePayBtn');
            if (stripeBtn) {
                stripeBtn.disabled = false;
                const total = cart.reduce((sum, c) => sum + c.price * c.quantity, 0);
                document.getElementById('stripePayBtnText').textContent = 'Pay ₹' + total;
            }
        }
    } catch (err) {
        console.error(err);
        btn.disabled  = false;
        btn.innerHTML = '<i class="fas fa-check-circle"></i> Place Order';
    }
}

function showCardError(msg) {
    const el         = document.getElementById('card-error');
    el.textContent   = msg;
    el.style.display = 'block';
}

// ══ UTILS ══
function scrollToCategory(catId, activeBtn, source) {
    const section = document.getElementById('cat-' + catId);
    if (!section) return;
    section.scrollIntoView({ behavior: 'smooth', block: 'start' });
    if (source === 'tab') {
        document.querySelectorAll('.cat-tab').forEach(t => t.classList.remove('active'));
    } else {
        document.querySelectorAll('.sidebar-tab').forEach(t => t.classList.remove('active'));
    }
    activeBtn.classList.add('active');
}

function initScrollSpy() {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const catId = entry.target.id.replace('cat-', '');
                document.querySelectorAll('.sidebar-tab').forEach(t => {
                    t.classList.toggle('active', t.dataset.catId == catId);
                });
                document.querySelectorAll('.cat-tab').forEach((t, i) => {
                    t.classList.toggle('active', menuData[i]?.id == catId);
                });
            }
        });
    }, { threshold: 0.4 });
    document.querySelectorAll('.category-section').forEach(s => observer.observe(s));
}

function toggleLanguage() {
    lang = lang === 'en' ? 'te' : 'en';
    document.getElementById('langLabel').textContent = lang === 'en' ? 'తె' : 'EN';
    renderCategoryTabs(menuData);
    renderSidebarTabs(menuData);
    renderMenu(menuData);
    renderCartItems();
}

function findItem(itemId) {
    for (const cat of menuData) {
        const item = cat.menu_items.find(i => i.id === itemId);
        if (item) return item;
    }
    return null;
}

loadMenu();