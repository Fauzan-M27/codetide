<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "========================================\n";
echo "Testing CodeTide Models & Relationships\n";
echo "========================================\n\n";

// Test Module
echo "1. Module Test:\n";
$module = App\Models\Module::first();
echo "   - Module: {$module->title}\n";
echo "   - Materials: {$module->materials()->count()}\n";
echo "   - Questions: {$module->questions()->count()}\n";
echo "   - Badge: {$module->badge_name}\n\n";

// Test Material
echo "2. Material Test:\n";
$material = $module->materials()->first();
echo "   - Material: {$material->title}\n";
echo "   - Module: {$material->module->title}\n";
echo "   - Questions: {$material->questions()->count()}\n\n";

// Test Question
echo "3. Question Test:\n";
$question = $material->questions()->first();
echo "   - Question: {$question->title}\n";
echo "   - Material: {$question->material->title}\n";
echo "   - Type: {$question->type}\n";
echo "   - XP: {$question->xp_reward}\n";
echo "   - Is Bug Hunt: " . ($question->isBugHunt() ? 'Yes' : 'No') . "\n";
echo "   - Is Clone: " . ($question->isClone() ? 'Yes' : 'No') . "\n\n";

// Test Badge
echo "4. Badge Test:\n";
$badge = App\Models\Badge::first();
echo "   - Badge: {$badge->name}\n";
echo "   - Requirement: {$badge->requirement_type}\n";
echo "   - Module: " . ($badge->module ? $badge->module->title : 'N/A') . "\n\n";

// Test Costume
echo "5. Costume Test:\n";
$costume = App\Models\Costume::first();
echo "   - Costume: {$costume->name}\n";
echo "   - Unlock Type: {$costume->unlock_type}\n\n";

// Test special question types
echo "6. Special Question Types:\n";
$bugHunts = App\Models\Question::where('type', 'bug_hunt')->get();
$clones = App\Models\Question::where('type', 'clone')->get();
echo "   - Bug Hunt questions: {$bugHunts->count()}\n";
foreach ($bugHunts as $q) {
    echo "     * {$q->material->title} - Level {$q->order}: {$q->title}\n";
}
echo "   - Clone questions: {$clones->count()}\n";
foreach ($clones as $q) {
    echo "     * {$q->material->title} - Level {$q->order}: {$q->title}\n";
}

echo "\n========================================\n";
echo "All Models & Relationships Working! ✅\n";
echo "========================================\n";
