<?php

namespace App\Services;

use App\Models\Department;
use App\Repositories\DepartmentRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class DepartmentService
{
    public function __construct(protected
        DepartmentRepository $departmentRepository
        )
    {
    }

    /**
     * Get all departments for a company.
     */
    public function getDepartments(
        int $companyId,
        string $search = null,
        mixed $isActive = null
        ): Collection
    {
        return $this->departmentRepository->getByCompany($companyId, $search, $isActive);
    }

    /**
     * Find a department by slug, scoped to a company.
     */
    public function findBySlug(string $slug, int $companyId): ?Department
    {
        return $this->departmentRepository->findBySlugAndCompany($slug, $companyId);
    }

    /**
     * Create a new department with an auto-generated unique slug.
     */
    public function createDepartment(array $data): Department
    {
        $data['slug'] = $this->generateUniqueSlug($data['name']);

        return $this->departmentRepository->create($data);
    }

    /**
     * Update an existing department.
     * Regenerates the slug if the name changes.
     */
    public function updateDepartment(Department $department, array $data): Department
    {
        if (isset($data['name']) && $data['name'] !== $department->name) {
            $data['slug'] = $this->generateUniqueSlug($data['name'], $department->id);
        }

        return $this->departmentRepository->update($department, $data);
    }

    /**
     * Delete a department.
     */
    public function deleteDepartment(Department $department): bool
    {
        return $this->departmentRepository->delete($department);
    }

    /**
     * Generate a unique slug from a department name.
     * Appends -2, -3, etc. on collision.
     */
    public function generateUniqueSlug(string $name, int $excludeId = null): string
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $counter = 2;

        while ($this->departmentRepository->slugExists($slug, $excludeId)) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}
