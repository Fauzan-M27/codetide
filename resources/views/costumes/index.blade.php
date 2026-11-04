<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kostumisasi Karakter — CodeTide</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
        }

        html { scroll-behavior: smooth; }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: var(--ff-code);
            font-size: 14px;
            line-height: 1.6;
            overflow-x: hidden;
            min-height: 100vh;
        }

        #bg-canvas {
            position: fixed;
            inset: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
        }

        .page-content {
            position: relative;
            z-index: 1;
        }

        /* Navbar */
        .lp-nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 100;
            background: rgba(4, 13, 26, 0.85);
            border-bottom: 1px solid var(--border);
            backdrop-filter: blur(12px);
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
            background: transparent;
            cursor: pointer;
            transition: all 0.15s;
        }

        .lp-nav-links a:hover, .lp-nav-links button:hover {
            color: var(--cyan);
            border-color: var(--cyan);
        }

        .lp-nav-links a.active {
            color: var(--cyan);
            border-color: var(--cyan);
            background: rgba(83, 234, 253, 0.1);
        }

        .lp-nav-links .btn-logout {
            color: #FF6B6B;
            border-color: rgba(255, 107, 107, 0.3);
        }

        /* Container */
        .container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 80px 32px 60px;
        }

        /* Header */
        .page-header {
            margin-bottom: 48px;
        }

        .page-header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
            flex-wrap: wrap;
            gap: 20px;
        }

        .page-header h1 {
            font-family: var(--ff-pixel);
            font-size: 2rem;
            color: var(--purple);
            line-height: 1.6;
        }

        .page-header p {
            color: var(--muted);
            font-size: 0.9rem;
        }

        .btn-create {
            font-family: var(--ff-pixel);
            font-size: 0.6rem;
            padding: 14px 24px;
            background: var(--green);
            color: #000;
            text-decoration: none;
            border: 2px solid var(--green);
            transition: all 0.3s;
            display: inline-block;
        }

        .btn-create:hover {
            box-shadow: 4px 4px 0 rgba(123, 241, 168, 0.5);
            transform: translate(-2px, -2px);
        }

        /* Active Costume Panel */
        .active-costume-panel {
            background: rgba(9, 19, 38, 0.7);
            border: 2px solid var(--purple);
            padding: 32px;
            margin-bottom: 40px;
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 32px;
            align-items: center;
            transition: all 0.3s;
        }

        .active-costume-panel::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, var(--purple), var(--cyan));
        }

        .active-costume-panel:hover {
            border-color: var(--cyan);
            box-shadow: 0 8px 32px rgba(237, 106, 255, 0.2);
        }

        .active-costume-display {
            width: 200px;
            height: 200px;
            background: rgba(9, 19, 38, 0.8);
            border: 3px solid var(--cyan);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .active-costume-display img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            image-rendering: pixelated;
        }

        .active-costume-info h2 {
            font-family: var(--ff-pixel);
            font-size: 1.4rem;
            color: var(--cyan);
            margin-bottom: 8px;
        }

        .active-costume-info .badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-family: var(--ff-pixel);
            font-size: 0.5rem;
            padding: 8px 14px;
            background: rgba(123, 241, 168, 0.2);
            border: 1px solid var(--green);
            color: var(--green);
            margin-bottom: 16px;
        }

        .active-costume-info p {
            color: var(--muted);
            font-size: 0.85rem;
            line-height: 1.6;
        }

        /* Costumes Grid */
        .costumes-section {
            margin-bottom: 40px;
        }

        .section-title {
            font-family: var(--ff-pixel);
            font-size: 1rem;
            color: var(--cyan);
            margin-bottom: 24px;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
        }

        .costumes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 24px;
        }

        .costume-card {
            background: rgba(9, 19, 38, 0.6);
            border: 2px solid var(--border);
            padding: 20px;
            text-align: center;
            transition: all 0.3s;
            cursor: pointer;
            position: relative;
        }

        .costume-card:hover {
            border-color: var(--cyan);
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(83, 234, 253, 0.15);
        }

        .costume-card.locked {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .costume-card.locked:hover {
            border-color: var(--border);
            transform: none;
            box-shadow: none;
        }

        .costume-card.active {
            border-color: var(--green);
            background: rgba(123, 241, 168, 0.1);
        }

        .costume-preview {
            width: 160px;
            height: 160px;
            margin: 0 auto 16px;
            background: rgba(9, 19, 38, 0.6);
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .costume-preview img {
            max-width: 100%;
            max-height: 100%;
            image-rendering: pixelated;
        }

        .lock-overlay {
            position: absolute;
            inset: 0;
            backdrop-filter: blur(0.5px);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            transition: all 0.3s;
            gap: 12px;
            background: rgba(4, 13, 26, 0.25);
        }

        .lock-overlay .lock-icon-wrapper {
            background: rgba(4, 13, 26, 0.98);
            padding: 20px;
            border-radius: 16px;
            border: 4px solid var(--cyan);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.8);
        }

        .costume-card.locked:hover .lock-overlay {
            backdrop-filter: blur(0px);
            background: rgba(4, 13, 26, 0.15);
        }

        /* Make locked character very clear - minimal effects */
        .costume-card.locked img {
            opacity: 1;
            filter: grayscale(30%) brightness(0.9);
        }

        /* Remove the dark overlay - character should be clear */
        .costume-card.locked .costume-preview::before {
            display: none;
        }

        .costume-name {
            font-family: var(--ff-pixel);
            font-size: 0.7rem;
            color: var(--text);
            margin-bottom: 8px;
        }

        .costume-hint {
            font-size: 0.65rem;
            color: var(--muted);
            line-height: 1.4;
        }

        .costume-card.active .costume-name {
            color: var(--green);
        }

        .active-badge {
            position: absolute;
            top: 12px;
            right: 12px;
            font-family: var(--ff-pixel);
            font-size: 0.45rem;
            padding: 4px 8px;
            background: var(--green);
            color: #000;
        }

        .btn-activate {
            font-family: var(--ff-pixel);
            font-size: 0.5rem;
            padding: 8px 16px;
            background: var(--cyan);
            color: #000;
            border: 1px solid var(--cyan);
            cursor: pointer;
            margin-top: 12px;
            transition: all 0.2s;
        }

        .btn-activate:hover {
            box-shadow: 2px 2px 0 rgba(83, 234, 253, 0.5);
            transform: translate(-1px, -1px);
        }

        .btn-activate:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 32px;
            color: var(--muted);
        }

        .empty-state h3 {
            font-family: var(--ff-pixel);
            font-size: 1rem;
            color: var(--cyan);
            margin-bottom: 12px;
        }

        /* Alert */
        .alert {
            padding: 16px 20px;
            margin-bottom: 24px;
            border: 1px solid;
            font-size: 0.85rem;
        }

        .alert-success {
            background: rgba(123, 241, 168, 0.1);
            border-color: var(--green);
            color: var(--green);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .container {
                padding: 80px 16px 40px;
            }

            .page-header h1 {
                font-size: 1.4rem;
            }

            .active-costume-panel {
                grid-template-columns: 1fr;
                text-align: center;
            }

            .active-costume-display {
                margin: 0 auto;
            }

            .costumes-grid {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
                gap: 16px;
            }
        }
    </style>
