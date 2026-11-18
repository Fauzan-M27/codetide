<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edit Profile — CodeTide</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/toast.css') }}">
    <link rel="stylesheet" href="{{ asset('css/loading.css') }}">
    <link rel="stylesheet" href="{{ asset('css/mobile-responsive.css') }}">
    @vite(['resources/css/tutorial.css', 'resources/js/app.js'])
    <script src="{{ asset('js/pxlkit-renderer.js') }}"></script>
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
            --red: #FF6B6B;
            --bg: #040D1A;
            --bg2: #091326;
            --bg3: #0B1622;
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
            font-size: 14px;
            line-height: 1.6;
            min-height: 100vh;
        }

        /* Header */
        .header {
            background: var(--bg2);
            border-bottom: 1px solid var(--border);
            padding: 20px 32px;
            position: sticky;
            top: 0;
            z-index: 100;
            backdrop-filter: blur(12px);
        }

        .header-content {
            max-width: 1280px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logo {
            font-family: var(--ff-pixel);
            font-size: 0.75rem;
            color: var(--purple);
            text-decoration: none;
        }

        .logo .bracket {
            color: var(--cyan);
        }

        .nav-links {
            display: flex;
            gap: 16px;
        }

        .nav-links a {
            font-family: var(--ff-pixel);
            font-size: 0.5rem;
            padding: 8px 16px;
            border: 1px solid var(--border);
            color: var(--muted);
            text-decoration: none;
            transition: all 0.15s;
        }

        .nav-links a:hover {
            color: var(--cyan);
            border-color: var(--cyan);
        }

        /* Container */
        .container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 40px 32px;
        }

        .page-title {
            font-family: var(--ff-pixel);
            font-size: 1.25rem;
            color: var(--cyan);
            margin-bottom: 32px;
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .page-title .icon {
            display: inline-flex;
            align-items: center;
        }

        /* Grid Layout */
        .profile-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
        }

        @media (max-width: 968px) {
            .profile-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Card */
        .card {
            background: var(--bg2);
            border: 1px solid var(--border);
            padding: 24px;
        }

        .card-title {
            font-family: var(--ff-pixel);
            font-size: 0.65rem;
            color: var(--cyan);
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .card-title .icon {
            display: inline-flex;
            align-items: center;
        }

        /* Profile Info */
        .profile-info-item {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid rgba(83, 234, 253, 0.08);
        }

        .profile-info-label {
            color: var(--muted);
            font-size: 0.875rem;
        }

        .profile-info-value {
            color: var(--text);
            font-weight: 700;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }

        .stat-box {
            background: var(--bg3);
            border: 1px solid var(--border);
            padding: 16px;
            text-align: center;
        }

        .stat-value {
            font-family: var(--ff-pixel);
            font-size: 1.5rem;
            color: var(--cyan);
            margin-bottom: 8px;
        }

        .stat-label {
            font-size: 0.75rem;
            color: var(--muted);
        }

        /* Badge Grid */
        .badge-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }

        .badge-item {
            background: var(--bg3);
            border: 1px solid var(--border);
            padding: 16px;
            text-align: center;
            position: relative;
            transition: all 0.2s;
        }

        .badge-item.earned {
            border-color: var(--yellow);
            background: rgba(255, 223, 32, 0.05);
        }

        .badge-item.locked {
            opacity: 0.4;
            filter: grayscale(100%);
        }

        .badge-item:hover {
            transform: translateY(-2px);
            border-color: var(--cyan);
        }

        .badge-icon {
            margin-bottom: 12px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .badge-name {
            font-family: var(--ff-pixel);
            font-size: 0.5rem;
            color: var(--text);
            margin-bottom: 8px;
        }

        .badge-desc {
            font-size: 0.75rem;
            color: var(--muted);
        }

        .badge-earned-at {
            font-size: 0.65rem;
            color: var(--green);
            margin-top: 8px;
        }

        /* Costume Preview */
        .costume-preview-box {
            background: var(--bg3);
            border: 2px solid var(--border);
            padding: 24px;
            text-align: center;
            min-height: 280px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 16px;
        }

        .costume-preview-box img {
            max-width: 160px;
            max-height: 160px;
            image-rendering: pixelated;
        }

        .costume-name {
            font-family: var(--ff-pixel);
            font-size: 0.65rem;
            color: var(--cyan);
        }

        .costume-change-link {
            font-size: 0.75rem;
            color: var(--purple);
            text-decoration: none;
            border-bottom: 1px dashed var(--purple);
            margin-top: 8px;
            display: inline-block;
        }

        .costume-change-link:hover {
            color: var(--cyan);
            border-color: var(--cyan);
        }

        /* Form */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            color: var(--muted);
            margin-bottom: 8px;
        }

        .form-input {
            width: 100%;
            background: var(--bg3);
            border: 1px solid var(--border);
            color: var(--text);
            padding: 12px 16px;
            font-family: var(--ff-code);
            font-size: 0.875rem;
            transition: all 0.2s;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--cyan);
            box-shadow: 0 0 0 3px rgba(83, 234, 253, 0.1);
        }

        .form-input:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .btn {
            font-family: var(--ff-pixel);
            font-size: 0.5rem;
            padding: 12px 24px;
            border: 1px solid var(--cyan);
            background: transparent;
            color: var(--cyan);
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-block;
        }

        .btn:hover {
            background: var(--cyan);
            color: var(--bg);
        }

        .btn-danger {
            border-color: var(--red);
            color: var(--red);
        }

        .btn-danger:hover {
            background: var(--red);
            color: white;
        }

        .alert {
            padding: 12px 16px;
            margin-bottom: 20px;
            border-left: 4px solid var(--green);
            background: rgba(123, 241, 168, 0.1);
            color: var(--green);
            font-size: 0.875rem;
        }

        /* Form input error state */
        .form-input.is-error {
            border-color: var(--red);
        }

        .form-error-msg {
            color: var(--red);
            font-size: 0.78rem;
            margin-top: 4px;
            display: block;
        }

        /* Danger Zone */
        .danger-zone {
            border-color: var(--red) !important;
        }

        .danger-zone .card-title {
            color: var(--red);
        }

        /* Full Width */
        .full-width {
            grid-column: 1 / -1;
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            color: var(--muted);
        }

        .btn-icon {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-icon .icon {
            display: inline-flex;
        }
    </style>
</head>
<body>
    <script>
        // PxlKit Icon Map - using available icons
        const PXL_MAP = {
            user: () => PXL_SHIELD_SMALL,
            document: () => PXL_CHECK,
            chart: () => PXL_TARGET_HIT,
            trophy: () => PXL_TROPHY,
            sparkle: () => PXL_SPARKLE_STAR,
            lightning: () => PXL_LIGHTNING,
            target: () => PXL_TARGET_HIT,
            gem: () => PXL_FLOATING_GEM,
            lock: () => PXL_LOCK,
            book: () => PXL_CROWN,
            save: () => PXL_CHECK,
            costume: () => PXL_CROWN,
            flag: () => PXL_MEDAL,
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

        // Initialize
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', injectPxlIcons);
        } else {
            injectPxlIcons();
        }

        // Re-inject periodically for dynamic content
        setInterval(injectPxlIcons, 1000);
    </script>
    <script src="{{ asset('js/toast.js') }}"></script>
    <script src="{{ asset('js/loading.js') }}"></script>
    <script>
        // Profile form submission with loading & toast
        document.getElementById('profileForm')?.addEventListener('submit', function(e) {
            const saveBtn = document.getElementById('saveBtn');
            Loading.button(saveBtn, true, 'MENYIMPAN...');
            // Biarkan form submit normal (bukan AJAX)
        });

        // Tampilkan toast dari session
        @if (session('status') === 'profile-updated')
            setTimeout(() => {
                Toast.success('Profil Updated!', 'Nickname berhasil diperbarui.');
            }, 300);
        @endif

        // Tampilkan toast error validasi dari session
        @if ($errors->has('nickname'))
            setTimeout(() => {
                Toast.error('Validasi Gagal', '{{ $errors->first('nickname') }}');
            }, 300);
        @endif

        // Tampilkan toast sukses ganti password
        @if (session('status') === 'password-updated')
            setTimeout(() => {
                Toast.success('Password Diperbarui!', 'Kamu bisa login dengan password baru.');
            }, 300);
        @endif

        // Tampilkan toast error ganti password
        @if ($errors->updatePassword->any())
            setTimeout(() => {
                Toast.error('Validasi Gagal', '{{ $errors->updatePassword->first() }}');
            }, 300);
        @endif

        // Auto-buka modal hapus akun jika ada error
        @if ($errors->userDeletion->any())
            document.getElementById('deleteAccountModal').style.display = 'flex';
            setTimeout(() => {
                Toast.error('Password Salah', '{{ $errors->userDeletion->first() }}');
            }, 300);
        @endif

        // Restart tutorial function
        function restartTutorial() {
            // Show pixel-style confirmation using Toast (non-blocking)
            const confirmed = confirm('Ulangi tutorial panduan CodeTide?\n\nAnda akan diarahkan ke Dashboard dan tutorial akan muncul kembali.');
            if (!confirmed) return;

            // Reset tutorial completion status
            fetch('{{ route("tutorial.reset") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Clear localStorage
                    localStorage.removeItem('codetide_tutorial_completed');
                    localStorage.removeItem('codetide_tutorial_step');
                    
                    // Show success message
                    Toast.success('Tutorial Reset! 🎮', 'Memuat ulang dashboard — tutorial akan segera dimulai!');
                    
                    // Redirect to dashboard after a short delay
                    setTimeout(() => {
                        window.location.href = '{{ route("dashboard") }}';
                    }, 1800);
                } else {
                    Toast.error('Gagal', 'Tidak dapat mereset tutorial. Coba lagi.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Toast.error('Error', 'Koneksi gagal. Coba lagi.');
            });
        }
    </script>
    <!-- Header -->
    <div class="header">
        <div class="header-content">
            <a href="{{ route('dashboard') }}" class="logo">
                <span class="bracket">&lt;</span>Code<span class="bracket">/</span>Tide<span class="bracket">&gt;</span>
            </a>
            <div class="nav-links">
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <a href="{{ route('costumes.index') }}">Kostum</a>
                <a href="{{ route('leaderboard.index') }}">Leaderboard</a>
            </div>
        </div>
    </div>

    <div class="container">
        <h1 class="page-title">
            <span class="icon" data-pxl-icon="user" data-pxl-scale="3"></span>
            Edit Profile
        </h1>

        @if (session('status') === 'profile-updated')
            <div class="alert">
                <span data-pxl-icon="sparkle" data-pxl-scale="2" style="display: inline-flex; vertical-align: middle; margin-right: 8px;"></span>
                Profile berhasil diupdate!
            </div>
        @endif

        <div class="profile-grid">
            <!-- Profile Info -->
            <div class="card">
                <h2 class="card-title">
                    <span class="icon" data-pxl-icon="document" data-pxl-scale="2"></span>
                    Informasi Akun
                </h2>
                
                <form method="POST" action="{{ route('profile.update') }}" id="profileForm">
                    @csrf
                    @method('PATCH')

                    <div class="form-group">
                        <label class="form-label">Nickname</label>
                        <input type="text" name="nickname" class="form-input {{ $errors->has('nickname') ? 'is-error' : '' }}" value="{{ old('nickname', $user->nickname) }}" required>
                        @error('nickname')
                            <span style="color:#FF6B6B; font-size:0.78rem; margin-top:4px; display:block;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Participant Code</label>
                        <input type="text" class="form-input" value="{{ $user->participant_code }}" disabled>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Bergabung Sejak</label>
                        <input type="text" class="form-input" value="{{ $stats['join_date'] }}" disabled>
                    </div>

                    <button type="submit" class="btn btn-icon" id="saveBtn">
                        <span class="icon" data-pxl-icon="save" data-pxl-scale="1"></span>
                        Simpan Perubahan
                    </button>
                </form>
            </div>

            <!-- Stats Overview -->
            <div class="card">
                <h2 class="card-title">
                    <span class="icon" data-pxl-icon="chart" data-pxl-scale="2"></span>
                    Statistik
                </h2>
                
                <div class="stats-grid">
                    <div class="stat-box">
                        <div class="stat-value">{{ $stats['total_xp'] }}</div>
                        <div class="stat-label">Total XP</div>
                    </div>

                    <div class="stat-box">
                        <div class="stat-value">{{ $stats['badges_earned'] }}/4</div>
                        <div class="stat-label">Badges</div>
                    </div>

                    <div class="stat-box">
                        <div class="stat-value">{{ $stats['completed_questions'] }}/{{ $stats['total_questions'] }}</div>
                        <div class="stat-label">Soal Selesai</div>
                    </div>

                    <div class="stat-box">
                        <div class="stat-value">{{ $stats['costumes_unlocked'] }}/19</div>
                        <div class="stat-label">Kostum Unlock</div>
                    </div>
                </div>
            </div>

            <!-- Badge Showcase -->
            <div class="card full-width">
                <h2 class="card-title">
                    <span class="icon" data-pxl-icon="trophy" data-pxl-scale="2"></span>
                    Badge Collection
                </h2>
                
                @if($allBadges->where('is_earned', true)->count() > 0)
                    <div class="badge-grid">
                        @foreach($allBadges as $badge)
                            <div class="badge-item {{ $badge['is_earned'] ? 'earned' : 'locked' }}">
                                <div class="badge-icon">
                                    <span data-pxl-icon="{{ $badge['is_earned'] ? 'trophy' : 'lock' }}" data-pxl-scale="4"></span>
                                </div>
                                <div class="badge-name">{{ $badge['name'] }}</div>
                                <div class="badge-desc">{{ $badge['description'] }}</div>
                                @if($badge['is_earned'] && $badge['earned_at'])
                                    <div class="badge-earned-at">
                                        Earned: {{ \Carbon\Carbon::parse($badge['earned_at'])->format('d M Y') }}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <p>Belum ada badge yang earned.</p>
                        <p style="margin-top: 8px;">Selesaikan modul untuk mendapatkan badge!</p>
                    </div>
                @endif
            </div>

            <!-- Active Costume -->
            <div class="card">
                <h2 class="card-title">
                    <span class="icon" data-pxl-icon="gem" data-pxl-scale="2"></span>
                    Kostum Aktif
                </h2>
                
                <div class="costume-preview-box">
                    @if($activeCostume)
                        <img src="{{ asset($activeCostume->sprite_idle) }}" alt="{{ $activeCostume->name }}">
                        <div class="costume-name">{{ $activeCostume->name }}</div>
                        <a href="{{ route('costumes.index') }}" class="costume-change-link">
                            Ganti Kostum
                        </a>
                    @else
                        <span data-pxl-icon="user" data-pxl-scale="8" style="opacity: 0.3;"></span>
                        <div class="costume-name">No Costume</div>
                        <a href="{{ route('costumes.index') }}" class="costume-change-link">
                            Pilih Kostum
                        </a>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card">
                <h2 class="card-title">
                    <span class="icon" data-pxl-icon="lightning" data-pxl-scale="2"></span>
                    Quick Actions
                </h2>
                
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <a href="{{ route('modules.index') }}" class="btn btn-icon" style="justify-content: center;">
                        <span class="icon" data-pxl-icon="book" data-pxl-scale="1"></span>
                        Lanjutkan Belajar
                    </a>
                    <a href="{{ route('costumes.index') }}" class="btn btn-icon" style="justify-content: center;">
                        <span class="icon" data-pxl-icon="gem" data-pxl-scale="1"></span>
                        Kelola Kostum
                    </a>
                    <a href="{{ route('leaderboard.index') }}" class="btn btn-icon" style="justify-content: center;">
                        <span class="icon" data-pxl-icon="flag" data-pxl-scale="1"></span>
                        Lihat Leaderboard
                    </a>
                    <button onclick="restartTutorial()" class="btn btn-icon" style="justify-content:center; border-color:var(--purple); color:var(--purple); background:rgba(237,106,255,0.06); position:relative; overflow:hidden;" title="Mulai ulang panduan penggunaan website">
                        <span class="icon" data-pxl-icon="sparkle" data-pxl-scale="1"></span>
                        ❓ Ulangi Tutorial
                    </button>
                </div>
            </div>

            {{-- ===================== GANTI PASSWORD ===================== --}}
            <div class="card full-width">
                <h2 class="card-title">
                    <span class="icon" data-pxl-icon="save" data-pxl-scale="2"></span>
                    Ganti Password
                </h2>

                @if (session('status') === 'password-updated')
                    <div class="alert" style="margin-bottom:16px;">
                        Password berhasil diperbarui!
                    </div>
                @endif

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label class="form-label">Password Lama</label>
                        <input type="password" name="current_password"
                               class="form-input {{ $errors->updatePassword->has('current_password') ? 'is-error' : '' }}"
                               placeholder="Masukkan password lama" autocomplete="current-password">
                        @if ($errors->updatePassword->has('current_password'))
                            <span class="form-error-msg">{{ $errors->updatePassword->first('current_password') }}</span>
                        @endif
                    </div>

                    <div class="form-group">
                        <label class="form-label">Password Baru</label>
                        <input type="password" name="password"
                               class="form-input {{ $errors->updatePassword->has('password') ? 'is-error' : '' }}"
                               placeholder="Minimal 8 karakter" autocomplete="new-password">
                        @if ($errors->updatePassword->has('password'))
                            <span class="form-error-msg">{{ $errors->updatePassword->first('password') }}</span>
                        @endif
                    </div>

                    <div class="form-group">
                        <label class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation"
                               class="form-input"
                               placeholder="Ulangi password baru" autocomplete="new-password">
                    </div>

                    <button type="submit" class="btn btn-icon">
                        <span class="icon" data-pxl-icon="save" data-pxl-scale="1"></span>
                        Ganti Password
                    </button>
                </form>
            </div>

            {{-- ===================== HAPUS AKUN ===================== --}}
            <div class="card full-width danger-zone">
                <h2 class="card-title">
                    ⚠ Hapus Akun
                </h2>
                <p style="color: var(--muted); font-size: 0.875rem; margin-bottom: 20px; line-height: 1.6;">
                    Setelah akun dihapus, semua data termasuk progress, badge, dan kostum akan hilang secara permanen.
                    Tindakan ini <strong style="color: var(--red);">tidak dapat dibatalkan</strong>.
                </p>

                <button type="button" class="btn btn-danger btn-icon"
                        onclick="document.getElementById('deleteAccountModal').style.display='flex'">
                    🗑 Hapus Akun
                </button>
            </div>

        </div>
    </div>

    {{-- ===================== MODAL HAPUS AKUN ===================== --}}
    <div id="deleteAccountModal" style="
        display: none;
        position: fixed; inset: 0; z-index: 999;
        background: rgba(0,0,0,0.75);
        align-items: center; justify-content: center;
    ">
        <div style="
            background: var(--bg2);
            border: 1px solid var(--red);
            padding: 32px;
            max-width: 420px;
            width: 90%;
        ">
            <h3 style="font-family: var(--ff-pixel); color: var(--red); font-size: 0.75rem; margin-bottom: 16px;">
                ⚠ Konfirmasi Hapus Akun
            </h3>
            <p style="color: var(--muted); font-size: 0.85rem; margin-bottom: 20px; line-height: 1.6;">
                Masukkan password kamu untuk mengonfirmasi penghapusan akun.
            </p>

            <form method="POST" action="{{ route('profile.destroy') }}">
                @csrf
                @method('DELETE')

                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password"
                           class="form-input {{ $errors->userDeletion->has('password') ? 'is-error' : '' }}"
                           placeholder="Masukkan password" id="deletePasswordInput">
                    @if ($errors->userDeletion->has('password'))
                        <span class="form-error-msg">{{ $errors->userDeletion->first('password') }}</span>
                    @endif
                </div>

                <div style="display: flex; gap: 12px; margin-top: 8px;">
                    <button type="submit" class="btn btn-danger btn-icon">
                        🗑 Hapus Akun
                    </button>
                    <button type="button" class="btn btn-icon"
                            onclick="document.getElementById('deleteAccountModal').style.display='none'">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
