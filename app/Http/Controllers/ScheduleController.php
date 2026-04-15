<?php
namespace App\Http\Controllers;

use App\Actions\Schedule\ShowScheduleAction;
use App\Actions\Schedule\ToggleAvailabilityAction;
use App\Actions\Schedule\UpdateScheduleAction;
use App\Actions\Schedule\UpdateSettingsAction;
use App\DTOs\UpdateScheduleDTO;
use App\Http\Requests\UpdateScheduleRequest;
use App\Http\Requests\UpdateSettingsRequest;
use App\Http\Resources\AvailabilityTemplateResource;
use App\Http\Resources\SchedulingSettingsResource;

use App\Response\ApiResponse;
use Illuminate\Http\JsonResponse;

class ScheduleController extends Controller {
    public function __construct(private ShowScheduleAction $showScheduleAction , private UpdateScheduleAction $updateScheduleAction, private UpdateSettingsAction $updateSettingsAction, private ToggleAvailabilityAction $toggleAction) {}

    public function show(string $doctorId): JsonResponse {
        [$templates, $settings] = $this->showScheduleAction->execute($doctorId);
        return ApiResponse::success(['settings' => new SchedulingSettingsResource($settings), 'templates' => AvailabilityTemplateResource::collection($templates)]);
    }

    public function update(string $doctorId, UpdateScheduleRequest $request): JsonResponse {
        $result = $this->updateScheduleAction->execute($doctorId, UpdateScheduleDTO::fromRequest($request));
        return ApiResponse::success(['settings' => new SchedulingSettingsResource($result['settings']), 'templates' => AvailabilityTemplateResource::collection($result['templates'])]);
    }

    public function updateSettings(string $doctorId, UpdateSettingsRequest $request): JsonResponse {
        return ApiResponse::success(new SchedulingSettingsResource($this->updateSettingsAction->execute($doctorId, $request->validated())));
    }

    public function toggleAvailability(string $doctorId): JsonResponse {
        return ApiResponse::success(new SchedulingSettingsResource($this->toggleAction->execute($doctorId)));
    }
}