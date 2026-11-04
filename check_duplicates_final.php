<?php
// Check costumes and badges duplicates

$pdo = new PDO('mysql:host=127.0.0.1;dbname=codetide', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

echo "=== COSTUMES ===\n";
$costumes = $pdo->query('SELECT id, name FROM costumes ORDER BY id')->fetchAll(PDO::FETCH_ASSOC);
foreach ($costumes as $costume) {
    echo "ID {$costume['id']}: {$costume['name']}\n";
}

echo "\n=== BADGES ===\n";
$badges = $pdo->query('SELECT id, name FROM badges ORDER BY id')->fetchAll(PDO::FETCH_ASSOC);
foreach ($badges as $badge) {
    echo "ID {$badge['id']}: {$badge['name']}\n";
}

echo "\n=== DUPLICATE CHECK ===\n";
$costumeDuplicates = $pdo->query('
    SELECT name, COUNT(*) as count 
    FROM costumes 
    GROUP BY name 
    HAVING count > 1
')->fetchAll(PDO::FETCH_ASSOC);

if (empty($costumeDuplicates)) {
    echo "✓ No costume duplicates found\n";
} else {
    echo "✗ Costume duplicates:\n";
    foreach ($costumeDuplicates as $dup) {
        echo "  - {$dup['name']}: {$dup['count']}x\n";
    }
}

$badgeDuplicates = $pdo->query('
    SELECT name, COUNT(*) as count 
    FROM badges 
    GROUP BY name 
    HAVING count > 1
')->fetchAll(PDO::FETCH_ASSOC);

if (empty($badgeDuplicates)) {
    echo "✓ No badge duplicates found\n";
} else {
    echo "✗ Badge duplicates:\n";
    foreach ($badgeDuplicates as $dup) {
        echo "  - {$dup['name']}: {$dup['count']}x\n";
    }
}
