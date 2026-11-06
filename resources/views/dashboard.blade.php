<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard — CodeTide</title>
    <meta name="description" content="Dashboard belajar CodeTide — pantau XP, badge, dan progres modulmu.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/mobile-responsive.css') }}">
    <script src="{{ asset('js/pxlkit-renderer.js') }}"></script>
    @vite(['resources/css/tutorial.css', 'resources/js/app.js'])
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --cyan:   #53EAFD;
            --purple: #ED6AFF;
            --yellow: #FFDF20;
            --green:  #7BF1A8;
            --red:    #FF6B6B;
            --bg:     #040D1A;
            --bg2:    #091326;
            --surface:#0D1B30;
            --border: rgba(83, 234, 253, 0.15);
            --text:   #E8F4FF;
            --muted:  rgba(232, 244, 255, 0.55);
            --ff-pixel:'Press Start 2P', monospace;
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
            position: fixed; inset: 0;
            width: 100%; height: 100%;
            pointer-events: none; z-index: 0 !important;
        }

        .page-content { position: relative; z-index: 1; }

        /* ── NAVBAR ──────────────────────────────────────── */
        .lp-nav {
            position: fixed; top: 0; left: 0; right: 0;
            z-index: 100;
            background: rgba(4, 13, 26, 0.88);
            border-bottom: 1px solid var(--border);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        .lp-nav-inner {
            display: flex; align-items: center;
            justify-content: space-between;
            padding: 0 32px; height: 64px;
            max-width: 1280px; margin: 0 auto;
        }
        .lp-logo {
            font-family: var(--ff-pixel); font-size: 0.75rem;
            color: var(--purple); text-decoration: none;
            text-shadow: 0 0 16px rgba(237,106,255,0.5);
            letter-spacing: 0.05em;
        }
        .lp-logo .bracket { color: var(--cyan); }
        .lp-nav-links { display: flex; align-items: center; gap: 16px; }
        .lp-nav-links a, .lp-nav-links button {
            font-family: var(--ff-pixel); font-size: 0.5rem;
            padding: 8px 16px; border: 1px solid var(--border);
            color: var(--muted); text-decoration: none;
            letter-spacing: 0.05em; transition: all 0.15s;
            background: transparent; cursor: pointer; white-space: nowrap;
        }
        .lp-nav-links a:hover, .lp-nav-links button:hover {
            color: var(--cyan); border-color: var(--cyan);
            transform: translateY(-1px);
        }
        .lp-nav-links a.active {
            color: var(--cyan); border-color: var(--cyan);
            background: rgba(83,234,253,0.1);
        }
        .lp-nav-links .nav-divider { width: 1px; height: 20px; background: var(--border); }
        .lp-nav-links .user-name {
            color: var(--text); font-family: var(--ff-pixel);
            font-size: 0.5rem; padding: 0 8px;
        }
        .lp-nav-links .btn-logout {
            color: var(--red); border-color: rgba(255,107,107,0.3);
        }
        .lp-nav-links .btn-logout:hover {
            color: var(--red); border-color: var(--red);
        }
        @media (max-width: 768px) {
            .lp-nav-inner { padding: 0 16px; }
            .lp-nav-links { gap: 8px; }
            .lp-nav-links a, .lp-nav-links button { font-size: 0.45rem; padding: 6px 10px; }
            .lp-nav-links .user-name, .lp-nav-links .nav-divider { display: none; }
        }

        /* ── LAYOUT ──────────────────────────────────────── */
        .db-wrap {
            max-width: 1280px; margin: 0 auto;
            padding: 84px 32px 60px;
        }

        /* ── WELCOME CARD ────────────────────────────────── */
        .welcome-card {
            background: rgba(9,19,38,0.65);
            border: 1px solid var(--border);
            padding: 28px 32px;
            margin-bottom: 24px;
            position: relative; overflow: hidden;
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        .welcome-card::before {
            content: '';
            position: absolute; top: 0; left: 0; right: 0; height: 3px;
            background: linear-gradient(90deg, var(--purple), var(--cyan), var(--yellow));
        }
        .welcome-card:hover {
            border-color: rgba(83,234,253,0.35);
            box-shadow: 0 8px 32px rgba(83,234,253,0.06);
        }
        .welcome-inner {
            display: flex; align-items: center;
            justify-content: space-between; gap: 24px; flex-wrap: wrap;
        }
        .welcome-left { display: flex; align-items: center; gap: 20px; }
        .welcome-avatar {
            width: 72px; height: 72px; flex-shrink: 0;
            background: linear-gradient(135deg, var(--bg2), var(--surface));
            border: 2px solid var(--cyan);
            display: flex; align-items: center; justify-content: center;
            position: relative; overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .welcome-avatar:hover {
            transform: scale(1.08) rotate(4deg);
            box-shadow: 0 0 20px rgba(83,234,253,0.4);
        }
        .welcome-avatar::after {
            content: '';
            position: absolute; inset: 0;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.06), transparent);
            transform: translateX(-100%);
            animation: av-shimmer 3s ease-in-out infinite;
        }
        @keyframes av-shimmer { 100% { transform: translateX(100%); } }

        .welcome-info h1 {
            font-family: var(--ff-pixel); font-size: 1rem;
            color: var(--text); line-height: 1.7; margin-bottom: 6px;
        }
        .welcome-info h1 .name { color: var(--yellow); }
        .welcome-info .sub {
            font-size: 0.8rem; color: var(--muted); line-height: 1.5;
        }
        .welcome-info .costume-tag {
            display: inline-flex; align-items: center; gap: 6px;
            font-size: 0.7rem; color: var(--purple);
            border: 1px solid rgba(237,106,255,0.3);
            padding: 3px 10px; margin-top: 6px;
            background: rgba(237,106,255,0.05);
        }

        .welcome-right {
            display: flex; align-items: center; gap: 12px; flex-wrap: wrap;
        }
        .quick-btn {
            display: inline-flex; align-items: center; gap: 8px;
            font-family: var(--ff-pixel); font-size: 0.45rem;
            padding: 10px 16px; border: 1px solid var(--border);
            color: var(--muted); text-decoration: none;
            background: transparent; transition: all 0.15s;
            cursor: pointer; letter-spacing: 0.05em;
        }
        .quick-btn:hover {
            color: var(--cyan); border-color: var(--cyan);
            transform: translateY(-2px);
            background: rgba(83,234,253,0.05);
        }
        .quick-btn.primary {
            background: var(--cyan); color: var(--bg);
            border-color: var(--cyan); font-weight: 700;
        }
        .quick-btn.primary:hover {
            box-shadow: 4px 4px 0 rgba(83,234,253,0.25);
            transform: translate(-2px,-2px);
        }

        /* ── STAT CARDS ROW ──────────────────────────────── */
        .stat-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px; margin-bottom: 24px;
        }
        @media (max-width: 900px) { .stat-row { grid-template-columns: repeat(2,1fr); } }
        @media (max-width: 480px) { .stat-row { grid-template-columns: 1fr 1fr; } }

        .stat-card {
            background: rgba(9,19,38,0.65);
            border: 1px solid var(--border);
            padding: 20px 22px;
            position: relative; overflow: hidden;
            transition: border-color 0.25s, transform 0.25s, box-shadow 0.25s;
        }
        .stat-card::after {
            content: '';
            position: absolute; bottom: 0; left: 0; right: 0; height: 2px;
        }
        .stat-card.xp::after    { background: linear-gradient(90deg, transparent, var(--yellow)); }
        .stat-card.soal::after  { background: linear-gradient(90deg, transparent, var(--green)); }
        .stat-card.rank::after  { background: linear-gradient(90deg, transparent, var(--purple)); }
        .stat-card.badge::after { background: linear-gradient(90deg, transparent, var(--cyan)); }

        .stat-card:hover {
            border-color: rgba(83,234,253,0.3);
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.3);
        }
        .stat-label {
            font-family: var(--ff-pixel); font-size: 0.42rem;
            color: var(--muted); letter-spacing: 0.12em;
            margin-bottom: 10px; display: flex; align-items: center; gap: 8px;
        }
        .stat-value {
            font-family: var(--ff-pixel); font-size: 1.6rem;
            line-height: 1; margin-bottom: 4px;
        }
        .stat-card.xp    .stat-value { color: var(--yellow); text-shadow: 0 0 16px rgba(255,223,32,0.4); }
        .stat-card.soal  .stat-value { color: var(--green);  text-shadow: 0 0 16px rgba(123,241,168,0.3); }
        .stat-card.rank  .stat-value { color: var(--purple); text-shadow: 0 0 16px rgba(237,106,255,0.3); }
        .stat-card.badge .stat-value { color: var(--cyan);   text-shadow: 0 0 16px rgba(83,234,253,0.3); }
        .stat-sub { font-size: 0.72rem; color: var(--muted); }

        .stat-icon {
            position: absolute; bottom: 16px; right: 16px;
            opacity: 0.12; transition: opacity 0.25s;
        }
        .stat-card:hover .stat-icon { opacity: 0.22; }

        /* ── MAIN GRID ───────────────────────────────────── */
        .db-grid {
            display: grid;
            grid-template-columns: 1fr 360px;
            gap: 24px; align-items: start;
        }
        @media (max-width: 1024px) { .db-grid { grid-template-columns: 1fr; } }

        /* ── PANEL ───────────────────────────────────────── */
        .panel {
            background: rgba(9,19,38,0.65);
            border: 1px solid var(--border);
            padding: 24px; position: relative;
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        .panel:hover {
            border-color: rgba(83,234,253,0.3);
            box-shadow: 0 8px 32px rgba(0,0,0,0.25);
        }
        .panel + .panel { margin-top: 24px; }

        .panel-hd {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 20px; padding-bottom: 16px;
            border-bottom: 1px solid var(--border);
        }
        .panel-title {
            font-family: var(--ff-pixel); font-size: 0.75rem;
            color: var(--cyan); letter-spacing: 0.05em;
            display: flex; align-items: center; gap: 10px; line-height: 1.6;
        }
        .panel-icon { display: flex; align-items: center; flex-shrink: 0; }

        /* ── ACTIVE MODULE ───────────────────────────────── */
        .module-card {
            background: rgba(9,19,38,0.7);
            border: 2px solid rgba(123,241,168,0.4);
            padding: 28px; position: relative; overflow: hidden;
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        .module-card::before {
            content: '';
            position: absolute; top: 0; left: 0; right: 0; height: 2px;
            background: linear-gradient(90deg, var(--green), var(--cyan));
        }
        .module-card:hover {
            border-color: rgba(123,241,168,0.7);
            box-shadow: 0 8px 32px rgba(123,241,168,0.1);
        }

        .module-name-tag {
            display: inline-block;
            font-family: var(--ff-pixel); font-size: 0.42rem;
            padding: 4px 10px; border: 1px solid rgba(123,241,168,0.4);
            color: var(--green); background: rgba(123,241,168,0.06);
            letter-spacing: 0.08em; margin-bottom: 12px;
        }
        .module-desc {
            font-size: 0.875rem; color: var(--muted);
            margin-bottom: 20px; line-height: 1.6;
        }

        /* ── PROGRESS BAR ────────────────────────────────── */
        .prog-wrap { margin-bottom: 24px; }
        .prog-meta {
            display: flex; justify-content: space-between;
            font-size: 0.78rem; margin-bottom: 8px;
        }
        .prog-meta .val { color: var(--cyan); font-weight: 700; }
        .prog-bar {
            width: 100%; height: 8px;
            background: rgba(83,234,253,0.08);
            border: 1px solid var(--border); overflow: hidden;
        }
        .prog-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--green), var(--cyan));
            transition: width 1s cubic-bezier(0.16,1,0.3,1);
            position: relative;
        }
        .prog-fill::after {
            content: ''; position: absolute; inset: 0;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.25), transparent);
            animation: prog-shine 2.5s ease infinite;
        }
        @keyframes prog-shine {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        .btn-row { display: flex; gap: 12px; flex-wrap: wrap; }
        .btn {
            display: inline-flex; align-items: center; gap: 8px;
            font-family: var(--ff-pixel); font-size: 0.48rem;
            padding: 12px 20px; border: 1px solid;
            text-decoration: none; letter-spacing: 0.05em;
            transition: all 0.15s; cursor: pointer; background: transparent;
        }
        .btn-primary {
            background: var(--cyan); color: #000;
            border-color: var(--cyan); font-weight: 700;
        }
        .btn-primary:hover {
            box-shadow: 4px 4px 0 rgba(83,234,253,0.3);
            transform: translate(-2px,-2px);
        }
        .btn-ghost {
            color: var(--text); border-color: var(--border);
        }
        .btn-ghost:hover {
            color: var(--cyan); border-color: var(--cyan);
            background: rgba(83,234,253,0.05);
        }

        /* ── BADGE PANEL (sidebar) ───────────────────────── */
        .badge-list { display: flex; flex-direction: column; gap: 10px; }
        .badge-row {
            display: flex; align-items: center; gap: 14px;
            padding: 14px 16px;
            border: 1px solid var(--border);
            background: rgba(83,234,253,0.02);
            transition: all 0.2s;
        }
        .badge-row:hover {
            background: rgba(83,234,253,0.07);
            border-color: rgba(83,234,253,0.3);
            transform: translateX(4px);
        }
        .badge-ico {
            flex-shrink: 0;
            display: flex; align-items: center; justify-content: center;
        }
        .badge-info { flex: 1; min-width: 0; }
        .badge-info .bname {
            font-size: 0.82rem; color: var(--yellow);
            margin-bottom: 3px; white-space: nowrap;
            overflow: hidden; text-overflow: ellipsis;
        }
        .badge-info .bdesc {
            font-size: 0.68rem; color: var(--muted); line-height: 1.4;
        }
        .badge-info .btime {
            font-size: 0.6rem; color: var(--cyan);
            margin-top: 3px; opacity: 0.8;
        }

        /* Empty state */
        .empty-state {
            text-align: center; padding: 28px 16px;
        }
        .empty-state .es-icon {
            font-size: 2rem; margin-bottom: 12px; display: block;
        }
        .empty-state p {
            font-size: 0.8rem; color: var(--muted); line-height: 1.6;
        }

        /* ── MINI LEADERBOARD ────────────────────────────── */
        .lb-table { width: 100%; border-collapse: collapse; }
        .lb-table th, .lb-table td {
            padding: 10px 12px; text-align: left;
            border-bottom: 1px solid var(--border);
        }
        .lb-table th {
            font-family: var(--ff-pixel); font-size: 0.4rem;
            color: var(--cyan); letter-spacing: 0.08em;
        }
        .lb-table td { font-size: 0.8rem; }
        .lb-table tr:last-child td { border-bottom: none; }
        .lb-table tr:hover td { background: rgba(83,234,253,0.04); }
        .lb-me td { background: rgba(83,234,253,0.06) !important; }
        .lb-me td:first-child { color: var(--cyan); }

        .rank-medal { font-size: 1rem; }
        .lb-table td:nth-child(3) { color: var(--yellow); font-weight: 700; }

        .rank-1-row td { color: #FFD700 !important; }
        .rank-1-row td:nth-child(3) { color: #FFD700 !important; }
        .rank-2-row td:first-child { color: #C0C0C0; }
        .rank-3-row td:first-child { color: #CD7F32; }

        .btn-full { width: 100%; text-align: center; }

        /* ── RESPONSIVE ──────────────────────────────────── */
        @media (max-width: 768px) {
            .db-wrap { padding: 80px 16px 48px; }
            .welcome-card { padding: 20px; }
            .welcome-inner { flex-direction: column; align-items: flex-start; }
            .welcome-right { width: 100%; }
            .module-card { padding: 20px; }
        }
    </style>
</head>
<body data-tutorial-completed="{{ auth()->user()->tutorial_completed ? 'true' : 'false' }}"
      data-show-tutorial="{{ $showTutorial ? 'true' : 'false' }}">
    <canvas id="bg-canvas"></canvas>

    <div class="page-content">

        <!-- Navbar -->
        <nav class="lp-nav">
            <div class="lp-nav-inner">
                <a href="{{ route('dashboard') }}" class="lp-logo">
                    <span class="bracket">&lt;</span>Code<span style="color:var(--cyan)">/</span>Tide<span class="bracket">&gt;</span>
                </a>
                <div class="lp-nav-links">
                    <a href="{{ route('dashboard') }}" class="active">Dashboard</a>
                    <a href="{{ route('modules.index') }}">Materi</a>
                    <a href="{{ route('leaderboard.index') }}">Leaderboard</a>
                    <a href="{{ route('costumes.index') }}">Kostumisasi</a>
                    <div class="nav-divider"></div>
                    <span class="user-name">{{ auth()->user()->nickname }}</span>
                    <button type="button" class="btn-restart-tutorial" onclick="restartTutorial()" title="Ulangi tutorial panduan">
                        ❓ Tutorial
                    </button>
                    <div class="nav-divider"></div>
                    <form method="POST" action="{{ route('logout') }}" style="display:inline;margin:0;">
                        @csrf
                        <button type="submit" class="btn-logout">Logout</button>
                    </form>
                </div>
            </div>
        </nav>

        <div class="db-wrap">

            <!-- ── WELCOME CARD ──────────────────────────────── -->
            <div class="welcome-card">
                <div class="welcome-inner">
                    <div class="welcome-left">
                        <div class="welcome-avatar" id="db-avatar">
                            @if($activeCostume && isset($activeCostume->avatar_icon))
                                <span data-pxl-icon="{{ $activeCostume->avatar_icon }}" data-pxl-scale="5"></span>
                            @else
                                <span data-pxl-icon="user" data-pxl-scale="5"></span>
                            @endif
                        </div>
                        <div class="welcome-info">
                            <h1>Halo, <span class="name">{{ auth()->user()->nickname }}</span>!</h1>
                            <p class="sub">
                                Rank <strong style="color:var(--cyan)">#{{ $stats['rank'] }}</strong>
                                &nbsp;·&nbsp;
                                {{ $stats['completed_questions'] }}/{{ $stats['total_questions'] }} soal selesai
                            </p>
                            <div class="costume-tag">
                                <span data-pxl-icon="gem" data-pxl-scale="1" style="display:inline-flex;"></span>
                                {{ $activeCostume ? $activeCostume->name : 'Petualang Dasar' }}
                            </div>
                        </div>
                    </div>

                    <div class="welcome-right">
                        @if($nextQuestion)
                        <a href="{{ route('questions.show', $nextQuestion->id) }}" class="quick-btn primary">
                            ▶ Lanjutkan Belajar
                        </a>
                        @endif
                        <a href="{{ route('modules.index') }}" class="quick-btn">
                            Semua Materi
                        </a>
                        <a href="{{ route('costumes.index') }}" class="quick-btn">
                            Kostumisasi
                        </a>
                        <a href="{{ route('profile.edit') }}" class="quick-btn">
                            Profil
                        </a>
                    </div>
                </div>
            </div>

            <!-- ── STAT CARDS ──────────────────────────────────── -->
            <div class="stat-row">

                <div class="stat-card xp">
                    <div class="stat-label">
                        <span data-pxl-icon="lightning" data-pxl-scale="1" style="display:inline-flex;"></span>
                        TOTAL XP
                    </div>
                    <div class="stat-value">{{ number_format($stats['total_xp']) }}</div>
                    <div class="stat-sub">experience points</div>
                    <div class="stat-icon" data-pxl-icon="lightning" data-pxl-scale="5"></div>
                </div>

                <div class="stat-card soal">
                    <div class="stat-label">
                        <span data-pxl-icon="check" data-pxl-scale="1" style="display:inline-flex;"></span>
                        SOAL SELESAI
                    </div>
                    <div class="stat-value">{{ $stats['completed_questions'] }}</div>
                    <div class="stat-sub">dari {{ $stats['total_questions'] }} soal &nbsp; ({{ number_format($stats['completion_percentage'], 0) }}%)</div>
                    <div class="stat-icon" data-pxl-icon="check" data-pxl-scale="5"></div>
                </div>

                <div class="stat-card rank">
                    <div class="stat-label">
                        <span data-pxl-icon="crown" data-pxl-scale="1" style="display:inline-flex;"></span>
                        RANKING
                    </div>
                    <div class="stat-value">#{{ $stats['rank'] }}</div>
                    <div class="stat-sub">dari semua pengguna</div>
                    <div class="stat-icon" data-pxl-icon="crown" data-pxl-scale="5"></div>
                </div>

                <div class="stat-card badge">
                    <div class="stat-label">
                        <span data-pxl-icon="trophy" data-pxl-scale="1" style="display:inline-flex;"></span>
                        BADGE
                    </div>
                    <div class="stat-value">{{ $stats['badges_count'] }}</div>
                    <div class="stat-sub">badge diperoleh</div>
                    <div class="stat-icon" data-pxl-icon="trophy" data-pxl-scale="5"></div>
                </div>

            </div>

            <!-- ── MAIN GRID ──────────────────────────────────── -->
            <div class="db-grid">

                <!-- Left — Active Module -->
                <div class="db-main">

                    @php
                        $activeModule = collect($modules)->first(fn($m) => $m['is_unlocked'] && !$m['is_completed']);
                    @endphp

                    @if($activeModule)
                    <div class="module-card">
                        <div class="panel-hd">
                            <div class="panel-title">
                                <span class="panel-icon" data-pxl-icon="book" data-pxl-scale="2"></span>
                                Modul Aktif
                            </div>
                            <span class="panel-icon" data-pxl-icon="sparkle" data-pxl-scale="2"
                                  style="animation: pulse-icon 3s ease-in-out infinite;"></span>
                        </div>

                        <div class="module-name-tag">📦 {{ $activeModule['title'] }}</div>
                        <p class="module-desc">{{ $activeModule['description'] ?? 'Terus semangat menyelesaikan modul ini!' }}</p>

                        <div class="prog-wrap">
                            <div class="prog-meta">
                                <span>Progress Modul</span>
                                <span class="val">{{ number_format($activeModule['completion_percentage'], 0) }}%</span>
                            </div>
                            <div class="prog-bar">
                                <div class="prog-fill" style="width: {{ $activeModule['completion_percentage'] }}%"></div>
                            </div>
                        </div>

                        <div class="btn-row">
                            @if($nextQuestion)
                            <a href="{{ route('questions.show', $nextQuestion->id) }}" class="btn btn-primary">
                                Lanjutkan Belajar →
                            </a>
                            @endif
                            <a href="{{ route('modules.index') }}" class="btn btn-ghost">
                                Semua Materi
                            </a>
                        </div>
                    </div>

                    @else
                    <div class="module-card" style="border-color: rgba(123,241,168,0.6);">
                        <div class="panel-hd">
                            <div class="panel-title">
                                <span class="panel-icon" data-pxl-icon="check" data-pxl-scale="2"></span>
                                Semua Modul Selesai!
                            </div>
                        </div>
                        <p style="color: var(--green); font-size:0.9rem; margin-bottom:20px; line-height:1.6;">
                            Luar biasa — kamu telah menyelesaikan semua modul yang tersedia. 🎉
                        </p>
                        <a href="{{ route('modules.index') }}" class="btn btn-primary">
                            Lihat Semua Modul →
                        </a>
                    </div>
                    @endif

                    <!-- ── OVERALL PROGRESS ──────────────────────── -->
                    <div class="panel" style="margin-top:24px;">
                        <div class="panel-hd">
                            <div class="panel-title">
                                <span class="panel-icon" data-pxl-icon="target" data-pxl-scale="2"></span>
                                Progress Keseluruhan
                            </div>
                        </div>

                        <div class="prog-wrap" style="margin-bottom: 0;">
                            <div class="prog-meta">
                                <span>{{ $stats['completed_questions'] }} / {{ $stats['total_questions'] }} soal selesai</span>
                                <span class="val">{{ number_format($stats['completion_percentage'], 0) }}%</span>
                            </div>
                            <div class="prog-bar" style="height:12px;">
                                <div class="prog-fill" style="width: {{ $stats['completion_percentage'] }}%;
                                    background: linear-gradient(90deg, var(--cyan), var(--purple));"></div>
                            </div>
                        </div>

                        <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:12px; margin-top:20px;">
                            @foreach($modules as $mod)
                            <div style="background:rgba(83,234,253,0.04); border:1px solid {{ $mod['is_completed'] ? 'rgba(123,241,168,0.4)' : 'var(--border)' }};
                                        padding:12px; text-align:center; transition:all 0.2s;"
                                 title="{{ $mod['title'] }}">
                                <div style="font-family:var(--ff-pixel); font-size:0.4rem; color:{{ $mod['is_completed'] ? 'var(--green)' : ($mod['is_unlocked'] ? 'var(--yellow)' : 'var(--muted)') }}; margin-bottom:6px;">
                                    M{{ str_pad($mod['id'], 2, '0', STR_PAD_LEFT) }}
                                </div>
                                <div style="font-size:0.72rem; color:var(--muted); overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                                    {{ $mod['title'] }}
                                </div>
                                <div style="font-family:var(--ff-pixel); font-size:0.5rem; margin-top:6px;
                                            color:{{ $mod['is_completed'] ? 'var(--green)' : ($mod['is_unlocked'] ? 'var(--cyan)' : 'var(--muted)') }}">
                                    {{ number_format($mod['completion_percentage'],0) }}%
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                </div>

                <!-- Sidebar -->
                <div class="db-sidebar">

                    <!-- Mini Leaderboard -->
                    <div class="panel">
                        <div class="panel-hd">
                            <div class="panel-title">
                                <span class="panel-icon" data-pxl-icon="star" data-pxl-scale="2"></span>
                                Top Ranking
                            </div>
                            <span class="panel-icon" data-pxl-icon="crown" data-pxl-scale="2"></span>
                        </div>

                        @php $authUserId = auth()->id(); @endphp
                        <table class="lb-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Pemain</th>
                                    <th>XP</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topUsers as $i => $lbUser)
                                <tr class="{{ $i === 0 ? 'rank-1-row' : ($i === 1 ? 'rank-2-row' : ($i === 2 ? 'rank-3-row' : '')) }} {{ $lbUser['id'] === $authUserId ? 'lb-me' : '' }}">
                                    <td>
                                        @if($i === 0) <span class="rank-medal">🥇</span>
                                        @elseif($i === 1) <span class="rank-medal">🥈</span>
                                        @elseif($i === 2) <span class="rank-medal">🥉</span>
                                        @else {{ $i + 1 }}
                                        @endif
                                    </td>
                                    <td>
                                        {{ $lbUser['nickname'] }}
                                        @if($lbUser['id'] === $authUserId)
                                            <span style="font-size:0.6rem;color:var(--cyan);"> (kamu)</span>
                                        @endif
                                    </td>
                                    <td>{{ number_format($lbUser['xp']) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <a href="{{ route('leaderboard.index') }}" class="btn btn-ghost btn-full" style="margin-top:16px;">
                            Lihat Semua →
                        </a>
                    </div>

                    <!-- Badge Panel (menggantikan Pengaturan) -->
                    <div class="panel">
                        <div class="panel-hd">
                            <div class="panel-title">
                                <span class="panel-icon" data-pxl-icon="trophy" data-pxl-scale="2"></span>
                                Badge Diperoleh
                            </div>
                            <span style="font-family:var(--ff-pixel);font-size:0.4rem;color:var(--yellow);">
                                {{ $stats['badges_count'] }} badge
                            </span>
                        </div>

                        @if($recentBadges->count() > 0)
                        <div class="badge-list">
                            @foreach($recentBadges as $badge)
                            <div class="badge-row">
                                <div class="badge-ico">
                                    <span data-pxl-icon="{{ $badge->icon ?? 'trophy' }}" data-pxl-scale="3"></span>
                                </div>
                                <div class="badge-info">
                                    <div class="bname">{{ $badge->name }}</div>
                                    <div class="bdesc">{{ $badge->description }}</div>
                                    <div class="btime">{{ $badge->pivot->earned_at->diffForHumans() }}</div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="empty-state">
                            <span class="es-icon" data-pxl-icon="medal" data-pxl-scale="4"
                                  style="display:flex;justify-content:center;margin-bottom:12px;opacity:0.4;"></span>
                            <p>Belum ada badge.<br>Selesaikan modul untuk mendapatkan badge pertamamu!</p>
                        </div>
                        @endif
                    </div>

                </div>
            </div>

        </div><!-- /db-wrap -->

    </div><!-- /page-content -->

    <script src="{{ asset('js/pxlkit-renderer.js') }}"></script>
    <script src="{{ asset('js/scene3d.js') }}"></script>
    <script>
        /* PxlKit icon map */
        const PXL_MAP = {
            lightning: () => typeof PXL_LIGHTNING    !== 'undefined' ? PXL_LIGHTNING    : null,
            star:      () => typeof PXL_SPARKLE_STAR !== 'undefined' ? PXL_SPARKLE_STAR : null,
            trophy:    () => typeof PXL_TROPHY       !== 'undefined' ? PXL_TROPHY       : null,
            crown:     () => typeof PXL_CROWN        !== 'undefined' ? PXL_CROWN        : null,
            coin:      () => typeof PXL_COIN         !== 'undefined' ? PXL_COIN         : null,
            medal:     () => typeof PXL_MEDAL        !== 'undefined' ? PXL_MEDAL        : null,
            check:     () => typeof PXL_CHECK        !== 'undefined' ? PXL_CHECK        : null,
            gem:       () => typeof PXL_FLOATING_GEM !== 'undefined' ? PXL_FLOATING_GEM : null,
            target:    () => typeof PXL_TARGET_HIT   !== 'undefined' ? PXL_TARGET_HIT   : null,
            sparkle:   () => typeof PXL_SPARKLE_STAR !== 'undefined' ? PXL_SPARKLE_STAR : null,
            /* aliases */
            book:      () => typeof PXL_RETRO_TV     !== 'undefined' ? PXL_RETRO_TV     : null,
            user:      () => typeof PXL_GHOST_FRIEND !== 'undefined' ? PXL_GHOST_FRIEND : null,
        };

        function injectPxlIcons() {
            if (typeof PXLKIT === 'undefined') { setTimeout(injectPxlIcons, 100); return; }
            document.querySelectorAll('[data-pxl-icon]:not([data-pxl-done])').forEach(el => {
                const name   = el.dataset.pxlIcon;
                const scale  = parseFloat(el.dataset.pxlScale || '2');
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
            requestAnimationFrame(() => requestAnimationFrame(injectPxlIcons));
        });

        /* Animate progress bars on load */
        window.addEventListener('load', () => {
            document.querySelectorAll('.prog-fill').forEach(bar => {
                const w = bar.style.width;
                bar.style.width = '0%';
                setTimeout(() => { bar.style.width = w; }, 250);
            });
        });

        /* Stat card entrance animation */
        const statCards = document.querySelectorAll('.stat-card');
        statCards.forEach((card, i) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(16px)';
            setTimeout(() => {
                card.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 100 + i * 80);
        });
    </script>
    <style>
        @keyframes pulse-icon {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.15); opacity: 0.75; }
        }
    </style>
</body>
</html>
