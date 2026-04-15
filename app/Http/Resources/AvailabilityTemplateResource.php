<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AvailabilityTemplateResource extends JsonResource {
    private const DAY_NAMES = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

    public function toArray(Request $request): array {
        return [
            'id' => $this->id,
            'dayOfWeek' => $this->dayOfWeek,
            'dayName' => self::DAY_NAMES[$this->dayOfWeek],
            'startTime' => $this->startTime,
            'endTime' => $this->endTime,
            'isActive' => $this->isActive,
        ];
    }
}
