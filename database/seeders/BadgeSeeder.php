<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BadgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $badges = [
            [
                'name' => 'HTML Explorer',
                'description' => 'Selesaikan seluruh modul HTML',
                'icon' => 'html-badge.svg',
                'requirement_type' => 'complete_module',
                'requirement_value' => 1, // module_id untuk HTML
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'CSS Stylist',
                'description' => 'Selesaikan seluruh modul CSS',
                'icon' => 'css-badge.svg',
                'requirement_type' => 'complete_module',
                'requirement_value' => 2, // module_id untuk CSS
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'JS Interactor',
                'description' => 'Selesaikan seluruh modul JavaScript',
                'icon' => 'js-badge.svg',
                'requirement_type' => 'complete_module',
                'requirement_value' => 3, // module_id untuk JavaScript
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Web Developer Pemula',
                'description' => 'Selesaikan semua modul (HTML, CSS, dan JavaScript)',
                'icon' => 'master-badge.svg',
                'requirement_type' => 'complete_all',
                'requirement_value' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('badges')->insert($badges);
    }
}
