<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateAdminRequest;
use App\Http\Requests\Admin\UpdateAdminRequest;
use App\Http\Resources\AdminResource;
use App\Services\AdminService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class AdminController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        protected AdminService $adminService
    ) {}

    /**
     * Get paginated list of admins.
     *
     * GET /api/admins?search=&status=&per_page=
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $paginator = $this->adminService->getAdmins(
                search: $request->query('search'),
                status: $request->query('status'),
                perPage: (int) $request->query('per_page', 10)
            );

            return $this->paginatedResponse(
                paginator: $paginator,
                data: AdminResource::collection($paginator),
                message: 'Admins retrieved successfully.'
            );
        } catch (Throwable $e) {
            Log::error('Admin index error', ['error' => $e->getMessage()]);

            return $this->errorResponse(
                message: 'An error occurred while fetching admins.',
                statusCode: 500
            );
        }
    }

    /**
     * Get a single admin by slug.
     *
     * GET /api/admins/{slug}
     */
    public function show(string $slug): JsonResponse
    {
        try {
            $admin = $this->adminService->findBySlug($slug);

            if (!$admin) {
                return $this->errorResponse('Admin not found.', statusCode: 404);
            }

            return $this->successResponse(
                data: new AdminResource($admin),
                message: 'Admin retrieved successfully.'
            );
        } catch (Throwable $e) {
            Log::error('Admin show error', ['slug' => $slug, 'error' => $e->getMessage()]);

            return $this->errorResponse(
                message: 'An error occurred while fetching the admin.',
                statusCode: 500
            );
        }
    }

    /**
     * Create a new admin.
     *
     * POST /api/admins
     */
    public function store(CreateAdminRequest $request): JsonResponse
    {
        try {
            $admin = $this->adminService->createAdmin($request->validated());

            return $this->createdResponse(
                data: new AdminResource($admin),
                message: 'Admin created successfully.'
            );
        } catch (Throwable $e) {
            Log::error('Admin store error', ['error' => $e->getMessage()]);

            return $this->errorResponse(
                message: 'An error occurred while creating the admin.',
                statusCode: 500
            );
        }
    }

    /**
     * Update an existing admin by slug.
     *
     * PUT /api/admins/{slug}
     */
    public function update(UpdateAdminRequest $request, string $slug): JsonResponse
    {
        try {
            $admin = $this->adminService->findBySlug($slug);

            if (!$admin) {
                return $this->errorResponse('Admin not found.', statusCode: 404);
            }

            $data         = $request->validated();
            $updatedAdmin = $this->adminService->updateAdmin($admin, $data);

            return $this->successResponse(
                data: new AdminResource($updatedAdmin),
                message: 'Admin updated successfully.'
            );
        } catch (Throwable $e) {
            Log::error('Admin update error', ['slug' => $slug, 'error' => $e->getMessage()]);

            return $this->errorResponse(
                message: 'An error occurred while updating the admin.',
                statusCode: 500
            );
        }
    }

    /**
     * Soft-delete an admin by slug.
     * Prevents deleting own account.
     *
     * DELETE /api/admins/{slug}
     */
    public function destroy(Request $request, string $slug): JsonResponse
    {
        try {
            $admin = $this->adminService->findBySlug($slug);

            if (!$admin) {
                return $this->errorResponse('Admin not found.', statusCode: 404);
            }

            // Prevent self-delete
            if ($admin->id === $request->user()->id) {
                return $this->errorResponse(
                    message: 'You cannot delete your own account.',
                    statusCode: 403
                );
            }

            $this->adminService->deleteAdmin($admin);

            return $this->noContentResponse('Admin deleted successfully.');
        } catch (Throwable $e) {
            Log::error('Admin destroy error', ['slug' => $slug, 'error' => $e->getMessage()]);

            return $this->errorResponse(
                message: 'An error occurred while deleting the admin.',
                statusCode: 500
            );
        }
    }

    /**
     * Restore a soft-deleted admin by slug.
     *
     * POST /api/admins/{slug}/restore
     */
    public function restore(string $slug): JsonResponse
    {
        try {
            $admin = $this->adminService->restoreAdmin($slug);

            if (!$admin) {
                return $this->errorResponse(
                    message: 'Admin not found or is not deleted.',
                    statusCode: 404
                );
            }

            return $this->successResponse(
                data: new AdminResource($admin),
                message: 'Admin restored successfully.'
            );
        } catch (Throwable $e) {
            Log::error('Admin restore error', ['slug' => $slug, 'error' => $e->getMessage()]);

            return $this->errorResponse(
                message: 'An error occurred while restoring the admin.',
                statusCode: 500
            );
        }
    }
}
