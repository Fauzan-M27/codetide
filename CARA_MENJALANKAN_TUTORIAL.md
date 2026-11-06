# 🚀 Cara Menjalankan Fitur Tutorial

## Persiapan

### 1. Install Dependencies
```bash
npm install
```

### 2. Jalankan Migration
Pastikan database MySQL Anda sudah berjalan, kemudian:
```bash
php artisan migrate
```

### 3. Build Assets
```bash
# Untuk production
npm run build

# ATAU untuk development (dengan auto-reload)
npm run dev
```

## Menjalankan Tutorial

### Otomatis (Untuk Pengguna Baru)
Tutorial akan otomatis muncul saat pengguna baru login pertama kali ke dashboard.

### Manual (Mengulang Tutorial)
Ada 2 cara:

#### Cara 1: Dari Halaman Profile
1. Login ke aplikasi
2. Klik menu "Profil" di navbar
3. Scroll ke bagian "Quick Actions"
4. Klik tombol "Ulangi Tutorial"
5. Konfirmasi dengan klik "OK"
6. Anda akan dialihkan ke dashboard dan tutorial akan dimulai

#### Cara 2: Developer Console
1. Buka browser console (F12)
2. Ketik: `window.restartTutorial()`
3. Tekan Enter

## Testing Tutorial

### Reset Tutorial Status di Database
Jika ingin test dari awal, reset status tutorial user:

```sql
-- Reset semua user
UPDATE users SET tutorial_completed = 0;

-- Atau reset user tertentu
UPDATE users SET tutorial_completed = 0 WHERE email = 'user@example.com';
```

### Clear LocalStorage
Buka browser console dan ketik:
```javascript
localStorage.removeItem('codetide_tutorial_completed');
localStorage.removeItem('codetide_tutorial_step');
```

Kemudian refresh halaman.

## Troubleshooting

### Problem: Tutorial tidak muncul

**Solusi 1: Cek status di database**
```sql
SELECT nickname, tutorial_completed FROM users WHERE email = 'your@email.com';
```
Pastikan `tutorial_completed` = 0

**Solusi 2: Clear browser cache**
- Chrome: Ctrl + Shift + Delete
- Firefox: Ctrl + Shift + Delete

**Solusi 3: Rebuild assets**
```bash
npm run build
```

### Problem: Error "shepherd is not defined"

**Solusi:** Pastikan dependencies sudah terinstall
```bash
npm install shepherd.js --save
npm run build
```

### Problem: Styling tidak muncul

**Solusi 1:** Cek apakah vite sudah running
```bash
npm run dev
```

**Solusi 2:** Clear browser cache dan hard reload
- Windows: Ctrl + Shift + R
- Mac: Cmd + Shift + R

### Problem: API error saat complete tutorial

**Solusi 1:** Cek CSRF token
Pastikan ada di HTML head:
```html
<meta name="csrf-token" content="{{ csrf_token() }}">
```

**Solusi 2:** Cek routes
```bash
php artisan route:list | grep tutorial
```

Harus ada 3 routes:
- POST /api/tutorial/complete
- POST /api/tutorial/reset
- GET /api/tutorial/status

## Development Tips

### Melihat Tutorial di Semua Halaman
Edit `tutorial.js`, uncomment bagian ini untuk force show:
```javascript
shouldShowTutorial() {
    return true; // Force show untuk testing
}
```

### Skip Auto-start Tutorial
Jika ingin disable auto-start sementara:
```javascript
// Tambahkan di browser console
localStorage.setItem('codetide_tutorial_completed', 'true');
```

### Test di Mobile
1. Buka Chrome DevTools (F12)
2. Click icon device toolbar (Ctrl + Shift + M)
3. Pilih device (iPhone, iPad, etc.)
4. Refresh dan test tutorial

## Checklist Sebelum Deploy

- [ ] Database migration sudah dijalankan
- [ ] Assets sudah di-build (`npm run build`)
- [ ] Tutorial muncul untuk user baru
- [ ] Button "Ulangi Tutorial" berfungsi di profile
- [ ] Tutorial responsive di mobile
- [ ] Tidak ada error di console
- [ ] CSRF token configured
- [ ] Routes terdaftar dengan benar

## Selamat Menggunakan! 🎉

Jika ada pertanyaan atau issue, silakan cek file `TUTORIAL_FEATURE.md` untuk dokumentasi lengkap.
