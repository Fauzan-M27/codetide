# CodeTide Project - Context Prompt untuk AI Assistant

> **Gunakan prompt ini saat melanjutkan proyek CodeTide dengan AI Assistant**  
> **Last Updated:** November 1, 2026  
> **Project Status:** 100% Complete & Production Ready

---

## 📋 Project Overview

**CodeTide** adalah platform pembelajaran web development berbasis gamifikasi yang mengajarkan HTML, CSS, dan JavaScript melalui 40 soal interaktif dengan sistem XP, level, badges, dan costumes.

### Quick Facts
- **Tech Stack:** Laravel 11.x, MySQL, Blade Templates, Vanilla JS
- **Theme:** Pixel Art / 8-bit Retro
- **Target Users:** 50 users dalam 2 minggu pertama
- **Status:** Production-ready, siap launch

---

## 🎯 Prompt untuk AI Assistant

```
Saya sedang melanjutkan proyek CodeTide, sebuah platform pembelajaran web development 
berbasis gamifikasi dengan tema pixel art. Proyek ini sudah 100% complete dan 
production-ready.

### Konteks Proyek:

**Tech Stack:**
- Backend: Laravel 11.x (PHP 8.2+)
- Frontend: Blade Templates, Vanilla JavaScript
- Database: MySQL (codetide)
- Theme: Pixel Art / 8-bit Retro
- Fonts: Press Start 2P, JetBrains Mono
- Icons: PxlKit (custom pixel art renderer)

**Fitur Utama yang Sudah Ada:**
1. Authentication (register, login, logout, forgot password via email)
2. 40 Soal Interaktif (HTML, CSS, JavaScript) dengan Monaco code editor
3. Sistem XP & Leveling dengan leaderboard real-time
4. 19 Kostum Karakter yang bisa dibeli dengan coins
5. 4 Achievement Badges (HTML Explorer, CSS Stylist, JS Interactor, Web Developer Pemula)
6. Toast Notifications dengan 4 PxlKit icons (success, error, warning, info)
7. Loading States (overlay, 4 button animation styles, autosave)
8. Custom Error Pages (404, 403, 500, 503)
9. Mobile Responsive penuh (3 breakpoints: 968px, 640px, 480px)
10. Email system dengan Gmail SMTP (asiapin20@gmail.com)

**Database Schema:**
- users (id, nickname, participant_code, email, password)
- modules (3: HTML, CSS, JavaScript)
- materials (8 total: HTML=3, CSS=2, JavaScript=3)
- questions (40 total: 5 per material)
- user_progress (answer attempts & progress tracking)
- costumes (19 unlockable character sprites)
- user_costumes (costume ownership records)
- badges (4 achievement definitions)
- user_badges (badge awards to users)

**File Structure Penting:**
- Custom CSS: /public/css/toast.css, loading.css, mobile-responsive.css
- Custom JS: /public/js/toast.js, loading.js, mobile-menu.js, pxlkit-renderer.js
- Error Pages: /resources/views/errors/404.blade.php (+ 403, 500, 503)
- Auth Pages: /resources/views/auth/* (login, register, forgot-password, reset-password)
- Documentation: EMAIL_SETUP_GUIDE.md, FORGOT_PASSWORD_TESTING.md, MAILTRAP_SETUP.md

**Email Configuration:**
- Provider: Gmail SMTP
- Email: asiapin20@gmail.com
- Password: gtmv xlmo ftyq ntls
- From: noreply@codetide.com
- Test command: php artisan test:email your-email@example.com

**Important Notes:**
- Database pernah mengalami duplikasi karena seeder dijalankan 2x
- Gunakan: php artisan migrate:fresh --seed (BUKAN php artisan db:seed saja)
- Semua auth pages sudah fix scroll issue (overflow-y: auto, bukan hidden)
- Mobile responsive sudah linked ke 13 halaman utama
- Final verification: php final_check.php (all checks passed ✅)

**Known Issues:**
- NONE (semua sudah resolved)

### Task yang Ingin Saya Kerjakan:
[JELASKAN TASK ANDA DI SINI]

Tolong bantu saya dengan:
1. Membaca konteks proyek di atas
2. Memahami struktur dan fitur yang sudah ada
3. Memberikan solusi yang konsisten dengan style dan pattern existing code
4. Menggunakan tema pixel art dan PxlKit icons untuk UI baru
5. Memastikan mobile responsive jika ada perubahan UI
6. Testing dan verifikasi setelah perubahan
```

