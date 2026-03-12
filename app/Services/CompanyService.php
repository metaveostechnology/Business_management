<?php

namespace App\Services;

use App\Models\Company;
use App\Repositories\CompanyRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class CompanyService
{
    public function __construct(
        protected CompanyRepository $companyRepository
    ) {}

    /**
     * Get paginated list of companies.
     */
    public function getCompanies(string $search = null, mixed $isActive = null, int $perPage = 10): LengthAwarePaginator
    {
        return $this->companyRepository->getAll($search, $isActive, $perPage);
    }

    /**
     * Find a company by slug.
     */
    public function findBySlug(string $slug): ?Company
    {
        return $this->companyRepository->findBySlug($slug);
    }

    /**
     * Create a new company with auto-generated slug.
     */
    public function createCompany(array $data): \App\Models\Company
    {
        $data['slug']     = $this->generateUniqueSlug($data['name']);
        $data['code']     = 'CMP-' . strtoupper(Str::random(6));

        if (!empty($data['password'])) {
            $data['password'] = \Illuminate\Support\Facades\Hash::make($data['password']);
        }

        return $this->companyRepository->create($data);
    }

    /**
     * Update an existing company.
     * Regenerates slug if name changes.
     */
    public function updateCompany(Company $company, array $data): Company
    {
        if (isset($data['name']) && $data['name'] !== $company->name) {
            $data['slug'] = $this->generateUniqueSlug($data['name'], $company->id);
        }

        return $this->companyRepository->update($company, $data);
    }

    /**
     * Delete a company.
     */
    public function deleteCompany(Company $company): bool
    {
        return $this->companyRepository->delete($company);
    }

    /**
     * Generate a unique slug from a company name,
     * appending -2, -3, etc. if duplicates exist.
     */
    public function generateUniqueSlug(string $name, int $excludeId = null): string
    {
        $baseSlug = Str::slug($name);
        $slug     = $baseSlug;
        $counter  = 2;

        while ($this->companyRepository->slugExists($slug, $excludeId)) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}
