<?php

use App\Models\BranchUser;

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$inactive = BranchUser::where('is_active', 0)->get(['id', 'name', 'email']);

foreach ($inactive as $emp) {
    echo "ID: {$emp->id}, Name: {$emp->name}, Email: {$emp->email} is INACTIVE\n";
}
