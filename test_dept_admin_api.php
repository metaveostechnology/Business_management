<?php

require 'vendor/autoload.php';

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

$baseUrl = 'http://localhost:8000/api';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Setting up Test Data...\n";

// Ensure we have a company, branch, and department
$company = \App\Models\Company::first();
if (!$company) {
    $company = \App\Models\Company::create([
        'name' => 'Acme Corporation',
        'email' => 'acme@example.com',
        'password' => \Illuminate\Support\Facades\Hash::make('password'),
        'slug' => 'acme-corporation-' . Str::random(5),
        'is_active' => true,
        'domain_name' => 'acme.com'
    ]);
}

$branch = \App\Models\Branch::where('company_id', $company->id)->first();
if (!$branch) {
    $branch = \App\Models\Branch::create([
        'company_id' => $company->id,
        'code' => 'MAIN-1',
        'name' => 'Main Branch',
        'slug' => 'main-branch-' . Str::random(5),
        'email' => 'branch@acme.com',
        'phone' => '1234567890',
        'address' => '123 Main St',
        'is_active' => true,
    ]);
}

$dept = \App\Models\Department::first();
if (!$dept) {
    $dept = \App\Models\Department::create([
        'code' => 'IT-1',
        'name' => 'IT Department',
        'slug' => 'it-department-' . Str::random(5),
        'is_active' => true,
    ]);
}

// Create a Dept Admin
$deptAdminEmail = 'deptadmin@example.com';
$deptAdmin = \App\Models\BranchUser::where('email', $deptAdminEmail)->first();
if (!$deptAdmin) {
    $deptAdmin = \App\Models\BranchUser::create([
        'company_id' => $company->id,
        'branch_id' => $branch->id,
        'dept_id' => $dept->id,
        'name' => 'Dept Admin User',
        'email' => $deptAdminEmail,
        'password' => \Illuminate\Support\Facades\Hash::make('password'),
        'is_active' => true,
        'is_delete' => false,
        'is_dept_admin' => true,
        'is_branch_admin' => false,
        'slug' => Str::slug('Dept Admin User') . '-' . Str::random(5),
        'emp_id' => 'COM-' . rand(10000000, 99999999)
    ]);
} else {
    // ensure password is correct
    $deptAdmin->update([
        'password' => \Illuminate\Support\Facades\Hash::make('password'),
        'is_dept_admin' => true,
        'is_active' => true,
        'is_delete' => false
    ]);
}

echo "Testing Dept Admin Login...\n";
$response = Http::post($baseUrl . '/dept-admin/login', [
    'email' => $deptAdminEmail,
    'password' => 'password',
]);

if (!$response->successful()) {
    echo "Login failed.\n";
    echo "Status: " . $response->status() . "\n";
    echo "Body: " . $response->body() . "\n";
    exit;
}

$token = $response->json('token');
echo "Login successful! Token: " . substr($token, 0, 10) . "...\n";

// --- CRUD Tests ---

// 1. Store Employee
echo "\nTesting Create Employee (/dept/employees)...\n";
$createResponse = Http::withToken($token)->post($baseUrl . '/dept/employees', [
    'name' => 'Dept Employee 1',
    'email' => 'employee1.' . uniqid() . '@example.com',
    'password' => 'password123',
    'phone' => '9876543210'
]);

if (!$createResponse->successful()) {
    echo "Create Failed. Status: " . $createResponse->status() . "\n";
    echo "Body: " . $createResponse->body() . "\n";
    exit;
}

$newEmployee = $createResponse->json('data');
echo "Created Employee successfully! EMP ID: " . $newEmployee['emp_id'] . " | Name: " . $newEmployee['name'] . "\n";

// 2. Index Employees
echo "\nTesting Index Employees (/dept/employees)...\n";
$indexResponse = Http::withToken($token)->get($baseUrl . '/dept/employees');
echo "Status: " . $indexResponse->status() . " | Count: " . count($indexResponse->json('data')) . "\n";

// 3. Show Employee
echo "\nTesting Show Employee (/dept/employees/{slug})...\n";
$showResponse = Http::withToken($token)->get($baseUrl . '/dept/employees/' . $newEmployee['slug']);
echo "Status: " . $showResponse->status() . " | Retrieved Name: " . $showResponse->json('data.name') . "\n";

// 4. Update Employee
echo "\nTesting Update Employee (/dept/employees/{slug})...\n";
$updateResponse = Http::withToken($token)->put($baseUrl . '/dept/employees/' . $newEmployee['slug'], [
    'name' => 'Dept Employee 1 Updated'
]);
echo "Status: " . $updateResponse->status() . " | Updated Name: " . $updateResponse->json('data.name') . "\n";
$updatedSlug = $updateResponse->json('data.slug');

// 5. Delete Employee
echo "\nTesting Delete Employee (/dept/employees/{slug})...\n";
$deleteResponse = Http::withToken($token)->delete($baseUrl . '/dept/employees/' . $updatedSlug);
echo "Status: " . $deleteResponse->status() . " | Message: " . $deleteResponse->json('message') . "\n";

// 6. Logout
echo "\nTesting Logout (/dept-admin/logout)...\n";
$logoutResponse = Http::withToken($token)->post($baseUrl . '/dept-admin/logout');
echo "Status: " . $logoutResponse->status() . " | Message: " . $logoutResponse->json('message') . "\n";

echo "\nAll Tests Completed!\n";
