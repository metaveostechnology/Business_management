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
     * Get all departments.
     */
    public function getDepartments(
        string $search = null,
        mixed $isActive = null
        ): Collection
    {
        return $this->departmentRepository->getDepartments($search, $isActive);
    }

    /**
     * Find a department by slug.
     */
    public function findBySlug(string $slug): ?Department
    {
        return $this->departmentRepository->findBySlug($slug);
    }
}