---

## 🔍 Verification Commands

Sebelum dan sesudah melakukan perubahan, jalankan commands ini:

```bash
# 1. Check database integrity
cd laravel-app
php final_check.php

# 2. Test email system
php artisan test:email your-email@example.com

# 3. Clear cache after config changes
php artisan config:cache
php artisan route:cache

# 4. Start dev server
php artisan serve

# 5. Check routes
php artisan route:list

# 6. View logs
tail -f storage/logs/laravel.log
```

---

## 📂 Project Structure

```
CodeTide/
├── laravel-app/
│   ├── app/
│   │   ├── Http/Controllers/
│   │   │   ├── Auth/
│   │   │   │   ├── AuthenticatedSessionController.php
│   │   │   │   ├── RegisteredUserController.php
│   │   │   │   ├── PasswordResetLinkController.php
│   │   │   │   ├── NewPasswordController.php
│   │   │   │   └── AdminPasswordResetController.php
│   │   │   ├── DashboardController.php
│   │   │   ├── ModuleController.php
│   │   │   ├── MaterialController.php
│   │   │   ├── QuestionController.php
│   │   │   ├── CostumeController.php
│   │   │   ├── LeaderboardController.php
│   │   │   ├── ProfileController.php
│   │   │   └── ProgressController.php
│   │   ├── Models/
│   │   │   ├── User.php
│   │   │   ├── Module.php
│   │   │   ├── Material.php
│   │   │   ├── Question.php
│   │   │   ├── UserProgress.php
│   │   │   ├── Costume.php
│   │   │   ├── UserCostume.php
│   │   │   ├── Badge.php
│   │   │   └── UserBadge.php
│   │   └── Console/Commands/
│   │       └── TestEmail.php
│   ├── database/
│   │   ├── migrations/
│   │   └── seeders/
│   │       ├── DatabaseSeeder.php
│   │       ├── ModuleSeeder.php
│   │       ├── QuestionSeeder.php
│   │       ├── CostumeSeeder.php
│   │       └── BadgeSeeder.php
│   ├── resources/views/
│   │   ├── welcome.blade.php
│   │   ├── dashboard.blade.php
│   │   ├── auth/
│   │   │   ├── login.blade.php
│   │   │   ├── register.blade.php
│   │   │   ├── forgot-password.blade.php
│   │   │   ├── reset-password.blade.php
│   │   │   └── admin-reset-link.blade.php
│   │   ├── modules/
│   │   │   └── index.blade.php
│   │   ├── materials/
│   │   │   └── show.blade.php
│   │   ├── questions/
│   │   │   └── show.blade.php
│   │   ├── costumes/
│   │   │   └── index.blade.php
│   │   ├── leaderboard/
│   │   │   └── index.blade.php
│   │   ├── profile/
│   │   │   └── edit.blade.php
│   │   └── errors/
│   │       ├── 404.blade.php
│   │       ├── 403.blade.php
│   │       ├── 500.blade.php
│   │       └── 503.blade.php
│   ├── public/
│   │   ├── css/
│   │   │   ├── toast.css (5.5 KB)
│   │   │   ├── loading.css (11.5 KB)
│   │   │   ├── mobile-responsive.css (26.6 KB)
│   │   │   └── sprite-animator.css (2.3 KB)
│   │   └── js/
│   │       ├── toast.js (5.0 KB)
│   │       ├── loading.js (8.6 KB)
│   │       ├── mobile-menu.js (6.9 KB)
│   │       ├── pxlkit-renderer.js (27.2 KB)
│   │       ├── monaco-setup.js
│   │       └── scene3d.js
│   ├── routes/
│   │   ├── web.php
│   │   └── auth.php
│   ├── .env
│   ├── final_check.php
│   ├── EMAIL_SETUP_GUIDE.md
│   ├── FORGOT_PASSWORD_TESTING.md
│   └── MAILTRAP_SETUP.md
└── Asset Karakter/ (character sprite sheets)
```

