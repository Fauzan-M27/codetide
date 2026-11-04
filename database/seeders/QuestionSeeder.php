<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $questions = [];

        // ====================================
        // MODUL 1: HTML
        // ====================================
        
        // Materi 1: Struktur Dasar HTML (material_id: 1)
        $questions[] = [
            'material_id' => 1,
            'order' => 1,
            'type' => 'reguler',
            'title' => 'Kerangka HTML Dasar',
            'instruction' => 'Lengkapi kerangka <html><head><title> dan <body> kosong',
            'starter_code' => '<!-- Lengkapi struktur HTML di bawah ini -->\n',
            'xp_reward' => 5,
            'checker_file' => 'checkers/m1_materi1_soal1.js',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $questions[] = [
            'material_id' => 1,
            'order' => 2,
            'type' => 'reguler',
            'title' => 'Judul Halaman',
            'instruction' => 'Tambahkan <h1> judul halaman',
            'starter_code' => '<!DOCTYPE html>\n<html>\n<head>\n    <title>Halaman Saya</title>\n</head>\n<body>\n    <!-- Tambahkan h1 di sini -->\n</body>\n</html>',
            'xp_reward' => 10,
            'checker_file' => 'checkers/m1_materi1_soal2.js',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $questions[] = [
            'material_id' => 1,
            'order' => 3,
            'type' => 'reguler',
            'title' => 'Paragraf Konten',
            'instruction' => 'Tambahkan 2 <p> paragraf konten (masing-masing minimal 5 kata)',
            'starter_code' => '<!DOCTYPE html>\n<html>\n<head>\n    <title>Konten Saya</title>\n</head>\n<body>\n    <h1>Selamat Datang</h1>\n    <!-- Tambahkan 2 paragraf di sini -->\n</body>\n</html>',
            'xp_reward' => 10,
            'checker_file' => 'checkers/m1_materi1_soal3.js',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $questions[] = [
            'material_id' => 1,
            'order' => 4,
            'type' => 'reguler',
            'title' => 'Komentar HTML',
            'instruction' => 'Tambahkan komentar HTML <!-- --> yang menjelaskan tiap bagian',
            'starter_code' => '<!DOCTYPE html>\n<html>\n<head>\n    <title>Dokumentasi</title>\n</head>\n<body>\n    <h1>Judul Utama</h1>\n    <p>Paragraf pertama.</p>\n</body>\n</html>',
            'xp_reward' => 10,
            'checker_file' => 'checkers/m1_materi1_soal4.js',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $questions[] = [
            'material_id' => 1,
            'order' => 5,
            'type' => 'reguler',
            'title' => 'Halaman Lengkap',
            'instruction' => 'Gabungkan head lengkap (title + meta charset) dengan body terstruktur (heading + 2 paragraf)',
            'starter_code' => '<!DOCTYPE html>\n<html>\n<!-- Lengkapi halaman ini -->\n</html>',
            'xp_reward' => 15,
            'checker_file' => 'checkers/m1_materi1_soal5.js',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Materi 2: HTML Lanjut (material_id: 2)
        $questions[] = [
            'material_id' => 2,
            'order' => 1,
            'type' => 'reguler',
            'title' => 'Link Internal',
            'instruction' => 'Buat <a href="#id"> sebagai link internal ke elemen tertentu',
            'starter_code' => '<!DOCTYPE html>\n<html>\n<body>\n    <h1 id="atas">Judul</h1>\n    <!-- Buat link ke #atas -->\n    <div id="bawah">\n        <p>Konten di bawah</p>\n    </div>\n</body>\n</html>',
            'xp_reward' => 10,
            'checker_file' => 'checkers/m1_materi2_soal1.js',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $questions[] = [
            'material_id' => 2,
            'order' => 2,
            'type' => 'reguler',
            'title' => 'Link Eksternal',
            'instruction' => 'Buat <a target="_blank"> sebagai link eksternal',
            'starter_code' => '<!DOCTYPE html>\n<html>\n<body>\n    <h1>Link Eksternal</h1>\n    <!-- Buat link ke https://www.google.com yang terbuka di tab baru -->\n</body>\n</html>',
            'xp_reward' => 10,
            'checker_file' => 'checkers/m1_materi2_soal2.js',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $questions[] = [
            'material_id' => 2,
            'order' => 3,
            'type' => 'reguler',
            'title' => 'Tabel Data',
            'instruction' => 'Buat tabel 3x3 dengan baris header <th>',
            'starter_code' => '<!DOCTYPE html>\n<html>\n<body>\n    <h1>Tabel Mahasiswa</h1>\n    <!-- Buat tabel di sini -->\n</body>\n</html>',
            'xp_reward' => 15,
            'checker_file' => 'checkers/m1_materi2_soal3.js',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $questions[] = [
            'material_id' => 2,
            'order' => 4,
            'type' => 'reguler',
            'title' => 'Iframe',
            'instruction' => 'Tambahkan <iframe> menampilkan halaman lain',
            'starter_code' => '<!DOCTYPE html>\n<html>\n<body>\n    <h1>Embedded Page</h1>\n    <!-- Tambahkan iframe di sini -->\n</body>\n</html>',
            'xp_reward' => 15,
            'checker_file' => 'checkers/m1_materi2_soal4.js',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $questions[] = [
            'material_id' => 2,
            'order' => 5,
            'type' => 'reguler',
            'title' => 'Halaman Navigasi Lengkap',
            'instruction' => 'Gabungkan navigasi (beberapa <a>), tabel data, dan <iframe> dalam satu halaman terstruktur',
            'starter_code' => '<!DOCTYPE html>\n<html>\n<body>\n    <!-- Buat halaman lengkap dengan navigasi, tabel, dan iframe -->\n</body>\n</html>',
            'xp_reward' => 20,
            'checker_file' => 'checkers/m1_materi2_soal5.js',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Materi 3: HTML Interaktif (material_id: 3)
        $questions[] = [
            'material_id' => 3,
            'order' => 1,
            'type' => 'reguler',
            'title' => 'Form Sederhana',
            'instruction' => 'Buat <form> dengan satu <input type="text">',
            'starter_code' => '<!DOCTYPE html>\n<html>\n<body>\n    <h1>Form Input</h1>\n    <!-- Buat form di sini -->\n</body>\n</html>',
            'xp_reward' => 10,
            'checker_file' => 'checkers/m1_materi3_soal1.js',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $questions[] = [
            'material_id' => 3,
            'order' => 2,
            'type' => 'reguler',
            'title' => 'Select Dropdown',
            'instruction' => 'Tambahkan <select> dengan 3 <option>',
            'starter_code' => '<!DOCTYPE html>\n<html>\n<body>\n    <form>\n        <input type="text" name="nama">\n        <!-- Tambahkan select di sini -->\n    </form>\n</body>\n</html>',
            'xp_reward' => 10,
            'checker_file' => 'checkers/m1_materi3_soal2.js',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $questions[] = [
            'material_id' => 3,
            'order' => 3,
            'type' => 'reguler',
            'title' => 'Textarea dengan Label',
            'instruction' => 'Tambahkan <textarea> dan <label> yang terhubung lewat for/id',
            'starter_code' => '<!DOCTYPE html>\n<html>\n<body>\n    <form>\n        <!-- Tambahkan label dan textarea di sini -->\n    </form>\n</body>\n</html>',
            'xp_reward' => 15,
            'checker_file' => 'checkers/m1_materi3_soal3.js',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $questions[] = [
            'material_id' => 3,
            'order' => 4,
            'type' => 'reguler',
            'title' => 'Image dan Submit Button',
            'instruction' => 'Tambahkan <img> dengan alt deskriptif dan <button type="submit">',
            'starter_code' => '<!DOCTYPE html>\n<html>\n<body>\n    <form>\n        <input type="text" name="nama">\n        <!-- Tambahkan img dan button submit di sini -->\n    </form>\n</body>\n</html>',
            'xp_reward' => 15,
            'checker_file' => 'checkers/m1_materi3_soal4.js',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $questions[] = [
            'material_id' => 3,
            'order' => 5,
            'type' => 'bug_hunt',
            'title' => 'Form Lengkap (Bug Hunt)',
            'instruction' => 'Perbaiki bug pada form lengkap ini. Ada label yang tidak terhubung, attribute yang salah, dan elemen yang kurang.',
            'starter_code' => '<!DOCTYPE html>\n<html>\n<body>\n    <form>\n        <label>Nama</label>\n        <input type="text" id="nama">\n        \n        <label for="email">Email</label>\n        <input type="text" id="emailField">\n        \n        <label for="pesan">Pesan</label>\n        <textarea id="pesan"></textarea>\n        \n        <input type="checkbox">\n        <label>Setuju dengan syarat</label>\n        \n        <button>Kirim</button>\n    </form>\n</body>\n</html>',
            'xp_reward' => 20,
            'checker_file' => 'checkers/m1_materi3_soal5.js',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // ====================================
        // MODUL 2: CSS
        // ====================================
        
        // Materi 4: Dasar CSS (material_id: 4)
        $questions[] = [
            'material_id' => 4,
            'order' => 1,
            'type' => 'reguler',
            'title' => 'Warna Teks',
            'instruction' => 'Ubah warna teks <h1> jadi biru memakai selector tag',
            'starter_code' => '<!DOCTYPE html>\n<html>\n<head>\n<style>\n/* Tambahkan CSS di sini */\n</style>\n</head>\n<body>\n    <h1>Judul Biru</h1>\n</body>\n</html>',
            'xp_reward' => 10,
            'checker_file' => 'checkers/m2_materi1_soal1.js',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $questions[] = [
            'material_id' => 4,
            'order' => 2,
            'type' => 'reguler',
            'title' => 'Ukuran Font',
            'instruction' => 'Ubah ukuran font <p> jadi 18px memakai class selector',
            'starter_code' => '<!DOCTYPE html>\n<html>\n<head>\n<style>\n/* Tambahkan CSS di sini */\n</style>\n</head>\n<body>\n    <p class="besar">Teks berukuran 18px</p>\n</body>\n</html>',
            'xp_reward' => 10,
            'checker_file' => 'checkers/m2_materi1_soal2.js',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $questions[] = [
            'material_id' => 4,
            'order' => 3,
            'type' => 'reguler',
            'title' => 'Background Color',
            'instruction' => 'Beri background color pada <div id="kotak"> memakai id selector',
            'starter_code' => '<!DOCTYPE html>\n<html>\n<head>\n<style>\n/* Tambahkan CSS di sini */\n</style>\n</head>\n<body>\n    <div id="kotak">Kotak berwarna</div>\n</body>\n</html>',
            'xp_reward' => 10,
            'checker_file' => 'checkers/m2_materi1_soal3.js',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $questions[] = [
            'material_id' => 4,
            'order' => 4,
            'type' => 'reguler',
            'title' => 'Padding dan Margin',
            'instruction' => 'Atur padding 20px dan margin 10px pada <div class="kartu">',
            'starter_code' => '<!DOCTYPE html>\n<html>\n<head>\n<style>\n/* Tambahkan CSS di sini */\n</style>\n</head>\n<body>\n    <div class="kartu">Kartu dengan spacing</div>\n</body>\n</html>',
            'xp_reward' => 15,
            'checker_file' => 'checkers/m2_materi1_soal4.js',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $questions[] = [
            'material_id' => 4,
            'order' => 5,
            'type' => 'reguler',
            'title' => 'Stylesheet Terorganisir',
            'instruction' => 'Gabungkan selector tag, class, dan id dalam satu stylesheet terorganisir untuk styling satu halaman kecil',
            'starter_code' => '<!DOCTYPE html>\n<html>\n<head>\n<style>\n/* Styling untuk h1 */\n/* Styling untuk .intro */\n/* Styling untuk #footer */\n</style>\n</head>\n<body>\n    <h1>Judul Halaman</h1>\n    <p class="intro">Paragraf intro</p>\n    <div id="footer">Footer halaman</div>\n</body>\n</html>',
            'xp_reward' => 20,
            'checker_file' => 'checkers/m2_materi1_soal5.js',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Materi 5: Layout & Interaksi CSS (material_id: 5)
        $questions[] = [
            'material_id' => 5,
            'order' => 1,
            'type' => 'reguler',
            'title' => 'Flexbox Horizontal',
            'instruction' => 'Buat 3 <div> tersusun horizontal memakai display: flex',
            'starter_code' => '<!DOCTYPE html>\n<html>\n<head>\n<style>\n/* Tambahkan CSS di sini */\n</style>\n</head>\n<body>\n    <div class="container">\n        <div>Box 1</div>\n        <div>Box 2</div>\n        <div>Box 3</div>\n    </div>\n</body>\n</html>',
            'xp_reward' => 15,
            'checker_file' => 'checkers/m2_materi2_soal1.js',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $questions[] = [
            'material_id' => 5,
            'order' => 2,
            'type' => 'reguler',
            'title' => 'Gap pada Flexbox',
            'instruction' => 'Atur jarak antar elemen flex dengan gap: 20px',
            'starter_code' => '<!DOCTYPE html>\n<html>\n<head>\n<style>\n.container {\n    display: flex;\n    /* Tambahkan gap di sini */\n}\n</style>\n</head>\n<body>\n    <div class="container">\n        <div>Item 1</div>\n        <div>Item 2</div>\n        <div>Item 3</div>\n    </div>\n</body>\n</html>',
            'xp_reward' => 15,
            'checker_file' => 'checkers/m2_materi2_soal2.js',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $questions[] = [
            'material_id' => 5,
            'order' => 3,
            'type' => 'reguler',
            'title' => 'Tombol dengan Hover',
            'instruction' => 'Buat tombol dengan border-radius dan transisi warna saat hover',
            'starter_code' => '<!DOCTYPE html>\n<html>\n<head>\n<style>\n.btn {\n    /* Tambahkan style tombol */\n}\n.btn:hover {\n    /* Tambahkan style hover */\n}\n</style>\n</head>\n<body>\n    <button class="btn">Klik Saya</button>\n</body>\n</html>',
            'xp_reward' => 15,
            'checker_file' => 'checkers/m2_materi2_soal3.js',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $questions[] = [
            'material_id' => 5,
            'order' => 4,
            'type' => 'reguler',
            'title' => 'Layout Dua Kolom',
            'instruction' => 'Buat layout 2 kolom (sidebar + konten) memakai flexbox',
            'starter_code' => '<!DOCTYPE html>\n<html>\n<head>\n<style>\n/* Tambahkan CSS di sini */\n</style>\n</head>\n<body>\n    <div class="layout">\n        <aside class="sidebar">Sidebar</aside>\n        <main class="content">Konten Utama</main>\n    </div>\n</body>\n</html>',
            'xp_reward' => 20,
            'checker_file' => 'checkers/m2_materi2_soal4.js',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $questions[] = [
            'material_id' => 5,
            'order' => 5,
            'type' => 'bug_hunt',
            'title' => 'Kartu Interaktif (Bug Hunt)',
            'instruction' => 'Perbaiki bug pada kartu interaktif ini. Flexbox tidak bekerja dengan benar, hover tidak smooth, dan shadow tidak muncul.',
            'starter_code' => '<!DOCTYPE html>\n<html>\n<head>\n<style>\n.card-container {\n    display: block;\n}\n.card {\n    width: 200px;\n    padding: 20px;\n    background: white;\n    border-radius: 10px;\n}\n.card:hover {\n    box-shadow: 0 5px 15px rgba(0,0,0,0.3);\n    transform: scale(1.05);\n}\n</style>\n</head>\n<body>\n    <div class="card-container">\n        <div class="card">Kartu 1</div>\n        <div class="card">Kartu 2</div>\n        <div class="card">Kartu 3</div>\n    </div>\n</body>\n</html>',
            'xp_reward' => 25,
            'checker_file' => 'checkers/m2_materi2_soal5.js',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // ====================================
        // MODUL 3: JavaScript
        // ====================================
        
        // Materi 6: Dasar JavaScript (material_id: 6)
        $questions[] = [
            'material_id' => 6,
            'order' => 1,
            'type' => 'reguler',
            'title' => 'Variabel dan Output',
            'instruction' => 'Deklarasikan variabel nama, tampilkan ke <p id="output"> memakai innerText',
            'starter_code' => '<!DOCTYPE html>\n<html>\n<body>\n    <p id="output"></p>\n    <script>\n        // Tulis kode JavaScript di sini\n    </script>\n</body>\n</html>',
            'xp_reward' => 10,
            'checker_file' => 'checkers/m3_materi1_soal1.js',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $questions[] = [
            'material_id' => 6,
            'order' => 2,
            'type' => 'reguler',
            'title' => 'Menggabungkan Variabel',
            'instruction' => 'Gabungkan 2 variabel (nama, semester) jadi satu kalimat output',
            'starter_code' => '<!DOCTYPE html>\n<html>\n<body>\n    <p id="output"></p>\n    <script>\n        // Tulis kode JavaScript di sini\n    </script>\n</body>\n</html>',
            'xp_reward' => 10,
            'checker_file' => 'checkers/m3_materi1_soal2.js',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $questions[] = [
            'material_id' => 6,
            'order' => 3,
            'type' => 'reguler',
            'title' => 'Operator Aritmatika',
            'instruction' => 'Gunakan operator aritmatika sederhana (mis. jumlahkan 2 angka) dan tampilkan hasilnya',
            'starter_code' => '<!DOCTYPE html>\n<html>\n<body>\n    <p id="output"></p>\n    <script>\n        // Tulis kode JavaScript di sini\n    </script>\n</body>\n</html>',
            'xp_reward' => 15,
            'checker_file' => 'checkers/m3_materi1_soal3.js',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $questions[] = [
            'material_id' => 6,
            'order' => 4,
            'type' => 'reguler',
            'title' => 'Template Literal',
            'instruction' => 'Gunakan template literal (`${}`) untuk menyusun output dinamis',
            'starter_code' => '<!DOCTYPE html>\n<html>\n<body>\n    <p id="output"></p>\n    <script>\n        const nama = "Budi";\n        const umur = 20;\n        // Gunakan template literal\n    </script>\n</body>\n</html>',
            'xp_reward' => 15,
            'checker_file' => 'checkers/m3_materi1_soal4.js',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $questions[] = [
            'material_id' => 6,
            'order' => 5,
            'type' => 'reguler',
            'title' => 'Fungsi dengan Parameter',
            'instruction' => 'Buat fungsi sederhana yang menerima parameter dan mengembalikan string ke elemen output',
            'starter_code' => '<!DOCTYPE html>\n<html>\n<body>\n    <p id="output"></p>\n    <script>\n        // Buat fungsi di sini\n    </script>\n</body>\n</html>',
            'xp_reward' => 20,
            'checker_file' => 'checkers/m3_materi1_soal5.js',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Materi 7: Event Handling (material_id: 7)
        $questions[] = [
            'material_id' => 7,
            'order' => 1,
            'type' => 'reguler',
            'title' => 'Event Listener Klik',
            'instruction' => 'Tambahkan event listener klik pada <button> yang mengubah teks <p>',
            'starter_code' => '<!DOCTYPE html>\n<html>\n<body>\n    <button id="btn">Klik Saya</button>\n    <p id="teks">Teks awal</p>\n    <script>\n        // Tambahkan event listener di sini\n    </script>\n</body>\n</html>',
            'xp_reward' => 15,
            'checker_file' => 'checkers/m3_materi2_soal1.js',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $questions[] = [
            'material_id' => 7,
            'order' => 2,
            'type' => 'reguler',
            'title' => 'Dua Tombol Berbeda',
            'instruction' => 'Event listener pada 2 tombol berbeda dengan aksi berbeda',
            'starter_code' => '<!DOCTYPE html>\n<html>\n<body>\n    <button id="btn1">Tombol 1</button>\n    <button id="btn2">Tombol 2</button>\n    <p id="output">-</p>\n    <script>\n        // Tambahkan event listener untuk kedua tombol\n    </script>\n</body>\n</html>',
            'xp_reward' => 15,
            'checker_file' => 'checkers/m3_materi2_soal2.js',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $questions[] = [
            'material_id' => 7,
            'order' => 3,
            'type' => 'reguler',
            'title' => 'Event Keyup',
            'instruction' => 'Event keyup pada <input> yang menampilkan live preview teks',
            'starter_code' => '<!DOCTYPE html>\n<html>\n<body>\n    <input type="text" id="inputan">\n    <p id="preview"></p>\n    <script>\n        // Tambahkan event listener keyup\n    </script>\n</body>\n</html>',
            'xp_reward' => 15,
            'checker_file' => 'checkers/m3_materi2_soal3.js',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $questions[] = [
            'material_id' => 7,
            'order' => 4,
            'type' => 'reguler',
            'title' => 'Prevent Default Submit',
            'instruction' => 'Event submit pada form yang mencegah reload (preventDefault) dan menampilkan pesan',
            'starter_code' => '<!DOCTYPE html>\n<html>\n<body>\n    <form id="myForm">\n        <input type="text" name="nama">\n        <button type="submit">Submit</button>\n    </form>\n    <p id="pesan"></p>\n    <script>\n        // Tambahkan event listener submit\n    </script>\n</body>\n</html>',
            'xp_reward' => 20,
            'checker_file' => 'checkers/m3_materi2_soal4.js',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $questions[] = [
            'material_id' => 7,
            'order' => 5,
            'type' => 'clone',
            'title' => 'Kalkulator Sederhana (Clone Challenge)',
            'instruction' => 'Lihat target output kalkulator sederhana di panel preview. Buat dari nol dengan gabungan beberapa event (klik + input)',
            'starter_code' => '<!DOCTYPE html>\n<html>\n<body>\n    <!-- Buat kalkulator sederhana di sini -->\n    <script>\n        // Tulis JavaScript di sini\n    </script>\n</body>\n</html>',
            'xp_reward' => 25,
            'checker_file' => 'checkers/m3_materi2_soal5.js',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Materi 8: Manipulasi DOM (material_id: 8)
        $questions[] = [
            'material_id' => 8,
            'order' => 1,
            'type' => 'reguler',
            'title' => 'Ubah Background via JS',
            'instruction' => 'Ubah warna background elemen via JS saat tombol diklik',
            'starter_code' => '<!DOCTYPE html>\n<html>\n<body>\n    <div id="box" style="width:200px;height:200px;background:gray;"></div>\n    <button id="btnUbah">Ubah Warna</button>\n    <script>\n        // Tulis kode di sini\n    </script>\n</body>\n</html>',
            'xp_reward' => 10,
            'checker_file' => 'checkers/m3_materi3_soal1.js',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $questions[] = [
            'material_id' => 8,
            'order' => 2,
            'type' => 'reguler',
            'title' => 'Toggle Class',
            'instruction' => 'Tambah/hapus class pada elemen via JS (classList.toggle)',
            'starter_code' => '<!DOCTYPE html>\n<html>\n<head>\n<style>\n.active { color: red; font-weight: bold; }\n</style>\n</head>\n<body>\n    <p id="teks">Teks ini bisa berubah</p>\n    <button id="btnToggle">Toggle</button>\n    <script>\n        // Tulis kode di sini\n    </script>\n</body>\n</html>',
            'xp_reward' => 15,
            'checker_file' => 'checkers/m3_materi3_soal2.js',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $questions[] = [
            'material_id' => 8,
            'order' => 3,
            'type' => 'reguler',
            'title' => 'Toggle Display',
            'instruction' => 'Tampilkan/sembunyikan elemen (toggle display) via tombol',
            'starter_code' => '<!DOCTYPE html>\n<html>\n<body>\n    <div id="konten">Konten yang bisa disembunyikan</div>\n    <button id="btnToggle">Tampil/Sembunyikan</button>\n    <script>\n        // Tulis kode di sini\n    </script>\n</body>\n</html>',
            'xp_reward' => 15,
            'checker_file' => 'checkers/m3_materi3_soal3.js',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $questions[] = [
            'material_id' => 8,
            'order' => 4,
            'type' => 'reguler',
            'title' => 'Validasi Input dengan If/Else',
            'instruction' => 'Gunakan if/else untuk validasi input (mis. cek usia >= 17)',
            'starter_code' => '<!DOCTYPE html>\n<html>\n<body>\n    <input type="number" id="usia" placeholder="Masukkan usia">\n    <button id="btnCek">Cek</button>\n    <p id="hasil"></p>\n    <script>\n        // Tulis kode di sini\n    </script>\n</body>\n</html>',
            'xp_reward' => 20,
            'checker_file' => 'checkers/m3_materi3_soal4.js',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $questions[] = [
            'material_id' => 8,
            'order' => 5,
            'type' => 'clone',
            'title' => 'Todo List Sederhana (Clone Challenge)',
            'instruction' => 'Lihat target output todo list sederhana. Gunakan perulangan for untuk menampilkan daftar dinamis ke <ul>',
            'starter_code' => '<!DOCTYPE html>\n<html>\n<body>\n    <!-- Buat todo list sederhana di sini -->\n    <script>\n        // Tulis kode di sini\n    </script>\n</body>\n</html>',
            'xp_reward' => 20,
            'checker_file' => 'checkers/m3_materi3_soal5.js',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        DB::table('questions')->insert($questions);
    }
}
