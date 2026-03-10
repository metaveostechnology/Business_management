<?php

namespace App\Repositories;

use App\Models\Feature;
use Illuminate\Database\Eloquent\Collection;

class FeatureRepository
{
    /**
     * Get all features ordered by sort_order, with optional search & filter.
     */
    public function getAll(string $search = null, mixed $isActive = null): Collection
    {
        $query = Feature::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
            });
        }

        if (!is_null($isActive) && $isActive !== '') {
            $query->where('is_active', (bool)$isActive);
        }

        return $query->orderBy('sort_order')->orderBy('name')->get();
    }

    /**
     * Find a feature by slug.
     */
    public function findBySlug(string $slug): ?Feature
    {
        return Feature::where('slug', $slug)->first();
    }

    /**
     * Create a new feature.
     */
    public function create(array $data): Feature
    {
        return Feature::create($data);
    }

    /**
     * Update an existing feature.
     */
    public function update(Feature $feature, array $data): Feature
    {
        $feature->update($data);
        return $feature->fresh();
    }

    /**
     * Delete a feature.
     */
    public function delete(Feature $feature): bool
    {
        return (bool)$feature->delete();
    }

    /**
     * Check if a slug already exists (optionally excluding a given feature id).
     */
    public function slugExists(string $slug, int $excludeId = null): bool
    {
        return Feature::where('slug', $slug)
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->exists();
    }
}
