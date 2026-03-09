<?php

namespace Tests\Feature;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
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
        
        $this->actingAs($admin, 'admin');
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

    /** @test */
    public function unauthenticated_admin_is_redirected_to_login()
    {
        Auth::guard('admin')->logout();
        $response = $this->get(route('admins.index'));
        $response->assertRedirect(route('login'));
    }
}
