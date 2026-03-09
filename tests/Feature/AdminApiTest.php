<?php

namespace Tests\Feature;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminApiTest extends TestCase
{
    use RefreshDatabase;

    private string $seededAdminEmail    = 'admin@example.com';
    private string $seededAdminPassword = 'password123';
    private Admin $admin;
    private string $token;

    /**
     * Set up a default admin and token before each test.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Use a unique email per test run to avoid unique constraint violations
        // when RefreshDatabase wraps tests in transactions (Faker unique() state persists)
        $uniqueSuffix            = uniqid();
        $this->seededAdminEmail  = "admin_{$uniqueSuffix}@example.com";

        $this->admin = Admin::factory()->create([
            'name'     => 'Test Admin',
            'email'    => $this->seededAdminEmail,
            'password' => Hash::make($this->seededAdminPassword),
            'status'   => 'active',
        ]);

        $this->token = $this->admin->createToken('test-token')->plainTextToken;
    }

    // ─── Authentication Tests ─────────────────────────────────────────────────

    /**
     * @test
     */
    public function admin_can_login_with_valid_email_and_password(): void
    {
        $response = $this->postJson('/api/admin/login', [
            'login'    => $this->seededAdminEmail,
            'password' => $this->seededAdminPassword,
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'data' => ['token', 'admin'],
                 ])
                 ->assertJson(['success' => true]);
    }

    /**
     * @test
     */
    public function admin_cannot_login_with_invalid_credentials(): void
    {
        $response = $this->postJson('/api/admin/login', [
            'login'    => $this->seededAdminEmail,
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(401)
                 ->assertJson(['success' => false]);
    }

    /**
     * @test
     */
    public function login_requires_login_field(): void
    {
        $response = $this->postJson('/api/admin/login', [
            'password' => $this->seededAdminPassword,
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['login']);
    }

    /**
     * @test
     */
    public function inactive_admin_cannot_login(): void
    {
        $inactiveAdmin = Admin::factory()->create([
            'email'    => 'inactive@example.com',
            'password' => Hash::make('password123'),
            'status'   => 'inactive',
        ]);

        $response = $this->postJson('/api/admin/login', [
            'login'    => 'inactive@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(401);
    }

    /**
     * @test
     */
    public function authenticated_admin_can_logout(): void
    {
        $response = $this->withToken($this->token)
                         ->postJson('/api/admin/logout');

        $response->assertStatus(200)
                 ->assertJson(['success' => true]);
    }

    /**
     * @test
     */
    public function unauthenticated_request_returns_401(): void
    {
        $response = $this->getJson('/api/admins');

        $response->assertStatus(401)
                 ->assertJson(['success' => false]);
    }

    // ─── Profile Tests ────────────────────────────────────────────────────────

    /**
     * @test
     */
    public function admin_can_view_own_profile(): void
    {
        $response = $this->withToken($this->token)
                         ->getJson('/api/admin/profile');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'data' => ['slug', 'name', 'email', 'status'],
                 ]);
    }

    // ─── Admin Management Tests ───────────────────────────────────────────────

    /**
     * @test
     */
    public function admin_can_list_all_admins(): void
    {
        Admin::factory()->count(5)->create();

        $response = $this->withToken($this->token)
                         ->getJson('/api/admins');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'data',
                     'meta' => ['current_page', 'per_page', 'total'],
                 ]);
    }

    /**
     * @test
     */
    public function admin_can_search_admins(): void
    {
        Admin::factory()->create(['name' => 'John Doe', 'email' => 'john@test.com']);

        $response = $this->withToken($this->token)
                         ->getJson('/api/admins?search=John');

        $response->assertStatus(200)
                 ->assertJson(['success' => true]);
    }

    /**
     * @test
     */
    public function admin_can_filter_by_status(): void
    {
        Admin::factory()->count(3)->active()->create();
        Admin::factory()->count(2)->inactive()->create();

        $response = $this->withToken($this->token)
                         ->getJson('/api/admins?status=active');

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertTrue(count($data) > 0);
    }

    /**
     * @test
     */
    public function admin_can_create_new_admin(): void
    {
        $uniqueName = 'Test User Create ' . uniqid();

        $response = $this->withToken($this->token)
                         ->postJson('/api/admins', [
                             'name'                  => $uniqueName,
                             'email'                 => 'createtest_' . uniqid() . '@example.com',
                             'phone'                 => '9999999999',
                             'username'              => 'createtest_' . uniqid(),
                             'password'              => 'password123',
                             'password_confirmation' => 'password123',
                             'status'                => 'active',
                         ]);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'success',
                     'data' => ['slug', 'name', 'email'],
                 ]);