</head>
<body>
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
                    <a href="{{ route('costumes.index') }}" class="active">Kostumisasi</a>
                    <form method="POST" action="{{ route('logout') }}" style="display: inline; margin: 0;">
                        @csrf
                        <button type="submit" class="btn-logout">Logout</button>
                    </form>
                </div>
            </div>
        </nav>

        <!-- Container -->
        <main class="container">
            
            <!-- Header -->
            <div class="page-header">
                <div class="page-header-top">
                    <div>
                        <h1>Koleksi Karakter</h1>
                        <p>Pilih karakter favoritmu untuk menemani belajar coding!</p>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Active Costume -->
            @if($activeCostume)
            <div class="active-costume-panel">
                <div class="active-costume-display">
                    @if($activeCostume && $activeCostume->sprite_idle)
                        <img src="{{ asset($activeCostume->sprite_idle) }}" alt="{{ $activeCostume->character_name }}">
                    @else
                        <span data-pxl-icon="user" data-pxl-scale="8"></span>
                    @endif
                </div>
                <div class="active-costume-info">
                    <h2>{{ $activeCostume->character_name ?? $activeCostume->name }}</h2>
                    <span class="badge"><span data-pxl-icon="check" data-pxl-scale="1" style="display: inline-block; vertical-align: middle;"></span> AKTIF SEKARANG</span>
                    <p>{{ $activeCostume->description ?? 'Karakter ini sedang menemani perjalanan belajarmu!' }}</p>
                </div>
            </div>
            @else
            <div class="active-costume-panel">
                <div class="active-costume-display">
                    <span data-pxl-icon="user" data-pxl-scale="8"></span>
                </div>
                <div class="active-costume-info">
                    <h2>Belum Ada Karakter Aktif</h2>
                    <span class="badge" style="background: rgba(255, 107, 107, 0.2); border-color: #FF6B6B; color: #FF6B6B;"><span data-pxl-icon="warning" data-pxl-scale="1" style="display: inline-block; vertical-align: middle;"></span> TIDAK ADA</span>
                    <p>Pilih salah satu karakter di bawah atau buat karakter kustom baru!</p>
                </div>
            </div>
            @endif

            <!-- Unlocked Costumes -->
            @php
                $unlocked = collect($costumes)->where('is_unlocked', true)->where('is_active', false);
                $locked = collect($costumes)->where('is_unlocked', false);
            @endphp

            @if($unlocked->count() > 0)
            <div class="costumes-section">
                <h2 class="section-title"><span data-pxl-icon="masks" data-pxl-scale="1.5" style="display: inline-block; vertical-align: middle; margin-right: 8px;"></span> Karakter Terbuka ({{ $unlocked->count() }})</h2>
                <div class="costumes-grid">
                    @foreach($unlocked as $costume)
                    <div class="costume-card" data-costume-id="{{ $costume['id'] }}">
                        <div class="costume-preview">
                            @php
                                $costumeModel = \App\Models\Costume::find($costume['id']);
                                $spritePath = $costumeModel->sprite_idle ?? '';
                            @endphp
                            @if($spritePath)
                                <img src="{{ asset($spritePath) }}" alt="{{ $costume['name'] }}">
                            @else
                                <span data-pxl-icon="{{ $costume['icon'] }}" data-pxl-scale="6"></span>
                            @endif
                        </div>
                        <div class="costume-name">{{ $costume['name'] }}</div>
                        <div class="costume-hint">{{ $costume['unlock_hint'] }}</div>
                        <button class="btn-activate" onclick="activateCostume({{ $costume['id'] }}, this)">
                            Aktifkan
                        </button>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Locked Costumes -->
            @if($locked->count() > 0)
            <div class="costumes-section">
                <h2 class="section-title"><span data-pxl-icon="lock" data-pxl-scale="1.5" style="display: inline-block; vertical-align: middle; margin-right: 8px;"></span> Karakter Terkunci ({{ $locked->count() }})</h2>
                <div class="costumes-grid">
                    @foreach($locked as $costume)
                    <div class="costume-card locked">
                        <div class="costume-preview">
                            @php
                                $costumeModel = \App\Models\Costume::find($costume['id']);
                                // Use original PNG (Idle.png) instead of GIF for locked characters
                                $spritePath = $costumeModel->sprite_idle ?? '';
                                // Replace .gif with .png to get static first frame
                                $staticSpritePath = str_replace('/Idle.gif', '/Idle.png', $spritePath);
                            @endphp
                            @if($spritePath)
                                <img src="{{ asset($staticSpritePath) }}" alt="{{ $costume['name'] }}" class="locked-character-img">
                            @else
                                <span data-pxl-icon="{{ $costume['icon'] }}" data-pxl-scale="6"></span>
                            @endif
                            <div class="lock-overlay">
                                <div class="lock-icon-wrapper">
                                    <span data-pxl-icon="lock" data-pxl-scale="5"></span>
                                </div>
                            </div>
                        </div>
                        <div class="costume-name">{{ $costume['name'] }}</div>
                        <div class="costume-hint">{{ $costume['unlock_hint'] }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            @if($unlocked->count() === 0 && $locked->count() === 0)
            <div class="empty-state">
                <h3>Belum Ada Karakter</h3>
                <p>Mulai belajar untuk membuka karakter baru!</p>
                <a href="{{ route('modules.index') }}" class="btn-create" style="margin-top: 20px; display: inline-flex; align-items: center; gap: 8px;">
                    <span data-pxl-icon="rocket" data-pxl-scale="1"></span> Mulai Belajar
                </a>
            </div>
            @endif

        </main>

    </div>

    <script src="{{ asset('js/pxlkit-renderer.js') }}"></script>
    <script src="{{ asset('js/scene3d.js') }}"></script>
    <script>
        // PxlKit Icon Map
        const PXL_MAP = {
            user:     () => typeof PXL_GHOST_FRIEND  !== 'undefined' ? PXL_GHOST_FRIEND  : null,
            lock:     () => typeof PXL_LOCK          !== 'undefined' ? PXL_LOCK          : null,
            gem:      () => typeof PXL_FLOATING_GEM  !== 'undefined' ? PXL_FLOATING_GEM  : null,
            trophy:   () => typeof PXL_TROPHY        !== 'undefined' ? PXL_TROPHY        : null,
            check:    () => typeof PXL_CHECKMARK     !== 'undefined' ? PXL_CHECKMARK     : null,
            warning:  () => typeof PXL_WARNING       !== 'undefined' ? PXL_WARNING       : null,
            rocket:   () => typeof PXL_ROCKET        !== 'undefined' ? PXL_ROCKET        : null,
            masks:    () => typeof PXL_GHOST_FRIEND  !== 'undefined' ? PXL_GHOST_FRIEND  : null,
        };

        // Inject PxlKit Icons
        function injectPxlIcons() {
            if (typeof PXLKIT === 'undefined') {
                setTimeout(injectPxlIcons, 100);
                return;
            }

            document.querySelectorAll('[data-pxl-icon]').forEach(el => {
                const iconName = el.getAttribute('data-pxl-icon');
                const scale = parseFloat(el.getAttribute('data-pxl-scale') || '2');
                
                if (PXL_MAP[iconName]) {
                    const getIcon = PXL_MAP[iconName];
                    const iconData = getIcon();
                    if (iconData) {
                        const canvas = PXLKIT.renderIcon(iconData, { scale });
                        el.innerHTML = '';
                        el.appendChild(canvas);
                    }
                }
            });
        }

        // Activate costume
        async function activateCostume(costumeId, button) {
            button.disabled = true;
            button.textContent = 'Loading...';

            try {
                const response = await fetch(`/costumes/${costumeId}/activate`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const data = await response.json();

                if (data.success) {
                    // Reload page to show new active costume
                    window.location.reload();
                } else {
                    alert(data.message || 'Gagal mengaktifkan kostum');
                    button.disabled = false;
                    button.textContent = 'Aktifkan';
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan');
                button.disabled = false;
                button.textContent = 'Aktifkan';
            }
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            injectPxlIcons();
        });

        if (document.readyState === 'complete') {
            injectPxlIcons();
        }
    </script>
</body>
</html>
