/**
 * CodeTide Tutorial System — Enhanced Edition
 * Interactive guided tour untuk pengguna baru
 */

import Shepherd from 'shepherd.js';
import 'shepherd.js/dist/css/shepherd.css';

class CodeTideTutorial {
    constructor() {
        this.tour        = null;
        this.totalSteps  = 0;
        this.currentPage = this.getCurrentPage();
        this.pxlkitLoaded = false;
        this.userName    = document.querySelector('.welcome-info .name')?.textContent?.trim()
                        || document.querySelector('.user-name')?.textContent?.trim()
                        || 'Pemain';
        this.initPxlKit();
    }

    /* ─── PxlKit ─────────────────────────────────────── */

    initPxlKit() {
        const check = setInterval(() => {
            if (typeof PXLKIT !== 'undefined' && typeof PXL_SPARKLE_STAR !== 'undefined') {
                this.pxlkitLoaded = true;
                clearInterval(check);
            }
        }, 100);
        // Setelah 6 detik paksa dianggap selesai (pakai emoji fallback)
        setTimeout(() => { clearInterval(check); }, 6000);
    }

    /**
     * Tunggu hingga PxlKit siap, lalu jalankan callback.
     * Menjamin renderIcon() selalu menghasilkan gambar, bukan canvas kosong.
     */
    waitForPxlKit(callback) {
        if (this.pxlkitLoaded) {
            callback();
            return;
        }
        const wait = setInterval(() => {
            if (this.pxlkitLoaded || typeof PXLKIT !== 'undefined') {
                this.pxlkitLoaded = true;
                clearInterval(wait);
                callback();
            }
        }, 80);
        // Fallback: jalankan juga setelah 3 detik walau belum siap
        setTimeout(() => {
            clearInterval(wait);
            callback();
        }, 3000);
    }

    renderIcon(iconName, scale = 5) {
        const FALLBACKS = {
            wave: '👋', stats: '📊', book: '📚', play: '▶️',
            box: '📦', trophy: '🏆', gem: '💎', medal: '🏅',
            user: '👤', sparkle: '✨', check: '✅', leaderboard: '🥇',
            costume: '🎨', badge: '🎖️', progress: '📈', tip: '💡'
        };

        if (!this.pxlkitLoaded || typeof PXLKIT === 'undefined') {
            return `<span class="tutorial-emoji">${FALLBACKS[iconName] || '⭐'}</span>`;
        }

        const iconMap = {
            wave:        PXL_SPARKLE_STAR,
            stats:       PXL_TARGET_HIT,
            book:        PXL_CROWN,
            play:        PXL_LIGHTNING,
            box:         typeof PXL_SHIELD_SMALL !== 'undefined' ? PXL_SHIELD_SMALL : PXL_SPARKLE_STAR,
            trophy:      PXL_TROPHY,
            gem:         PXL_FLOATING_GEM,
            medal:       PXL_MEDAL,
            user:        typeof PXL_GHOST_FRIEND !== 'undefined' ? PXL_GHOST_FRIEND : PXL_SPARKLE_STAR,
            sparkle:     PXL_SPARKLE_STAR,
            check:       PXL_CHECK,
            leaderboard: PXL_TROPHY,
            costume:     PXL_FLOATING_GEM,
            badge:       PXL_MEDAL,
            progress:    PXL_TARGET_HIT,
        };

        try {
            const data   = iconMap[iconName] || PXL_SPARKLE_STAR;
            const canvas = PXLKIT.render(data, scale);
            // canvas.outerHTML hanya meng-copy struktur HTML kosong, bukan pixel-nya.
            // Gunakan toDataURL() untuk mengambil gambar sebagai PNG data URL.
            const dataUrl = canvas.toDataURL('image/png');
            const w = canvas.width;
            const h = canvas.height;
            return `<div class="tutorial-icon"><img src="${dataUrl}" width="${w}" height="${h}" alt="${iconName} icon" style="image-rendering:pixelated;image-rendering:-moz-crisp-edges;image-rendering:crisp-edges;"></div>`;
        } catch {
            return `<span class="tutorial-emoji">${FALLBACKS[iconName] || '⭐'}</span>`;
        }
    }

