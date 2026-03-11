<?php

namespace App\Services;

use App\Models\Role;
use App\Repositories\RoleRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class RoleService
{
    public function __construct(
        protected RoleRepository $roleRepository
    ) {}

    /**
     * Get paginated roles.
     */
    public function getRoles(
        string $search = null,
        mixed $isActive = null,
        int $perPage = 10
    ): LengthAwarePaginator {
        return $this->roleRepository->getAll($search, $isActive, $perPage);
    }

    /**
     * Find a role by slug.
     */
    public function findBySlug(string $slug): ?Role
    {
        return $this->roleRepository->findBySlug($slug);
    }

    /**
     * Create a new role with an auto-generated unique slug.
     */
    public function createRole(array $data): Role
    {
        $data['slug'] = $this->generateUniqueSlug($data['name']);
        return $this->roleRepository->create($data);
    }

    /**
     * Update an existing role. Regenerates slug if name changes.
     */
    public function updateRole(Role $role, array $data): Role
    {
        if (isset($data['name']) && $data['name'] !== $role->name) {
            $data['slug'] = $this->generateUniqueSlug($data['name'], $role->id);
        }
        return $this->roleRepository->update($role, $data);
    }

    /**
     * Delete a role.
     */
    public function deleteRole(Role $role): bool
    {
        return $this->roleRepository->delete($role);
    }

    /**
     * Generate a unique slug from a role name.
     * Appends -2, -3, etc. if duplicates exist.
     */
    public function generateUniqueSlug(string $name, int $excludeId = null): string
    {
        $baseSlug = Str::slug($name);
        $slug     = $baseSlug;
        $counter  = 2;

        while ($this->roleRepository->slugExists($slug, $excludeId)) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}
