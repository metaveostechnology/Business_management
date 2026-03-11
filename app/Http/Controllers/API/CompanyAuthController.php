<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyAuth\ChangeCompanyPasswordRequest;
use App\Http\Requests\CompanyAuth\LoginCompanyRequest;
use App\Http\Requests\CompanyAuth\RegisterCompanyRequest;
use App\Http\Requests\CompanyAuth\UpdateCompanyProfileRequest;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class CompanyAuthController extends Controller
{
    use ApiResponseTrait;

    // ─────────────────────────────────────────────────────────────────────────
    // PUBLIC: POST /api/register-company
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Register a new company (self-registration).
     */
    public function register(RegisterCompanyRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            // Generate unique slug from company name
            $data['slug']     = $this->generateUniqueSlug($data['name']);
            $data['code']     = 'CMP-' . strtoupper(Str::random(6));
            $data['password'] = Hash::make($data['password']);

            // Map `logo` file upload or string to `logo_path` column
            if ($request->hasFile('logo')) {
                $data['logo_path'] = $request->file('logo')->store('companylogo', 'public');
                unset($data['logo']);
            } elseif (isset($data['logo'])) {
                $data['logo_path'] = $data['logo'];
                unset($data['logo']);
            }

            $company = Company::create($data);

            return $this->createdResponse(
                new CompanyResource($company),
                'Company registered successfully.'
            );
        } catch (Throwable $e) {
            Log::error('Company register error', ['error' => $e->getMessage()]);
            return $this->errorResponse('An error occurred during registration.', statusCode: 500);
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // PUBLIC: POST /api/company/login
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Authenticate a company and return a Sanctum token.
     */
    public function login(LoginCompanyRequest $request): JsonResponse
    {
        try {
            $company = Company::where('email', $request->email)
                              ->where('is_active', true)
                              ->where('is_delete', false)
                              ->first();

            if (!$company || !Hash::check($request->password, $company->password)) {
                return $this->errorResponse(
                    'Invalid credentials or account is not active.',
                    statusCode: 401
                );
            }

            // Revoke old tokens and issue a fresh one
            $company->tokens()->delete();
            $token = $company->createToken('company-token')->plainTextToken;

            return $this->successResponse([
                'company' => $company->name,
                'email'   => $company->email,
                'token'   => $token,
                'profile' => new CompanyResource($company),
            ], 'Login successful.');
        } catch (Throwable $e) {
            Log::error('Company login error', ['error' => $e->getMessage()]);
            return $this->errorResponse('An error occurred during login.', statusCode: 500);
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // PROTECTED: POST /api/company/logout
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Revoke the current Sanctum token (logout).
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return $this->successResponse(null, 'Logged out successfully.');
        } catch (Throwable $e) {
            Log::error('Company logout error', ['error' => $e->getMessage()]);
            return $this->errorResponse('An error occurred during logout.', statusCode: 500);
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // PROTECTED: GET /api/company/profile
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Get the authenticated company's profile.
     */
    public function profile(Request $request): JsonResponse
    {
        try {
            return $this->successResponse(
                new CompanyResource($request->user()),
                'Profile retrieved successfully.'
            );
        } catch (Throwable $e) {
            Log::error('Company profile fetch error', ['error' => $e->getMessage()]);
            return $this->errorResponse('An error occurred while fetching the profile.', statusCode: 500);
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // PROTECTED: PUT /api/company/profile
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Update the authenticated company's profile.
     */
    public function updateProfile(UpdateCompanyProfileRequest $request): JsonResponse
    {
        try {
            $company = $request->user();
            $data    = $request->validated();

            // Map `logo` file upload or string to `logo_path` column
            if ($request->hasFile('logo')) {
                // Delete old logo
                if ($company->logo_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($company->logo_path)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($company->logo_path);
                }
                $data['logo_path'] = $request->file('logo')->store('companylogo', 'public');
                unset($data['logo']);
            } elseif (array_key_exists('logo', $data)) {
                $data['logo_path'] = $data['logo'];
                unset($data['logo']);
            }

            // Regenerate slug if name changes
            if (isset($data['name']) && $data['name'] !== $company->name) {
                $data['slug'] = $this->generateUniqueSlug($data['name'], $company->id);
            }

            $company->update($data);

            return $this->successResponse(
                new CompanyResource($company->fresh()),
                'Profile updated successfully.'
            );
        } catch (Throwable $e) {
            Log::error('Company profile update error', ['error' => $e->getMessage()]);
            return $this->errorResponse('An error occurred while updating the profile.', statusCode: 500);
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // PROTECTED: POST /api/company/change-password
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Change the authenticated company's password.
     */
    public function changePassword(ChangeCompanyPasswordRequest $request): JsonResponse
    {
        try {
            $company = $request->user();

            if (!Hash::check($request->current_password, $company->password)) {
                return $this->errorResponse('The current password is incorrect.', statusCode: 422);
            }

            $company->update(['password' => Hash::make($request->new_password)]);

            return $this->successResponse(null, 'Password updated successfully.');
        } catch (Throwable $e) {
            Log::error('Company change-password error', ['error' => $e->getMessage()]);
            return $this->errorResponse('An error occurred while changing the password.', statusCode: 500);
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // HELPER
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Generate a unique slug from the company name.
     */
    private function generateUniqueSlug(string $name, int $excludeId = null): string
    {
        $base    = Str::slug($name);
        $slug    = $base;
        $counter = 2;

        while (
            Company::where('slug', $slug)
                   ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
                   ->exists()
        ) {
            $slug = "{$base}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}
