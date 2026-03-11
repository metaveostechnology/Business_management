<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\SystemSetting\StoreSystemSettingRequest;
use App\Http\Requests\SystemSetting\UpdateSystemSettingRequest;
use App\Http\Resources\SystemSettingResource;
use App\Services\SystemSettingService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class SystemSettingController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        protected SystemSettingService $settingService
    ) {}

    /**
     * GET /api/company/settings?search=&group=
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $settings = $this->settingService->getSettings(
                companyId: auth()->id(),
                search:    $request->query('search'),
                group:     $request->query('group')
            );

            return $this->successResponse(
                data:    SystemSettingResource::collection($settings),
                message: 'Settings retrieved successfully.'
            );
        } catch (Throwable $e) {
            Log::error('SystemSetting index error', ['error' => $e->getMessage()]);
            return $this->errorResponse('An error occurred while fetching settings.', statusCode: 500);
        }
    }

    /**
     * POST /api/company/settings
     */
    public function store(StoreSystemSettingRequest $request): JsonResponse
    {
        try {
            $companyId = auth()->id();
            $validated = $request->validated();

            // Guard: prevent duplicate (company, branch, group, key)
            if ($this->settingService->scopedKeyExists(
                $companyId,
                $validated['branch_id'] ?? null,
                $validated['setting_group'],
                $validated['setting_key']
            )) {
                return $this->errorResponse(
                    'This setting key already exists for the given group and scope.',
                    statusCode: 409
                );
            }

            $validated['company_id'] = $companyId;

            $setting = $this->settingService->createSetting($validated);

            return $this->createdResponse(
                data:    new SystemSettingResource($setting),
                message: 'Setting created successfully.'
            );
        } catch (Throwable $e) {
            Log::error('SystemSetting store error', ['error' => $e->getMessage()]);
            return $this->errorResponse('An error occurred while creating the setting.', statusCode: 500);
        }
    }

    /**
     * GET /api/company/settings/{slug}
     */
    public function show(string $slug): JsonResponse
    {
        try {
            $setting = $this->settingService->findBySlug($slug, auth()->id());

            if (!$setting) {
                return $this->errorResponse('Setting not found.', statusCode: 404);
            }

            return $this->successResponse(
                data:    new SystemSettingResource($setting),
                message: 'Setting retrieved successfully.'
            );
        } catch (Throwable $e) {
            Log::error('SystemSetting show error', ['slug' => $slug, 'error' => $e->getMessage()]);
            return $this->errorResponse('An error occurred while fetching the setting.', statusCode: 500);
        }
    }

    /**
     * PUT /api/company/settings/{slug}
     */
    public function update(UpdateSystemSettingRequest $request, string $slug): JsonResponse
    {
        try {
            $setting = $this->settingService->findBySlug($slug, auth()->id());

            if (!$setting) {
                return $this->errorResponse('Setting not found.', statusCode: 404);
            }

            $updated = $this->settingService->updateSetting($setting, $request->validated());

            return $this->successResponse(
                data:    new SystemSettingResource($updated),
                message: 'Setting updated successfully.'
            );
        } catch (Throwable $e) {
            Log::error('SystemSetting update error', ['slug' => $slug, 'error' => $e->getMessage()]);
            return $this->errorResponse('An error occurred while updating the setting.', statusCode: 500);
        }
    }

    /**
     * DELETE /api/company/settings/{slug}
     */
    public function destroy(string $slug): JsonResponse
    {
        try {
            $setting = $this->settingService->findBySlug($slug, auth()->id());

            if (!$setting) {
                return $this->errorResponse('Setting not found.', statusCode: 404);
            }

            $this->settingService->deleteSetting($setting);

            return $this->noContentResponse('Setting deleted successfully.');
        } catch (Throwable $e) {
            Log::error('SystemSetting destroy error', ['slug' => $slug, 'error' => $e->getMessage()]);
            return $this->errorResponse('An error occurred while deleting the setting.', statusCode: 500);
        }
    }
}
