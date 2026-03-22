<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'South Tiffins')</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,700;0,9..144,900;1,9..144,700;1,9..144,900&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        /* ══════════════════════════════════════════
           TOKENS
        ══════════════════════════════════════════ */
        :root {
            --primary:        #FF6B35;
            --primary-dark:   #E85520;
            --primary-light:  #FFF0E8;
            --green:          #2D6A4F;
            --green-light:    #E8F5EE;
            --dark:           #1A1A1A;
            --muted:          #666666;
            --border:         #EEEEEE;
            --bg:             #FAFAFA;
            --white:          #FFFFFF;
            --counter-bg:     #0D0D1A;
            --counter-card:   #1E1E2E;
            --counter-border: #2C2C44;
            --font-display:   'Fraunces', serif;
            --font-body:      'Outfit', sans-serif;
            --radius-sm:   8px;
            --radius-md:   12px;
            --radius-lg:   16px;
            --radius-xl:   20px;
            --radius-full: 9999px;
            --shadow-sm:      0 2px 8px rgba(0,0,0,0.06);
            --shadow-md:      0 4px 16px rgba(0,0,0,0.08);
            --shadow-lg:      0 8px 32px rgba(0,0,0,0.12);
            --shadow-primary: 0 8px 24px rgba(255,107,53,0.3);
            --transition: all 0.3s ease;
            --nav-h: 64px;
        }

        /* ══ RESET ══ */
        *, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }
        html { scroll-behavior:smooth; -webkit-tap-highlight-color:transparent; }
        body {
            font-family: var(--font-body);
            background: var(--bg); color: var(--dark);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            overflow-x: hidden;
        }
        a { text-decoration:none; color:inherit; }
        img { max-width:100%; display:block; }

        /* ══ LAYOUT ══ */
        .container { max-width:1160px; margin:0 auto; padding:0 24px; }

        /* ══ TYPOGRAPHY ══ */
        .section-label {
            font-family:var(--font-body); font-weight:600; font-size:11px;
            letter-spacing:0.12em; text-transform:uppercase;
            color:var(--primary); margin-bottom:10px; display:block;
        }
        .section-title {
            font-family:var(--font-display); font-weight:700; font-size:38px;
            color:var(--dark); line-height:1.15; letter-spacing:-0.02em; margin-bottom:14px;
        }
        .section-title em { font-style:italic; color:var(--primary); }
        .section-subtitle { font-size:16px; color:var(--muted); line-height:1.7; max-width:520px; font-weight:400; }

        /* ══ BUTTONS ══ */
        .btn-primary {
            display:inline-flex; align-items:center; gap:8px;
            background:var(--primary); color:white;
            padding:13px 26px; border-radius:var(--radius-full);
            font-family:var(--font-body); font-weight:600; font-size:14px;
            border:none; cursor:pointer; box-shadow:var(--shadow-primary); transition:var(--transition);
        }
        .btn-primary:hover { background:var(--primary-dark); transform:translateY(-2px); color:white; }

        .btn-outline {
            display:inline-flex; align-items:center; gap:8px;
            background:transparent; color:var(--primary);
            padding:11px 24px; border-radius:var(--radius-full);
            font-family:var(--font-body); font-weight:600; font-size:14px;
            border:2px solid var(--primary); cursor:pointer; transition:var(--transition);
        }
        .btn-outline:hover { background:var(--primary); color:white; transform:translateY(-2px); }

        .btn-dark {
            display:inline-flex; align-items:center; gap:8px;
            background:var(--dark); color:white;
            padding:11px 22px; border-radius:var(--radius-full);
            font-family:var(--font-body); font-weight:600; font-size:14px;
            border:none; cursor:pointer; transition:var(--transition);
        }
        .btn-dark:hover { background:#333; transform:translateY(-2px); color:white; }

        .btn-ghost {
            display:inline-flex; align-items:center; gap:6px;
            background:transparent; color:var(--muted);
            padding:10px 18px; border-radius:var(--radius-full);
            font-family:var(--font-body); font-weight:500; font-size:14px;
            border:1.5px solid var(--border); cursor:pointer; transition:var(--transition);
        }
        .btn-ghost:hover { border-color:var(--primary); color:var(--primary); }

        .btn-white {
            display:inline-flex; align-items:center; gap:8px;
            background:white; color:var(--primary);
            padding:13px 26px; border-radius:var(--radius-full);
            font-family:var(--font-body); font-weight:700; font-size:14px;
            border:none; cursor:pointer; transition:var(--transition);
            box-shadow:0 8px 24px rgba(0,0,0,0.15);
        }
        .btn-white:hover { transform:translateY(-2px); box-shadow:0 12px 32px rgba(0,0,0,0.2); color:var(--primary); }

        .btn-white-outline {
            display:inline-flex; align-items:center; gap:8px;
            background:transparent; color:white;
            padding:11px 24px; border-radius:var(--radius-full);
            font-family:var(--font-body); font-weight:600; font-size:14px;
            border:2px solid rgba(255,255,255,0.55); cursor:pointer; transition:var(--transition);
        }
        .btn-white-outline:hover { background:rgba(255,255,255,0.12); border-color:white; color:white; transform:translateY(-2px); }

        /* ══ ANIMATIONS ══ */
        @keyframes fadeUp    { from{opacity:0;transform:translateY(28px)} to{opacity:1;transform:translateY(0)} }
        @keyframes float     { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-10px)} }
        @keyframes pulse     { 0%,100%{transform:scale(1);opacity:1} 50%{transform:scale(1.25);opacity:0.6} }
        @keyframes shimmer   { 0%{background-position:200% 0} 100%{background-position:-200% 0} }
        @keyframes badgeSlide{ from{opacity:0;transform:translateY(12px)} to{opacity:1;transform:translateY(0)} }
        @keyframes scaleIn   { from{opacity:0;transform:scale(0.92)} to{opacity:1;transform:scale(1)} }
        @keyframes slideRight{ from{opacity:0;transform:translateX(-24px)} to{opacity:1;transform:translateX(0)} }
        @keyframes menuOpen  { from{opacity:0;transform:translateY(-8px)} to{opacity:1;transform:translateY(0)} }

        .anim-fade { opacity:0; animation:fadeUp 0.7s ease forwards; }
        .d1{animation-delay:0.1s} .d2{animation-delay:0.2s}
        .d3{animation-delay:0.3s} .d4{animation-delay:0.4s}
        .d5{animation-delay:0.5s} .d6{animation-delay:0.6s}

        .animate-fade-up    { animation:fadeUp 0.6s ease forwards; }
        .animate-scale-in   { animation:scaleIn 0.4s cubic-bezier(0.34,1.56,0.64,1) forwards; }
        .animate-slide-right{ animation:slideRight 0.5s ease forwards; }
        .animate-float      { animation:float 3s ease infinite; }
        .animate-pulse      { animation:pulse 1.5s ease infinite; }
        .delay-1{animation-delay:0.1s} .delay-2{animation-delay:0.2s}
        .delay-3{animation-delay:0.3s} .delay-4{animation-delay:0.4s}
        .delay-5{animation-delay:0.5s}

        /* ══ NAVBAR ══ */
        .nav {
            position:fixed; top:0; left:0; right:0; z-index:1000;
            background:rgba(255,255,255,0.95);
            backdrop-filter:blur(14px); -webkit-backdrop-filter:blur(14px);
            border-bottom:1px solid rgba(0,0,0,0.06);
            transition:box-shadow 0.3s ease;
        }
        .nav-inner {
            display:flex; align-items:center; justify-content:space-between;
            height:var(--nav-h);
        }
        .nav-logo { display:flex; align-items:center; }
        .nav-logo img { height:46px; width:auto; object-fit:contain; }
        .nav-logo span { display:none; }
        .nav-links { display:flex; align-items:center; gap:30px; list-style:none; }
        .nav-links a {
            font-size:13.5px; font-weight:500; color:var(--muted);
            transition:color 0.2s; position:relative; padding-bottom:2px;
        }
        .nav-links a::after {
            content:''; position:absolute; bottom:-2px; left:0; right:100%;
            height:2px; background:var(--primary); transition:right 0.3s ease;
        }
        .nav-links a:hover { color:var(--primary); }
        .nav-links a:hover::after { right:0; }
        .nav-actions { display:flex; align-items:center; gap:10px; }

        /* Hamburger button */
        .nav-hamburger {
            display:none; flex-direction:column; gap:5px;
            background:none; border:none; cursor:pointer;
            padding:8px; border-radius:var(--radius-sm); transition:var(--transition);
        }
        .nav-hamburger span {
            display:block; width:22px; height:2px;
            background:var(--dark); border-radius:2px; transition:var(--transition);
        }
        .nav-hamburger.open span:nth-child(1) { transform:translateY(7px) rotate(45deg); }
        .nav-hamburger.open span:nth-child(2) { opacity:0; transform:scaleX(0); }
        .nav-hamburger.open span:nth-child(3) { transform:translateY(-7px) rotate(-45deg); }

        /* Mobile drawer */
        .nav-drawer {
            display:none; position:fixed;
            top:var(--nav-h); left:0; right:0;
            background:white; z-index:999;
            border-bottom:1px solid var(--border);
            box-shadow:0 8px 24px rgba(0,0,0,0.08);
            padding:16px 20px 24px;
            animation:menuOpen 0.25s ease both;
        }
        .nav-drawer.open { display:block; }
        .nav-drawer nav ul { list-style:none; }
        .nav-drawer nav ul li { border-bottom:1px solid var(--border); }
        .nav-drawer nav ul li:last-child { border-bottom:none; }
        .nav-drawer nav ul li a {
            display:block; padding:14px 0;
            font-size:15px; font-weight:500; color:var(--dark); transition:color 0.2s;
        }
        .nav-drawer nav ul li a:hover { color:var(--primary); }
        .nav-drawer-btns {
            display:flex; flex-direction:column; gap:10px; margin-top:18px;
        }
        .nav-drawer-btns a { text-align:center; justify-content:center; }

        /* ══ HERO ══ */
        .hero {
            min-height:100vh; display:flex; align-items:center;
            padding:100px 0 64px; background:white;
            position:relative; overflow:hidden;
        }
        .hero::before {
            content:''; position:absolute; top:-200px; right:-200px;
            width:700px; height:700px;
            background:radial-gradient(circle,rgba(255,107,53,0.07) 0%,transparent 65%);
            pointer-events:none;
        }
        .hero::after {
            content:''; position:absolute; bottom:-100px; left:-100px;
            width:500px; height:500px;
            background:radial-gradient(circle,rgba(45,106,79,0.05) 0%,transparent 65%);
            pointer-events:none;
        }
        .hero-grid {
            display:grid; grid-template-columns:1fr 1fr;
            gap:60px; align-items:center; position:relative; z-index:1;
        }
        .hero-tag {
            display:inline-flex; align-items:center; gap:8px;
            background:var(--primary-light); border:1px solid rgba(255,107,53,0.2);
            padding:6px 14px; border-radius:var(--radius-full);
            font-size:11.5px; font-weight:600; color:var(--primary);
            margin-bottom:20px; letter-spacing:0.02em;
        }
        .hero-tag .dot {
            width:6px; height:6px; background:var(--primary);
            border-radius:50%; animation:pulse 1.5s ease infinite;
        }
        .hero-title {
            font-family:var(--font-display); font-weight:900; font-style:italic;
            font-size:56px; line-height:1.02; letter-spacing:-0.03em;
            color:var(--dark); margin-bottom:20px;
        }
        .hero-title .accent { color:var(--primary); }
        .hero-desc { font-size:16px; color:var(--muted); line-height:1.75; margin-bottom:36px; max-width:440px; }
        .hero-actions { display:flex; gap:12px; margin-bottom:48px; flex-wrap:wrap; }
        .hero-stats { display:flex; gap:28px; align-items:center; }
        .hero-stat-val { font-family:var(--font-display); font-weight:700; font-size:26px; color:var(--dark); line-height:1; }
        .hero-stat-lbl { font-size:11.5px; color:var(--muted); margin-top:3px; }
        .hero-stat-divider { width:1px; height:36px; background:var(--border); }

        .hero-visual { position:relative; }
        .hero-img-wrap {
            border-radius:24px; overflow:hidden;
            box-shadow:0 20px 60px rgba(0,0,0,0.14);
            animation:float 5s ease infinite;
        }
        .hero-img-wrap img { width:100%; height:460px; object-fit:cover; }
        .hero-badge {
            position:absolute; background:white;
            border-radius:var(--radius-lg); padding:12px 16px;
            box-shadow:0 8px 28px rgba(0,0,0,0.12);
            display:flex; align-items:center; gap:10px;
        }
        .hero-badge-1 { bottom:36px; left:-24px; animation:badgeSlide 0.6s 0.4s ease both; }
        .hero-badge-2 { top:32px; right:-24px; animation:badgeSlide 0.6s 0.6s ease both; }
        .badge-icon { width:38px; height:38px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:18px; }
        .badge-txt strong { display:block; font-size:12.5px; font-weight:700; color:var(--dark); }
        .badge-txt span   { font-size:11px; color:var(--muted); }

        /* ══ STATS BAR ══ */
        .stats-bar { background:var(--dark); padding:28px 0; }
        .stats-bar-grid { display:grid; grid-template-columns:repeat(4,1fr); text-align:center; }
        .stat-col { padding:0 20px; border-right:1px solid rgba(255,255,255,0.08); }
        .stat-col:last-child { border-right:none; }
        .stat-val { font-family:var(--font-display); font-weight:700; font-style:italic; font-size:30px; color:var(--primary); margin-bottom:4px; }
        .stat-lbl { font-size:12px; color:rgba(255,255,255,0.5); font-weight:300; }

        /* ══ FEATURES ══ */
        .features { padding:96px 0; background:var(--bg); }
        .section-head { text-align:center; margin-bottom:56px; }
        .section-head .section-subtitle { margin:0 auto; }
        .features-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:22px; }
        .feature-card {
            background:white; border-radius:var(--radius-xl);
            padding:30px; border:1.5px solid var(--border);
            transition:var(--transition); position:relative; overflow:hidden;
        }
        .feature-card::before {
            content:''; position:absolute; top:0; left:0; right:0; height:3px;
            background:linear-gradient(90deg,var(--primary),var(--primary-dark));
            transform:scaleX(0); transform-origin:left; transition:transform 0.3s ease;
        }
        .feature-card:hover { border-color:rgba(255,107,53,0.2); transform:translateY(-5px); box-shadow:var(--shadow-lg); }
        .feature-card:hover::before { transform:scaleX(1); }
        .feature-icon { width:52px; height:52px; background:var(--primary-light); border-radius:var(--radius-md); display:flex; align-items:center; justify-content:center; font-size:22px; margin-bottom:18px; }
        .feature-title { font-family:var(--font-display); font-weight:700; font-size:17px; color:var(--dark); margin-bottom:8px; }
        .feature-desc  { font-size:13.5px; color:var(--muted); line-height:1.7; }

        /* ══ HOW IT WORKS ══ */
        .how { padding:96px 0; background:white; }
        .how-grid { display:grid; grid-template-columns:1fr 1fr; gap:72px; align-items:center; }
        .how-img { width:100%; height:460px; object-fit:cover; border-radius:24px; box-shadow:0 20px 60px rgba(0,0,0,0.1); }
        .steps { display:flex; flex-direction:column; gap:28px; }
        .step  { display:flex; gap:18px; align-items:flex-start; }
        .step-num {
            width:42px; height:42px; background:var(--primary); color:white;
            border-radius:50%; display:flex; align-items:center; justify-content:center;
            font-family:var(--font-display); font-weight:700; font-size:15px;
            flex-shrink:0; box-shadow:0 4px 14px rgba(255,107,53,0.35);
        }
        .step-title { font-family:var(--font-display); font-weight:700; font-size:16px; color:var(--dark); margin-bottom:4px; }
        .step-desc  { font-size:13.5px; color:var(--muted); line-height:1.6; }

        /* ══ SCREENS ══ */
        .screens { padding:96px 0; background:var(--bg); }
        .screens-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:22px; }
        .screen-card { background:white; border-radius:var(--radius-xl); overflow:hidden; border:1.5px solid var(--border); transition:var(--transition); }
        .screen-card:hover { transform:translateY(-5px); box-shadow:var(--shadow-lg); border-color:rgba(255,107,53,0.2); }
        .screen-img  { width:100%; height:200px; object-fit:cover; }
        .screen-body { padding:22px; }
        .screen-tag  { font-size:10px; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--primary); margin-bottom:7px; display:block; }
        .screen-title{ font-family:var(--font-display); font-weight:700; font-size:19px; color:var(--dark); margin-bottom:7px; }
        .screen-desc { font-size:13px; color:var(--muted); line-height:1.6; }

        /* ══ CTA ══ */
        .cta { padding:96px 0; background:var(--primary); text-align:center; position:relative; overflow:hidden; }
        .cta::before {
            content:''; position:absolute; inset:0;
            background:radial-gradient(ellipse at 25% 50%,rgba(255,255,255,0.1) 0%,transparent 55%),
                        radial-gradient(ellipse at 75% 50%,rgba(0,0,0,0.08) 0%,transparent 55%);
            pointer-events:none;
        }
        .cta-inner { position:relative; z-index:1; padding:0 24px; }
        .cta-logo-wrap {
            background:white; border-radius:14px; display:inline-block;
            padding:10px 20px; margin:0 auto 22px;
            box-shadow:0 8px 28px rgba(0,0,0,0.15);
        }
        .cta-logo-wrap img { height:56px; width:auto; }
        .cta-title { font-family:var(--font-display); font-weight:900; font-style:italic; font-size:46px; color:white; letter-spacing:-0.03em; margin-bottom:14px; }
        .cta-desc  { font-size:16px; color:rgba(255,255,255,0.82); margin-bottom:36px; font-weight:300; }
        .cta-actions { display:flex; gap:14px; justify-content:center; flex-wrap:wrap; }

        /* ══ FOOTER ══ */
        .footer { background:var(--dark); padding:28px 0; }
        .footer-inner { display:flex; align-items:center; justify-content:space-between; }
        .footer-logo  { display:flex; align-items:center; }
        .footer-logo img { height:38px; width:auto; object-fit:contain; filter:brightness(0) invert(1); }
        .footer-logo span { display:none; }
        .footer-copy  { font-size:12px; color:rgba(255,255,255,0.35); }
        .footer-links { display:flex; gap:22px; }
        .footer-links a { font-size:12.5px; color:rgba(255,255,255,0.45); transition:color 0.2s; }
        .footer-links a:hover { color:var(--primary); }

        /* ══ SHARED UTILS ══ */
        .veg-dot { display:inline-flex; align-items:center; justify-content:center; width:14px; height:14px; border:2px solid var(--green); border-radius:2px; flex-shrink:0; }
        .veg-dot::after { content:''; display:block; width:7px; height:7px; background:var(--green); border-radius:50%; }
        .nonveg-dot { display:inline-flex; align-items:center; justify-content:center; width:14px; height:14px; border:2px solid #e53935; border-radius:2px; flex-shrink:0; }
        .nonveg-dot::after { content:''; display:block; width:7px; height:7px; background:#e53935; border-radius:50%; }

        .badge-pending   { background:rgba(255,193,7,0.15);  color:#856404; font-size:11px; font-weight:600; padding:4px 10px; border-radius:20px; border:1px solid rgba(255,193,7,0.3);  display:inline-block; }
        .badge-preparing { background:rgba(33,150,243,0.12); color:#1565C0; font-size:11px; font-weight:600; padding:4px 10px; border-radius:20px; border:1px solid rgba(33,150,243,0.3); display:inline-block; }
        .badge-ready     { background:rgba(255,152,0,0.12);  color:#E65100; font-size:11px; font-weight:600; padding:4px 10px; border-radius:20px; border:1px solid rgba(255,152,0,0.3);  display:inline-block; }
        .badge-served    { background:rgba(46,125,50,0.12);  color:#2E7D32; font-size:11px; font-weight:600; padding:4px 10px; border-radius:20px; border:1px solid rgba(46,125,50,0.3);  display:inline-block; }
        .badge-paid      { background:rgba(46,125,50,0.12);  color:#2E7D32; font-size:11px; font-weight:600; padding:4px 10px; border-radius:20px; display:inline-block; }
        .badge-unpaid    { background:rgba(198,40,40,0.12);  color:#C62828; font-size:11px; font-weight:600; padding:4px 10px; border-radius:20px; display:inline-block; }

        input, select, textarea {
            width:100%; padding:12px 16px;
            border:1.5px solid var(--border); border-radius:var(--radius-md);
            font-family:var(--font-body); font-size:14px;
            color:var(--dark); background:white;
            outline:none; transition:var(--transition);
        }
        input:focus, select:focus, textarea:focus { border-color:var(--primary); box-shadow:0 0 0 3px rgba(255,107,53,0.1); }
        input::placeholder, textarea::placeholder { color:#AAAAAA; font-weight:300; }

        .card { background:white; border-radius:var(--radius-lg); box-shadow:var(--shadow-md); padding:24px; }
        .skeleton { background:linear-gradient(90deg,#f0f0f0 25%,#f8f8f8 50%,#f0f0f0 75%); background-size:200% 100%; animation:shimmer 1.5s infinite; border-radius:var(--radius-md); }

        /* ══════════════════════════════════════════
           RESPONSIVE — TABLET ≤ 960px
        ══════════════════════════════════════════ */
        @media (max-width: 960px) {
            .hero-grid      { grid-template-columns:1fr; gap:0; }
            .hero-visual    { display:none; }
            .hero           { padding:90px 0 56px; min-height:auto; }
            .hero-title     { font-size:46px; }
            .how-grid       { grid-template-columns:1fr; }
            .how-img        { display:none; }
            .how            { padding:72px 0; }
            .features-grid  { grid-template-columns:repeat(2,1fr); }
            .screens-grid   { grid-template-columns:repeat(2,1fr); }
            .stats-bar-grid { grid-template-columns:repeat(2,1fr); row-gap:0; }
            .stat-col       { border-right:none; padding:16px 0; border-bottom:1px solid rgba(255,255,255,0.08); }
            .stat-col:nth-child(odd)  { border-right:1px solid rgba(255,255,255,0.08); }
            .stat-col:nth-child(3), .stat-col:nth-child(4) { border-bottom:none; }
            .section-title  { font-size:32px; }
            .features { padding:72px 0; }
            .screens  { padding:72px 0; }
            .cta      { padding:72px 0; }
            .cta-title{ font-size:38px; }
        }

        /* ══════════════════════════════════════════
           RESPONSIVE — MOBILE ≤ 600px
        ══════════════════════════════════════════ */
        @media (max-width: 600px) {
            :root { --nav-h: 60px; }

            .container { padding:0 16px; }

            /* Navbar */
            .nav-links    { display:none; }
            .nav-actions  { display:none; }
            .nav-hamburger{ display:flex; }
            .nav-logo img { height:40px; }

            /* Hero */
            .hero          { padding:80px 0 48px; }
            .hero-title    { font-size:34px; line-height:1.08; }
            .hero-tag      { font-size:11px; padding:5px 12px; margin-bottom:16px; }
            .hero-desc     { font-size:15px; margin-bottom:28px; }
            .hero-actions  { flex-direction:column; gap:10px; margin-bottom:32px; }
            .hero-actions a{ width:100%; justify-content:center; }
            .hero-stats    { gap:16px; flex-wrap:wrap; }
            .hero-stat-val { font-size:22px; }
            .hero-stat-lbl { font-size:11px; }

            /* Stats bar */
            .stats-bar      { padding:20px 0; }
            .stats-bar-grid { grid-template-columns:repeat(2,1fr); }
            .stat-col       { padding:12px 8px; border-right:none; border-bottom:1px solid rgba(255,255,255,0.08); }
            .stat-col:nth-child(odd)  { border-right:1px solid rgba(255,255,255,0.08); }
            .stat-col:nth-child(3), .stat-col:nth-child(4) { border-bottom:none; }
            .stat-val { font-size:24px; }
            .stat-lbl { font-size:11px; }

            /* Sections */
            .features { padding:52px 0; }
            .screens  { padding:52px 0; }
            .how      { padding:52px 0; }
            .section-head { margin-bottom:32px; }
            .section-title    { font-size:26px; }
            .section-subtitle { font-size:14px; }
            .section-label    { font-size:10px; }

            /* Features */
            .features-grid { grid-template-columns:1fr; gap:12px; }
            .feature-card  { padding:20px; }
            .feature-icon  { width:44px; height:44px; font-size:20px; margin-bottom:14px; }
            .feature-title { font-size:16px; }
            .feature-desc  { font-size:13px; }

            /* How */
            .steps     { gap:20px; }
            .step-num  { width:36px; height:36px; font-size:13px; flex-shrink:0; }
            .step-title{ font-size:15px; }
            .step-desc { font-size:13px; }

            /* Screens */
            .screens-grid { grid-template-columns:1fr; gap:12px; }
            .screen-img   { height:180px; }
            .screen-body  { padding:18px; }
            .screen-title { font-size:17px; }

            /* CTA */
            .cta           { padding:52px 0; }
            .cta-title     { font-size:28px; }
            .cta-desc      { font-size:14px; margin-bottom:24px; }
            .cta-actions   { flex-direction:column; align-items:center; gap:10px; }
            .cta-actions a { width:100%; max-width:280px; justify-content:center; }
            .cta-logo-wrap img { height:44px; }
            .cta-logo-wrap { padding:8px 16px; }

            /* Footer */
            .footer-inner  { flex-direction:column; gap:14px; text-align:center; }
            .footer-logo   { justify-content:center; }
            .footer-logo img{ height:32px; }
            .footer-links  { flex-wrap:wrap; justify-content:center; gap:14px; }
            .footer-copy   { order:3; font-size:11px; }

            /* Buttons */
            .btn-primary, .btn-outline, .btn-dark { font-size:14px; padding:12px 20px; }
        }

        /* ══ EXTRA SMALL ≤ 380px ══ */
        @media (max-width: 380px) {
            .hero-title    { font-size:28px; }
            .section-title { font-size:23px; }
            .cta-title     { font-size:24px; }
            .stat-val      { font-size:20px; }
            .hero-stats    { gap:10px; }
            .hero-stat-divider { display:none; }
        }
    </style>

    @yield('styles')
</head>
<body>

@yield('content')

@yield('scripts')

</body>
</html>