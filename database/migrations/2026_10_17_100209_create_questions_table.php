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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_id')->constrained()->onDelete('cascade');
            $table->integer('order');
            $table->enum('type', ['reguler', 'bug_hunt', 'clone', 'time_mode'])->default('reguler');
            $table->string('title');
            $table->text('instruction');
            $table->text('starter_code')->nullable();
            $table->integer('xp_reward')->default(10);
            $table->string('checker_file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
