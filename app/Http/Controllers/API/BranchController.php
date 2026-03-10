<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Branch\StoreBranchRequest;
use App\Http\Requests\Branch\UpdateBranchRequest;
use App\Http\Resources\BranchResource;
use App\Services\BranchService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class BranchController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        protected BranchService $branchService
    ) {}

    public function index(Request $request): JsonResponse
    {
        try {
            $companyId = auth()->user()->company_id;
            $paginator = $this->branchService->getBranches(
                companyId: $companyId,
                search:    $request->query('search'),
                isActive:  $request->query('is_active'),
                perPage:   (int) $request->query('per_page', 10)
            );
            return $this->paginatedResponse(
                paginator: $paginator,
                data:      BranchResource::collection($paginator),
                message:   'Branches retrieved successfully.'
            );
        } catch (Throwable $e) {
            Log::error('Branch index error', ['error' => $e->getMessage()]);
            return $this->errorResponse('An error occurred while fetching branches.', statusCode: 500);
        }
    }

    public function store(StoreBranchRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $data['company_id'] = auth()->user()->company_id;
            $branch = $this->branchService->createBranch($data);
            return $this->createdResponse(new BranchResource($branch), 'Branch created successfully.');
        } catch (Throwable $e) {
            Log::error('Branch store error', ['error' => $e->getMessage()]);
            return $this->errorResponse('An error occurred while creating the branch.', statusCode: 500);
        }
    }

    public function show(string $slug): JsonResponse
    {
        try {
            $branch = $this->branchService->findBySlug($slug, auth()->user()->company_id);
            if (!$branch) { return $this->errorResponse('Branch not found.', statusCode: 404); }
            return $this->successResponse(new BranchResource($branch), 'Branch retrieved successfully.');
        } catch (Throwable $e) {
            Log::error('Branch show error', ['slug' => $slug, 'error' => $e->getMessage()]);
            return $this->errorResponse('An error occurred while fetching the branch.', statusCode: 500);
        }
    }

    public function update(UpdateBranchRequest $request, string $slug): JsonResponse
    {
        try {
            $branch = $this->branchService->findBySlug($slug, auth()->user()->company_id);
            if (!$branch) { return $this->errorResponse('Branch not found.', statusCode: 404); }
            $updated = $this->branchService->updateBranch($branch, $request->validated());
            return $this->successResponse(new BranchResource($updated), 'Branch updated successfully.');
        } catch (Throwable $e) {
            Log::error('Branch update error', ['slug' => $slug, 'error' => $e->getMessage()]);
            return $this->errorResponse('An error occurred while updating the branch.', statusCode: 500);
        }
    }

    public function destroy(string $slug): JsonResponse
    {
        try {
            $branch = $this->branchService->findBySlug($slug, auth()->user()->company_id);
            if (!$branch) { return $this->errorResponse('Branch not found.', statusCode: 404); }
            $this->branchService->deleteBranch($branch);
            return $this->noContentResponse('Branch deleted successfully.');
        } catch (Throwable $e) {
            Log::error('Branch destroy error', ['slug' => $slug, 'error' => $e->getMessage()]);
            return $this->errorResponse('An error occurred while deleting the branch.', statusCode: 500);
        }
    }
}
