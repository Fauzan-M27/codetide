<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 — Server Error | CodeTide</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
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
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        /* Animated background particles */
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
            background: var(--red);
            border-radius: 50%;
            opacity: 0.3;
            animation: float 10s infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) translateX(0); }
            25% { transform: translateY(-100px) translateX(50px); }
            50% { transform: translateY(-200px) translateX(-50px); }
            75% { transform: translateY(-100px) translateX(50px); }
        }

        /* Error container */
        .error-container {
            position: relative;
            z-index: 10;
            max-width: 700px;
            width: 100%;
            text-align: center;
            animation: fadeInUp 0.8s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Error card */
        .error-card {
            background: rgba(9, 19, 38, 0.9);
            border: 2px solid rgba(255, 107, 107, 0.3);
            padding: 48px 40px;
            position: relative;
            box-shadow: 
                8px 8px 0 rgba(255, 107, 107, 0.15),
                0 0 60px rgba(255, 107, 107, 0.1);
            backdrop-filter: blur(12px);
        }

        .error-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--red), var(--yellow), var(--red));
        }

        /* Error code */
        .error-code {
            font-family: var(--ff-pixel);
            font-size: 4rem;
            color: var(--red);
            text-shadow: 
                0 0 20px rgba(255, 107, 107, 0.6),
                4px 4px 0 rgba(0, 0, 0, 0.3);
            margin-bottom: 24px;
            line-height: 1.2;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.8; transform: scale(1.05); }
        }

        /* Error title */
        .error-title {
            font-family: var(--ff-pixel);
            font-size: 1rem;
            color: var(--yellow);
            margin-bottom: 16px;
            letter-spacing: 0.05em;
            line-height: 1.6;
        }

        /* Error description */
        .error-description {
            color: var(--muted);
            font-size: 0.95rem;
            line-height: 1.8;
            margin-bottom: 36px;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Pixel art illustration */
        .error-illustration {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 40px;
            gap: 16px;
        }

        /* Code snippet box */
        .code-box {
            background: rgba(255, 107, 107, 0.05);
            border: 1px solid rgba(255, 107, 107, 0.2);
            padding: 16px 20px;
            margin: 32px 0;
            text-align: left;
            font-family: var(--ff-code);
            font-size: 0.75rem;
            color: var(--red);
            line-height: 1.6;
        }

        .code-box strong {
            color: var(--yellow);
            font-family: var(--ff-pixel);
            font-size: 0.5rem;
        }

        /* Action buttons */
        .error-actions {
            display: flex;
            gap: 16px;
            justify-content: center;
            flex-wrap: wrap;
            margin-bottom: 32px;
        }

        .btn {
            font-family: var(--ff-pixel);
            font-size: 0.5rem;
            padding: 14px 28px;
            border: 2px solid;
            text-decoration: none;
            letter-spacing: 0.05em;
            transition: all 0.3s cubic-bezier(0.23, 1, 0.32, 1);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
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
            filter: brightness(1.1);
        }

        .btn-secondary {
            background: transparent;
            color: var(--cyan);
            border-color: var(--cyan);
            box-shadow: 4px 4px 0 rgba(83, 234, 253, 0.2);
        }

        .btn-secondary:hover {
            background: rgba(83, 234, 253, 0.1);
            transform: translate(-2px, -2px);
            box-shadow: 6px 6px 0 rgba(83, 234, 253, 0.2);
        }

        /* Quick links */
        .quick-links {
            border-top: 1px solid var(--border);
            padding-top: 28px;
            margin-top: 28px;
        }

        .quick-links-title {
            font-family: var(--ff-pixel);
            font-size: 0.5rem;
            color: var(--muted);
            margin-bottom: 16px;
            letter-spacing: 0.05em;
        }

        .quick-links-list {
            display: flex;
            gap: 24px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .quick-link {
            color: var(--cyan);
            text-decoration: none;
            font-size: 0.85rem;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .quick-link:hover {
            color: var(--yellow);
            transform: translateY(-2px);
        }

        .quick-link::before {
            content: '→';
            font-family: var(--ff-pixel);
            font-size: 0.4rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .error-card {
                padding: 36px 24px;
            }

            .error-code {
                font-size: 3rem;
            }

            .error-title {
                font-size: 0.75rem;
            }

            .error-description {
                font-size: 0.85rem;
            }

            .error-actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }

            .quick-links-list {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
</head>
<body>
    <!-- Floating particles background -->
    <div class="particles" id="particles"></div>

    <div class="error-container">
        <div class="error-card">
            <!-- Error code -->
            <div class="error-code">500</div>

            <!-- Error title -->
            <h1 class="error-title">INTERNAL SERVER ERROR</h1>

            <!-- Error description -->
            <p class="error-description">
                Oops! Ada bug di server kami 🐛<br>
                Tim developer sedang investigating masalahnya. Coba refresh atau kembali nanti ya!
            </p>

            <!-- Illustration (using text-based pixel art) -->
            <div class="error-illustration">
                <div style="font-size: 4rem; opacity: 0.3; animation: pulse 2s infinite;">
                    🔧
                </div>
            </div>

            <!-- Code snippet -->
            <div class="code-box">
                <strong>ERROR:</strong> Something went wrong on our end<br>
                <strong>STATUS:</strong> 500 Internal Server Error<br>
                <strong>ACTION:</strong> Our team has been notified
            </div>

            <!-- Action buttons -->
            <div class="error-actions">
                <button onclick="location.reload()" class="btn btn-primary">
                    ↻ Refresh Halaman
                </button>
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                        Kembali ke Dashboard
                    </a>
                @else
                    <a href="{{ route('home') }}" class="btn btn-secondary">
                        Kembali ke Home
                    </a>
                @endauth
            </div>

            <!-- Quick links -->
            <div class="quick-links">
                <div class="quick-links-title">ATAU NAVIGASI KE</div>
                <div class="quick-links-list">
                    @auth
                        <a href="{{ route('dashboard') }}" class="quick-link">Dashboard</a>
                        <a href="{{ route('modules.index') }}" class="quick-link">Materi</a>
                        <a href="{{ route('leaderboard.index') }}" class="quick-link">Leaderboard</a>
                    @else
                        <a href="{{ route('home') }}" class="quick-link">Home</a>
                        <a href="{{ route('login') }}" class="quick-link">Login</a>
                        <a href="{{ route('register') }}" class="quick-link">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <script>
        // Create floating particles
        const particlesContainer = document.getElementById('particles');
        const colors = ['#FF6B6B', '#FFDF20', '#FF6B6B'];
        
        for (let i = 0; i < 40; i++) {
            const particle = document.createElement('div');
            particle.className = 'particle';
            particle.style.left = Math.random() * 100 + '%';
            particle.style.top = Math.random() * 100 + '%';
            particle.style.background = colors[Math.floor(Math.random() * colors.length)];
            particle.style.animationDelay = Math.random() * 10 + 's';
            particle.style.animationDuration = (Math.random() * 10 + 10) + 's';
            particlesContainer.appendChild(particle);
        }
    </script>
</body>
</html>
