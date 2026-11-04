# 📧 Email Setup Guide - CodeTide Password Reset

## 🎯 3 Pilihan Setup Email

### **Option 1: MAILTRAP (Recommended untuk Testing)** ⭐

**Kelebihan:**
- ✅ Gratis
- ✅ Mudah setup (5 menit)
- ✅ Perfect untuk development/testing
- ✅ Tidak perlu email real
- ✅ Dashboard untuk lihat email yang "terkirim"

**Kekurangan:**
- ❌ Email tidak benar-benar sampai ke inbox user
- ❌ Hanya untuk testing, bukan production

**Setup Steps:**

1. **Daftar Mailtrap:**
   - Buka: https://mailtrap.io
   - Klik "Sign Up" - pilih "Email Testing"
   - Daftar dengan Google/GitHub atau email

2. **Get SMTP Credentials:**
   - Login ke Mailtrap
   - Go to: **Email Testing** → **Inboxes** → **My Inbox**
   - Klik tab **"SMTP Settings"**
   - Pilih **"Laravel 9+"**
   - Copy credentials yang muncul

3. **Update `.env` file:**
   ```env
   MAIL_MAILER=smtp
   MAIL_HOST=sandbox.smtp.mailtrap.io
   MAIL_PORT=2525
   MAIL_USERNAME=your_mailtrap_username_here
   MAIL_PASSWORD=your_mailtrap_password_here
   MAIL_ENCRYPTION=null
   MAIL_FROM_ADDRESS="noreply@codetide.com"
   MAIL_FROM_NAME="CodeTide"
   ```

4. **Clear cache:**
   ```bash
   php artisan config:cache
   ```

5. **Test:**
   - Buka: http://localhost:8000/forgot-password
   - Input email yang valid
   - Submit
   - Cek **Mailtrap Dashboard** → inbox → email masuk!

---

### **Option 2: GMAIL (Production - Kirim Email Real)** 📨

**Kelebihan:**
- ✅ Email benar-benar terkirim ke inbox
- ✅ Gratis (Gmail)
- ✅ Reliable

**Kekurangan:**
- ⚠️ Perlu setup App Password
- ⚠️ Bisa kena spam filter
- ⚠️ Ada limit 500 email/day

**Setup Steps:**

1. **Enable 2-Factor Authentication di Gmail:**
   - Buka: https://myaccount.google.com/security
   - Enable **2-Step Verification**

2. **Generate App Password:**
   - Buka: https://myaccount.google.com/apppasswords
   - App name: "CodeTide Laravel"
   - Click "Generate"
   - **Copy 16-digit password** (contoh: `abcd efgh ijkl mnop`)

3. **Update `.env` file:**
   ```env
   MAIL_MAILER=smtp
   MAIL_HOST=smtp.gmail.com
   MAIL_PORT=587
   MAIL_USERNAME=your-email@gmail.com
   MAIL_PASSWORD=abcdefghijklmnop
   MAIL_ENCRYPTION=tls
   MAIL_FROM_ADDRESS="noreply@codetide.com"
   MAIL_FROM_NAME="CodeTide"
   ```

4. **Clear cache:**
   ```bash
   php artisan config:cache
   ```

5. **Test:**
   - Forgot password → Input email real
   - Cek inbox/spam folder Gmail
   - Email reset password masuk!

**Troubleshooting Gmail:**
- ❌ Error "Username and Password not accepted"
  - Pastikan 2FA enabled
  - Pastikan pakai App Password (bukan password biasa)
  - Hapus spasi di App Password

- ❌ Email masuk ke spam
  - Normal untuk development
  - Di production, pakai domain email sendiri (bukan gmail)

---

### **Option 3: ADMIN TOOL (Paling Praktis untuk 50 Users!)** 🚀

**Kelebihan:**
- ✅ Tidak perlu setup email sama sekali
- ✅ Instant - langsung generate link
- ✅ Perfect untuk skala kecil (50 users)
- ✅ Admin punya kontrol penuh

**Kekurangan:**
- ❌ Manual work untuk admin
- ❌ Tidak scalable untuk ratusan users

**How to Use:**

1. **User lupa password:**
   - User buka: `/forgot-password`
   - Input email
   - Submit

2. **System show message:**
   ```
   "Email belum dikonfigurasi. 
    Silakan gunakan Admin Tool untuk generate reset link."
   
   → Buka Admin Tool
   ```

3. **Admin generate link:**
   - Admin buka: http://localhost:8000/admin/password-reset
   - Input email user: `user@example.com`
   - Click "GENERATE LINK"
   - Copy reset URL yang muncul

