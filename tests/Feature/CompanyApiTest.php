<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Company;
use App\Models\CompanyRegister;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CompanyApiTest extends TestCase
{
    use RefreshDatabase;

    protected CompanyRegister $companyUser;
    protected string $token;
    protected string $uniqueEmail;

    protected function setUp(): void
    {
        parent::setUp();

        $this->uniqueEmail = 'company_admin_' . uniqid() . '@example.com';

        $this->companyUser = CompanyRegister::create([
            'email'     => $this->uniqueEmail,
            'password'  => bcrypt('password123'),
            'is_active' => 1,
        ]);

        $this->token = $this->companyUser->createToken('test-token')->plainTextToken;
    }

    // ────────────────────────────────────────────────────────
    // AUTHENTICATION GUARD
    // ────────────────────────────────────────────────────────

    /** @test */
    public function unauthenticated_cannot_access_companies(): void
    {
        $this->getJson('/api/companies')
             ->assertStatus(401)
             ->assertJson(['success' => false]);
    }

    // ────────────────────────────────────────────────────────
    // CREATE COMPANY
    // ────────────────────────────────────────────────────────

    /** @test */
    public function admin_can_create_company_with_all_fields(): void
    {
        Storage::fake('public');

        $payload = [
            'name'                => 'Infosys Ltd',
            'code'                => 'INFY',
            'legal_name'          => 'Infosys Limited',
            'email'               => 'info@infosys.com',
            'phone'               => '9876543210',
            'website'             => 'https://infosys.com',
            'tax_number'          => 'GSTIN123456',
            'registration_number' => 'REG123456',
            'currency_code'       => 'INR',
            'timezone'            => 'Asia/Kolkata',
            'address_line1'       => 'Electronics City Phase 1',
            'address_line2'       => 'Near Wipro Gate',
            'city'                => 'Bangalore',
            'state'               => 'Karnataka',
            'country'             => 'India',
            'postal_code'         => '560100',
            'is_active'           => 1,
        ];

        $response = $this->withToken($this->token)
                         ->postJson('/api/companies', $payload);

        $response->assertStatus(201)
                 ->assertJson(['success' => true, 'message' => 'Company created successfully.'])
                 ->assertJsonPath('data.slug', 'infosys-ltd')
                 ->assertJsonPath('data.code', 'INFY')
                 ->assertJsonPath('data.name', 'Infosys Ltd')
                 ->assertJsonPath('data.legal_name', 'Infosys Limited')
                 ->assertJsonPath('data.email', 'info@infosys.com')
                 ->assertJsonPath('data.phone', '9876543210')
                 ->assertJsonPath('data.website', 'https://infosys.com')
                 ->assertJsonPath('data.tax_number', 'GSTIN123456')
                 ->assertJsonPath('data.registration_number', 'REG123456')
                 ->assertJsonPath('data.currency_code', 'INR')
                 ->assertJsonPath('data.timezone', 'Asia/Kolkata')
                 ->assertJsonPath('data.address_line1', 'Electronics City Phase 1')
                 ->assertJsonPath('data.address_line2', 'Near Wipro Gate')
                 ->assertJsonPath('data.city', 'Bangalore')
                 ->assertJsonPath('data.state', 'Karnataka')
                 ->assertJsonPath('data.country', 'India')
                 ->assertJsonPath('data.postal_code', '560100')
                 ->assertJsonPath('data.is_active', true);

        $this->assertDatabaseHas('companies', ['slug' => 'infosys-ltd', 'code' => 'INFY']);
    }

    /** @test */
    public function company_slug_is_auto_generated_from_name(): void
    {
        $this->withToken($this->token)->postJson('/api/companies', [
            'name'          => 'Tata Consultancy',
            'code'          => 'TCS001',
            'currency_code' => 'INR',
            'timezone'      => 'Asia/Kolkata',
        ])->assertStatus(201)->assertJsonPath('data.slug', 'tata-consultancy');
    }

    /** @test */
    public function duplicate_name_generates_unique_slug(): void
    {
        $this->withToken($this->token)->postJson('/api/companies', [
            'name'          => 'Wipro Ltd',
            'code'          => 'WIP1',
            'currency_code' => 'INR',
            'timezone'      => 'Asia/Kolkata',
        ])->assertStatus(201)->assertJsonPath('data.slug', 'wipro-ltd');

        $this->withToken($this->token)->postJson('/api/companies', [
            'name'          => 'Wipro Ltd',
            'code'          => 'WIP2',
            'currency_code' => 'INR',
            'timezone'      => 'Asia/Kolkata',
        ])->assertStatus(201)->assertJsonPath('data.slug', 'wipro-ltd-2');

        $this->withToken($this->token)->postJson('/api/companies', [
            'name'          => 'Wipro Ltd',
            'code'          => 'WIP3',
            'currency_code' => 'INR',
            'timezone'      => 'Asia/Kolkata',
        ])->assertStatus(201)->assertJsonPath('data.slug', 'wipro-ltd-3');
    }

    /** @test */
    public function create_company_with_logo_upload(): void
    {
        Storage::fake('public');

        // Use create() instead of image() — GD extension may not be available
        $logo = UploadedFile::fake()->create('logo.png', 100, 'image/png');

        $response = $this->withToken($this->token)->post(
            '/api/companies',
            [
                'name'          => 'Logo Corp',
                'code'          => 'LGCORP',
                'currency_code' => 'INR',
                'timezone'      => 'Asia/Kolkata',
                'logo'          => $logo,
            ],
            ['Accept' => 'application/json']
        );

        $response->assertStatus(201);
        $this->assertNotNull($response->json('data.logo_path'));
        // logo_path must contain a full storage URL
        $this->assertStringContainsString('storage', $response->json('data.logo_path'));
    }

    // ────────────────────────────────────────────────────────
    // VALIDATION — CREATE
    // ────────────────────────────────────────────────────────

    /** @test */
    public function create_company_fails_with_missing_required_fields(): void
    {
        $this->withToken($this->token)->postJson('/api/companies', [])
             ->assertStatus(422)
             ->assertJsonValidationErrors(['name', 'code', 'currency_code', 'timezone']);
    }

    /** @test */
    public function create_company_fails_with_phone_too_short(): void
    {
        $this->withToken($this->token)->postJson('/api/companies', [
            'name'          => 'Short Phone Co',
            'code'          => 'SPC01',
            'currency_code' => 'INR',
            'timezone'      => 'Asia/Kolkata',
            'phone'         => '12345',
        ])->assertStatus(422)->assertJsonValidationErrors(['phone']);
    }

    /** @test */
    public function create_company_fails_with_phone_too_long(): void
    {
        $this->withToken($this->token)->postJson('/api/companies', [
            'name'          => 'Long Phone Co',
            'code'          => 'LPC01',
            'currency_code' => 'INR',
            'timezone'      => 'Asia/Kolkata',
            'phone'         => '98765432109',
        ])->assertStatus(422)->assertJsonValidationErrors(['phone']);
    }

    /** @test */
    public function create_company_fails_with_invalid_email(): void
    {
        $this->withToken($this->token)->postJson('/api/companies', [
            'name'          => 'Bad Email Co',
            'code'          => 'BEC01',
            'currency_code' => 'INR',
            'timezone'      => 'Asia/Kolkata',
            'email'         => 'not-an-email',
        ])->assertStatus(422)->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function create_company_fails_with_invalid_website(): void
    {
        $this->withToken($this->token)->postJson('/api/companies', [
            'name'          => 'Bad URL Co',
            'code'          => 'BUC01',
            'currency_code' => 'INR',
            'timezone'      => 'Asia/Kolkata',
            'website'       => 'not-a-url',
        ])->assertStatus(422)->assertJsonValidationErrors(['website']);
    }

    /** @test */
    public function create_company_fails_with_duplicate_code(): void
    {
        Company::create([
            'slug'          => 'existing-co',
            'name'          => 'Existing Co',
            'code'          => 'EXIST',
            'currency_code' => 'INR',
            'timezone'      => 'Asia/Kolkata',
        ]);

        $this->withToken($this->token)->postJson('/api/companies', [
            'name'          => 'Other Co',
            'code'          => 'EXIST',
            'currency_code' => 'INR',
            'timezone'      => 'Asia/Kolkata',
        ])->assertStatus(422)->assertJsonValidationErrors(['code']);
    }

    /** @test */
    public function create_company_fails_with_duplicate_email(): void
    {
        Company::create([
            'slug'          => 'email-co',
            'name'          => 'Email Co',
            'code'          => 'EMLCO',
            'email'         => 'dup@example.com',
            'currency_code' => 'INR',
            'timezone'      => 'Asia/Kolkata',
        ]);

        $this->withToken($this->token)->postJson('/api/companies', [
            'name'          => 'Another Co',
            'code'          => 'ANCO1',
            'email'         => 'dup@example.com',
            'currency_code' => 'INR',
            'timezone'      => 'Asia/Kolkata',
        ])->assertStatus(422)->assertJsonValidationErrors(['email']);
    }

    // ────────────────────────────────────────────────────────
    // READ
    // ────────────────────────────────────────────────────────

    /** @test */
    public function admin_can_list_all_companies_paginated(): void
    {
        Company::factory()->count(5)->create();

        $response = $this->withToken($this->token)->getJson('/api/companies');

        $response->assertStatus(200)
                 ->assertJson(['success' => true])
                 ->assertJsonStructure([
                     'data', 'meta' => ['current_page', 'per_page', 'total'],
                 ]);
    }

    /** @test */
    public function admin_can_search_companies(): void
    {
        Company::create([
            'slug'          => 'searchable-corp',
            'name'          => 'Searchable Corp',
            'code'          => 'SRCH1',
            'currency_code' => 'INR',
            'timezone'      => 'Asia/Kolkata',
        ]);

        $this->withToken($this->token)->getJson('/api/companies?search=Searchable')
             ->assertStatus(200)
             ->assertJsonPath('meta.total', 1);
    }

    /** @test */
    public function admin_can_filter_companies_by_is_active(): void
    {
        // Use a fresh unique search to isolate these two records
        Company::create(['slug' => 'active-filter-co',   'name' => 'Active Filter Co',   'code' => 'ACTF1', 'currency_code' => 'INR', 'timezone' => 'Asia/Kolkata', 'is_active' => 1]);
        Company::create(['slug' => 'inactive-filter-co', 'name' => 'Inactive Filter Co', 'code' => 'INAF1', 'currency_code' => 'INR', 'timezone' => 'Asia/Kolkata', 'is_active' => 0]);

        $activeResponse = $this->withToken($this->token)->getJson('/api/companies?search=Active+Filter+Co&is_active=1');
        $activeResponse->assertStatus(200);
        $this->assertGreaterThanOrEqual(1, $activeResponse->json('meta.total'));

        $inactiveResponse = $this->withToken($this->token)->getJson('/api/companies?search=Inactive+Filter+Co&is_active=0');
        $inactiveResponse->assertStatus(200);
        $this->assertGreaterThanOrEqual(1, $inactiveResponse->json('meta.total'));
    }

    /** @test */
    public function admin_can_view_company_by_slug(): void
    {
        Company::create([
            'slug'          => 'view-corp',
            'name'          => 'View Corp',
            'code'          => 'VIEW1',
            'currency_code' => 'INR',
            'timezone'      => 'Asia/Kolkata',
        ]);

        $this->withToken($this->token)->getJson('/api/companies/view-corp')
             ->assertStatus(200)
             ->assertJsonPath('data.slug', 'view-corp')
             ->assertJsonPath('data.code', 'VIEW1');
    }

    /** @test */
    public function returns_404_for_nonexistent_company_slug(): void
    {
        $this->withToken($this->token)->getJson('/api/companies/does-not-exist-xyz')
             ->assertStatus(404)
             ->assertJson(['success' => false]);
    }

    // ────────────────────────────────────────────────────────
    // UPDATE
    // ────────────────────────────────────────────────────────

    /** @test */
    public function admin_can_update_company_fields(): void
    {
        Company::create([
            'slug'          => 'update-corp',
            'name'          => 'Update Corp',
            'code'          => 'UPD01',
            'currency_code' => 'INR',
            'timezone'      => 'Asia/Kolkata',
        ]);

        $this->withToken($this->token)->putJson('/api/companies/update-corp', [
            'name'          => 'Updated Name',
            'city'          => 'Mumbai',
            'currency_code' => 'USD',
            'is_active'     => 0,
        ])->assertStatus(200)
          ->assertJsonPath('data.name', 'Updated Name')
          ->assertJsonPath('data.city', 'Mumbai')
          ->assertJsonPath('data.currency_code', 'USD')
          ->assertJsonPath('data.is_active', false);
    }

    /** @test */
    public function update_company_with_logo_replaces_old_logo(): void
    {
        Storage::fake('public');

        // Create with logo
        $company = Company::create([
            'slug'          => 'logo-update-corp',
            'name'          => 'Logo Update Corp',
            'code'          => 'LUC01',
            'currency_code' => 'INR',
            'timezone'      => 'Asia/Kolkata',
            'logo_path'     => 'logos/old_logo.png',
        ]);

        Storage::disk('public')->put('logos/old_logo.png', 'fake old content');

        // Use create() instead of image() — GD extension may not be available
        $newLogo = UploadedFile::fake()->create('new_logo.png', 500, 'image/png');

        $response = $this->withToken($this->token)->post(
            '/api/companies/logo-update-corp',
            ['logo' => $newLogo],
            ['Accept' => 'application/json']
        );

        $response->assertStatus(200);
        $this->assertNotNull($response->json('data.logo_path'));
        // Old logo should be deleted
        Storage::disk('public')->assertMissing('logos/old_logo.png');
    }

    /** @test */
    public function update_company_name_regenerates_slug(): void
    {
        Company::create([
            'slug'          => 'old-slug-corp',
            'name'          => 'Old Slug Corp',
            'code'          => 'OSC01',
            'currency_code' => 'INR',
            'timezone'      => 'Asia/Kolkata',
        ]);

        $this->withToken($this->token)->putJson('/api/companies/old-slug-corp', [
            'name' => 'New Name Corp',
        ])->assertStatus(200)
          ->assertJsonPath('data.slug', 'new-name-corp');
    }

    /** @test */
    public function update_company_fails_with_invalid_phone(): void
    {
        Company::create([
            'slug'          => 'phone-test-corp',
            'name'          => 'Phone Test Corp',
            'code'          => 'PTC01',
            'currency_code' => 'INR',
            'timezone'      => 'Asia/Kolkata',
        ]);

        $this->withToken($this->token)->putJson('/api/companies/phone-test-corp', [
            'phone' => '123',
        ])->assertStatus(422)->assertJsonValidationErrors(['phone']);
    }

    // ────────────────────────────────────────────────────────
    // DELETE
    // ────────────────────────────────────────────────────────

    /** @test */
    public function admin_can_delete_company(): void
    {
        Company::create([
            'slug'          => 'delete-corp',
            'name'          => 'Delete Corp',
            'code'          => 'DEL01',
            'currency_code' => 'INR',
            'timezone'      => 'Asia/Kolkata',
        ]);

        $this->withToken($this->token)->deleteJson('/api/companies/delete-corp')
             ->assertStatus(200)
             ->assertJson(['success' => true]);

        $this->assertDatabaseMissing('companies', ['slug' => 'delete-corp']);
    }

    /** @test */
    public function delete_company_also_removes_logo(): void
    {
        Storage::fake('public');

        Company::create([
            'slug'          => 'logo-delete-corp',
            'name'          => 'Logo Delete Corp',
            'code'          => 'LDC01',
            'currency_code' => 'INR',
            'timezone'      => 'Asia/Kolkata',
            'logo_path'     => 'logos/delete_me.png',
        ]);

        Storage::disk('public')->put('logos/delete_me.png', 'fake content');

        $this->withToken($this->token)->deleteJson('/api/companies/logo-delete-corp')
             ->assertStatus(200);

        Storage::disk('public')->assertMissing('logos/delete_me.png');
    }

    /** @test */
    public function delete_returns_404_for_nonexistent_slug(): void
    {
        $this->withToken($this->token)->deleteJson('/api/companies/ghost-corp')
             ->assertStatus(404)
             ->assertJson(['success' => false]);
    }
}
