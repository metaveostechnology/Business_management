<?php

namespace App\Repositories;

use App\Models\SystemSetting;
use Illuminate\Database\Eloquent\Collection;

class SystemSettingRepository
{
    /**
     * Get all settings scoped to a company, with optional group and key filters.
     */
    public function getByCompany(
        int $companyId,
        string $search = null,
        string $group = null
        ): Collection
    {
        $query = SystemSetting::where('company_id', $companyId);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('setting_key', 'like', "%{$search}%")
                    ->orWhere('setting_group', 'like', "%{$search}%");
            });
        }

        if ($group) {
            $query->where('setting_group', $group);
        }

        return $query->orderBy('setting_group')->orderBy('setting_key')->get();
    }

    /**
     * Find a setting by slug, scoped to a company.
     */
    public function findBySlugAndCompany(string $slug, int $companyId): ?SystemSetting
    {
        return SystemSetting::where('slug', $slug)
            ->where('company_id', $companyId)
            ->first();
    }

    /**
     * Create a new system setting.
     */
    public function create(array $data): SystemSetting
    {
        return SystemSetting::create($data);
    }

    /**
     * Update an existing setting.
     */
    public function update(SystemSetting $setting, array $data): SystemSetting
    {
        $setting->update($data);
        return $setting->fresh();
    }

    /**
     * Delete a setting.
     */
    public function delete(SystemSetting $setting): bool
    {
        return (bool)$setting->delete();
    }

    /**
     * Check if a slug already exists (optionally excluding a given id).
     */
    public function slugExists(string $slug, int $excludeId = null): bool
    {
        return SystemSetting::where('slug', $slug)
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->exists();
    }

    /**
     * Check if a (company_id, branch_id, group, key) combination already exists.
     */
    public function scopedKeyExists(
        int $companyId,
        ?int $branchId,
        string $group,
        string $key,
        int $excludeId = null
        ): bool
    {
        return SystemSetting::where('company_id', $companyId)
            ->where('branch_id', $branchId)
            ->where('setting_group', $group)
            ->where('setting_key', $key)
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->exists();
    }
}