    /* ─── Helpers ─────────────────────────────────────── */

    getCurrentPage() {
        const p = window.location.pathname;
        if (p === '/' || p === '/dashboard') return 'dashboard';
        if (p.includes('/modules'))     return 'modules';
        if (p.includes('/leaderboard')) return 'leaderboard';
        if (p.includes('/costumes'))    return 'costumes';
        if (p.includes('/questions'))   return 'questions';
        return 'other';
    }

    /** Build dot-progress HTML: N dots, currentIdx active */
    buildDots(currentIdx) {
        const n = this.totalSteps;
        let dots = '<div class="tutorial-dots">';
        for (let i = 0; i < n; i++) {
            const cls = i < currentIdx ? 'done' : (i === currentIdx ? 'active' : '');
            dots += `<span class="tutorial-dot ${cls}"></span>`;
        }
        return dots + '</div>';
    }

    /** Wrap step text with badge + dots */
    wrap(innerHtml, stepIdx) {
        const badge = `<div class="tutorial-step-badge">LANGKAH ${stepIdx + 1} / ${this.totalSteps}</div>`;
        const dots  = this.buildDots(stepIdx);
        return `<div class="tutorial-content">${badge}${innerHtml}${dots}</div>`;
    }

    /* ─── Tour init ───────────────────────────────────── */

    initTour() {
        this.tour = new Shepherd.Tour({
            useModalOverlay: true,
            defaultStepOptions: {
                cancelIcon:  { enabled: true },
                classes:     'codetide-shepherd-theme',
                scrollTo:    { behavior: 'smooth', block: 'center' },
                modalOverlayOpeningPadding: 12,
                modalOverlayOpeningRadius:  6,
                popperOptions: {
                    modifiers: [
                        { name: 'offset',          options: { offset: [0, 28] } },
                        { name: 'preventOverflow', options: { padding: 16 } }
                    ]
                }
            },
            tourName: 'codetide-onboarding',
            exitOnEsc: true,
            keyboardNavigation: true
        });

        this.tour.on('complete', () => this.onTourComplete());
        this.tour.on('cancel',   () => this.onTourCancel());
    }

    /* ─── Dashboard Tour ──────────────────────────────── */

