<?php

use App\Models\Company;
use App\Models\Branch;
use App\Models\Department;
use App\Models\BranchUser;
use Illuminate\Support\Facades\Hash;

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// 1. Ensure a Companyexists
$company = Company::firstOrCreate([
    'email' => 'test-company@example.com'
], [
    'slug' => 'test-company',
    'code' => 'TC',
    'name' => 'Test Company',
    'password' => Hash::make('password123'),
    'is_active' => 1,
    'is_delete' => 0
]);

// 2. Ensure a Branch exists
$branch = Branch::firstOrCreate([
    'company_id' => $company->id,
    'name' => 'Main Branch'
], [
    'slug' => 'main-branch',
    'code' => 'MB',
    'is_active' => 1,
    'is_delete' => 0
]);

// 3. Ensure a Department exists
$department = Department::firstOrCreate([
    'slug' => 'human-resource-hr-department'
], [
    'name' => 'Human Resource (HR) Department',
    'code' => 'HR',
    'is_active' => 1
]);

// 4. Create a test employee
$email = 'emp-test@example.com';
$password = 'password123';

$employee = BranchUser::where('email', $email)->first();
if ($employee) {
    $employee->delete();
}

$employee = BranchUser::create([
    'company_id' => $company->id,
    'branch_id' => $branch->id,
    'dept_id' => $department->id,
    'emp_id' => 'TC-00000001',
    'name' => 'Test Employee',
    'email' => $email,
    'password' => Hash::make($password),
    'is_dept_admin' => 0,
    'is_branch_admin' => 0,
    'is_active' => 1,
    'is_delete' => 0,
    'slug' => 'test-employee'
]);

echo "Employee created: " . $employee->email . "\n";

// 5. Simulate API login logic
$loginUser = BranchUser::with(['company', 'branch', 'department'])
    ->where('email', $email)
    ->where('is_dept_admin', 0)
    ->where('is_branch_admin', 0)
    ->whereNotNull('dept_id')
    ->where('dept_id', '>', 0)
    ->where('is_active', 1)
    ->where('is_delete', 0)
    ->first();

if (!$loginUser) {
    echo "FAILED: User not found in database with required conditions.\n";
} else {
    echo "SUCCESS: User found in database.\n";
    if (Hash::check($password, $loginUser->password)) {
        echo "SUCCESS: Password matches.\n";
        echo "Department Slug: " . $loginUser->department->slug . "\n";
    } else {
        echo "FAILED: Password does not match.\n";
    }
}
