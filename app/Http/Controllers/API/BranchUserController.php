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
                message:   'Employees retrieved successfully.'
            );
        } catch (Throwable $e) {
            Log::error('BranchUser index error', ['error' => $e->getMessage()]);
            return $this->errorResponse('An error occurred while fetching employees.', statusCode: 500);
        }
    }

    /**
     * POST /api/company/branch-users
     */
    public function store(StoreBranchUserRequest $request): JsonResponse
    {
        try {
            $company = auth()->user();
            $companyId = $company->id;

            // Ensure the selected branch belongs to the authenticated company
            $branch = \App\Models\Branch::where('id', $request->integer('branch_id'))
                            ->where('company_id', $companyId)
                            ->first();

            if (!$branch) {
                return $this->errorResponse(
                    'The selected branch does not belong to your company.',
                    statusCode: 403
                );
            }

            // Note: Departments are now global, so we don't need to check company_id for dept_id.
            // Validation is already handled by StoreBranchUserRequest.

            // Generate Employee ID
            $prefix = strtoupper(substr($company->name, 0, 3));
            $lastUser = \App\Models\BranchUser::where('company_id', $companyId)
                        ->orderBy('id', 'desc')
                        ->first();
            
            $nextNumber = $lastUser ? (int)substr($lastUser->emp_id, 4) + 1 : 1;
            $empId = $prefix . '-' . str_pad($nextNumber, 8, '0', STR_PAD_LEFT);

            $data               = $request->validated();
            $data['company_id'] = $companyId;
            $data['created_by'] = $companyId;
            $data['emp_id']     = $empId;

            $branchUser = $this->branchUserService->createBranchUser($data);

            return $this->createdResponse(
                new BranchUserResource($branchUser->load('branch', 'department')),
                'Employee created successfully.'
            );
        } catch (Throwable $e) {
            Log::error('BranchUser store error', ['error' => $e->getMessage()]);
            return $this->errorResponse('An error occurred while creating the employee.', statusCode: 500);
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
                return $this->errorResponse('Employee not found.', statusCode: 404);
            }

            return $this->successResponse(
                new BranchUserResource($branchUser),
                'Employee retrieved successfully.'
            );
        } catch (Throwable $e) {
            Log::error('BranchUser show error', ['slug' => $slug, 'error' => $e->getMessage()]);
            return $this->errorResponse('An error occurred while fetching the employee.', statusCode: 500);
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
                return $this->errorResponse('Employee not found.', statusCode: 404);
            }

            // If branch_id is being updated, verify it belongs to the company
            if ($request->has('branch_id')) {
                $branch = \App\Models\Branch::where('id', $request->integer('branch_id'))
                                ->where('company_id', $companyId)
                                ->first();

                if (!$branch) {
                    return $this->errorResponse('The selected branch does not belong to your company.', statusCode: 403);
                }
            }

            $updated = $this->branchUserService->updateBranchUser($branchUser, $request->validated());

            return $this->successResponse(
                new BranchUserResource($updated),
                'Employee updated successfully.'
            );
        } catch (Throwable $e) {
            Log::error('BranchUser update error', ['slug' => $slug, 'error' => $e->getMessage()]);
            return $this->errorResponse('An error occurred while updating the employee.', statusCode: 500);
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
                return $this->errorResponse('Employee not found.', statusCode: 404);
            }

            $this->branchUserService->deleteBranchUser($branchUser);

            return $this->noContentResponse('Employee deleted successfully.');
        } catch (Throwable $e) {
            Log::error('BranchUser destroy error', ['slug' => $slug, 'error' => $e->getMessage()]);
            return $this->errorResponse('An error occurred while deleting the employee.', statusCode: 500);
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
                return $this->errorResponse('Employee not found.', statusCode: 404);
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
