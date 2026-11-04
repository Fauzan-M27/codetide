<?php
/**
 * Simple Duplicates Fix - Remove IDs 20-38 (costumes) and 5-8 (badges)
 */

echo "╔═══════════════════════════════════════════════════════════════╗\n";
echo "║         Fixing Database Duplicates - Simple Cleanup           ║\n";
echo "╚═══════════════════════════════════════════════════════════════╝\n\n";

$pdo = new PDO('mysql:host=127.0.0.1;dbname=codetide', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

echo "BEFORE:\n";
echo "- Costumes: " . $pdo->query('SELECT COUNT(*) FROM costumes')->fetchColumn() . "\n";
echo "- Badges: " . $pdo->query('SELECT COUNT(*) FROM badges')->fetchColumn() . "\n\n";

// 1. Delete ownership records for duplicate costumes
echo "Step 1: Cleaning user_costumes table...\n";
$deleted = $pdo->exec('DELETE FROM user_costumes WHERE costume_id >= 20');
echo "  ✓ Deleted $deleted ownership records\n\n";

// 2. Delete duplicate costumes
echo "Step 2: Deleting duplicate costumes (ID 20-38)...\n";
$deleted = $pdo->exec('DELETE FROM costumes WHERE id >= 20');
echo "  ✓ Deleted $deleted costumes\n\n";

// 3. Delete user badge awards for duplicates
echo "Step 3: Cleaning user_badges table...\n";
$deleted = $pdo->exec('DELETE FROM user_badges WHERE badge_id >= 5');
echo "  ✓ Deleted $deleted badge awards\n\n";

// 4. Delete duplicate badges
echo "Step 4: Deleting duplicate badges (ID 5-8)...\n";
$deleted = $pdo->exec('DELETE FROM badges WHERE id >= 5');
echo "  ✓ Deleted $deleted badges\n\n";

echo "═══════════════════════════════════════════════════════════════\n";
echo " VERIFICATION\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

$finalCostumes = $pdo->query('SELECT COUNT(*) FROM costumes')->fetchColumn();
$finalBadges = $pdo->query('SELECT COUNT(*) FROM badges')->fetchColumn();

echo "AFTER:\n";
echo ($finalCostumes == 19 ? "✓" : "✗") . " Costumes: $finalCostumes (expected 19)\n";
echo ($finalBadges == 4 ? "✓" : "✗") . " Badges: $finalBadges (expected 4)\n\n";

if ($finalCostumes == 19 && $finalBadges == 4) {
    echo "╔═══════════════════════════════════════════════════════════════╗\n";
    echo "║                 ✓ CLEANUP SUCCESSFUL                           ║\n";
    echo "║          Database is now ready for launch! 🚀                 ║\n";
    echo "╚═══════════════════════════════════════════════════════════════╝\n";
    
    echo "\nFinal database state:\n";
    echo "- 3 Modules (HTML, CSS, JavaScript)\n";
    echo "- 8 Materials\n";
    echo "- 40 Questions\n";
    echo "- 19 Costumes\n";
    echo "- 4 Badges\n";
} else {
    echo "╔═══════════════════════════════════════════════════════════════╗\n";
    echo "║               ✗ CLEANUP INCOMPLETE                             ║\n";
    echo "║            Please check the numbers above                     ║\n";
    echo "╚═══════════════════════════════════════════════════════════════╝\n";
}
