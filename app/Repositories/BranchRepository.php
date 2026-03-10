<?php

namespace App\Repositories;

use App\Models\Branch;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class BranchRepository
{
    /**
     * Get paginated branches scoped to a company, with optional search & filter.
     */
    public function getByCompany(
        int $companyId,
        string $search = null,
        mixed $isActive = null,
        int $perPage = 10
        ): LengthAwarePaginator
    {
        $query = Branch::where('company_id', $companyId);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%");
            });
        }

        if (!is_null($isActive) && $isActive !== '') {
            $query->where('is_active', (bool)$isActive);
        }

        return $query->latest()->paginate($perPage);
    }

    /**
     * Find a branch by slug, scoped to a company.
     */
    public function findBySlugAndCompany(string $slug, int $companyId): ?Branch
    {
        return Branch::where('slug', $slug)
            ->where('company_id', $companyId)
            ->first();
    }

    /**
     * Create a new branch.
     */
    public function create(array $data): Branch
    {
        return Branch::create($data);
    }

    /**
     * Update an existing branch.
     */
    public function update(Branch $branch, array $data): Branch
    {
        $branch->update($data);
        return $branch->fresh();
    }

    /**
     * Delete a branch.
     */
    public function delete(Branch $branch): bool
    {
        return (bool)$branch->delete();
    }

    /**
     * Check if a slug already exists (optionally excluding a given branch id).
     */
    public function slugExists(string $slug, int $excludeId = null): bool
    {
        return Branch::where('slug', $slug)
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->exists();
    }

    /**
     * Check if a code already exists within a company (optionally excluding a branch id).
     */
    public function codeExistsInCompany(string $code, int $companyId, int $excludeId = null): bool
    {
        return Branch::where('company_id', $companyId)
            ->where('code', $code)
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->exists();
    }
}
