<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Reset Link — Admin CodeTide</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/toast.css') }}">
    <link rel="stylesheet" href="{{ asset('css/mobile-responsive.css') }}">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --cyan: #53EAFD;
            --purple: #ED6AFF;
            --yellow: #FFDF20;
            --green: #7BF1A8;
            --red: #FF6B6B;
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
            padding: 40px 20px;
            overflow-x: hidden; /* Only hide horizontal scroll */
            overflow-y: auto; /* Allow vertical scroll */
        }

        .admin-container {
            max-width: 800px;
            margin: 0 auto;
        }

        .admin-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .admin-logo {
            font-family: var(--ff-pixel);
            font-size: 1rem;
            color: var(--purple);
            text-shadow: 0 0 16px rgba(237, 106, 255, 0.5);
            margin-bottom: 16px;
        }

        .admin-title {
            font-family: var(--ff-pixel);
            font-size: 0.7rem;
            color: var(--cyan);
            margin-bottom: 8px;
        }

        .admin-subtitle {
            color: var(--muted);
            font-size: 0.85rem;
        }

        .admin-card {
            background: rgba(9, 19, 38, 0.8);
            border: 1px solid var(--border);
            padding: 32px;
            margin-bottom: 24px;
        }

        .admin-card h2 {
            font-family: var(--ff-pixel);
            font-size: 0.6rem;
            color: var(--yellow);
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-family: var(--ff-pixel);
            font-size: 0.45rem;
            color: var(--text);
            margin-bottom: 8px;
        }

        .form-input {
            width: 100%;
            padding: 12px 16px;
            background: rgba(4, 13, 26, 0.6);
            border: 1px solid var(--border);
            color: var(--text);
            font-family: var(--ff-code);
            font-size: 0.9rem;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--cyan);
        }

        .btn {
            font-family: var(--ff-pixel);
            font-size: 0.5rem;
            padding: 14px 24px;
            border: 2px solid;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-primary {
            background: var(--yellow);
            color: #000;
            border-color: var(--yellow);
            box-shadow: 4px 4px 0 rgba(0, 0, 0, 0.4);
        }

        .btn-primary:hover {
            transform: translate(-2px, -2px);
            box-shadow: 6px 6px 0 rgba(0, 0, 0, 0.4);
        }

        .result-box {
            background: rgba(123, 241, 168, 0.1);
            border: 2px solid var(--green);
            padding: 24px;
            margin-top: 24px;
        }

        .result-box h3 {
            font-family: var(--ff-pixel);
            font-size: 0.55rem;
            color: var(--green);
            margin-bottom: 16px;
        }

        .result-info {
            margin-bottom: 16px;
            font-size: 0.85rem;
        }

        .result-info strong {
            color: var(--cyan);
        }

        .reset-url-box {
            background: rgba(4, 13, 26, 0.8);
            border: 1px solid var(--cyan);
            padding: 16px;
            margin: 16px 0;
            word-break: break-all;
            font-family: var(--ff-code);
            font-size: 0.75rem;
            color: var(--cyan);
        }

        .btn-copy {
            background: var(--cyan);
            color: #000;
            border-color: var(--cyan);
            box-shadow: 4px 4px 0 rgba(0, 0, 0, 0.4);
            margin-top: 12px;
        }

        .btn-copy:hover {
            transform: translate(-2px, -2px);
            box-shadow: 6px 6px 0 rgba(0, 0, 0, 0.4);
        }

        .alert {
            padding: 12px 16px;
            border: 1px solid;
            margin-bottom: 20px;
            font-size: 0.85rem;
        }

        .alert-error {
            background: rgba(255, 107, 107, 0.1);
            border-color: var(--red);
            color: var(--red);
        }

        .warning-box {
            background: rgba(255, 223, 32, 0.1);
            border: 1px solid var(--yellow);
            padding: 16px;
            margin-bottom: 24px;
            font-size: 0.85rem;
            color: var(--muted);
        }

        .warning-box strong {
            color: var(--yellow);
            display: block;
            margin-bottom: 8px;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="admin-header">
            <div class="admin-logo">
                <span style="color: var(--cyan)">&lt;</span>Code<span style="color:var(--cyan)">/</span>Tide<span style="color: var(--cyan)">&gt;</span>
            </div>
            <h1 class="admin-title">ADMIN PASSWORD RESET</h1>
            <p class="admin-subtitle">Generate password reset link untuk peserta</p>
        </div>

        <div class="warning-box">
            <strong>⚠️ ADMIN TOOL:</strong>
            Tool ini hanya untuk lingkungan development/testing. Di production, gunakan sistem email untuk mengirim link reset secara otomatis.
        </div>

        <div class="admin-card">
            <h2>GENERATE RESET LINK</h2>

            @if ($errors->any())
                <div class="alert alert-error">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.password.generate') }}">
                @csrf

                <div class="form-group">
                    <label for="email" class="form-label">EMAIL</label>
                    <input 
                        id="email" 
                        class="form-input" 
                        type="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required 
                        autofocus
                        placeholder="contoh@email.com"
                    />
                </div>

                <button type="submit" class="btn btn-primary">
                    GENERATE LINK →
                </button>
            </form>

            @if (session('status'))
                <div class="result-box">
                    <h3>✓ LINK BERHASIL DIBUAT</h3>
                    
                    <div class="result-info">
                        <strong>Peserta:</strong> {{ session('nickname') }} ({{ session('email') }})
                    </div>

                    <div class="result-info">
                        <strong>Reset Link:</strong>
                    </div>
                    
                    <div class="reset-url-box" id="resetUrl">{{ session('reset_url') }}</div>

                    <button type="button" class="btn btn-copy" onclick="copyResetUrl()">
                        📋 COPY LINK
                    </button>

                    <div style="margin-top: 20px; font-size: 0.75rem; color: var(--muted);">
                        <strong style="color: var(--yellow);">Petunjuk:</strong><br>
                        1. Copy link di atas<br>
                        2. Kirim link ke peserta melalui komunikasi langsung<br>
                        3. Link valid selama 60 menit<br>
                        4. Peserta dapat langsung klik link dan set password baru
                    </div>
                </div>
            @endif
        </div>

        <div style="text-align: center; margin-top: 40px;">
            <a href="{{ route('login') }}" style="color: var(--muted); text-decoration: none; font-size: 0.85rem;">
                ← Kembali ke Login
            </a>
        </div>
    </div>

    <script src="{{ asset('js/toast.js') }}"></script>
    <script>
        function copyResetUrl() {
            const urlText = document.getElementById('resetUrl').textContent;
            
            // Copy to clipboard
            navigator.clipboard.writeText(urlText).then(() => {
                Toast.success('Berhasil', 'Link reset telah disalin ke clipboard!');
            }).catch(() => {
                // Fallback for older browsers
                const textArea = document.createElement('textarea');
                textArea.value = urlText;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                Toast.success('Berhasil', 'Link reset telah disalin!');
            });
        }

        @if (session('status'))
            setTimeout(() => {
                Toast.success('Berhasil', '{{ session('status') }}');
            }, 300);
        @endif
    </script>
</body>
</html>
