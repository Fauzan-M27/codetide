<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard — CodeTide</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/toast.css') }}">
    <link rel="stylesheet" href="{{ asset('css/loading.css') }}">
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
            --gold: #FFD700;
            --silver: #C0C0C0;
            --bronze: #CD7F32;
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

        /* ── LEADERBOARD CONTAINER ─────────────────────── */
        .leaderboard-container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 80px 32px 60px;
        }

        /* ── HEADER SECTION ──────────────────────────────── */
        .leaderboard-header {
            text-align: center;
            margin-bottom: 48px;
            padding: 48px 32px;
            background: rgba(9, 19, 38, 0.6);
            border: 1px solid var(--border);
            position: relative;
            overflow: hidden;
        }

        .leaderboard-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--gold), var(--yellow), var(--cyan));
        }

        .leaderboard-header h1 {
            font-family: var(--ff-pixel);
            font-size: 2rem;
            color: var(--yellow);
            text-shadow: 0 0 24px rgba(255, 223, 32, 0.6);
            margin-bottom: 16px;
            line-height: 1.6;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 16px;
            flex-wrap: wrap;
        }

        .leaderboard-header p {
            font-size: 0.9rem;
            color: var(--muted);
            line-height: 1.6;
        }

        /* ── USER STATS PANEL ──────────────────────────── */
        .user-stats-panel {
            background: rgba(9, 19, 38, 0.7);
            border: 2px solid var(--purple);
            padding: 32px;
            margin-bottom: 40px;
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 32px;
            align-items: center;
            position: relative;
            overflow: hidden;
            transition: all 0.3s;
        }

        .user-stats-panel::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, var(--purple), var(--cyan));
        }

        .user-stats-panel:hover {
            border-color: var(--cyan);
            box-shadow: 0 8px 32px rgba(237, 106, 255, 0.2);
            transform: translateY(-2px);
        }

        .user-stats-avatar {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, var(--cyan) 0%, var(--purple) 100%);
            border: 3px solid var(--cyan);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .user-stats-avatar::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.15), transparent);
            animation: avatar-shimmer 3s infinite;
        }

        @keyframes avatar-shimmer {
            0% { transform: translateX(-100%) translateY(-100%); }
            100% { transform: translateX(100%) translateY(100%); }
        }

        .user-stats-info {
            display: grid;
            gap: 16px;
        }

        .user-stats-name {
            font-family: var(--ff-pixel);
            font-size: 1.4rem;
            color: var(--cyan);
            margin-bottom: 4px;
        }

        .user-stats-rank {
            font-family: var(--ff-pixel);
            font-size: 0.7rem;
            color: var(--yellow);
            letter-spacing: 0.05em;
        }

        .user-stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 16px;
            margin-top: 12px;
        }

        .stat-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            background: rgba(83, 234, 253, 0.05);
            border: 1px solid var(--border);
            transition: all 0.3s;
        }

        .stat-item:hover {
            background: rgba(83, 234, 253, 0.1);
            border-color: var(--cyan);
            transform: translateY(-2px);
        }

        .stat-icon {
            flex-shrink: 0;
            display: flex;
            align-items: center;
        }

        .stat-text .stat-value {
            font-family: var(--ff-pixel);
            font-size: 1.2rem;
            color: var(--yellow);
            display: block;
            line-height: 1;
            margin-bottom: 4px;
        }

        .stat-text .stat-label {
            font-size: 0.65rem;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* ── LEADERBOARD TABLE ──────────────────────────── */
        .leaderboard-panel {
            background: rgba(9, 19, 38, 0.6);
            border: 1px solid var(--border);
            padding: 32px;
            position: relative;
            overflow: hidden;
        }

        .leaderboard-panel::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, var(--cyan), var(--green));
        }

        .leaderboard-table {
            width: 100%;
            border-collapse: collapse;
        }

        .leaderboard-table thead {
            border-bottom: 2px solid var(--border);
        }

        .leaderboard-table th {
            font-family: var(--ff-pixel);
            font-size: 0.55rem;
            color: var(--cyan);
            text-align: left;
            padding: 16px 12px;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        .leaderboard-table th.center {
            text-align: center;
        }

        .leaderboard-table tbody tr {
            border-bottom: 1px solid var(--border);
            transition: all 0.2s;
        }

        .leaderboard-table tbody tr:hover {
            background: rgba(83, 234, 253, 0.08);
        }

        .leaderboard-table tbody tr.current-user {
            background: rgba(237, 106, 255, 0.15);
            border: 2px solid var(--purple);
            box-shadow: 0 0 20px rgba(237, 106, 255, 0.3);
        }

        .leaderboard-table tbody tr.current-user td {
            font-weight: 700;
            color: var(--cyan);
        }

        .leaderboard-table td {
            padding: 16px 12px;
            font-size: 0.85rem;
        }

        .leaderboard-table td.center {
            text-align: center;
        }

        /* Rank styling */
        .rank-cell {
            font-family: var(--ff-pixel);
            font-size: 1rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .rank-1 .rank-cell { color: var(--gold); }
        .rank-2 .rank-cell { color: var(--silver); }
        .rank-3 .rank-cell { color: var(--bronze); }

        .medal-icon {
            display: inline-flex;
            animation: medal-spin 4s ease-in-out infinite;
        }

        @keyframes medal-spin {
            0%, 100% { transform: rotateY(0deg) scale(1); }
            50% { transform: rotateY(180deg) scale(1.1); }
        }

        /* Player cell */
        .player-cell {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .player-avatar {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, var(--cyan) 0%, var(--purple) 100%);
            border: 2px solid var(--cyan);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .player-info .player-name {
            font-family: var(--ff-pixel);
            font-size: 0.8rem;
            color: var(--text);
            margin-bottom: 4px;
        }

        .player-info .player-code {
            font-size: 0.65rem;
            color: var(--muted);
            font-family: var(--ff-code);
        }

        /* XP cell */
        .xp-cell {
            font-family: var(--ff-pixel);
            font-size: 1rem;
            color: var(--yellow);
            font-weight: 700;
        }

        /* Badge cell */
        .badge-count {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 12px;
            background: rgba(255, 223, 32, 0.1);
            border: 1px solid rgba(255, 223, 32, 0.3);
            border-radius: 4px;
        }

        .badge-count .count {
            font-family: var(--ff-pixel);
            font-size: 0.7rem;
            color: var(--yellow);
        }

        /* Questions cell */
        .questions-cell {
            font-family: var(--ff-pixel);
            font-size: 0.75rem;
            color: var(--green);
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 60px 32px;
            color: var(--muted);
        }

        .empty-state-icon {
            margin-bottom: 24px;
            opacity: 0.5;
        }

        .empty-state h3 {
            font-family: var(--ff-pixel);
            font-size: 1rem;
            color: var(--cyan);
            margin-bottom: 12px;
        }

        .empty-state p {
            font-size: 0.85rem;
            line-height: 1.6;
        }

        /* ── PAGINATION ──────────────────────────────────── */
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 12px;
            margin-top: 32px;
            flex-wrap: wrap;
        }

        .pagination a,
        .pagination span {
            font-family: var(--ff-pixel);
            font-size: 0.5rem;
            padding: 10px 16px;
            border: 1px solid var(--border);
            color: var(--muted);
            text-decoration: none;
            transition: all 0.2s;
            background: transparent;
        }

        .pagination a:hover {
            color: var(--cyan);
            border-color: var(--cyan);
            transform: translateY(-2px);
        }

        .pagination .active {
            color: var(--cyan);
            border-color: var(--cyan);
            background: rgba(83, 234, 253, 0.1);
        }

        .pagination .disabled {
            opacity: 0.3;
            cursor: not-allowed;
        }

        /* ── RESPONSIVE ──────────────────────────────────── */
        @media (max-width: 1024px) {
            .user-stats-panel {
                grid-template-columns: 1fr;
                text-align: center;
            }

            .user-stats-avatar {
                margin: 0 auto;
            }

            .user-stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .leaderboard-container {
                padding: 80px 16px 40px;
            }

            .leaderboard-header {
                padding: 32px 20px;
            }

            .leaderboard-header h1 {
                font-size: 1.2rem;
            }

            .user-stats-panel {
                padding: 24px;
            }

            .user-stats-grid {
                grid-template-columns: 1fr;
            }

            .leaderboard-panel {
                padding: 20px;
                overflow-x: auto;
            }

            .leaderboard-table {
                min-width: 600px;
            }

            .leaderboard-table th,
            .leaderboard-table td {
                padding: 12px 8px;
                font-size: 0.75rem;
            }

            .player-avatar {
                width: 40px;
                height: 40px;
            }

            .player-info .player-name {
                font-size: 0.7rem;
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
                    <a href="{{ route('leaderboard.index') }}" class="active">Leaderboard</a>
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

        <!-- Leaderboard Container -->
        <main class="leaderboard-container">
            
            <!-- Header -->
            <div class="leaderboard-header">
                <h1>
                    <span data-pxl-icon="trophy" data-pxl-scale="3"></span>
                    LEADERBOARD
                    <span data-pxl-icon="crown" data-pxl-scale="3"></span>
                </h1>
                <p>Lihat ranking pemain terbaik CodeTide berdasarkan XP yang dikumpulkan</p>
            </div>

            <!-- User Stats Panel -->
            <div class="user-stats-panel">
                <div class="user-stats-avatar">
                    @if($currentUserCostume && isset($currentUserCostume->avatar_icon))
                        <span data-pxl-icon="{{ $currentUserCostume->avatar_icon }}" data-pxl-scale="7"></span>
                    @else
                        <span data-pxl-icon="user" data-pxl-scale="7"></span>
                    @endif
                </div>
                <div class="user-stats-info">
                    <div>
                        <div class="user-stats-name">{{ auth()->user()->nickname }}</div>
                        <div class="user-stats-rank">
                            PERINGKAT #{{ $userRank }} dari {{ $totalUsers }} pemain
                        </div>
                    </div>
                    <div class="user-stats-grid">
                        <div class="stat-item">
                            <div class="stat-icon">
                                <span data-pxl-icon="lightning" data-pxl-scale="3"></span>
                            </div>
                            <div class="stat-text">
                                <span class="stat-value">{{ $userStats['total_xp'] }}</span>
                                <span class="stat-label">Total XP</span>
                            </div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-icon">
                                <span data-pxl-icon="check" data-pxl-scale="3"></span>
                            </div>
                            <div class="stat-text">
                                <span class="stat-value">{{ $userStats['completed_questions'] }}</span>
                                <span class="stat-label">Soal Selesai</span>
                            </div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-icon">
                                <span data-pxl-icon="medal" data-pxl-scale="3"></span>
                            </div>
                            <div class="stat-text">
                                <span class="stat-value">{{ $userStats['badges_earned'] }}</span>
                                <span class="stat-label">Badge</span>
                            </div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-icon">
                                <span data-pxl-icon="target" data-pxl-scale="3"></span>
                            </div>
                            <div class="stat-text">
                                <span class="stat-value">{{ number_format($userStats['average_score'], 0) }}%</span>
                                <span class="stat-label">Rata-rata</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Leaderboard Table -->
            <div class="leaderboard-panel">
                @if($topPlayers->count() > 0)
                    <table class="leaderboard-table">
                        <thead>
                            <tr>
                                <th>RANK</th>
                                <th>PEMAIN</th>
                                <th class="center">XP</th>
                                <th class="center">BADGE</th>
                                <th class="center">SOAL</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topPlayers as $index => $player)
                                @php
                                    $rank = ($topPlayers->currentPage() - 1) * $topPlayers->perPage() + $index + 1;
                                    $isCurrentUser = $player->id === auth()->id();
                                @endphp
                                <tr class="rank-{{ $rank <= 3 ? $rank : '' }} {{ $isCurrentUser ? 'current-user' : '' }}">
                                    <td>
                                        <div class="rank-cell">
                                            @if($rank === 1)
                                                <span class="medal-icon" data-pxl-icon="trophy" data-pxl-scale="2.5" style="filter: drop-shadow(0 0 8px #FFD700);"></span>
                                            @elseif($rank === 2)
                                                <span class="medal-icon" data-pxl-icon="medal" data-pxl-scale="2.5" style="filter: drop-shadow(0 0 8px #C0C0C0);"></span>
                                            @elseif($rank === 3)
                                                <span class="medal-icon" data-pxl-icon="medal" data-pxl-scale="2.5" style="filter: drop-shadow(0 0 8px #CD7F32);"></span>
                                            @endif
                                            <span>#{{ $rank }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="player-cell">
                                            <div class="player-avatar">
                                                @php
                                                    $costume = $player->costumes()->wherePivot('is_active', true)->first();
                                                @endphp
                                                @if($costume && isset($costume->avatar_icon))
                                                    <span data-pxl-icon="{{ $costume->avatar_icon }}" data-pxl-scale="4"></span>
                                                @else
                                                    <span data-pxl-icon="user" data-pxl-scale="4"></span>
                                                @endif
                                            </div>
                                            <div class="player-info">
                                                <div class="player-name">
                                                    {{ $player->nickname }}
                                                    @if($isCurrentUser)
                                                        <span style="color: var(--purple); font-size: 0.6rem;"> (Anda)</span>
                                                    @endif
                                                </div>
                                                <div class="player-code">{{ $player->participant_code }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="center">
                                        <div class="xp-cell">{{ $player->getTotalXP() }}</div>
                                    </td>
                                    <td class="center">
                                        <div class="badge-count">
                                            <span data-pxl-icon="star" data-pxl-scale="1.5"></span>
                                            <span class="count">{{ $player->badges->count() }}</span>
                                        </div>
                                    </td>
                                    <td class="center">
                                        <div class="questions-cell">
                                            {{ $player->progress()->whereNotNull('completed_at')->count() }}/40
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    @if($topPlayers->hasPages())
                        <div class="pagination">
                            @if($topPlayers->onFirstPage())
                                <span class="disabled">← Prev</span>
                            @else
                                <a href="{{ $topPlayers->previousPageUrl() }}">← Prev</a>
                            @endif

                            @foreach($topPlayers->getUrlRange(1, $topPlayers->lastPage()) as $page => $url)
                                @if($page == $topPlayers->currentPage())
                                    <span class="active">{{ $page }}</span>
                                @else
                                    <a href="{{ $url }}">{{ $page }}</a>
                                @endif
                            @endforeach

                            @if($topPlayers->hasMorePages())
                                <a href="{{ $topPlayers->nextPageUrl() }}">Next →</a>
                            @else
                                <span class="disabled">Next →</span>
                            @endif
                        </div>
                    @endif
                @else
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <span data-pxl-icon="ghost" data-pxl-scale="6"></span>
                        </div>
                        <h3>Belum Ada Pemain</h3>
                        <p>Jadilah yang pertama menyelesaikan soal dan raih peringkat teratas!</p>
                    </div>
                @endif
            </div>

        </main>

    </div>

    <script src="{{ asset('js/pxlkit-renderer.js') }}"></script>
    <script src="{{ asset('js/scene3d.js') }}"></script>
    <script src="{{ asset('js/toast.js') }}"></script>
    <script src="{{ asset('js/loading.js') }}"></script>
    <script>
        // PxlKit Icon Map
        const PXL_MAP = {
            trophy:   () => typeof PXL_TROPHY        !== 'undefined' ? PXL_TROPHY        : null,
            crown:    () => typeof PXL_CROWN         !== 'undefined' ? PXL_CROWN         : null,
            medal:    () => typeof PXL_MEDAL         !== 'undefined' ? PXL_MEDAL         : null,
            star:     () => typeof PXL_SPARKLE_STAR  !== 'undefined' ? PXL_SPARKLE_STAR  : null,
            lightning:() => typeof PXL_LIGHTNING     !== 'undefined' ? PXL_LIGHTNING     : null,
            check:    () => typeof PXL_CHECK         !== 'undefined' ? PXL_CHECK         : null,
            target:   () => typeof PXL_TARGET_HIT    !== 'undefined' ? PXL_TARGET_HIT    : null,
            user:     () => typeof PXL_GHOST_FRIEND  !== 'undefined' ? PXL_GHOST_FRIEND  : null,
            ghost:    () => typeof PXL_GHOST_FRIEND  !== 'undefined' ? PXL_GHOST_FRIEND  : null,
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

        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            injectPxlIcons();
        });

        // Re-inject if needed
        if (document.readyState === 'complete') {
            injectPxlIcons();
        }
        
        // Show loading on pagination clicks
        document.querySelectorAll('.pagination a:not(.disabled)').forEach(link => {
            link.addEventListener('click', function(e) {
                Loading.show('Memuat Leaderboard...', 'Sabar ya, lagi ambil data ranking');
            });
        });
        
        // Hide any lingering loading
        Loading.hide();
    </script>
</body>
</html>
