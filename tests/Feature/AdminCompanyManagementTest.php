<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCompanyManagementTest extends TestCase
{
    use RefreshDatabase;

    protected Admin $admin;
    protected string $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = Admin::factory()->create();
        $this->token = $this->admin->createToken('admin-token')->plainTextToken;
    }

    /** @test */
    public function admin_can_list_companies()
    {
        Company::factory()->count(3)->create();

        $response = $this->withToken($this->token)->getJson('/api/companies');

        $response->assertStatus(200);
        $this->assertCount(3, $response->json('data'));
    }

    /** @test */
    public function admin_can_create_a_company()
    {
        $payload = [
            'name' => 'Acme Corp',
            'code' => 'ACME',
            'currency_code' => 'USD',
            'timezone' => 'America/New_York',
        ];

        $response = $this->withToken($this->token)->postJson('/api/companies', $payload);

        $response->assertStatus(201);
        $this->assertEquals('acme-corp', $response->json('data.slug'));
    }

    /** @test */
    public function admin_can_update_a_company()
    {
        $company = Company::factory()->create([
            'name' => 'Old Name',
        ]);

        $payload = [
            'name' => 'New Name',
        ];

        $response = $this->withToken($this->token)->putJson('/api/companies/' . $company->slug, $payload);

        $response->assertStatus(200);
        $this->assertEquals('new-name', $response->json('data.slug'));
        $this->assertEquals('New Name', $response->json('data.name'));
    }

    /** @test */
    public function admin_can_delete_a_company()
    {
        $company = Company::factory()->create();

        $response = $this->withToken($this->token)->deleteJson('/api/companies/' . $company->slug);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('companies', ['id' => $company->id]);
    }
}
