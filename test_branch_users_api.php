<?php

require 'vendor/autoload.php';

use Illuminate\Support\Facades\Http;

$baseUrl = 'http://localhost:8000/api';

echo "Testing Company Login...\n";
// Assuming there is a company in DB. Let's register one first or just grab one.
// The easiest way is to bootstrap Laravel and use Tinker code or HTTP.
// Let's use Laravel's internal HTTP client inside a script by booting the app.
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$company = \App\Models\Company::first();
if (!$company) {
    echo "No company found. Creating one...\n";
    $company = \App\Models\Company::create([
        'name' => 'Acme Corporation',
        'email' => 'acme@example.com',
        'password' => \Illuminate\Support\Facades\Hash::make('password'),
        'slug' => 'acme-corporation',
        'is_active' => true,
        'domain_name' => 'acme.com'
    ]);
}

$branch = \App\Models\Branch::where('company_id', $company->id)->first();
if (!$branch) {
    echo "No branch found. Creating one...\n";
    $branch = clone $company; // Just for simplicity, using company model isn't right
    $branch = \App\Models\Branch::create([
        'company_id' => $company->id,
        'code' => 'MAIN-1',
        'name' => 'Main Branch',
        'slug' => 'main-branch',
        'email' => 'branch@acme.com',
        'phone' => '1234567890',
        'address' => '123 Main St',
        'is_active' => true,
    ]);
}

$dept = \App\Models\Department::where('company_id', $company->id)->first();
if (!$dept) {
    echo "No department found. Creating one...\n";
    $dept = \App\Models\Department::create([
        'company_id' => $company->id,
        'code' => 'IT-1',
        'name' => 'IT Department',
        'slug' => 'it-department',
        'is_active' => true,
    ]);
}

echo "Authenticating via API...\n";
$response = Http::post($baseUrl . '/company/login', [
    'email' => $company->email,
    'password' => 'password', // Assuming the password is password, if not, we overwrite it.
]);

if (!$response->successful()) {
    // Overwrite password for testing
    $company->update(['password' => \Illuminate\Support\Facades\Hash::make('password')]);
    $response = Http::post($baseUrl . '/company/login', [
        'email' => $company->email,
        'password' => 'password',
    ]);
}

$token = $response->json('data.token');
if (!$token) {
    echo "Login failed.\n";
    echo "Status: " . $response->status() . "\n";
    echo "Body: " . $response->body() . "\n";
    exit;
}

echo "Authenticated successfully. Token: " . substr($token, 0, 10) . "...\n";

// Test 1: Create Branch User
echo "\nTesting Store Branch User...\n";
$createResponse = Http::withToken($token)->post($baseUrl . '/company/branch-users', [
    'name' => 'John Doe',
    'email' => 'john.doe.' . uniqid() . '@example.com',
    'password' => 'password123',
    'branch_id' => $branch->id,
    'dept_id' => $dept->id,
    'is_dept_admin' => true,
    'is_branch_admin' => false,
]);

echo "Status: " . $createResponse->status() . "\n";
echo "Response: " . $createResponse->body() . "\n";

$createdUser = $createResponse->json('data');

if ($createResponse->successful()) {
    echo "Employee ID Generated: " . $createdUser['emp_id'] . "\n";

    // Test 2: Index Branch Users
    echo "\nTesting Index Branch Users...\n";
    $indexResponse = Http::withToken($token)->get($baseUrl . '/company/branch-users');
    echo "Status: " . $indexResponse->status() . "\n";
    // echo "Response: " . $indexResponse->body() . "\n";
    
    // Test 3: Update Branch User
    echo "\nTesting Update Branch User...\n";
    $updateResponse = Http::withToken($token)->put($baseUrl . '/company/branch-users/' . $createdUser['slug'], [
        'name' => 'John Doe Updated',
        'is_branch_admin' => true,
    ]);
    echo "Status: " . $updateResponse->status() . "\n";
    echo "Response: " . $updateResponse->body() . "\n";
} else {
    echo "Creation failed. Skipping update and index.\n";
}

echo "\nDone!\n";
