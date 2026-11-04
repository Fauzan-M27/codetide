<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MaterialContentSeeder extends Seeder
{
    /**
     * Mengisi kolom `content` pada tabel materials dengan konten HTML
     * dari 8 file materi yang tersimpan di database/seeders/materi-content/.
     *
     * Pemetaan: order materi → file HTML.
     * Jalankan: php artisan db:seed --class=MaterialContentSeeder
     */
    public function run(): void
    {
        // Peta: order material => nama file HTML di folder materi-content/
        $map = [
            1 => 'materi-1-struktur-dasar-html.html',
            2 => 'materi-2-html-lanjut.html',
            3 => 'materi-3-html-interaktif.html',
            4 => 'materi-4-dasar-css.html',
            5 => 'materi-5-layout-css.html',
            6 => 'materi-6-dasar-javascript.html',
            7 => 'materi-7-event-handling.html',
            8 => 'materi-8-manipulasi-dom.html',
        ];

        $baseDir = database_path('seeders/materi-content');

        // Ambil semua material diurutkan per module lalu per order
        $materials = DB::table('materials')
            ->orderBy('module_id')
            ->orderBy('order')
            ->get(['id', 'module_id', 'title', 'order']);

        // Buat indeks global (1-8) dari urutan module & order
        $globalIndex = 1;
        foreach ($materials as $material) {
            if (!isset($map[$globalIndex])) {
                $globalIndex++;
                continue;
            }

            $filePath = $baseDir . DIRECTORY_SEPARATOR . $map[$globalIndex];

            if (!file_exists($filePath)) {
                $this->command->warn("File tidak ditemukan: {$filePath}");
                $globalIndex++;
                continue;
            }

            $content = file_get_contents($filePath);

            DB::table('materials')
                ->where('id', $material->id)
                ->update(['content' => $content]);

            $this->command->info("✓ [{$globalIndex}] Konten diisi untuk: \"{$material->title}\" (ID {$material->id})");
            $globalIndex++;
        }

        $this->command->info('MaterialContentSeeder selesai.');
    }
}
