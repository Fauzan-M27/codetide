<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$userId = 11; // testuser01
$user = App\Models\User::find($userId);

echo "=== USER INFO ===\n";
echo "User: {$user->nickname} (ID: {$user->id})\n";
echo "Total XP: " . $user->getTotalXP() . "\n";
echo "Completed questions: " . $user->getCompletedQuestionsCount() . "/" . $user->getTotalQuestionsCount() . "\n\n";

echo "=== SEMUA KOSTUM & STATUS UNLOCK ===\n";
$costumes = App\Models\Costume::all();
$unlockedIds = $user->costumes()->pluck('costumes.id')->toArray();

foreach ($costumes as $c) {
    $isUnlocked = in_array($c->id, $unlockedIds);
    $canUnlock = $c->canBeUnlockedBy($user);
    echo "ID={$c->id} [{$c->unlock_type}] {$c->name}\n";
    echo "  unlock_requirement: {$c->unlock_requirement}\n";
    echo "  Sudah unlock: " . ($isUnlocked ? "✅ YA" : "❌ BELUM") . "\n";
    echo "  canBeUnlockedBy: " . ($canUnlock ? "✅ BISA" : "❌ TIDAK BISA") . "\n";
    echo "\n";
}

echo "=== KOSTUM YANG SUDAH DI-UNLOCK USER ===\n";
$userCostumes = $user->costumes()->withPivot('unlocked_at','is_active')->get();
foreach ($userCostumes as $uc) {
    echo "ID={$uc->id} {$uc->name} | active=" . ($uc->pivot->is_active ? 'YES' : 'no') . " | unlocked_at={$uc->pivot->unlocked_at}\n";
}