---

## 🎨 Design System

### Colors
```css
/* Primary Colors */
--primary-color: #FF6B9D;      /* Pink */
--secondary-color: #C2FCF1;    /* Cyan */
--accent-color: #FEC858;       /* Yellow */
--background: #1a1a2e;         /* Dark blue */
--surface: #16213e;            /* Navy blue */

/* Semantic Colors */
--success: #4ade80;            /* Green */
--error: #ef4444;              /* Red */
--warning: #f59e0b;            /* Orange */
--info: #3b82f6;               /* Blue */

/* Text Colors */
--text-primary: #ffffff;
--text-secondary: #C2FCF1;
--text-muted: rgba(255,255,255,0.6);
```

### Typography
```css
/* Headings & UI */
font-family: 'Press Start 2P', cursive;

/* Code & Content */
font-family: 'JetBrains Mono', monospace;
```

### Spacing
```css
/* Base unit: 8px */
--spacing-xs: 4px;
--spacing-sm: 8px;
--spacing-md: 16px;
--spacing-lg: 24px;
--spacing-xl: 32px;
--spacing-2xl: 48px;
```

### Border Radius (Pixel Art Style)
```css
border-radius: 0;              /* Sharp corners */
border: 2px solid;             /* Thick borders */
```

### Responsive Breakpoints
```css
/* Tablet */
@media (max-width: 968px) { }

/* Mobile */
@media (max-width: 640px) { }

/* Small Mobile */
@media (max-width: 480px) { }
```

---

## 🧩 Common Patterns

### Toast Notifications
```javascript
// Success
Toast.success('Operasi berhasil!');

// Error
Toast.error('Terjadi kesalahan!');

// Warning
Toast.warning('Perhatian diperlukan!');

// Info
Toast.info('Informasi penting');
```

### Loading States
```javascript
// Show overlay loading
Loading.show();

// Hide loading
Loading.hide();

// Button loading
const button = document.querySelector('button');
Loading.button(button, true);  // Start
Loading.button(button, false); // Stop

// Auto-save indicator
AutoSave.show();
AutoSave.hide();
```

### API Calls with CSRF
```javascript
fetch('/api/endpoint', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    },
    body: JSON.stringify(data)
})
.then(response => response.json())
.then(data => {
    Toast.success('Berhasil!');
})
.catch(error => {
    Toast.error('Gagal!');
});
```

### Blade Component Pattern
```blade
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Page Title - CodeTide</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/mobile-responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('css/toast.css') }}">
    <link rel="stylesheet" href="{{ asset('css/loading.css') }}">
    
    <!-- Custom JS -->
    <script src="{{ asset('js/pxlkit-renderer.js') }}"></script>
    <script src="{{ asset('js/toast.js') }}"></script>
    <script src="{{ asset('js/loading.js') }}"></script>
</head>
<body>
    <!-- Content here -->
    
    <script>
        // Page-specific JS
    </script>
</body>
</html>
```

---

## 🔐 Security Notes

### Email Credentials
```env
# DO NOT SHARE PUBLICLY
MAIL_USERNAME=asiapin20@gmail.com
MAIL_PASSWORD=gtmvxlmoftyqntls

# These are App Password, not regular Gmail password
# Regenerate if compromised at: https://myaccount.google.com/apppasswords
```

### Database Access
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=codetide
DB_USERNAME=root
DB_PASSWORD=
```

### Production Settings
```env
# Change before production deployment
APP_ENV=production
APP_DEBUG=false
APP_KEY=[generate-new-key]
```

---

## 🐛 Common Issues & Solutions

### Issue: Database Duplicates
**Symptom:** Modules/questions/costumes/badges duplicated  
**Cause:** Running `php artisan db:seed` multiple times  
**Solution:** 
```bash
php artisan migrate:fresh --seed
# Always use migrate:fresh --seed, not db:seed alone
```

### Issue: Email Not Sending
**Symptom:** Password reset email not received  
**Solution:**
```bash
# 1. Check .env configuration
# 2. Clear config cache
php artisan config:cache

