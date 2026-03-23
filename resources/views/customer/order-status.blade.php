@extends('layouts.app')

@section('title', 'Order Status — South Tiffins')

@section('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,700;0,900;1,700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/customer.css') }}">
<style>
/* ── RESET & BASE ── */
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}

:root{
  --primary:#FF6B35;
  --primary-dark:#E55A26;
  --green:#22C55E;
  --amber:#F59E0B;
  --blue:#3B82F6;
  --dark:#1A0F05;
  --cream:#FDF6ED;
  --muted:#9C7B5E;
  --border:rgba(0,0,0,0.08);
  --font-display:'Playfair Display',Georgia,serif;
  --font-body:'Outfit',sans-serif;
}

body{
  font-family:var(--font-body);
  background:#F7F0E8;
  min-height:100vh;
  color:var(--dark);
}

/* ── PAGE WRAPPER ── */
.os-page{
  min-height:100vh;
  display:flex;
  flex-direction:column;
  max-width:480px;
  margin:0 auto;
  padding:0 0 40px;
  position:relative;
}

/* ── TOP BAR ── */
.os-topbar{
  display:flex;
  align-items:center;
  justify-content:space-between;
  padding:16px 20px;
  background:white;
  border-bottom:1px solid var(--border);
  position:sticky;
  top:0;
  z-index:10;
}
.os-topbar-brand{
  display:flex;
  align-items:center;
  gap:10px;
}
.os-topbar-logo{
  width:36px;height:36px;
  border-radius:10px;
  object-fit:contain;
  background:#FFF0E8;
  padding:2px;
}
.os-topbar-name{
  font-family:var(--font-display);
  font-size:1.1rem;
  font-weight:700;
  font-style:italic;
  color:var(--primary);
}
.os-topbar-sub{
  font-size:.68rem;
  color:var(--muted);
  letter-spacing:.06em;
  text-transform:uppercase;
}

/* Live dot */
.os-live{
  display:flex;
  align-items:center;
  gap:6px;
  font-size:.72rem;
  font-weight:600;
  color:var(--green);
  letter-spacing:.06em;
  text-transform:uppercase;
  background:#F0FDF4;
  border:1px solid rgba(34,197,94,.2);
  padding:5px 12px;
  border-radius:20px;
}
.os-live-dot{
  width:7px;height:7px;
  background:var(--green);
  border-radius:50%;
  animation:livePulse 1.4s ease-in-out infinite;
}
@keyframes livePulse{
  0%,100%{opacity:1;transform:scale(1)}
  50%{opacity:.4;transform:scale(.75)}
}

/* ── HERO ORDER CARD ── */
.os-hero{
  margin:20px 20px 0;
  background:var(--dark);
  border-radius:20px;
  padding:24px;
  position:relative;
  overflow:hidden;
  animation:fadeUp .6s ease both;
}
/* Decorative background circles */
.os-hero::before{
  content:'';
  position:absolute;
  top:-60px;right:-60px;
  width:200px;height:200px;
  background:rgba(255,107,53,.12);
  border-radius:50%;
}
.os-hero::after{
  content:'';
  position:absolute;
  bottom:-40px;left:-40px;
  width:150px;height:150px;
  background:rgba(255,107,53,.07);
  border-radius:50%;
}

.os-order-label{
  font-size:.68rem;
  font-weight:600;
  letter-spacing:.18em;
  text-transform:uppercase;
  color:rgba(253,246,237,.4);
  margin-bottom:4px;
  position:relative;z-index:1;
}
.os-order-number{
  font-family:var(--font-display);
  font-size:2rem;
  font-weight:900;
  color:var(--primary);
  line-height:1;
  margin-bottom:16px;
  position:relative;z-index:1;
}

