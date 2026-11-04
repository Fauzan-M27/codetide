# Developer Notes — Menambahkan Materi Pembelajaran ke CodeTide

Catatan ini menjelaskan perubahan teknis yang perlu dilakukan di project Laravel
CodeTide agar konten materi pembelajaran (8 file HTML terlampir) bisa ditampilkan
ke pengguna **sebelum** mereka mengerjakan soal di materi tersebut — sesuai revisi
dosen pembimbing.

## 1. Mengapa Perubahan Ini Diperlukan

Berdasarkan `CODETIDE_PROJECT_PROMPT.md`, tabel `materials` saat ini **tidak**
punya kolom untuk menyimpan konten teks/tutorial. Skema yang ada kira-kira:

```
materials: id, module_id, name, slug, order, ...
```

Supaya pengguna bisa membaca materi sebelum mengerjakan soal, kita perlu tempat
untuk menyimpan HTML materi tersebut, lalu menampilkannya di halaman sebelum
daftar soal.

## 2. Migration — Menambahkan Kolom `content`

Buat migration baru:

```bash
php artisan make:migration add_content_to_materials_table --table=materials
```

Isi migration:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('materials', function (Blueprint $table) {
            $table->longText('content')->nullable()->after('name');
        });
    }

    public function down(): void
    {
        Schema::table('materials', function (Blueprint $table) {
            $table->dropColumn('content');
        });
    }
};
```

Jalankan:

```bash
php artisan migrate
```

`longText` dipilih (bukan `text` biasa) karena isi tutorial cukup panjang —
beberapa file materi terlampir ini di atas 5000 karakter per materi.

## 3. Mengisi Kolom `content`

Dua opsi, pilih salah satu:

**Opsi A — Seeder/Script sekali jalan (direkomendasikan):**

Buat file `database/seeders/MaterialContentSeeder.php` yang membaca 8 file HTML
terlampir dan meng-update baris `materials` yang sesuai berdasarkan `slug` atau
`name`. Contoh pola:

```php
use App\Models\Material;

$map = [
    'struktur-dasar-html' => 'materi-1-struktur-dasar-html.html',
    'html-lanjut'         => 'materi-2-html-lanjut.html',
    'html-interaktif'     => 'materi-3-html-interaktif.html',
    'dasar-css'           => 'materi-4-dasar-css.html',
    'layout-css'          => 'materi-5-layout-css.html',
    'dasar-javascript'    => 'materi-6-dasar-javascript.html',
    'event-handling'      => 'materi-7-event-handling.html',
    'manipulasi-dom'      => 'materi-8-manipulasi-dom.html',
];

foreach ($map as $slug => $file) {
    $content = file_get_contents(database_path('seeders/materi-content/' . $file));
    Material::where('slug', $slug)->update(['content' => $content]);
}
```

Sesuaikan `$map` dengan `slug`/`name` asli di tabel `materials` kamu (cek dulu
dengan `SELECT id, name, slug FROM materials;`).

**Opsi B — Update manual via Tinker/phpMyAdmin**, kalau hanya ingin tempel satu
per satu untuk 8 baris.

## 4. Menampilkan Konten di Blade View

Konten HTML perlu ditampilkan **sebelum** daftar 5 soal pada materi tersebut.
Kemungkinan besar ini ada di view semacam `resources/views/materials/show.blade.php`
(sesuaikan nama file dengan struktur project kamu). Tambahkan blok berikut di
atas bagian yang me-render daftar soal:

```blade
@if($material->content)
    <section class="materi-pembelajaran">
        {!! $material->content !!}
    </section>
