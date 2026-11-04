<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Materi: {{ $material->title }} — CodeTide</title>
    <meta name="description" content="Baca materi pembelajaran {{ $material->title }} sebelum mengerjakan soal di CodeTide.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&family=JetBrains+Mono:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/mobile-responsive.css') }}">
    <style>
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --cyan:    #53EAFD;
            --purple:  #ED6AFF;
            --yellow:  #FFDF20;
            --green:   #7BF1A8;
            --bg:      #040D1A;
            --bg2:     #091326;
            --surface: #0D1B30;
            --border:  rgba(83, 234, 253, 0.15);
            --text:    #E8F4FF;
            --muted:   rgba(232, 244, 255, 0.55);
            --ff-pixel: 'Press Start 2P', monospace;
            --ff-code:  'JetBrains Mono', monospace;
            --sp-2: 8px;
            --sp-3: 12px;
            --sp-4: 16px;
            --sp-5: 24px;
            --sp-6: 32px;
            --sp-8: 48px;
        }

        html { scroll-behavior: smooth; }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: var(--ff-code);
            font-size: 14px;
            line-height: 1.7;
            overflow-x: hidden;
            position: relative;
            min-height: 100vh;
        }

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

        /* ── NAVBAR ───────────────────────────────── */
        .lp-nav {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 100;
            background: rgba(4, 13, 26, 0.85);
            border-bottom: 1px solid var(--border);
            backdrop-filter: blur(12px);
            transition: all 0.3s;
        }

        .lp-nav-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
            height: 64px;
            max-width: 1280px;
            margin: 0 auto;
        }

        .lp-logo {
            font-family: var(--ff-pixel);
            font-size: 0.75rem;
            color: var(--purple);
            text-decoration: none;
            text-shadow: 0 0 16px rgba(237, 106, 255, 0.5);
            letter-spacing: 0.05em;
        }

        .lp-logo .bracket { color: var(--cyan); }

        .lp-nav-links {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .lp-nav-links a, .lp-nav-links button {
            font-family: var(--ff-pixel);
            font-size: 0.5rem;
            padding: 8px 16px;
            border: 1px solid var(--border);
            color: var(--muted);
            text-decoration: none;
            letter-spacing: 0.05em;
            transition: all 0.15s;
            background: transparent;
            cursor: pointer;
            white-space: nowrap;
        }

        .lp-nav-links a:hover, .lp-nav-links button:hover {
            color: var(--cyan);
            border-color: var(--cyan);
            transform: translateY(-1px);
        }

        .lp-nav-links .btn-logout {
            color: #FF6B6B;
            border-color: rgba(255, 107, 107, 0.3);
        }

        /* ── READING PROGRESS BAR ──────────────────── */
        #reading-progress {
            position: fixed;
            top: 64px;
            left: 0;
            width: 0%;
            height: 3px;
            background: linear-gradient(90deg, var(--cyan), var(--purple));
            z-index: 101;
            transition: width 0.1s linear;
            box-shadow: 0 0 8px rgba(83, 234, 253, 0.5);
        }

        /* ── PAGE LAYOUT ──────────────────────────── */
        .materi-page {
            max-width: 820px;
            margin: 0 auto;
            padding: 100px 32px 80px;
        }

        /* ── BREADCRUMB ───────────────────────────── */
        .breadcrumb {
            display: flex;
            align-items: center;
            gap: var(--sp-2);
            margin-bottom: var(--sp-5);
            font-size: 0.75rem;
            flex-wrap: wrap;
        }

        .breadcrumb a {
            color: var(--cyan);
            text-decoration: none;
            transition: opacity 0.2s;
        }

        .breadcrumb a:hover { opacity: 0.75; text-decoration: underline; }

        .breadcrumb .sep { color: var(--muted); }
        .breadcrumb .cur { color: var(--muted); }

        /* ── PAGE HEADER ──────────────────────────── */
        .materi-page-header {
            margin-bottom: var(--sp-8);
            padding-bottom: var(--sp-5);
            border-bottom: 2px solid var(--border);
        }

        .materi-badge {
            display: inline-block;
            font-family: var(--ff-pixel);
            font-size: 0.45rem;
            padding: 5px 10px;
            border: 2px solid var(--purple);
            color: var(--purple);
            background: rgba(237, 106, 255, 0.08);
            letter-spacing: 0.08em;
            margin-bottom: var(--sp-4);
        }

        .materi-page-header h1 {
            font-family: var(--ff-pixel);
            font-size: 1.35rem;
            color: var(--cyan);
            line-height: 1.6;
            margin-bottom: var(--sp-3);
            text-shadow: 0 0 24px rgba(83, 234, 253, 0.25);
        }

        .materi-meta {
            display: flex;
            align-items: center;
            gap: var(--sp-4);
            font-size: 0.8rem;
            color: var(--muted);
            flex-wrap: wrap;
        }

        .materi-meta span {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* ── MATERI CONTENT AREA ──────────────────── */
        .materi-content-wrapper {
            background: rgba(9, 19, 38, 0.5);
            border: 2px solid var(--border);
            padding: var(--sp-6) var(--sp-6);
            margin-bottom: var(--sp-8);
            position: relative;
        }

        .materi-content-wrapper::before {
            content: '// MATERI';
            position: absolute;
            top: -12px;
            left: 24px;
            font-family: var(--ff-pixel);
            font-size: 0.45rem;
            color: var(--cyan);
            background: var(--bg);
            padding: 2px 10px;
            border: 1px solid var(--cyan);
            letter-spacing: 0.1em;
        }

        /* ── MATERI CONTENT STYLES ────────────────── */
        .materi-content {
            font-family: var(--ff-code);
            line-height: 1.75;
            color: var(--text);
        }

        .materi-content h2 {
            font-family: var(--ff-pixel);
            font-size: 1rem;
            color: var(--cyan);
            margin-bottom: var(--sp-5);
            line-height: 1.7;
            text-shadow: 0 0 16px rgba(83, 234, 253, 0.2);
        }

        .materi-content h3 {
            font-family: var(--ff-pixel);
            font-size: 0.7rem;
            color: var(--purple);
            margin-top: var(--sp-6);
            margin-bottom: var(--sp-3);
            line-height: 1.6;
            padding-left: var(--sp-3);
            border-left: 3px solid var(--purple);
        }

        .materi-content p {
            margin-bottom: var(--sp-4);
            font-size: 0.925rem;
            color: rgba(232, 244, 255, 0.9);
        }

        .materi-content ul,
        .materi-content ol {
            margin: var(--sp-3) 0 var(--sp-4) var(--sp-5);
        }

        .materi-content li {
            margin-bottom: var(--sp-2);
            font-size: 0.9rem;
            color: rgba(232, 244, 255, 0.85);
        }

        .materi-content strong {
            color: var(--yellow);
            font-weight: 700;
        }

        .materi-content em {
            color: var(--green);
            font-style: italic;
        }

        /* ── CODE BLOCKS ──────────────────────────── */
        .materi-content pre {
            background: var(--bg);
            border: 2px solid rgba(83, 234, 253, 0.25);
            border-left: 4px solid var(--cyan);
            padding: var(--sp-5);
            overflow-x: auto;
            margin: var(--sp-4) 0 var(--sp-5);
            position: relative;
        }

        .materi-content pre::before {
            content: 'CODE';
            position: absolute;
            top: -1px;
            right: 12px;
            font-family: var(--ff-pixel);
            font-size: 0.4rem;
            color: var(--cyan);
            opacity: 0.5;
            letter-spacing: 0.1em;
        }

        .materi-content pre code {
            font-family: var(--ff-code);
            font-size: 0.85rem;
            color: var(--green);
            background: none;
            padding: 0;
            line-height: 1.6;
        }

        /* Inline code */
        .materi-content p code,
        .materi-content li code {
            font-family: var(--ff-code);
            font-size: 0.85em;
            background: rgba(83, 234, 253, 0.08);
            border: 1px solid rgba(83, 234, 253, 0.2);
            color: var(--cyan);
            padding: 2px 7px;
        }

        /* ── TIPS BOX ─────────────────────────────── */
        .materi-tips {
            background: rgba(255, 223, 32, 0.05);
            border: 2px solid var(--yellow);
            padding: var(--sp-5);
            margin-top: var(--sp-5);
            position: relative;
        }

        .materi-tips::before {
            content: '! TIPS';
            position: absolute;
            top: -12px;
            left: 16px;
            font-family: var(--ff-pixel);
            font-size: 0.45rem;
            color: var(--yellow);
            background: var(--bg);
            padding: 2px 10px;
            border: 1px solid var(--yellow);
            letter-spacing: 0.1em;
        }

        .materi-tips h3 {
            font-family: var(--ff-pixel);
            font-size: 0.6rem;
            color: var(--yellow) !important;
            border-color: var(--yellow) !important;
            margin-top: 0 !important;
            margin-bottom: var(--sp-3) !important;
        }

        .materi-tips ul {
            margin-left: var(--sp-5);
        }

        .materi-tips li {
            color: rgba(232, 244, 255, 0.8);
            margin-bottom: var(--sp-2);
        }

        /* ── CTA — LANJUT KE SOAL ─────────────────── */
        .materi-cta {
            background: rgba(9, 19, 38, 0.6);
            border: 2px solid var(--border);
            padding: var(--sp-6);
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .materi-cta::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg,
                rgba(83, 234, 253, 0.04) 0%,
                rgba(237, 106, 255, 0.04) 100%);
            pointer-events: none;
        }

        .materi-cta-label {
            font-family: var(--ff-pixel);
            font-size: 0.5rem;
            color: var(--muted);
            letter-spacing: 0.12em;
            margin-bottom: var(--sp-3);
        }

        .materi-cta h2 {
            font-family: var(--ff-pixel);
            font-size: 0.85rem;
            color: var(--text);
            margin-bottom: var(--sp-4);
            line-height: 1.6;
        }

        .materi-cta p {
            color: var(--muted);
            font-size: 0.85rem;
            margin-bottom: var(--sp-6);
        }

        .btn-lanjut {
            display: inline-flex;
            align-items: center;
            gap: var(--sp-3);
            font-family: var(--ff-pixel);
            font-size: 0.6rem;
            padding: 16px 36px;
            background: var(--cyan);
            color: var(--bg);
            text-decoration: none;
            border: 2px solid var(--cyan);
            letter-spacing: 0.08em;
            transition: all 0.2s cubic-bezier(0.23, 1, 0.32, 1);
            position: relative;
            z-index: 1;
            cursor: pointer;
        }

        .btn-lanjut:hover {
            background: transparent;
            color: var(--cyan);
            box-shadow: 6px 6px 0 rgba(83, 234, 253, 0.2);
            transform: translate(-3px, -3px);
        }

        .btn-lanjut:active {
            transform: translate(0, 0);
            box-shadow: none;
        }

        .btn-back-soft {
            display: inline-block;
            font-family: var(--ff-pixel);
            font-size: 0.45rem;
            padding: 8px 18px;
            border: 1px solid var(--border);
            color: var(--muted);
            text-decoration: none;
            background: transparent;
            transition: all 0.2s;
            margin-top: var(--sp-4);
        }

        .btn-back-soft:hover {
            color: var(--text);
            border-color: var(--muted);
        }

        /* ── PROGRESS INDICATOR ───────────────────── */
        .scroll-hint {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: var(--sp-2);
            margin-bottom: var(--sp-5);
            opacity: 0.6;
            animation: fade-in 1s 0.5s ease both;
        }

        .scroll-hint span {
            font-size: 0.75rem;
            color: var(--muted);
        }

        .scroll-arrow {
            width: 2px;
            height: 32px;
            background: linear-gradient(to bottom, var(--cyan), transparent);
            position: relative;
            animation: scroll-pulse 1.5s ease-in-out infinite;
        }

        @keyframes scroll-pulse {
            0%, 100% { opacity: 0.4; transform: scaleY(1); }
            50%       { opacity: 1;   transform: scaleY(1.15); }
        }

        @keyframes fade-in {
            from { opacity: 0; transform: translateY(8px); }
            to   { opacity: 0.6; transform: translateY(0); }
        }

        /* ── RESPONSIVE ───────────────────────────── */
        @media (max-width: 768px) {
            .lp-nav-inner { padding: 0 16px; }

            .lp-nav-links a, .lp-nav-links button {
                font-size: 0.45rem;
                padding: 6px 10px;
            }

            .materi-page {
                padding: 90px 16px 60px;
            }

            .materi-page-header h1 {
                font-size: 1rem;
            }

            .materi-content-wrapper {
                padding: var(--sp-5) var(--sp-4);
            }

            .materi-content h2 { font-size: 0.85rem; }
            .materi-content h3 { font-size: 0.6rem; }

            .btn-lanjut {
                padding: 14px 24px;
                font-size: 0.55rem;
            }
        }
    </style>
