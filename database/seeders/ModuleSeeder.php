<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules = [
            [
                'title' => 'HTML',
                'description' => 'Modul pembelajaran HTML dasar hingga lanjut',
                'order' => 1,
                'badge_name' => 'HTML Explorer',
                'badge_icon' => 'html-badge.svg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'CSS',
                'description' => 'Modul pembelajaran CSS styling dan layout',
                'order' => 2,
                'badge_name' => 'CSS Stylist',
                'badge_icon' => 'css-badge.svg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'JavaScript',
                'description' => 'Modul pembelajaran JavaScript dasar dan manipulasi DOM',
                'order' => 3,
                'badge_name' => 'JS Interactor',
                'badge_icon' => 'js-badge.svg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('modules')->insert($modules);
    }
}
