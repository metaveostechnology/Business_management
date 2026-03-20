<?php

use App\Models\BranchUser;

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$totalEmployees = BranchUser::where('is_dept_admin', 0)->where('is_branch_admin', 0)->count();
$validDeptEmployees = BranchUser::where('is_dept_admin', 0)
    ->where('is_branch_admin', 0)
    ->whereNotNull('dept_id')
    ->where('dept_id', '>', 0)
    ->count();

$inactiveEmployees = BranchUser::where('is_dept_admin', 0)->where('is_branch_admin', 0)->where('is_active', 0)->count();

echo "Total Employees: $totalEmployees\n";
echo "Employees with Valid Dept ID: $validDeptEmployees\n";
echo "Inactive Employees: $inactiveEmployees\n";

if ($totalEmployees > $validDeptEmployees) {
    echo "WARNING: There are employees without a valid Department ID. They will NOT be able to login.\n";
    $missingDepts = BranchUser::where('is_dept_admin', 0)
        ->where('is_branch_admin', 0)
        ->where(function($q) {
            $q->whereNull('dept_id')->orWhere('dept_id', '<=', 0);
        })
        ->get(['id', 'name', 'email', 'dept_id']);
    
    foreach ($missingDepts as $emp) {
        echo " - ID: {$emp->id}, Name: {$emp->name}, Email: {$emp->email}, Dept ID: " . ($emp->dept_id ?? 'NULL') . "\n";
    }
}
