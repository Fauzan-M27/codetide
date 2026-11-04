<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=codetide', 'root', '');

echo "=== QUESTIONS TABLE COLUMNS ===\n";
$columns = $pdo->query('DESCRIBE questions')->fetchAll(PDO::FETCH_ASSOC);
foreach ($columns as $col) {
    echo "- {$col['Field']} ({$col['Type']})\n";
}

echo "\n=== SAMPLE QUESTION ===\n";
$sample = $pdo->query('SELECT * FROM questions LIMIT 1')->fetch(PDO::FETCH_ASSOC);
foreach ($sample as $key => $value) {
    $preview = strlen($value) > 100 ? substr($value, 0, 100) . '...' : $value;
    echo "{$key}: {$preview}\n";
}
