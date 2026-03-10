<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\DepartmentFeature\StoreDepartmentFeatureRequest;
use App\Http\Requests\DepartmentFeature\UpdateDepartmentFeatureRequest;
use App\Http\Resources\DepartmentFeatureResource;
use App\Models\Department;
use App\Services\DepartmentFeatureService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class DepartmentFeatureController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        protected DepartmentFeatureService $service
    ) {}

    /**
     * GET /api/company/department-features?search=
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $mappings = $this->service->getMappings(
                companyId: auth()->user()->company_id,
                search:    $request->query('search')
            );

            return $this->successResponse(
                data:    DepartmentFeatureResource::collection($mappings),
                message: 'Department features retrieved successfully.'
            );
        } catch (Throwable $e) {
            Log::error('DepartmentFeature index error', ['error' => $e->getMessage()]);
            return $this->errorResponse('An error occurred while fetching department features.', statusCode: 500);
        }
    }

    /**
     * POST /api/company/department-features
     */
    public function store(StoreDepartmentFeatureRequest $request): JsonResponse
    {
        try {
            $companyId    = auth()->user()->company_id;
            $departmentId = $request->integer('department_id');
            $featureId    = $request->integer('feature_id');

            // Ensure the department belongs to the authenticated company
            $department = Department::where('id', $departmentId)
                                    ->where('company_id', $companyId)
                                    ->first();

            if (!$department) {
                return $this->errorResponse(
                    'The selected department does not belong to your company.',
                    statusCode: 403
                );
            }

            // Prevent duplicate (department_id, feature_id) pairs
            if ($this->service->pairExists($departmentId, $featureId)) {
                return $this->errorResponse(
                    'This feature is already assigned to the selected department.',
                    statusCode: 409
                );
            }

            $data                = $request->validated();
            $data['assigned_by'] = auth()->id();

            $mapping = $this->service->createMapping($data);

            return $this->createdResponse(
                data:    new DepartmentFeatureResource($mapping->load('department', 'feature')),
                message: 'Feature assigned to department successfully.'
            );
        } catch (Throwable $e) {
            Log::error('DepartmentFeature store error', ['error' => $e->getMessage()]);
            return $this->errorResponse('An error occurred while assigning the feature.', statusCode: 500);
        }
    }

    /**
     * GET /api/company/department-features/{slug}
     */
    public function show(string $slug): JsonResponse
    {
        try {
            $mapping = $this->service->findBySlug($slug, auth()->user()->company_id);

            if (!$mapping) {
                return $this->errorResponse('Department feature not found.', statusCode: 404);
            }

            return $this->successResponse(
                data:    new DepartmentFeatureResource($mapping),
                message: 'Department feature retrieved successfully.'
            );
        } catch (Throwable $e) {
            Log::error('DepartmentFeature show error', ['slug' => $slug, 'error' => $e->getMessage()]);
            return $this->errorResponse('An error occurred while fetching the department feature.', statusCode: 500);
        }
    }

    /**
     * PUT /api/company/department-features/{slug}
     */
    public function update(UpdateDepartmentFeatureRequest $request, string $slug): JsonResponse
    {
        try {
            $mapping = $this->service->findBySlug($slug, auth()->user()->company_id);

            if (!$mapping) {
                return $this->errorResponse('Department feature not found.', statusCode: 404);
            }

            $updated = $this->service->updateMapping($mapping, $request->validated());

            return $this->successResponse(
                data:    new DepartmentFeatureResource($updated),
                message: 'Department feature updated successfully.'
            );
        } catch (Throwable $e) {
            Log::error('DepartmentFeature update error', ['slug' => $slug, 'error' => $e->getMessage()]);
            return $this->errorResponse('An error occurred while updating the department feature.', statusCode: 500);
        }
    }

    /**
     * DELETE /api/company/department-features/{slug}
     */
    public function destroy(string $slug): JsonResponse
    {
        try {
            $mapping = $this->service->findBySlug($slug, auth()->user()->company_id);

            if (!$mapping) {
                return $this->errorResponse('Department feature not found.', statusCode: 404);
            }

            $this->service->deleteMapping($mapping);

            return $this->noContentResponse('Feature removed from department successfully.');
        } catch (Throwable $e) {
            Log::error('DepartmentFeature destroy error', ['slug' => $slug, 'error' => $e->getMessage()]);
            return $this->errorResponse('An error occurred while removing the department feature.', statusCode: 500);
        }
    }
}