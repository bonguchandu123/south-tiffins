@extends('layouts.app')

@section('title', 'Admin Login — South Tiffins')

@section('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Oswald:wght@400;500;600;700&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
<style>
:root {
  --amber:     #F5A800;
  --amber2:    #FFB800;
  --orange:    #E85D04;
  --brown:     #3D1C02;
  --brown2:    #5C2E00;
  --brown-mid: #2A1200;
  --cream:     #FFF8ED;
  --white:     #FFFFFF;
  --gray:      #888;
  --red:       #D62828;
  --font-hero: 'Bebas Neue', cursive;
  --font-head: 'Oswald', sans-serif;
  --font-body: 'Nunito', sans-serif;
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

body {
  font-family: var(--font-body) !important;
  background: var(--brown) !important;
  color: var(--cream);
  min-height: 100vh;
  display: flex !important;
  align-items: stretch;
  padding: 0 !important;
  overflow: hidden;
}

/* Food pattern background overlay */
body::before {
  content: '';
  position: fixed; inset: 0;
  background-image: url("data:image/svg+xml,%3Csvg width='70' height='70' viewBox='0 0 70 70' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none'%3E%3Ccircle cx='18' cy='18' r='9' stroke='%23c47a00' stroke-width='1.2' fill='none' opacity='0.1'/%3E%3Ccircle cx='52' cy='52' r='9' stroke='%23c47a00' stroke-width='1.2' fill='none' opacity='0.1'/%3E%3Cpath d='M35 6 L39 16 L31 16 Z' fill='%23c47a00' opacity='0.08'/%3E%3Crect x='44' y='10' width='16' height='11' rx='5.5' stroke='%23c47a00' stroke-width='1.2' fill='none' opacity='0.1'/%3E%3Cpath d='M6 42 Q10 36 14 42 Q18 48 22 42' stroke='%23c47a00' stroke-width='1.2' fill='none' opacity='0.1'/%3E%3C/g%3E%3C/svg%3E");
  pointer-events: none; z-index: 0; opacity: .7;
}

@keyframes slideInLeft  { from{opacity:0;transform:translateX(-32px)} to{opacity:1;transform:translateX(0)} }
@keyframes slideInRight { from{opacity:0;transform:translateX(32px)}  to{opacity:1;transform:translateX(0)} }
@keyframes slideInUp    { from{opacity:0;transform:translateY(28px)}  to{opacity:1;transform:translateY(0)} }
@keyframes fadeUp       { from{opacity:0;transform:translateY(14px)}  to{opacity:1;transform:translateY(0)} }
@keyframes spin         { to{transform:rotate(360deg)} }
@keyframes pulse-ring   { 0%,100%{transform:scale(1);opacity:.6} 50%{transform:scale(1.08);opacity:1} }

/* ══ SPLIT LAYOUT ══ */
.login-split {
  display: flex;
  width: 100%;
  min-height: 100vh;
  position: relative;
  z-index: 1;
}

/* ══════════════════════════════
   LEFT — BRAND / VISUAL PANEL
══════════════════════════════ */
.login-left {
  width: 52%;
  position: relative;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  overflow: hidden;
  padding: 60px 48px;
  animation: slideInLeft .7s cubic-bezier(.25,.46,.45,.94) both;
}

/* Background food image */
.ll-bg {
  position: absolute; inset: 0;
  background-image: url('https://images.unsplash.com/photo-1601050690597-df0568f70950?w=1200&q=85');
  background-size: cover;
  background-position: center;
  filter: brightness(.22) saturate(1.1);
  transition: transform 18s ease;
}
.login-left:hover .ll-bg { transform: scale(1.06); }

/* Amber gradient overlay */
.ll-grad {
  position: absolute; inset: 0;
  background: linear-gradient(145deg,
    rgba(245,168,0,.18) 0%,
    rgba(61,28,2,.55) 45%,
    rgba(30,10,0,.92) 100%
  );
}

/* Right edge accent line */
.ll-edge {
  position: absolute; top: 60px; bottom: 60px; right: 0;
  width: 4px;
  background: linear-gradient(to bottom, transparent, var(--amber), transparent);
  opacity: .45;
}

/* TIFFINS big watermark — amber stroke */
.ll-wm {
  position: absolute;
  bottom: -20px; left: -15px;
  font-family: var(--font-hero);
  font-size: clamp(100px, 16vw, 200px);
  line-height: 1; white-space: nowrap;
  pointer-events: none; user-select: none; z-index: 1;
  color: transparent;
  -webkit-text-stroke: 2px rgba(245,168,0,.09);
  letter-spacing: .04em;
  transform: rotate(-5deg);
}

.ll-content {
  position: relative; z-index: 2;
  text-align: center;
  max-width: 380px; width: 100%;
  display: flex; flex-direction: column; align-items: center;
}

/* ── Eyebrow tag ── */
.ll-eyebrow {
  display: inline-flex; align-items: center; gap: 8px;
  border: 1.5px solid rgba(245,168,0,.4);
  color: rgba(255,255,255,.7);
  font-family: var(--font-head);
  font-size: .7rem; font-weight: 600;
  letter-spacing: .2em; text-transform: uppercase;
  padding: 6px 18px; border-radius: 2px;
  margin-bottom: 24px; background: rgba(245,168,0,.06);
  animation: fadeUp .6s .1s both;
}
.ll-eyebrow::before { content: ''; display: block; width: 16px; height: 1.5px; background: var(--amber); }

/* ── Big typographic hero ── */
.ll-title {
  margin-bottom: 6px;
  animation: fadeUp .7s .2s both;
  line-height: 1;
}
.ll-title .t-top {
  display: block;
  font-family: var(--font-head);
  font-size: clamp(.7rem, 1vw, .82rem);
  font-weight: 500; letter-spacing: .42em; text-transform: uppercase;
  color: rgba(255,255,255,.5); margin-bottom: 4px;
}
.ll-title .t-mid {
  display: block;
  font-family: var(--font-hero);
  font-size: clamp(4rem, 7.5vw, 7rem);
  color: var(--amber);
  letter-spacing: .04em; line-height: .88;
  text-shadow: 0 6px 40px rgba(245,168,0,.2);
}
.ll-title .t-bot {
  display: block;
  font-family: var(--font-head);
  font-size: clamp(.7rem, 1vw, .82rem);
  font-weight: 500; letter-spacing: .42em; text-transform: uppercase;
  color: rgba(255,255,255,.5); margin-top: 4px;
}

/* ── Divider ornament ── */
.ll-ornament {
  display: flex; align-items: center; gap: 12px;
  margin: 22px 0 28px; width: 100%;
  animation: fadeUp .6s .35s both;
}
.ll-ornament::before { content: ''; flex: 1; height: 1px; background: linear-gradient(to right, transparent, rgba(245,168,0,.4)); }
.ll-ornament::after  { content: ''; flex: 1; height: 1px; background: linear-gradient(to left,  transparent, rgba(245,168,0,.4)); }
.ll-ornament-icon { font-size: 1.1rem; flex-shrink: 0; }

/* ── Feature pills ── */
.ll-pills {
  display: flex; flex-direction: column; gap: 10px;
  width: 100%; animation: fadeUp .6s .48s both;
}
.ll-pill {
  display: flex; align-items: center; gap: 14px;
  background: rgba(255,255,255,.04);
  border: 1px solid rgba(255,255,255,.08);
  border-left: 3px solid var(--amber);
  border-radius: 3px; padding: 13px 16px;
  transition: all .3s; cursor: default;
}
.ll-pill:hover {
  background: rgba(245,168,0,.08);
  border-color: rgba(245,168,0,.25);
  border-left-color: var(--amber);
  transform: translateX(6px);
}
.ll-pill-icon {
  width: 38px; height: 38px;
  background: rgba(245,168,0,.12);
  border: 1px solid rgba(245,168,0,.2);
  border-radius: 3px;
  display: flex; align-items: center; justify-content: center;
  font-size: 1rem; flex-shrink: 0;
}
.ll-pill-txt strong {
  display: block; font-family: var(--font-head);
  font-size: .82rem; font-weight: 700; letter-spacing: .04em;
  color: var(--cream); margin-bottom: 1px; text-transform: uppercase;
}
.ll-pill-txt span { font-size: .72rem; color: rgba(255,255,255,.38); font-weight: 400; }

/* ── Stats row ── */
.ll-stats {
  display: flex; margin-top: 20px; width: 100%;
  border: 1px solid rgba(245,168,0,.18);
  border-radius: 3px; overflow: hidden;
  animation: fadeUp .6s .62s both;
  background: rgba(0,0,0,.2);
}
.ll-stat {
  flex: 1; text-align: center; padding: 12px 8px;
  border-right: 1px solid rgba(245,168,0,.12);
}
.ll-stat:last-child { border-right: none; }
.ll-stat-val {
  font-family: var(--font-hero); font-size: 1.6rem;
  color: var(--amber); line-height: 1; margin-bottom: 3px;
  letter-spacing: .04em;
}
.ll-stat-lbl {
  font-family: var(--font-head); font-size: .6rem;
  font-weight: 600; letter-spacing: .12em; text-transform: uppercase;
  color: rgba(255,255,255,.28);
}

/* ══════════════════════════════
   RIGHT — FORM PANEL
══════════════════════════════ */
.login-right {
  flex: 1;
  display: flex; flex-direction: column;
  justify-content: center; align-items: center;
  padding: 60px 52px;
  background: var(--cream);
  position: relative; overflow: hidden;
  animation: slideInRight .7s cubic-bezier(.25,.46,.45,.94) both;
}

/* Subtle amber radial glow bottom-right */
.login-right::before {
  content: ''; position: absolute;
  bottom: -80px; right: -60px;
  width: 420px; height: 420px;
  background: radial-gradient(circle, rgba(245,168,0,.1) 0%, transparent 68%);
  pointer-events: none;
}
/* Top-left accent */
.login-right::after {
  content: ''; position: absolute;
  top: -60px; left: -40px;
  width: 300px; height: 300px;
  background: radial-gradient(circle, rgba(232,93,4,.06) 0%, transparent 68%);
  pointer-events: none;
}

/* Big "TIFFINS" cream watermark */
.rp-wm {
  position: absolute;
  bottom: -10px; right: -14px;
  font-family: var(--font-hero);
  font-size: clamp(80px, 12vw, 160px);
  line-height: 1; white-space: nowrap;
  pointer-events: none; user-select: none; z-index: 0;
  color: transparent;
  -webkit-text-stroke: 2px rgba(61,28,2,.06);
  letter-spacing: .04em;
  transform: rotate(-5deg);
}

/* Top accent bar */
.rp-accent-bar {
  position: absolute; top: 0; left: 0; right: 0;
  height: 4px;
  background: linear-gradient(to right, var(--amber), var(--orange), var(--brown));
}

.login-form-inner {
  width: 100%; max-width: 400px;
  position: relative; z-index: 1;
}

/* Back link */
.login-back {
  display: inline-flex; align-items: center; gap: 8px;
  font-family: var(--font-head); font-size: .72rem; font-weight: 600;
  letter-spacing: .14em; text-transform: uppercase;
  color: rgba(61,28,2,.35); text-decoration: none;
  margin-bottom: 40px; transition: color .25s;
  animation: fadeUp .5s .1s both;
}
.login-back:hover { color: var(--orange); }
.login-back i { font-size: .65rem; }

/* Heading */
.login-heading {
  margin-bottom: 32px;
  animation: fadeUp .6s .2s both;
}
.login-heading-eyebrow {
  font-family: var(--font-head); font-size: .7rem; font-weight: 700;
  letter-spacing: .2em; text-transform: uppercase;
  color: var(--amber); background: rgba(245,168,0,.12);
  padding: 4px 12px; border-radius: 2px;
  display: inline-block; margin-bottom: 12px;
}
.login-heading h1 {
  font-family: var(--font-hero);
  font-size: clamp(2.8rem, 4.5vw, 4rem);
  line-height: .9; color: var(--brown);
  letter-spacing: .04em; margin-bottom: 8px;
}
.login-heading h1 span { color: var(--orange); }
.login-heading p {
  font-size: .87rem; font-weight: 400;
  color: rgba(61,28,2,.45); letter-spacing: .02em;
}

/* Error message */
.error-msg {
  background: rgba(214,40,40,.08);
  border: 1px solid rgba(214,40,40,.25);
  border-left: 3px solid var(--red);
  color: var(--red); padding: 12px 16px; border-radius: 3px;
  font-size: .82rem; margin-bottom: 20px;
  display: flex; align-items: center; gap: 9px;
  font-weight: 600;
}

/* Form groups */
.form-group {
  margin-bottom: 18px;
  animation: fadeUp .6s both;
}
.form-group:nth-child(1){ animation-delay: .3s }
.form-group:nth-child(2){ animation-delay: .38s }

.form-label {
  display: block;
  font-family: var(--font-head); font-size: .7rem; font-weight: 700;
  letter-spacing: .16em; text-transform: uppercase;
  color: rgba(61,28,2,.5); margin-bottom: 8px;
}

.input-wrap { position: relative; }
.input-wrap i.icon-left {
  position: absolute; left: 16px; top: 50%; transform: translateY(-50%);
  color: rgba(61,28,2,.25); font-size: .82rem;
  pointer-events: none; transition: color .3s;
}
.input-wrap:focus-within i.icon-left { color: var(--amber); }

.input-wrap input {
  width: 100%; padding: 0 48px; height: 54px;
  background: #fff !important;
  border: 2px solid rgba(61,28,2,.1) !important;
  border-radius: 3px;
  color: var(--brown) !important;
  font-family: var(--font-body); font-size: .92rem; font-weight: 600;
  outline: none;
  transition: border-color .3s, box-shadow .3s;
  -webkit-appearance: none;
}
.input-wrap input::placeholder { color: rgba(61,28,2,.22); font-weight: 400; }
.input-wrap input:focus {
  border-color: var(--amber) !important;
  box-shadow: 0 0 0 4px rgba(245,168,0,.12) !important;
}
.input-wrap input:-webkit-autofill,
.input-wrap input:-webkit-autofill:hover,
.input-wrap input:-webkit-autofill:focus {
  -webkit-box-shadow: 0 0 0 1000px #fff inset !important;
  -webkit-text-fill-color: var(--brown) !important;
  border-color: rgba(61,28,2,.15) !important;
}

.toggle-pass {
  position: absolute; right: 16px; top: 50%; transform: translateY(-50%);
  color: rgba(61,28,2,.2); font-size: .82rem;
  cursor: pointer; transition: color .25s; z-index: 1;
}
.toggle-pass:hover { color: var(--amber); }

/* Submit button — Burger House style */
.login-btn {
  width: 100%; height: 56px;
  background: var(--brown); color: var(--amber);
  border: none; border-radius: 3px;
  font-family: var(--font-head);
  font-weight: 700; font-size: .92rem;
  letter-spacing: .16em; text-transform: uppercase;
  cursor: pointer;
  display: flex; align-items: center; justify-content: center; gap: 10px;
  position: relative; overflow: hidden;
  transition: color .3s; margin-top: 10px;
  animation: fadeUp .6s .46s both;
  box-shadow: 0 6px 24px rgba(61,28,2,.2);
}
/* Amber fill on hover */
.login-btn::before {
  content: ''; position: absolute; inset: 0;
  background: var(--amber);
  transform: scaleX(0); transform-origin: left;
  transition: transform .4s ease; z-index: 0;
}
.login-btn:hover::before { transform: scaleX(1); }
.login-btn:hover { color: var(--brown); }
.login-btn > * { position: relative; z-index: 1; }
.login-btn:disabled { opacity: .6; cursor: not-allowed; }
.login-btn:disabled::before { display: none; }
.login-btn:active { transform: translateY(1px); }

.spinner {
  width: 18px; height: 18px;
  border: 2px solid rgba(245,168,0,.3);
  border-top-color: var(--amber);
  border-radius: 50%; animation: spin .65s linear infinite; display: none;
}

/* Footer */
.login-footer {
  margin-top: 28px; text-align: center;
  font-size: .7rem; letter-spacing: .08em;
  color: rgba(61,28,2,.3);
  animation: fadeUp .6s .6s both;
  font-family: var(--font-head); font-weight: 600; text-transform: uppercase;
}

/* ══ MOBILE ══ */
@media (max-width: 860px) {
  body { display: block !important; overflow-y: auto; overflow-x: hidden; }
  .login-split { flex-direction: column; min-height: 100vh; }

  .login-left {
    width: 100%; order: -1;
    padding: 44px 24px 32px;
    animation: slideInUp .7s cubic-bezier(.25,.46,.45,.94) both;
  }
  .ll-edge { display: none; }
  .ll-wm { font-size: 80px; }
  .ll-title .t-mid { font-size: 3.6rem; }
  .ll-pills { flex-direction: row; flex-wrap: wrap; gap: 8px; }
  .ll-pill { flex: 1 1 calc(50% - 4px); min-width: 140px; padding: 10px 12px; gap: 10px; }
  .ll-pill-icon { width: 32px; height: 32px; font-size: .85rem; }

  .login-right {
    padding: 36px 24px 48px;
    animation: slideInUp .7s .1s cubic-bezier(.25,.46,.45,.94) both;
  }
  .login-back { margin-bottom: 24px; }
  .login-heading h1 { font-size: 2.8rem; }
}

@media (max-width: 480px) {
  .login-left { padding: 36px 20px 28px; }
  .ll-pills { flex-direction: column; }
  .ll-pill { min-width: unset; }
  .ll-title .t-mid { font-size: 3rem; }
  .login-right { padding: 28px 20px 40px; }
  .login-heading h1 { font-size: 2.4rem; }
}
</style>
@endsection

@section('content')

<div class="login-split">

  {{-- ══ LEFT: BRAND PANEL ══ --}}
  <div class="login-left">
    <div class="ll-bg"></div>
    <div class="ll-grad"></div>
    <div class="ll-edge"></div>
    <div class="ll-wm" aria-hidden="true">TIFFINS</div>

    <div class="ll-content">

      <div class="ll-eyebrow">Authentic South Indian</div>

      {{-- Big Bebas Neue typographic title --}}
      <div class="ll-title">
        <span class="t-top">South Indian</span>
        <span class="t-mid">TIFFIN</span>
        <span class="t-bot">Parlour System</span>
      </div>

      <div class="ll-ornament">
        <span class="ll-ornament-icon">🍽️</span>
      </div>

      <div class="ll-pills">
        <div class="ll-pill">
          <div class="ll-pill-icon">📱</div>
          <div class="ll-pill-txt">
            <strong>QR Table Ordering</strong>
            <span>No app download needed</span>
          </div>
        </div>
        <div class="ll-pill">
          <div class="ll-pill-icon">⚡</div>
          <div class="ll-pill-txt">
            <strong>Live Counter Alerts</strong>
            <span>Orders in under 2 seconds</span>
          </div>
        </div>
        <div class="ll-pill">
          <div class="ll-pill-icon">📊</div>
          <div class="ll-pill-txt">
            <strong>Daily PDF Reports</strong>
            <span>Auto-generated every night</span>
          </div>
        </div>
      </div>

      <div class="ll-stats">
        <div class="ll-stat">
          <div class="ll-stat-val">0%</div>
          <div class="ll-stat-lbl">Commission</div>
        </div>
        <div class="ll-stat">
          <div class="ll-stat-val">&lt;2s</div>
          <div class="ll-stat-lbl">Notification</div>
        </div>
        <div class="ll-stat">
          <div class="ll-stat-val">EN+తె</div>
          <div class="ll-stat-lbl">Bilingual</div>
        </div>
      </div>

    </div>
  </div>

  {{-- ══ RIGHT: FORM ══ --}}
  <div class="login-right">
    <div class="rp-accent-bar"></div>
    <div class="rp-wm" aria-hidden="true">TIFFINS</div>

    <div class="login-form-inner">

      <a href="/" class="login-back">
        <i class="fas fa-arrow-left"></i>
        <span>Back to Home</span>
      </a>

      <div class="login-heading">
        <div class="login-heading-eyebrow">Admin Portal</div>
        <h1>SIGN <span>IN.</span></h1>
        <p>Manage your parlour — menu, orders, reports</p>
      </div>

      @if(session('error'))
      <div class="error-msg">
        <i class="fas fa-exclamation-circle"></i>
        {{ session('error') }}
      </div>
      @endif

      <form method="POST" action="{{ route('login.post') }}" id="loginForm">
        @csrf

        <div class="form-group">
          <label class="form-label">Email Address</label>
          <div class="input-wrap">
            <i class="fas fa-envelope icon-left"></i>
            <input
              type="email"
              name="email"
              placeholder="admin@southtiffins.com"
              value="{{ old('email') }}"
              autocomplete="email"
              required autofocus
            >
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Password</label>
          <div class="input-wrap">
            <i class="fas fa-lock icon-left"></i>
            <input
              type="password"
              name="password"
              id="password"
              placeholder="Enter your password"
              autocomplete="current-password"
              required
            >
            <i class="fas fa-eye toggle-pass" id="togglePass"></i>
          </div>
        </div>

        <button type="submit" class="login-btn" id="loginBtn">
          <div class="spinner" id="spinner"></div>
          <i class="fas fa-sign-in-alt" id="loginIcon"></i>
          <span id="loginText">Sign In</span>
        </button>

      </form>

      <div class="login-footer">
        &copy; {{ date('Y') }} South Tiffins &nbsp;·&nbsp; All rights reserved.
      </div>

    </div>
  </div>

</div>

@endsection

@section('scripts')
<script>
  const togglePass = document.getElementById('togglePass');
  const password   = document.getElementById('password');
  const loginForm  = document.getElementById('loginForm');
  const loginBtn   = document.getElementById('loginBtn');
  const spinner    = document.getElementById('spinner');
  const loginIcon  = document.getElementById('loginIcon');
  const loginText  = document.getElementById('loginText');

  togglePass.addEventListener('click', () => {
    const isPass = password.type === 'password';
    password.type = isPass ? 'text' : 'password';
    togglePass.classList.toggle('fa-eye',      !isPass);
    togglePass.classList.toggle('fa-eye-slash', isPass);
  });

  loginForm.addEventListener('submit', () => {
    loginBtn.disabled       = true;
    spinner.style.display   = 'block';
    loginIcon.style.display = 'none';
    loginText.textContent   = 'Signing in...';
  });
</script>
@endsection