4. **Admin kirim link ke user:**
   - Via WhatsApp/Telegram/Email manual
   - User klik link → Reset password → Done!

**Workflow:**
```
User Request (via /forgot-password)
        ↓
Admin buka /admin/password-reset
        ↓
Generate link untuk email user
        ↓
Copy & kirim link manual (WA/Telegram)
        ↓
User reset password via link
        ↓
Done! ✅
```

---

## 🧪 Testing Each Option:

### **Test Mailtrap:**
```bash
# 1. Setup .env dengan Mailtrap credentials
# 2. Clear cache
php artisan config:cache

# 3. Test
# Buka: /forgot-password
# Input: testuser@example.com
# Submit

# 4. Check Mailtrap dashboard
# Email muncul dengan subject: "Reset Password Notification"
# Click link di email → Reset password page opens
```

### **Test Gmail:**
```bash
# 1. Setup .env dengan Gmail credentials
# 2. Clear cache
php artisan config:cache

# 3. Test
# Buka: /forgot-password
# Input: your-real-email@gmail.com
# Submit

# 4. Check Gmail inbox/spam
# Email dari "CodeTide" muncul
# Click link → Reset password
```

### **Test Admin Tool:**
```bash
# 1. Keep MAIL_MAILER=log (no email setup)
# 2. User request: /forgot-password
# 3. System show: "Gunakan Admin Tool"
# 4. Admin: /admin/password-reset
# 5. Generate link → Copy → Send manual
```

---

## 🔍 Debugging Email Issues:

### **Check Laravel Logs:**
```bash
# Check if email was attempted
tail -f storage/logs/laravel.log
```

### **Check Config:**
```bash
php artisan config:show mail

# Should show your SMTP settings
# If not, run: php artisan config:cache
```

### **Test Email Sending:**
```bash
php artisan tinker

# Then in tinker:
Mail::raw('Test email from CodeTide', function ($message) {
    $message->to('your-email@example.com')
            ->subject('Test Email');
});

# Check if email sent successfully
# Error? Check logs and credentials
```

### **Common Errors:**

**1. "Connection refused"**
```
Fix: Check MAIL_HOST and MAIL_PORT
     Firewall might be blocking port 587/2525
```

**2. "Username and Password not accepted"**
```
Fix: For Gmail - use App Password (not regular password)
     For Mailtrap - copy exact credentials from dashboard
```

**3. "Email not received"**
```
Fix: Check spam folder
     Check logs: storage/logs/laravel.log
     Verify email exists in database: SELECT * FROM users WHERE email = 'xxx';
```

---

## 📊 Comparison Table:

| Feature | Mailtrap | Gmail | Admin Tool |
|---------|----------|-------|------------|
| **Setup Time** | 5 mins | 10 mins | 0 mins (sudah ada) |
| **Cost** | Free | Free | Free |
| **Email Real?** | ❌ No (test) | ✅ Yes | ❌ Manual link |
| **Good for** | Development | Production (small) | Development/Small scale |
| **Scalability** | Testing only | 500/day limit | Manual (not scalable) |
| **Recommended for CodeTide?** | ✅ Yes (testing) | ⚠️ Maybe (50 users) | ✅ **YES** (most practical) |

---

## 💡 **Recommendation untuk CodeTide (50 Users):**

Pakai **Admin Tool** dulu karena:
1. ✅ No setup needed
2. ✅ Instant - generate link langsung
3. ✅ Admin punya kontrol
4. ✅ 50 users = manageable manual work
5. ✅ Tidak perlu worry tentang email deliverability

**Future:** Kalau udah 100+ users atau mau otomatis, baru setup Gmail/SMTP.

---

## ✅ Quick Setup Summary:

### **For Testing (Mailtrap):**
```bash
# 1. Sign up: https://mailtrap.io
# 2. Copy credentials from dashboard
# 3. Edit .env:
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password

# 4. Cache
php artisan config:cache

# 5. Test /forgot-password
```

### **For Production (Gmail):**
```bash
# 1. Enable 2FA on Gmail
# 2. Generate App Password
# 3. Edit .env:
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls

# 4. Cache
php artisan config:cache

# 5. Test with real email
```

### **For Simplicity (Admin Tool):**
```bash
# 1. Keep MAIL_MAILER=log
# 2. User: /forgot-password
# 3. Admin: /admin/password-reset
# 4. Generate → Copy → Send
# Done! ✅
```

---

**Need Help?** Check Laravel logs: `storage/logs/laravel.log`
