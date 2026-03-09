<?php

namespace App\Services;

use App\Models\CompanyRegister;
use App\Repositories\CompanyRegisterRepository;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function __construct(
        protected CompanyRegisterRepository $repository
    ) {}

    /**
     * Register a new company user.
     */
    public function register(array $data): CompanyRegister
    {
        $data['password'] = Hash::make($data['password']);
        return $this->repository->create($data);
    }

    /**
     * Attempt login for a company user.
     * Returns Sanctum token on success, null on failure.
     */
    public function attemptLogin(string $email, string $password): ?string
    {
        $user = $this->repository->findByEmail($email);

        if (!$user || !Hash::check($password, $user->password)) {
            return null;
        }

        if (!$user->is_active) {
            return null; // Or throw a specific exception if distinguishing is required
        }

        return $user->createToken('company-api-token')->plainTextToken;
    }

    /**
     * Logout the current user (revoke token).
     */
    public function logout(CompanyRegister $user): void
    {
        $user->currentAccessToken()->delete();
    }
}
