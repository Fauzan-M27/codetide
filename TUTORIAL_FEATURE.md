# 📚 Tutorial Feature - CodeTide

Fitur tutorial interaktif untuk membantu pengguna baru memahami cara menggunakan platform CodeTide.

## ✨ Fitur

- **Interactive Guided Tour**: Tutorial step-by-step dengan highlight pada elemen UI
- **Modal Overlay**: Seluruh halaman buram kecuali elemen yang sedang dijelaskan
- **Visual Indicators**: Panah animasi menunjuk ke elemen target
- **Pixel Art Theme**: Styling konsisten dengan tema CodeTide
- **Auto-trigger**: Otomatis muncul untuk pengguna baru
- **Restart Capability**: Pengguna bisa mengulang tutorial kapan saja

## 🎯 Tutorial Steps

### Dashboard Tour
1. **Welcome** - Sambutan awal
2. **Stats Overview** - Penjelasan statistik (XP, Soal, Rank, Badge)
3. **Semua Materi** - Tombol untuk melihat semua modul
4. **Lanjutkan Belajar** - Tombol untuk melanjutkan soal
5. **Modul Aktif** - Card modul yang sedang dikerjakan
6. **Leaderboard** - Link ke halaman ranking
7. **Kostumisasi** - Link ke halaman kostum
8. **Badge Sidebar** - Panel badge yang diperoleh
9. **Profile** - Link ke halaman profil
10. **Complete** - Pesan penutup

### Modules Tour
- Penjelasan singkat tentang halaman daftar modul

## 🛠️ Komponen Teknis

### File yang Ditambahkan

1. **resources/js/tutorial.js**
   - Core tutorial logic menggunakan Shepherd.js
   - Definisi semua tutorial steps
   - API integration untuk menyimpan status

2. **resources/css/tutorial.css**
   - Custom styling untuk tutorial popups
   - Animasi dan efek visual
   - Responsive design

3. **app/Http/Controllers/TutorialController.php**
   - API endpoint untuk complete tutorial
   - API endpoint untuk reset tutorial
   - API endpoint untuk cek status tutorial

4. **database/migrations/2026_11_05_110516_add_tutorial_completed_to_users_table.php**
   - Menambah kolom `tutorial_completed` di tabel users

### Routes yang Ditambahkan

```php
Route::post('/api/tutorial/complete', [TutorialController::class, 'complete']);
Route::post('/api/tutorial/reset', [TutorialController::class, 'reset']);
Route::get('/api/tutorial/status', [TutorialController::class, 'status']);
```

### Model Updates

- **User Model**: Menambah `tutorial_completed` ke `$fillable` dan `casts`

## 📦 Dependencies

- **shepherd.js**: Library untuk membuat guided tours
  ```bash
  npm install shepherd.js
  ```

## 🚀 Instalasi

1. Install dependencies:
   ```bash
   npm install shepherd.js --save
   ```

2. Jalankan migration:
   ```bash
   php artisan migrate
   ```

3. Build assets:
   ```bash
   npm run build
   # atau untuk development
   npm run dev
   ```

## 💻 Cara Menggunakan

### Automatic Trigger
Tutorial otomatis muncul untuk pengguna yang belum menyelesaikan tutorial (`tutorial_completed = false`).

### Manual Restart
Pengguna bisa mengulang tutorial dari:
1. **Halaman Profile** - Klik tombol "Ulangi Tutorial"
2. **Developer Console**:
   ```javascript
   window.restartTutorial();
   ```

### Disable Tutorial
Untuk menonaktifkan tutorial secara manual:
```javascript
localStorage.setItem('codetide_tutorial_completed', 'true');
```

## 🎨 Customization

### Menambah Tutorial Step Baru

Edit file `resources/js/tutorial.js`:

```javascript
this.tour.addStep({
    id: 'custom-step',
    text: `
        <div class="tutorial-content">
            <div class="tutorial-icon">🎯</div>
            <h3>Judul Step</h3>
            <p>Deskripsi step tutorial Anda.</p>
        </div>
    `,
    attachTo: {
        element: '.target-element',
        on: 'bottom' // top, bottom, left, right
    },
    buttons: [
        {
            text: 'Kembali',
            classes: 'shepherd-button-secondary',
            action: () => this.tour.back()
        },
        {
            text: 'Lanjut →',
            action: () => this.tour.next()
        }
    ]
});
```

### Mengubah Styling

Edit file `resources/css/tutorial.css` untuk mengubah warna, font, atau animasi.

## 🔧 Configuration

### Tutorial Options

Dalam `tutorial.js`, Anda bisa mengubah konfigurasi:

```javascript
this.tour = new Shepherd.Tour({
    useModalOverlay: true,           // Aktifkan overlay
    exitOnEsc: true,                 // Tekan ESC untuk keluar
    keyboardNavigation: true,        // Navigasi dengan keyboard
    modalOverlayOpeningPadding: 8,   // Padding highlight
    modalOverlayOpeningRadius: 8     // Border radius highlight
});
```

## 📱 Responsive Design

Tutorial sudah responsive dan akan menyesuaikan dengan ukuran layar:
- Desktop: Full-width popup dengan animasi
- Tablet: Adjusted spacing
- Mobile: Compact layout dengan font lebih kecil

## 🐛 Troubleshooting

### Tutorial Tidak Muncul

1. **Cek database**: Pastikan kolom `tutorial_completed` bernilai `false`
2. **Cek localStorage**: Clear item `codetide_tutorial_completed`
3. **Cek console**: Lihat error di browser console
4. **Build assets**: Jalankan `npm run build` atau `npm run dev`

### Styling Tidak Sesuai

1. Pastikan `tutorial.css` di-import di view
2. Clear browser cache
3. Rebuild assets dengan `npm run build`

### API Error

1. Cek route sudah terdaftar: `php artisan route:list`
2. Cek CSRF token ada di halaman: `<meta name="csrf-token">`
3. Cek controller TutorialController sudah ada

## 📊 Database Schema

```sql
ALTER TABLE users ADD COLUMN tutorial_completed BOOLEAN DEFAULT FALSE;
```

## 🎓 Best Practices

1. **Keep Steps Short**: Maksimal 2-3 kalimat per step
2. **Use Icons**: Tambahkan emoji/icon untuk visual appeal
3. **Test Thoroughly**: Test di berbagai ukuran layar
4. **Update Documentation**: Update guide jika menambah fitur baru
5. **User Feedback**: Tambahkan feedback mechanism untuk improve tutorial

## 📝 Future Improvements

- [ ] Multi-language support
- [ ] Tutorial untuk halaman lain (questions, leaderboard, dll)
- [ ] Progress indicator yang lebih jelas
- [ ] Video tutorial sebagai alternatif
- [ ] Analytics untuk tracking completion rate
- [ ] Custom tutorial paths berdasarkan user role

## 🤝 Contributing

Jika ingin menambah atau memperbaiki tutorial:
1. Edit file `tutorial.js` untuk logic
2. Edit file `tutorial.css` untuk styling
3. Test di berbagai browser dan device
4. Update dokumentasi ini

## 📜 License

Tutorial feature ini adalah bagian dari CodeTide project.

---

**Dibuat dengan ❤️ untuk membantu pengguna baru belajar coding!**
