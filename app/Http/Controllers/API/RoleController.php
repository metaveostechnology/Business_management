<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Role\StoreRoleRequest;
use App\Http\Requests\Role\UpdateRoleRequest;
use App\Http\Resources\RoleResource;
use App\Services\RoleService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class RoleController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        protected RoleService $roleService
    ) {}

    /**
     * GET /api/company/roles
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $paginator = $this->roleService->getRoles(
                search:   $request->query('search'),
                isActive: $request->query('is_active'),
                perPage:  (int) $request->query('per_page', 10)
            );

            return $this->paginatedResponse(
                paginator: $paginator,
                data:      RoleResource::collection($paginator),
                message:   'Roles retrieved successfully.'
            );
        } catch (Throwable $e) {
            Log::error('Role index error', ['error' => $e->getMessage()]);
            return $this->errorResponse('An error occurred while fetching roles.', statusCode: 500);
        }
    }

    /**
     * POST /api/company/roles
     */
    public function store(StoreRoleRequest $request): JsonResponse
    {
        try {
            $role = $this->roleService->createRole($request->validated());
            return $this->createdResponse(new RoleResource($role), 'Role created successfully.');
        } catch (Throwable $e) {
            Log::error('Role store error', ['error' => $e->getMessage()]);
            return $this->errorResponse('An error occurred while creating the role.', statusCode: 500);
        }
    }

    /**
     * GET /api/company/roles/{slug}
     */
    public function show(string $slug): JsonResponse
    {
        try {
            $role = $this->roleService->findBySlug($slug);
            if (!$role) {
                return $this->errorResponse('Role not found.', statusCode: 404);
            }
            return $this->successResponse(new RoleResource($role), 'Role retrieved successfully.');
        } catch (Throwable $e) {
            Log::error('Role show error', ['slug' => $slug, 'error' => $e->getMessage()]);
            return $this->errorResponse('An error occurred while fetching the role.', statusCode: 500);
        }
    }

    /**
     * PUT /api/company/roles/{slug}
     */
    public function update(UpdateRoleRequest $request, string $slug): JsonResponse
    {
        try {
            $role = $this->roleService->findBySlug($slug);
            if (!$role) {
                return $this->errorResponse('Role not found.', statusCode: 404);
            }
            $updated = $this->roleService->updateRole($role, $request->validated());
            return $this->successResponse(new RoleResource($updated), 'Role updated successfully.');
        } catch (Throwable $e) {
            Log::error('Role update error', ['slug' => $slug, 'error' => $e->getMessage()]);
            return $this->errorResponse('An error occurred while updating the role.', statusCode: 500);
        }
    }

    /**
     * DELETE /api/company/roles/{slug}
     */
    public function destroy(string $slug): JsonResponse
    {
        try {
            $role = $this->roleService->findBySlug($slug);
            if (!$role) {
                return $this->errorResponse('Role not found.', statusCode: 404);
            }
            $this->roleService->deleteRole($role);
            return $this->noContentResponse('Role deleted successfully.');
        } catch (Throwable $e) {
            Log::error('Role destroy error', ['slug' => $slug, 'error' => $e->getMessage()]);
            return $this->errorResponse('An error occurred while deleting the role.', statusCode: 500);
        }
    }
}
