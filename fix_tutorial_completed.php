<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Tampilkan status tutorial semua user
echo "=== Status tutorial_completed semua user ===\n";
$users = \App\Models\User::all(['id', 'nickname', 'tutorial_completed']);
foreach ($users as $u) {
    $status = $u->tutorial_completed ? 'true' : 'false';
    echo "ID={$u->id} | {$u->nickname} | tutorial_completed={$status}\n";
}

// Set tutorial_completed = true untuk testuser01 (ID=11) 
// sehingga tutorial tidak muncul lagi setelah login
echo "\n=== Set tutorial_completed = true untuk testuser01 ===\n";
$testUser = \App\Models\User::where('nickname', 'like', '%testuser01%')->first();
if (!$testUser) {
    // Coba cari berdasarkan participant_code
    $testUser = \App\Models\User::where('participant_code', 'CP-261112-F2E3')->first();
}
if ($testUser) {
    $testUser->tutorial_completed = true;
    $testUser->save();
    echo "Berhasil: ID={$testUser->id} | {$testUser->nickname} | tutorial_completed=true\n";
} else {
    echo "User testuser01 tidak ditemukan!\n";
}

echo "\n=== Status setelah update ===\n";
$users = \App\Models\User::all(['id', 'nickname', 'tutorial_completed']);
foreach ($users as $u) {
    $status = $u->tutorial_completed ? 'true' : 'false';
    echo "ID={$u->id} | {$u->nickname} | tutorial_completed={$status}\n";
}

