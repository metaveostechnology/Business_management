<?php

namespace App\Services;

use App\Models\Branch;
use App\Repositories\BranchRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class BranchService
{
    public function __construct(protected
        BranchRepository $branchRepository
        )
    {
    }

    /**
     * Get paginated branches for a company.
     */
    public function getBranches(
        int $companyId,
        string $search = null,
        mixed $isActive = null,
        int $perPage = 10
        ): LengthAwarePaginator
    {
        return $this->branchRepository->getByCompany($companyId, $search, $isActive, $perPage);
    }

    /**
     * Find a branch by slug, scoped to a company.
     */
    public function findBySlug(string $slug, int $companyId): ?Branch
    {
        return $this->branchRepository->findBySlugAndCompany($slug, $companyId);
    }

    /**
     * Create a new branch with an auto-generated unique slug.
     */
    public function createBranch(array $data): Branch
    {
        $data['slug'] = $this->generateUniqueSlug($data['name']);

        return $this->branchRepository->create($data);
    }

    /**
     * Update an existing branch.
     * Regenerates the slug if the branch name changes.
     */
    public function updateBranch(Branch $branch, array $data): Branch
    {
        if (isset($data['name']) && $data['name'] !== $branch->name) {
            $data['slug'] = $this->generateUniqueSlug($data['name'], $branch->id);
        }

        return $this->branchRepository->update($branch, $data);
    }

    /**
     * Delete a branch.
     */
    public function deleteBranch(Branch $branch): bool
    {
        return $this->branchRepository->delete($branch);
    }

    /**
     * Generate a unique slug from a branch name.
     * Appends -2, -3, etc. if duplicates exist.
     */
    public function generateUniqueSlug(string $name, int $excludeId = null): string
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $counter = 2;

        while ($this->branchRepository->slugExists($slug, $excludeId)) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}
