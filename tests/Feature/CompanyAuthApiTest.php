<?php

namespace Tests\Feature;

use App\Models\CompanyRegister;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CompanyAuthApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_company_can_register()
    {
        $payload = [
            'email'                 => 'register_test@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson('/api/register', $payload);

        $response->assertStatus(201)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Registration successful',
                 ]);

        $this->assertDatabaseHas('company_register', [
            'email'     => 'register_test@example.com',
            'is_active' => 1,
        ]);
    }

    public function test_company_cannot_register_with_existing_email()
    {
        CompanyRegister::create([
            'email'     => 'existing@example.com',
            'password'  => Hash::make('password123'),
            'is_active' => 1,
        ]);

        $payload = [
            'email'                 => 'existing@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson('/api/register', $payload);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }

    public function test_company_can_login_with_valid_credentials()
    {
        CompanyRegister::create([
            'email'     => 'login_valid@example.com',
            'password'  => Hash::make('password123'),
            'is_active' => 1,
        ]);

        $payload = [
            'email'    => 'login_valid@example.com',
            'password' => 'password123',
        ];

        $response = $this->postJson('/api/login', $payload);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'token',
                 ])
                 ->assertJson([
                     'success' => true,
                     'message' => 'Login successful',
                 ]);
    }

    public function test_company_cannot_login_with_invalid_password()
    {
        CompanyRegister::create([
            'email'     => 'login_invalid@example.com',
            'password'  => Hash::make('password123'),
            'is_active' => 1,
        ]);

        $payload = [
            'email'    => 'login_invalid@example.com',
            'password' => 'wrongpassword',
        ];

        $response = $this->postJson('/api/login', $payload);

        $response->assertStatus(401)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Invalid credentials or account is not active.',
                 ]);
    }

    public function test_inactive_company_cannot_login()
    {
        CompanyRegister::create([
            'email'     => 'inactive@example.com',
            'password'  => Hash::make('password123'),
            'is_active' => 0,
        ]);

        $payload = [
            'email'    => 'inactive@example.com',
            'password' => 'password123',
        ];

        $response = $this->postJson('/api/login', $payload);

        $response->assertStatus(401)
                 ->assertJson([
                     'success' => false,
                 ]);
    }

    public function test_authenticated_company_can_logout()
    {
        $companyUser = CompanyRegister::create([
            'email'     => 'logout_test@example.com',
            'password'  => Hash::make('password123'),
            'is_active' => 1,
        ]);

        $token = $companyUser->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/logout');

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Logout successful',
                 ]);

        // Assert token was deleted
        $this->assertDatabaseMissing('personal_access_tokens', [
            'tokenable_id' => $companyUser->id,
        ]);
    }

    public function test_unauthenticated_request_to_logout_fails()
    {
        $response = $this->postJson('/api/logout');

        $response->assertStatus(401);
    }
}
