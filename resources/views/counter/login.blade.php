@extends('layouts.app')

@section('title', 'Counter Login — South Tiffins')

@section('styles')
<style>
    body {
        background: #0D0D1A;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 24px;
        position: relative;
        overflow: hidden;
    }

    body::before {
        content: '';
        position: absolute;
        top: -200px;
        left: -200px;
        width: 500px;
        height: 500px;
        background: radial-gradient(circle, rgba(255,107,53,0.08) 0%, transparent 65%);
        pointer-events: none;
    }

    body::after {
        content: '';
        position: absolute;
        bottom: -150px;
        right: -150px;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(45,106,79,0.06) 0%, transparent 65%);
        pointer-events: none;
    }

    .counter-login-wrap {
        width: 100%;
        max-width: 420px;
        position: relative;
        z-index: 1;
        animation: fadeUp 0.5s ease forwards;
    }

    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(24px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .cl-logo-block {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-bottom: 32px;
    }

    .cl-logo-card {
        background: white;
        border-radius: 20px;
        padding: 14px 24px;
        display: inline-block;
        box-shadow: 0 8px 32px rgba(0,0,0,0.3);
        margin-bottom: 18px;
    }

    .cl-logo-card img {
        height: 64px;
        width: auto;
        display: block;
    }

    .cl-logo-title {
        font-family: var(--font-display);
        font-weight: 900;
        font-style: italic;
        font-size: 26px;
        color: white;
        letter-spacing: -0.02em;
        margin-bottom: 4px;
        text-align: center;
    }

    .cl-logo-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(255,107,53,0.15);
        border: 1px solid rgba(255,107,53,0.3);
        color: var(--primary);
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        padding: 5px 14px;
        border-radius: var(--radius-full);
    }

    .cl-logo-badge .live-dot {
        width: 6px;
        height: 6px;
        background: var(--primary);
        border-radius: 50%;
        animation: blink 1.5s ease infinite;
    }

    @keyframes blink {
        0%, 100% { opacity: 1; }
        50%       { opacity: 0.3; }
    }

    .cl-card {
        background: #1E1E2E;
        border-radius: 24px;
        padding: 36px;
        border: 1.5px solid #2C2C44;
        box-shadow: 0 20px 60px rgba(0,0,0,0.4);
    }

    .cl-card-head {
        margin-bottom: 28px;
        text-align: center;
    }

    .cl-card-head h2 {
        font-family: var(--font-display);
        font-weight: 700;
        font-style: italic;
        font-size: 24px;
        color: white;
        letter-spacing: -0.02em;
        margin-bottom: 6px;
    }

    .cl-card-head p {
        font-size: 13px;
        color: rgba(255,255,255,0.38);
        font-weight: 300;
        line-height: 1.5;
    }

    .cl-divider {
        width: 40px;
        height: 3px;
        background: var(--primary);
        border-radius: 2px;
        margin: 12px auto 0;
    }

    .cl-error {
        background: rgba(198,40,40,0.1);
        border: 1px solid rgba(198,40,40,0.25);
        color: #ff8a80;
        padding: 11px 16px;
        border-radius: var(--radius-md);
        font-size: 13px;
        margin-bottom: 22px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .cl-label {
        display: block;
        font-size: 11px;
        font-weight: 600;
        color: rgba(255,255,255,0.45);
        letter-spacing: 0.1em;
        text-transform: uppercase;
        margin-bottom: 10px;
        text-align: center;
    }

    .pin-dots {
        display: flex;
        justify-content: center;
        gap: 12px;
        margin-bottom: 16px;
    }

    .pin-dot {
        width: 14px;
        height: 14px;
        border-radius: 50%;
        background: rgba(255,255,255,0.08);
        border: 2px solid rgba(255,255,255,0.15);
        transition: all 0.2s ease;
    }

    .pin-dot.filled {
        background: var(--primary);
        border-color: var(--primary);
        box-shadow: 0 0 8px rgba(255,107,53,0.5);
    }

    .pin-input-wrap {
        position: relative;
        margin-bottom: 28px;
    }

    .pin-input {
        width: 100%;
        padding: 18px 20px;
        background: rgba(255,255,255,0.05);
        border: 1.5px solid #2C2C44;
        border-radius: var(--radius-lg);
        font-family: var(--font-display);
        font-size: 32px;
        font-weight: 700;
        color: white;
        text-align: center;
        letter-spacing: 0.4em;
        outline: none;
        transition: all 0.25s ease;
        caret-color: var(--primary);
    }

    .pin-input:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(255,107,53,0.12);
        background: rgba(255,107,53,0.04);
    }

    .pin-input::placeholder {
        color: rgba(255,255,255,0.1);
        letter-spacing: 0.3em;
        font-size: 24px;
    }

    .cl-btn {
        width: 100%;
        height: 52px;
        background: var(--primary);
        color: white;
        border: none;
        border-radius: var(--radius-full);
        font-family: var(--font-body);
        font-weight: 700;
        font-size: 15px;
        cursor: pointer;
        box-shadow: 0 8px 24px rgba(255,107,53,0.35);
        transition: all 0.25s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        letter-spacing: 0.01em;
    }

    .cl-btn:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 12px 32px rgba(255,107,53,0.45);
    }

    .cl-btn:active {
        transform: translateY(0);
    }

    .cl-back {
        text-align: center;
        margin-top: 22px;
    }

    .cl-back a {
        font-size: 13px;
        color: rgba(255,255,255,0.25);
        display: inline-flex;
        align-items: center;
        gap: 7px;
        transition: color 0.2s;
    }

    .cl-back a:hover {
        color: var(--primary);
    }

    @media (max-width: 480px) {
        .cl-card        { padding: 28px 22px; border-radius: 20px; }
        .cl-logo-card   { padding: 12px 20px; }
        .cl-logo-card img { height: 52px; }
        .cl-logo-title  { font-size: 22px; }
        .pin-input      { font-size: 28px; padding: 16px; }
        .cl-card-head h2{ font-size: 21px; }
    }