/* Status badge inside hero */
.os-status-badge{
  display:inline-flex;
  align-items:center;
  gap:8px;
  padding:8px 16px;
  border-radius:30px;
  font-size:.8rem;
  font-weight:700;
  letter-spacing:.04em;
  text-transform:uppercase;
  position:relative;z-index:1;
  transition:all .4s ease;
}
.os-status-badge.status-PENDING   {background:rgba(245,158,11,.15);color:#F59E0B;border:1px solid rgba(245,158,11,.25)}
.os-status-badge.status-PREPARING {background:rgba(59,130,246,.15);color:#3B82F6;border:1px solid rgba(59,130,246,.25)}
.os-status-badge.status-READY     {background:rgba(34,197,94,.15);color:var(--green);border:1px solid rgba(34,197,94,.25)}
.os-status-badge.status-SERVED    {background:rgba(34,197,94,.15);color:var(--green);border:1px solid rgba(34,197,94,.25)}
.os-status-badge.status-PICKEDUP  {background:rgba(34,197,94,.15);color:var(--green);border:1px solid rgba(34,197,94,.25)}

.os-badge-icon{font-size:1rem}

/* Order meta row */
.os-order-meta{
  display:flex;
  gap:16px;
  margin-top:16px;
  position:relative;z-index:1;
}
.os-meta-chip{
  display:flex;
  align-items:center;
  gap:6px;
  background:rgba(253,246,237,.07);
  border:1px solid rgba(253,246,237,.1);
  padding:6px 12px;
  border-radius:20px;
  font-size:.75rem;
  color:rgba(253,246,237,.65);
}
.os-meta-chip i{font-size:.75rem;color:var(--primary)}

/* ── PROGRESS TRACK ── */
.os-progress-wrap{
  margin:16px 20px 0;
  background:white;
  border-radius:20px;
  padding:24px;
  animation:fadeUp .6s .1s ease both;
}
.os-progress-title{
  font-size:.72rem;
  font-weight:700;
  letter-spacing:.14em;
  text-transform:uppercase;
  color:var(--muted);
  margin-bottom:24px;
}

/* Steps */
.os-steps{
  display:flex;
  flex-direction:column;
  gap:0;
}
.os-step{
  display:flex;
  align-items:flex-start;
  gap:16px;
  position:relative;
}
/* Connector line */
.os-step:not(:last-child)::after{
  content:'';
  position:absolute;
  left:19px;
  top:42px;
  width:2px;
  height:calc(100% - 18px);
  background:#EDE8E0;
  transition:background .6s ease;
}
.os-step.done:not(:last-child)::after{
  background:var(--primary);
}
.os-step.active:not(:last-child)::after{
  background:linear-gradient(to bottom, var(--primary), #EDE8E0);
}

/* Step icon */
.os-step-icon{
  width:40px;height:40px;
  border-radius:50%;
  display:flex;align-items:center;justify-content:center;
  font-size:1.1rem;
  flex-shrink:0;
  position:relative;z-index:1;
  transition:all .4s cubic-bezier(.34,1.56,.64,1);
  border:2px solid #EDE8E0;
  background:white;
  color:#C4B5A5;
}
.os-step.done .os-step-icon{
  background:var(--primary);
  border-color:var(--primary);
  color:white;
  font-size:.85rem;
}
.os-step.active .os-step-icon{
  background:white;
  border-color:var(--primary);
  color:var(--primary);
  box-shadow:0 0 0 6px rgba(255,107,53,.1);
  animation:stepPulse 2s ease infinite;
}
@keyframes stepPulse{
  0%,100%{box-shadow:0 0 0 6px rgba(255,107,53,.1)}
  50%{box-shadow:0 0 0 10px rgba(255,107,53,.04)}
}

/* Step body */
.os-step-body{
  flex:1;
  padding:8px 0 28px;
}
.os-step-name{
  font-size:.92rem;
  font-weight:600;
  color:#C4B5A5;
  margin-bottom:3px;
  transition:color .4s;
}
.os-step.done .os-step-name,
.os-step.active .os-step-name{
  color:var(--dark);
}
.os-step-desc{
  font-size:.78rem;
  font-weight:300;
  color:#C4B5A5;
  transition:color .4s;
}
.os-step.active .os-step-desc{
  color:var(--muted);
}
.os-step.done .os-step-desc{
  color:var(--muted);
}

/* Active step time indicator */
.os-step-time{
  display:none;
  font-size:.7rem;
  font-weight:600;
  color:var(--primary);
  margin-top:4px;
  letter-spacing:.04em;
}
.os-step.active .os-step-time{display:block}

/* ── ETA BANNER ── */
.os-eta{
  margin:16px 20px 0;
  border-radius:16px;
  padding:16px 20px;
  display:flex;
  align-items:center;
  gap:14px;
  animation:fadeUp .6s .2s ease both;
  transition:all .4s ease;
}
.os-eta.eta-waiting  {background:#FFF8E7;border:1px solid rgba(245,158,11,.2)}
.os-eta.eta-cooking  {background:#EFF6FF;border:1px solid rgba(59,130,246,.2)}
.os-eta.eta-ready    {background:#F0FDF4;border:1px solid rgba(34,197,94,.2)}
.os-eta.eta-done     {background:#F0FDF4;border:1px solid rgba(34,197,94,.2)}

.os-eta-icon{
  font-size:1.8rem;
  flex-shrink:0;
  animation:etaBounce 2s ease infinite;
}
@keyframes etaBounce{
  0%,100%{transform:translateY(0)}
  50%{transform:translateY(-4px)}
}
.os-eta-text{}
.os-eta-label{
  font-size:.7rem;
  font-weight:600;
  letter-spacing:.1em;
  text-transform:uppercase;
  margin-bottom:3px;
}
.os-eta.eta-waiting  .os-eta-label{color:#B45309}
.os-eta.eta-cooking  .os-eta-label{color:#1D4ED8}
.os-eta.eta-ready    .os-eta-label,
.os-eta.eta-done     .os-eta-label{color:#15803D}

.os-eta-msg{
  font-size:.88rem;
  font-weight:500;
}
.os-eta.eta-waiting  .os-eta-msg{color:#92400E}
.os-eta.eta-cooking  .os-eta-msg{color:#1E40AF}
.os-eta.eta-ready    .os-eta-msg,
.os-eta.eta-done     .os-eta-msg{color:#166534}

/* ── ITEMS CARD ── */
.os-items-card{
  margin:16px 20px 0;
  background:white;
  border-radius:20px;
  padding:20px;
  animation:fadeUp .6s .3s ease both;
}
.os-items-title{
  font-size:.72rem;
  font-weight:700;
  letter-spacing:.14em;
  text-transform:uppercase;
  color:var(--muted);
  margin-bottom:16px;
}
.os-item-row{
  display:flex;
  align-items:center;
  gap:12px;
  padding:10px 0;
  border-bottom:1px solid #F5F0EA;
}
.os-item-row:last-child{border-bottom:none}
.os-item-img{
  width:42px;height:42px;
  border-radius:10px;
  object-fit:cover;
  background:#FFF0E8;
  display:flex;align-items:center;justify-content:center;
  font-size:1.2rem;
  flex-shrink:0;
  overflow:hidden;
}
.os-item-img img{width:42px;height:42px;object-fit:cover;border-radius:10px}
.os-item-name{
  flex:1;
  font-size:.88rem;
  font-weight:500;
  color:var(--dark);
}
.os-item-qty{
  font-size:.75rem;
  color:var(--muted);
  background:#F5F0EA;
  padding:2px 8px;
  border-radius:8px;
  font-weight:500;
}
.os-item-price{
  font-size:.88rem;
  font-weight:700;
  color:var(--primary);
  min-width:48px;
  text-align:right;
}

/* Total row */
.os-total-row{
  display:flex;
  justify-content:space-between;
  align-items:center;
  margin-top:14px;
  padding-top:14px;
  border-top:1.5px dashed #EDE8E0;
}
.os-total-lbl{
  font-size:.8rem;
  font-weight:600;
  color:var(--muted);
  text-transform:uppercase;
  letter-spacing:.08em;
}
.os-total-val{
  font-family:var(--font-display);
  font-size:1.3rem;
  font-weight:900;
  color:var(--primary);
}

/* ── ORDER MORE BTN ── */
.os-more-btn{
  display:flex;
  align-items:center;
  justify-content:center;
  gap:8px;
  margin:16px 20px 0;
  padding:14px;
  background:white;
  border:1.5px solid var(--border);
  border-radius:14px;
  font-size:.85rem;
  font-weight:600;
  color:var(--dark);
  text-decoration:none;
  transition:all .25s;
  animation:fadeUp .6s .4s ease both;
}
.os-more-btn:hover{
  border-color:var(--primary);
  color:var(--primary);
  background:#FFF8F5;
}

/* ── UPDATE FLASH ── */
.os-update-flash{
  position:fixed;
  top:80px;left:50%;
  transform:translateX(-50%) translateY(-20px);
  background:var(--dark);
  color:white;
  padding:10px 20px;
  border-radius:30px;
  font-size:.82rem;
  font-weight:600;
  display:flex;align-items:center;gap:8px;
  opacity:0;
  transition:all .35s cubic-bezier(.34,1.56,.64,1);
  z-index:100;
  white-space:nowrap;
  pointer-events:none;
}
.os-update-flash.show{
  opacity:1;
  transform:translateX(-50%) translateY(0);
}
.os-update-flash-dot{
  width:8px;height:8px;
  background:var(--green);
  border-radius:50%;
}

@keyframes fadeUp{
  from{opacity:0;transform:translateY(16px)}
  to{opacity:1;transform:translateY(0)}
}

/* ── CONFETTI (for served/pickedup) ── */
.os-confetti-wrap{
  position:fixed;inset:0;
  pointer-events:none;
  z-index:200;
  overflow:hidden;
  display:none;
}
.os-confetti-wrap.active{display:block}
.confetti-piece{
  position:absolute;
  width:8px;height:8px;
  border-radius:2px;
  animation:confettiFall linear both;
}
@keyframes confettiFall{
  0%{transform:translateY(-20px) rotate(0deg);opacity:1}
  100%{transform:translateY(100vh) rotate(720deg);opacity:0}
}
</style>
@endsection

@section('content')

<!-- Update flash notification -->
<div class="os-update-flash" id="updateFlash">
  <div class="os-update-flash-dot"></div>
  <span id="updateFlashText">Status updated!</span>
</div>

<!-- Confetti wrapper -->
<div class="os-confetti-wrap" id="confettiWrap"></div>

<div class="os-page">

  <!-- Top bar -->
  <div class="os-topbar">
    <div class="os-topbar-brand">
      <img src="{{ asset('images/logo.png') }}" alt="Logo" class="os-topbar-logo">
      <div>
        <div class="os-topbar-name">South Tiffins</div>
        <div class="os-topbar-sub">Order Tracking</div>
      </div>
    </div>
    <div class="os-live">
      <div class="os-live-dot"></div>
      Live
    </div>
  </div>

  <!-- Hero order card -->
  <div class="os-hero">
    <div class="os-order-label">Order Number</div>
    <div class="os-order-number">{{ $order->order_number }}</div>

    <div class="os-status-badge status-{{ $order->status }}" id="statusBadge">
      <span class="os-badge-icon" id="badgeIcon">
        @php
          $icons = ['PENDING'=>'🕐','PREPARING'=>'👨‍🍳','READY'=>'📦','SERVED'=>'✅','PICKEDUP'=>'✅'];
          echo $icons[$order->status] ?? '🕐';
        @endphp
      </span>
      <span id="badgeText">
        @php
          $labels = ['PENDING'=>'Order Received','PREPARING'=>'Preparing Now','READY'=>'Ready for Pickup','SERVED'=>'Served!','PICKEDUP'=>'Picked Up!'];
          echo $labels[$order->status] ?? $order->status;
        @endphp
      </span>
    </div>

    <div class="os-order-meta">
      <div class="os-meta-chip">
        <i class="fas fa-{{ $order->order_type === 'PARCEL' ? 'box' : 'chair' }}"></i>
        {{ $order->order_type === 'PARCEL' ? 'Parcel' : 'Table '.$order->table?->table_number }}
      </div>
      <div class="os-meta-chip">
        <i class="fas fa-{{ $order->payment_method === 'CASH' ? 'money-bill-wave' : 'credit-card' }}"></i>
        {{ $order->payment_method === 'CASH' ? 'Cash' : 'Online' }}
      </div>
      <div class="os-meta-chip">
        <i class="fas fa-rupee-sign"></i>
        ₹{{ $order->total_amount }}
      </div>
    </div>
  </div>

  <!-- ETA Banner -->
  @php
    $etaConfig = [
      'PENDING'   => ['class'=>'eta-waiting','icon'=>'⏳','label'=>'Waiting','msg'=>'Your order is confirmed, kitchen will start soon'],
      'PREPARING' => ['class'=>'eta-cooking','icon'=>'🍳','label'=>'Cooking Now','msg'=>'Fresh food being prepared for you'],
      'READY'     => ['class'=>'eta-ready',  'icon'=>'🎉','label'=>'Ready!','msg'=>$order->order_type==='PARCEL'?'Come collect your parcel!':'Bringing to your table now!'],
      'SERVED'    => ['class'=>'eta-done',   'icon'=>'😊','label'=>'Enjoy!','msg'=>'Your food has been served. Bon Appétit!'],
      'PICKEDUP'  => ['class'=>'eta-done',   'icon'=>'😊','label'=>'Enjoy!','msg'=>'Order picked up. Enjoy your food!'],
    ];
    $eta = $etaConfig[$order->status] ?? $etaConfig['PENDING'];
  @endphp
  <div class="os-eta {{ $eta['class'] }}" id="etaBanner">
    <div class="os-eta-icon" id="etaIcon">{{ $eta['icon'] }}</div>
    <div class="os-eta-text">
      <div class="os-eta-label" id="etaLabel">{{ $eta['label'] }}</div>
      <div class="os-eta-msg" id="etaMsg">{{ $eta['msg'] }}</div>
    </div>
  </div>

  <!-- Progress steps -->
  <div class="os-progress-wrap">
    <div class="os-progress-title">Order Progress</div>
    <div class="os-steps" id="osSteps">

      @php
        $isParcel = $order->order_type === 'PARCEL';
        $steps = $isParcel ? [
          ['key'=>'PENDING',   'icon'=>'🕐', 'name'=>'Order Received',   'desc'=>'Your order has been confirmed'],
          ['key'=>'PREPARING', 'icon'=>'👨‍🍳', 'name'=>'Preparing',        'desc'=>'Kitchen is cooking your food'],
          ['key'=>'READY',     'icon'=>'📦', 'name'=>'Ready for Pickup',  'desc'=>'Your parcel is packed and waiting'],
          ['key'=>'PICKEDUP',  'icon'=>'✓',  'name'=>'Picked Up',         'desc'=>'Enjoy your food!'],
        ] : [
          ['key'=>'PENDING',   'icon'=>'🕐', 'name'=>'Order Received',   'desc'=>'Your order has been confirmed'],
          ['key'=>'PREPARING', 'icon'=>'👨‍🍳', 'name'=>'Preparing',        'desc'=>'Kitchen is cooking your food'],
          ['key'=>'SERVED',    'icon'=>'✓',  'name'=>'Served',            'desc'=>'Enjoy your meal!'],
        ];
        $order_status_map = ['PENDING'=>0,'PREPARING'=>1,'READY'=>2,'SERVED'=>2,'PICKEDUP'=>3];
        $currentIdx = $order_status_map[$order->status] ?? 0;
      @endphp

      @foreach($steps as $i => $step)
        @php
          $isDone   = $i < $currentIdx;
          $isActive = $i === $currentIdx;
          $cls      = $isDone ? 'done' : ($isActive ? 'active' : '');
        @endphp
        <div class="os-step {{ $cls }}" id="step-{{ $i }}">
          <div class="os-step-icon">
            @if($isDone)✓@else{{ $step['icon'] }}@endif
          </div>
          <div class="os-step-body">
            <div class="os-step-name">{{ $step['name'] }}</div>
            <div class="os-step-desc">{{ $step['desc'] }}</div>
            <div class="os-step-time" id="stepTime-{{ $i }}">Just now</div>
          </div>
        </div>
      @endforeach

    </div>
  </div>

  <!-- Items -->
  <div class="os-items-card">
    <div class="os-items-title">Your Items</div>

    @foreach($order->items as $item)
    <div class="os-item-row">
      <div class="os-item-img">
        @if($item->image_url)
          <img src="{{ $item->image_url }}" alt="{{ $item->name_en }}">
        @else
          🍽️
        @endif
      </div>
      <div class="os-item-name">{{ $item->name_en }}</div>
      <div class="os-item-qty">×{{ $item->quantity }}</div>
      <div class="os-item-price">₹{{ $item->subtotal }}</div>
    </div>
    @endforeach

    <div class="os-total-row">
      <div class="os-total-lbl">Total</div>
      <div class="os-total-val">₹{{ $order->total_amount }}</div>
    </div>
  </div>

  <!-- Order more -->
  <a href="/menu{{ $order->table_id ? '?table='.$order->table_id : '/parcel' }}" class="os-more-btn">
    <i class="fas fa-plus-circle"></i>
    Order More Items
  </a>

</div>
@endsection

@section('scripts')
<script>
// ── CONFIG ──────────────────────────────
const ORDER_ID      = {{ $order->id }};
const IS_PARCEL     = {{ $order->order_type === 'PARCEL' ? 'true' : 'false' }};
const POLL_INTERVAL = 4000; // poll every 4 seconds

// ── STATUS CONFIG ────────────────────────
const STATUS_CONFIG = {
  PENDING:   { badge:'status-PENDING',   icon:'🕐', label:'Order Received',   etaClass:'eta-waiting', etaIcon:'⏳', etaLabel:'Waiting',     etaMsg:'Your order is confirmed, kitchen will start soon', stepIdx:0 },
  PREPARING: { badge:'status-PREPARING', icon:'👨‍🍳', label:'Preparing Now',    etaClass:'eta-cooking', etaIcon:'🍳', etaLabel:'Cooking Now',  etaMsg:'Fresh food being prepared for you',                stepIdx:1 },
  READY:     { badge:'status-READY',     icon:'📦', label:'Ready for Pickup', etaClass:'eta-ready',   etaIcon:'🎉', etaLabel:'Ready!',        etaMsg:'Come collect your parcel!',                        stepIdx:2 },
  SERVED:    { badge:'status-SERVED',    icon:'✅', label:'Served!',          etaClass:'eta-done',    etaIcon:'😊', etaLabel:'Enjoy!',        etaMsg:'Your food has been served. Bon Appétit!',           stepIdx:2 },
  PICKEDUP:  { badge:'status-PICKEDUP',  icon:'✅', label:'Picked Up!',       etaClass:'eta-done',    etaIcon:'😊', etaLabel:'Enjoy!',        etaMsg:'Order picked up. Enjoy your food!',                 stepIdx:3 },
};

const DINE_STEPS    = ['PENDING','PREPARING','SERVED'];
const PARCEL_STEPS  = ['PENDING','PREPARING','READY','PICKEDUP'];
const TERMINAL_STATUSES = ['SERVED','PICKEDUP','CANCELLED'];

let currentStatus = '{{ $order->status }}';
let pollTimer     = null;
let confettiFired = false;

// ── POLLING ──────────────────────────────
async function pollStatus() {
  try {
    const res  = await fetch('/api/orders/get?id=' + ORDER_ID, { cache:'no-store' });
    const data = await res.json();
    if (!data.success) return;

    const newStatus = data.order.status;

    if (newStatus !== currentStatus) {
      updateUI(newStatus, true);
      currentStatus = newStatus;

      // Stop polling on terminal status
      if (TERMINAL_STATUSES.includes(newStatus)) {
        clearInterval(pollTimer);
      }
    }
  } catch(e) {
    console.warn('Poll error:', e);
  }
}

// ── UPDATE UI ────────────────────────────
function updateUI(status, animate) {
  const cfg   = STATUS_CONFIG[status];
  if (!cfg) return;

  // 1. Badge
  const badge = document.getElementById('statusBadge');
  badge.className = 'os-status-badge ' + cfg.badge;
  document.getElementById('badgeIcon').textContent = cfg.icon;
  document.getElementById('badgeText').textContent = cfg.label;

  // 2. ETA banner
  const eta = document.getElementById('etaBanner');
  eta.className = 'os-eta ' + cfg.etaClass;
  document.getElementById('etaIcon').textContent   = cfg.etaIcon;
  document.getElementById('etaLabel').textContent  = cfg.etaLabel;
  document.getElementById('etaMsg').textContent    = cfg.etaMsg;

  // 3. Steps
  const allSteps = IS_PARCEL ? PARCEL_STEPS : DINE_STEPS;
  const activeIdx = cfg.stepIdx;

  allSteps.forEach((s, i) => {
    const el = document.getElementById('step-' + i);
    if (!el) return;
    el.classList.remove('done','active');
    const iconEl = el.querySelector('.os-step-icon');
    const timeEl = document.getElementById('stepTime-' + i);

    if (i < activeIdx) {
      el.classList.add('done');
      iconEl.textContent = '✓';
    } else if (i === activeIdx) {
      el.classList.add('active');
      // Restore emoji icon
      const emojis = { PENDING:'🕐', PREPARING:'👨‍🍳', READY:'📦', SERVED:'✓', PICKEDUP:'✓' };
      iconEl.textContent = emojis[s] || '•';
      if (timeEl) timeEl.textContent = 'Just now';
    } else {
      // Restore emoji for upcoming
      const emojis = { PENDING:'🕐', PREPARING:'👨‍🍳', READY:'📦', SERVED:'✓', PICKEDUP:'✓' };
      iconEl.textContent = emojis[s] || '•';
    }
  });

  // 4. Flash notification
  if (animate) {
    showFlash('Status updated — ' + cfg.label);
  }

  // 5. Confetti on completion
  if (['SERVED','PICKEDUP'].includes(status) && !confettiFired) {
    confettiFired = true;
    fireConfetti();
  }
}

// ── FLASH NOTIFICATION ───────────────────
function showFlash(msg) {
  const el = document.getElementById('updateFlash');
  document.getElementById('updateFlashText').textContent = msg;
  el.classList.add('show');
  setTimeout(() => el.classList.remove('show'), 3000);
}

// ── CONFETTI ─────────────────────────────
function fireConfetti() {
  const wrap   = document.getElementById('confettiWrap');
  const colors = ['#FF6B35','#F59E0B','#22C55E','#3B82F6','#EC4899','#FDE68A'];
  wrap.classList.add('active');

  for (let i = 0; i < 60; i++) {
    const piece = document.createElement('div');
    piece.className = 'confetti-piece';
    piece.style.cssText = `
      left:${Math.random()*100}%;
      background:${colors[Math.floor(Math.random()*colors.length)]};
      animation-duration:${1.5+Math.random()*2}s;
      animation-delay:${Math.random()*0.8}s;
      width:${6+Math.random()*6}px;
      height:${6+Math.random()*6}px;
      border-radius:${Math.random()>0.5?'50%':'2px'};
    `;
    wrap.appendChild(piece);
  }

  setTimeout(() => {
    wrap.classList.remove('active');
    wrap.innerHTML = '';
  }, 4000);
}

// ── INIT ─────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
  // Fire confetti if already served
  if (['SERVED','PICKEDUP'].includes(currentStatus)) {
    setTimeout(fireConfetti, 600);
  }

  // Start polling if not terminal
  if (!TERMINAL_STATUSES.includes(currentStatus)) {
    pollTimer = setInterval(pollStatus, POLL_INTERVAL);
  }
});
</script>
@endsection