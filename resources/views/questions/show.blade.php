<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $question->title }} — CodeTide</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    
    <!-- Monaco Editor -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/monaco-editor@0.50.0/min/vs/editor/editor.main.css">
    
    <!-- Toast & Loading System -->
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
            --bg: #040D1A;
            --bg2: #091326;
            --border: rgba(83, 234, 253, 0.15);
            --text: #E8F4FF;
            --muted: rgba(232, 244, 255, 0.55);
            --faint: rgba(232, 244, 255, 0.25);
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
            opacity: 0.25 !important;
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
            max-width: 1400px;
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

        .lp-nav-links a {
            font-family: var(--ff-pixel);
            font-size: 0.5rem;
            padding: 8px 16px;
            border: 1px solid var(--border);
            color: var(--muted);
            text-decoration: none;
            letter-spacing: 0.05em;
            transition: all 0.15s;
            background: transparent;
            white-space: nowrap;
        }

        .lp-nav-links a:hover {
            color: var(--cyan);
            border-color: var(--cyan);
            transform: translateY(-1px);
        }

        /* ── QUESTION CONTAINER ──────────────────────── */
        .question-container {
            max-width: 1400px;
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

        /* ── QUESTION HEADER ─────────────────────────── */
        .question-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: var(--sp-4);
            margin-bottom: var(--sp-5);
            flex-wrap: wrap;
        }

        .question-title-group {
            display: flex;
            align-items: center;
            gap: var(--sp-3);
            flex-wrap: wrap;
        }

        .q-header-icon {
            flex-shrink: 0;
            display: flex;
            align-items: center;
        }

        .question-header h1 {
            font-family: var(--ff-pixel);
            font-size: 1.2rem;
            color: var(--cyan);
            line-height: 1.6;
        }

        .question-meta {
            color: var(--muted);
            font-size: 0.75rem;
            margin-top: var(--sp-2);
        }

        /* Progress dots */
        .q-dots {
            display: flex;
            gap: 6px;
            align-items: center;
        }

        .q-dot {
            width: 8px;
            height: 8px;
            background: var(--border);
            transition: background 0.2s, transform 0.2s;
            cursor: pointer;
        }

        .q-dot.done {
            background: var(--green);
            transform: scale(1.2);
        }

        .q-dot.current {
            background: var(--cyan);
            animation: pulse-dot 1.5s ease-in-out infinite;
        }

        @keyframes pulse-dot {
            0%, 100% {
                transform: scale(1);
                box-shadow: none;
            }
            50% {
                transform: scale(1.4);
                box-shadow: 0 0 6px var(--cyan);
            }
        }

        /* ── ALERT ───────────────────────────────────── */
        .alert {
            padding: var(--sp-4);
            margin-bottom: var(--sp-5);
            border: 2px solid;
            border-radius: 4px;
            display: none;
        }

        .alert.show {
            display: block;
        }

        .alert-success {
            background: rgba(123, 241, 168, 0.1);
            border-color: var(--green);
            color: var(--green);
        }

        .alert-error {
            background: rgba(255, 107, 107, 0.1);
            border-color: #FF6B6B;
            color: #FF6B6B;
        }

        .alert-info {
            background: rgba(83, 234, 253, 0.1);
            border-color: var(--cyan);
            color: var(--cyan);
        }

        /* ── INSTRUCTION SECTION ─────────────────────── */
        .instruction-section {
            display: flex;
            align-items: flex-start;
            gap: var(--sp-4);
            margin-bottom: var(--sp-6);
            padding: var(--sp-5);
            background: rgba(9, 19, 38, 0.4);
            border: 1px solid var(--border);
        }

        .instruction-text {
            flex: 1;
        }

        .instruction-text h2 {
            font-family: var(--ff-pixel);
            font-size: 0.85rem;
            color: var(--text);
            margin-bottom: var(--sp-2);
        }

        .instruction-text p {
            color: var(--muted);
            line-height: 1.7;
            font-size: 0.9rem;
        }

        .instruction-icon-slot {
            flex-shrink: 0;
            display: flex;
            align-items: center;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        /* ── WORKSPACE (Split Screen) ───────────────── */
        .workspace {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: var(--sp-4);
            margin-bottom: var(--sp-5);
            min-height: 600px;
        }

        .editor-panel,
        .preview-panel {
            display: flex;
            flex-direction: column;
            background: rgba(9, 19, 38, 0.4);
            border: 2px solid var(--border);
            overflow: hidden;
        }

        .panel-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: var(--sp-3) var(--sp-4);
            background: rgba(4, 13, 26, 0.8);
            border-bottom: 1px solid var(--border);
        }

        .panel-title-row {
            display: flex;
            align-items: center;
            gap: var(--sp-2);
            font-family: var(--ff-pixel);
            font-size: 0.6rem;
            color: var(--text);
        }

        .panel-title-icon-slot {
            display: flex;
            align-items: center;
            flex-shrink: 0;
        }

        .panel-title .type-label {
            color: var(--faint);
            font-size: 0.4rem;
            font-family: var(--ff-code);
            text-transform: uppercase;
        }

        /* Monaco Editor Container */
        .monaco-container {
            flex: 1;
            position: relative;
            min-height: 0;
        }

        #editor {
            width: 100%;
            height: 100%;
        }

        /* Preview Frame */
        .preview-frame {
            flex: 1;
            width: 100%;
            border: none;
            background: white;
        }

        /* Console Panel (for JS questions) */
        .console-panel {
            height: 150px;
            background: rgba(4, 13, 26, 0.95);
            border-top: 1px solid var(--border);
            overflow-y: auto;
            font-family: var(--ff-code);
            font-size: 0.75rem;
        }

        .console-panel .console-line {
            padding: 4px 12px;
            border-bottom: 1px solid rgba(83, 234, 253, 0.05);
        }

        .console-panel .console-log {
            color: var(--text);
        }

        .console-panel .console-error {
            color: #FF6B6B;
        }

        .console-panel .console-warn {
            color: var(--yellow);
        }

        /* ── ACTION BAR ──────────────────────────────── */
        .question-actions {
            display: flex;
            align-items: center;
            gap: var(--sp-3);
            flex-wrap: wrap;
            margin-bottom: var(--sp-5);
        }

        .btn {
            font-family: var(--ff-pixel);
            font-size: 0.5rem;
            padding: 12px 24px;
            border: 2px solid;
            background: transparent;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
            letter-spacing: 0.05em;
            white-space: nowrap;
        }

        .btn-primary {
            border-color: var(--cyan);
            color: var(--cyan);
        }

        .btn-primary:hover:not(:disabled) {
            background: var(--cyan);
            color: var(--bg);
            transform: translateY(-2px);
            box-shadow: 4px 4px 0 rgba(83, 234, 253, 0.3);
        }

        .btn-primary:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .btn-secondary {
            border-color: var(--border);
            color: var(--muted);
        }

        .btn-secondary:hover {
            border-color: var(--cyan);
            color: var(--cyan);
            transform: translateY(-2px);
        }

        .q-action-dekor {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: var(--sp-2);
            opacity: 0.4;
        }

        /* ── FEEDBACK SECTION ────────────────────────── */
        .feedback-section {
            display: none;
            padding: var(--sp-5);
            background: rgba(9, 19, 38, 0.6);
            border: 2px solid;
            margin-bottom: var(--sp-5);
        }

        .feedback-section.show {
            display: block;
        }

        .feedback-section.success {
            border-color: var(--green);
        }

        .feedback-section.error {
            border-color: #FF6B6B;
        }

        .feedback-icon-row {
            display: flex;
            align-items: flex-start;
            gap: var(--sp-3);
        }

        .feedback-icon-slot {
            flex-shrink: 0;
        }

        .feedback-text-area {
            flex: 1;
        }

        .feedback-text-area h3 {
            font-family: var(--ff-pixel);
            font-size: 0.85rem;
            margin-bottom: var(--sp-2);
        }

        .feedback-section.success h3 {
            color: var(--green);
        }

        .feedback-section.error h3 {
            color: #FF6B6B;
        }

        .feedback-text-area p {
            color: var(--muted);
            line-height: 1.7;
        }

        .xp-earned {
            display: inline-flex;
            align-items: center;
            gap: var(--sp-2);
            margin-top: var(--sp-3);
            padding: var(--sp-2) var(--sp-3);
            background: rgba(255, 223, 32, 0.1);
            border: 1px solid var(--yellow);
            font-family: var(--ff-pixel);
            font-size: 0.6rem;
            color: var(--yellow);
        }

        /* ── RESPONSIVE ──────────────────────────────── */
        @media (max-width: 1024px) {
            .workspace {
                grid-template-columns: 1fr;
                min-height: auto;
            }

            .editor-panel,
            .preview-panel {
                min-height: 400px;
            }
        }

        @media (max-width: 768px) {
            .question-container {
                padding: 96px 16px 40px;
            }

            .lp-nav-inner {
                padding: 0 16px;
            }

            .question-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .instruction-section {
                flex-direction: column;
            }

            .q-action-dekor {
                display: none;
            }
        }

        /* Loading State */
        .loading-overlay {
            position: absolute;
            inset: 0;
            background: rgba(4, 13, 26, 0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
        }

        .loading-text {
            font-family: var(--ff-pixel);
            font-size: 0.7rem;
            color: var(--cyan);
            animation: pulse 1.5s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        /* ── CHARACTER COMPANION ──────────────────────── */
        .character-companion {
            position: fixed;
            bottom: 24px;
            left: 5%;  /* Start from left side */
            width: 180px;
            height: 180px;
            z-index: 999;
            pointer-events: none;
            transition: transform 0.3s ease;
            /* Pacing animation - walk left to right (SLOWER) */
            animation: companion-pacing 30s linear infinite;
        }

        /* Stop pacing when doing actions */
        .character-companion.typing,
        .character-companion.thinking,
        .character-companion.success,
        .character-companion.error {
            animation-play-state: paused !important;
        }

        .character-companion img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            image-rendering: pixelated;
            filter: drop-shadow(0 4px 12px rgba(0, 0, 0, 0.5));
            transition: transform 0.5s ease;
        }

        /* Flip character when walking left */
        .character-companion.walking-left img {
            transform: scaleX(-1);
        }

        /* Default idle state = walking (no extra animation, just GIF) */
        .character-companion.idle img {
            /* Walk.gif plays naturally */
        }

        .character-companion.typing img {
            animation: companion-bounce 0.5s ease-in-out infinite;
        }

        .character-companion.thinking img {
            animation: companion-sway 2s ease-in-out infinite;
        }

        .character-companion.success img {
            animation: companion-celebrate 0.6s ease-in-out 3;
        }

        .character-companion.error img {
            animation: companion-shake 0.4s ease-in-out 2;
        }

        /* Pacing animation - horizontal movement */
        @keyframes companion-pacing {
            0% {
                left: 5%;
            }
            50% {
                left: calc(90% - 180px);  /* Smooth transition to right */
            }
            100% {
                left: 5%;  /* Smooth transition back to left */
            }
        }

        @keyframes companion-bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-12px); }
        }

        @keyframes companion-sway {
            0%, 100% { transform: rotate(0deg); }
            25% { transform: rotate(-5deg); }
            75% { transform: rotate(5deg); }
        }

        @keyframes companion-celebrate {
            0%, 100% { transform: scale(1) rotate(0deg); }
            25% { transform: scale(1.2) rotate(-15deg); }
            75% { transform: scale(1.2) rotate(15deg); }
        }

        @keyframes companion-shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }

        @media (max-width: 768px) {
            .character-companion {
                width: 110px;
                height: 110px;
                bottom: 16px;
                animation: companion-pacing-mobile 24s linear infinite;
            }
            
            @keyframes companion-pacing-mobile {
                0% {
                    left: 5%;
                }
                50% {
                    left: calc(85% - 110px);
                }
                100% {
                    left: 5%;
                }
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
                </div>
            </div>
        </nav>

        <!-- Character Companion -->
        @php
            $activeCostume = auth()->user()->getActiveCostume();
        @endphp
        @if($activeCostume && $activeCostume->sprite_idle)
        <div class="character-companion idle" id="characterCompanion">
            <img id="companionSprite" 
                 src="{{ asset($activeCostume->sprite_typing) }}"
                 alt="Character Companion"
                 data-idle="{{ asset($activeCostume->sprite_typing) }}"
                 data-typing="{{ asset($activeCostume->sprite_thinking) }}"
                 data-thinking="{{ asset($activeCostume->sprite_thinking) }}"
                 data-success="{{ asset($activeCostume->sprite_success) }}"
                 data-error="{{ asset($activeCostume->sprite_error) }}">
        </div>
        <script>
            console.log('🎭 Active Costume:', {
                name: '{{ $activeCostume->name ?? "N/A" }}',
                walking: '{{ $activeCostume->sprite_typing ?? "N/A" }}',  // Walk.gif
                typing: '{{ $activeCostume->sprite_thinking ?? "N/A" }}',  // Attack.gif
                thinking: '{{ $activeCostume->sprite_thinking ?? "N/A" }}',
                success: '{{ $activeCostume->sprite_success ?? "N/A" }}',
                error: '{{ $activeCostume->sprite_error ?? "N/A" }}'
            });
        </script>
        @else
        <script>
            console.log('⚠️ No active costume found');
        </script>
        @endif

        <!-- Question Container -->
        <main class="question-container">
            
            <!-- Breadcrumb -->
            <div class="breadcrumb">
                <a href="{{ route('modules.index') }}">← Materi</a>
                <span class="separator">/</span>
                <a href="{{ route('materials.show', $question->material_id) }}">{{ $question->material->title }}</a>
                <span class="separator">/</span>
                <span class="current">Soal {{ $question->order }}</span>
            </div>

            <!-- Header -->
            <div class="question-header">
                <div class="question-title-group">
                    <div class="q-header-icon" data-pxl-icon="lightning" data-pxl-scale="3"></div>
                    <div>
                        <h1>{{ $question->title }}</h1>
                        <p class="question-meta">Soal {{ $question->order }} dari {{ $totalQuestions }}</p>
                    </div>
                </div>
                
                <!-- Progress Dots -->
                <div class="q-dots">
                    @foreach($progressDots as $dot)
                        <div class="q-dot {{ $dot['status'] }}" 
                             @if($dot['url']) onclick="window.location.href='{{ $dot['url'] }}'" @endif
                             title="Soal {{ $dot['order'] }}"></div>
                    @endforeach
                </div>
            </div>

            <!-- Alert -->
            <div id="alert" class="alert"></div>

            <!-- Instruction -->
            <div class="instruction-section">
                <div class="instruction-text">
                    <h2>Instruksi</h2>
                    <p>{{ $question->instruction }}</p>
                </div>
                <div class="instruction-icon-slot" data-pxl-icon="star" data-pxl-scale="4"></div>
            </div>

            <!-- Workspace -->
            <div class="workspace">
                
                <!-- Editor Panel -->
                <div class="editor-panel">
                    <div class="panel-title">
                        <div class="panel-title-row">
                            <span class="panel-title-icon-slot" data-pxl-icon="play" data-pxl-scale="2"></span>
                            <span>Editor Kode</span>
                        </div>
                        <span class="type-label">{{ strtoupper($question->question_type) }}</span>
                    </div>
                    <div class="monaco-container">
                        <div id="editor"></div>
                        <div class="loading-overlay" id="editorLoading">
                            <div class="loading-text">Loading Editor...</div>
                        </div>
                    </div>
                </div>

                <!-- Preview Panel -->
                <div class="preview-panel">
                    <div class="panel-title">
                        <div class="panel-title-row">
                            <span class="panel-title-icon-slot" data-pxl-icon="glow" data-pxl-scale="2"></span>
                            <span>Live Preview</span>
                        </div>
                        <span class="type-label">OUTPUT</span>
                    </div>
                    <iframe class="preview-frame" id="previewFrame" sandbox="allow-scripts allow-same-origin" title="Preview"></iframe>
                    
                    <!-- Console Panel (only for JS) -->
                    @if($question->question_type === 'javascript')
                    <div class="console-panel" id="consolePanel">
                        <div class="console-line console-log">Console output akan muncul di sini...</div>
                    </div>
                    @endif
                </div>

            </div>

            <!-- Action Bar -->
            <div class="question-actions">
                <button class="btn btn-primary" id="checkBtn" onclick="checkAnswer()">
                    ✓ Periksa Jawaban
                </button>
                <a href="{{ route('materials.show', $question->material_id) }}" class="btn btn-secondary">
                    ← Kembali ke Level
                </a>

                <!-- Dekor Icons -->
                <div class="q-action-dekor">
                    <span data-pxl-icon="target" data-pxl-scale="3"></span>
                    <span data-pxl-icon="check" data-pxl-scale="3"></span>
                </div>
            </div>

            <!-- Feedback Section -->
            <div id="feedbackSection" class="feedback-section"></div>

        </main>

    </div>

    <!-- Monaco Editor -->
    <script src="https://cdn.jsdelivr.net/npm/monaco-editor@0.50.0/min/vs/loader.js"></script>
    <script src="{{ asset('js/pxlkit-renderer.js') }}"></script>
    <script src="{{ asset('js/scene3d.js') }}"></script>
    
    <script>
        // Question Data
        const QUESTION_DATA = {
            id: {{ $question->id }},
            type: '{{ $question->question_type }}',
            starterCode: @json($question->starter_code),
            currentCode: @json($savedCode ?? $question->starter_code),
        };

        // PxlKit Icon Map
        const PXL_MAP = {
            lightning: () => typeof PXL_LIGHTNING !== 'undefined' ? PXL_LIGHTNING : null,
            star: () => typeof PXL_SPARKLE_STAR !== 'undefined' ? PXL_SPARKLE_STAR : null,
            play: () => typeof PXL_PLAY !== 'undefined' ? PXL_PLAY : null,
            glow: () => typeof PXL_GLOW !== 'undefined' ? PXL_GLOW : null,
            target: () => typeof PXL_TARGET !== 'undefined' ? PXL_TARGET : null,
            check: () => typeof PXL_CHECK !== 'undefined' ? PXL_CHECK : null,
            coin: () => typeof PXL_COIN !== 'undefined' ? PXL_COIN : null,
            alert: () => typeof PXL_ALERT !== 'undefined' ? PXL_ALERT : null,
            cross: () => typeof PXL_CROSS !== 'undefined' ? PXL_CROSS : null,
            document: () => typeof PXL_DOCUMENT !== 'undefined' ? PXL_DOCUMENT : null,
        };

        // Inject PxlKit Icons
        function injectPxlIcons() {
            if (typeof PXLKIT === 'undefined') {
                setTimeout(injectPxlIcons, 100);
                return;
            }

            document.querySelectorAll('[data-pxl-icon]:not([data-pxl-done])').forEach(el => {
                const name = el.dataset.pxlIcon;
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

        // Monaco Editor Setup
        let editor = null;
        let updateTimer = null;
        let autosaveTimer = null;

        require.config({ 
            paths: { 
                'vs': 'https://cdn.jsdelivr.net/npm/monaco-editor@0.50.0/min/vs' 
            } 
        });

        require(['vs/editor/editor.main'], function() {
            // Define theme
            monaco.editor.defineTheme('codetide-dark', {
                base: 'vs-dark',
                inherit: true,
                rules: [
                    { token: 'comment', foreground: '6A9955', fontStyle: 'italic' },
                    { token: 'keyword', foreground: '569CD6', fontStyle: 'bold' },
                    { token: 'string', foreground: 'CE9178' },
                    { token: 'tag', foreground: '569CD6' },
                    { token: 'attribute.name', foreground: '9CDCFE' },
                ],
                colors: {
                    'editor.background': '#0B1622',
                    'editor.foreground': '#E8F4FF',
                    'editorCursor.foreground': '#53EAFD',
                    'editorLineNumber.activeForeground': '#53EAFD',
                }
            });

            // Initialize editor immediately
            initEditor();
        });

        function initEditor() {
            // Hide loading overlay
            document.getElementById('editorLoading').style.display = 'none';

            // Create Monaco editor directly
            editor = monaco.editor.create(document.getElementById('editor'), {
                value: QUESTION_DATA.currentCode,
                language: QUESTION_DATA.type,
                theme: 'codetide-dark',
                automaticLayout: true,
                fontSize: 14,
                lineNumbers: 'on',
                minimap: { enabled: false },
                scrollBeyondLastLine: false,
                wordWrap: 'on',
                tabSize: 2,
                insertSpaces: true,
                padding: { top: 16, bottom: 16 },
                quickSuggestions: true,
                suggestOnTriggerCharacters: true,
            });

            // Setup onChange with debounce
            let debounceTimer;
            editor.onDidChangeModelContent(() => {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    handleCodeChange(editor.getValue());
                }, 500);
            });

            // Initial preview update
            updatePreview(QUESTION_DATA.currentCode);
        }

        // Handle code change with debounce
        function handleCodeChange(code) {
            // Clear previous timers
            clearTimeout(updateTimer);
            clearTimeout(autosaveTimer);

            // Debounced preview update (500ms)
            updateTimer = setTimeout(() => {
                updatePreview(code);
            }, 500);

            // Debounced autosave (30 seconds)
            autosaveTimer = setTimeout(() => {
                autosaveCode(code);
            }, 30000);
        }

        // Update preview iframe
        function updatePreview(code) {
            const iframe = document.getElementById('previewFrame');
            const consolePanel = document.getElementById('consolePanel');
            
            if (QUESTION_DATA.type === 'html') {
                iframe.srcdoc = code;
            } 
            else if (QUESTION_DATA.type === 'css') {
                // Check if code contains full HTML document
                if (code.includes('<!DOCTYPE') || code.includes('<html')) {
                    // It's a full HTML document with embedded CSS
                    iframe.srcdoc = code;
                } else {
                    // It's pure CSS, wrap it in template
                    iframe.srcdoc = `
                        <!DOCTYPE html>
                        <html>
                        <head>
                            <style>${code}</style>
                        </head>
                        <body>
                            <div style="padding: 20px; font-family: system-ui;">
                                <h1>Heading 1</h1>
                                <h2>Heading 2</h2>
                                <p>Paragraf teks untuk melihat style CSS yang diterapkan.</p>
                                <button>Button</button>
                                <div class="box" style="margin-top: 20px; padding: 20px; background: #f0f0f0;">
                                    Box element untuk testing
                                </div>
                            </div>
                        </body>
                        </html>
                    `;
                }
            }
            else if (QUESTION_DATA.type === 'javascript') {
                // Clear console
                if (consolePanel) {
                    consolePanel.innerHTML = '';
                }

                // Check if code contains full HTML document
                if (code.includes('<!DOCTYPE') || code.includes('<html>') || code.includes('<html ')) {
                    // It's a full HTML document with embedded JavaScript
                    iframe.srcdoc = code;
                } else {
                    // It's pure JavaScript, wrap it in template with console capture
                    const htmlContent = `
                        <!DOCTYPE html>
                        <html>
                        <head>
                            <style>
                                body { 
                                    padding: 20px; 
                                    font-family: system-ui; 
                                }
                            </style>
                        </head>
                        <body>
                            <div id="output"></div>
                            <script>
                                // Capture console
                                (function() {
                                    const originalLog = console.log;
                                    const originalError = console.error;
                                    const originalWarn = console.warn;
                                    
                                    console.log = function(...args) {
                                        window.parent.postMessage({
                                            type: 'console',
                                            level: 'log',
                                            message: args.map(a => String(a)).join(' ')
                                        }, '*');
                                        originalLog.apply(console, args);
                                    };
                                    
                                    console.error = function(...args) {
                                        window.parent.postMessage({
                                            type: 'console',
                                            level: 'error',
                                            message: args.map(a => String(a)).join(' ')
                                        }, '*');
                                        originalError.apply(console, args);
                                    };
                                    
                                    console.warn = function(...args) {
                                        window.parent.postMessage({
                                            type: 'console',
                                            level: 'warn',
                                            message: args.map(a => String(a)).join(' ')
                                        }, '*');
                                        originalWarn.apply(console, args);
                                    };

                                    window.onerror = function(msg, url, line, col, error) {
                                        console.error('Error: ' + msg);
                                        return false;
                                    };
                                })();
                            <\/script>
                            <script>
                                try {
                                    ${code}
                                } catch (e) {
                                    console.error(e.message);
                                }
                            <\/script>
                        </body>
                        </html>
                    `;
                    
                    iframe.srcdoc = htmlContent;
                }
            }
        }

        // Listen for console messages from iframe
        window.addEventListener('message', (event) => {
            if (event.data.type === 'console') {
                const consolePanel = document.getElementById('consolePanel');
                if (!consolePanel) return;

                const line = document.createElement('div');
                line.className = `console-line console-${event.data.level}`;
                line.textContent = event.data.message;
                consolePanel.appendChild(line);
                consolePanel.scrollTop = consolePanel.scrollHeight;
            }
        });

        // Autosave code
        function autosaveCode(code) {
            // Show saving indicator
            AutoSave.saving();
            
            fetch('{{ route('questions.autosave', $question->id) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ code: code })
            })
            .then(response => response.json())
            .then(data => {
                console.log('Autosaved');
                AutoSave.saved();
            })
            .catch(error => {
                console.error('Autosave failed:', error);
                AutoSave.error();
            });
        }

        // Check answer
        function checkAnswer() {
            const code = editor ? editor.getValue() : '';
            const checkBtn = document.getElementById('checkBtn');
            
            // Show loading state
            Loading.button(checkBtn, true);

            fetch('{{ route('questions.check', $question->id) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ code: code })
            })
            .then(response => response.json())
            .then(data => {
                Loading.button(checkBtn, false);
                showFeedback(data);
                
                // Show toast notification
                if (data.status === 'correct') {
                    Toast.success(
                        'KODE BENAR!',
                        data.xp_earned ? `+${data.xp_earned} XP Earned!` : data.message
                    );
                } else if (data.status === 'incorrect') {
                    Toast.error(
                        'BELUM TEPAT',
                        data.message || 'Coba periksa kode kamu lagi'
                    );
                } else {
                    Toast.warning(
                        'PERINGATAN',
                        data.message || 'Silakan lengkapi kode terlebih dahulu'
                    );
                }
            })
            .catch(error => {
                Loading.button(checkBtn, false);
                console.error('Check failed:', error);
                
                Toast.error(
                    'ERROR',
                    'Terjadi kesalahan saat memeriksa jawaban'
                );
                
                showAlert('Terjadi kesalahan saat memeriksa jawaban.', 'error');
            });
        }

        // Show feedback
        function showFeedback(data) {
            const section = document.getElementById('feedbackSection');
            section.className = 'feedback-section show';
            
            if (data.status === 'correct') {
                section.classList.add('success');
                
                // Build next action buttons
                let nextActionHtml = '';
                if (data.next_question) {
                    nextActionHtml = `
                        <div style="margin-top: var(--sp-4); display: flex; gap: var(--sp-3); flex-wrap: wrap;">
                            <a href="${data.next_question.url}" class="btn btn-primary" style="text-decoration: none;">
                                ➜ Lanjut ke Soal Berikutnya
                            </a>
                            <a href="{{ route('materials.show', $question->material_id) }}" class="btn btn-secondary" style="text-decoration: none;">
                                ← Kembali ke Level
                            </a>
                        </div>
                    `;
                } else if (data.back_to_material) {
                    nextActionHtml = `
                        <p style="margin-top: var(--sp-3); color: var(--text);">${data.back_to_material.message}</p>
                        <div style="margin-top: var(--sp-4);">
                            <a href="${data.back_to_material.url}" class="btn btn-primary" style="text-decoration: none;">
                                ✓ Kembali ke Level
                            </a>
                        </div>
                    `;
                }
                
                section.innerHTML = `
                    <div class="feedback-icon-row">
                        <div class="feedback-icon-slot"></div>
                        <div class="feedback-text-area">
                            <h3 style="display: flex; align-items: center; gap: var(--sp-2);">
                                <span class="success-icon-inline" data-pxl-icon="check" data-pxl-scale="2"></span>
                                <span>Kode Benar!</span>
                            </h3>
                            <p>${data.message}</p>
                            <div class="xp-earned">
                                <span data-pxl-icon="coin" data-pxl-scale="1.5"></span>
                                <span>+${data.xp_earned} XP</span>
                            </div>
                            ${nextActionHtml}
                        </div>
                    </div>
                `;

                // Inject main floating icon
                const iconSlot = section.querySelector('.feedback-icon-slot');
                if (typeof PXL_SPARKLE_STAR !== 'undefined' && typeof PXLKIT !== 'undefined') {
                    const canvas = PXLKIT.render(PXL_SPARKLE_STAR, 4);
                    canvas.style.animation = 'float 2s ease-in-out infinite';
                    iconSlot.appendChild(canvas);
                }

                injectPxlIcons();
            } 
            else {
                section.classList.add('error');

                let titleIcon = 'alert';
                let title = '';
                if (data.status === 'incomplete') {
                    titleIcon = 'document';
                    title = 'Lengkapi Kode';
                } else if (data.status === 'incorrect') {
                    titleIcon = 'cross';
                    title = 'Perbaiki Kode';
                }
                
                section.innerHTML = `
                    <div class="feedback-icon-row">
                        <div class="feedback-icon-slot"></div>
                        <div class="feedback-text-area">
                            <h3 style="display: flex; align-items: center; gap: var(--sp-2);">
                                <span class="error-icon-inline" data-pxl-icon="${titleIcon}" data-pxl-scale="2"></span>
                                <span>${title}</span>
                            </h3>
                            <p>${data.message}</p>
                        </div>
                    </div>
                `;

                // Inject main floating icon
                const iconSlot = section.querySelector('.feedback-icon-slot');
                if (typeof PXL_BUG !== 'undefined' && typeof PXLKIT !== 'undefined') {
                    const canvas = PXLKIT.render(PXL_BUG, 4);
                    canvas.style.animation = 'float 2s ease-in-out infinite';
                    iconSlot.appendChild(canvas);
                }

                injectPxlIcons();
            }
        }

        // Show alert
        function showAlert(message, type = 'info') {
            const alert = document.getElementById('alert');
            alert.className = `alert alert-${type} show`;
            alert.textContent = message;
            
            setTimeout(() => {
                alert.classList.remove('show');
            }, 5000);
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            injectPxlIcons();
            initCharacterCompanion();
        });

        // ═══════════════════════════════════════════════════
        // CHARACTER COMPANION ANIMATION CONTROLLER
        // ═══════════════════════════════════════════════════
        
        let typingTimeout;
        let thinkingTimeout;
        const companion = document.getElementById('characterCompanion');
        const companionSprite = document.getElementById('companionSprite');

        function initCharacterCompanion() {
            if (!companion || !companionSprite) {
                console.log('❌ Companion elements not found');
                return;
            }

            console.log('✅ Companion initialized');

            // Set initial idle state
            setCompanionState('idle');

            // Flip character only at screen edges (not middle)
            // RIGHT side (0-85%) = face RIGHT, LEFT side (85-100%) = face LEFT
            const flipInterval = setInterval(() => {
                const rect = companion.getBoundingClientRect();
                const viewportWidth = window.innerWidth;
                const characterPosition = rect.left;
                const characterCenter = characterPosition + (rect.width / 2);
                
                // Only flip when character reaches far right (>80% of screen)
                // This prevents walking backwards in the middle
                if (characterCenter > viewportWidth * 0.80) {
                    // At right edge - face left for return journey
                    companion.classList.add('walking-left');
                } else if (characterCenter < viewportWidth * 0.20) {
                    // At left edge - face right for forward journey
                    companion.classList.remove('walking-left');
                }
                // Between 20-80%: keep current direction (no flip)
            }, 100);

            // Wait for editor to be ready
            const checkEditor = setInterval(() => {
                if (typeof editor !== 'undefined' && editor) {
                    console.log('✅ Editor found, attaching listener');
                    clearInterval(checkEditor);
                    
                    editor.onDidChangeModelContent(() => {
                        onUserTyping();
                    });
                }
            }, 100);

            // Stop checking after 5 seconds
            setTimeout(() => clearInterval(checkEditor), 5000);
        }

        function setCompanionState(state) {
            if (!companion || !companionSprite) return;
            
            console.log('🎮 Companion state:', state);
            
            // Remove all state classes from companion container
            companion.classList.remove('idle', 'typing', 'thinking', 'success', 'error');
            
            // Add new state to container
            companion.classList.add(state);

            // Switch animated GIF source
            const spriteUrl = companionSprite.getAttribute('data-' + state);
            if (spriteUrl) {
                console.log('🖼️ Loading sprite:', spriteUrl);
                companionSprite.src = spriteUrl;
            } else {
                console.log('⚠️ No sprite URL for state:', state);
            }
        }

        function onUserTyping() {
            console.log('⌨️ User typing detected');
            
            // User is typing - show typing animation
            setCompanionState('typing');

            // Clear existing timeouts
            clearTimeout(typingTimeout);
            clearTimeout(thinkingTimeout);

            // After 500ms of no typing, go to thinking state
            typingTimeout = setTimeout(() => {
                setCompanionState('thinking');

                // After 1 second of thinking, go back to idle
                thinkingTimeout = setTimeout(() => {
                    setCompanionState('idle');
                }, 1000);
            }, 500);
        }

        // Override showFeedback to include character reactions
        const originalShowFeedback = showFeedback;
        showFeedback = function(data) {
            // Call original function
            originalShowFeedback(data);

            console.log('📝 Feedback received:', data);

            // Character reaction
            if (!companion || !companionSprite) {
                console.log('❌ Companion not available for reaction');
                return;
            }

            if (data.status === 'correct') {
                console.log('✅ Correct answer - celebrating!');
                setCompanionState('success');
                // Back to idle after animation
                setTimeout(() => {
                    setCompanionState('idle');
                }, 2000);
            } else {
                console.log('❌ Wrong answer - showing error');
                setCompanionState('error');
                // Back to idle after animation
                setTimeout(() => {
                    setCompanionState('idle');
                }, 1500);
            }
        };
    </script>
    
    <!-- Toast & Loading System Scripts -->
    <script src="{{ asset('js/pxlkit-renderer.js') }}"></script>
    <script src="{{ asset('js/toast.js') }}"></script>
    <script src="{{ asset('js/loading.js') }}"></script>
</body>
</html>
