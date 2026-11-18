<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Cek kolom tabel users yang berhubungan dengan tutorial
$cols = DB::select('DESCRIBE users');
echo "=== Kolom di tabel users ===\n";
foreach ($cols as $c) {
    echo $c->Field . ' | ' . $c->Type . ' | default: ' . $c->Default . "\n";
}

// Cek nilai tutorial_completed untuk semua user
echo "\n=== Tutorial completed status per user ===\n";
$users = DB::select('SELECT id, nickname, tutorial_completed FROM users ORDER BY id');
foreach ($users as $u) {
    echo "ID={$u->id} | {$u->nickname} | tutorial_completed=" . var_export($u->tutorial_completed, true) . "\n";
}

