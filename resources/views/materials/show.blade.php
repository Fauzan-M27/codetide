<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $material['title'] }} — CodeTide</title>
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
            --sp-6: 32px;
            --sp-8: 48px;
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

        /* ── MATERIAL CONTAINER ─────────────────────── */
        .material-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 96px 32px 60px;
        }

        /* ── BREADCRUMB ──────────────────────────────── */
        .breadcrumb {
            display: flex;
            align-items: center;
            gap: var(--sp-2);
            margin-bottom: var(--sp-5);
            font-size: 0.8rem;
        }

        .breadcrumb a {
            color: var(--cyan);
            text-decoration: none;
            transition: opacity 0.3s;
        }

        .breadcrumb a:hover {
            opacity: 0.8;
            text-decoration: underline;
        }

        .breadcrumb .separator {
            color: var(--muted);
        }

        .breadcrumb .current {
            color: var(--muted);
        }

        /* ── HEADER ──────────────────────────────────── */
        .material-header {
            text-align: center;
            margin-bottom: var(--sp-8);
        }

        .header-icons {
            display: flex;
            justify-content: center;
            gap: var(--sp-4);
            margin-bottom: var(--sp-4);
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .material-header h1 {
            font-family: var(--ff-pixel);
            font-size: 1.5rem;
            color: var(--cyan);
            margin-bottom: var(--sp-3);
            line-height: 1.6;
        }

        .material-header .description {
            color: var(--muted);
            font-size: 0.9rem;
            line-height: 1.6;
            max-width: 600px;
            margin: 0 auto;
        }

        /* ── QUESTIONS GRID ──────────────────────────── */
        .questions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: var(--sp-5);
            margin-bottom: var(--sp-8);
        }

        /* ── QUESTION NODE ───────────────────────────── */
        .question-node {
            background: rgba(9, 19, 38, 0.6);
            border: 2px solid var(--border);
            padding: var(--sp-6) var(--sp-4) var(--sp-4);
            position: relative;
            min-height: 140px;
            transition: all 0.3s cubic-bezier(0.23, 1, 0.32, 1);
            cursor: pointer;
        }

        .question-node:hover {
            border-color: var(--cyan);
            box-shadow: 8px 8px 0 rgba(83, 234, 253, 0.1);
            transform: translate(-2px, -2px);
        }

        .question-node.locked {
            opacity: 0.5;
            cursor: not-allowed;
            border-color: rgba(128, 128, 128, 0.3);
        }

        .question-node.locked:hover {
            transform: none;
            box-shadow: none;
            border-color: rgba(128, 128, 128, 0.3);
        }

        .question-node.complete {
            border-color: var(--green);
            background: rgba(123, 241, 168, 0.05);
        }

        .question-node.active {
            border-color: var(--yellow);
            background: rgba(255, 223, 32, 0.05);
        }

        /* Node icon at top */
        .node-pxl-icon {
            position: absolute;
            top: -20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 2;
            pointer-events: none;
        }

        .node-number {
            font-family: var(--ff-pixel);
            font-size: 0.65rem;
            color: var(--text);
            text-align: center;
            margin-bottom: var(--sp-2);
        }

        .node-title {
            font-size: 0.85rem;
            color: var(--muted);
            text-align: center;
            line-height: 1.5;
        }

        .node-xp {
            font-family: var(--ff-pixel);
            font-size: 0.5rem;
            color: var(--yellow);
            text-align: center;
            margin-top: var(--sp-3);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: var(--sp-2);
        }

        /* ── BACK BUTTON ─────────────────────────────── */
        .back-section {
            text-align: center;
        }

        .btn-back {
            font-family: var(--ff-pixel);
            font-size: 0.5rem;
            padding: 12px 24px;
            border: 1px solid var(--border);
            color: var(--text);
            text-decoration: none;
            background: transparent;
            transition: all 0.3s;
            display: inline-block;
        }

        .btn-back:hover {
            color: var(--cyan);
            border-color: var(--cyan);
            transform: translateY(-2px);
        }

        /* ── RESPONSIVE ──────────────────────────────── */
        @media (max-width: 768px) {
            .material-container {
                padding: 96px 16px 40px;
            }

            .questions-grid {
                grid-template-columns: 1fr;
            }

            .material-header h1 {
                font-size: 1.2rem;
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
                    <a href="{{ route('modules.index') }}">Materi</a>
                    <a href="{{ route('leaderboard.index') }}">Leaderboard</a>
                    <a href="{{ route('costumes.index') }}">Kostumisasi</a>
                    <div style="width: 1px; height: 20px; background: var(--border);"></div>
                    <span style="color: var(--text); font-family: var(--ff-pixel); font-size: 0.5rem; padding: 0 8px;">{{ auth()->user()->nickname }}</span>
                    <form method="POST" action="{{ route('logout') }}" style="display: inline; margin: 0;">
                        @csrf
                        <button type="submit" class="btn-logout">Logout</button>
                    </form>
                </div>
            </div>
        </nav>

        <!-- Material Container -->
        <main class="material-container">
            
            <!-- Breadcrumb -->
            <div class="breadcrumb">
                <a href="{{ route('modules.index') }}">← Materi</a>
                <span class="separator">/</span>
                <span class="current">{{ $material['module_title'] }}</span>
                <span class="separator">/</span>
                <span class="current">{{ $material['title'] }}</span>
            </div>

            <!-- Header -->
            <div class="material-header">
                <div class="header-icons">
                    <span data-pxl-icon="star" data-pxl-scale="3" style="animation: float 2.5s ease-in-out infinite;"></span>
                    <span data-pxl-icon="lightning" data-pxl-scale="3" style="animation: float 2.5s ease-in-out 0.3s infinite;"></span>
                    <span data-pxl-icon="star" data-pxl-scale="3" style="animation: float 2.5s ease-in-out 0.6s infinite;"></span>
                </div>
                <h1>{{ $material['title'] }}</h1>
                <p class="description">{{ $material['description'] }}</p>
            </div>

            <!-- Questions Grid -->
            <div class="questions-grid">
                @foreach($material['questions'] as $question)
                <div class="question-node {{ !$question['is_unlocked'] ? 'locked' : ($question['is_completed'] ? 'complete' : 'active') }}"
                     @if($question['is_unlocked'])
                         onclick="window.location.href='{{ route('questions.show', $question['id']) }}'"
                     @endif>
                    
                    <!-- Icon at top -->
                    <div class="node-pxl-icon">
                        <span data-pxl-icon="{{ !$question['is_unlocked'] ? 'lock' : ($question['is_completed'] ? 'check' : 'play') }}" data-pxl-scale="3"></span>
                    </div>

                    <div class="node-number">Soal {{ $question['order'] }}</div>
                    <div class="node-title">{{ $question['title'] }}</div>
                    <div class="node-xp">
                        <span data-pxl-icon="coin" data-pxl-scale="1.5"></span>
                        <span>{{ $question['xp_reward'] }} XP</span>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Back Button -->
            <div class="back-section">
                <a href="{{ route('modules.index') }}" class="btn-back">
                    ← Kembali ke Materi
                </a>
            </div>

        </main>

    </div>

    <script src="{{ asset('js/pxlkit-renderer.js') }}"></script>
    <script src="{{ asset('js/scene3d.js') }}"></script>
    <script>
        // PxlKit Icon Map
        const PXL_MAP = {
            star:     () => typeof PXL_SPARKLE_STAR  !== 'undefined' ? PXL_SPARKLE_STAR  : null,
            lightning:() => typeof PXL_LIGHTNING     !== 'undefined' ? PXL_LIGHTNING     : null,
            lock:     () => typeof PXL_LOCK          !== 'undefined' ? PXL_LOCK          : null,
            check:    () => typeof PXL_CHECK         !== 'undefined' ? PXL_CHECK         : null,
            play:     () => typeof PXL_PLAY          !== 'undefined' ? PXL_PLAY          : null,
            coin:     () => typeof PXL_COIN          !== 'undefined' ? PXL_COIN          : null,
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
        }

        // Init
        document.addEventListener('DOMContentLoaded', () => {
            requestAnimationFrame(() => {
                requestAnimationFrame(() => {
                    injectPxlIcons();
                });
            });
        });

        // Subtle hover tilt for active nodes
        document.querySelectorAll('.question-node.active').forEach(node => {
            node.addEventListener('mousemove', (e) => {
                const rect = node.getBoundingClientRect();
                const x = (e.clientX - rect.left) / rect.width - 0.5;
                const y = (e.clientY - rect.top) / rect.height - 0.5;
                
                node.style.transform = `translate(-3px, -3px) perspective(300px) rotateX(${-y * 4}deg) rotateY(${x * 4}deg)`;
            });
            
            node.addEventListener('mouseleave', () => {
                node.style.transform = '';
            });
        });
    </script>
</body>
</html>
