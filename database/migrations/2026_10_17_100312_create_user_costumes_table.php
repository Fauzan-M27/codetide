<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_costumes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('costume_id')->constrained()->onDelete('cascade');
            $table->timestamp('unlocked_at');
            $table->boolean('is_active')->default(false);
            $table->timestamps();
            
            // Ensure one record per user per costume
            $table->unique(['user_id', 'costume_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_costumes');
    }
};