    startDashboardTour() {
        this.initTour();

        /* Detect optional elements */
        const continueBtn    = document.querySelector('.quick-btn.primary, .btn-primary[href*="questions"]');
        const moduleCard     = document.querySelector('.module-card');
        const lbLink         = document.querySelector('.lp-nav-links a[href*="leaderboard"]');
        const costumeLink    = document.querySelector('.lp-nav-links a[href*="costumes"]');
        const badgePanel     = document.querySelector('.panel:has(.badge-list), .panel:has(.empty-state)');
        const profileBtn     = document.querySelector('a[href*="profile"].quick-btn');
        const progressPanel  = document.querySelector('.panel:has(.prog-bar)');

        /* Count total steps */
        this.totalSteps = 4
            + (continueBtn   ? 1 : 0)
            + (moduleCard    ? 1 : 0)
            + (lbLink        ? 1 : 0)
            + (costumeLink   ? 1 : 0)
            + (badgePanel    ? 1 : 0)
            + (progressPanel ? 1 : 0)
            + (profileBtn    ? 1 : 0);

        let idx = 0;

        /* ── Step 1: Welcome ── */
        this.tour.addStep({
            id: 'welcome',
            text: this.wrap(`
                ${this.renderIcon('wave', 6)}
                <h3>Selamat Datang, ${this.userName}! 👾</h3>
                <p>Halo! Saya akan memandu Anda mengenal <strong>CodeTide</strong> — platform belajar coding bergaya retro yang menyenangkan.</p>
                <p>Tutorial singkat ini membutuhkan sekitar <strong>2 menit</strong>. Siap?</p>
                <ul class="tutorial-features">
                    <li>Belajar coding lewat soal interaktif</li>
                    <li>Kumpulkan XP &amp; badge pencapaian</li>
                    <li>Bersaing di leaderboard global</li>
                    <li>Personalisasi avatar &amp; kostum</li>
                </ul>
            `, idx++),
            buttons: [
                { text: 'Lewati', classes: 'shepherd-button-secondary', action: () => this.tour.cancel() },
                { text: 'Mulai Tour ▶', action: () => this.tour.next() }
            ]
        });

        /* ── Step 2: Navbar ── */
        this.tour.addStep({
            id: 'navbar',
            text: this.wrap(`
                ${this.renderIcon('play', 5)}
                <h3>Navigasi Utama</h3>
                <p>Gunakan menu di bagian atas untuk berpindah antar halaman:</p>
                <ul class="tutorial-features">
                    <li><strong>Dashboard</strong> — ringkasan progres Anda</li>
                    <li><strong>Materi</strong> — daftar semua modul</li>
                    <li><strong>Leaderboard</strong> — ranking pemain</li>
                    <li><strong>Kostumisasi</strong> — ubah tampilan avatar</li>
                </ul>
                <div class="tutorial-tip">Navbar selalu tersedia di semua halaman.</div>
            `, idx++),
            attachTo: { element: '.lp-nav', on: 'bottom' },
            buttons: [
                { text: '← Kembali', classes: 'shepherd-button-secondary', action: () => this.tour.back() },
                { text: 'Lanjut →',  action: () => this.tour.next() }
            ]
        });

        /* ── Step 3: Welcome Card ── */
        this.tour.addStep({
            id: 'welcome-card',
            text: this.wrap(`
                ${this.renderIcon('user', 5)}
                <h3>Kartu Profil Anda</h3>
                <p>Di sini tampil <strong>avatar</strong>, <strong>nama</strong>, dan <strong>kostum aktif</strong> Anda saat ini.</p>
                <p>Gunakan tombol cepat di kanan untuk mulai belajar atau mengatur profil.</p>
                <div class="tutorial-tip">Avatar bisa diubah dari halaman Kostumisasi.</div>
            `, idx++),
            attachTo: { element: '.welcome-card', on: 'bottom' },
            buttons: [
                { text: '← Kembali', classes: 'shepherd-button-secondary', action: () => this.tour.back() },
                { text: 'Lanjut →',  action: () => this.tour.next() }
            ]
        });

        /* ── Step 4: Stats Row ── */
        this.tour.addStep({
            id: 'stats',
            text: this.wrap(`
                ${this.renderIcon('stats', 5)}
                <h3>Statistik Anda</h3>
                <p>Empat kartu ini merangkum pencapaian Anda:</p>
                <ul class="tutorial-features">
                    <li><strong style="color:#FFDF20">Total XP</strong> — poin pengalaman yang terkumpul</li>
                    <li><strong style="color:#7BF1A8">Soal Selesai</strong> — jumlah soal yang sudah dijawab</li>
                    <li><strong style="color:#ED6AFF">Ranking</strong> — posisi Anda di antara semua pemain</li>
                    <li><strong style="color:#53EAFD">Badge</strong> — koleksi pencapaian yang diraih</li>
                </ul>
                <div class="tutorial-tip">XP bertambah setiap kali Anda menjawab soal dengan benar!</div>
            `, idx++),
            attachTo: { element: '.stat-row', on: 'bottom' },
            buttons: [
                { text: '← Kembali', classes: 'shepherd-button-secondary', action: () => this.tour.back() },
                { text: 'Lanjut →',  action: () => this.tour.next() }
            ]
        });

        /* ── Step optional: Lanjutkan Belajar ── */
        if (continueBtn) {
            this.tour.addStep({
                id: 'continue-learning',
                text: this.wrap(`
                    ${this.renderIcon('play', 5)}
                    <h3>Lanjutkan Belajar</h3>
                    <p>Tombol <strong>"Lanjutkan Belajar"</strong> membawa Anda langsung ke soal berikutnya yang belum diselesaikan.</p>
                    <p>Klik di sini setiap kali ingin melanjutkan sesi belajar Anda!</p>
                    <div class="tutorial-tip">Jawab soal secara berurutan untuk membuka modul berikutnya.</div>
                `, idx++),
                attachTo: { element: continueBtn, on: 'bottom' },
                buttons: [
                    { text: '← Kembali', classes: 'shepherd-button-secondary', action: () => this.tour.back() },
                    { text: 'Lanjut →',  action: () => this.tour.next() }
                ]
            });
        }

        /* ── Step optional: Modul Aktif ── */
        if (moduleCard) {
            this.tour.addStep({
                id: 'active-module',
                text: this.wrap(`
                    ${this.renderIcon('box', 5)}
                    <h3>Modul Aktif</h3>
                    <p>Kartu ini menampilkan <strong>modul yang sedang Anda kerjakan</strong> beserta progres penyelesaiannya.</p>
                    <p>Progress bar menunjukkan persentase soal yang sudah selesai dalam modul tersebut.</p>
                    <div class="tutorial-tip">Selesaikan 100% soal dalam satu modul untuk membuka modul berikutnya!</div>
                `, idx++),
                attachTo: { element: moduleCard, on: 'top' },
                buttons: [
                    { text: '← Kembali', classes: 'shepherd-button-secondary', action: () => this.tour.back() },
                    { text: 'Lanjut →',  action: () => this.tour.next() }
                ]
            });
        }

        /* ── Step optional: Progress Keseluruhan ── */
        if (progressPanel) {
            this.tour.addStep({
                id: 'overall-progress',
                text: this.wrap(`
                    ${this.renderIcon('progress', 5)}
                    <h3>Progress Keseluruhan</h3>
                    <p>Panel ini menampilkan <strong>total kemajuan belajar</strong> Anda di semua modul yang tersedia.</p>
                    <p>Setiap kotak kecil mewakili satu modul — warna <span class="highlight-green">hijau</span> artinya selesai, <strong style="color:#FFDF20">kuning</strong> artinya sedang dikerjakan.</p>
                `, idx++),
                attachTo: { element: progressPanel, on: 'top' },
                buttons: [
                    { text: '← Kembali', classes: 'shepherd-button-secondary', action: () => this.tour.back() },
                    { text: 'Lanjut →',  action: () => this.tour.next() }
                ]
            });
        }

        /* ── Step optional: Leaderboard Navbar ── */
        if (lbLink) {
            this.tour.addStep({
                id: 'leaderboard',
                text: this.wrap(`
                    ${this.renderIcon('leaderboard', 5)}
                    <h3>Leaderboard</h3>
                    <p>Halaman <strong>Leaderboard</strong> menampilkan peringkat semua pemain berdasarkan XP yang dikumpulkan.</p>
                    <p>Bersainglah dengan teman-teman untuk meraih posisi teratas! 🏆</p>
                    <div class="tutorial-tip">XP tidak akan berkurang — terus jawab soal untuk naik peringkat.</div>
                `, idx++),
                attachTo: { element: lbLink, on: 'bottom' },
                buttons: [
                    { text: '← Kembali', classes: 'shepherd-button-secondary', action: () => this.tour.back() },
                    { text: 'Lanjut →',  action: () => this.tour.next() }
                ]
            });
        }

        /* ── Step optional: Kostumisasi Navbar ── */
        if (costumeLink) {
            this.tour.addStep({
                id: 'costumes',
                text: this.wrap(`
                    ${this.renderIcon('costume', 5)}
                    <h3>Kostumisasi</h3>
                    <p>Buka kunci berbagai <strong>kostum &amp; avatar pixel-art</strong> yang keren dengan mengumpulkan XP atau menyelesaikan tantangan tertentu!</p>
                    <p>Setiap kostum punya tampilan unik — tunjukkan gaya belajar Anda.</p>
                    <div class="tutorial-tip">Beberapa kostum eksklusif hanya bisa dibuka dengan menyelesaikan semua modul.</div>
                `, idx++),
                attachTo: { element: costumeLink, on: 'bottom' },
                buttons: [
                    { text: '← Kembali', classes: 'shepherd-button-secondary', action: () => this.tour.back() },
                    { text: 'Lanjut →',  action: () => this.tour.next() }
                ]
            });
        }

        /* ── Step optional: Badge Panel ── */
        if (badgePanel) {
            this.tour.addStep({
                id: 'badges',
                text: this.wrap(`
                    ${this.renderIcon('badge', 5)}
                    <h3>Koleksi Badge</h3>
                    <p>Panel ini menampilkan <strong>badge pencapaian</strong> yang sudah Anda kumpulkan.</p>
                    <p>Badge diperoleh dengan menyelesaikan modul, menjawab soal streak, dan mencapai ranking tertentu.</p>
                    <div class="tutorial-tip">Badge pertama Anda akan muncul setelah menyelesaikan soal pertama!</div>
                `, idx++),
                attachTo: { element: badgePanel, on: 'left' },
                buttons: [
                    { text: '← Kembali', classes: 'shepherd-button-secondary', action: () => this.tour.back() },
                    { text: 'Lanjut →',  action: () => this.tour.next() }
                ]
            });
        }

        /* ── Step optional: Profil ── */
        if (profileBtn) {
            this.tour.addStep({
                id: 'profile',
                text: this.wrap(`
                    ${this.renderIcon('user', 5)}
                    <h3>Halaman Profil</h3>
                    <p>Di halaman <strong>Profil</strong>, Anda dapat:</p>
                    <ul class="tutorial-features">
                        <li>Mengubah <strong>nickname</strong> tampilan</li>
                        <li>Mengganti <strong>password</strong> akun</li>
                        <li>Mengulang tutorial ini kapan saja</li>
                    </ul>
                `, idx++),
                attachTo: { element: profileBtn, on: 'left' },
                buttons: [
                    { text: '← Kembali', classes: 'shepherd-button-secondary', action: () => this.tour.back() },
                    { text: 'Lanjut →',  action: () => this.tour.next() }
                ]
            });
        }

        /* ── Final Step ── */
        this.tour.addStep({
            id: 'complete',
            text: this.wrap(`
                ${this.renderIcon('sparkle', 6)}
                <h3>Siap Memulai! 🚀</h3>
                <p>Selamat! Anda sudah mengenal semua fitur utama <strong>CodeTide</strong>.</p>
                <p>Klik <strong>"Lanjutkan Belajar"</strong> dan mulailah perjalanan coding Anda sekarang!</p>
                <div class="tutorial-tip">Anda bisa mengulang tutorial ini kapan saja melalui tombol di halaman Profil.</div>
            `, idx),
            buttons: [
                { text: '← Kembali', classes: 'shepherd-button-secondary', action: () => this.tour.back() },
                { text: 'Mulai Belajar! ✓', classes: 'btn-finish', action: () => this.tour.complete() }
            ]
        });

        this.tour.start();
    }

