<?php

namespace App\Repositories;

use App\Models\Role;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class RoleRepository
{
    /**
     * Get paginated roles with optional search & filter.
     */
    public function getAll(
        string $search = null,
        mixed $isActive = null,
        int $perPage = 10
    ): LengthAwarePaginator {
        $query = Role::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if (!is_null($isActive) && $isActive !== '') {
            $query->where('is_active', (bool) $isActive);
        }

        return $query->latest()->paginate($perPage);
    }

    /**
     * Find a role by its slug.
     */
    public function findBySlug(string $slug): ?Role
    {
        return Role::where('slug', $slug)->first();
    }

    /**
     * Create a new role.
     */
    public function create(array $data): Role
    {
        return Role::create($data);
    }

    /**
     * Update an existing role.
     */
    public function update(Role $role, array $data): Role
    {
        $role->update($data);
        return $role->fresh();
    }

    /**
     * Delete a role.
     */
    public function delete(Role $role): bool
    {
        return (bool) $role->delete();
    }

    /**
     * Check if a slug already exists (optionally excluding a given role id).
     */
    public function slugExists(string $slug, int $excludeId = null): bool
    {
        return Role::where('slug', $slug)
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->exists();
    }
}
