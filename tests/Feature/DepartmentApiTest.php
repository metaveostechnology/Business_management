<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Company;
use App\Models\Department;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DepartmentApiTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected Company $company;
    protected Branch $branch;
    protected string $token;

    protected function setUp(): void
    {
        parent::setUp();

        // Ensure fake storage if needed, though mostly using DB
        $this->company = Company::factory()->create([
            'is_active' => true,
        ]);

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

        $this->token = $this->company->createToken('test-token')->plainTextToken;
    }

    /** @test */
    public function unauthenticated_user_cannot_access_departments()
    {
        $this->getJson('/api/company/departments')
             ->assertStatus(401);
    }

    /** @test */
    public function company_can_list_its_departments()
    {
        Department::create([
            'company_id' => $this->company->id,
            'branch_id'  => $this->branch->id,
            'name'       => 'HR Department',
            'code'       => 'HR01',
            'slug'       => 'hr-department',
        ]);

        $response = $this->withToken($this->token)->getJson('/api/company/departments');

        $response->assertStatus(200)
                 ->assertJsonCount(1, 'data')
                 ->assertJsonPath('data.0.name', 'HR Department');
    }

    /** @test */
    public function other_company_cannot_see_departments_of_this_company()
    {
        $otherCompany = Company::factory()->create();
        Department::create([
            'company_id' => $otherCompany->id,
            'branch_id'  => $this->branch->id,
            'name'       => 'Sales Department',
            'code'       => 'SD01',
            'slug'       => 'sales-department',
        ]);

        $response = $this->withToken($this->token)->getJson('/api/company/departments');

        $response->assertStatus(200)
                 ->assertJsonCount(0, 'data');
    }

    /** @test */
    public function company_can_create_a_department()
    {
        $payload = [
            'name'        => 'IT Department',
            'code'        => 'IT01',
            'branch_id'   => $this->branch->id,
            'description' => 'Information Technology',
            'is_active'   => 1,
            'can_create_tasks' => 1,
            'can_receive_tasks' => 1,
        ];

        $response = $this->withToken($this->token)->postJson('/api/company/departments', $payload);

        $response->assertStatus(201)
                 ->assertJsonPath('data.name', 'IT Department')
                 ->assertJsonPath('data.code', 'IT01')
                 ->assertJsonPath('data.slug', 'it-department');

        $this->assertDatabaseHas('departments', [
            'company_id' => $this->company->id,
            'branch_id'  => $this->branch->id,
            'code'       => 'IT01',
            'name'       => 'IT Department',
            'slug'       => 'it-department',
        ]);
    }

    /** @test */
    public function company_can_view_a_specific_department()
    {
        $department = Department::create([
            'company_id' => $this->company->id,
            'branch_id'  => $this->branch->id,
            'name'       => 'Finance Department',
            'code'       => 'FIN01',
            'slug'       => 'finance-department',
        ]);

        $response = $this->withToken($this->token)->getJson('/api/company/departments/finance-department');

        $response->assertStatus(200)
                 ->assertJsonPath('data.name', 'Finance Department')
                 ->assertJsonPath('data.code', 'FIN01');
    }

    /** @test */
    public function company_cannot_view_department_from_another_company()
    {
        $otherCompany = Company::factory()->create();
        $department = Department::create([
            'company_id' => $otherCompany->id,
            'branch_id'  => $this->branch->id,
            'name'       => 'Secret Department',
            'code'       => 'SEC01',
            'slug'       => 'secret-department',
        ]);

        $response = $this->withToken($this->token)->getJson('/api/company/departments/secret-department');

        $response->assertStatus(404);
    }

    /** @test */
    public function company_can_update_its_department()
    {
        $department = Department::create([
            'company_id' => $this->company->id,
            'branch_id'  => $this->branch->id,
            'name'       => 'Old Department',
            'code'       => 'OLD01',
            'slug'       => 'old-department',
        ]);

        $payload = [
            'name' => 'New Department Name',
            'code' => 'NEW01',
            'branch_id' => $this->branch->id,
        ];

        $response = $this->withToken($this->token)->putJson('/api/company/departments/old-department', $payload);

        $response->assertStatus(200)
                 ->assertJsonPath('data.name', 'New Department Name')
                 ->assertJsonPath('data.slug', 'new-department-name'); // Assuming slug gets updated

        $this->assertDatabaseHas('departments', [
            'id'   => $department->id,
            'name' => 'New Department Name',
            'code' => 'NEW01',
        ]);
    }

    /** @test */
    public function company_can_delete_a_department()
    {
        $department = Department::create([
            'company_id' => $this->company->id,
            'branch_id'  => $this->branch->id,
            'name'       => 'Delete Me',
            'code'       => 'DEL01',
            'slug'       => 'delete-me',
            'is_system_default' => false,
        ]);

        $response = $this->withToken($this->token)->deleteJson('/api/company/departments/delete-me');

        $response->assertStatus(200);

        $this->assertDatabaseMissing('departments', [
            'id' => $department->id,
        ]);
    }

    /** @test */
    public function company_cannot_delete_system_default_department()
    {
        $department = Department::create([
            'company_id' => $this->company->id,
            'branch_id'  => $this->branch->id,
            'name'       => 'System Default Dept',
            'code'       => 'SYS01',
            'slug'       => 'system-default-dept',
            'is_system_default' => true,
        ]);

        $response = $this->withToken($this->token)->deleteJson('/api/company/departments/system-default-dept');

        $response->assertStatus(403)
                 ->assertJsonPath('message', 'System default departments cannot be deleted.');

        $this->assertDatabaseHas('departments', [
            'id' => $department->id,
        ]);
    }
}