@endif
```

**Penting:** gunakan `{!! !!}` (bukan `{{ }}`) karena konten berisi markup HTML
asli yang perlu dirender, bukan ditampilkan sebagai teks mentah. Karena konten
ini sepenuhnya kamu kontrol sendiri (bukan input dari pengguna), ini aman dari
sisi XSS — jangan pernah pakai pola ini untuk menampilkan input pengguna.

## 5. Styling agar Konsisten dengan Tema CodeTide

Setiap file materi sudah dibungkus tag `<article class="materi-content">` agar
mudah di-style. Tambahkan CSS berikut (atau sesuaikan dengan token desain
project, lihat `--primary-color`, `--secondary-color`, dll. di
`CODETIDE_PROJECT_PROMPT.md`):

```css
.materi-content {
    font-family: 'JetBrains Mono', monospace;
    line-height: 1.6;
    color: var(--text-color, #e0e0e0);
    max-width: 800px;
    margin: 0 auto 32px;
}

.materi-content h2 {
    font-family: 'Press Start 2P', cursive;
    font-size: 1.1rem;
    color: var(--primary-color);
    margin-bottom: 16px;
}

.materi-content h3 {
    font-family: 'Press Start 2P', cursive;
    font-size: 0.85rem;
    color: var(--secondary-color);
    margin-top: 24px;
    margin-bottom: 8px;
}

.materi-content pre {
    background: var(--surface, #16213e);
    border: 2px solid var(--secondary-color);
    border-radius: 0;
    padding: 16px;
    overflow-x: auto;
    margin: 12px 0;
}

.materi-content code {
    font-family: 'JetBrains Mono', monospace;
    font-size: 0.9rem;
}

.materi-content p code,
.materi-content li code {
    background: rgba(194, 252, 241, 0.1);
    padding: 2px 6px;
    color: var(--secondary-color);
}

.materi-tips {
    background: rgba(254, 200, 88, 0.1);
    border: 2px solid var(--accent-color);
    padding: 16px;
    margin-top: 24px;
}

.materi-tips h3 {
    color: var(--accent-color) !important;
    font-size: 0.8rem !important;
}
```

## 6. Alur Pengguna yang Disarankan

1. Pengguna membuka sebuah materi (mis. "Struktur Dasar HTML") dari halaman
   daftar materi.
2. Halaman menampilkan konten tutorial (`$material->content`) **di atas**
   daftar 5 soal/level materi tersebut.
3. Pengguna membaca materi, lalu scroll ke bawah untuk memilih level 1-5 seperti
   biasa (tidak ada perubahan pada alur pengerjaan soal itu sendiri).

Tidak perlu mengubah logika pengerjaan soal, validasi otomatis, sistem XP/badge,
atau apa pun selain menambahkan satu kolom dan menampilkannya — ini perubahan
yang kecil dan aman untuk 100% fitur yang sudah ada.

## 7. Opsional — Menandai "Sudah Dibaca"

Kalau dosen pembimbing juga ingin bukti bahwa materi benar-benar dibaca (bukan
sekadar ditampilkan), bisa ditambahkan kolom `materi_read_at` pada tabel
`user_progress`, di-update via sedikit JavaScript (event `scroll` mendekati
akhir `<article>`, atau tombol "Saya sudah membaca materi ini, lanjut ke soal").
Ini bukan kebutuhan wajib dari revisi yang disebutkan, jadi cukup dicatat sebagai
opsi pengembangan lanjutan (Phase 2), bukan prioritas sekarang.

## 8. Ringkasan Perubahan

| Langkah | File yang Diubah/Dibuat |
|---|---|
| 1 | Migration baru: `add_content_to_materials_table` |
| 2 | (opsional) Seeder: `MaterialContentSeeder.php` |
| 3 | 8 file konten HTML (terlampir) disalin ke `database/seeders/materi-content/` atau disisipkan langsung ke DB |
| 4 | View materi (mis. `materials/show.blade.php`) — tambah blok `{!! $material->content !!}` |
| 5 | CSS tema — tambah styling `.materi-content` dan `.materi-tips` |

Setelah langkah 1-5 selesai, jalankan `php artisan migrate` lalu seeder/update,
dan konten materi akan langsung tampil di halaman yang sudah ada tanpa perlu
membuat route atau controller baru.
