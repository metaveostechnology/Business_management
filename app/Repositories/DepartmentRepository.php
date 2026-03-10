<?php

namespace App\Repositories;

use App\Models\Department;
use Illuminate\Database\Eloquent\Collection;

class DepartmentRepository
{
    /**
     * Get all departments scoped to a company, with optional search and filter.
     */
    public function getByCompany(
        int $companyId,
        string $search = null,
        mixed $isActive = null
        ): Collection
    {
        $query = Department::where('company_id', $companyId);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if (!is_null($isActive) && $isActive !== '') {
            $query->where('is_active', (bool)$isActive);
        }

        return $query->orderBy('level_no')->orderBy('name')->get();
    }

    /**
     * Find a department by slug, scoped to a company.
     */
    public function findBySlugAndCompany(string $slug, int $companyId): ?Department
    {
        return Department::where('slug', $slug)
            ->where('company_id', $companyId)
            ->first();
    }

    /**
     * Create a new department.
     */
    public function create(array $data): Department
    {
        return Department::create($data);
    }

    /**
     * Update an existing department.
     */
    public function update(Department $department, array $data): Department
    {
        $department->update($data);
        return $department->fresh();
    }

    /**
     * Delete a department.
     */
    public function delete(Department $department): bool
    {
        return (bool)$department->delete();
    }

    /**
     * Check if a slug already exists (optionally excluding a given department id).
     */
    public function slugExists(string $slug, int $excludeId = null): bool
    {
        return Department::where('slug', $slug)
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->exists();
    }

    /**
     * Check if a code already exists within a company (optionally excluding a department id).
     */
    public function codeExistsInCompany(string $code, int $companyId, int $excludeId = null): bool
    {
        return Department::where('company_id', $companyId)
            ->where('code', $code)
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->exists();
    }
}
