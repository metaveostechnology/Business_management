<?php

use App\Models\BranchUser;

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$employees = BranchUser::where('is_dept_admin', 0)
    ->where('is_branch_admin', 0)
    ->where('is_delete', 0)
    ->with('department')
    ->get();

echo "ID | Name | Email | Dept | Active\n";
echo str_repeat("-", 60) . "\n";
foreach ($employees as $emp) {
    echo "{$emp->id} | {$emp->name} | {$emp->email} | " . ($emp->department->name ?? 'NONE') . " | " . ($emp->is_active ? 'YES' : 'NO') . "\n";
}
