# 🧪 Forgot Password Feature - Quick Testing Guide

## 🚀 Quick Start

### 1. Start Laravel Server
```bash
cd laravel-app
php artisan serve
```

Server akan jalan di: `http://localhost:8000`

---

## 📝 Test Scenarios

### **Scenario 1: User Lupa Password**

**Step 1:** Buka halaman login
```
http://localhost:8000/login
```

**Step 2:** Klik link **"Lupa password?"** di bawah form

**Step 3:** Di halaman forgot password:
- Input kode peserta yang valid (misal: `CT-001`)
- Klik "KIRIM LINK RESET"
- ✅ Harus muncul toast success

**Step 4:** Sekarang sebagai admin, generate reset link...

---

### **Scenario 2: Admin Generate Reset Link**

**Step 1:** Buka admin tool
```
http://localhost:8000/admin/password-reset
```

**Step 2:** Input kode peserta
- Masukkan kode peserta yang sama (misal: `CT-001`)
- Klik "GENERATE LINK"
- ✅ Harus muncul box hijau dengan reset URL

**Step 3:** Copy reset link
- Klik tombol "📋 COPY LINK"
- ✅ Toast "Link reset telah disalin"

**Step 4:** Paste link di browser baru (atau incognito)

---

### **Scenario 3: Reset Password**

**Step 1:** Buka reset link yang dicopy dari admin tool
```
http://localhost:8000/reset-password/[TOKEN]?participant_code=CT-001
```

**Step 2:** Di form reset password:
- Cek kode peserta sudah pre-filled
- Input password baru (misal: `newpassword123`)
- Konfirmasi password (ketik ulang yang sama)
- Klik "RESET PASSWORD"

**Step 3:** Verifikasi redirect
- ✅ Harus redirect ke `/login`
- ✅ Toast success: "Password berhasil direset!"

**Step 4:** Login dengan password baru
- Input kode peserta: `CT-001`
- Input password baru: `newpassword123`
- ✅ Harus berhasil masuk dashboard

---

## 🔍 Database Check

### Cek password_reset_tokens table:

**Sebelum generate link:**
```sql
SELECT * FROM password_reset_tokens;
-- Harus kosong atau tidak ada record untuk CT-001
```

**Setelah generate link:**
```sql
SELECT * FROM password_reset_tokens WHERE participant_code = 'CT-001';
-- Harus ada 1 record dengan token (hashed)
```

**Setelah reset berhasil:**
```sql
SELECT * FROM password_reset_tokens WHERE participant_code = 'CT-001';
-- Harus kosong (token dihapus setelah dipakai)
```

### Cek user password berubah:

```sql
SELECT participant_code, password FROM users WHERE participant_code = 'CT-001';
-- Password hash harus berbeda dari sebelumnya
```

---

## ❌ Error Testing

### Test 1: Kode Peserta Tidak Ditemukan
1. Input kode peserta yang tidak exist: `CT-999`
2. ✅ Expected: Error "Kode peserta tidak ditemukan"

### Test 2: Password Tidak Match
1. Buka reset link yang valid
2. Password: `newpass123`
3. Konfirmasi: `different456`
4. ✅ Expected: Border merah pada konfirmasi password

### Test 3: Password Terlalu Pendek
1. Input password: `123` (< 8 chars)
2. ✅ Expected: Validation error

### Test 4: Token Expired (Manual Test)
1. Generate link via admin
2. Di database, ubah `created_at` jadi 2 jam yang lalu:
   ```sql
   UPDATE password_reset_tokens 
   SET created_at = DATE_SUB(NOW(), INTERVAL 2 HOUR) 
   WHERE participant_code = 'CT-001';
   ```
3. Coba reset dengan link tersebut
4. ✅ Expected: Error "Token sudah kadaluarsa"

### Test 5: Token Already Used
1. Generate link → Reset password (berhasil)
2. Coba akses link yang sama lagi
3. ✅ Expected: Error "Token tidak valid"

---

## 📱 Mobile Testing

