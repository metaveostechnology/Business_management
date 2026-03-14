<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\BranchUser\ChangeBranchUserPasswordRequest;
use App\Http\Requests\BranchUser\StoreBranchUserRequest;
use App\Http\Requests\BranchUser\UpdateBranchUserRequest;
use App\Http\Resources\BranchUserResource;
use App\Models\Branch;
use App\Services\BranchUserService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class BranchUserController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        protected BranchUserService $branchUserService
    ) {}

    /**
     * GET /api/company/branch-users
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $companyId = auth()->id();
            $paginator = $this->branchUserService->getBranchUsers(
                companyId: $companyId,
                search:    $request->query('search'),
                isActive:  $request->query('is_active'),
                perPage:   (int) $request->query('per_page', 10)
            );

            return $this->paginatedResponse(
                paginator: $paginator,
                data:      BranchUserResource::collection($paginator),
                message:   'Branch users retrieved successfully.'
            );
        } catch (Throwable $e) {
            Log::error('BranchUser index error', ['error' => $e->getMessage()]);
            return $this->errorResponse('An error occurred while fetching branch users.', statusCode: 500);
        }
    }

    /**
     * POST /api/company/branch-users
     */
    public function store(StoreBranchUserRequest $request): JsonResponse
    {
        try {
            $companyId = auth()->id();

            // Ensure the selected branch and role belong to the authenticated company
            $branch = \App\Models\Branch::where('id', $request->integer('branch_id'))
                            ->where('company_id', $companyId)
                            ->first();

            $role = \App\Models\Role::where('id', $request->integer('role_id'))
                            ->where('company_id', $companyId)
                            ->first();

            if (!$branch || !$role) {
                return $this->errorResponse(
                    'The selected branch or role does not belong to your company.',
                    statusCode: 403
                );
            }

            $data               = $request->validated();
            $data['company_id'] = $companyId;
            $data['created_by'] = auth()->id();

            $branchUser = $this->branchUserService->createBranchUser($data);

            return $this->createdResponse(
                new BranchUserResource($branchUser->load('branch', 'role')),
                'Branch user created successfully.'
            );
        } catch (Throwable $e) {
            Log::error('BranchUser store error', ['error' => $e->getMessage()]);
            return $this->errorResponse('An error occurred while creating the branch user.', statusCode: 500);
        }
    }

    /**
     * GET /api/company/branch-users/{slug}
     */
    public function show(string $slug): JsonResponse
    {
        try {
            $branchUser = $this->branchUserService->findBySlug($slug, auth()->id());

            if (!$branchUser) {
                return $this->errorResponse('Branch user not found.', statusCode: 404);
            }

            return $this->successResponse(
                new BranchUserResource($branchUser),
                'Branch user retrieved successfully.'
            );
        } catch (Throwable $e) {
            Log::error('BranchUser show error', ['slug' => $slug, 'error' => $e->getMessage()]);
            return $this->errorResponse('An error occurred while fetching the branch user.', statusCode: 500);
        }
    }

    /**
     * PUT /api/company/branch-users/{slug}
     */
    public function update(UpdateBranchUserRequest $request, string $slug): JsonResponse
    {
        try {
            $companyId  = auth()->id();
            $branchUser = $this->branchUserService->findBySlug($slug, $companyId);

            if (!$branchUser) {
                return $this->errorResponse('Branch user not found.', statusCode: 404);
            }

            // If branch_id or role_id is being updated, verify it belongs to the company
            if ($request->has('branch_id')) {
                $branch = \App\Models\Branch::where('id', $request->integer('branch_id'))
                                ->where('company_id', $companyId)
                                ->first();

                if (!$branch) {
                    return $this->errorResponse('The selected branch does not belong to your company.', statusCode: 403);
                }
            }

            if ($request->has('role_id')) {
                $role = \App\Models\Role::where('id', $request->integer('role_id'))
                                ->where('company_id', $companyId)
                                ->first();

                if (!$role) {
                    return $this->errorResponse('The selected role does not belong to your company.', statusCode: 403);
                }
            }

            $updated = $this->branchUserService->updateBranchUser($branchUser, $request->validated());

            return $this->successResponse(
                new BranchUserResource($updated),
                'Branch user updated successfully.'
            );
        } catch (Throwable $e) {
            Log::error('BranchUser update error', ['slug' => $slug, 'error' => $e->getMessage()]);
            return $this->errorResponse('An error occurred while updating the branch user.', statusCode: 500);
        }
    }

    /**
     * DELETE /api/company/branch-users/{slug}
     */
    public function destroy(string $slug): JsonResponse
    {
        try {
            $branchUser = $this->branchUserService->findBySlug($slug, auth()->id());

            if (!$branchUser) {
                return $this->errorResponse('Branch user not found.', statusCode: 404);
            }

            $this->branchUserService->deleteBranchUser($branchUser);

            return $this->noContentResponse('Branch user deleted successfully.');
        } catch (Throwable $e) {
            Log::error('BranchUser destroy error', ['slug' => $slug, 'error' => $e->getMessage()]);
            return $this->errorResponse('An error occurred while deleting the branch user.', statusCode: 500);
        }
    }

    /**
     * POST /api/company/branch-users/{slug}/change-password
     */
    public function changePassword(ChangeBranchUserPasswordRequest $request, string $slug): JsonResponse
    {
        try {
            $branchUser = $this->branchUserService->findBySlug($slug, auth()->id());

            if (!$branchUser) {
                return $this->errorResponse('Branch user not found.', statusCode: 404);
            }

            $this->branchUserService->changePassword($branchUser, $request->validated());

            return $this->successResponse(null, 'Password updated successfully.');
        } catch (\Exception $e) {
            // Catch specific password mismatch (code 422) vs. general errors
            if ($e->getCode() === 422) {
                return $this->errorResponse($e->getMessage(), statusCode: 422);
            }
            Log::error('BranchUser changePassword error', ['slug' => $slug, 'error' => $e->getMessage()]);
            return $this->errorResponse('An error occurred while changing the password.', statusCode: 500);
        }
    }
}
