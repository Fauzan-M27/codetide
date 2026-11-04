<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toast & Loading Demo — CodeTide</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    
    <!-- Toast & Loading System -->
    <link rel="stylesheet" href="{{ asset('css/toast.css') }}">
    <link rel="stylesheet" href="{{ asset('css/loading.css') }}">
    
    <style>
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

        body {
            background: var(--bg);
            color: var(--text);
            font-family: var(--ff-code);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        .demo-container {
            max-width: 800px;
            width: 100%;
        }

        h1 {
            font-family: var(--ff-pixel);
            font-size: 1.2rem;
            color: var(--cyan);
            margin-bottom: 40px;
            text-align: center;
            text-shadow: 0 0 20px rgba(83, 234, 253, 0.5);
        }

        .demo-section {
            background: rgba(9, 19, 38, 0.8);
            border: 1px solid var(--border);
            padding: 32px;
            margin-bottom: 24px;
        }

        .demo-section h2 {
            font-family: var(--ff-pixel);
            font-size: 0.6rem;
            color: var(--yellow);
            margin-bottom: 20px;
            letter-spacing: 0.05em;
        }

        .demo-section p {
            color: var(--muted);
            font-size: 0.85rem;
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .demo-buttons {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .btn {
            font-family: var(--ff-pixel);
            font-size: 0.5rem;
            padding: 12px 20px;
            border: 2px solid;
            background: transparent;
            color: var(--text);
            cursor: pointer;
            letter-spacing: 0.05em;
            transition: all 0.3s;
        }

        .btn:hover {
            transform: translate(-2px, -2px);
            box-shadow: 4px 4px 0 rgba(83, 234, 253, 0.3);
        }

        .btn:active {
            transform: translate(0, 0);
            box-shadow: 2px 2px 0 rgba(83, 234, 253, 0.3);
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none !important;
            box-shadow: none !important;
        }

        .btn-success {
            border-color: var(--green);
            color: var(--green);
        }

        .btn-error {
            border-color: #FF6B6B;
            color: #FF6B6B;
        }

        .btn-warning {
            border-color: var(--yellow);
            color: var(--yellow);
        }

        .btn-info {
            border-color: var(--cyan);
            color: var(--cyan);
        }

        .btn-primary {
            background: var(--yellow);
            color: #000;
            border-color: var(--yellow);
        }

        code {
            background: rgba(83, 234, 253, 0.1);
            padding: 2px 8px;
            border: 1px solid rgba(83, 234, 253, 0.2);
            color: var(--cyan);
            font-size: 0.8rem;
        }
    </style>
</head>
<body>
    <div class="demo-container">
        <h1>TOAST & LOADING DEMO</h1>

        <!-- Toast Notifications -->
        <div class="demo-section">
            <h2>1. TOAST NOTIFICATIONS WITH PXLKIT ICONS</h2>
            <p>Click buttons below to show different toast types (with pixel art icons):</p>
            <div class="demo-buttons">
                <button class="btn btn-success" onclick="showSuccessToast()">✓ Success Toast</button>
                <button class="btn btn-error" onclick="showErrorToast()">✕ Error Toast</button>
                <button class="btn btn-warning" onclick="showWarningToast()">⚡ Warning Toast</button>
                <button class="btn btn-info" onclick="showInfoToast()">★ Info Toast</button>
            </div>
            <p style="margin-top: 20px; font-size: 0.75rem;">
                <strong>Icons:</strong> Success (Check) • Error (Bug) • Warning (Lightning) • Info (Sparkle Star)<br>
                <strong>API:</strong> <code>Toast.success('Title', 'Message')</code>
            </p>
        </div>

        <!-- Loading Overlay -->
        <div class="demo-section">
            <h2>2. LOADING OVERLAY</h2>
            <p>Full-screen loading with animated spinner:</p>
            <div class="demo-buttons">
                <button class="btn btn-primary" onclick="showLoading()">Show Loading (3s)</button>
                <button class="btn btn-info" onclick="showLoadingCustom()">Custom Loading Text</button>
            </div>
            <p style="margin-top: 20px; font-size: 0.75rem;">
                API: <code>Loading.show('Text')</code> / <code>Loading.hide()</code>
            </p>
        </div>

        <!-- Button Loading State -->
        <div class="demo-section">
            <h2>3. BUTTON LOADING STYLES</h2>
            <p>Multiple loading animation styles:</p>
            
            <div style="margin-bottom: 16px;">
                <p style="font-size: 0.75rem; color: var(--muted); margin-bottom: 8px;">
                    → Square Spinner (Default Pixel Art)
                </p>
                <div class="demo-buttons">
                    <button class="btn btn-primary" id="btnSquare" onclick="demoButtonStyle('btnSquare', '')">
                        ✓ Submit Code
                    </button>
                </div>
            </div>

            <div style="margin-bottom: 16px;">
                <p style="font-size: 0.75rem; color: var(--muted); margin-bottom: 8px;">
                    → Pixel Blocks
                </p>
                <div class="demo-buttons">
                    <button class="btn btn-success" id="btnBlocks" onclick="demoButtonStyle('btnBlocks', 'btn-blocks')">
                        Check Answer
                    </button>
                </div>
            </div>

            <div style="margin-bottom: 16px;">
                <p style="font-size: 0.75rem; color: var(--muted); margin-bottom: 8px;">
                    → Pulse Animation
                </p>
                <div class="demo-buttons">
                    <button class="btn btn-warning" id="btnPulse" onclick="demoButtonStyle('btnPulse', 'btn-pulse')">
                        Save Progress
                    </button>
                </div>
            </div>

            <div>
                <p style="font-size: 0.75rem; color: var(--muted); margin-bottom: 8px;">
                    → Bounce Animation
                </p>
                <div class="demo-buttons">
                    <button class="btn btn-info" id="btnBounce" onclick="demoButtonStyle('btnBounce', 'btn-bounce')">
                        Upload File
                    </button>
                </div>
            </div>

            <p style="margin-top: 20px; font-size: 0.75rem;">
                API: <code>Loading.button(element, true, 'Text', 'style-class')</code>
            </p>
        </div>

        <!-- Autosave Indicator -->
        <div class="demo-section">
            <h2>4. AUTOSAVE INDICATOR</h2>
            <p>Bottom-right corner notification:</p>
            <div class="demo-buttons">
                <button class="btn btn-warning" onclick="AutoSave.saving()">Show Saving</button>
                <button class="btn btn-success" onclick="AutoSave.saved()">Show Saved</button>
                <button class="btn btn-error" onclick="AutoSave.error()">Show Error</button>
            </div>
            <p style="margin-top: 20px; font-size: 0.75rem;">
                API: <code>AutoSave.saving()</code> / <code>AutoSave.saved()</code>
            </p>
        </div>

        <!-- Combined Example -->
        <div class="demo-section">
            <h2>5. COMBINED EXAMPLE</h2>
            <p>Simulate code submission with all feedback states:</p>
            <div class="demo-buttons">
                <button class="btn btn-primary" id="submitBtn" onclick="simulateSubmit()">
                    Submit & Check Code
                </button>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/pxlkit-renderer.js') }}"></script>
    <script src="{{ asset('js/toast.js') }}"></script>
    <script src="{{ asset('js/loading.js') }}"></script>

    <script>
        // Toast Examples
        function showSuccessToast() {
            Toast.success('KODE BENAR!', '+15 XP Earned! Mantap sekali!');
        }

        function showErrorToast() {
            Toast.error('BELUM TEPAT', 'Tag <h1> belum ada. Tambahkan heading.');
        }

        function showWarningToast() {
            Toast.warning('PERINGATAN', 'Silakan tulis kode terlebih dahulu.');
        }

        function showInfoToast() {
            Toast.info('INFO', 'Kamu bisa menggunakan Ctrl+Enter untuk submit.');
        }

        // Loading Examples
        function showLoading() {
            Loading.show('PROCESSING');
            setTimeout(() => {
                Loading.hide();
                Toast.success('SELESAI', 'Loading finished!');
            }, 3000);
        }

        function showLoadingCustom() {
            Loading.show('CHECKING CODE', 'Validating your answer...');
            setTimeout(() => {
                Loading.hide();
                Toast.info('DONE', 'Validation complete!');
            }, 2500);
        }

        // Button Loading Example with Styles
        const buttonLoadingState = {};
        
        function demoButtonStyle(btnId, styleClass = '') {
            const btn = document.getElementById(btnId);
            if (!btn) return;
            
            if (buttonLoadingState[btnId]) return; // Prevent double click
            
            // Start loading with style
            Loading.button(btn, true, null, styleClass);
            buttonLoadingState[btnId] = true;
            
            // Auto stop after 2.5s
            setTimeout(() => {
                Loading.button(btn, false);
                buttonLoadingState[btnId] = false;
                Toast.success('SUCCESS', 'Action completed!');
            }, 2500);
        }

        // Combined Example
        function simulateSubmit() {
            const btn = document.getElementById('submitBtn');
            
            // Step 1: Button loading
            Loading.button(btn, true);
            
            // Step 2: Show autosave
            setTimeout(() => {
                AutoSave.saving();
            }, 500);
            
            // Step 3: Code saved
            setTimeout(() => {
                AutoSave.saved();
            }, 1500);
            
            // Step 4: Show loading overlay
            setTimeout(() => {
                Loading.show('CHECKING CODE');
            }, 2000);
            
            // Step 5: Hide loading and show result
            setTimeout(() => {
                Loading.hide();
                Loading.button(btn, false);
                
                // Random result
                const isCorrect = Math.random() > 0.5;
                
                if (isCorrect) {
                    Toast.success('KODE BENAR!', '+15 XP Earned! Luar biasa!');
                } else {
                    Toast.error('BELUM TEPAT', 'Coba periksa kode kamu lagi.');
                }
            }, 4000);
        }
    </script>
</body>
</html>
