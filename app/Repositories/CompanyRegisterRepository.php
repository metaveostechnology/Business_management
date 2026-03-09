<?php

namespace App\Repositories;

use App\Models\CompanyRegister;

class CompanyRegisterRepository
{
    /**
     * Find a company user by email.
     */
    public function findByEmail(string $email): ?CompanyRegister
    {
        return CompanyRegister::where('email', $email)->first();
    }

    /**
     * Create a new company user.
     */
    public function create(array $data): CompanyRegister
    {
        return CompanyRegister::create($data);
    }
}
