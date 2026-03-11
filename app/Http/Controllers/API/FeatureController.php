<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Feature\StoreFeatureRequest;
use App\Http\Requests\Feature\UpdateFeatureRequest;
use App\Http\Resources\FeatureResource;
use App\Services\FeatureService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class FeatureController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        protected FeatureService $featureService
    ) {}

    /**
     * GET /api/company/features?search=&is_active=
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $features = $this->featureService->getFeatures(
                search:   $request->query('search'),
                isActive: $request->query('is_active')
            );

            return $this->successResponse(
                data:    FeatureResource::collection($features),
                message: 'Features retrieved successfully.'
            );
        } catch (Throwable $e) {
            Log::error('Feature index error', ['error' => $e->getMessage()]);
            return $this->errorResponse('An error occurred while fetching features.', statusCode: 500);
        }
    }

    /**
     * POST /api/company/features
     */
    public function store(StoreFeatureRequest $request): JsonResponse
    {
        try {
            $feature = $this->featureService->createFeature($request->validated());

            return $this->createdResponse(
                data:    new FeatureResource($feature),
                message: 'Feature created successfully.'
            );
        } catch (Throwable $e) {
            Log::error('Feature store error', ['error' => $e->getMessage()]);
            return $this->errorResponse('An error occurred while creating the feature.', statusCode: 500);
        }
    }

    /**
     * GET /api/company/features/{slug}
     */
    public function show(string $slug): JsonResponse
    {
        try {
            $feature = $this->featureService->findBySlug($slug);

            if (!$feature) {
                return $this->errorResponse('Feature not found.', statusCode: 404);
            }

            return $this->successResponse(
                data:    new FeatureResource($feature),
                message: 'Feature retrieved successfully.'
            );
        } catch (Throwable $e) {
            Log::error('Feature show error', ['slug' => $slug, 'error' => $e->getMessage()]);
            return $this->errorResponse('An error occurred while fetching the feature.', statusCode: 500);
        }
    }

    /**
     * PUT /api/company/features/{slug}
     */
    public function update(UpdateFeatureRequest $request, string $slug): JsonResponse
    {
        try {
            $feature = $this->featureService->findBySlug($slug);

            if (!$feature) {
                return $this->errorResponse('Feature not found.', statusCode: 404);
            }

            $updated = $this->featureService->updateFeature($feature, $request->validated());

            return $this->successResponse(
                data:    new FeatureResource($updated),
                message: 'Feature updated successfully.'
            );
        } catch (Throwable $e) {
            Log::error('Feature update error', ['slug' => $slug, 'error' => $e->getMessage()]);
            return $this->errorResponse('An error occurred while updating the feature.', statusCode: 500);
        }
    }

    /**
     * DELETE /api/company/features/{slug}
     */
    public function destroy(string $slug): JsonResponse
    {
        try {
            $feature = $this->featureService->findBySlug($slug);

            if (!$feature) {
                return $this->errorResponse('Feature not found.', statusCode: 404);
            }

            // System features cannot be deleted
            if ($feature->is_system) {
                return $this->errorResponse(
                    message:    'System features cannot be deleted.',
                    statusCode: 403
                );
            }

            $this->featureService->deleteFeature($feature);

            return $this->noContentResponse('Feature deleted successfully.');
        } catch (Throwable $e) {
            Log::error('Feature destroy error', ['slug' => $slug, 'error' => $e->getMessage()]);
            return $this->errorResponse('An error occurred while deleting the feature.', statusCode: 500);
        }
    }
}
