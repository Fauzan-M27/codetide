# Tahap 2 & 3: Models, Controllers & Routes

## Tahap 2: Models & Relationships ✅

### Models yang Dibuat

1. **Module** (`app/Models/Module.php`)
   - Relationships: `hasMany(Material)`, `hasOne(Badge)`, `hasManyThrough(Question)`
   - Methods: `isCompletedBy()`, `getCompletionPercentage()`, `isUnlockedFor()`

2. **Material** (`app/Models/Material.php`)
   - Relationships: `belongsTo(Module)`, `hasMany(Question)`
   - Methods: `isCompletedBy()`, `getCompletionPercentage()`, `isUnlockedFor()`

3. **Question** (`app/Models/Question.php`)
   - Relationships: `belongsTo(Material)`, `hasMany(UserProgress)`
   - Methods: `isCompletedBy()`, `isUnlockedFor()`, `nextQuestion()`, `isBugHunt()`, `isClone()`, dll
   - Casts: `xp_reward` (integer), `order` (integer)

4. **UserProgress** (`app/Models/UserProgress.php`)
   - Relationships: `belongsTo(User)`, `belongsTo(Question)`
   - Methods: `isCompleted()`, `markAsCompleted()`, `incrementAttempts()`, `saveCode()`
   - Casts: `completed_at` (datetime), `attempts` (integer)

5. **Badge** (`app/Models/Badge.php`)
   - Relationships: `belongsTo(Module)`, `belongsToMany(User)`
   - Methods: `isEarnedBy()`, `canBeEarnedBy()`, `awardTo()`
   - Pivot: `user_badges` dengan `earned_at`

6. **Costume** (`app/Models/Costume.php`)
   - Relationships: `belongsToMany(User)`
   - Methods: `isUnlockedBy()`, `canBeUnlockedBy()`, `unlockFor()`, `isActiveFor()`
   - Pivot: `user_costumes` dengan `unlocked_at`, `is_active`

7. **ActivityLog** (`app/Models/ActivityLog.php`)
   - Relationships: `belongsTo(User)`, `belongsTo(Question)`
   - Static Methods: `logActivity()`, `getTotalDuration()`, `getSummary()`
   - Casts: `meta` (array/JSON)

8. **User** (`app/Models/User.php`) - UPDATED
   - Modified fillable: `nickname`, `participant_code`, `password`, `consent_at`
   - Relationships: `hasMany(UserProgress, ActivityLog)`, `belongsToMany(Badge, Costume)`
   - XP Methods:
     - `getTotalXP()` - hitung total XP dari soal selesai
     - `getCompletedQuestionsCount()` - jumlah soal selesai
     - `getCompletionPercentage()` - persentase keseluruhan
     - `getRank()` - peringkat di leaderboard
   - Badge/Costume Methods:
     - `checkAndAwardBadges()` - cek & berikan badge otomatis
     - `checkAndUnlockCostumes()` - unlock kostum otomatis
     - `getActiveCostume()` - kostum yang sedang dipakai
     - `setActiveCostume()` - ganti kostum aktif
   - Unlock Logic:
     - `getNextUnlockedQuestion()` - soal berikutnya yang bisa dikerjakan
     - `initializeProgress()` - unlock kostum default saat register
   - Activity:
     - `logActivity()` - catat aktivitas
     - `getActivitySummary()` - ringkasan aktivitas

### Buka-Kunci Berurutan (Unlock Logic)

Sistem buka-kunci cascade:

```
Module unlocked → Material unlocked → Question unlocked
     ↓                    ↓                   ↓
Prev module        Prev material       Prev question
 completed           completed          completed
```

**Aturan**:
- Modul 1 selalu unlocked
- Modul N unlocked jika Modul N-1 selesai
- Material 1 di modul unlocked selalu unlocked
- Material N unlocked jika Material N-1 selesai
- Question 1 di material unlocked selalu unlocked
- Question N unlocked jika Question N-1 selesai

