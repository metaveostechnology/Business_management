<?php

namespace App\Services;

use App\Models\BranchUser;
use App\Repositories\BranchUserRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class BranchUserService
{
    public function __construct(
        protected BranchUserRepository $branchUserRepository
    ) {}

    /**
     * Get paginated branch users for a company.
     */
    public function getBranchUsers(
        int $companyId,
        string $search = null,
        mixed $isActive = null,
        int $perPage = 10
    ): LengthAwarePaginator {
        return $this->branchUserRepository->getByCompany($companyId, $search, $isActive, $perPage);
    }

    /**
     * Find a branch user by slug, scoped to a company.
     */
    public function findBySlug(string $slug, int $companyId): ?BranchUser
    {
        return $this->branchUserRepository->findBySlugAndCompany($slug, $companyId);
    }

    /**
     * Create a new branch user with a hashed password and unique slug.
     */
    public function createBranchUser(array $data): BranchUser
    {
        $data['slug']     = $this->generateUniqueSlug($data['name']);
        $data['password'] = Hash::make($data['password']);

        return $this->branchUserRepository->create($data);
    }

    /**
     * Update an existing branch user.
     * Hashes password if provided, regenerates slug if name changes.
     */
    public function updateBranchUser(BranchUser $branchUser, array $data): BranchUser
    {
        if (isset($data['name']) && $data['name'] !== $branchUser->name) {
            $data['slug'] = $this->generateUniqueSlug($data['name'], $branchUser->id);
        }

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        return $this->branchUserRepository->update($branchUser, $data);
    }

    /**
     * Soft-delete a branch user.
     */
    public function deleteBranchUser(BranchUser $branchUser): bool
    {
        return $this->branchUserRepository->delete($branchUser);
    }

    /**
     * Change a branch user's password.
     * Optionally verifies the current password if provided.
     *
     * @throws \Exception
     */
    public function changePassword(BranchUser $branchUser, array $data): BranchUser
    {
        // If current_password is provided, verify it
        if (!empty($data['current_password'])) {
            if (!Hash::check($data['current_password'], $branchUser->password)) {
                throw new \Exception('The current password is incorrect.', 422);
            }
        }

        return $this->branchUserRepository->update($branchUser, [
            'password' => Hash::make($data['new_password']),
        ]);
    }

    /**
     * Generate a unique slug from a branch user name.
     * Appends -2, -3, etc. if duplicates exist.
     */
    public function generateUniqueSlug(string $name, int $excludeId = null): string
    {
        $baseSlug = Str::slug($name);
        $slug     = $baseSlug;
        $counter  = 2;

        while ($this->branchUserRepository->slugExists($slug, $excludeId)) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}
