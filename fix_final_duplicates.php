<?php
/**
 * Fix Costumes & Badges Duplicates
 * Removes IDs 20-38 (costumes) and IDs 5-8 (badges)
 */

echo "╔═══════════════════════════════════════════════════════════════╗\n";
echo "║     Fixing Costumes & Badges Duplicates - Final Cleanup       ║\n";
echo "╚═══════════════════════════════════════════════════════════════╝\n\n";

$pdo = new PDO('mysql:host=127.0.0.1;dbname=codetide', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

echo "Current state:\n";
$costumeCount = $pdo->query('SELECT COUNT(*) FROM costumes')->fetchColumn();
$badgeCount = $pdo->query('SELECT COUNT(*) FROM badges')->fetchColumn();
echo "- Costumes: $costumeCount (should be 19)\n";
echo "- Badges: $badgeCount (should be 4)\n\n";

echo "═══════════════════════════════════════════════════════════════\n";
echo " CLEANING COSTUMES (Keep ID 1-19, Delete ID 20-38)\n";
echo "═══════════════════════════════════════════════════════════════\n";

// Check if any user has these costumes equipped
$usersWithDuplicateCostumes = $pdo->query('
    SELECT COUNT(*) FROM users WHERE active_costume_id >= 20
')->fetchColumn();

if ($usersWithDuplicateCostumes > 0) {
    echo "⚠ Warning: $usersWithDuplicateCostumes users have duplicate costumes equipped\n";
    echo "  Resetting their active costume to ID 1 (default)...\n";
    
    $pdo->exec('UPDATE users SET active_costume_id = 1 WHERE active_costume_id >= 20');
    echo "  ✓ Reset complete\n\n";
}

// Check if any users own these costumes
$ownerships = $pdo->query('
    SELECT COUNT(*) FROM user_costumes WHERE costume_id >= 20
')->fetchColumn();

if ($ownerships > 0) {
    echo "⚠ Warning: $ownerships ownership records for duplicate costumes\n";
    echo "  Deleting duplicate ownerships...\n";
    
    $pdo->exec('DELETE FROM user_costumes WHERE costume_id >= 20');
    echo "  ✓ Deleted $ownerships records\n\n";
}

// Delete duplicate costumes
$pdo->exec('DELETE FROM costumes WHERE id >= 20');
$deleted = $pdo->query('SELECT COUNT(*) FROM costumes')->fetchColumn();
echo "✓ Costumes cleaned: $deleted remaining (expected 19)\n\n";

echo "═══════════════════════════════════════════════════════════════\n";
echo " CLEANING BADGES (Keep ID 1-4, Delete ID 5-8)\n";
echo "═══════════════════════════════════════════════════════════════\n";

// Check if any users have these duplicate badges
$userBadges = $pdo->query('
    SELECT COUNT(*) FROM user_badges WHERE badge_id >= 5
')->fetchColumn();

if ($userBadges > 0) {
    echo "⚠ Warning: $userBadges user badge records for duplicates\n";
    echo "  Deleting duplicate badge awards...\n";
    
    $pdo->exec('DELETE FROM user_badges WHERE badge_id >= 5');
    echo "  ✓ Deleted $userBadges records\n\n";
}

// Delete duplicate badges
$pdo->exec('DELETE FROM badges WHERE id >= 5');
$deleted = $pdo->query('SELECT COUNT(*) FROM badges')->fetchColumn();
echo "✓ Badges cleaned: $deleted remaining (expected 4)\n\n";

echo "═══════════════════════════════════════════════════════════════\n";
echo " VERIFICATION\n";
echo "═══════════════════════════════════════════════════════════════\n";

$finalCostumes = $pdo->query('SELECT COUNT(*) FROM costumes')->fetchColumn();
$finalBadges = $pdo->query('SELECT COUNT(*) FROM badges')->fetchColumn();

echo "Final state:\n";
echo ($finalCostumes == 19 ? "✓" : "✗") . " Costumes: $finalCostumes\n";
echo ($finalBadges == 4 ? "✓" : "✗") . " Badges: $finalBadges\n\n";

// Show remaining costumes
echo "Remaining costumes:\n";
$costumes = $pdo->query('SELECT id, name, price FROM costumes ORDER BY id')->fetchAll(PDO::FETCH_ASSOC);
foreach ($costumes as $costume) {
    echo "  ID {$costume['id']}: {$costume['name']} ({$costume['price']} coins)\n";
}

echo "\nRemaining badges:\n";
$badges = $pdo->query('SELECT id, name FROM badges ORDER BY id')->fetchAll(PDO::FETCH_ASSOC);
foreach ($badges as $badge) {
    echo "  ID {$badge['id']}: {$badge['name']}\n";
}

echo "\n";
if ($finalCostumes == 19 && $finalBadges == 4) {
    echo "╔═══════════════════════════════════════════════════════════════╗\n";
    echo "║                ✓ CLEANUP SUCCESSFUL                            ║\n";
    echo "║         Database is now ready for launch! 🚀                  ║\n";
    echo "╚═══════════════════════════════════════════════════════════════╝\n";
} else {
    echo "╔═══════════════════════════════════════════════════════════════╗\n";
    echo "║              ✗ CLEANUP INCOMPLETE                              ║\n";
    echo "║           Please review the output above                      ║\n";
    echo "╚═══════════════════════════════════════════════════════════════╝\n";
}
