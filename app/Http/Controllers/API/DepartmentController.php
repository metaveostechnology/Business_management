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
            $departments = $this->departmentService->getDepartments(
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

    public function show(string $slug): JsonResponse
    {
        try {
            $department = $this->departmentService->findBySlug($slug);

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
}


}
