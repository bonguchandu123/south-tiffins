@extends('layouts.app')

@section('title', 'Admin Login — South Tiffins')

@section('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;1,300;1,600&family=Outfit:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,700;0,900;1,700;1,900&display=swap" rel="stylesheet">
<style>
:root {
  --burnt:   #C1440E;
  --saffron: #F4A135;
  --cream:   #FDF6ED;
  --dark:    #1A0F05;
  --dark2:   #2D1C0D;
  --border:  rgba(253,246,237,0.1);
  --font-display: 'Playfair Display', Georgia, serif;
  --font-body:    'Outfit', sans-serif;
}
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

body {
  font-family: var(--font-body) !important;
  background: var(--dark) !important;
  color: var(--cream);
  min-height: 100vh;
  display: flex !important;
  align-items: stretch;
  padding: 0 !important;
}

/* Noise texture */
body::before {
  content: ''; position: fixed; inset: 0;
  background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='.04'/%3E%3C/svg%3E");
  pointer-events: none; z-index: 9997; opacity: .28;
}

@keyframes slideInLeft  { from{opacity:0;transform:translateX(-28px)} to{opacity:1;transform:translateX(0)} }
@keyframes slideInRight { from{opacity:0;transform:translateX(28px)}  to{opacity:1;transform:translateX(0)} }
@keyframes slideInUp    { from{opacity:0;transform:translateY(28px)}  to{opacity:1;transform:translateY(0)} }
@keyframes fadeUp       { from{opacity:0;transform:translateY(14px)}  to{opacity:1;transform:translateY(0)} }
@keyframes spin         { to{transform:rotate(360deg)} }

/* ══ SPLIT ══ */
.login-split {
  display: flex;
  width: 100%;
  min-height: 100vh;
}

/* ══════════════════════
   LEFT — FORM
══════════════════════ */
.login-left {
  flex: 1;
  display: flex; flex-direction: column;
  justify-content: center; align-items: center;
  padding: 60px 52px;
  background: var(--dark);
  position: relative; overflow: hidden;
  animation: slideInLeft .7s cubic-bezier(.25,.46,.45,.94) both;
}

/* Radial glow */
.login-left::before {
  content: ''; position: absolute;
  bottom: -120px; left: -80px;
  width: 500px; height: 500px;
  background: radial-gradient(circle, rgba(193,68,14,.09) 0%, transparent 68%);
  pointer-events: none; z-index: 0;
}

/* Big "Tiffins" watermark behind form — cream stroke only */
.wm-text {
  position: absolute;
  bottom: -10px; left: -10px;
  font-family: var(--font-display);
  font-weight: 900; font-style: italic;
  font-size: clamp(80px, 14vw, 160px);
  line-height: 1; white-space: nowrap;
  pointer-events: none; user-select: none; z-index: 0;
  color: transparent;
  -webkit-text-stroke: 1.5px rgba(253,246,237,0.07);
  letter-spacing: -.02em;
  transform: rotate(-6deg);
}

.login-form-inner { width: 100%; max-width: 400px; position: relative; z-index: 1; }

.login-back {
  display: inline-flex; align-items: center; gap: 8px;
  font-size: .75rem; font-weight: 500; letter-spacing: .12em; text-transform: uppercase;
  color: rgba(253,246,237,.32); text-decoration: none;
  margin-bottom: 44px; transition: color .25s;
  animation: fadeUp .6s .1s both;
}
.login-back:hover { color: var(--saffron); }

.login-heading { margin-bottom: 36px; animation: fadeUp .6s .2s both; }
.login-heading h1 {
  font-family: var(--font-display);
  font-size: clamp(2rem,4vw,2.8rem); font-weight: 900; font-style: italic;
  color: var(--cream); letter-spacing: -.03em; line-height: .95; margin-bottom: 10px;
}
.login-heading h1 span { color: var(--saffron); }
.login-heading p { font-size: .85rem; font-weight: 300; color: rgba(253,246,237,.42); letter-spacing: .04em; }

.error-msg {
  background: rgba(193,68,14,.1); border: 1px solid rgba(193,68,14,.3);
  color: #F4A135; padding: 12px 16px; border-radius: 3px;
  font-size: .82rem; margin-bottom: 20px;
  display: flex; align-items: center; gap: 9px;
}

.form-group { margin-bottom: 20px; animation: fadeUp .6s both; }
.form-group:nth-child(1){ animation-delay: .3s }
.form-group:nth-child(2){ animation-delay: .4s }

.form-label {
  display: block; font-size: .7rem; font-weight: 600;
  letter-spacing: .16em; text-transform: uppercase;
  color: rgba(253,246,237,.36); margin-bottom: 10px;
}
.input-wrap { position: relative; }
.input-wrap i.icon-left {
  position: absolute; left: 16px; top: 50%; transform: translateY(-50%);
  color: rgba(253,246,237,.18); font-size: .8rem; pointer-events: none; transition: color .3s;
}
.input-wrap:focus-within i.icon-left { color: var(--saffron); }
.input-wrap input {
  width: 100%; padding: 0 48px; height: 52px;
  background: rgba(253,246,237,.04) !important;
  border: 1px solid var(--border) !important;
  border-radius: 3px; color: var(--cream) !important;
  font-family: var(--font-body); font-size: .9rem; font-weight: 400;
  outline: none; transition: border-color .3s, background .3s, box-shadow .3s;
  -webkit-appearance: none;
}
.input-wrap input::placeholder { color: rgba(253,246,237,.16); }
.input-wrap input:focus {
  border-color: rgba(244,161,53,.5) !important;
  background: rgba(244,161,53,.04) !important;
  box-shadow: 0 0 0 3px rgba(244,161,53,.07) !important;
}
.input-wrap input:-webkit-autofill,
.input-wrap input:-webkit-autofill:hover,
.input-wrap input:-webkit-autofill:focus {
  -webkit-box-shadow: 0 0 0 1000px #2D1C0D inset !important;
  -webkit-text-fill-color: var(--cream) !important;
  border-color: rgba(253,246,237,.15) !important;
}
.toggle-pass {
  position: absolute; right: 16px; top: 50%; transform: translateY(-50%);
  color: rgba(253,246,237,.2); font-size: .8rem; cursor: pointer; transition: color .25s; z-index: 1;
}
.toggle-pass:hover { color: var(--saffron); }

.login-btn {
  width: 100%; height: 54px; background: var(--saffron); color: var(--dark);
  border: none; border-radius: 3px; font-family: var(--font-body);
  font-weight: 700; font-size: .8rem; letter-spacing: .16em; text-transform: uppercase;
  cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 10px;
  position: relative; overflow: hidden; transition: color .3s; margin-top: 10px;
}
.login-btn::after {
  content: ''; position: absolute; inset: 0; background: var(--burnt);
  transform: scaleX(0); transform-origin: left; transition: transform .4s ease; z-index: 0;
}
.login-btn:hover::after { transform: scaleX(1); }
.login-btn:hover { color: #fff; }
.login-btn > * { position: relative; z-index: 1; }
.login-btn:disabled { opacity: .6; cursor: not-allowed; }
.login-btn:disabled::after { display: none; }
.login-btn:active { transform: translateY(1px); }

.spinner {
  width: 16px; height: 16px;
  border: 2px solid rgba(26,15,5,.3); border-top-color: var(--dark);
  border-radius: 50%; animation: spin .65s linear infinite; display: none;
}

.login-footer {
  margin-top: 32px; text-align: center;
  font-size: .7rem; letter-spacing: .08em;
  color: rgba(253,246,237,.16);
  animation: fadeUp .6s .65s both;
}

/* ══════════════════════
   RIGHT — BRAND
══════════════════════ */
.login-right {
  width: 48%; position: relative;
  display: flex; flex-direction: column;
  align-items: center; justify-content: center;
  overflow: hidden; padding: 60px 48px;
  animation: slideInRight .7s cubic-bezier(.25,.46,.45,.94) both;
}

.rp-bg {
  position: absolute; inset: 0;
  background-image: url('https://images.unsplash.com/photo-1567188040759-fb8a883dc6d8?w=1200&q=85');
  background-size: cover; background-position: center;
  filter: brightness(.2) saturate(1.1); transition: transform 16s ease;
}
.login-right:hover .rp-bg { transform: scale(1.05); }

.rp-grad {
  position: absolute; inset: 0;
  background: linear-gradient(150deg, rgba(193,68,14,.42) 0%, rgba(26,15,5,.58) 52%, rgba(26,15,5,.93) 100%);
}

/* Big "Tiffins" watermark on right — saffron stroke */
.rp-wm {
  position: absolute;
  bottom: -30px; right: -20px;
  font-family: var(--font-display);
  font-weight: 900; font-style: italic;
  font-size: clamp(80px, 14vw, 160px);
  line-height: 1; white-space: nowrap;
  pointer-events: none; user-select: none; z-index: 1;
  color: transparent;
  -webkit-text-stroke: 1.5px rgba(244,161,53,0.1);
  letter-spacing: -.02em;
  transform: rotate(-8deg);
}

.rp-line {
  position: absolute; top: 60px; bottom: 60px; left: 0;
  width: 3px;
  background: linear-gradient(to bottom, transparent, var(--saffron), transparent);
  opacity: .35;
}

.rp-content {
  position: relative; z-index: 2; text-align: center;
  max-width: 360px; width: 100%;
  display: flex; flex-direction: column; align-items: center;
}

/* ── THREE-COLOUR TITLE ──
   SOUTH INDIAN  →  cream, thin, wide tracking
   TIFFIN        →  saffron, huge italic display
   PARLOUR       →  cream, thin, wide tracking
*/
.rp-title {
  margin-bottom: 4px;
  animation: fadeUp .7s .2s both;
  line-height: 1;
}
.rp-title .t-top {
  display: block;
  font-family: var(--font-body);
  font-size: clamp(.7rem, 1.1vw, .85rem);
  font-weight: 300;
  letter-spacing: .42em;
  text-transform: uppercase;
  color: rgba(253,246,237,.58);
  margin-bottom: 0;
}
.rp-title .t-mid {
  display: block;
  font-family: var(--font-display);
  font-size: clamp(3.6rem, 6.8vw, 5.2rem);
  font-weight: 900;
  font-style: italic;
  color: var(--saffron);
  letter-spacing: -.04em;
  line-height: .85;
  text-shadow: 0 8px 48px rgba(244,161,53,.22), 0 2px 16px rgba(244,161,53,.12);
}
.rp-title .t-bot {
  display: block;
  font-family: var(--font-body);
  font-size: clamp(.7rem, 1.1vw, .85rem);
  font-weight: 300;
  letter-spacing: .42em;
  text-transform: uppercase;
  color: rgba(253,246,237,.58);
  margin-top: 2px;
}

/* Ornament divider */
.rp-ornament {
  display: flex; align-items: center; gap: 10px;
  margin: 20px 0 26px;
  animation: fadeUp .7s .35s both;
  width: 100%;
}
.rp-ornament::before,
.rp-ornament::after {
  content: ''; flex: 1; height: 1px;
}
.rp-ornament::before { background: linear-gradient(to right, transparent, rgba(244,161,53,.4)); }
.rp-ornament::after  { background: linear-gradient(to left,  transparent, rgba(244,161,53,.4)); }
.rp-ornament-dots { display: flex; gap: 5px; align-items: center; flex-shrink: 0; }
.rp-ornament-dots span { display: block; border-radius: 50%; background: var(--saffron); opacity: .65; }
.rp-ornament-dots span:nth-child(1),
.rp-ornament-dots span:nth-child(3) { width: 4px; height: 4px; }
.rp-ornament-dots span:nth-child(2) { width: 6px; height: 6px; opacity: .9; }

/* Pills */
.rp-pills { display: flex; flex-direction: column; gap: 9px; width: 100%; animation: fadeUp .7s .48s both; }
.rp-pill {
  display: flex; align-items: center; gap: 14px;
  background: rgba(253,246,237,.05); border: 1px solid rgba(253,246,237,.09);
  border-radius: 4px; padding: 13px 16px; text-align: left;
  transition: background .3s, border-color .3s, transform .3s;
}
.rp-pill:hover { background: rgba(244,161,53,.07); border-color: rgba(244,161,53,.22); transform: translateX(5px); }
.rp-pill-icon { width: 36px; height: 36px; background: rgba(244,161,53,.1); border: 1px solid rgba(244,161,53,.18); border-radius: 3px; display: flex; align-items: center; justify-content: center; font-size: 1rem; flex-shrink: 0; }
.rp-pill-txt strong { display: block; font-size: .8rem; font-weight: 600; color: var(--cream); margin-bottom: 1px; }
.rp-pill-txt span { font-size: .7rem; color: rgba(253,246,237,.38); font-weight: 300; }

/* Stats */
.rp-stats { display: flex; margin-top: 18px; width: 100%; border: 1px solid rgba(253,246,237,.09); border-radius: 4px; overflow: hidden; animation: fadeUp .7s .62s both; }
.rp-stat { flex: 1; text-align: center; padding: 11px 8px; border-right: 1px solid rgba(253,246,237,.09); }
.rp-stat:last-child { border-right: none; }
.rp-stat-val { font-family: var(--font-display); font-size: 1.3rem; font-weight: 900; color: var(--saffron); line-height: 1; margin-bottom: 3px; }
.rp-stat-lbl { font-size: .6rem; font-weight: 500; letter-spacing: .1em; text-transform: uppercase; color: rgba(253,246,237,.26); }

/* ══ MOBILE ══ */
@media (max-width: 820px) {
  body { display: block !important; overflow-y: auto; }
  .login-split { flex-direction: column; min-height: 100vh; }

  .login-right { width: 100%; order: -1; padding: 44px 24px 32px; animation: slideInUp .7s cubic-bezier(.25,.46,.45,.94) both; }
  .rp-line { display: none; }
  .rp-wm { font-size: 80px; bottom: -10px; right: -10px; }
  .rp-content { max-width: 100%; }
  .rp-title .t-top { font-size: .63rem; letter-spacing: .3em; }
  .rp-title .t-mid { font-size: 3.2rem; }
  .rp-title .t-bot { font-size: .63rem; letter-spacing: .3em; }
  .rp-ornament { margin: 14px 0 20px; }
  .rp-pills { flex-direction: row; flex-wrap: wrap; gap: 8px; }
  .rp-pill { flex: 1 1 calc(50% - 4px); min-width: 130px; padding: 10px 12px; gap: 10px; }
  .rp-pill-icon { width: 30px; height: 30px; font-size: .85rem; }
  .rp-pill-txt strong { font-size: .75rem; }
  .rp-pill-txt span { font-size: .65rem; }
  .rp-stats { margin-top: 12px; }
  .rp-stat { padding: 10px 6px; }
  .rp-stat-val { font-size: 1.1rem; }

  .login-left { padding: 36px 24px 48px; justify-content: flex-start; animation: slideInUp .7s .1s cubic-bezier(.25,.46,.45,.94) both; }
  .wm-text { font-size: 80px; }
  .login-back { margin-bottom: 28px; }
  .login-heading h1 { font-size: 2rem; }
}

@media (max-width: 480px) {
  .login-right { padding: 36px 20px 26px; }
  .rp-title .t-mid { font-size: 2.8rem; }
  .rp-pills { flex-direction: column; }
  .rp-pill { min-width: unset; }
  .rp-wm { font-size: 60px; }
  .login-left { padding: 28px 20px 40px; }
  .login-back { margin-bottom: 22px; }
  .login-heading h1 { font-size: 1.8rem; }
  .wm-text { font-size: 56px; }
}
</style>
@endsection

@section('content')

<div class="login-split">

  {{-- ══ LEFT: FORM ══ --}}
  <div class="login-left">

    {{-- Watermark text behind form --}}
    <div class="wm-text" aria-hidden="true">Tiffins</div>

    <div class="login-form-inner">

      <a href="/" class="login-back">
        <i class="fas fa-arrow-left"></i>
        <span>Back to home</span>
      </a>

      <div class="login-heading">
        <h1>Welcome <span>back.</span></h1>
        <p>Sign in to manage your parlour</p>
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
            <input
              type="email"
              name="email"
              placeholder="admin@southtiffins.com"
              value="{{ old('email') }}"
              autocomplete="email"
              required autofocus
            >
            <i class="fas fa-envelope icon-left"></i>
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Password</label>
          <div class="input-wrap">
            <input
              type="password"
              name="password"
              id="password"
              placeholder="Enter your password"
              autocomplete="current-password"
              required
            >
            <i class="fas fa-lock icon-left"></i>
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
        &copy; {{ date('Y') }} South Tiffins. All rights reserved.
      </div>

    </div>
  </div>

  {{-- ══ RIGHT: BRAND PANEL ══ --}}
  <div class="login-right">
    <div class="rp-bg"></div>
    <div class="rp-grad"></div>
    <div class="rp-line"></div>

    {{-- Watermark text behind content --}}
    <div class="rp-wm" aria-hidden="true">Tiffins</div>

    <div class="rp-content">

      {{-- Three-colour typographic title — no logo --}}
      <div class="rp-title">
        <span class="t-top">South Indian</span>
        <span class="t-mid">Tiffin</span>
        <span class="t-bot">Parlour</span>
      </div>

      <div class="rp-ornament">
        <div class="rp-ornament-dots">
          <span></span><span></span><span></span>
        </div>
      </div>

      <div class="rp-pills">
        <div class="rp-pill">
          <div class="rp-pill-icon">&#128241;</div>
          <div class="rp-pill-txt">
            <strong>QR Table Ordering</strong>
            <span>No app download needed</span>
          </div>
        </div>
        <div class="rp-pill">
          <div class="rp-pill-icon">&#9889;</div>
          <div class="rp-pill-txt">
            <strong>Live Counter Alerts</strong>
            <span>Orders in under 2 seconds</span>
          </div>
        </div>
        <div class="rp-pill">
          <div class="rp-pill-icon">&#128202;</div>
          <div class="rp-pill-txt">
            <strong>Daily PDF Reports</strong>
            <span>Auto-generated every night</span>
          </div>
        </div>
      </div>

      <div class="rp-stats">
        <div class="rp-stat">
          <div class="rp-stat-val">0%</div>
          <div class="rp-stat-lbl">Commission</div>
        </div>
        <div class="rp-stat">
          <div class="rp-stat-val">&lt;2s</div>
          <div class="rp-stat-lbl">Notification</div>
        </div>
        <div class="rp-stat">
          <div class="rp-stat-val">EN+&#3078;</div>
          <div class="rp-stat-lbl">Bilingual</div>
        </div>
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
    togglePass.classList.toggle('fa-eye',       !isPass);
    togglePass.classList.toggle('fa-eye-slash',  isPass);
  });

  loginForm.addEventListener('submit', () => {
    loginBtn.disabled       = true;
    spinner.style.display   = 'block';
    loginIcon.style.display = 'none';
    loginText.textContent   = 'Signing in...';
  });
</script>
@endsection