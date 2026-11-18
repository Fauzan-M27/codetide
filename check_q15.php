<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$q = DB::table('questions')->find(15);
echo "Q15: id={$q->id} order={$q->order} material_id={$q->material_id} title={$q->title}\n";
echo "\nSemua soal di material {$q->material_id}:\n";
$qs = DB::table('questions')->where('material_id', $q->material_id)->orderBy('order')->get();
foreach($qs as $qq) {
    echo "  order={$qq->order} id={$qq->id} [{$qq->type}] {$qq->title}\n";
}

// Juga cek starter_code
echo "\nStarter code Q15:\n";
echo $q->starter_code . "\n";

