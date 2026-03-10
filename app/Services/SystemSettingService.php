<?php

namespace App\Services;

use App\Models\SystemSetting;
use App\Repositories\SystemSettingRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class SystemSettingService
{
    public function __construct(protected
        SystemSettingRepository $repository
        )
    {
    }

    /**
     * Get all settings for a company.
     */
    public function getSettings(int $companyId, string $search = null, string $group = null): Collection
    {
        return $this->repository->getByCompany($companyId, $search, $group);
    }

    /**
     * Find a setting by slug, scoped to a company.
     */
    public function findBySlug(string $slug, int $companyId): ?SystemSetting
    {
        return $this->repository->findBySlugAndCompany($slug, $companyId);
    }

    /**
     * Check if the (company, branch, group, key) combination already exists.
     */
    public function scopedKeyExists(int $companyId, ?int $branchId, string $group, string $key, int $excludeId = null): bool
    {
        return $this->repository->scopedKeyExists($companyId, $branchId, $group, $key, $excludeId);
    }

    /**
     * Create a new setting with auto-generated slug.
     */
    public function createSetting(array $data): SystemSetting
    {
        $data['slug'] = $this->generateUniqueSlug($data['setting_group'], $data['setting_key']);

        return $this->repository->create($data);
    }

    /**
     * Update an existing setting (only setting_value, value_type, is_public).
     */
    public function updateSetting(SystemSetting $setting, array $data): SystemSetting
    {
        return $this->repository->update($setting, $data);
    }

    /**
     * Delete a setting.
     */
    public function deleteSetting(SystemSetting $setting): bool
    {
        return $this->repository->delete($setting);
    }

    /**
     * Generate a unique slug from setting_group + setting_key.
     *
     * Format: {setting_group}-{setting_key}
     * e.g. "company-timezone"
     */
    public function generateUniqueSlug(string $group, string $key, int $excludeId = null): string
    {
        $baseSlug = Str::slug("{$group}-{$key}");
        $slug = $baseSlug;
        $counter = 2;

        while ($this->repository->slugExists($slug, $excludeId)) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}