**Implemented di**:
- `Module::isUnlockedFor(User $user)`
- `Material::isUnlockedFor(User $user)`
- `Question::isUnlockedFor(User $user)`

### XP & Badge System

**XP Calculation**:
- Setiap soal punya `xp_reward` (5-25 poin)
- XP diberikan **hanya sekali** saat pertama kali selesai
- Total XP: jumlah XP dari semua soal yang `completed_at !== null`

**Badge Requirements**:
- `complete_module`: Selesaikan 1 modul tertentu
- `complete_all`: Selesaikan semua modul

**Auto-Award**:
- Dipanggil di `UserProgress::markAsCompleted()`
- Cek semua badge, berikan yang memenuhi syarat
- Return: list badge yang baru didapat (untuk notifikasi)

### Costume Unlock System

**Unlock Types**:
- `default`: Unlocked dari awal untuk semua user
- `badge`: Unlock setelah dapat badge tertentu
- `xp`: Unlock setelah kumpulkan XP tertentu
- `module`: Unlock setelah selesaikan modul tertentu

**Auto-Unlock**:
- Dipanggil di `UserProgress::markAsCompleted()`
- Cek semua kostum, unlock yang memenuhi syarat
- Kostum pertama default otomatis aktif saat register

---

## Tahap 3: Controllers & Routes ✅

### Controllers yang Dibuat

#### 1. **DashboardController**
**Route**: `GET /dashboard`

**Method**: `index()`
- Log activity login
- Get modules dengan progress per modul
- Get user stats (XP, completed, badges, rank)
- Get recent badges (3 terbaru)
- Get active costume
- Get next question

**Return**: View `dashboard` dengan data modul, stats, badges, costume

---

#### 2. **ModuleController**
**Routes**:
- `GET /modules` → `index()`
- `GET /modules/{module}` → `show()`

**Method**: `index()`
- List semua modul dengan status unlock/complete
- List materials per modul dengan status

**Method**: `show(Module $module)`
- Detail 1 modul
- **Validation**: Redirect jika modul terkunci
- List materials dengan completion percentage

---

#### 3. **MaterialController**
**Route**: `GET /materials/{material}`

**Method**: `show(Material $material)`
- Detail 1 material
- **Validation**: Redirect jika material terkunci
- List questions (5 soal) dengan status unlock/complete
- Show attempts per soal

---

#### 4. **QuestionController**
**Route**: `GET /questions/{question}`

**Method**: `show(Question $question)`
- Halaman editor soal
- **Validation**: Redirect jika soal terkunci
- Log activity `open_question`
- Get or create `UserProgress`
- Load saved code atau starter code
- Get next question untuk navigasi

**Return**: View `questions.show` dengan data soal, code, next question

---

#### 5. **ProgressController** (API)
**Routes**:
- `POST /questions/{question}/autosave` → `autosave()`
- `POST /questions/{question}/check` → `check()`
- `POST /questions/{question}/complete` → `complete()`
- `GET /progress` → `getProgress()`

**Method**: `autosave(Request $request, Question $question)`
- Save code ke `user_progress.code_submitted`
- Dipanggil tiap 500ms dari editor (debounce)

**Method**: `check(Request $request, Question $question)`
- Increment `attempts` counter
- Log activity `check_code`
- Return: attempts count

**Method**: `complete(Request $request, Question $question)`
- **Validation**: Cek soal unlocked
- Mark `UserProgress::completed_at = now()`
- Award XP (hanya pertama kali)
- Trigger auto-badge & auto-costume unlock
- Log activity `complete_question`
- **Return JSON**:
  ```json
  {
    "success": true,
    "xp_awarded": 10,
    "total_xp": 150,
    "new_badges": [...],
    "new_costumes": [...],
    "next_question": {...}
  }
  ```

**Method**: `getProgress(Request $request)`
- Get user stats realtime
- Return: XP, completed, percentage, badges, rank

---

#### 6. **LeaderboardController**
**Routes**:
- `GET /leaderboard` → `index()`
- `GET /leaderboard/data` → `getData()` (JSON)

