<?php

namespace App\Repositories;

use App\Models\DepartmentFeature;
use Illuminate\Database\Eloquent\Collection;

class DepartmentFeatureRepository
{
    /**
     * Get all department-feature mappings scoped to a company.
     */
    public function getByCompany(int $companyId, string $search = null): Collection
    {
        $query = DepartmentFeature::whereHas('department', fn($q) => $q->where('company_id', $companyId))
            ->with(['department', 'feature']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('department', fn($d) => $d->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('feature', fn($f) => $f->where('name', 'like', "%{$search}%"));
            });
        }

        return $query->latest()->get();
    }

    /**
     * Find a department-feature by slug, ensuring it belongs to the given company.
     */
    public function findBySlugAndCompany(string $slug, int $companyId): ?DepartmentFeature
    {
        return DepartmentFeature::where('slug', $slug)
            ->whereHas('department', fn($q) => $q->where('company_id', $companyId))
            ->with(['department', 'feature'])
            ->first();
    }

    /**
     * Check if the (department_id, feature_id) pair already exists.
     */
    public function pairExists(int $departmentId, int $featureId, int $excludeId = null): bool
    {
        return DepartmentFeature::where('department_id', $departmentId)
            ->where('feature_id', $featureId)
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->exists();
    }

    /**
     * Create a new department-feature mapping.
     */
    public function create(array $data): DepartmentFeature
    {
        return DepartmentFeature::create($data);
    }

    /**
     * Update an existing mapping.
     */
    public function update(DepartmentFeature $departmentFeature, array $data): DepartmentFeature
    {
        $departmentFeature->update($data);
        return $departmentFeature->fresh(['department', 'feature']);
    }

    /**
     * Delete a mapping.
     */
    public function delete(DepartmentFeature $departmentFeature): bool
    {
        return (bool)$departmentFeature->delete();
    }

    /**
     * Check if a slug already exists (optionally excluding a given id).
     */
    public function slugExists(string $slug, int $excludeId = null): bool
    {
        return DepartmentFeature::where('slug', $slug)
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->exists();
    }
}
