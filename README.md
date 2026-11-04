# CodeTide - Website Game Edukasi Pemrograman Web

**Produk Skripsi:** Pengembangan Website Game Edukasi Berbasis Gamifikasi dengan Umpan Balik Visual Langsung untuk Meningkatkan Motivasi Belajar Pemrograman Web Mahasiswa PTIK UNM

## 📋 Deskripsi

CodeTide adalah platform pembelajaran interaktif untuk mahasiswa PTIK UNM yang sedang mempelajari pemrograman web (HTML, CSS, JavaScript). Platform ini menerapkan gamifikasi (XP, level, badge, kostum) dan umpan balik visual langsung (live preview editor).

## ✨ Fitur Utama

### 1. Gamifikasi
- **XP System**: 5-25 XP per soal (total 585 XP)
- **Level Bertingkat**: HTML → CSS → JavaScript
- **Badge**: 4 badge pencapaian (HTML Explorer, CSS Stylist, JS Interactor, Web Developer Pemula)
- **Kostum**: 6 avatar yang bisa di-unlock (2 default, 2 badge-locked, 1 XP-locked, 1 module-locked)
- **Leaderboard**: Ranking berdasarkan total XP

### 2. Umpan Balik Visual Langsung
- **Live Preview**: Editor kiri, preview kanan
- **Auto-update**: Debounce 300-500ms, tanpa tombol kirim
- **Status Panel**: "Lengkapi kode" / "Perbaiki kode" / "Kode benar"
- **Checker Client-side**: Validasi instan tanpa reload

### 3. Unsur Game
- **Buka-kunci Berurutan**: Soal → Materi → Modul
- **Tipe Soal Bervariasi**:
  - Reguler (36 soal)
  - Bug Hunt (2 soal) - cari dan perbaiki bug
  - Clone Challenge (2 soal) - lihat target, buat dari nol
- **Autosave**: Code tersimpan otomatis per soal
- **Activity Logging**: Durasi & progres tercatat

## 📚 Struktur Konten

### Modul 1: HTML (190 XP)
1. **Struktur Dasar HTML** (5 soal)
2. **HTML Lanjut — Link, Frame, Tabel** (5 soal)
3. **HTML Interaktif — Form & Media** (5 soal, termasuk 1 Bug Hunt)

### Modul 2: CSS (155 XP)
4. **Dasar CSS — Selector & Properti** (5 soal)
5. **Layout & Interaksi CSS — Flexbox & Hover** (5 soal, termasuk 1 Bug Hunt)

### Modul 3: JavaScript (240 XP)
6. **Dasar JavaScript — Variabel & Output** (5 soal)
7. **Event Handling** (5 soal, termasuk 1 Clone Challenge)
8. **Manipulasi DOM & Logika Sederhana** (5 soal, termasuk 1 Clone Challenge)

**Total**: 3 modul, 8 materi, 40 soal

## 🛠️ Teknologi

- **Backend**: Laravel 12
- **Frontend**: Blade templates, Vanilla JavaScript
- **Database**: MySQL (SQLite untuk development)
- **Authentication**: Laravel Breeze
- **Build**: Vite

## 🚀 Instalasi

### Prerequisites
- PHP >= 8.2
- Composer
- Node.js & npm
- MySQL (optional, SQLite default)

### Setup

```bash
# Clone repository
cd laravel-app

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Database setup
php artisan migrate:fresh --seed

# Build assets
npm run build

# Start server
php artisan serve
```

Akses: `http://localhost:8000`

## 📁 Struktur Database

```
users (nickname, participant_code, password, consent_at)
  └─ user_progress (track soal selesai, autosave code)
  └─ user_badges (badge yang sudah didapat)
  └─ user_costumes (kostum yang di-unlock & aktif)
  └─ activity_logs (login, buka_soal, check_code, selesai)

modules (3: HTML, CSS, JavaScript)
  └─ materials (8 materi)
      └─ questions (40 soal dengan tipe & XP)

badges (4 badge)
costumes (6 kostum)
```

## 🎯 Alur Penggunaan

1. **Register/Login** dengan nickname & participant_code
2. **Consent** (centang persetujuan responden)
3. **Dashboard** - lihat progres, XP, badge, leaderboard
4. **Pilih Modul** → **Pilih Materi** → **Kerjakan Soal**
5. **Editor** (kiri) + **Live Preview** (kanan)
6. **Auto-check** → Status langsung muncul
7. **Selesai** → XP bertambah, soal berikutnya terbuka
8. **Unlock Badge** setelah modul selesai
9. **Kostumisasi Avatar** dengan kostum yang ter-unlock

## 📊 Data Penelitian

Data untuk analisis skripsi:
- **Angket**: Google Form terpisah (pakai `participant_code` untuk mapping)
- **Aktivitas**: Tercatat di `activity_logs` (durasi, jumlah soal, login)
- **Progres**: Di `user_progress` (XP, badge, waktu penyelesaian)

## 🔐 Security

- **Buka-kunci di Server**: Middleware/policy untuk akses soal
- **XP Sekali Saja**: Validasi backend, tidak bisa cheat
- **Badge Auto**: Trigger saat modul selesai
- **Autosave**: Per soal, per user
- **Activity Log**: Semua event tercatat dengan timestamp

## 📝 Development Notes

- Prototipe statis ada di folder `../Prototipe` (referensi)
- Checker JavaScript: akan dibuat di `public/checkers/` (40 files)
- Test suite: akan dibuat untuk memastikan semua fitur sesuai prototipe
- Deployment: HTTPS wajib untuk responden

## 📖 Dokumentasi

- `SETUP_LOG.md` - Log setup & progress
- `PROJECT_CONTEXT.md` (di folder Prototipe) - Konteks lengkap proyek
- `Daftar Modul, Materi, dan Soal.md` - Spesifikasi konten detail

## 👤 Author

Mahasiswa PTIK UNM Semester 5  
Skripsi: Penelitian R&D Model ADDIE

---

**Status**: Tahap 1 selesai (Setup Laravel + Database) ✅  
**Next**: Tahap 2 (Models & Relationships)