### Chrome DevTools:
1. Press `F12`
2. Klik icon device toolbar (atau `Ctrl+Shift+M`)
3. Pilih device: "iPhone 12 Pro"
4. Test semua halaman:
   - `/login` (cek link "Lupa password?" visible)
   - `/forgot-password` (form responsive)
   - `/reset-password/[token]` (form responsive)
   - `/admin/password-reset` (admin tool responsive)

✅ **Checklist:**
- [ ] Text readable tanpa zoom
- [ ] Button mudah di-tap (min 44px)
- [ ] Toast muncul dengan baik
- [ ] Loading states bekerja
- [ ] Form input tidak terlalu kecil

---

## 🎨 Visual Checklist

### Login Page:
- [ ] Link "Lupa password?" muncul di bawah checkbox "Ingat saya"
- [ ] Link berwarna cyan
- [ ] Hover berubah jadi yellow

### Forgot Password Page:
- [ ] Logo CodeTide centered
- [ ] Title "LUPA PASSWORD" dengan font pixel
- [ ] Info box biru dengan petunjuk
- [ ] Input kode peserta dengan placeholder "CT-001"
- [ ] Button kuning "KIRIM LINK RESET"
- [ ] Floating particles background
- [ ] Toast notification muncul saat submit

### Reset Password Page:
- [ ] Info box hijau dengan icon 🔐
- [ ] Input kode peserta (pre-filled dari URL)
- [ ] Input password baru
- [ ] Input konfirmasi password
- [ ] Password requirements box (cyan border)
- [ ] Button kuning "RESET PASSWORD"
- [ ] Real-time validation (border merah jika tidak match)

### Admin Tool Page:
- [ ] Warning box kuning di atas
- [ ] Form generate link
- [ ] Result box hijau saat berhasil
- [ ] Reset URL dengan monospace font
- [ ] Button cyan "COPY LINK"
- [ ] Petunjuk penggunaan

---

## 🐛 Common Issues

### Issue 1: Routes not found (404)
**Fix:**
```bash
php artisan route:cache
php artisan config:cache
```

### Issue 2: Token validation gagal
**Debug:**
```sql
SELECT * FROM password_reset_tokens;
-- Cek format token, created_at, participant_code
```

### Issue 3: Password tidak berubah
**Debug:**
- Check user table: `SELECT * FROM users WHERE participant_code = 'CT-001';`
- Verify password hash berubah setelah reset

### Issue 4: Toast tidak muncul
**Fix:**
- Clear browser cache (`Ctrl+Shift+R`)
- Check console error (`F12` → Console tab)
- Verify `toast.js` loaded: Network tab cari `toast.js`

---

## ✅ Success Criteria

Feature dianggap berhasil jika:
1. ✅ User bisa request reset password
2. ✅ Admin bisa generate reset link
3. ✅ Reset link berfungsi dengan baik
4. ✅ Token expire setelah 60 menit
5. ✅ Token dihapus setelah dipakai
6. ✅ Password berhasil diubah
7. ✅ User bisa login dengan password baru
8. ✅ Toast notifications bekerja
9. ✅ Loading states bekerja
10. ✅ Mobile responsive sempurna
11. ✅ Error handling proper
12. ✅ Design consistent dengan CodeTide theme

---

## 📸 Screenshot Checklist untuk Dosen

Ambil screenshot dari:
1. [ ] Login page dengan link "Lupa password?"
2. [ ] Forgot password form (kosong)
3. [ ] Forgot password form (after submit - toast success)
4. [ ] Admin reset link generator (kosong)
5. [ ] Admin reset link result (dengan URL dan copy button)
6. [ ] Reset password form
7. [ ] Success toast after password reset
8. [ ] Login dengan password baru (berhasil masuk)
9. [ ] Mobile view - forgot password page
10. [ ] Mobile view - reset password page

---

**Happy Testing! 🚀**

Jika ada bug atau issue, check error log:
```bash
tail -f laravel-app/storage/logs/laravel.log
```
