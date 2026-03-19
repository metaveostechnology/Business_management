<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Company;
use App\Models\Department;
use App\Models\BranchUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DeptEmployeeAuthTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected Company $company;
    protected Branch $branch;
    protected Department $department;
    protected BranchUser $employee;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a test company
        $this->company = Company::factory()->create([
            'is_active' => true,
        ]);

        // Create a test branch
        $uniqueBranch = uniqid();
        $this->branch = Branch::create([
            'company_id'   => $this->company->id,
            'name'         => 'Main Branch ' . $uniqueBranch,
            'code'         => 'MB' . $uniqueBranch,
            'slug'         => 'main-branch-' . $uniqueBranch,
            'is_active'    => true,
            'address_line1'=> '123 Main St',
            'city'         => 'Metropolis',
            'state'        => 'NY',
            'country'      => 'USA',
        ]);

        // Create a test department
        $uniqueDept = uniqid();
        $this->department = Department::create([
            'company_id' => $this->company->id,
            'branch_id'  => $this->branch->id,
            'name'       => 'HR Department ' . $uniqueDept,
            'code'       => 'HR' . $uniqueDept,
            'slug'       => 'hr-department-' . $uniqueDept,
            'is_active'  => true,
        ]);

        // Create a valid department employee
        $uniqueEmp = uniqid();
        $this->employee = BranchUser::create([
            'company_id'      => $this->company->id,
            'branch_id'       => $this->branch->id,
            'dept_id'         => $this->department->id,
            'emp_id'          => 'EMP' . $uniqueEmp,
            'name'            => 'John Doe',
            'email'           => 'john.doe.' . $uniqueEmp . '@example.com',
            'password'        => Hash::make('password123'),
            'phone'           => '1234567890',
            'slug'            => 'john-doe-' . $uniqueEmp,
            'is_branch_admin' => 0,
            'is_dept_admin'   => 0,
            'is_active'       => 1,
            'is_delete'       => 0,
        ]);
    }

    /** @test */
    public function dept_employee_can_login_with_valid_credentials()
    {
        $response = $this->postJson('/api/dept-employee/login', [
            'email'    => $this->employee->email,
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
                 ->assertJsonPath('status', true)
                 ->assertJsonPath('message', 'Login successful')
                 ->assertJsonStructure(['token', 'user']);
    }

    /** @test */
    public function dept_employee_cannot_login_with_invalid_password()
    {
        $response = $this->postJson('/api/dept-employee/login', [
            'email'    => $this->employee->email,
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)
                 ->assertJsonPath('status', false)
                 ->assertJsonPath('message', 'Invalid credentials');
    }

    /** @test */
    public function dept_admin_cannot_login_via_employee_login()
    {
        // Make John a Dept Admin
        $this->employee->update(['is_dept_admin' => 1]);

        $response = $this->postJson('/api/dept-employee/login', [
            'email'    => $this->employee->email,
            'password' => 'password123',
        ]);

        $response->assertStatus(401) // Because the query filters is_dept_admin=0
                 ->assertJsonPath('status', false);
    }

    /** @test */
    public function inactive_dept_employee_cannot_login()
    {
        $this->employee->update(['is_active' => 0]);

        $response = $this->postJson('/api/dept-employee/login', [
            'email'    => $this->employee->email,
            'password' => 'password123',
        ]);

        $response->assertStatus(401);
    }

    /** @test */
    public function dept_employee_can_logout()
    {
        Sanctum::actingAs($this->employee, ['*']);

        $response = $this->postJson('/api/dept-employee/logout');

        $response->assertStatus(200)
                 ->assertJsonPath('status', true)
                 ->assertJsonPath('message', 'Logout successful');
    }

    /** @test */
    public function dept_employee_can_change_password()
    {
        Sanctum::actingAs($this->employee, ['*']);

        $response = $this->postJson('/api/dept-employee/change-password', [
            'current_password'          => 'password123',
            'new_password'              => 'newpassword456',
            'new_password_confirmation' => 'newpassword456',
        ]);

        $response->assertStatus(200)
                 ->assertJsonPath('status', true)
                 ->assertJsonPath('message', 'Password changed successfully');

        $this->assertTrue(Hash::check('newpassword456', $this->employee->fresh()->password));
    }

    /** @test */
    public function change_password_fails_if_current_password_incorrect()
    {
        Sanctum::actingAs($this->employee, ['*']);

        $response = $this->postJson('/api/dept-employee/change-password', [
            'current_password'          => 'wrongpassword',
            'new_password'              => 'newpassword456',
            'new_password_confirmation' => 'newpassword456',
        ]);

        $response->assertStatus(400)
                 ->assertJsonPath('status', false)
                 ->assertJsonPath('message', 'Current password is incorrect');
    }
}
