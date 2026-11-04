<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CodeTide — Naik Level. Ketik Kode. Taklukkan Bug.</title>
  <meta name="description"
    content="CodeTide adalah game edukasi interaktif untuk belajar HTML, CSS, dan JavaScript. 40 Soal • 3 Modul • XP Grind.">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&family=JetBrains+Mono:wght@400;700&display=swap"
    rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/mobile-responsive.css') }}">
  <style>
    /* ═══════════════════════════════════════════════════
   LANDING PAGE — CodeTide
═══════════════════════════════════════════════════ */

    *,
    *::before,
    *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    :root {
      --cyan: #53EAFD;
      --purple: #ED6AFF;
      --yellow: #FFDF20;
      --green: #7BF1A8;
      --bg: #040D1A;
      --bg2: #091326;
      --border: rgba(83, 234, 253, 0.15);
      --text: #E8F4FF;
      --muted: rgba(232, 244, 255, 0.55);
      --ff-pixel: 'Press Start 2P', monospace;
      --ff-code: 'JetBrains Mono', monospace;
    }

    html {
      scroll-behavior: smooth;
    }

    body {
      background: var(--bg);
      color: var(--text);
      font-family: var(--ff-code);
      font-size: 14px;
      line-height: 1.6;
      overflow-x: hidden;
      position: relative;
      min-height: 100vh;
    }

    /* Canvas background di belakang segalanya */
    #bg-canvas {
      position: fixed;
      inset: 0;
      width: 100%;
      height: 100%;
      pointer-events: none;
      z-index: 0 !important;
    }

    .page-content {
      position: relative;
      z-index: 1;
    }

    /* ── NAVBAR ─────────────────────────────────────── */
    .lp-nav {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      z-index: 100;
      background: rgba(4, 13, 26, 0.85);
      border-bottom: 1px solid var(--border);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      transition: all 0.3s cubic-bezier(0.23, 1, 0.32, 1);
    }

    .lp-nav-inner {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 32px;
      height: 56px;
      max-width: 1280px;
      margin: 0 auto;
    }

    .lp-logo {
      font-family: var(--ff-pixel);
      font-size: 0.7rem;
      color: var(--purple);
      text-decoration: none;
      text-shadow: 0 0 16px rgba(237, 106, 255, 0.5);
      letter-spacing: 0.05em;
    }

    .lp-logo .bracket {
      color: var(--cyan);
    }

    .lp-nav-stats {
      display: flex;
      align-items: center;
      gap: 20px;
      font-size: 0.65rem;
      color: var(--muted);
      font-family: var(--ff-pixel);
    }

    .lp-nav-stats .online-dot {
      display: inline-block;
      width: 6px;
      height: 6px;
      border-radius: 50%;
      background: var(--green);
      box-shadow: 0 0 6px var(--green);
      margin-right: 4px;
      animation: pulse 2s ease-in-out infinite;
    }

    .lp-nav-links {
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .lp-nav-links a {
      font-family: var(--ff-pixel);
      font-size: 0.45rem;
      padding: 7px 14px;
      border: 1px solid var(--border);
      color: var(--muted);
      text-decoration: none;
      letter-spacing: 0.05em;
      transition: all 0.15s;
      background: transparent;
      cursor: pointer;
    }

    .lp-nav-links a:hover {
      color: var(--cyan);
      border-color: var(--cyan);
    }

    .lp-nav-links a.btn-nav-cta {
      background: var(--yellow);
      color: #000;
      border-color: var(--yellow);
      font-weight: 700;
    }

    .lp-nav-links a.btn-nav-cta:hover {
      box-shadow: 4px 4px 0 rgba(255, 223, 32, 0.3);
    }

    /* ── HERO ───────────────────────────────────────── */
    .hero {
      min-height: 100vh;
      display: flex;
      align-items: center;
      padding: 100px 32px 60px;
      max-width: 1280px;
      margin: 0 auto;
      gap: 60px;
      will-change: transform;
    }

    .hero-left {
      flex: 1;
      min-width: 0;
    }

    .hero-right {
      flex: 0 0 480px;
      max-width: 480px;
      will-change: transform;
    }

    /* Badge tags */
    .hero-tags {
      display: flex;
      gap: 8px;
      flex-wrap: wrap;
      margin-bottom: 28px;
    }

    .hero-tag {
      font-family: var(--ff-pixel);
      font-size: 0.4rem;
      padding: 6px 12px;
      letter-spacing: 0.1em;
      border: 1px solid;
    }

    .hero-tag.purple {
      color: var(--purple);
      border-color: var(--purple);
      background: rgba(237, 106, 255, 0.08);
    }

    .hero-tag.cyan {
      color: var(--cyan);
      border-color: var(--cyan);
      background: rgba(83, 234, 253, 0.08);
    }

    /* Hero title */
    .hero-title {
      font-family: var(--ff-pixel);
      line-height: 1.6;
      margin-bottom: 24px;
    }

    .hero-title .line1 {
      font-size: clamp(1.2rem, 3vw, 1.8rem);
      color: var(--text);
    }

    .hero-title .line2 {
      font-size: clamp(1.2rem, 3vw, 1.8rem);
    }

    .hero-title .word-ketik {
      color: var(--cyan);
      text-shadow: 0 0 20px rgba(83, 234, 253, 0.4);
    }

    .hero-title .word-bug {
      color: var(--purple);
      text-shadow: 0 0 20px rgba(237, 106, 255, 0.4);
    }

    /* Hero subtitle */
    .hero-sub {
      color: var(--muted);
      font-size: 0.875rem;
      max-width: 480px;
      margin-bottom: 36px;
      line-height: 1.7;
    }

    /* CTA buttons */
    .hero-ctas {
      display: flex;
      gap: 12px;
      flex-wrap: wrap;
      margin-bottom: 48px;
    }

    .btn-cta {
      font-family: var(--ff-pixel);
      font-size: 0.5rem;
      padding: 14px 24px;
      text-decoration: none;
      border: 2px solid;
      letter-spacing: 0.05em;
      transition: all 0.15s;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      cursor: pointer;
    }

    .btn-cta-primary {
      background: var(--yellow);
      color: #000;
      border-color: var(--yellow);
      box-shadow: 4px 4px 0 rgba(0, 0, 0, 0.4);
    }

    .btn-cta-primary:hover {
      transform: translate(-2px, -2px);
      box-shadow: 6px 6px 0 rgba(0, 0, 0, 0.4);
      filter: drop-shadow(0 0 8px var(--yellow));
    }

    .btn-cta-secondary {
      color: var(--cyan);
      border-color: var(--cyan);
      background: transparent;
      box-shadow: 4px 4px 0 rgba(83, 234, 253, 0.2);
    }

    .btn-cta-secondary:hover {
      background: rgba(83, 234, 253, 0.08);
      transform: translate(-2px, -2px);
      box-shadow: 6px 6px 0 rgba(83, 234, 253, 0.2);
      filter: drop-shadow(0 0 8px var(--cyan));
    }

    /* Hero stats */
    .hero-stats {
      display: flex;
      gap: 40px;
      flex-wrap: wrap;
    }

    .hero-stat {
      display: flex;
      flex-direction: column;
      gap: 4px;
    }

    .hero-stat-value {
      font-family: var(--ff-pixel);
      font-size: 0.8rem;
      color: var(--yellow);
      text-shadow: 0 0 12px rgba(255, 223, 32, 0.3);
    }

    .hero-stat-label {
      font-size: 0.625rem;
      color: var(--muted);
      letter-spacing: 0.1em;
      text-transform: uppercase;
    }

    /* ── MOCK EDITOR ────────────────────────────────── */
    .mock-editor {
      background: #0A1628;
      border: 1px solid var(--border);
      box-shadow:
        8px 8px 0 rgba(83, 234, 253, 0.1),
        0 0 40px rgba(83, 234, 253, 0.05);
      position: relative;
      overflow: hidden;
      transform-style: preserve-3d;
      transition: all 0.4s cubic-bezier(0.23, 1, 0.32, 1);
      cursor: pointer;
    }

    .mock-editor:hover {
      box-shadow:
        12px 12px 0 rgba(83, 234, 253, 0.15),
        0 0 60px rgba(83, 234, 253, 0.1),
        0 20px 40px rgba(0, 0, 0, 0.3);
      border-color: rgba(83, 234, 253, 0.3);
    }

    .mock-editor::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 2px;
      background: linear-gradient(90deg, var(--purple), var(--cyan), var(--yellow));
    }

    .editor-titlebar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 10px 16px;
      border-bottom: 1px solid var(--border);
      background: rgba(83, 234, 253, 0.03);
    }

    .editor-dots {
      display: flex;
      gap: 6px;
    }

    .editor-dot {
      width: 8px;
      height: 8px;
      border-radius: 0;
      background: var(--border);
    }

    .editor-dot.red {
      background: #FF5F57;
    }

    .editor-dot.yellow {
      background: #FFBD2E;
    }

    .editor-dot.green {
      background: #28C840;
    }

    .editor-filename {
      font-size: 0.55rem;
      color: var(--muted);
      font-family: var(--ff-pixel);
    }

    .editor-badge {
      font-size: 0.45rem;
      font-family: var(--ff-pixel);
      padding: 3px 8px;
      border: 1px solid var(--green);
      color: var(--green);
      background: rgba(123, 241, 168, 0.08);
    }

    .editor-body {
      display: grid;
      grid-template-columns: 1fr 1fr;
    }

    .editor-code-area {
      padding: 16px;
      border-right: 1px solid var(--border);
      min-height: 240px;
    }

    .code-lines {
      list-style: none;
    }

    .code-line {
      display: flex;
      gap: 16px;
      line-height: 1.8;
      font-size: 0.7rem;
      font-family: var(--ff-code);
    }

    .line-num {
      color: rgba(83, 234, 253, 0.25);
      min-width: 16px;
      text-align: right;
      user-select: none;
    }

    .code-tag {
      color: #ED6AFF;
    }

    .code-attr {
      color: #7BF1A8;
    }

    .code-val {
      color: #FFDF20;
    }

    .code-text {
      color: #E8F4FF;
    }

    .code-comment {
      color: rgba(232, 244, 255, 0.3);
    }

    /* Typing cursor blink */
    .cursor-blink {
      display: inline-block;
      width: 7px;
      height: 13px;
      background: var(--cyan);
      animation: blink 1s step-end infinite;
      vertical-align: middle;
      margin-left: 2px;
    }

    @keyframes blink {

      0%,
      100% {
        opacity: 1
      }

      50% {
        opacity: 0
      }
    }

    /* Preview panel */
    .editor-preview {
      padding: 16px;
      display: flex;
      flex-direction: column;
      gap: 8px;
    }

    .preview-label {
      font-family: var(--ff-pixel);
      font-size: 0.4rem;
      color: var(--muted);
      margin-bottom: 8px;
    }

    .preview-output {
      background: #fff;
      border-radius: 0;
      padding: 12px;
      flex: 1;
      font-family: sans-serif;
      font-size: 12px;
      color: #222;
      min-height: 100px;
    }

    .preview-output h1 {
      font-size: 16px;
      margin-bottom: 6px;
      color: #333;
    }

    .preview-output p {
      font-size: 11px;
      color: #555;
      margin-bottom: 4px;
    }

    .preview-output button {
      background: #007bff;
      color: #fff;
      border: none;
      padding: 4px 10px;
      font-size: 10px;
      cursor: pointer;
      border-radius: 2px;
      margin-top: 4px;
    }

    /* Bottom bar editor */
    .editor-footer {
      padding: 8px 16px;
      border-top: 1px solid var(--border);
      display: flex;
      align-items: center;
      justify-content: space-between;
      background: rgba(83, 234, 253, 0.02);
    }

    .editor-xp {
      font-family: var(--ff-pixel);
      font-size: 0.4rem;
      color: var(--yellow);
    }

    .editor-hint {
      font-size: 0.55rem;
      color: var(--muted);
    }

    .check-btn-mini {
      font-family: var(--ff-pixel);
      font-size: 0.4rem;
      padding: 6px 12px;
      background: var(--yellow);
      color: #000;
      border: none;
      cursor: pointer;
      box-shadow: 2px 2px 0 rgba(0, 0, 0, 0.4);
    }

    /* ── MARQUEE STRIP ──────────────────────────────── */
    .marquee-strip {
      background: #0A1628;
      border-top: 1px solid var(--border);
      border-bottom: 1px solid var(--border);
      padding: 14px 0;
      overflow: hidden;
      white-space: nowrap;
      position: relative;
    }

    .marquee-inner {
      display: inline-flex;
      gap: 0;
      animation: marquee-scroll 18s linear infinite;
    }

    .marquee-inner span {
      font-family: var(--ff-pixel);
      font-size: 0.45rem;
      letter-spacing: 0.15em;
      color: var(--muted);
      padding: 0 24px;
    }

    .marquee-inner .dot {
      color: var(--cyan);
    }

    @keyframes marquee-scroll {
      0% {
        transform: translateX(0);
      }

      100% {
        transform: translateX(-50%);
      }
    }

    /* ── HOW TO PLAY ─────────────────────────────────── */
    .section-htplay {
      max-width: 1280px;
      margin: 0 auto;
      padding: 80px 32px;
    }

    .section-title {
      font-family: var(--ff-pixel);
      font-size: clamp(0.9rem, 2.5vw, 1.4rem);
      color: var(--yellow);
      margin-bottom: 48px;
      text-shadow: 0 0 20px rgba(255, 223, 32, 0.3);
    }

    .htp-cards {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 24px;
      perspective: 1500px;
    }

    .htp-card {
      border: 1px solid var(--border);
      padding: 32px 28px;
      background: rgba(9, 19, 38, 0.6);
      position: relative;
      transition: all 0.4s cubic-bezier(0.23, 1, 0.32, 1);
      cursor: pointer;
      backdrop-filter: blur(4px);
      transform-style: preserve-3d;
      will-change: transform;
    }

    .htp-card::after {
      content: '';
      position: absolute;
      inset: 0;
      background: linear-gradient(135deg, rgba(83, 234, 253, 0.03) 0%, transparent 50%);
      opacity: 0;
      transition: opacity 0.4s;
      pointer-events: none;
      z-index: 1;
    }

    .htp-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 2px;
      opacity: 0;
      transition: opacity 0.4s;
      z-index: 2;
    }

    .htp-card:nth-child(1)::before {
      background: var(--purple);
    }

    .htp-card:nth-child(2)::before {
      background: var(--cyan);
    }

    .htp-card:nth-child(3)::before {
      background: var(--yellow);
    }

    .htp-card:hover {
      border-color: rgba(83, 234, 253, 0.4);
      box-shadow: 
        0 20px 40px rgba(0, 0, 0, 0.3),
        0 0 0 1px rgba(83, 234, 253, 0.1) inset,
        0 8px 16px rgba(83, 234, 253, 0.1);
      filter: brightness(1.1);
    }

    .htp-card:hover::before {
      opacity: 1;
    }

    .htp-card:hover::after {
      opacity: 1;
    }

    /* Inner content layer untuk depth */
    .htp-card-content {
      position: relative;
      z-index: 2;
      transform: translateZ(50px);
    }

    .htp-card-num {
      font-family: var(--ff-pixel);
      font-size: 0.5rem;
      color: var(--muted);
      margin-bottom: 16px;
      opacity: 0.5;
      transform: translateZ(20px);
    }

    .htp-card-icon {
      display: flex;
      align-items: center;
      justify-content: flex-end;
      margin-bottom: 20px;
      transform: translateZ(40px);
    }

    .htp-card-icon canvas {
      image-rendering: pixelated;
    }

    .htp-card-title {
      font-family: var(--ff-pixel);
      font-size: 0.65rem;
      color: var(--text);
      margin-bottom: 16px;
      letter-spacing: 0.05em;
      transform: translateZ(30px);
    }

    .htp-card-desc {
      color: var(--muted);
      font-size: 0.8125rem;
      line-height: 1.7;
      transform: translateZ(20px);
    }

    /* ── FEATURES SECTION ───────────────────────────── */
    .section-features {
      background: rgba(9, 19, 38, 0.5);
      border-top: 1px solid var(--border);
      border-bottom: 1px solid var(--border);
      padding: 80px 32px;
    }

    .features-inner {
      max-width: 1280px;
      margin: 0 auto;
    }

    .features-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 24px;
      margin-top: 48px;
      perspective: 1500px;
    }

    .feature-pill {
      display: flex;
      align-items: flex-start;
      gap: 16px;
      padding: 20px;
      border: 1px solid var(--border);
      background: rgba(4, 13, 26, 0.6);
      transition: all 0.4s cubic-bezier(0.23, 1, 0.32, 1);
      transform-style: preserve-3d;
      cursor: pointer;
      position: relative;
      overflow: hidden;
    }

    .feature-pill::after {
      content: '';
      position: absolute;
      inset: 0;
      background: radial-gradient(circle at var(--mouse-x, 50%) var(--mouse-y, 50%), rgba(83, 234, 253, 0.08) 0%, transparent 70%);
      opacity: 0;
      transition: opacity 0.4s;
    }

    .feature-pill:hover {
      border-color: rgba(83, 234, 253, 0.4);
      background: rgba(4, 13, 26, 0.8);
      box-shadow: 
        0 10px 30px rgba(0, 0, 0, 0.3),
        0 0 0 1px rgba(83, 234, 253, 0.1) inset;
    }

    .feature-pill:hover::after {
      opacity: 1;
    }

    .feature-pill-icon,
    .feature-pill-text {
      position: relative;
      z-index: 1;
    }

    .feature-pill-icon {
      flex-shrink: 0;
    }

    .feature-pill-text h3 {
      font-family: var(--ff-pixel);
      font-size: 0.5rem;
      color: var(--text);
      margin-bottom: 8px;
      letter-spacing: 0.05em;
    }

    .feature-pill-text p {
      font-size: 0.75rem;
      color: var(--muted);
      line-height: 1.6;
    }

    .badge-cs {
      font-family: var(--ff-pixel);
      font-size: 0.35rem;
      padding: 3px 7px;
      border: 1px solid var(--purple);
      color: var(--purple);
      background: rgba(237, 106, 255, 0.08);
      margin-left: 8px;
      vertical-align: middle;
    }

    /* ── PREVIEW SECTION ────────────────────────────── */
    .section-preview {
      max-width: 1280px;
      margin: 0 auto;
      padding: 80px 32px;
    }

    .preview-layout {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 60px;
      align-items: center;
    }

    .preview-text h2 {
      font-family: var(--ff-pixel);
      font-size: clamp(0.8rem, 2vw, 1.2rem);
      color: var(--cyan);
      margin-bottom: 24px;
      line-height: 1.7;
      text-shadow: 0 0 20px rgba(83, 234, 253, 0.3);
    }

    .preview-text p {
      color: var(--muted);
      line-height: 1.8;
      margin-bottom: 32px;
    }

    .preview-features {
      list-style: none;
      display: flex;
      flex-direction: column;
      gap: 12px;
    }

    .preview-features li {
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 0.875rem;
      color: var(--text);
    }

    .preview-features li::before {
      content: '▶';
      color: var(--yellow);
      font-size: 0.6rem;
      flex-shrink: 0;
    }

    /* ── LEADERBOARD PREVIEW ────────────────────────── */
    .lb-preview {
      background: #0A1628;
      border: 1px solid var(--border);
      box-shadow: 8px 8px 0 rgba(83, 234, 253, 0.08);
      transform-style: preserve-3d;
      transition: all 0.4s cubic-bezier(0.23, 1, 0.32, 1);
      cursor: pointer;
      position: relative;
      overflow: hidden;
    }

    .lb-preview::after {
      content: '';
      position: absolute;
      inset: 0;
      background: linear-gradient(135deg, rgba(255, 223, 32, 0.03) 0%, transparent 60%);
      opacity: 0;
      transition: opacity 0.4s;
    }

    .lb-preview:hover {
      box-shadow: 
        12px 12px 0 rgba(83, 234, 253, 0.12),
        0 20px 40px rgba(0, 0, 0, 0.3),
        0 0 0 1px rgba(83, 234, 253, 0.1) inset;
      border-color: rgba(83, 234, 253, 0.3);
    }

    .lb-preview:hover::after {
      opacity: 1;
    }

    .lb-preview-header,
    .lb-preview-rows {
      position: relative;
      z-index: 1;
    }

    .lb-preview-header {
      padding: 14px 20px;
      border-bottom: 1px solid var(--border);
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .lb-preview-title {
      font-family: var(--ff-pixel);
      font-size: 0.5rem;
      color: var(--yellow);
    }

    .lb-preview-rows {
      padding: 16px 20px;
    }

    .lb-row {
      display: flex;
      align-items: center;
      gap: 16px;
      padding: 10px 0;
      border-bottom: 1px solid rgba(83, 234, 253, 0.06);
      font-size: 0.8rem;
    }

    .lb-row:last-child {
      border-bottom: none;
    }

    .lb-rank {
      font-family: var(--ff-pixel);
      font-size: 0.45rem;
      width: 24px;
      text-align: center;
    }

    .lb-rank.r1 {
      color: var(--yellow);
    }

    .lb-rank.r2 {
      color: #C0C0C0;
    }

    .lb-rank.r3 {
      color: #CD7F32;
    }

    .lb-name {
      flex: 1;
      color: var(--text);
    }

    .lb-xp {
      font-family: var(--ff-pixel);
      font-size: 0.45rem;
      color: var(--yellow);
    }

    /* ── CTA FINAL ──────────────────────────────────── */
    .section-cta-final {
      text-align: center;
      padding: 100px 32px;
      background: rgba(9, 19, 38, 0.5);
      border-top: 1px solid var(--border);
      position: relative;
      overflow: hidden;
    }

    .section-cta-final::before {
      content: '';
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      width: 600px;
      height: 600px;
      background: radial-gradient(circle, rgba(237, 106, 255, 0.04) 0%, transparent 70%);
      pointer-events: none;
    }

    .cta-final-icons {
      display: flex;
      justify-content: center;
      gap: 24px;
      margin-bottom: 40px;
    }

    .cta-final-title {
      font-family: var(--ff-pixel);
      font-size: clamp(0.9rem, 2.5vw, 1.4rem);
      color: var(--text);
      margin-bottom: 20px;
      line-height: 1.6;
    }

    .cta-final-title .hl {
      color: var(--yellow);
    }

    .cta-final-sub {
      color: var(--muted);
      font-size: 0.875rem;
      margin-bottom: 48px;
      max-width: 500px;
      margin-left: auto;
      margin-right: auto;
    }

    .cta-final-btns {
      display: flex;
      gap: 16px;
      justify-content: center;
      flex-wrap: wrap;
    }

    /* ── FOOTER ─────────────────────────────────────── */
    .lp-footer {
      background: #040D1A;
      border-top: 1px solid var(--border);
      padding: 40px 32px;
    }

    .lp-footer-inner {
      max-width: 1280px;
      margin: 0 auto;
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 16px;
    }

    .lp-footer-copy {
      font-size: 0.7rem;
      color: var(--muted);
      font-family: var(--ff-pixel);
    }

    .lp-footer-links {
      display: flex;
      gap: 24px;
    }

    .lp-footer-links a {
      font-size: 0.625rem;
      color: var(--muted);
      text-decoration: none;
      font-family: var(--ff-pixel);
      transition: color 0.15s;
    }

    .lp-footer-links a:hover {
      color: var(--cyan);
    }

    /* ── ANIMATIONS ─────────────────────────────────── */
    @keyframes float {

      0%,
      100% {
        transform: translateY(0);
      }

      50% {
        transform: translateY(-8px);
      }
    }

    @keyframes pulse {

      0%,
      100% {
        opacity: 1;
        box-shadow: 0 0 6px var(--green);
      }

      50% {
        opacity: 0.5;
        box-shadow: 0 0 12px var(--green);
      }
    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(40px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes fadeInLeft {
      from {
        opacity: 0;
        transform: translateX(-50px);
      }
      to {
        opacity: 1;
        transform: translateX(0);
      }
    }

    @keyframes fadeInRight {
      from {
        opacity: 0;
        transform: translateX(50px);
      }
      to {
        opacity: 1;
        transform: translateX(0);
      }
    }

    @keyframes scaleIn {
      from {
        opacity: 0;
        transform: scale(0.8);
      }
      to {
        opacity: 1;
        transform: scale(1);
      }
    }

    @keyframes slideInBottom {
      from {
        opacity: 0;
        transform: translateY(60px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* Scroll reveal states */
    .scroll-reveal {
      opacity: 0;
      transition: none;
    }

    .scroll-reveal.revealed {
      animation: fadeInUp 0.8s cubic-bezier(0.23, 1, 0.32, 1) forwards;
    }

    .scroll-reveal-left.revealed {
      animation: fadeInLeft 0.8s cubic-bezier(0.23, 1, 0.32, 1) forwards;
    }

    .scroll-reveal-right.revealed {
      animation: fadeInRight 0.8s cubic-bezier(0.23, 1, 0.32, 1) forwards;
    }

    .scroll-reveal-scale.revealed {
      animation: scaleIn 0.6s cubic-bezier(0.23, 1, 0.32, 1) forwards;
    }

    /* Stagger delays for cards */
    .scroll-reveal.delay-1 { animation-delay: 0.1s; }
    .scroll-reveal.delay-2 { animation-delay: 0.2s; }
    .scroll-reveal.delay-3 { animation-delay: 0.3s; }
    .scroll-reveal.delay-4 { animation-delay: 0.4s; }

    /* Scroll progress indicator */
    .scroll-progress {
      position: fixed;
      top: 0;
      left: 0;
      height: 3px;
      background: linear-gradient(90deg, var(--cyan), var(--purple), var(--yellow));
      width: 0%;
      z-index: 9999;
      transition: width 0.1s ease;
      box-shadow: 0 0 10px rgba(83, 234, 253, 0.5);
    }

    /* ── RESPONSIVE ─────────────────────────────────── */
    @media (max-width: 900px) {
      .hero {
        flex-direction: column;
        padding-top: 80px;
      }

      .hero-right {
        flex: none;
        max-width: 100%;
        width: 100%;
      }

      .preview-layout {
        grid-template-columns: 1fr;
      }

      .editor-body {
        grid-template-columns: 1fr;
      }

      .editor-preview {
        display: none;
      }

      .lp-nav-stats {
        display: none;
      }
    }

    @media (max-width: 600px) {
      .lp-nav-links a:not(.btn-nav-cta) {
        display: none;
      }

      .htp-cards {
        grid-template-columns: 1fr;
      }

      .hero-stats {
        gap: 24px;
      }
    }
  </style>
</head>

<body>
  <div class="page-content">

    <!-- Scroll Progress Bar -->
    <div class="scroll-progress" id="scrollProgress"></div>

    <!-- ═══ NAVBAR ═════════════════════════════════════ -->
    <nav class="lp-nav">
      <div class="lp-nav-inner">
        <a href="{{ route('home') }}" class="lp-logo">
          <span class="bracket">&lt;</span>Code<span style="color:var(--cyan)">/</span>Tide<span
            class="bracket">&gt;</span>
        </a>
        <div class="lp-nav-stats">
          <span><span class="online-dot"></span>Online</span>
          <span>⚡ XP TOTAL</span>
        </div>
        <div class="lp-nav-links">
          @auth
            <a href="{{ route('dashboard') }}">DASHBOARD</a>
            <a href="{{ route('modules.index') }}">MATERI</a>
            <a href="{{ route('leaderboard.index') }}">RANKING</a>
          @else
            <a href="{{ route('login') }}">LOGIN</a>
            <a href="{{ route('register') }}" class="btn-nav-cta">MULAI &rarr;</a>
          @endauth
        </div>
      </div>
    </nav>

    <!-- ═══ HERO ════════════════════════════════════════ -->
    <section class="hero">
      <!-- Left: teks -->
      <div class="hero-left scroll-reveal-left">
        <div class="hero-tags">
          <span class="hero-tag purple">SKRIPSI 01 // WEB QUEST</span>
          <span class="hero-tag cyan">HTML • CSS • JS</span>
        </div>

        <h1 class="hero-title">
          <span class="line1">NAIK LEVEL. <span class="word-ketik">KETIK</span></span><br>
          <span class="line2">KODE. <span class="word-bug">TAKLUKKAN BUG.</span></span>
        </h1>

        <p class="hero-sub">
          Game edukasi pemrograman web untuk mahasiswa PTIK UNM semester 3–5.
          Belajar HTML, CSS, dan JavaScript lewat misi yang terasa seperti arcade.
        </p>

        <div class="hero-ctas">
          @auth
            <a href="{{ route('dashboard') }}" class="btn-cta btn-cta-primary" id="startBtn">
              LANJUTKAN &rarr;
            </a>
            <a href="{{ route('modules.index') }}" class="btn-cta btn-cta-secondary" id="viewBtn">
              LIHAT SOAL
            </a>
          @else
            <a href="{{ route('register') }}" class="btn-cta btn-cta-primary" id="startBtn">
              MULAI QUEST &rarr;
            </a>
            <a href="{{ route('login') }}" class="btn-cta btn-cta-secondary" id="viewBtn">
              LOGIN
            </a>
          @endauth
        </div>

        <div class="hero-stats">
          <div class="hero-stat">
            <span class="hero-stat-value" id="statLevels">40 SOAL</span>
            <span class="hero-stat-label">Quest Path</span>
          </div>
          <div class="hero-stat">
            <span class="hero-stat-value" id="statModules">3 MODUL</span>
            <span class="hero-stat-label">Core Skills</span>
          </div>
          <div class="hero-stat">
            <span class="hero-stat-value" id="statXP">= XP GRIND</span>
            <span class="hero-stat-label">Keep Playing</span>
          </div>
        </div>
      </div>

      <!-- Right: Mock editor -->
      <div class="hero-right scroll-reveal-right">
        <div class="mock-editor">
          <div class="editor-titlebar">
            <div class="editor-dots">
              <div class="editor-dot red"></div>
              <div class="editor-dot yellow"></div>
              <div class="editor-dot green"></div>
            </div>
            <span class="editor-filename">LEVEL 01 // HTML</span>
            <span class="editor-badge">● AKTIF</span>
          </div>

          <div class="editor-body">
            <!-- Kode -->
            <div class="editor-code-area">
              <ul class="code-lines">
                <li class="code-line"><span class="line-num">1</span><span class="code-tag">&lt;!DOCTYPE html&gt;</span>
                </li>
                <li class="code-line"><span class="line-num">2</span><span class="code-tag">&lt;html&gt;</span></li>
                <li class="code-line"><span class="line-num">3</span>&nbsp;&nbsp;<span
                    class="code-tag">&lt;body&gt;</span></li>
                <li class="code-line"><span class="line-num">4</span>&nbsp;&nbsp;&nbsp;&nbsp;<span
                    class="code-tag">&lt;h1&gt;</span><span class="code-text">Hello!</span><span
                    class="code-tag">&lt;/h1&gt;</span></li>
                <li class="code-line"><span class="line-num">5</span>&nbsp;&nbsp;&nbsp;&nbsp;<span
                    class="code-tag">&lt;p&gt;</span><span class="code-text">Ini paragraf</span><span
                    class="code-tag">&lt;/p&gt;</span></li>
                <li class="code-line"><span class="line-num">6</span>&nbsp;&nbsp;&nbsp;&nbsp;<span
                    class="code-tag">&lt;button</span> <span class="code-attr">class</span>=<span
                    class="code-val">"btn"</span><span class="code-tag">&gt;</span></li>
                <li class="code-line"><span class="line-num">7</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span
                    class="code-text">Klik Aku</span></li>
                <li class="code-line"><span class="line-num">8</span>&nbsp;&nbsp;&nbsp;&nbsp;<span
                    class="code-tag">&lt;/button&gt;</span><span class="cursor-blink"></span></li>
                <li class="code-line"><span class="line-num">9</span>&nbsp;&nbsp;<span
                    class="code-tag">&lt;/body&gt;</span></li>
                <li class="code-line"><span class="line-num">10</span><span class="code-tag">&lt;/html&gt;</span></li>
              </ul>
            </div>

            <!-- Preview -->
            <div class="editor-preview">
              <div class="preview-label">LIVE PREVIEW</div>
              <div class="preview-output">
                <h1>Hello!</h1>
                <p>Ini paragraf</p>
                <button>Klik Aku</button>
              </div>
            </div>
          </div>

          <div class="editor-footer">
            <span class="editor-xp">⚡ +10 XP</span>
            <span class="editor-hint">Selesaikan untuk lanjut →</span>
            <button class="check-btn-mini">CEKLIS</button>
          </div>
        </div>
      </div>
    </section>

    <!-- ═══ MARQUEE STRIP ═══════════════════════════════ -->
    <div class="marquee-strip">
      <div class="marquee-inner" id="marqueeInner">
        <span>CODE</span><span class="dot">•</span>
        <span>PLAY</span><span class="dot">•</span>
        <span>LEVEL UP</span><span class="dot">•</span>
        <span>CODE</span><span class="dot">•</span>
        <span>PLAY</span><span class="dot">•</span>
        <span>LEVEL UP</span><span class="dot">•</span>
        <span>CODE</span><span class="dot">•</span>
        <span>PLAY</span><span class="dot">•</span>
        <span>LEVEL UP</span><span class="dot">•</span>
        <span>HTML</span><span class="dot">•</span>
        <span>CSS</span><span class="dot">•</span>
        <span>JAVASCRIPT</span><span class="dot">•</span>
        <!-- duplikat untuk seamless loop -->
        <span>CODE</span><span class="dot">•</span>
        <span>PLAY</span><span class="dot">•</span>
        <span>LEVEL UP</span><span class="dot">•</span>
        <span>CODE</span><span class="dot">•</span>
        <span>PLAY</span><span class="dot">•</span>
        <span>LEVEL UP</span><span class="dot">•</span>
        <span>CODE</span><span class="dot">•</span>
        <span>PLAY</span><span class="dot">•</span>
        <span>LEVEL UP</span><span class="dot">•</span>
        <span>HTML</span><span class="dot">•</span>
        <span>CSS</span><span class="dot">•</span>
        <span>JAVASCRIPT</span><span class="dot">•</span>
      </div>
    </div>

    <!-- ═══ HOW TO PLAY ════════════════════════════════ -->
    <section class="section-htplay">
      <h2 class="section-title scroll-reveal">HOW TO PLAY</h2>
      <div class="htp-cards">
        <div class="htp-card scroll-reveal delay-1" id="htpCard1">
          <div class="htp-card-content">
            <div class="htp-card-num">01</div>
            <div class="htp-card-icon scroll-reveal-scale" id="htpIcon1"></div>
            <h3 class="htp-card-title">TULIS KODE</h3>
            <p class="htp-card-desc">Pecahkan misi HTML, CSS, dan JavaScript di editor interaktif. Kode langsung terlihat
              hasilnya.</p>
          </div>
        </div>
        <div class="htp-card scroll-reveal delay-2" id="htpCard2">
          <div class="htp-card-content">
            <div class="htp-card-num">02</div>
            <div class="htp-card-icon scroll-reveal-scale" id="htpIcon2"></div>
            <h3 class="htp-card-title">LIHAT HASILNYA</h3>
            <p class="htp-card-desc">Live preview berubah seketika saat kamu mengetik. Tidak perlu refresh atau setup
              apapun.</p>
          </div>
        </div>
        <div class="htp-card scroll-reveal delay-3" id="htpCard3">
          <div class="htp-card-content">
            <div class="htp-card-num">03</div>
            <div class="htp-card-icon scroll-reveal-scale" id="htpIcon3"></div>
            <h3 class="htp-card-title">NAIK LEVEL</h3>
            <p class="htp-card-desc">Jawaban benar memberi XP, badge, dan akses ke level baru. Compete di leaderboard!</p>
          </div>
        </div>
      </div>
    </section>

    <!-- ═══ FEATURES ════════════════════════════════════ -->
    <section class="section-features">
      <div class="features-inner">
        <h2 class="section-title scroll-reveal">JENIS TANTANGAN</h2>
        <div class="features-grid scroll-reveal" id="featuresGrid"></div>
      </div>
    </section>

    <!-- ═══ PREVIEW (Leaderboard + text) ═══════════════ -->
    <section class="section-preview">
      <div class="preview-layout">
        <div class="preview-text scroll-reveal-left">
          <h2>BERSAING DI<br><span style="color:var(--yellow)">LEADERBOARD</span><br>GLOBAL</h2>
          <p>Setiap soal yang kamu selesaikan pertama kali memberi <strong style="color:var(--yellow)">+10 XP</strong>.
            Kumpulkan XP, panjat ranking, dan buktikan siapa yang paling cepat menguasai web.</p>
          <ul class="preview-features">
            <li>Real-time ranking berdasarkan total XP</li>
            <li>Badge eksklusif untuk pencapaian khusus</li>
            <li>Kostum karakter unik yang bisa di-unlock</li>
            <li>Progress tersimpan otomatis</li>
          </ul>
        </div>

        <div class="lb-preview scroll-reveal-right">
          <div class="lb-preview-header">
            <span class="lb-preview-title" id="lbPreviewTitle">TOP RANKING</span>
            <span style="font-size:0.55rem;color:var(--muted);">Live</span>
          </div>
          <div class="lb-preview-rows" id="lbPreviewRows">
            <!-- Leaderboard akan diisi via JavaScript atau server-side -->
            <div class="lb-row">
              <span class="lb-rank r1">#1</span>
              <span class="lb-name">Player 1</span>
              <span class="lb-xp">⚡ 150 XP</span>
            </div>
            <div class="lb-row">
              <span class="lb-rank r2">#2</span>
              <span class="lb-name">Player 2</span>
              <span class="lb-xp">⚡ 120 XP</span>
            </div>
            <div class="lb-row">
              <span class="lb-rank r3">#3</span>
              <span class="lb-name">Player 3</span>
              <span class="lb-xp">⚡ 100 XP</span>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ═══ CTA FINAL ════════════════════════════════════ -->
    <section class="section-cta-final scroll-reveal">
      <div class="cta-final-icons" id="ctaFinalIcons"></div>
      <h2 class="cta-final-title">
        SIAP MULAI<br><span class="hl">PETUALANGAN</span> CODING?
      </h2>
      <p class="cta-final-sub">Masukkan nickname dan kode peserta, lalu langsung mulai belajar.</p>
      <div class="cta-final-btns">
        @auth
          <a href="{{ route('dashboard') }}" class="btn-cta btn-cta-primary" id="ctaLoginBtn">LANJUTKAN &rarr;</a>
          <a href="{{ route('modules.index') }}" class="btn-cta btn-cta-secondary" id="ctaMateriBtn">LIHAT MATERI</a>
        @else
          <a href="{{ route('register') }}" class="btn-cta btn-cta-primary" id="ctaLoginBtn">DAFTAR &amp; MAIN &rarr;</a>
          <a href="{{ route('login') }}" class="btn-cta btn-cta-secondary" id="ctaMateriBtn">LOGIN</a>
        @endauth
      </div>
    </section>

    <!-- ═══ FOOTER ════════════════════════════════════════ -->
    <footer class="lp-footer">
      <div class="lp-footer-inner">
        <span class="lp-footer-copy">&lt;CodeTide&gt; — Produk Skripsi PTIK UNM</span>
        <div class="lp-footer-links">
          <a href="{{ route('login') }}">Login</a>
          <a href="{{ route('register') }}">Register</a>
          @auth
            <a href="{{ route('leaderboard.index') }}">Ranking</a>
          @endauth
        </div>
      </div>
    </footer>

  </div><!-- .page-content -->

  <!-- ═══ SCRIPTS ═══════════════════════════════════════ -->
  <script src="{{ asset('js/pxlkit-renderer.js') }}"></script>
  <script src="{{ asset('js/scene3d.js') }}"></script>
  <script>
    /* ── How To Play icons ── */
    const htpDefs = [
      { id: 'htpIcon1', data: PXL_PLAY, scale: 5 },
      { id: 'htpIcon2', data: PXL_GLOW_PULSE, scale: 5 },
      { id: 'htpIcon3', data: PXL_SPARKLE_STAR, scale: 5 },
    ];
    htpDefs.forEach(({ id, data, scale }) => {
      const el = document.getElementById(id);
      if (el) el.appendChild(PXLKIT.render(data, scale));
    });

    /* ── Features grid ── */
    const featuresDefs = [
      { icon: PXL_CHECK, title: 'Reguler', desc: 'Pelajari konsep HTML/CSS/JS step-by-step.', cs: false },
      { icon: PXL_BUG, title: 'Bug Hunt', desc: 'Temukan dan perbaiki bug dalam kode.', cs: true },
      { icon: PXL_TARGET_HIT, title: 'Clone Challenge', desc: 'Tiru tampilan UI yang diberikan.', cs: true },
      { icon: PXL_LIGHTNING, title: 'Mode Kompetisi', desc: 'Selesaikan soal secepat mungkin.', cs: true },
    ];
    const featGrid = document.getElementById('featuresGrid');
    featuresDefs.forEach(f => {
      const pill = document.createElement('div');
      pill.className = 'feature-pill';
      const icon = PXLKIT.createElement(f.icon, 3);
      icon.className = 'feature-pill-icon';
      const text = document.createElement('div');
      text.className = 'feature-pill-text';
      text.innerHTML = `
    <h3>${f.title}${f.cs ? '<span class="badge-cs">SOON</span>' : ''}</h3>
    <p>${f.desc}</p>
  `;
      pill.appendChild(icon);
      pill.appendChild(text);
      featGrid.appendChild(pill);
    });

    /* ── CTA final icons ── */
    const ctaFinal = document.getElementById('ctaFinalIcons');
    [PXL_TROPHY, PXL_FLOATING_GEM, PXL_SPARKLE_STAR, PXL_MEDAL].forEach((d, i) => {
      const w = PXLKIT.createElement(d, 4);
      w.style.animation = `float ${2.5 + i * 0.3}s ease-in-out ${i * 0.2}s infinite`;
      ctaFinal.appendChild(w);
    });

    /* ── 3D Tilt Effect untuk Cards (Advanced) ── */
    function add3DTiltEffect(selector, maxTilt = 12, glareEffect = true) {
      const elements = document.querySelectorAll(selector);
      
      elements.forEach(el => {
        let tiltX = 0, tiltY = 0;
        let targetTiltX = 0, targetTiltY = 0;
        let isHovering = false;

        el.addEventListener('mouseenter', () => {
          isHovering = true;
        });

        el.addEventListener('mousemove', (e) => {
          if (!isHovering) return;
          
          const rect = el.getBoundingClientRect();
          const x = e.clientX - rect.left;
          const y = e.clientY - rect.top;
          
          const centerX = rect.width / 2;
          const centerY = rect.height / 2;
          
          // Calculate tilt with smooth easing
          targetTiltX = ((y - centerY) / centerY) * -maxTilt;
          targetTiltY = ((x - centerX) / centerX) * maxTilt;

          // Update mouse position for radial gradient
          if (glareEffect) {
            el.style.setProperty('--mouse-x', `${(x / rect.width) * 100}%`);
            el.style.setProperty('--mouse-y', `${(y / rect.height) * 100}%`);
          }
        });
        
        el.addEventListener('mouseleave', () => {
          isHovering = false;
          targetTiltX = 0;
          targetTiltY = 0;
        });

        // Smooth animation loop
        function animate() {
          if (Math.abs(tiltX - targetTiltX) > 0.1 || Math.abs(tiltY - targetTiltY) > 0.1) {
            tiltX += (targetTiltX - tiltX) * 0.15;
            tiltY += (targetTiltY - tiltY) * 0.15;
            
            const scale = isHovering ? 1.03 : 1;
            const translateZ = isHovering ? 20 : 0;
            
            el.style.transform = `
              perspective(1500px) 
              rotateX(${tiltX}deg) 
              rotateY(${tiltY}deg) 
              scale3d(${scale}, ${scale}, ${scale})
              translateZ(${translateZ}px)
            `;
          }
          requestAnimationFrame(animate);
        }
        animate();
      });
    }

    // Apply 3D tilt to cards with different intensities
    document.addEventListener('DOMContentLoaded', () => {
      add3DTiltEffect('.htp-card', 15, true);
      add3DTiltEffect('.feature-pill', 8, true);
      add3DTiltEffect('.mock-editor', 10, false);
      add3DTiltEffect('.lb-preview', 10, false);
    });

    /* ── Parallax Scroll Effect ── */
    let ticking = false;
    let lastScrollY = 0;
    
    window.addEventListener('scroll', () => {
      lastScrollY = window.scrollY;
      
      if (!ticking) {
        window.requestAnimationFrame(() => {
          handleParallax(lastScrollY);
          updateScrollProgress(lastScrollY);
          ticking = false;
        });
        ticking = true;
      }
    });

    function handleParallax(scrollY) {
      // Parallax untuk hero right (mock editor)
      const heroRight = document.querySelector('.hero-right');
      if (heroRight && scrollY < window.innerHeight) {
        const speed = 0.15;
        heroRight.style.transform = `translateY(${scrollY * speed}px)`;
      }
      
      // Parallax untuk hero left (text)
      const heroLeft = document.querySelector('.hero-left');
      if (heroLeft && scrollY < window.innerHeight) {
        const speed = 0.08;
        heroLeft.style.transform = `translateY(${scrollY * speed}px)`;
      }
      
      // Parallax untuk icons di How To Play
      const htpIcons = document.querySelectorAll('.htp-card-icon');
      htpIcons.forEach((icon, index) => {
        const rect = icon.getBoundingClientRect();
        if (rect.top < window.innerHeight && rect.bottom > 0) {
          const speed = 0.03 + (index * 0.015);
          const offset = (scrollY - rect.top) * speed;
          icon.style.transform = `translateY(${offset}px) translateZ(40px)`;
        }
      });
    }

    function updateScrollProgress(scrollY) {
      const docHeight = document.documentElement.scrollHeight - window.innerHeight;
      const progress = (scrollY / docHeight) * 100;
      const progressBar = document.getElementById('scrollProgress');
      if (progressBar) {
        progressBar.style.width = `${progress}%`;
      }
    }

    /* ── Smooth Scroll Reveal System ── */
    const revealObserver = new IntersectionObserver((entries) => {
      entries.forEach((entry, index) => {
        if (entry.isIntersecting) {
          // Add delay based on index for stagger effect
          setTimeout(() => {
            entry.target.classList.add('revealed');
          }, index * 50);
          
          // Optional: unobserve after reveal
          revealObserver.unobserve(entry.target);
        }
      });
    }, {
      threshold: 0.1,
      rootMargin: '0px 0px -50px 0px' // Trigger slightly before element enters viewport
    });

    // Observe all scroll-reveal elements
    document.addEventListener('DOMContentLoaded', () => {
      const scrollRevealElements = document.querySelectorAll(
        '.scroll-reveal, .scroll-reveal-left, .scroll-reveal-right, .scroll-reveal-scale'
      );
      
      scrollRevealElements.forEach(el => {
        revealObserver.observe(el);
      });

      // Hero elements reveal immediately on load
      setTimeout(() => {
        document.querySelector('.hero-left')?.classList.add('revealed');
        document.querySelector('.hero-right')?.classList.add('revealed');
      }, 100);
    });

    /* ── Interactive Cursor Glow ── */
    const createCursorGlow = () => {
      const cursorGlow = document.createElement('div');
      cursorGlow.style.cssText = `
        position: fixed;
        width: 400px;
        height: 400px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(83, 234, 253, 0.08) 0%, transparent 70%);
        pointer-events: none;
        z-index: 9999;
        mix-blend-mode: screen;
        transition: opacity 0.3s;
        opacity: 0;
      `;
      document.body.appendChild(cursorGlow);

      let mouseX = 0, mouseY = 0;
      let glowX = 0, glowY = 0;

      document.addEventListener('mousemove', (e) => {
        mouseX = e.clientX;
        mouseY = e.clientY;
        cursorGlow.style.opacity = '1';
      });

      document.addEventListener('mouseleave', () => {
        cursorGlow.style.opacity = '0';
      });

      function animateGlow() {
        glowX += (mouseX - glowX) * 0.1;
        glowY += (mouseY - glowY) * 0.1;
        cursorGlow.style.transform = `translate(${glowX - 200}px, ${glowY - 200}px)`;
        requestAnimationFrame(animateGlow);
      }
      animateGlow();
    };

    // Initialize cursor glow on desktop only
    if (window.innerWidth > 900) {
      createCursorGlow();
    }

    /* ── Smooth Scroll for Anchor Links ── */
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
          target.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
          });
        }
      });
    });

    /* ── Navbar Scroll Effect ── */
    let lastScroll = 0;
    const navbar = document.querySelector('.lp-nav');
    
    window.addEventListener('scroll', () => {
      const currentScroll = window.scrollY;
      
      if (currentScroll > 100) {
        navbar.style.background = 'rgba(4, 13, 26, 0.95)';
        navbar.style.boxShadow = '0 4px 20px rgba(0, 0, 0, 0.3)';
      } else {
        navbar.style.background = 'rgba(4, 13, 26, 0.85)';
        navbar.style.boxShadow = 'none';
      }
      
      // Hide navbar on scroll down, show on scroll up
      if (currentScroll > lastScroll && currentScroll > 200) {
        navbar.style.transform = 'translateY(-100%)';
      } else {
        navbar.style.transform = 'translateY(0)';
      }
      
      lastScroll = currentScroll;
    });

    /* ── Scroll to Top Button ── */
    const createScrollTopBtn = () => {
      const btn = document.createElement('button');
      btn.innerHTML = '↑';
      btn.style.cssText = `
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 50px;
        height: 50px;
        background: var(--cyan);
        color: #000;
        border: 2px solid var(--cyan);
        font-family: var(--ff-pixel);
        font-size: 1.2rem;
        cursor: pointer;
        z-index: 1000;
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.3s;
        box-shadow: 4px 4px 0 rgba(0, 0, 0, 0.3);
      `;
      
      btn.addEventListener('click', () => {
        window.scrollTo({
          top: 0,
          behavior: 'smooth'
        });
      });
      
      btn.addEventListener('mouseenter', () => {
        btn.style.transform = 'translateY(-5px)';
        btn.style.boxShadow = '6px 6px 0 rgba(0, 0, 0, 0.3)';
      });
      
      btn.addEventListener('mouseleave', () => {
        btn.style.transform = 'translateY(0)';
        btn.style.boxShadow = '4px 4px 0 rgba(0, 0, 0, 0.3)';
      });
      
      document.body.appendChild(btn);
      
      // Show/hide based on scroll position
      window.addEventListener('scroll', () => {
        if (window.scrollY > 500) {
          btn.style.opacity = '1';
          btn.style.transform = 'translateY(0)';
        } else {
          btn.style.opacity = '0';
          btn.style.transform = 'translateY(20px)';
        }
      });
    };
    
    createScrollTopBtn();
  </script>
</body>

</html>
