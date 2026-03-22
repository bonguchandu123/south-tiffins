<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>South Tiffins — QR Table Ordering System</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;0,700;1,300;1,400;1,600&family=Outfit:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,700;0,900;1,700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
:root {
  --burnt:   #C1440E;
  --saffron: #F4A135;
  --cream:   #FDF6ED;
  --dark:    #1A0F05;
  --dark2:   #2D1C0D;
  --font-display: 'Playfair Display', Georgia, serif;
  --font-serif:   'Cormorant Garamond', Georgia, serif;
  --font-body:    'Outfit', sans-serif;
}
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
html { scroll-behavior: smooth; overflow-x: hidden; }
body { background: var(--dark); color: var(--cream); font-family: var(--font-body); overflow-x: hidden; cursor: none; }

/* CURSOR */
.cursor { width:12px;height:12px;background:var(--saffron);border-radius:50%;position:fixed;top:0;left:0;pointer-events:none;z-index:9999;transform:translate(-50%,-50%);transition:width .3s,height .3s,background .3s;mix-blend-mode:difference; }
.cursor-ring { width:38px;height:38px;border:1.5px solid rgba(244,161,53,.5);border-radius:50%;position:fixed;top:0;left:0;pointer-events:none;z-index:9998;transform:translate(-50%,-50%);transition:left .12s ease,top .12s ease; }
@media(hover:none){ .cursor,.cursor-ring{display:none} body{cursor:auto} }

