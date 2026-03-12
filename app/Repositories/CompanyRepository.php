<?php

namespace App\Repositories;

use App\Models\Company;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CompanyRepository
{
    /**
     * Get paginated list of companies with optional search and is_active filter.
     */
    public function getAll(string $search = null, mixed $isActive = null, int $perPage = 10): LengthAwarePaginator
    {
        $query = Company::where('is_delete', false);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('legal_name', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
            });
        }

        if (!is_null($isActive) && $isActive !== '') {
            $query->where('is_active', (bool) $isActive);
        }

        return $query->latest()->paginate($perPage);
    }

    /**
     * Find a company by slug.
     */
    public function findBySlug(string $slug): ?Company
    {
        return Company::where('slug', $slug)->where('is_delete', false)->first();
    }

    /**
     * Create a new company.
     */
    public function create(array $data): Company
    {
        return Company::create($data);
    }

    /**
     * Update an existing company.
     */
    public function update(Company $company, array $data): Company
    {
        $company->update($data);
        return $company->fresh();
    }

    /**
     * Delete a company.
     */
    public function delete(Company $company): bool
    {
        return $company->delete();
    }

    /**
     * Check if a slug already exists (optionally excluding a given company id).
     */
    public function slugExists(string $slug, int $excludeId = null): bool
    {
        return Company::where('slug', $slug)
                      ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
                      ->exists();
    }
}
