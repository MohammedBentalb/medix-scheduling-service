<?php

namespace App\Http\Controllers;

use App\Actions\Schedule\CalculateAvailabilityAction;
use App\Response\ApiResponse;
use Illuminate\Http\JsonResponse;

class AvailabilityController extends Controller {

    public function __construct(private readonly CalculateAvailabilityAction $action) {}

    public function show(string $doctorId): JsonResponse {
        return ApiResponse::success($this->action->execute($doctorId));
    }
}
