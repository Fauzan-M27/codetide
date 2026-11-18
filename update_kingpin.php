<?php
/**
 * Update Kingpin (ID=15): dari xp=1500 → badge complete_all (badge ID=4)
 */
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Update costume ID=15
$updated = DB::table('costumes')
    ->where('id', 15)
    ->update([
        'unlock_type'        => 'badge',
        'unlock_requirement' => 4, // Badge ID=4: Web Developer Pemula (complete_all)
        'updated_at'         => now(),
    ]);

echo $updated
    ? "✅ Kingpin berhasil diupdate: unlock_type=badge, unlock_requirement=4 (complete_all)\n"
    : "❌ Gagal update!\n";

// Verifikasi
$c = DB::table('costumes')->find(15);
echo "\nKostum ID=15 setelah update:\n";
echo "  name            : {$c->name}\n";
echo "  unlock_type     : {$c->unlock_type}\n";
echo "  unlock_requirement: {$c->unlock_requirement}\n";

// Langsung cek testuser01
$user = App\Models\User::find(11);
$costume = App\Models\Costume::find(15);
$canUnlock = $costume->canBeUnlockedBy($user);
$isUnlocked = $costume->isUnlockedBy($user);

echo "\nCek testuser01 (ID=11):\n";
echo "  canBeUnlockedBy : " . ($canUnlock ? "✅ YA (memenuhi syarat)" : "❌ TIDAK") . "\n";
echo "  isUnlockedBy    : " . ($isUnlocked ? "✅ SUDAH UNLOCK" : "❌ BELUM") . "\n";

// Kalau bisa unlock, langsung unlock sekarang
if ($canUnlock && !$isUnlocked) {
    $user->costumes()->attach(15, [
        'unlocked_at' => now(),
        'is_active'   => false,
    ]);
    echo "  → ✅ Langsung di-unlock untuk testuser01!\n";
}

echo "\nTotal kostum testuser01: " . $user->costumes()->count() . "/19\n";

