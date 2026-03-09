<?php

namespace Tests\Feature;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WebManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        $admin = Admin::factory()->create([
            'status' => 'active'
        ]);
        
        $this->actingAs($admin);
    }

    /** @test */
    public function can_access_admin_index()
    {
        $response = $this->get(route('admins.index'));
        $response->assertStatus(200);
        $response->assertViewIs('admins.index');
    }

    /** @test */
    public function can_access_admin_create_page()
    {
        $response = $this->get(route('admins.create'));
        $response->assertStatus(200);
        $response->assertViewIs('admins.create');
    }

    /** @test */
    public function can_access_admin_show_page()
    {
        $admin = Admin::factory()->create();
        $response = $this->get(route('admins.show', $admin->slug));
        $response->assertStatus(200);
        $response->assertViewIs('admins.show');
    }

    /** @test */
    public function can_access_admin_edit_page()
    {
        $admin = Admin::factory()->create();
        $response = $this->get(route('admins.edit', $admin->slug));
        $response->assertStatus(200);
        $response->assertViewIs('admins.edit');
    }

    /** @test */
    public function can_access_company_create_page()
    {
        $response = $this->get(route('companies.create'));
        $response->assertStatus(200);
        $response->assertViewIs('companies.create');
    }
}
