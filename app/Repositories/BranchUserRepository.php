<?php

namespace App\Repositories;

use App\Models\BranchUser;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class BranchUserRepository
{
    /**
     * Get paginated branch users scoped to a company, with optional search & filter.
     */
    public function getByCompany(
        int $companyId,
        string $search = null,
        mixed $isActive = null,
        int $perPage = 10
    ): LengthAwarePaginator {
        $query = BranchUser::where('company_id', $companyId)
                            ->where('is_delete', false)
                            ->with(['branch', 'department']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if (!is_null($isActive) && $isActive !== '') {
            $query->where('is_active', (bool) $isActive);
        }

        return $query->latest()->paginate($perPage);
    }

    /**
     * Find a branch user by slug, scoped to a company.
     */
    public function findBySlugAndCompany(string $slug, int $companyId): ?BranchUser
    {
        return BranchUser::where('slug', $slug)
            ->where('company_id', $companyId)
            ->where('is_delete', false)
            ->with(['branch', 'department'])
            ->first();
    }

    /**
     * Create a new branch user.
     */
    public function create(array $data): BranchUser
    {
        return BranchUser::create($data);
    }

    /**
     * Update an existing branch user.
     */
    public function update(BranchUser $branchUser, array $data): BranchUser
    {
        $branchUser->update($data);
        return $branchUser->fresh(['branch', 'department']);
    }

    /**
     * Soft-delete a branch user (sets is_delete = true).
     */
    public function delete(BranchUser $branchUser): bool
    {
        return (bool) $branchUser->update(['is_delete' => true]);
    }

    /**
     * Check if a slug already exists (optionally excluding a given branch user id).
     */
    public function slugExists(string $slug, int $excludeId = null): bool
    {
        return BranchUser::where('slug', $slug)
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->exists();
    }

    /**
     * Check if an email already exists (optionally excluding a given branch user id).
     */
    public function emailExists(string $email, int $excludeId = null): bool
    {
        return BranchUser::where('email', $email)
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->exists();
    }
}
