<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SchedulingSettingsResource extends JsonResource {
    public function toArray(Request $request): array {
        return [
            'id' => $this->id,
            'doctorId' => $this->doctorId,
            'slotDurationMinutes' => $this->slotDurationMinutes,
            'bookingWindowDays' => $this->bookingWindowDays,
            'isAvailable' => $this->isAvailable,
            'updatedAt' => $this->updated_at->toIso8601String(),
        ];
    }
}