# 3. Test email
php artisan test:email your-email@example.com

# 4. Check logs
tail -f storage/logs/laravel.log
```

### Issue: Scroll Not Working on Auth Pages
**Symptom:** Cannot scroll down on login/register pages  
**Cause:** `overflow: hidden` on container  
**Solution:** Already fixed - use `overflow-x: hidden; overflow-y: auto`

### Issue: Mobile Responsive Not Working
**Symptom:** Layout broken on mobile  
**Cause:** mobile-responsive.css not linked  
**Solution:** Already fixed - linked to all 13 pages

### Issue: Routes Not Found
**Symptom:** 404 on existing routes  
**Solution:**
```bash
php artisan route:cache
php artisan route:clear
php artisan route:list  # Verify
```

---

## 📊 Database Data Verification

### Expected Counts
```sql
-- Modules: 3
SELECT COUNT(*) FROM modules;  -- Should be: 3
SELECT * FROM modules ORDER BY id;
-- ID 1: HTML
-- ID 2: CSS
-- ID 3: JavaScript

-- Materials: 8
SELECT COUNT(*) FROM materials;  -- Should be: 8
SELECT module_id, COUNT(*) FROM materials GROUP BY module_id;
-- HTML: 3, CSS: 2, JavaScript: 3

-- Questions: 40
SELECT COUNT(*) FROM questions;  -- Should be: 40
SELECT material_id, COUNT(*) FROM questions GROUP BY material_id;
-- Each material should have exactly 5 questions

-- Costumes: 19
SELECT COUNT(*) FROM costumes;  -- Should be: 19

-- Badges: 4
SELECT COUNT(*) FROM badges;  -- Should be: 4
SELECT * FROM badges ORDER BY id;
-- ID 1: HTML Explorer
-- ID 2: CSS Stylist
-- ID 3: JS Interactor
-- ID 4: Web Developer Pemula
```

### Check for Duplicates
```bash
cd laravel-app
php final_check.php
# Should show all ✓ marks
```

---

## 🚀 Deployment Checklist

### Before Production
```bash
# 1. Environment
APP_ENV=production
APP_DEBUG=false

# 2. Generate new app key
php artisan key:generate

# 3. Cache optimization
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 4. Database backup
mysqldump -u root codetide > backup_production.sql

# 5. Security
# - Enable HTTPS (SSL certificate)
# - Setup CORS properly
# - Review .env security settings
# - Setup rate limiting

# 6. Monitoring
# - Setup error tracking (Sentry, Bugsnag)
# - Setup uptime monitoring
# - Configure automated backups
```

---

## 📈 Success Metrics to Track

### User Metrics
- Registration count (target: 50 in 2 weeks)
- Active users (daily/weekly)
- Completion rate (users completing all modules)
- Average session duration
- Return rate

### Learning Metrics
- Questions answered
- Accuracy rate per question
- Time to complete each module
- Most difficult questions
- Most skipped questions

### Gamification Metrics
- XP earned per user (average)
- Badges awarded (distribution)
- Costumes purchased (most popular)
- Leaderboard engagement
- Costume equip frequency

### Technical Metrics
- Page load time (target: < 2s)
- Error rate (target: < 0.1%)
- Uptime (target: 99.9%)
- API response time
- Database query performance

---

## 💡 Future Enhancement Ideas

### Phase 2 (Post-Launch)
1. **Email Verification** on registration
2. **Advanced Analytics** dashboard for admin
3. **More Modules** (React, Vue, Node.js, etc.)
4. **Social Features**
   - Friend system
   - Challenge friends to compete
   - Share achievements on social media
5. **Achievements System Expansion**
   - Streak tracking (login daily)
   - Special event badges
   - Seasonal costumes
6. **Content Improvements**
   - Video tutorials
   - Code snippet library
   - Hints system for difficult questions
   - Step-by-step walkthroughs
7. **Leaderboard Enhancements**
   - Weekly/monthly leaderboards
   - Category-specific rankings (HTML, CSS, JS)
   - Team competitions

### Nice-to-Have Features
- Dark mode toggle (currently always dark)
- Accessibility improvements (WCAG 2.1 AA)
- Multi-language support (EN, ID)
- Export certificate of completion (PDF)
- Progress visualization (charts, graphs)
- Code playground (try code without questions)
- AI-powered hint system
- Voice narration for materials
- Gamification sound effects
- Achievements notification animations

---

## 🎓 For New Team Members

### Onboarding Checklist
1. ✅ Read this prompt document
2. ✅ Review Final Pre-Launch Report artifact
3. ✅ Clone repository
4. ✅ Setup local environment (PHP, MySQL, Composer)
5. ✅ Run `composer install`
6. ✅ Copy `.env.example` to `.env`
7. ✅ Run `php artisan key:generate`
8. ✅ Create database `codetide`
9. ✅ Run `php artisan migrate:fresh --seed`
10. ✅ Run `php final_check.php` (verify all ✓)
11. ✅ Run `php artisan serve`
12. ✅ Test at http://localhost:8000
13. ✅ Create test account
14. ✅ Try completing a question
15. ✅ Test forgot password flow

### Key Files to Understand
1. `routes/web.php` - All application routes
2. `routes/auth.php` - Authentication routes
3. `app/Http/Controllers/QuestionController.php` - Question logic
4. `app/Http/Controllers/CostumeController.php` - Gamification
5. `public/js/toast.js` - Toast notification system
6. `public/js/loading.js` - Loading state management
7. `public/css/mobile-responsive.css` - Responsive design
8. `database/seeders/QuestionSeeder.php` - Question content

---

## 📞 Quick Reference

### Important Commands
```bash
# Development
php artisan serve                          # Start server
php final_check.php                        # Verify everything
php artisan test:email email@example.com   # Test email

