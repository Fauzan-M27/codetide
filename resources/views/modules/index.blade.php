<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Materi — CodeTide</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/mobile-responsive.css') }}">
    <style>
        *, *::before, *::after {
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
            --sp-2: 8px;
            --sp-3: 12px;
            --sp-4: 16px;
            --sp-5: 24px;
        }

        html { scroll-behavior: smooth; }

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

        /* Canvas background */
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

        /* ── NAVBAR ───────────────────────────────────── */
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

        .lp-logo .bracket {
            color: var(--cyan);
        }

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

        .lp-nav-links a.active {
            color: var(--cyan);
            border-color: var(--cyan);
            background: rgba(83, 234, 253, 0.1);
        }

        .lp-nav-links .nav-divider {
            width: 1px;
            height: 20px;
            background: var(--border);
        }

        .lp-nav-links .user-name {
            color: var(--text);
            font-family: var(--ff-pixel);
            font-size: 0.5rem;
            padding: 0 8px;
        }

        .lp-nav-links .btn-logout {
            color: #FF6B6B;
            border-color: rgba(255, 107, 107, 0.3);
        }

        .lp-nav-links .btn-logout:hover {
            color: #FF6B6B;
            border-color: #FF6B6B;
        }

        /* Responsive navbar */
        @media (max-width: 768px) {
            .lp-nav-inner {
                padding: 0 16px;
            }

            .lp-nav-links {
                gap: 8px;
            }

            .lp-nav-links a, .lp-nav-links button {
                font-size: 0.45rem;
                padding: 6px 12px;
            }

            .lp-nav-links .user-name {
                display: none;
            }

            .lp-nav-links .nav-divider {
                display: none;
            }
        }

        /* ── MODULES CONTAINER ─────────────────────────── */
        .modules-container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 80px 32px 60px;
        }

        /* ── MODULES HEADER ─────────────────────────────── */
        .modules-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: var(--sp-5);
            gap: var(--sp-4);
            flex-wrap: wrap;
        }

        .modules-header h1 {
            font-family: var(--ff-pixel);
            font-size: 1.5rem;
            color: var(--cyan);
            margin-bottom: var(--sp-2);
            line-height: 1.5;
        }

        .modules-header .subtitle {
            color: var(--muted);
            font-size: 0.875rem;
            line-height: 1.5;
        }

        .modules-header-stat {
            display: flex;
            flex-direction: column;
            gap: var(--sp-2);
            min-width: 200px;
        }

        .xp-badge {
            font-family: var(--ff-pixel);
            font-size: 0.7rem;
            color: var(--yellow);
            display: flex;
            align-items: center;
            gap: var(--sp-2);
            justify-content: flex-end;
        }

        /* ── PROGRESS BAR ─────────────────────────────── */
        .progress-wrapper {
            width: 100%;
        }

        .progress-meta {
            display: flex;
            justify-content: space-between;
            margin-bottom: var(--sp-2);
            font-size: 0.75rem;
        }

        .progress-meta .value {
            color: var(--cyan);
            font-weight: 700;
        }

        .progress-bar {
            width: 100%;
            height: 8px;
            background: rgba(83, 234, 253, 0.1);
            border: 1px solid var(--border);
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--cyan), var(--green));
            transition: width 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .progress-fill.success {
            background: linear-gradient(90deg, var(--green), var(--yellow));
        }

        /* ── MODULES GRID ──────────────────────────────── */
        .modules-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
            gap: var(--sp-5);
        }

        @media (max-width: 768px) {
            .modules-grid {
                grid-template-columns: 1fr;
            }
        }

        /* ── MODULE CARD ───────────────────────────────── */
        .module-card {
            background: rgba(9, 19, 38, 0.6);
            border: 1px solid var(--border);
            padding: var(--sp-5);
            position: relative;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.23, 1, 0.32, 1);
            opacity: 0;
            transform: translateY(20px);
            animation: card-in 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes card-in {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .module-card:nth-child(1) { animation-delay: 0.05s; }
        .module-card:nth-child(2) { animation-delay: 0.10s; }
        .module-card:nth-child(3) { animation-delay: 0.15s; }

        .module-card:hover {
            border-color: var(--cyan);
            box-shadow: 8px 8px 0 rgba(83, 234, 253, 0.1);
            transform: translateY(-2px);
        }

        .module-card.locked {
            opacity: 0.6;
            border-color: rgba(128, 128, 128, 0.3);
        }

        .module-card.complete {
            border-color: var(--green);
        }

        .module-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: var(--sp-3);
        }

        .module-number {
            font-family: var(--ff-pixel);
            font-size: 0.7rem;
            color: var(--cyan);
            letter-spacing: 0.05em;
        }

        .badge {
            font-family: var(--ff-pixel);
            font-size: 0.4rem;
            padding: 4px 8px;
            border: 1px solid;
            letter-spacing: 0.05em;
        }

        .badge-locked {
            color: var(--muted);
            border-color: var(--border);
            background: rgba(128, 128, 128, 0.1);
        }

        .badge-active {
            color: var(--yellow);
            border-color: var(--yellow);
            background: rgba(255, 223, 32, 0.1);
        }

        .badge-complete {
            color: var(--green);
            border-color: var(--green);
            background: rgba(123, 241, 168, 0.1);
        }

        .module-name {
            font-family: var(--ff-pixel);
            font-size: 0.9rem;
            color: var(--text);
            margin-bottom: var(--sp-4);
            line-height: 1.5;
        }

        .module-progress-info {
            display: flex;
            justify-content: space-between;
            font-size: 0.75rem;
            margin-bottom: var(--sp-2);
        }

        /* ── LEVELS LIST ────────────────────────────────── */
        .levels-list {
            list-style: none;
            padding: 0;
            margin: var(--sp-4) 0 0;
        }

        .levels-list li {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: var(--sp-3);
            padding: var(--sp-2) var(--sp-3);
            margin-bottom: var(--sp-2);
            border: 1px solid var(--border);
            background: rgba(83, 234, 253, 0.03);
            transition: all 0.3s;
        }

        .levels-list li:hover {
            background: rgba(83, 234, 253, 0.08);
        }

        .levels-list li.locked {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .levels-list li.active {
            border-color: var(--yellow);
            background: rgba(255, 223, 32, 0.05);
            cursor: pointer;
        }

        .levels-list li.complete {
            border-color: var(--green);
            background: rgba(123, 241, 168, 0.05);
            cursor: pointer;
        }

        .levels-list li span:first-child {
            flex: 1;
            font-size: 0.85rem;
        }

        .level-progress {
            font-size: 0.7rem;
            color: var(--muted);
        }

        .level-pxl-icon {
            flex-shrink: 0;
            display: inline-flex;
            align-items: center;
            line-height: 0;
        }

        /* ── MODULE CARD ICON ───────────────────────────── */
        .module-card-icon {
            position: absolute;
            bottom: var(--sp-3);
            right: var(--sp-3);
            opacity: 0.55;
            transition: opacity 0.2s;
            pointer-events: none;
        }

        .module-card:hover .module-card-icon {
            opacity: 0.9;
        }

        /* ── RESPONSIVE ──────────────────────────────────── */
        @media (max-width: 768px) {
            .modules-container {
                padding: 80px 16px 40px;
            }

            .modules-header {
                flex-direction: column;
            }

            .modules-header-stat {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <!-- Canvas Background -->
    <canvas id="bg-canvas"></canvas>

    <div class="page-content">
        
        <!-- Navbar -->
        <nav class="lp-nav">
            <div class="lp-nav-inner">
                <a href="{{ route('dashboard') }}" class="lp-logo">
                    <span class="bracket">&lt;</span>Code<span style="color:var(--cyan)">/</span>Tide<span class="bracket">&gt;</span>
                </a>
                <div class="lp-nav-links">
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                    <a href="{{ route('modules.index') }}" class="active">Materi</a>
                    <a href="{{ route('leaderboard.index') }}">Leaderboard</a>
                    <a href="{{ route('costumes.index') }}">Kostumisasi</a>
                    <div class="nav-divider"></div>
                    <span class="user-name">{{ auth()->user()->nickname }}</span>
                    <form method="POST" action="{{ route('logout') }}" style="display: inline; margin: 0;">
                        @csrf
                        <button type="submit" class="btn-logout">Logout</button>
                    </form>
                </div>
            </div>
        </nav>

        <!-- Modules Container -->
        <main class="modules-container">
            
            <!-- Header -->
            <div class="modules-header">
                <div>
                    <h1>Materi Belajar</h1>
                    <p class="subtitle">Selesaikan modul secara berurutan untuk membuka konten berikutnya.</p>
                </div>
                <div class="modules-header-stat">
                    <div class="xp-badge">
                        <span data-pxl-icon="lightning" data-pxl-scale="2"></span>
                        <span>{{ $stats['total_xp'] }} XP</span>
                    </div>
                    <div class="progress-wrapper">
                        <div class="progress-meta">
                            <span>{{ $stats['completed_questions'] }}/{{ $stats['total_questions'] }} soal</span>
                            <span class="value">{{ number_format($stats['completion_percentage'], 0) }}%</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: {{ $stats['completion_percentage'] }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modules Grid -->
            <div class="modules-grid">
                @foreach($modules as $module)
                <div class="module-card {{ !$module['is_unlocked'] ? 'locked' : ($module['is_completed'] ? 'complete' : '') }}"
                     data-status="{{ !$module['is_unlocked'] ? 'locked' : ($module['is_completed'] ? 'complete' : 'active') }}"
                     data-num="{{ str_pad($module['order'], 2, '0', STR_PAD_LEFT) }}">
                    
                    <div class="module-card-header">
                        <span class="module-number">M{{ str_pad($module['order'], 2, '0', STR_PAD_LEFT) }}</span>
                        <span class="badge {{ !$module['is_unlocked'] ? 'badge-locked' : ($module['is_completed'] ? 'badge-complete' : 'badge-active') }}">
                            {{ !$module['is_unlocked'] ? 'Terkunci' : ($module['is_completed'] ? 'Selesai' : 'Aktif') }}
                        </span>
                    </div>

                    <h3 class="module-name">{{ $module['title'] }}</h3>

                    <div class="module-progress-info">
                        <span>{{ $module['completed_materials'] }}/{{ $module['total_materials'] }} materi</span>
                        <span style="color: var(--color-primary);">{{ number_format($module['completion_percentage'], 0) }}%</span>
                    </div>

                    <div class="progress-bar" style="margin-bottom: var(--sp-4);">
                        <div class="progress-fill {{ $module['is_completed'] ? 'success' : '' }}" style="width: {{ $module['completion_percentage'] }}%"></div>
                    </div>

                    <!-- Materials List -->
                    <ul class="levels-list">
                        @foreach($module['materials'] as $material)
                        <li class="{{ !$material['is_unlocked'] ? 'locked' : ($material['is_completed'] ? 'complete' : 'active') }}"
                            @if($material['is_unlocked'])
                                onclick="window.location.href='{{ route('materials.materi', $material['id']) }}'"
                                style="cursor: pointer;"
                            @endif>
                            <span>{{ $material['title'] }}</span>
                            <span class="level-progress">{{ $material['completed_questions'] }}/{{ $material['total_questions'] }} soal</span>
                            <span class="level-pxl-icon" data-icon="{{ !$material['is_unlocked'] ? 'lock' : ($material['is_completed'] ? 'check' : 'play') }}"></span>
                        </li>
                        @endforeach
                    </ul>

                    <!-- Module Icon (bottom right) -->
                    <div class="module-card-icon">
                        <span data-pxl-icon="{{ !$module['is_unlocked'] ? 'lock' : ($module['is_completed'] ? 'check' : 'star') }}" data-pxl-scale="2"></span>
                    </div>
                </div>
                @endforeach
            </div>

        </main>

    </div>

    <script src="{{ asset('js/pxlkit-renderer.js') }}"></script>
    <script src="{{ asset('js/scene3d.js') }}"></script>
    <script>
        // PxlKit Icon Map
        const PXL_MAP = {
            lightning:() => typeof PXL_LIGHTNING    !== 'undefined' ? PXL_LIGHTNING     : null,
            lock:     () => typeof PXL_LOCK          !== 'undefined' ? PXL_LOCK          : null,
            check:    () => typeof PXL_CHECK         !== 'undefined' ? PXL_CHECK         : null,
            play:     () => typeof PXL_PLAY          !== 'undefined' ? PXL_PLAY          : null,
            star:     () => typeof PXL_SPARKLE_STAR  !== 'undefined' ? PXL_SPARKLE_STAR  : null,
        };

        // Inject PxlKit Icons
        function injectPxlIcons() {
            if (typeof PXLKIT === 'undefined') {
                setTimeout(injectPxlIcons, 100);
                return;
            }

            document.querySelectorAll('[data-pxl-icon]:not([data-pxl-done])').forEach(el => {
                const name  = el.dataset.pxlIcon;
                const scale = parseInt(el.dataset.pxlScale || '2', 10);
                const getter = PXL_MAP[name];
                
                if (!getter) return;
                
                const data = getter();
                if (!data) return;
                
                el.innerHTML = '';
                el.appendChild(PXLKIT.render(data, scale));
                el.dataset.pxlDone = '1';
            });

            // Level icons
            document.querySelectorAll('.level-pxl-icon[data-icon]:not([data-pxl-done])').forEach(el => {
                const name = el.dataset.icon;
                const getter = PXL_MAP[name];
                if (!getter) return;
                const data = getter();
                if (!data) return;
                el.appendChild(PXLKIT.render(data, 2));
                el.dataset.pxlDone = '1';
            });
        }

        // Init when DOM ready
        document.addEventListener('DOMContentLoaded', () => {
            requestAnimationFrame(() => {
                requestAnimationFrame(() => {
                    injectPxlIcons();
                });
            });
        });

        // Animate progress bars on load
        window.addEventListener('load', () => {
            document.querySelectorAll('.progress-fill').forEach(bar => {
                const width = bar.style.width;
                bar.style.width = '0%';
                setTimeout(() => {
                    bar.style.width = width;
                }, 300);
            });
        });
    </script>
</body>
</html>