</head>
<body>
    <!-- Canvas Background -->
    <canvas id="bg-canvas"></canvas>

    <!-- Reading Progress Bar -->
    <div id="reading-progress"></div>

    <div class="page-content">

        <!-- Navbar -->
        <nav class="lp-nav">
            <div class="lp-nav-inner">
                <a href="{{ route('dashboard') }}" class="lp-logo">
                    <span class="bracket">&lt;</span>Code<span style="color:var(--cyan)">/</span>Tide<span class="bracket">&gt;</span>
                </a>
                <div class="lp-nav-links">
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                    <a href="{{ route('modules.index') }}">Materi</a>
                    <a href="{{ route('leaderboard.index') }}">Leaderboard</a>
                    <a href="{{ route('costumes.index') }}">Kostumisasi</a>
                    <div style="width:1px;height:20px;background:var(--border);"></div>
                    <span style="color:var(--text);font-family:var(--ff-pixel);font-size:0.5rem;padding:0 8px;">{{ auth()->user()->nickname }}</span>
                    <form method="POST" action="{{ route('logout') }}" style="display:inline;margin:0;">
                        @csrf
                        <button type="submit" class="btn-logout">Logout</button>
                    </form>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="materi-page">

            <!-- Breadcrumb -->
            <nav class="breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('modules.index') }}">← Materi</a>
                <span class="sep">/</span>
                <span class="cur">{{ $material->module->title }}</span>
                <span class="sep">/</span>
                <span class="cur">{{ $material->title }}</span>
            </nav>

            <!-- Page Header -->
            <header class="materi-page-header">
                <div class="materi-badge">📖 MATERI PEMBELAJARAN</div>
                <h1>{{ $material->title }}</h1>
                <div class="materi-meta">
                    <span>📦 {{ $material->module->title }}</span>
                    @if($material->description)
                        <span>{{ $material->description }}</span>
                    @endif
                </div>
            </header>

            <!-- Content Area -->
            @if($material->content)
            <div class="materi-content-wrapper">
                {!! $material->content !!}
            </div>
            @else
            <div class="materi-content-wrapper">
                <p style="color:var(--muted);text-align:center;padding:32px 0;">
                    Konten materi belum tersedia. Silakan lanjut ke soal.
                </p>
            </div>
            @endif

            <!-- Scroll hint (hidden after user scrolls) -->
            <div class="scroll-hint" id="scroll-hint">
                <span>Scroll untuk membaca materi</span>
                <div class="scroll-arrow"></div>
            </div>

            <!-- CTA — Lanjut ke Soal -->
            <div class="materi-cta" id="cta-section">
                <p class="materi-cta-label">SIAP MENGERJAKAN?</p>
                <h2>Sudah Paham Materinya?</h2>
                <p>Kerjakan {{ $material->title }} dan kumpulkan XP dari setiap soal yang kamu selesaikan.</p>

                <a href="{{ route('materials.show', $material->id) }}"
                   class="btn-lanjut"
                   id="btn-lanjut-soal">
                    Lanjut ke Soal →
                </a>

                <br>
                <a href="{{ route('modules.index') }}" class="btn-back-soft">
                    ← Kembali ke Daftar Materi
                </a>
            </div>

        </main>
    </div>

    <script src="{{ asset('js/pxlkit-renderer.js') }}"></script>
    <script src="{{ asset('js/scene3d.js') }}"></script>
    <script>
        // ── Reading Progress Bar ──────────────────────────────────
        const progressBar = document.getElementById('reading-progress');
        const scrollHint  = document.getElementById('scroll-hint');

        function updateProgress() {
            const scrollTop    = window.scrollY;
            const docHeight    = document.documentElement.scrollHeight - window.innerHeight;
            const pct          = docHeight > 0 ? (scrollTop / docHeight) * 100 : 0;

            progressBar.style.width = pct + '%';

            // Hide scroll hint after user scrolls a bit
            if (scrollTop > 80 && scrollHint) {
                scrollHint.style.opacity = '0';
                scrollHint.style.transition = 'opacity 0.4s';
            }
        }

        window.addEventListener('scroll', updateProgress, { passive: true });
        updateProgress();

        // ── PxlKit ───────────────────────────────────────────────
        function injectPxlIcons() {
            if (typeof PXLKIT === 'undefined') {
                setTimeout(injectPxlIcons, 100);
                return;
            }
            document.querySelectorAll('[data-pxl-icon]:not([data-pxl-done])').forEach(el => {
                const name  = el.dataset.pxlIcon;
                const scale = parseInt(el.dataset.pxlScale || '2', 10);
                const PXL_MAP = {
                    star:      () => typeof PXL_SPARKLE_STAR !== 'undefined' ? PXL_SPARKLE_STAR : null,
                    lightning: () => typeof PXL_LIGHTNING    !== 'undefined' ? PXL_LIGHTNING    : null,
                };
                const getter = PXL_MAP[name];
                if (!getter) return;
                const data = getter();
                if (!data) return;
                el.innerHTML = '';
                el.appendChild(PXLKIT.render(data, scale));
                el.dataset.pxlDone = '1';
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            requestAnimationFrame(() => {
                requestAnimationFrame(() => { injectPxlIcons(); });
            });
        });

        // ── Highlight active "Lanjut ke Soal" button on scroll near bottom ──
        const ctaSection = document.getElementById('cta-section');
        const btnLanjut  = document.getElementById('btn-lanjut-soal');

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    btnLanjut.style.animation = 'cta-pulse 1.2s ease 0.3s 2';
                }
            });
        }, { threshold: 0.4 });

        if (ctaSection) observer.observe(ctaSection);
    </script>

    <style>
        @keyframes cta-pulse {
            0%, 100% { box-shadow: 0 0 0 rgba(83,234,253,0); }
            50%       { box-shadow: 0 0 20px rgba(83,234,253,0.4), 6px 6px 0 rgba(83,234,253,0.2); }
        }
    </style>
</body>
</html>