# Database
php artisan migrate:fresh --seed           # Reset DB
mysqldump -u root codetide > backup.sql    # Backup

# Cache
php artisan config:cache                   # Cache config
php artisan route:cache                    # Cache routes
php artisan cache:clear                    # Clear cache

# Debug
php artisan route:list                     # List routes
php artisan about                          # System info
tail -f storage/logs/laravel.log           # View logs
```

### Important URLs (Development)
```
http://localhost:8000/              # Home
http://localhost:8000/register      # Register
http://localhost:8000/login         # Login
http://localhost:8000/dashboard     # Dashboard
http://localhost:8000/modules       # Modules list
http://localhost:8000/costumes      # Costume shop
http://localhost:8000/leaderboard   # Leaderboard
http://localhost:8000/profile       # Profile
http://localhost:8000/demo/toast    # Toast demo
http://localhost:8000/demo/error/404 # Error pages demo
```

### Important Files
```
.env                              # Environment config
final_check.php                   # Verification script
EMAIL_SETUP_GUIDE.md              # Email setup guide
FORGOT_PASSWORD_TESTING.md        # Password reset testing
CODETIDE_PROJECT_PROMPT.md        # This file
```

---

## ✅ Final Notes

### Project Status
- ✅ **100% Feature Complete**
- ✅ **Production Ready**
- ✅ **All Tests Passing**
- ✅ **Documentation Complete**
- ✅ **Database Clean**
- ✅ **Email Working**
- ✅ **Mobile Responsive**

### Before Starting New Work
1. Run `php final_check.php` to verify current state
2. Create a backup: `mysqldump -u root codetide > backup.sql`
3. Read relevant documentation files
4. Test existing features to understand behavior
5. Follow existing code patterns and style

### After Making Changes
1. Test manually in browser
2. Run `php final_check.php`
3. Test on mobile (responsive)
4. Check logs for errors
5. Update documentation if needed

### When Something Breaks
1. Check logs: `tail -f storage/logs/laravel.log`
2. Clear cache: `php artisan cache:clear && php artisan config:clear`
3. Verify .env configuration
4. Check database state with `php final_check.php`
5. Restore backup if needed: `mysql -u root codetide < backup.sql`

---

**Remember:** This project is complete and working. Any new changes should maintain the existing quality, style, and user experience. Always test thoroughly before deploying!

**Good luck! 🚀**

---

*Last Updated: November 1, 2026*  
*Project Version: 1.0 Final*  
*Status: Production Ready*
