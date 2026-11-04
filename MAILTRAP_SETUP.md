# 🚀 Mailtrap Setup - Quick Guide

## ✅ Step-by-Step Setup

### **Step 1: Sign Up Mailtrap** (2 minutes)

1. Open: **https://mailtrap.io**
2. Click **"Sign Up"** (top right)
3. Choose **"Email Testing"** (Free plan)
4. Sign up with:
   - Google (fastest)
   - GitHub
   - Or email + password

### **Step 2: Get SMTP Credentials** (1 minute)

After login:

1. Sidebar → **"Email Testing"**
2. **"Inboxes"** → **"My Inbox"**
3. Click tab **"SMTP Settings"**
4. Dropdown **"Integrations"** → Select **"Laravel 9+"**
5. You'll see credentials like:

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=1234567890abcd
MAIL_PASSWORD=1234567890abcd
MAIL_ENCRYPTION=null
```

**COPY** these credentials!

### **Step 3: Update Laravel .env** (30 seconds)

1. Open file: `laravel-app/.env`
2. Find the **MAIL_** section
3. Replace with your Mailtrap credentials:

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=paste_your_username_here
MAIL_PASSWORD=paste_your_password_here
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@codetide.com"
MAIL_FROM_NAME="CodeTide"
```

**IMPORTANT:** Replace `paste_your_username_here` and `paste_your_password_here` with your actual Mailtrap credentials!

### **Step 4: Clear Laravel Cache** (10 seconds)

Open terminal di folder `laravel-app`:

```bash
php artisan config:cache
```

Output: `Configuration cached successfully.`

### **Step 5: Test!** (1 minute)

1. **Start server** (if not running):
   ```bash
   php artisan serve
   ```

2. **Open browser**: `http://localhost:8000/forgot-password`

3. **Input email** (any valid email from registered user)

4. **Submit**

5. **Check Mailtrap dashboard**:
   - Go to: Mailtrap → Inboxes → My Inbox
   - You should see **1 new email**!
   - Subject: "Reset Password Notification"
   - Open email → Click the reset link
   - Should open reset password page ✅

---

## 🧪 Testing Workflow

### **Complete Test:**

```
1. Register new user:
   URL: http://localhost:8000/register
   - Nickname: TestMailtrap
   - Email: testmailtrap@example.com
   - Password: password123
   
2. Logout

3. Forgot password:
   URL: http://localhost:8000/forgot-password
   - Input: testmailtrap@example.com
   - Submit
   
4. Check Mailtrap Inbox:
   - 1 new email appears
   - Subject: "Reset Password Notification"
   - From: CodeTide <noreply@codetide.com>
   
5. Click reset link in email:
   - Opens: http://localhost:8000/reset-password/{token}?email=...
   - Email pre-filled
   
6. Reset password:
   - New password: newpassword123
   - Confirm: newpassword123
   - Submit
   
7. Login with new password:
   - Nickname: TestMailtrap
   - Password: newpassword123
   - Success! ✅
```

---

## ✅ Verification Checklist

After setup, verify:

- [x] Mailtrap account created
- [x] SMTP credentials copied
- [x] `.env` file updated with credentials
- [x] Config cached (`php artisan config:cache`)
- [x] Forgot password → Email appears in Mailtrap
- [x] Reset link works
- [x] Password successfully reset

---

## 🐛 Troubleshooting

### **Issue 1: Email not appearing in Mailtrap**

**Check:**
```bash
# 1. Verify config
php artisan config:show mail.mailers.smtp

# Should show:
# host => "sandbox.smtp.mailtrap.io"
# username => "your_username"

# 2. Check logs
tail -f storage/logs/laravel.log

# Look for errors related to mail
```

**Fix:**
- Re-check credentials in `.env`
- Run `php artisan config:cache` again
- Restart Laravel server

### **Issue 2: "Connection refused"**

**Possible causes:**
- Firewall blocking port 2525
- Wrong MAIL_HOST

**Fix:**
```env
# Make sure in .env:
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_ENCRYPTION=null  # NOT tls or ssl!
```

### **Issue 3: "Authentication failed"**

**Possible causes:**
- Wrong username/password
- Copy-paste error (extra spaces)

**Fix:**
- Re-copy credentials from Mailtrap (fresh)
- Check no extra spaces: `MAIL_USERNAME=abc123` (no spaces)
- Use the **Laravel 9+** integration (not others)

### **Issue 4: Still shows "Email belum dikonfigurasi"**

**Fix:**
```bash
# Clear all caches
php artisan config:cache
php artisan cache:clear
php artisan route:cache

# Restart server
php artisan serve
```

---

## 📊 Mailtrap Dashboard Features

### **What you can see in Mailtrap:**

1. **Inbox** - All emails sent from Laravel
2. **Email preview** - HTML & plain text versions
3. **Email source** - Raw email headers
4. **Spam analysis** - Check if email would go to spam
5. **HTML validation** - Check HTML errors
6. **Preview in clients** - See how email looks in Gmail, Outlook, etc.

### **Useful for debugging:**
- Check email formatting
- Verify reset link format
- Test email deliverability
- No risk of spamming real users

---

## 🎓 For Presentation

**Benefits to mention:**
1. ✅ **Secure** - Professional email reset (not manual admin links)
2. ✅ **Automated** - User receives email instantly
3. ✅ **Testing-safe** - No real emails sent during development
4. ✅ **Standard Laravel flow** - Best practice implementation

**Demo flow:**
```
1. Show register form (with email)
2. Show forgot password form
3. Submit → Show Mailtrap inbox (email received)
4. Click link in email → Reset password page
5. Reset → Login with new password
6. Explain: "This is testing env, production will use real SMTP"
```

---

## 🚀 Future: Switch to Gmail (Production)

When ready for production:

1. Get Gmail App Password
2. Update `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-16-digit-app-password
MAIL_ENCRYPTION=tls
```

3. Cache: `php artisan config:cache`
4. Done! Real emails will be sent ✅

**No code changes needed** - just credentials swap!

---

## ✅ Summary

| Step | Time | Status |
|------|------|--------|
| Sign up Mailtrap | 2 min | □ |
| Copy credentials | 1 min | □ |
| Update .env | 30 sec | □ |
| Clear cache | 10 sec | □ |
| Test forgot password | 1 min | □ |
| **Total** | **~5 minutes** | |

**After setup:** Forgot password feature fully functional with professional email delivery! 🎉

---

**Need help?** Check Laravel logs: `storage/logs/laravel.log`