    /* ─── Modules Tour ────────────────────────────────── */

    startModulesTour() {
        this.initTour();
        this.totalSteps = 2;
        let idx = 0;

        const moduleGrid   = document.querySelector('.modules-grid, .module-list, main');
        const firstModule  = document.querySelector('.module-card, [class*="module"]');

        this.tour.addStep({
            id: 'modules-welcome',
            text: this.wrap(`
                ${this.renderIcon('book', 6)}
                <h3>Halaman Materi</h3>
                <p>Di sini tersedia semua <strong>modul pembelajaran</strong> yang bisa Anda kerjakan.</p>
                <ul class="tutorial-features">
                    <li>Modul terkunci (🔒) perlu diselesaikan bertahap</li>
                    <li>Pilih modul yang sudah terbuka untuk mulai</li>
                    <li>Setiap modul berisi beberapa soal latihan</li>
                </ul>
                <div class="tutorial-tip">Selesaikan semua soal di satu modul untuk membuka modul berikutnya.</div>
            `, idx++),
            buttons: [
                { text: 'Lanjut →', action: () => this.tour.next() }
            ]
        });

        this.tour.addStep({
            id: 'modules-done',
            text: this.wrap(`
                ${this.renderIcon('sparkle', 6)}
                <h3>Siap Belajar! ✨</h3>
                <p>Pilih modul yang tersedia dan mulailah mengerjakan soal-soalnya. Setiap jawaban benar akan memberi Anda <strong>XP</strong>!</p>
                <div class="tutorial-tip">Kembali ke Dashboard kapan saja untuk melihat progres Anda.</div>
            `, idx),
            buttons: [
                { text: '← Kembali',   classes: 'shepherd-button-secondary', action: () => this.tour.back() },
                { text: 'Mengerti ✓',  classes: 'btn-finish', action: () => this.tour.complete() }
            ]
        });

        this.tour.start();
    }

