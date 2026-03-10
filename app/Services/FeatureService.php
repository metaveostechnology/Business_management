<?php

namespace App\Services;

use App\Models\Feature;
use App\Repositories\FeatureRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class FeatureService
{
    public function __construct(protected
        FeatureRepository $featureRepository
        )
    {
    }

    /**
     * Get all features (ordered by sort_order).
     */
    public function getFeatures(string $search = null, mixed $isActive = null): Collection
    {
        return $this->featureRepository->getAll($search, $isActive);
    }

    /**
     * Find a feature by slug.
     */
    public function findBySlug(string $slug): ?Feature
    {
        return $this->featureRepository->findBySlug($slug);
    }

    /**
     * Create a new feature with an auto-generated unique slug.
     */
    public function createFeature(array $data): Feature
    {
        $data['slug'] = $this->generateUniqueSlug($data['name']);

        return $this->featureRepository->create($data);
    }

    /**
     * Update an existing feature.
     * Regenerates the slug if the name changes.
     */
    public function updateFeature(Feature $feature, array $data): Feature
    {
        if (isset($data['name']) && $data['name'] !== $feature->name) {
            $data['slug'] = $this->generateUniqueSlug($data['name'], $feature->id);
        }

        return $this->featureRepository->update($feature, $data);
    }

    /**
     * Delete a feature.
     */
    public function deleteFeature(Feature $feature): bool
    {
        return $this->featureRepository->delete($feature);
    }

    /**
     * Generate a unique slug from a feature name.
     * Appends -2, -3, etc. if duplicates exist.
     */
    public function generateUniqueSlug(string $name, int $excludeId = null): string
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $counter = 2;

        while ($this->featureRepository->slugExists($slug, $excludeId)) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}