        // Slug should be auto-generated from the name
        $this->assertNotEmpty($response->json('data.slug'));
    }

    /**
     * @test
     */
    public function create_admin_with_duplicate_name_generates_unique_slug(): void
    {
        // Directly insert an admin with base slug
        $baseName = 'Duplicate Slug Test';
        Admin::create([
            'slug'     => 'duplicate-slug-test',
            'name'     => $baseName . ' Original',
            'email'    => 'dupslug1@example.com',
            'password' => Hash::make('password123'),
            'status'   => 'active',
        ]);

        $response = $this->withToken($this->token)
                         ->postJson('/api/admins', [
                             'name'                  => $baseName,
                             'email'                 => 'dupslug2@example.com',
                             'password'              => 'password123',
                             'password_confirmation' => 'password123',
                             'status'                => 'active',
                         ]);

        $response->assertStatus(201);
        $this->assertEquals('duplicate-slug-test-2', $response->json('data.slug'));
    }

    /**
     * @test
     */
    public function admin_can_view_admin_by_slug(): void
    {
        $adminToView = Admin::factory()->create();

        $response = $this->withToken($this->token)
                         ->getJson("/api/admins/{$adminToView->slug}");

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'data'    => ['slug' => $adminToView->slug],
                 ]);
    }

    /**
     * @test
     */
    public function returns_404_for_nonexistent_slug(): void
    {
        $response = $this->withToken($this->token)
                         ->getJson('/api/admins/nonexistent-slug');

        $response->assertStatus(404)
                 ->assertJson(['success' => false]);
    }

    /**
     * @test
     */
    public function admin_can_update_another_admin(): void
    {
        $targetAdmin = Admin::factory()->create();

        $response = $this->withToken($this->token)
                         ->putJson("/api/admins/{$targetAdmin->slug}", [
                             'name'   => 'Updated Name',
                             'status' => 'inactive',
                         ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'data'    => ['name' => 'Updated Name'],
                 ]);
    }

    /**
     * @test
     */
    public function admin_can_soft_delete_another_admin(): void
    {
        $targetAdmin = Admin::factory()->create();

        $response = $this->withToken($this->token)
                         ->deleteJson("/api/admins/{$targetAdmin->slug}");

        $response->assertStatus(200)
                 ->assertJson(['success' => true]);

        $this->assertSoftDeleted('admins', ['slug' => $targetAdmin->slug]);
    }

    /**
     * @test
     */
    public function admin_cannot_delete_own_account(): void
    {
        $response = $this->withToken($this->token)
                         ->deleteJson("/api/admins/{$this->admin->slug}");

        $response->assertStatus(403)
                 ->assertJson(['success' => false]);
    }

    /**
     * @test
     */
    public function admin_can_restore_soft_deleted_admin(): void
    {
        $targetAdmin = Admin::factory()->create();
        $targetAdmin->delete();

        $response = $this->withToken($this->token)
                         ->postJson("/api/admins/{$targetAdmin->slug}/restore");

        $response->assertStatus(200)
                 ->assertJson(['success' => true]);

        $this->assertNotSoftDeleted('admins', ['slug' => $targetAdmin->slug]);
    }

    /**
     * @test
     */
    public function create_admin_fails_with_invalid_data(): void
    {
        $response = $this->withToken($this->token)
                         ->postJson('/api/admins', [
                             'name'  => '',
                             'email' => 'not-an-email',
                         ]);

        $response->assertStatus(422)
                 ->assertJson(['success' => false])
                 ->assertJsonValidationErrors(['name', 'email', 'password', 'status']);
    }
}
