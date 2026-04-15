<?php
namespace App\DTOs;

use App\Http\Requests\UpdateScheduleRequest;

class UpdateScheduleDTO {
    public function __construct( public int $slotDurationMinutes, public int $bookingWindowDays, public array $templates) {}

    public static function fromRequest(UpdateScheduleRequest $request): self {
        return new self(
            slotDurationMinutes: $request->validated('slotDurationMinutes'),
            bookingWindowDays: $request->validated('bookingWindowDays'),
            templates: $request->validated('templates'),
        );
    }
}