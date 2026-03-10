<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\AuthService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class AuthController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        protected AuthService $authService
    ) {}

    /**
     * Register a new company user.
     *
     * POST /api/register
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $this->authService->register($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Registration successful',
            ], 201);
        } catch (Throwable $e) {
            Log::error('Company register error', ['error' => $e->getMessage()]);

            return $this->errorResponse(
                message: 'An error occurred during registration.',
                statusCode: 500
            );
        }
    }

    /**
     * Login a company user and get a token.
     *
     * POST /api/login
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $credentials = $request->validated();
            
            $token = $this->authService->attemptLogin($credentials['email'], $credentials['password']);

            if (!$token) {
                return $this->errorResponse(
                    message: 'Invalid credentials or account is not active.',
                    statusCode: 401
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'token'   => $token,
            ]);
        } catch (Throwable $e) {
            Log::error('Company login error', ['error' => $e->getMessage()]);

            return $this->errorResponse(
                message: 'An error occurred during login.',
                statusCode: 500
            );
        }
    }

    /**
     * Logout the authenticated company user.
     *
     * POST /api/logout
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            $this->authService->logout($request->user());

            return response()->json([
                'success' => true,
                'message' => 'Logout successful',
            ]);
        } catch (Throwable $e) {
            Log::error('Company logout error', ['error' => $e->getMessage()]);

            return $this->errorResponse(
                message: 'An error occurred during logout.',
                statusCode: 500
            );
        }
    }
}
