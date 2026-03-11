<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\AdminStoreCompanyRequest;
use App\Http\Requests\Company\AdminUpdateCompanyRequest;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class AdminCompanyController extends Controller
{
    use ApiResponseTrait;

    /**
     * GET /api/admin/companies
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Company::where('is_delete', false);

            if ($search = $request->query('search')) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
                });
            }

            if (!is_null($request->query('is_active')) && $request->query('is_active') !== '') {
                $query->where('is_active', (bool) $request->query('is_active'));
            }

            $paginator = $query->latest()->paginate((int) $request->query('per_page', 10));

            return $this->paginatedResponse(
                paginator: $paginator,
                data:      CompanyResource::collection($paginator),
                message:   'Companies retrieved successfully.'
            );
        } catch (Throwable $e) {
            Log::error('AdminCompany index error', ['error' => $e->getMessage()]);
            return $this->errorResponse('An error occurred while fetching companies.', statusCode: 500);
        }
    }

    /**
     * POST /api/admin/companies
     */
    public function store(AdminStoreCompanyRequest $request): JsonResponse
    {
        try {
            $data             = $request->validated();
            $data['slug']     = $this->generateUniqueSlug($data['name']);
            $data['password'] = Hash::make($data['password']);

            if (isset($data['logo'])) {
                $data['logo_path'] = $data['logo'];
                unset($data['logo']);
            }

            $company = Company::create($data);

            return $this->createdResponse(new CompanyResource($company), 'Company created successfully.');
        } catch (Throwable $e) {
            Log::error('AdminCompany store error', ['error' => $e->getMessage()]);
            return $this->errorResponse('An error occurred while creating the company.', statusCode: 500);
        }
    }

    /**
     * GET /api/admin/companies/{slug}
     */
    public function show(string $slug): JsonResponse
    {
        try {
            $company = Company::where('slug', $slug)->where('is_delete', false)->first();

            if (!$company) {
                return $this->errorResponse('Company not found.', statusCode: 404);
            }

            return $this->successResponse(new CompanyResource($company), 'Company retrieved successfully.');
        } catch (Throwable $e) {
            Log::error('AdminCompany show error', ['slug' => $slug, 'error' => $e->getMessage()]);
            return $this->errorResponse('An error occurred while fetching the company.', statusCode: 500);
        }
    }

    /**
     * PUT /api/admin/companies/{slug}
     */
    public function update(AdminUpdateCompanyRequest $request, string $slug): JsonResponse
    {
        try {
            $company = Company::where('slug', $slug)->where('is_delete', false)->first();

            if (!$company) {
                return $this->errorResponse('Company not found.', statusCode: 404);
            }

            $data = $request->validated();

            // Regenerate slug if company name changes
            if (isset($data['name']) && $data['name'] !== $company->name) {
                $data['slug'] = $this->generateUniqueSlug($data['name'], $company->id);
            }

            // Hash password if being changed
            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }

            if (isset($data['logo'])) {
                $data['logo_path'] = $data['logo'];
                unset($data['logo']);
            }

            $company->update($data);

            return $this->successResponse(new CompanyResource($company->fresh()), 'Company updated successfully.');
        } catch (Throwable $e) {
            Log::error('AdminCompany update error', ['slug' => $slug, 'error' => $e->getMessage()]);
            return $this->errorResponse('An error occurred while updating the company.', statusCode: 500);
        }
    }

    /**
     * DELETE /api/admin/companies/{slug}
     * Soft-deletes by setting is_delete = true and is_active = false.
     */
    public function destroy(string $slug): JsonResponse
    {
        try {
            $company = Company::where('slug', $slug)->where('is_delete', false)->first();

            if (!$company) {
                return $this->errorResponse('Company not found.', statusCode: 404);
            }

            $company->update(['is_delete' => true, 'is_active' => false]);

            return $this->noContentResponse('Company deactivated and deleted successfully.');
        } catch (Throwable $e) {
            Log::error('AdminCompany destroy error', ['slug' => $slug, 'error' => $e->getMessage()]);
            return $this->errorResponse('An error occurred while deleting the company.', statusCode: 500);
        }
    }

    /**
     * Generate a unique slug from the company name.
     */
    private function generateUniqueSlug(string $name, int $excludeId = null): string
    {
        $base    = Str::slug($name);
        $slug    = $base;
        $counter = 2;

        while (
            Company::where('slug', $slug)
                   ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
                   ->exists()
        ) {
            $slug = "{$base}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}