/* NOISE */
body::before { content:'';position:fixed;inset:0;background-image:url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='.04'/%3E%3C/svg%3E");pointer-events:none;z-index:9997;opacity:.35; }

/* ── NAV ── */
nav { position:fixed;top:0;left:0;right:0;z-index:1000;padding:0 5%;height:72px;display:flex;align-items:center;justify-content:space-between;transition:background .4s,backdrop-filter .4s; }
nav.scrolled { background:rgba(26,15,5,.9);backdrop-filter:blur(18px);border-bottom:1px solid rgba(244,161,53,.1); }

.nav-logo { text-decoration:none;display:flex;align-items:center;gap:10px; }
.nav-logo img { height:42px;width:auto;object-fit:contain; }
.nav-logo-txt { font-family:var(--font-display);font-size:1.4rem;font-weight:700;color:var(--cream); }
.nav-logo-txt span { color:var(--saffron); }

.nav-links { display:flex;gap:40px;list-style:none; }
.nav-links a { font-size:.82rem;font-weight:500;letter-spacing:.12em;text-transform:uppercase;color:rgba(253,246,237,.7);text-decoration:none;transition:color .25s; }
.nav-links a:hover { color:var(--saffron); }
.nav-actions { display:flex;gap:14px;align-items:center; }
.btn-ghost-nav { font-size:.82rem;font-weight:500;letter-spacing:.08em;text-transform:uppercase;color:var(--cream);text-decoration:none;padding:9px 20px;border:1px solid rgba(253,246,237,.2);border-radius:2px;transition:all .25s; }
.btn-ghost-nav:hover { border-color:var(--saffron);color:var(--saffron); }
.btn-primary-nav { font-size:.82rem;font-weight:600;letter-spacing:.08em;text-transform:uppercase;color:var(--dark);text-decoration:none;padding:10px 22px;background:var(--saffron);border-radius:2px;transition:all .25s; }
.btn-primary-nav:hover { background:var(--burnt);color:#fff; }

.hamburger { display:none;flex-direction:column;gap:5px;background:none;border:none;cursor:pointer;padding:6px; }
.hamburger span { display:block;width:24px;height:1.5px;background:var(--cream);transition:all .3s;transform-origin:center; }
.hamburger.open span:nth-child(1){ transform:translateY(6.5px) rotate(45deg); }
.hamburger.open span:nth-child(2){ opacity:0; }
.hamburger.open span:nth-child(3){ transform:translateY(-6.5px) rotate(-45deg); }

.drawer { position:fixed;inset:0;background:var(--dark2);z-index:999;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:32px;transform:translateX(100%);transition:transform .45s cubic-bezier(.77,0,.18,1); }
.drawer.open { transform:translateX(0); }
.drawer ul { list-style:none;text-align:center; }
.drawer ul li { margin-bottom:28px; }
.drawer ul li a { font-family:var(--font-serif);font-size:2.2rem;font-weight:300;color:var(--cream);text-decoration:none;letter-spacing:.04em;transition:color .2s; }
.drawer ul li a:hover { color:var(--saffron); }
.drawer-btns { display:flex;flex-direction:column;gap:12px;width:200px; }
.drawer-btns a { text-align:center;font-size:.82rem;letter-spacing:.1em;text-transform:uppercase;padding:14px;border-radius:2px;text-decoration:none;font-weight:600; }
.d-outline { border:1px solid var(--saffron);color:var(--saffron); }
.d-filled  { background:var(--saffron);color:var(--dark); }

/* ── HERO ── */
.hero { min-height:100vh;display:grid;grid-template-columns:1fr 1fr;position:relative;overflow:hidden; }
.hero-left { background:var(--dark2);display:flex;flex-direction:column;justify-content:flex-end;padding:140px 6% 80px;position:relative;z-index:2; }
.hero-left::after { content:'';position:absolute;top:0;right:-1px;bottom:0;width:80px;background:linear-gradient(to right,transparent,var(--dark));z-index:3; }
.hero-bg-word { position:absolute;bottom:-40px;left:-20px;font-family:var(--font-display);font-size:clamp(80px,14vw,200px);font-weight:900;color:rgba(244,161,53,.04);white-space:nowrap;user-select:none;line-height:1;z-index:0; }
.hero-eyebrow { display:inline-flex;align-items:center;gap:10px;font-size:.72rem;font-weight:600;letter-spacing:.2em;text-transform:uppercase;color:var(--saffron);margin-bottom:28px;position:relative;z-index:1;animation:fadeUp .8s .1s both ease; }
.hero-eyebrow::before { content:'';display:block;width:30px;height:1px;background:var(--saffron); }
h1.hero-title { font-family:var(--font-display);font-size:clamp(3rem,6vw,6rem);font-weight:900;line-height:.95;letter-spacing:-.03em;color:var(--cream);margin-bottom:28px;position:relative;z-index:1;animation:fadeUp .9s .25s both ease; }
h1.hero-title em { font-style:italic;color:var(--saffron);font-weight:700; }
.hero-desc { font-size:1rem;line-height:1.75;color:rgba(253,246,237,.6);max-width:440px;margin-bottom:40px;font-weight:300;position:relative;z-index:1;animation:fadeUp .9s .4s both ease; }
.hero-buttons { display:flex;gap:16px;flex-wrap:wrap;position:relative;z-index:1;animation:fadeUp .9s .55s both ease; }
.btn-saffron { font-size:.82rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:var(--dark);text-decoration:none;padding:16px 32px;background:var(--saffron);border-radius:2px;display:inline-flex;align-items:center;gap:10px;transition:all .3s;position:relative;overflow:hidden; }
.btn-saffron::after { content:'';position:absolute;inset:0;background:var(--burnt);transform:scaleX(0);transform-origin:left;transition:transform .35s ease;z-index:0; }
.btn-saffron:hover::after { transform:scaleX(1); }
.btn-saffron:hover { color:#fff; }
.btn-saffron span,.btn-saffron i { position:relative;z-index:1; }
.btn-outline-cream { font-size:.82rem;font-weight:500;letter-spacing:.12em;text-transform:uppercase;color:var(--cream);text-decoration:none;padding:15px 28px;border:1px solid rgba(253,246,237,.25);border-radius:2px;transition:all .3s; }
.btn-outline-cream:hover { border-color:var(--saffron);color:var(--saffron); }
.scroll-indicator { position:absolute;bottom:36px;left:6%;display:flex;align-items:center;gap:12px;font-size:.72rem;letter-spacing:.16em;text-transform:uppercase;color:rgba(253,246,237,.4);z-index:2;animation:fadeUp .9s .9s both ease; }
.scroll-line { width:40px;height:1px;background:rgba(244,161,53,.4);position:relative;overflow:hidden; }
.scroll-line::after { content:'';position:absolute;top:0;left:-100%;width:100%;height:100%;background:var(--saffron);animation:scrollAnim 2s ease infinite; }
@keyframes scrollAnim { 0%{left:-100%}100%{left:100%} }
.hero-right { position:relative;overflow:hidden;animation:fadeUp 1s .3s both ease; }
.hero-main-img { width:100%;height:100%;object-fit:cover;filter:brightness(.55) saturate(1.3); }
.hero-floats { position:absolute;inset:0;pointer-events:none; }
.float-card { position:absolute;background:rgba(26,15,5,.82);backdrop-filter:blur(16px);border:1px solid rgba(244,161,53,.2);border-radius:4px;padding:16px 20px;pointer-events:all;animation:floatUp .8s ease both; }
.float-card:nth-child(1){ bottom:30%;right:7%;animation-delay:.6s; }
.float-card:nth-child(2){ top:28%;right:10%;animation-delay:.9s; }
.float-card:nth-child(3){ bottom:12%;left:7%;animation-delay:1.1s; }
@keyframes floatUp { from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)} }
.float-label { font-size:.68rem;font-weight:600;letter-spacing:.14em;text-transform:uppercase;color:var(--saffron);margin-bottom:4px; }
.float-val { font-family:var(--font-display);font-size:1.7rem;font-weight:900;color:var(--cream);line-height:1; }
.float-sub { font-size:.72rem;color:rgba(253,246,237,.5);margin-top:2px; }
@keyframes fadeUp { from{opacity:0;transform:translateY(32px)}to{opacity:1;transform:translateY(0)} }

/* MARQUEE */
.marquee-band { background:var(--burnt);padding:14px 0;overflow:hidden; }
.marquee-track { display:flex;animation:marquee 24s linear infinite;white-space:nowrap; }
.marquee-track:hover { animation-play-state:paused; }
.marquee-item { display:flex;align-items:center;gap:16px;padding:0 28px;font-size:.78rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:var(--dark); }
.marquee-dot { width:5px;height:5px;border-radius:50%;background:var(--dark2);opacity:.4; }
@keyframes marquee { from{transform:translateX(0)}to{transform:translateX(-50%)} }

/* STATS */
.stats-strip { display:grid;grid-template-columns:repeat(4,1fr);border-top:1px solid rgba(253,246,237,.08);border-bottom:1px solid rgba(253,246,237,.08);margin:0 5%; }
.stat-cell { padding:48px 32px;border-right:1px solid rgba(253,246,237,.08);text-align:center;transition:background .35s; }
.stat-cell:last-child { border-right:none; }
.stat-cell:hover { background:rgba(244,161,53,.04); }
.stat-v { font-family:var(--font-display);font-size:clamp(2rem,4vw,3.5rem);font-weight:900;color:var(--cream);line-height:1;margin-bottom:8px;letter-spacing:-.04em; }
.stat-v span { color:var(--saffron); }
.stat-l { font-size:.75rem;font-weight:500;letter-spacing:.14em;text-transform:uppercase;color:rgba(253,246,237,.4); }

/* ══════════════════════════════════════════════════
   FEATURES — Full-bleed Image Hover Reveal
══════════════════════════════════════════════════ */
.features-section { padding:120px 5% 100px; }
.feat-intro { display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:72px;gap:40px;flex-wrap:wrap; }

.section-eyebrow { font-size:.72rem;font-weight:700;letter-spacing:.2em;text-transform:uppercase;color:var(--saffron);display:flex;align-items:center;gap:12px;margin-bottom:20px; }
.section-eyebrow::before { content:'';width:24px;height:1px;background:var(--saffron); }
h2.section-title { font-family:var(--font-display);font-size:clamp(2.2rem,4vw,4rem);font-weight:900;letter-spacing:-.03em;color:var(--cream);line-height:1.05;margin-bottom:16px; }
h2.section-title em { font-style:italic;color:var(--saffron); }
.section-sub { font-size:1rem;line-height:1.7;color:rgba(253,246,237,.5);font-weight:300;max-width:380px; }

/* GRID: asymmetric 3-col, 2-row */
.feat-img-grid {
  display: grid;
  grid-template-columns: 1.55fr 1fr 1fr;
  grid-template-rows: 360px 290px;
  gap: 5px;
}
/* Card 1: tall left (spans 2 rows) */
.fic:nth-child(1) { grid-row: span 2; }
/* Card 4: wide bottom-right (spans 2 cols) */
.fic:nth-child(4) { grid-column: span 2; }

/* Base card */
.fic {
  position: relative;
  overflow: hidden;
  cursor: default;
}

/* Image — slightly zoomed, pulls back on hover */
.fic-img {
  width:100%;height:100%;
  object-fit:cover;display:block;
  transform: scale(1.1);
  transition: transform .8s cubic-bezier(.25,.46,.45,.94), filter .5s;
  filter: brightness(.42) saturate(1.1);
}
.fic:hover .fic-img {
  transform: scale(1.0);
  filter: brightness(.18) saturate(.8);
}

/* Resting overlay — gradient + icon + name */
.fic-rest {
  position:absolute;inset:0;
  display:flex;flex-direction:column;justify-content:flex-end;
  padding:28px 30px 34px;
  background:linear-gradient(to top, rgba(26,15,5,.88) 0%, rgba(26,15,5,.08) 55%, transparent 100%);
  transition:opacity .35s ease;
}
.fic:hover .fic-rest { opacity:0;pointer-events:none; }

.fic-num   { font-family:var(--font-serif);font-size:.95rem;font-weight:300;color:rgba(244,161,53,.6);letter-spacing:.1em;margin-bottom:6px;display:block; }
.fic-icon  { font-size:2rem;display:block;margin-bottom:10px;filter:drop-shadow(0 2px 8px rgba(0,0,0,.7)); }
.fic-name  { font-family:var(--font-display);font-size:clamp(1.1rem,2.2vw,1.55rem);font-weight:700;color:var(--cream);line-height:1.1; }

/* Hover reveal — centered text floats up */
.fic-hover {
  position:absolute;inset:0;
  display:flex;flex-direction:column;justify-content:center;align-items:center;
  text-align:center;
  padding:36px 30px;
  opacity:0;
  transform:translateY(18px);
  transition:opacity .45s ease, transform .45s ease;
}
.fic:hover .fic-hover { opacity:1;transform:translateY(0); }

/* top accent line draws in */
.fic-hover::before {
  content:'';position:absolute;top:0;left:50%;
  width:0;height:3px;background:var(--saffron);
  transform:translateX(-50%);
  transition:width .55s .12s ease;
}
.fic:hover .fic-hover::before { width:55%; }

/* side glow */
.fic-hover::after {
  content:'';position:absolute;inset:0;
  background:radial-gradient(ellipse at center, rgba(244,161,53,.07) 0%, transparent 70%);
  pointer-events:none;
}

.fic-hover-icon { font-size:2.6rem;margin-bottom:14px;display:block;animation:iconPop .4s .2s both cubic-bezier(.34,1.56,.64,1); }
.fic:not(:hover) .fic-hover-icon { animation:none; }
@keyframes iconPop { from{transform:scale(.5);opacity:0}to{transform:scale(1);opacity:1} }

.fic-hover-name { font-family:var(--font-display);font-size:clamp(1.1rem,2vw,1.45rem);font-weight:700;color:var(--cream);margin-bottom:12px;line-height:1.1; }
.fic-hover-desc { font-size:.86rem;line-height:1.75;color:rgba(253,246,237,.65);font-weight:300;max-width:270px; }
.fic-hover-badge {
  margin-top:22px;
  display:inline-flex;align-items:center;gap:8px;
  font-size:.68rem;font-weight:700;letter-spacing:.16em;text-transform:uppercase;
  color:var(--saffron);
}
.fic-hover-badge::before,.fic-hover-badge::after { content:'';width:14px;height:1px;background:var(--saffron); }

/* ── HOW IT WORKS ── */
.how-section { padding:120px 5%;display:grid;grid-template-columns:1fr 1fr;gap:80px;align-items:center; }
.how-mosaic { position:relative;height:580px; }
.mosaic-img { position:absolute;object-fit:cover;border-radius:3px;filter:brightness(.7) saturate(1.2);transition:filter .4s,transform .5s; }
.mosaic-img:hover { filter:brightness(.9) saturate(1.4);transform:scale(1.02); }
.mi1{top:0;left:0;width:65%;height:55%}
.mi2{top:5%;right:0;width:32%;height:42%}
.mi3{bottom:0;left:0;width:32%;height:42%}
.mi4{bottom:5%;right:0;width:65%;height:52%}
.mosaic-badge { position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);background:var(--burnt);color:#fff;padding:18px 24px;border-radius:3px;text-align:center;z-index:4;box-shadow:0 20px 60px rgba(0,0,0,.5); }
.mosaic-badge-val { font-family:var(--font-display);font-size:2.2rem;font-weight:900;line-height:1;display:block; }
.mosaic-badge-lbl { font-size:.7rem;letter-spacing:.12em;text-transform:uppercase;opacity:.8;display:block;margin-top:4px; }
.steps { display:flex;flex-direction:column; }
.step { display:flex;align-items:flex-start;gap:28px;padding:32px 0;border-bottom:1px solid rgba(253,246,237,.07);transition:padding-left .35s; }
.step:hover { padding-left:12px; }
.step:last-child { border-bottom:none; }
.step-num { font-family:var(--font-serif);font-size:3rem;font-weight:300;color:rgba(244,161,53,.2);line-height:1;min-width:60px;transition:color .35s; }
.step:hover .step-num { color:var(--saffron); }
.step-name { font-family:var(--font-display);font-size:1.15rem;font-weight:700;color:var(--cream);margin-bottom:8px; }
.step-info { font-size:.88rem;line-height:1.7;color:rgba(253,246,237,.5);font-weight:300; }

/* SCREENS */
.screens-section { padding:120px 5%;background:rgba(10,5,2,.5); }
.screens-header { display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:64px;gap:40px;flex-wrap:wrap; }
.screens-gallery { display:grid;grid-template-columns:repeat(3,1fr);gap:20px; }
.sc-card { position:relative;border-radius:4px;overflow:hidden;aspect-ratio:3/4;transition:transform .5s cubic-bezier(.34,1.56,.64,1); }
.sc-card:hover { transform:translateY(-10px); }
.sc-card:nth-child(2){ margin-top:40px; }
.sc-card:nth-child(3){ margin-top:-20px; }
.sc-img { width:100%;height:100%;object-fit:cover;display:block;filter:brightness(.55) saturate(1.2);transition:filter .5s,transform .7s; }
.sc-card:hover .sc-img { filter:brightness(.75) saturate(1.4);transform:scale(1.05); }
.sc-overlay { position:absolute;inset:0;background:linear-gradient(to top,rgba(26,15,5,.95) 0%,rgba(26,15,5,.1) 60%,transparent 100%); }
.sc-body { position:absolute;bottom:0;left:0;right:0;padding:32px 28px; }
.sc-tag { display:inline-block;font-size:.7rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:var(--saffron);background:rgba(244,161,53,.12);border:1px solid rgba(244,161,53,.25);padding:4px 12px;border-radius:2px;margin-bottom:12px; }
.sc-name { font-family:var(--font-display);font-size:1.6rem;font-weight:700;color:var(--cream);line-height:1.1;margin-bottom:10px; }
.sc-desc { font-size:.85rem;line-height:1.6;color:rgba(253,246,237,.6);font-weight:300;transform:translateY(10px);opacity:0;transition:all .35s .1s; }
.sc-card:hover .sc-desc { transform:translateY(0);opacity:1; }

/* CTA */
.cta-section { padding:140px 5%;position:relative;overflow:hidden;text-align:center; }
.cta-bg { position:absolute;inset:0;background-image:url('https://images.unsplash.com/photo-1567188040759-fb8a883dc6d8?w=1400&q=80');background-size:cover;background-position:center;filter:brightness(.18) saturate(.8); }
.cta-grad { position:absolute;inset:0;background:linear-gradient(135deg,rgba(193,68,14,.35),rgba(26,15,5,.8)); }
.cta-content { position:relative;z-index:2; }
.cta-eyebrow { font-size:.72rem;font-weight:700;letter-spacing:.22em;text-transform:uppercase;color:var(--saffron);margin-bottom:24px;display:block; }
.cta-title { font-family:var(--font-display);font-size:clamp(2.8rem,6vw,6rem);font-weight:900;letter-spacing:-.04em;color:var(--cream);line-height:.95;margin-bottom:24px; }
.cta-title em { font-style:italic;color:var(--saffron); }
.cta-sub { font-size:1.05rem;line-height:1.7;color:rgba(253,246,237,.6);font-weight:300;max-width:480px;margin:0 auto 48px; }
.cta-btns { display:flex;gap:16px;justify-content:center;flex-wrap:wrap; }
.btn-cream-filled { font-size:.82rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:var(--dark);text-decoration:none;padding:18px 40px;background:var(--cream);border-radius:2px;display:inline-flex;align-items:center;gap:10px;transition:all .3s; }
.btn-cream-filled:hover { background:var(--saffron); }
.btn-outline-white { font-size:.82rem;font-weight:500;letter-spacing:.12em;text-transform:uppercase;color:var(--cream);text-decoration:none;padding:17px 36px;border:1px solid rgba(253,246,237,.35);border-radius:2px;transition:all .3s; }
.btn-outline-white:hover { border-color:var(--saffron);color:var(--saffron); }

/* FOOTER */
footer { padding:32px 5%;border-top:1px solid rgba(253,246,237,.08);display:flex;align-items:center;justify-content:space-between;gap:20px;flex-wrap:wrap; }
.footer-logo { display:flex;align-items:center; }
.footer-logo img { height:38px;width:auto;object-fit:contain; }
.footer-logo-txt { font-family:var(--font-display);font-size:1.2rem;font-weight:700;color:var(--cream); }
.footer-logo-txt span { color:var(--saffron); }
.footer-copy { font-size:.78rem;color:rgba(253,246,237,.3);letter-spacing:.06em; }
.footer-links { display:flex;gap:28px; }
.footer-links a { font-size:.78rem;letter-spacing:.1em;text-transform:uppercase;color:rgba(253,246,237,.4);text-decoration:none;transition:color .2s; }
.footer-links a:hover { color:var(--saffron); }

/* SCROLL ANIMS */
.anim { opacity:0;transform:translateY(28px);transition:opacity .7s ease,transform .7s ease; }
.anim.visible { opacity:1;transform:translateY(0); }
.anim.d1{transition-delay:.05s}.anim.d2{transition-delay:.15s}.anim.d3{transition-delay:.25s}
.anim.d4{transition-delay:.35s}.anim.d5{transition-delay:.45s}.anim.d6{transition-delay:.55s}

/* RESPONSIVE */
@media(max-width:900px){
  .hero{grid-template-columns:1fr;min-height:auto}
  .hero-left{padding:120px 6% 60px;min-height:80vh;justify-content:center}
  .hero-left::after{display:none}
  .hero-right{height:50vw;min-height:300px}
  .nav-links,.nav-actions{display:none}
  .hamburger{display:flex}
  .feat-img-grid{grid-template-columns:1fr 1fr;grid-template-rows:280px 280px 280px}
  .fic:nth-child(1){grid-row:span 1;grid-column:span 2}
  .fic:nth-child(4){grid-column:span 2}
  .how-section{grid-template-columns:1fr}
  .how-mosaic{height:320px}
  .screens-gallery{grid-template-columns:1fr}
  .sc-card:nth-child(2),.sc-card:nth-child(3){margin-top:0}
  .sc-card{aspect-ratio:16/9}
  .stats-strip{grid-template-columns:repeat(2,1fr)}
  .stat-cell:nth-child(2){border-right:none}
  footer{flex-direction:column;text-align:center}
  .screens-header{flex-direction:column}
  .feat-intro{flex-direction:column}
}
@media(max-width:560px){
  .feat-img-grid{grid-template-columns:1fr;grid-auto-rows:260px}
  .fic:nth-child(1),.fic:nth-child(4){grid-column:span 1}
  .stats-strip{grid-template-columns:1fr 1fr}
  body{cursor:auto}
  .cursor,.cursor-ring{display:none}
}
</style>
</head>
<body>

<div class="cursor" id="cursor"></div>
<div class="cursor-ring" id="cursor-ring"></div>

<!-- ──────────── NAV ──────────── -->
<nav id="navbar">
  <a href="/" class="nav-logo">
    <img
      src="/images/logo.png"
      alt="South Tiffins"
      onerror="this.style.display='none';this.nextElementSibling.style.display='block';"
    >
    <span class="nav-logo-txt" style="display:none;">South <span>Tiffins</span></span>
  </a>
  <ul class="nav-links">
    <li><a href="#features">Features</a></li>
    <li><a href="#how">How It Works</a></li>
    <li><a href="#screens">Screens</a></li>
  </ul>
  <div class="nav-actions">
    <a href="/login"   class="btn-ghost-nav">Admin Login</a>
    <a href="/counter" class="btn-primary-nav">Counter View</a>
  </div>
  <button class="hamburger" id="hamburger" aria-label="Toggle menu">
    <span></span><span></span><span></span>
  </button>
</nav>

<div class="drawer" id="drawer">
  <ul>
    <li><a href="#features" onclick="closeDrawer()">Features</a></li>
    <li><a href="#how"      onclick="closeDrawer()">How It Works</a></li>
    <li><a href="#screens"  onclick="closeDrawer()">Screens</a></li>
  </ul>
  <div class="drawer-btns">
    <a href="/login"   class="d-outline">Admin Login</a>
    <a href="/counter" class="d-filled">Counter View</a>
  </div>
</div>

<!-- ──────────── HERO ──────────── -->
<section class="hero">
  <div class="hero-left">
    <div class="hero-bg-word">TIFFINS</div>
    <div class="hero-eyebrow">Built for South Indian Tiffin Parlours</div>
    <h1 class="hero-title">Your tiffin,<br><em>fully digital.</em></h1>
    <p class="hero-desc">QR table ordering, real-time counter notifications, parcel orders and daily reports — everything your parlour needs. Zero commission. Full control.</p>
    <div class="hero-buttons">
      <a href="/login" class="btn-saffron"><span>Get Started</span><i class="fas fa-arrow-right"></i></a>
      <a href="#how" class="btn-outline-cream">How It Works</a>
    </div>
    <div class="scroll-indicator"><div class="scroll-line"></div>Scroll to explore</div>
  </div>
  <div class="hero-right">
    <video src="https://assets.mixkit.co/videos/preview/mixkit-abstract-background-of-a-golden-liquid-with-waves-32430-large.mp4" poster="https://images.unsplash.com/photo-1567188040759-fb8a883dc6d8?w=1200&q=85" class="hero-main-img" autoplay loop muted playsinline></video>
    <div class="hero-floats">
      <div class="float-card">
        <div class="float-label">New Order</div>
        <div class="float-val">₹145</div>
        <div class="float-sub">Table 3 · just now</div>
      </div>
      <div class="float-card">
        <div class="float-label">Today's Revenue</div>
        <div class="float-val">₹6,840</div>
        <div class="float-sub">+18% vs yesterday</div>
      </div>
      <div class="float-card">
        <div class="float-label">Commission Paid</div>
        <div class="float-val">₹0</div>
        <div class="float-sub">Zero. Forever.</div>
      </div>
    </div>
  </div>
</section>

<!-- ──────────── MARQUEE ──────────── -->
<div class="marquee-band" aria-hidden="true">
  <div class="marquee-track">
    <div class="marquee-item">QR Ordering<span class="marquee-dot"></span></div>
    <div class="marquee-item">Real-time Notifications<span class="marquee-dot"></span></div>
    <div class="marquee-item">Zero Commission<span class="marquee-dot"></span></div>
    <div class="marquee-item">Telugu + English<span class="marquee-dot"></span></div>
    <div class="marquee-item">Parcel Orders<span class="marquee-dot"></span></div>
    <div class="marquee-item">Daily PDF Reports<span class="marquee-dot"></span></div>
    <div class="marquee-item">Razorpay Integrated<span class="marquee-dot"></span></div>
    <div class="marquee-item">QR Ordering<span class="marquee-dot"></span></div>
    <div class="marquee-item">Real-time Notifications<span class="marquee-dot"></span></div>
    <div class="marquee-item">Zero Commission<span class="marquee-dot"></span></div>
    <div class="marquee-item">Telugu + English<span class="marquee-dot"></span></div>
    <div class="marquee-item">Parcel Orders<span class="marquee-dot"></span></div>
    <div class="marquee-item">Daily PDF Reports<span class="marquee-dot"></span></div>
    <div class="marquee-item">Razorpay Integrated<span class="marquee-dot"></span></div>
  </div>
</div>

<!-- ──────────── STATS ──────────── -->
<div class="stats-strip">
  <div class="stat-cell anim d1"><div class="stat-v"><span>0</span>%</div><div class="stat-l">Commission</div></div>
  <div class="stat-cell anim d2"><div class="stat-v">&lt;<span>2</span>s</div><div class="stat-l">Order Notification</div></div>
  <div class="stat-cell anim d3"><div class="stat-v">EN <span>+ తె</span></div><div class="stat-l">Bilingual Support</div></div>
  <div class="stat-cell anim d4"><div class="stat-v"><span>24</span>/7</div><div class="stat-l">System Uptime</div></div>
</div>

<!-- ──────────── FEATURES — IMAGE HOVER REVEAL ──────────── -->
<section class="features-section" id="features">
  <div class="feat-intro">
    <div>
      <div class="section-eyebrow anim d1">Features</div>
      <h2 class="section-title anim d2">Everything your parlour <em>needs</em></h2>
    </div>
    <p class="section-sub anim d3">Built for tiffin parlours — not generic software. Hover each feature to explore what it does.</p>
  </div>

  <div class="feat-img-grid">

    <!-- 1: QR Ordering — TALL LEFT -->
    <div class="fic anim d1">
      <img class="fic-img" src="https://images.unsplash.com/photo-1600891964599-f61ba0e24092?w=900&q=80" alt="QR Table Ordering">
      <div class="fic-rest">
        <span class="fic-num">01 —</span>
        <span class="fic-icon">📱</span>
        <div class="fic-name">QR Table Ordering</div>
      </div>
      <div class="fic-hover">
        <span class="fic-hover-icon">📱</span>
        <div class="fic-hover-name">QR Table Ordering</div>
        <p class="fic-hover-desc">Customer scans the QR on their table and orders directly from their phone — no app download, no login required.</p>
        <span class="fic-hover-badge">Zero friction ordering</span>
      </div>
    </div>

    <!-- 2: Real-time Counter -->
    <div class="fic anim d2">
      <img class="fic-img" src="https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=700&q=80" alt="Real-time Counter">
      <div class="fic-rest">
        <span class="fic-num">02 —</span>
        <span class="fic-icon">⚡</span>
        <div class="fic-name">Real-time Counter</div>
      </div>
      <div class="fic-hover">
        <span class="fic-hover-icon">⚡</span>
        <div class="fic-hover-name">Real-time Counter</div>
        <p class="fic-hover-desc">Orders appear on counter tablet in under 2 seconds with sound alert and visual flash. Never miss an order.</p>
        <span class="fic-hover-badge">&lt;2s notification</span>
      </div>
    </div>

    <!-- 3: Telugu + English -->
    <div class="fic anim d3">
      <img class="fic-img" src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=700&q=80" alt="Bilingual">
      <div class="fic-rest">
        <span class="fic-num">03 —</span>
        <span class="fic-icon">🌐</span>
        <div class="fic-name">Telugu + English</div>
      </div>
      <div class="fic-hover">
        <span class="fic-hover-icon">🌐</span>
        <div class="fic-hover-name">Telugu + English</div>
        <p class="fic-hover-desc">Full bilingual support across menu, labels and buttons. Customers switch languages instantly.</p>
        <span class="fic-hover-badge">EN + తెలుగు</span>
      </div>
    </div>

    <!-- 4: Parcel Orders — WIDE -->
    <div class="fic anim d4">
      <img class="fic-img" src="https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=1200&q=80" alt="Parcel Orders">
      <div class="fic-rest">
        <span class="fic-num">04 —</span>
        <span class="fic-icon">🛍️</span>
        <div class="fic-name">Parcel Orders</div>
      </div>
      <div class="fic-hover">
        <span class="fic-hover-icon">🛍️</span>
        <div class="fic-hover-name">Parcel Orders</div>
        <p class="fic-hover-desc">Customers order parcels from home and get notified when ready. No waiting, no phone calls needed.</p>
        <span class="fic-hover-badge">Home ordering</span>
      </div>
    </div>

    <!-- 5: Payments -->
    <div class="fic anim d5">
      <img class="fic-img" src="https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=700&q=80" alt="Online Payments">
      <div class="fic-rest">
        <span class="fic-num">05 —</span>
        <span class="fic-icon">💳</span>
        <div class="fic-name">Online + Cash</div>
      </div>
      <div class="fic-hover">
        <span class="fic-hover-icon">💳</span>
        <div class="fic-hover-name">Online + Cash</div>
        <p class="fic-hover-desc">Accept UPI, cards and cash. Razorpay integrated. Zero platform commission — unlike Zomato or Swiggy.</p>
        <span class="fic-hover-badge">0% commission</span>
      </div>
    </div>

    <!-- 6: Reports -->
    <div class="fic anim d6">
      <img class="fic-img" src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=700&q=80" alt="Daily Reports">
      <div class="fic-rest">
        <span class="fic-num">06 —</span>
        <span class="fic-icon">📊</span>
        <div class="fic-name">Daily Reports</div>
      </div>
      <div class="fic-hover">
        <span class="fic-hover-icon">📊</span>
        <div class="fic-hover-name">Daily Reports</div>
        <p class="fic-hover-desc">Auto PDF report every night. Daily and monthly revenue breakdown in one click. No spreadsheets.</p>
        <span class="fic-hover-badge">Auto-generated nightly</span>
      </div>
    </div>

  </div>
</section>

<!-- ──────────── HOW IT WORKS ──────────── -->
<section class="how-section" id="how">
  <div class="how-mosaic anim d1">
    <img src="https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=800&q=80"  alt="" class="mosaic-img mi1">
    <img src="https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=500&q=80"  alt="" class="mosaic-img mi2">
    <img src="https://images.unsplash.com/photo-1551218808-94e220e084d2?w=500&q=80"      alt="" class="mosaic-img mi3">
    <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=800&q=80"      alt="" class="mosaic-img mi4">
    <div class="mosaic-badge">
      <span class="mosaic-badge-val">30 min</span>
      <span class="mosaic-badge-lbl">Setup Time</span>
    </div>
  </div>
  <div>
    <div class="section-eyebrow anim d1">How It Works</div>
    <h2 class="section-title anim d2">Set up in under <em>30 minutes</em></h2>
    <p class="section-sub anim d3" style="margin-bottom:40px;">No technical knowledge required. We handle everything.</p>
    <div class="steps">
      <div class="step anim d1"><div class="step-num">01</div><div><div class="step-name">Place QR on tables</div><p class="step-info">We generate unique QR codes for each table. Print and place them. Takes 5 minutes.</p></div></div>
      <div class="step anim d2"><div class="step-num">02</div><div><div class="step-name">Customer scans and orders</div><p class="step-info">Customer scans QR, browses menu, selects items and pays online or at counter.</p></div></div>
      <div class="step anim d3"><div class="step-num">03</div><div><div class="step-name">Counter gets instant notification</div><p class="step-info">Sound alert and flash on counter tablet. Staff sees exactly what was ordered.</p></div></div>
      <div class="step anim d4"><div class="step-num">04</div><div><div class="step-name">Prepare and serve</div><p class="step-info">Update order status from pending to served. Customer tracks in real time.</p></div></div>
    </div>
  </div>
</section>

<!-- ──────────── THREE SCREENS ──────────── -->
<section class="screens-section" id="screens">
  <div class="screens-header">
    <div>
      <div class="section-eyebrow anim d1">Three Screens</div>
      <h2 class="section-title anim d2">One <em>complete</em> system</h2>
    </div>
    <p style="font-size:.95rem;line-height:1.7;color:rgba(253,246,237,.5);max-width:340px;font-weight:300;" class="anim d3">Customer phone, counter tablet and admin panel all work together in real time.</p>
  </div>
  <div class="screens-gallery">
    <div class="sc-card anim d1">
      <img src="https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=700&q=80" alt="Customer" class="sc-img">
      <div class="sc-overlay"></div>
      <div class="sc-body"><span class="sc-tag">📱 Customer</span><div class="sc-name">Menu Ordering</div><p class="sc-desc">Opens on phone after QR scan. Browse, order and pay in under 30 seconds.</p></div>
    </div>
    <div class="sc-card anim d2">
      <img src="https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=700&q=80" alt="Counter" class="sc-img">
      <div class="sc-overlay"></div>
      <div class="sc-body"><span class="sc-tag">🖥️ Counter</span><div class="sc-name">Live Orders</div><p class="sc-desc">Receives orders instantly with sound alert. Update status with one tap.</p></div>
    </div>
    <div class="sc-card anim d3">
      <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=700&q=80" alt="Admin" class="sc-img">
      <div class="sc-overlay"></div>
      <div class="sc-body"><span class="sc-tag">⚙️ Admin</span><div class="sc-name">Full Control</div><p class="sc-desc">Manage menu, billing, tables, QR codes and download reports.</p></div>
    </div>
  </div>
</section>

<!-- ──────────── CTA ──────────── -->
<section class="cta-section">
  <div class="cta-bg"></div>
  <div class="cta-grad"></div>
  <div class="cta-content">
    <span class="cta-eyebrow anim d1">Ready to Transform Your Parlour?</span>
    <h2 class="cta-title anim d2">Start <em>today.</em><br>Serve better.</h2>
    <p class="cta-sub anim d3">Login to admin panel and set up your menu, tables and QR codes in minutes. No tech skills needed.</p>
    <div class="cta-btns anim d4">
      <a href="/login" class="btn-cream-filled"><i class="fas fa-arrow-right"></i> Admin Login</a>
      <a href="/counter" class="btn-outline-white">Counter View</a>
    </div>
  </div>
</section>

<!-- ──────────── FOOTER ──────────── -->
<footer>
  <div class="footer-logo">
    <img
      src="/images/logo.png"
      alt="South Tiffins"
      onerror="this.style.display='none';this.nextElementSibling.style.display='block';"
    >
    <span class="footer-logo-txt" style="display:none;">South <span>Tiffins</span></span>
  </div>
  <p class="footer-copy">© 2025 South Tiffins. All rights reserved.</p>
  <div class="footer-links">
    <a href="/login">Admin</a>
    <a href="/counter">Counter</a>
  </div>
</footer>

<script>
// Cursor
const cursor = document.getElementById('cursor');
const ring   = document.getElementById('cursor-ring');
if(cursor && ring){
  document.addEventListener('mousemove', e => {
    cursor.style.left = e.clientX+'px'; cursor.style.top = e.clientY+'px';
    ring.style.left   = e.clientX+'px'; ring.style.top   = e.clientY+'px';
  });
}

// Nav scroll
const nav = document.getElementById('navbar');
window.addEventListener('scroll', () => nav.classList.toggle('scrolled', window.scrollY > 50), {passive:true});

// Hamburger
const hamburger = document.getElementById('hamburger');
const drawer    = document.getElementById('drawer');
hamburger.addEventListener('click', () => {
  const open = drawer.classList.toggle('open');
  hamburger.classList.toggle('open', open);
  document.body.style.overflow = open ? 'hidden' : '';
});
document.addEventListener('click', e => {
  if(!nav.contains(e.target) && !drawer.contains(e.target)) closeDrawer();
});
function closeDrawer(){
  drawer.classList.remove('open'); hamburger.classList.remove('open');
  document.body.style.overflow = '';
}
window.addEventListener('resize', () => { if(window.innerWidth>900) closeDrawer(); });

// Scroll anim
const io = new IntersectionObserver(entries => {
  entries.forEach(en => { if(en.isIntersecting){ en.target.classList.add('visible'); io.unobserve(en.target); } });
}, {threshold:0.1});
document.querySelectorAll('.anim').forEach(el => io.observe(el));

// Hero parallax
window.addEventListener('scroll', () => {
  const img = document.querySelector('.hero-main-img');
  if(img) img.style.transform = `scale(1.05) translateY(${window.scrollY*.08}px)`;
}, {passive:true});
</script>
</body>
</html>