<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LoginRequest;
use App\Http\Requests\Admin\UpdateProfileRequest;
use App\Http\Resources\AdminResource;
use App\Services\AdminService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Throwable;

class AdminAuthController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        protected AdminService $adminService
    ) {}

    /**
     * Authenticate an admin and return a Sanctum token.
     *
     * POST /api/admin/login
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $result = $this->adminService->attemptLogin(
                login: $request->input('login'),
                password: $request->input('password'),
                ip: $request->ip()
            );

            if (!$result) {
                return $this->errorResponse(
                    message: 'Invalid credentials or account is not active.',
                    statusCode: 401
                );
            }

            return $this->successResponse(
                data: [
                    'token' => $result['token'],
                    'admin' => new AdminResource($result['admin']),
                ],
                message: 'Login successful.'
            );
        } catch (Throwable $e) {
            Log::error('Admin login error', ['error' => $e->getMessage()]);

            return $this->errorResponse(
                message: 'An error occurred during login. Please try again.',
                statusCode: 500
            );
        }
    }

    /**
     * Revoke the current Sanctum token (logout).
     *
     * POST /api/admin/logout
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return $this->successResponse(message: 'Logged out successfully.');
        } catch (Throwable $e) {
            Log::error('Admin logout error', ['error' => $e->getMessage()]);

            return $this->errorResponse(
                message: 'An error occurred during logout. Please try again.',
                statusCode: 500
            );
        }
    }

    /**
     * Get the authenticated admin's profile.
     *
     * GET /api/admin/profile
     */
    public function profile(Request $request): JsonResponse
    {
        try {
            return $this->successResponse(
                data: new AdminResource($request->user()),
                message: 'Profile retrieved successfully.'
            );
        } catch (Throwable $e) {
            Log::error('Admin profile fetch error', ['error' => $e->getMessage()]);

            return $this->errorResponse(
                message: 'An error occurred while fetching the profile.',
                statusCode: 500
            );
        }
    }

    /**
     * Update the authenticated admin's own profile.
     *
     * PUT /api/admin/profile
     */
    public function updateProfile(UpdateProfileRequest $request): JsonResponse
    {
        try {
            $admin = $request->user();
            $data  = $request->validated();

            // Verify current password if changing password
            if (isset($data['password']) && !empty($data['password'])) {
                if (!Hash::check($data['current_password'] ?? '', $admin->password)) {
                    return $this->errorResponse(
                        message: 'Current password is incorrect.',
                        statusCode: 422
                    );
                }
            }

            unset($data['current_password'], $data['password_confirmation']);

            $updatedAdmin = $this->adminService->updateProfile($admin, $data);

            return $this->successResponse(
                data: new AdminResource($updatedAdmin),
                message: 'Profile updated successfully.'
            );
        } catch (Throwable $e) {
            Log::error('Admin profile update error', ['error' => $e->getMessage()]);

            return $this->errorResponse(
                message: 'An error occurred while updating the profile.',
                statusCode: 500
            );
        }
    }
}
