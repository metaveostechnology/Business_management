<?php

use App\Models\BranchUser;

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$employees = BranchUser::get(['id', 'email']);

foreach ($employees as $emp) {
    echo "ID: {$emp->id}, Email: '{$emp->email}' (Length: " . strlen($emp->email) . ")\n";
}
