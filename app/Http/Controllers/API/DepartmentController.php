<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Department\StoreDepartmentRequest;
use App\Http\Requests\Department\UpdateDepartmentRequest;
use App\Http\Resources\DepartmentResource;
use App\Services\DepartmentService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class DepartmentController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        protected DepartmentService $departmentService
    ) {}

    /**
     * GET /api/company/departments?search=&is_active=
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $companyId   = auth()->id();
            $departments = $this->departmentService->getDepartments(
                companyId: $companyId,
                search:    $request->query('search'),
                isActive:  $request->query('is_active')
            );

            return $this->successResponse(
                data:    DepartmentResource::collection($departments),
                message: 'Departments retrieved successfully.'
            );
        } catch (Throwable $e) {
            Log::error('Department index error', ['error' => $e->getMessage()]);
            return $this->errorResponse('An error occurred while fetching departments.', statusCode: 500);
        }
    }

    /**
     * POST /api/company/departments
     */
    public function store(StoreDepartmentRequest $request): JsonResponse
    {
        try {
            $data                = $request->validated();
            $data['company_id'] = auth()->id();
            $data['created_by'] = auth()->id();

            $department = $this->departmentService->createDepartment($data);

            return $this->createdResponse(
                data:    new DepartmentResource($department),
                message: 'Department created successfully.'
            );
        } catch (Throwable $e) {
            Log::error('Department store error', ['error' => $e->getMessage()]);
            return $this->errorResponse('An error occurred while creating the department.', statusCode: 500);
        }
    }

    /**
     * GET /api/company/departments/{slug}
     */
    public function show(string $slug): JsonResponse
    {
        try {
            $department = $this->departmentService->findBySlug($slug, auth()->id());

            if (!$department) {
                return $this->errorResponse('Department not found.', statusCode: 404);
            }

            return $this->successResponse(
                data:    new DepartmentResource($department),
                message: 'Department retrieved successfully.'
            );
        } catch (Throwable $e) {
            Log::error('Department show error', ['slug' => $slug, 'error' => $e->getMessage()]);
            return $this->errorResponse('An error occurred while fetching the department.', statusCode: 500);
        }
    }

    /**
     * PUT /api/company/departments/{slug}
     */
    public function update(UpdateDepartmentRequest $request, string $slug): JsonResponse
    {
        try {
            $department = $this->departmentService->findBySlug($slug, auth()->id());

            if (!$department) {
                return $this->errorResponse('Department not found.', statusCode: 404);
            }

            $updated = $this->departmentService->updateDepartment($department, $request->validated());

            return $this->successResponse(
                data:    new DepartmentResource($updated),
                message: 'Department updated successfully.'
            );
        } catch (Throwable $e) {
            Log::error('Department update error', ['slug' => $slug, 'error' => $e->getMessage()]);
            return $this->errorResponse('An error occurred while updating the department.', statusCode: 500);
        }
    }

    /**
     * DELETE /api/company/departments/{slug}
     */
    public function destroy(string $slug): JsonResponse
    {
        try {
            $department = $this->departmentService->findBySlug($slug, auth()->id());

            if (!$department) {
                return $this->errorResponse('Department not found.', statusCode: 404);
            }

            // System-default departments cannot be deleted
            if ($department->is_system_default) {
                return $this->errorResponse(
                    message:    'System default departments cannot be deleted.',
                    statusCode: 403
                );
            }

            $this->departmentService->deleteDepartment($department);

            return $this->noContentResponse('Department deleted successfully.');
        } catch (Throwable $e) {
            Log::error('Department destroy error', ['slug' => $slug, 'error' => $e->getMessage()]);
            return $this->errorResponse('An error occurred while deleting the department.', statusCode: 500);
        }
    }
}
