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
        $response->assertViewIs('appadmin.admins.index');
    }

    /** @test */
    public function can_access_admin_create_page()
    {
        $response = $this->get(route('admins.create'));
        $response->assertStatus(200);
        $response->assertViewIs('appadmin.admins.create');
    }

    /** @test */
    public function can_access_admin_show_page()
    {
        $admin = Admin::factory()->create();
        $response = $this->get(route('admins.show', $admin->slug));
        $response->assertStatus(200);
        $response->assertViewIs('appadmin.admins.show');
    }

    /** @test */
    public function can_access_admin_edit_page()
    {
        $admin = Admin::factory()->create();
        $response = $this->get(route('admins.edit', $admin->slug));
        $response->assertStatus(200);
        $response->assertViewIs('appadmin.admins.edit');
    }

    /** @test */
    public function can_access_company_index()
    {
        $response = $this->get(route('companies.index'));
        $response->assertStatus(200);
        $response->assertViewIs('appadmin.companies.index');
    }

    /** @test */
    public function can_access_company_create_page()
    {
        $response = $this->get(route('companies.create'));
        $response->assertStatus(200);
        $response->assertViewIs('appadmin.companies.create');
    }

    /** @test */
    public function can_access_company_show_page()
    {
        $company = \App\Models\Company::factory()->create();
        $response = $this->get(route('companies.show', $company->slug));
        $response->assertStatus(200);
        $response->assertViewIs('appadmin.companies.show');
    }

    /** @test */
    public function can_access_company_edit_page()
    {
        $company = \App\Models\Company::factory()->create();
        $response = $this->get(route('companies.edit', $company->slug));
        $response->assertStatus(200);
        $response->assertViewIs('appadmin.companies.edit');
    }

    /** @test */
    public function can_update_admin_without_email_conflict()
    {
        $admin = Auth::guard('admin')->user();
        $response = $this->from(route('admins.edit', $admin->slug))
            ->put(route('admins.update', $admin->slug), [
                'name' => 'Updated Name',
                'email' => $admin->email,
            ]);

        $response->assertRedirect(route('admins.index'));
        $response->assertSessionHas('success');
        $this->assertEquals('Updated Name', $admin->fresh()->name);
    }

    /** @test */
    public function can_delete_admin()
    {
        $admin = Admin::factory()->create();
        $response = $this->delete(route('admins.destroy', $admin->slug));

        $response->assertRedirect(route('admins.index'));
        $response->assertSessionHas('success');
        $this->assertSoftDeleted('admins', ['id' => $admin->id]);
    }

    /** @test */
    public function can_delete_company()
    {
        $company = \App\Models\Company::factory()->create();
        $response = $this->delete(route('companies.destroy', $company->slug));

        $response->assertRedirect(route('companies.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('companies', ['id' => $company->id]);
    }

    /** @test */
    public function unauthenticated_admin_is_redirected_to_login()
    {
        Auth::guard('admin')->logout();
        $response = $this->get(route('admins.index'));
        $response->assertRedirect(route('login'));
    }
}