**Method**: `index()`
- Get all users sorted by XP descending
- Show top 5 users
- Show current user position (jika di luar top 5)

**Method**: `getData()`
- Same as index() tapi return JSON
- Untuk AJAX refresh leaderboard

---

#### 7. **CostumeController**
**Routes**:
- `GET /costumes` → `index()`
- `POST /costumes/{costume}/activate` → `activate()`
- `GET /costumes/check-unlocks` → `checkUnlocks()`

**Method**: `index()`
- List all costumes dengan status locked/unlocked
- Show active costume
- Show unlock hint per costume

**Method**: `activate(Costume $costume)`
- **Validation**: Cek costume unlocked
- Set costume as active
- Deactivate others
- Return JSON success/fail

**Method**: `checkUnlocks()`
- Trigger `checkAndUnlockCostumes()`
- Return newly unlocked costumes (last 10 seconds)
- Untuk polling dari frontend

---

### Routes Summary

```
GET     /                                  Landing page
GET     /dashboard                         Dashboard (stats, modules, badges)
GET     /modules                           List modul (materi page)
GET     /modules/{module}                  Detail modul & materials
GET     /materials/{material}              Detail material & questions (level page)
GET     /questions/{question}              Halaman editor soal
POST    /questions/{question}/autosave     Autosave code (debounce)
POST    /questions/{question}/check        Increment attempts
POST    /questions/{question}/complete     Mark completed, award XP
GET     /progress                          Get user stats JSON
GET     /leaderboard                       Leaderboard page
GET     /leaderboard/data                  Leaderboard JSON
GET     /costumes                          Kostumisasi page
POST    /costumes/{costume}/activate       Aktifkan costume
GET     /costumes/check-unlocks            Check new unlocked costumes
```

**Auth routes** (Laravel Breeze):
- Login, Register, Logout, Forgot Password, dll

---

## Testing Models & Relationships

File: `test_models.php` (di root laravel-app)

```bash
php test_models.php
```

**Output**:
```
========================================
Testing CodeTide Models & Relationships
========================================

1. Module Test:
   - Module: HTML
   - Materials: 3
   - Questions: 15
   - Badge: HTML Explorer

2. Material Test:
   - Material: Struktur Dasar HTML
   - Module: HTML
   - Questions: 5

3. Question Test:
   - Question: Kerangka HTML Dasar
   - Material: Struktur Dasar HTML
   - Type: reguler
   - XP: 5
   - Is Bug Hunt: No
   - Is Clone: No

6. Special Question Types:
   - Bug Hunt questions: 2
   - Clone questions: 2

========================================
All Models & Relationships Working! ✅
========================================
```

---

## Verifikasi Routes

```bash
php artisan route:list --except-vendor
```

**Total**: 33 routes terdaftar ✅

---

## Yang Sudah Berfungsi

✅ Database structure (10 tables)
✅ Seeders (3 modul, 8 materi, 40 soal, 4 badge, 6 kostum)
✅ Models dengan relationships
✅ Unlock logic berurutan (Module → Material → Question)
✅ XP calculation
✅ Auto-badge award
✅ Auto-costume unlock
✅ Activity logging
✅ Leaderboard ranking
✅ Controllers untuk semua fitur
✅ Routes dengan middleware auth

---

## Yang Belum Ada

❌ Blade views (HTML templates)
❌ Live preview editor
❌ Checker JavaScript (40 files)
❌ Frontend CSS/JS untuk interaksi
❌ Authentication customization (nickname, participant_code)
❌ User registration flow dengan consent
❌ Testing end-to-end

---

## Next: Tahap 4 - Views & Frontend

1. Customize Breeze auth (nickname, participant_code, consent)
2. Buat Blade layouts & components
3. Dashboard view
4. Modules & Materials views
5. Question editor view dengan live preview
6. Checker JavaScript boilerplate
7. Leaderboard view
8. Costume customization view
9. CSS styling

Konfirmasi kalau mau lanjut!
