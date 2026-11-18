<?php

// Fix starter code - replace \n literal dengan actual newline
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$questions = DB::table('questions')->get();

foreach ($questions as $question) {
    $fixed = str_replace('\n', "\n", $question->starter_code);
    $fixed = str_replace('\t', "\t", $fixed);
    
    DB::table('questions')
        ->where('id', $question->id)
        ->update(['starter_code' => $fixed]);
    
    echo "Fixed question #{$question->id}: {$question->title}\n";
}

echo "\nDone! Fixed " . count($questions) . " questions.\n";