    /* ─── API & State ─────────────────────────────────── */

    async onTourComplete() {
        try {
            const r = await fetch('/api/tutorial/complete', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                }
            });
            if (r.ok) {
                localStorage.setItem('codetide_tutorial_completed', 'true');
                localStorage.removeItem('codetide_tutorial_step');
                this.showCompletionToast();
            }
        } catch (e) {
            console.error('Tutorial complete error:', e);
        }
    }

    onTourCancel() {
        const step = this.tour?.getCurrentStep();
        if (step) localStorage.setItem('codetide_tutorial_step', step.id);
    }

    showCompletionToast() {
        const toast = document.createElement('div');
        toast.style.cssText = `
            position:fixed; bottom:32px; right:32px; z-index:99999;
            background:rgba(9,19,38,0.97); border:1px solid rgba(123,241,168,0.5);
            padding:16px 24px; font-family:'Press Start 2P',monospace; font-size:0.5rem;
            color:#7BF1A8; letter-spacing:0.05em; line-height:1.6;
            box-shadow:0 8px 32px rgba(0,0,0,0.5), 0 0 20px rgba(123,241,168,0.15);
            animation:toast-in 0.4s cubic-bezier(0.16,1,0.3,1);
            border-left:3px solid #7BF1A8;
        `;
        toast.innerHTML = '✓ Tutorial selesai!<br><span style="color:rgba(232,244,255,0.5);font-size:0.38rem;">Selamat belajar di CodeTide!</span>';

        const style = document.createElement('style');
        style.textContent = '@keyframes toast-in{from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)}}';
        document.head.appendChild(style);
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 4000);
    }

    shouldShowTutorial() {
        // Hanya tampilkan tutorial jika server mengirim flag show_tutorial = true.
        // Flag ini hanya di-set saat login/register dan langsung dihapus setelah
        // dibaca oleh controller (session()->pull()), sehingga navigasi balik ke
        // dashboard tidak akan memunculkan tutorial lagi.
        const serverFlag = document.body.dataset.showTutorial;
        return serverFlag === 'true';
    }

    async restartTutorial() {
        try {
            await fetch('/api/tutorial/reset', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                }
            });
        } catch (e) { /* silent */ }
        localStorage.removeItem('codetide_tutorial_completed');
        localStorage.removeItem('codetide_tutorial_step');
        window.location.href = '/dashboard';
    }
}

/* ─── Bootstrap ───────────────────────────────────────── */

document.addEventListener('DOMContentLoaded', () => {
    const tutorial = new CodeTideTutorial();

    if (tutorial.shouldShowTutorial()) {
        // Tunggu halaman selesai render (1.2 detik),
        // lalu tunggu PxlKit siap sebelum membangun HTML step yang berisi icon.
        setTimeout(() => {
            tutorial.waitForPxlKit(() => {
                if (tutorial.currentPage === 'dashboard') {
                    tutorial.startDashboardTour();
                } else if (tutorial.currentPage === 'modules') {
                    tutorial.startModulesTour();
                }
            });
        }, 1200);
    }

    window.CodeTideTutorial = tutorial;
});

window.restartTutorial = function() {
    if (window.CodeTideTutorial) window.CodeTideTutorial.restartTutorial();
};
