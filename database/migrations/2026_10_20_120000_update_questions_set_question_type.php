<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Set question_type based on material_id
        // Material 1-3: HTML (material from Module 1)
        DB::table('questions')
            ->whereIn('material_id', [1, 2, 3])
            ->update(['question_type' => 'html']);
        
        // Material 4-5: CSS (material from Module 2)
        DB::table('questions')
            ->whereIn('material_id', [4, 5])
            ->update(['question_type' => 'css']);
        
        // Material 6-8: JavaScript (material from Module 3)
        DB::table('questions')
            ->whereIn('material_id', [6, 7, 8])
            ->update(['question_type' => 'javascript']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert all to default html
        DB::table('questions')->update(['question_type' => 'html']);
    }
};
