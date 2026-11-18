<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Hanya seed jika tabel modules masih kosong (idempotent — aman dijalankan berulang)
        if (\DB::table('modules')->count() === 0) {
            $this->call([
                ModuleSeeder::class,
                MaterialSeeder::class,
                QuestionSeeder::class,
                BadgeSeeder::class,
                CostumeSeeder::class,
            ]);
        } else {
            $this->command->info('Data sudah ada, skip seeding.');
            return;
        }
        
        // Update question_type based on material_id
        \DB::table('questions')
            ->whereIn('material_id', [1, 2, 3])
            ->update(['question_type' => 'html']);
        
        \DB::table('questions')
            ->whereIn('material_id', [4, 5])
            ->update(['question_type' => 'css']);
        
        \DB::table('questions')
            ->whereIn('material_id', [6, 7, 8])
            ->update(['question_type' => 'javascript']);
    }
}
