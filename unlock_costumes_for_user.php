<?php
/**
 * Script: unlock_costumes_for_user.php
 * Jalankan: php unlock_costumes_for_user.php
 * Fungsi: Trigger unlock kostum untuk user berdasarkan progress saat ini
 */

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$userId = 11; // testuser01 — ganti jika perlu

$user = App\Models\User::find($userId);
if (!$user) {
    echo "User ID $userId tidak ditemukan!\n";
    exit(1);
}

echo "User: {$user->nickname} | XP: {$user->getTotalXP()} | Soal: {$user->getCompletedQuestionsCount()}/{$user->getTotalQuestionsCount()}\n";
echo str_repeat("=", 60) . "\n";

$beforeCount = $user->costumes()->count();

// Jalankan pengecekan unlock kostum
$allCostumes = App\Models\Costume::all();
$unlocked = 0;
$skipped  = 0;

foreach ($allCostumes as $costume) {
    if ($costume->isUnlockedBy($user)) {
        $skipped++;
        continue;
    }

    if ($costume->canBeUnlockedBy($user)) {
        $user->costumes()->attach($costume->id, [
            'unlocked_at' => now(),
            'is_active'   => false,
        ]);
        echo "✅ Unlocked: [{$costume->unlock_type}] {$costume->name} (ID={$costume->id})\n";
        $unlocked++;
    } else {
        $xp = $user->getTotalXP();
        $req = $costume->unlock_requirement;
        echo "🔒 Masih terkunci: {$costume->name} [{$costume->unlock_type}] req={$req} | XP user={$xp}\n";
    }
}

$afterCount = $user->costumes()->count();

echo str_repeat("=", 60) . "\n";
echo "Sebelum : $beforeCount kostum\n";
echo "Sesudah : $afterCount kostum\n";
echo "Di-unlock: $unlocked kostum baru\n";
echo "Skipped  : $skipped kostum (sudah unlock sebelumnya)\n";

