<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\StoreCompanyRequest;
use App\Http\Requests\Company\UpdateCompanyRequest;
use App\Http\Resources\CompanyResource;
use App\Services\CompanyService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class CompanyController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        protected CompanyService $companyService
    ) {}

    /**
     * Get paginated list of companies.
     *
     * GET /api/companies?search=&is_active=&per_page=
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $paginator = $this->companyService->getCompanies(
                search: $request->query('search'),
                isActive: $request->query('is_active'),
                perPage: (int) $request->query('per_page', 10)
            );

            return $this->paginatedResponse(
                paginator: $paginator,
                data: CompanyResource::collection($paginator),
                message: 'Companies retrieved successfully.'
            );
        } catch (Throwable $e) {
            Log::error('Company index error', ['error' => $e->getMessage()]);

            return $this->errorResponse(
                message: 'An error occurred while fetching companies.',
                statusCode: 500
            );
        }
    }

    /**
     * Get a single company by slug.
     *
     * GET /api/companies/{slug}
     */
    public function show(string $slug): JsonResponse
    {
        try {
            $company = $this->companyService->findBySlug($slug);

            if (!$company) {
                return $this->errorResponse('Company not found.', statusCode: 404);
            }

            return $this->successResponse(
                data: new CompanyResource($company),
                message: 'Company retrieved successfully.'
            );
        } catch (Throwable $e) {
            Log::error('Company show error', ['slug' => $slug, 'error' => $e->getMessage()]);

            return $this->errorResponse(
                message: 'An error occurred while fetching the company.',
                statusCode: 500
            );
        }
    }

    /**
     * Create a new company (slug auto-generated from name).
     * Accepts multipart/form-data for logo upload.
     *
     * POST /api/companies
     */
    public function store(StoreCompanyRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            if ($request->hasFile('logo')) {
                $data['logo_path'] = $request->file('logo')->store('companylogo', 'public');
            }

            // Remove 'logo' key — model uses logo_path
            unset($data['logo']);

            $company = $this->companyService->createCompany($data);

            return $this->createdResponse(
                data: new CompanyResource($company),
                message: 'Company created successfully.'
            );
        } catch (Throwable $e) {
            Log::error('Company store error', ['error' => $e->getMessage()]);

            return $this->errorResponse(
                message: 'An error occurred while creating the company.',
                statusCode: 500
            );
        }
    }

    /**
     * Update an existing company by slug.
     * Accepts multipart/form-data for logo upload.
     *
     * POST /api/companies/{slug}  (use POST with _method=PUT for file upload in Postman)
     * PUT  /api/companies/{slug}  (JSON-only, no file)
     */
    public function update(UpdateCompanyRequest $request, string $slug): JsonResponse
    {
        try {
            $company = $this->companyService->findBySlug($slug);

            if (!$company) {
                return $this->errorResponse('Company not found.', statusCode: 404);
            }

            $data = $request->validated();

            // Handle logo file upload — delete old logo first
            if ($request->hasFile('logo')) {
                // Delete the previous logo from storage if it exists
                if ($company->logo_path && Storage::disk('public')->exists($company->logo_path)) {
                    Storage::disk('public')->delete($company->logo_path);
                }

                $data['logo_path'] = $request->file('logo')->store('companylogo', 'public');
            }

            // Remove 'logo' key — model uses logo_path
            unset($data['logo']);

            $updatedCompany = $this->companyService->updateCompany($company, $data);

            return $this->successResponse(
                data: new CompanyResource($updatedCompany),
                message: 'Company updated successfully.'
            );
        } catch (Throwable $e) {
            Log::error('Company update error', ['slug' => $slug, 'error' => $e->getMessage()]);

            return $this->errorResponse(
                message: 'An error occurred while updating the company.',
                statusCode: 500
            );
        }
    }

    /**
     * Delete a company by slug.
     * Also removes the associated logo from storage.
     *
     * DELETE /api/companies/{slug}
     */
    public function destroy(string $slug): JsonResponse
    {
        try {
            $company = $this->companyService->findBySlug($slug);

            if (!$company) {
                return $this->errorResponse('Company not found.', statusCode: 404);
            }

            // Delete logo from storage before deleting company
            if ($company->logo_path && Storage::disk('public')->exists($company->logo_path)) {
                Storage::disk('public')->delete($company->logo_path);
            }

            $this->companyService->deleteCompany($company);

            return $this->noContentResponse('Company deleted successfully.');
        } catch (Throwable $e) {
            Log::error('Company destroy error', ['slug' => $slug, 'error' => $e->getMessage()]);

            return $this->errorResponse(
                message: 'An error occurred while deleting the company.',
                statusCode: 500
            );
        }
    }
}
