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
        Schema::table('costumes', function (Blueprint $table) {
            // Character info
            $table->string('character_name')->nullable()->after('name');
            $table->text('description')->nullable()->after('character_name');
            $table->string('avatar_icon')->nullable()->after('icon');
            
            // Animation sprite paths (user can upload from Memao)
            $table->string('sprite_idle')->nullable();
            $table->string('sprite_typing')->nullable();
            $table->string('sprite_thinking')->nullable();
            $table->string('sprite_success')->nullable();
            $table->string('sprite_error')->nullable();
            
            // Sprite sheet metadata
            $table->integer('sprite_width')->default(64);
            $table->integer('sprite_height')->default(64);
            $table->string('sprite_format')->default('png'); // png, gif
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('costumes', function (Blueprint $table) {
            $table->dropColumn([
                'character_name',
                'description',
                'avatar_icon',
                'sprite_idle',
                'sprite_typing',
                'sprite_thinking',
                'sprite_success',
                'sprite_error',
                'sprite_width',
                'sprite_height',
                'sprite_format',
            ]);
        });
    }
};
