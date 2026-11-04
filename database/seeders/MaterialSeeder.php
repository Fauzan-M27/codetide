<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $materials = [
            // Modul 1: HTML (3 materi)
            [
                'module_id' => 1,
                'title' => 'Struktur Dasar HTML',
                'description' => 'Mempelajari struktur dasar HTML: head, title, body, heading, dan paragraf',
                'order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'module_id' => 1,
                'title' => 'HTML Lanjut — Link, Frame, Tabel',
                'description' => 'Mempelajari elemen HTML lanjut: anchor, iframe, dan tabel',
                'order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'module_id' => 1,
                'title' => 'HTML Interaktif — Form & Media',
                'description' => 'Mempelajari form input, select, textarea, button, dan elemen media',
                'order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Modul 2: CSS (2 materi)
            [
                'module_id' => 2,
                'title' => 'Dasar CSS — Selector & Properti',
                'description' => 'Mempelajari selector CSS, warna, font, padding, dan margin',
                'order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'module_id' => 2,
                'title' => 'Layout & Interaksi CSS — Flexbox & Hover',
                'description' => 'Mempelajari flexbox, hover state, dan transisi CSS',
                'order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Modul 3: JavaScript (3 materi)
            [
                'module_id' => 3,
                'title' => 'Dasar JavaScript — Variabel & Output',
                'description' => 'Mempelajari variabel, operator, template literal, dan fungsi dasar',
                'order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'module_id' => 3,
                'title' => 'Event Handling',
                'description' => 'Mempelajari event listener untuk klik, input, dan submit',
                'order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'module_id' => 3,
                'title' => 'Manipulasi DOM & Logika Sederhana',
                'description' => 'Mempelajari manipulasi elemen DOM, if/else, dan perulangan',
                'order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('materials')->insert($materials);
    }
}
