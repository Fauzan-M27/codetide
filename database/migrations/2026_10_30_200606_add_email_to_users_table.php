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
        // Add email column to users table (nullable first for existing data)
        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->nullable()->after('nickname');
        });
        
        // Update existing users with placeholder emails if needed
        // Generate email based on participant_code for existing users
        $users = \DB::table('users')->whereNull('email')->get();
        foreach ($users as $user) {
            \DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'email' => strtolower(str_replace('-', '', $user->participant_code)) . '@codetide.local'
                ]);
        }
        
        // Now make email unique
        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->unique()->change();
        });
        
        // Update password_reset_tokens table to use email instead of participant_code
        Schema::table('password_reset_tokens', function (Blueprint $table) {
            $table->dropPrimary(); // Drop old primary key
        });
        
        Schema::table('password_reset_tokens', function (Blueprint $table) {
            $table->dropColumn('participant_code');
            $table->string('email')->primary()->first();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('email');
        });
        
        // Revert password_reset_tokens table
        Schema::table('password_reset_tokens', function (Blueprint $table) {
            $table->dropPrimary();
            $table->dropColumn('email');
        });
        
        Schema::table('password_reset_tokens', function (Blueprint $table) {
            $table->string('participant_code')->primary()->first();
        });
    }
};