</style>
@endsection

@section('content')

<div class="counter-login-wrap">

    <div class="cl-logo-block">
        <div class="cl-logo-card">
            <img src="{{ asset('images/logo.png') }}" alt="South Tiffins">
        </div>
        <div class="cl-logo-title">South Tiffins</div>
        <div class="cl-logo-badge">
            <div class="live-dot"></div>
            Counter Access
        </div>
    </div>

    <div class="cl-card">

        <div class="cl-card-head">
            <h2>Enter Your PIN</h2>
            <p>Enter your 4–6 digit counter PIN<br>to access live orders</p>
            <div class="cl-divider"></div>
        </div>

        @if(session('error'))
        <div class="cl-error">
            <i class="fas fa-exclamation-circle"></i>
            {{ session('error') }}
        </div>
        @endif

        <form method="POST" action="{{ route('counter.login.post') }}" id="pinForm">
            @csrf

            <label class="cl-label">Counter PIN</label>

            <div class="pin-dots" id="pinDots">
                <div class="pin-dot" id="dot-0"></div>
                <div class="pin-dot" id="dot-1"></div>
                <div class="pin-dot" id="dot-2"></div>
                <div class="pin-dot" id="dot-3"></div>
                <div class="pin-dot" id="dot-4"></div>
                <div class="pin-dot" id="dot-5"></div>
            </div>

            <div class="pin-input-wrap">
                <input
                    type="password"
                    name="pin"
                    id="pinInput"
                    class="pin-input"
                    placeholder="••••••"
                    maxlength="6"
                    inputmode="numeric"
                    pattern="[0-9]*"
                    autofocus
                    required
                >
            </div>

            <button type="submit" class="cl-btn">
                <i class="fas fa-unlock-alt"></i>
                Access Counter
            </button>
        </form>

    </div>

    <div class="cl-back">
        <a href="/"><i class="fas fa-arrow-left"></i> Back to home</a>
    </div>

</div>

@endsection

@section('scripts')
<script>
    const pinInput = document.getElementById('pinInput');
    const dots     = document.querySelectorAll('.pin-dot');

    pinInput.addEventListener('input', function () {
        this.value = this.value.replace(/[^0-9]/g, '');
        const len  = this.value.length;
        dots.forEach((dot, i) => {
            dot.classList.toggle('filled', i < len);
        });
    });

    pinInput.addEventListener('keypress', function (e) {
        if (e.key === 'Enter') document.getElementById('pinForm').submit();
    });
</script>
@endsection