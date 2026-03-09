<?php

namespace App\Repositories;

use App\Models\Admin;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class AdminRepository
{
    /**
     * Get paginated list of admins with optional search and status filter.
     */
    public function getAll(string $search = null, string $status = null, int $perPage = 10): LengthAwarePaginator
    {
        $query = Admin::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        return $query->latest()->paginate($perPage);
    }

    /**
     * Find an admin by slug (including soft deleted).
     */
    public function findBySlugWithTrashed(string $slug): ?Admin
    {
        return Admin::withTrashed()->where('slug', $slug)->first();
    }

    /**
     * Find an admin by slug (active only).
     */
    public function findBySlug(string $slug): ?Admin
    {
        return Admin::where('slug', $slug)->first();
    }

    /**
     * Find an admin by email or username.
     */
    public function findByEmailOrUsername(string $login): ?Admin
    {
        return Admin::where('email', $login)
                    ->orWhere('username', $login)
                    ->first();
    }

    /**
     * Create a new admin.
     */
    public function create(array $data): Admin
    {
        return Admin::create($data);
    }

    /**
     * Update an admin.
     */
    public function update(Admin $admin, array $data): Admin
    {
        $admin->update($data);
        return $admin->fresh();
    }

    /**
     * Soft delete an admin.
     */
    public function delete(Admin $admin): bool
    {
        return $admin->delete();
    }

    /**
     * Restore a soft-deleted admin.
     */
    public function restore(string $slug): ?Admin
    {
        $admin = Admin::onlyTrashed()->where('slug', $slug)->first();

        if ($admin) {
            $admin->restore();
            return $admin->fresh();
        }

        return null;
    }

    /**
     * Check if a slug already exists.
     */
    public function slugExists(string $slug, int $excludeId = null): bool
    {
        return Admin::withTrashed()
                    ->where('slug', $slug)
                    ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
                    ->exists();
    }
}
