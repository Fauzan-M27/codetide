<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — CodeTide</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/toast.css') }}">
    <link rel="stylesheet" href="{{ asset('css/loading.css') }}">
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
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow-x: hidden; /* Only hide horizontal scroll */
            overflow-y: auto; /* Allow vertical scroll */
            padding: 40px 20px; /* Add padding for small screens */
        }

        .auth-container {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 480px;
            padding: 20px;
            animation: fadeInUp 0.8s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .auth-card {
            background: rgba(9, 19, 38, 0.8);
            border: 1px solid var(--border);
            backdrop-filter: blur(12px);
            padding: 40px;
            position: relative;
            box-shadow: 
                8px 8px 0 rgba(83, 234, 253, 0.1),
                0 0 40px rgba(83, 234, 253, 0.05);
        }

        .auth-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--purple), var(--cyan), var(--yellow));
        }

        .auth-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .auth-logo {
            font-family: var(--ff-pixel);
            font-size: 0.9rem;
            color: var(--purple);
            text-shadow: 0 0 16px rgba(237, 106, 255, 0.5);
            margin-bottom: 8px;
        }

        .auth-logo .bracket {
            color: var(--cyan);
        }

        .auth-title {
            font-family: var(--ff-pixel);
            font-size: 0.7rem;
            color: var(--cyan);
            margin-top: 24px;
            margin-bottom: 8px;
            letter-spacing: 0.05em;
        }

        .auth-subtitle {
            font-size: 0.8rem;
            color: var(--muted);
            line-height: 1.6;
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
            letter-spacing: 0.05em;
        }

        .form-input {
            width: 100%;
            padding: 12px 16px;
            background: rgba(4, 13, 26, 0.6);
            border: 1px solid var(--border);
            color: var(--text);
            font-family: var(--ff-code);
            font-size: 0.9rem;
            transition: all 0.3s;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--cyan);
            box-shadow: 0 0 0 2px rgba(83, 234, 253, 0.1);
            background: rgba(4, 13, 26, 0.8);
        }

        .form-input::placeholder {
            color: var(--muted);
        }

        .form-checkbox {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin: 20px 0;
        }

        .form-checkbox input[type="checkbox"] {
            margin-top: 4px;
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: var(--cyan);
        }

        .form-checkbox label {
            font-size: 0.85rem;
            color: var(--muted);
            line-height: 1.6;
            cursor: pointer;
        }

        .form-error {
            color: var(--red);
            font-size: 0.75rem;
            margin-top: 6px;
            font-family: var(--ff-code);
        }

        .form-hint {
            color: var(--muted);
            font-size: 0.75rem;
            margin-top: 6px;
            line-height: 1.5;
        }

        .btn {
            font-family: var(--ff-pixel);
            font-size: 0.5rem;
            padding: 14px 24px;
            border: 2px solid;
            letter-spacing: 0.05em;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-primary {
            background: var(--yellow);
            color: #000;
            border-color: var(--yellow);
            box-shadow: 4px 4px 0 rgba(0, 0, 0, 0.4);
            width: 100%;
        }

        .btn-primary:hover:not(:disabled) {
            transform: translate(-2px, -2px);
            box-shadow: 6px 6px 0 rgba(0, 0, 0, 0.4);
            filter: brightness(1.1);
        }

        .btn-primary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .auth-footer {
            margin-top: 24px;
            text-align: center;
        }

        .auth-link {
            color: var(--cyan);
            text-decoration: none;
            font-size: 0.85rem;
            transition: color 0.3s;
        }

        .auth-link:hover {
            color: var(--yellow);
            text-decoration: underline;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: var(--muted);
            text-decoration: none;
            font-size: 0.85rem;
            transition: color 0.3s;
        }

        .back-link:hover {
            color: var(--cyan);
        }

        .alert {
            padding: 12px 16px;
            border: 1px solid;
            margin-bottom: 20px;
            font-size: 0.85rem;
            line-height: 1.6;
        }

        .alert-success {
            background: rgba(123, 241, 168, 0.1);
            border-color: var(--green);
            color: var(--green);
        }

        .alert-error {
            background: rgba(255, 107, 107, 0.1);
            border-color: var(--red);
            color: var(--red);
        }

        /* Particles background */
        .particles {
            position: fixed;
            inset: 0;
            z-index: 0;
            overflow: hidden;
        }

        .particle {
            position: absolute;
            width: 2px;
            height: 2px;
            background: var(--cyan);
            border-radius: 50%;
            opacity: 0.3;
            animation: float 10s infinite;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0) translateX(0);
            }
            25% {
                transform: translateY(-100px) translateX(50px);
            }
            50% {
                transform: translateY(-200px) translateX(-50px);
            }
            75% {
                transform: translateY(-100px) translateX(50px);
            }
        }
    </style>
