# CodeTide - Laravel Migration Setup Log

## Tahap 1: Setup Project Laravel + Database ✅

**Tanggal:** 17 Oktober 2026

### Yang Telah Dikerjakan

#### 1. Instalasi Laravel
- ✅ Laravel v12.12.2 terinstall
- ✅ Laravel Breeze v2.4.2 untuk authentication
- ✅ Blade templates (dark mode)
- ✅ Node dependencies & Vite build

#### 2. Database Structure
Database menggunakan SQLite untuk development (bisa dipindah ke MySQL nanti).

**Tabel yang dibuat:**

1. **users**
   - nickname (unique)
   - participant_code (unique) ← untuk mapping dengan Google Form
   - password
   - consent_at (persetujuan responden)

2. **modules** (3 rows)
   - HTML, CSS, JavaScript
   - badge_name per modul

3. **materials** (8 rows)
   - 3 materi untuk HTML
   - 2 materi untuk CSS  
   - 3 materi untuk JavaScript

4. **questions** (40 rows)
   - 36 soal reguler
   - 2 soal bug_hunt (M1 Materi 3 Level 5, M2 Materi 2 Level 5)
   - 2 soal clone (M3 Materi 2 Level 5, M3 Materi 3 Level 5)
   - XP bervariasi: 5-25 per soal (total 585 XP)

5. **user_progress**
   - Track soal yang sudah diselesaikan
   - Autosave code
   - Attempts counter

6. **badges** (4 rows)
   - HTML Explorer
   - CSS Stylist
   - JS Interactor
   - Web Developer Pemula (badge akhir)

7. **user_badges**
   - Relasi many-to-many users ↔ badges

8. **costumes** (6 rows)
   - 2 default (unlocked dari awal)
   - 2 badge-locked
   - 1 XP-locked (300 XP)
   - 1 module-locked

9. **user_costumes**
   - Relasi many-to-many users ↔ costumes
   - is_active flag untuk kostum yang sedang dipakai

10. **activity_logs**
    - Event: login, open_question, check_code, complete_question
    - Meta JSON untuk durasi, dll

#### 3. Seeder Data
Semua data konten sudah di-seed:
- ✅ 3 modul dengan deskripsi
- ✅ 8 materi sesuai dokumen "Daftar Modul, Materi, dan Soal"
- ✅ 40 soal dengan instruksi, starter code, dan XP reward
- ✅ 4 badge dengan requirement
- ✅ 6 kostum dengan unlock requirement

#### 4. File Structure
```
laravel-app/
├── database/
│   ├── migrations/
│   │   ├── 0001_01_01_000000_create_users_table.php (modified)
│   │   ├── 2026_10_17_100102_create_modules_table.php
│   │   ├── 2026_10_17_100148_create_materials_table.php
│   │   ├── 2026_10_17_100209_create_questions_table.php
│   │   ├── 2026_10_17_100225_create_user_progress_table.php
│   │   ├── 2026_10_17_100242_create_badges_table.php
│   │   ├── 2026_10_17_100251_create_user_badges_table.php
│   │   ├── 2026_10_17_100303_create_costumes_table.php
│   │   ├── 2026_10_17_100312_create_user_costumes_table.php
│   │   └── 2026_10_17_100322_create_activity_logs_table.php
│   └── seeders/
│       ├── ModuleSeeder.php
│       ├── MaterialSeeder.php
│       ├── QuestionSeeder.php
│       ├── BadgeSeeder.php
│       ├── CostumeSeeder.php
│       └── DatabaseSeeder.php (updated)
├── .env (configured for CodeTide)
└── SETUP_LOG.md (this file)
```

### Catatan Penting

1. **Perbedaan dengan Prototipe**
   - Prototipe lama: 8 modul, 19 level, 5 soal/level = 95 soal
   - Versi final: 3 modul, 8 materi, 40 soal total
   - Ini sesuai dokumen "Daftar Modul, Materi, dan Soal" yang jadi acuan final

2. **Tipe Soal Khusus**
   - Bug Hunt: M1 Materi 3 Level 5, M2 Materi 2 Level 5
   - Clone: M3 Materi 2 Level 5, M3 Materi 3 Level 5
   - Time Mode: ditunda (belum ada di struktur sekarang)

3. **Authentication**
   - Pakai nickname (bukan email)
   - Pakai participant_code (unique string untuk mapping Google Form)
   - Tidak ada role (semua user = mahasiswa)

4. **Database**
   - Development: SQLite (file: database/database.sqlite)
   - Production: MySQL (tinggal update .env)
   - Struktur dirancang kompatibel dengan kedua engine

### Yang Belum Dikerjakan (Tahap Berikutnya)

- [x] Models & Relationships (Module, Material, Question, dll)
- [x] Controllers untuk CRUD operations
- [x] Routes & Middleware untuk buka-kunci berurutan
- [ ] Blade views untuk halaman-halaman
- [ ] Checker JavaScript files (40 files untuk validasi soal)
- [ ] Live preview panel & debounce editor
- [ ] XP & Badge logic di backend (sudah ada di Model, perlu ditest)
- [ ] Activity logging system (sudah ada, perlu ditest)
- [ ] Leaderboard query & display (sudah ada controller)
- [ ] Kostumisasi avatar (sudah ada controller)
- [ ] Migration data prototipe (Modul 1 yang sudah ada)
- [ ] Testing end-to-end
- [ ] Deployment setup

### Cara Menjalankan

```bash
# Development server
php artisan serve

# Run migration fresh
php artisan migrate:fresh

# Seed data
php artisan db:seed

# Reset & seed ulang
php artisan migrate:fresh --seed
```

### Next Steps

**Tahap 2: Models & Relationships** ✅ SELESAI

Semua model sudah dibuat dengan relationships lengkap:
- Module, Material, Question, UserProgress, Badge, Costume, ActivityLog
- User model dengan methods XP, badges, costumes, unlock logic
- Tested dan verified ✅

**Tahap 3: Controllers & Routes** ✅ SELESAI

7 Controllers dibuat:
- DashboardController (dashboard dengan stats)
- ModuleController (list & detail modul)
- MaterialController (list soal per materi)
- QuestionController (halaman editor soal)
- ProgressController (autosave, check, complete)
- LeaderboardController (ranking XP)
- CostumeController (kostumisasi avatar)

33 Routes terdaftar:
- Dashboard, Modules, Materials, Questions
- Progress API (autosave, check, complete)
- Leaderboard
- Costumes
- Auth routes (Breeze)

**Tahap 4: Views & Frontend** (BELUM)
- Blade templates
- Live preview editor dengan debounce
- Checker JavaScript (40 files)
- Styling CSS

Konfirmasi sebelum lanjut ke Tahap 4!
