<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\Company;
use App\Models\Branch;
use App\Models\Department;
use App\Models\BranchUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;

class BranchAdminApiTest extends TestCase
{
    use DatabaseTransactions;

    public function test_branch_admin_api_flow()
    {
        // 1. Setup Test Data
        $company = Company::create([
            'code' => 'COMP-' . time(),
            'name' => 'API Test Company',
            'slug' => 'api-test-company-' . time(),
            'email' => 'api_test_company_' . time() . '@example.com',
            'phone' => '1234567890',
            'password' => Hash::make('password'),
            'is_active' => 1
        ]);

        $branch = Branch::create([
            'company_id' => $company->id,
            'code' => 'TEST-BR-' . time(),
            'name' => 'Test Branch',
            'slug' => 'test-branch-' . time(),
            'email' => 'test_branch@example.com',
            'is_active' => 1
        ]);

        $department = Department::create([
            'company_id' => $company->id,
            'branch_id' => $branch->id,
            'code' => 'TEST-DEPT-' . time(),
            'name' => 'Test Department',
            'slug' => 'test-department-' . time(),
            'is_active' => 1
        ]);

        $adminEmail = 'branch_admin_' . time() . '@example.com';
        $branchAdmin = BranchUser::create([
            'company_id' => $company->id,
            'branch_id' => $branch->id,
            'emp_id' => 'ADMIN-' . time(),
            'name' => 'Test Branch Admin',
            'slug' => 'test-branch-admin-' . time(),
            'email' => $adminEmail,
            'password' => 'secret123', // Raw password for authentication check tests
            'is_branch_admin' => 1,
            'is_dept_admin' => 0,
            'is_active' => 1,
            'is_delete' => 0
        ]);
        // Update password with hash for DB after saving plaintext logic if needed, actually we just save hashed
        $branchAdmin->password = Hash::make('secret123');
        $branchAdmin->save();

        // 2. Test Login API
        $loginResponse = $this->postJson('/api/branch-admin/login', [
            'email' => $adminEmail,
            'password' => 'secret123' // The raw password to check
        ]);

        $loginResponse->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'Login successful'
            ]);

        $token = $loginResponse->json('token');
        $this->assertNotEmpty($token);

        // 3. Test Create Employee API
        Storage::fake('public');
        $file = UploadedFile::fake()->create('profile.jpg', 100, 'image/jpeg');

        Sanctum::actingAs($branchAdmin, ['*']);

        $createResponse = $this->postJson('/api/branch/employees', [
                'name' => 'Test Employee',
                'email' => 'test_employee_' . time() . '@example.com',
                'password' => 'password123',
                'phone' => '9876543210',
                'dept_id' => $department->id,
                'profile_image' => $file
            ]);

        if ($createResponse->status() !== 201) {
            dump($createResponse->json());
        }

        $createResponse->assertStatus(201)
            ->assertJson([
                'status' => true,
                'message' => 'Employee created successfully'
            ]);

        $employeeId = $createResponse->json('data.id');
        $empIdCode = $createResponse->json('data.emp_id');
        $this->assertStringStartsWith('API-', $empIdCode); // Auto-generated from "API Test Company"
        $this->assertNotNull($createResponse->json('data.profile_image'));

        // 4. Test List Employees API
        $listResponse = $this->getJson('/api/branch/employees');

        $listResponse->assertStatus(200)
            ->assertJson([
                'status' => true
            ]);
        $this->assertCount(1, $listResponse->json('data'));
        $this->assertEquals($employeeId, $listResponse->json('data.0.id'));

        // 5. Test Show Employee API
        $showResponse = $this->getJson('/api/branch/employees/' . $employeeId);

        $showResponse->assertStatus(200)
            ->assertJson([
                'status' => true
            ]);
        $this->assertEquals('Test Employee', $showResponse->json('data.name'));

        // 6. Test Update Employee API
        $updateResponse = $this->putJson('/api/branch/employees/' . $employeeId, [
                'name' => 'Updated Test Employee',
                'phone' => '1112223333'
            ]);

        $updateResponse->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'Employee updated successfully'
            ]);
        $this->assertEquals('Updated Test Employee', $updateResponse->json('data.name'));
        $this->assertEquals('1112223333', $updateResponse->json('data.phone'));

        // 7. Test Soft Delete Employee API
        $deleteResponse = $this->deleteJson('/api/branch/employees/' . $employeeId);

        $deleteResponse->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'Employee deleted successfully'
            ]);

        // Verify it's no longer in the listed employees
        $listResponseAfterDelete = $this->getJson('/api/branch/employees');
        $this->assertCount(0, $listResponseAfterDelete->json('data'));

        // 8. Test Logout
        $logoutResponse = $this->postJson('/api/branch-admin/logout');

        $logoutResponse->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'Logout successful'
            ]);
    }
}
