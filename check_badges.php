<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "BADGES:\n";
$badges = DB::table('badges')->get();
foreach ($badges as $b) {
    echo "ID={$b->id} [{$b->requirement_type}] req_val={$b->requirement_value} {$b->name}\n";
}

echo "\nKOSTUM ID=15:\n";
$c = DB::table('costumes')->find(15);
echo "name={$c->name} unlock_type={$c->unlock_type} req={$c->unlock_requirement}\n";

echo "\nENUM unlock_type yang tersedia di model: default, badge, xp, module\n";

