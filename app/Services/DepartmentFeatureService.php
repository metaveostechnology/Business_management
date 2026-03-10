<?php

namespace App\Services;

use App\Models\Department;
use App\Models\DepartmentFeature;
use App\Models\Feature;
use App\Repositories\DepartmentFeatureRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class DepartmentFeatureService
{
    public function __construct(
        protected DepartmentFeatureRepository $repository
    ) {}

    public function getMappings(int $companyId, string $search = null): Collection
    {
        return $this->repository->getByCompany($companyId, $search);
    }

    public function findBySlug(string $slug, int $companyId): ?DepartmentFeature
    {
        return $this->repository->findBySlugAndCompany($slug, $companyId);
    }

    public function pairExists(int $departmentId, int $featureId, int $excludeId = null): bool
    {
        return $this->repository->pairExists($departmentId, $featureId, $excludeId);
    }

    public function createMapping(array $data): DepartmentFeature
    {
        $data['slug'] = $this->generateSlug($data['department_id'], $data['feature_id']);
        return $this->repository->create($data);
    }

    public function updateMapping(DepartmentFeature $mapping, array $data): DepartmentFeature
    {
        return $this->repository->update($mapping, $data);
    }

    public function deleteMapping(DepartmentFeature $mapping): bool
    {
        return $this->repository->delete($mapping);
    }

    public function generateSlug(int $departmentId, int $featureId): string
    {
        $department = Department::find($departmentId);
        $feature    = Feature::find($featureId);
        $baseSlug   = Str::slug(($department?->slug ?? $departmentId) . '-' . ($feature?->slug ?? $featureId));
        $slug       = $baseSlug;
        $counter    = 2;
        while ($this->repository->slugExists($slug)) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }
        return $slug;
    }
}