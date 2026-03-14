<?php

namespace App\Repositories;

use App\Models\Department;
use Illuminate\Database\Eloquent\Collection;

class DepartmentRepository
{
    /**
     * Get all departments scoped to a company, with optional search and filter.
     */
    public function getDepartments(
        string $search = null,
        mixed $isActive = null
        ): Collection
    {
        $query = Department::query();

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

    public function findBySlug(string $slug): ?Department
    {
        return Department::where('slug', $slug)
            ->first();
    }
}