</head>
<body>
    <!-- Floating particles background -->
    <div class="particles" id="particles"></div>

    <div class="auth-container">
        <a href="{{ route('home') }}" class="back-link">← Kembali ke Home</a>
        
        <div class="auth-card">
            <div class="auth-header">
                <div class="auth-logo">
                    <span class="bracket">&lt;</span>Code<span style="color:var(--cyan)">/</span>Tide<span class="bracket">&gt;</span>
                </div>
                <h1 class="auth-title">LOGIN PLAYER</h1>
                <p class="auth-subtitle">Masuk untuk melanjutkan quest</p>
            </div>

            <!-- Session Status -->
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Nickname -->
                <div class="form-group">
                    <label for="nickname" class="form-label">NICKNAME</label>
                    <input 
                        id="nickname" 
                        class="form-input" 
                        type="text" 
                        name="nickname" 
                        value="{{ old('nickname') }}" 
                        required 
                        autofocus 
                        autocomplete="nickname"
                        placeholder="Masukkan nickname"
                    />
                    @error('nickname')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password" class="form-label">PASSWORD</label>
                    <input 
                        id="password" 
                        class="form-input" 
                        type="password" 
                        name="password" 
                        required 
                        autocomplete="current-password"
                        placeholder="Masukkan password"
                    />
                    @error('password')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="form-checkbox">
                    <input id="remember_me" type="checkbox" name="remember">
                    <label for="remember_me">Ingat saya</label>
                </div>

                <!-- Forgot Password Link -->
                <div style="text-align: right; margin-bottom: 16px;">
                    <a href="{{ route('password.request') }}" class="auth-link" style="font-size: 0.75rem;">
                        Lupa password?
                    </a>
                </div>

                <button type="submit" class="btn btn-primary">
                    MASUK →
                </button>

                <div class="auth-footer">
                    <p style="color: var(--muted); font-size: 0.85rem; margin-bottom: 8px;">
                        Belum punya akun?
                    </p>
                    <a href="{{ route('register') }}" class="auth-link">
                        Daftar sebagai player baru
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/pxlkit-renderer.js') }}"></script>
    <script src="{{ asset('js/scene3d.js') }}"></script>
    <script src="{{ asset('js/toast.js') }}"></script>
    <script src="{{ asset('js/loading.js') }}"></script>
    <script>
        // Create floating particles
        const particlesContainer = document.getElementById('particles');
        const colors = ['#53EAFD', '#ED6AFF', '#FFDF20', '#7BF1A8'];
        
        for (let i = 0; i < 30; i++) {
            const particle = document.createElement('div');
            particle.className = 'particle';
            particle.style.left = Math.random() * 100 + '%';
            particle.style.top = Math.random() * 100 + '%';
            particle.style.background = colors[Math.floor(Math.random() * colors.length)];
            particle.style.animationDelay = Math.random() * 10 + 's';
            particle.style.animationDuration = (Math.random() * 10 + 10) + 's';
            particlesContainer.appendChild(particle);
        }
        
        // Handle login form with loading button
        const loginForm = document.querySelector('form');
        const loginBtn = loginForm.querySelector('.btn-primary');
        
        loginForm.addEventListener('submit', function(e) {
            // Show button loading
            Loading.button(loginBtn, true, 'MASUK...');
        });
        
        // Show error toast if validation fails
        @if ($errors->any())
            setTimeout(() => {
                Toast.error('Login Gagal', '{{ $errors->first() }}');
            }, 300);
        @endif
        
        // Show success toast if there's a status message
        @if (session('status'))
            setTimeout(() => {
                Toast.success('Info', '{{ session('status') }}');
            }, 300);
        @endif
    </script>
</body>
</html>
