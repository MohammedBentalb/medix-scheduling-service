<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource {
    public function toArray(Request $request): array {
        return [
            'id' => $this->id,
            'patientId' => $this->patientId,
            'patientName' => $this->patientName,
            'doctorId' => $this->doctorId,
            'doctorName' => $this->doctorName,
            'bookedBy' => $this->bookedBy,
            'appointmentDate'=> $this->appointmentDate->toDateString(),
            'startTime' => $this->startTime,
            'endTime' => $this->endTime,
            'type' => $this->type->value,
            'status' => $this->status->value,
            'notes' => $this->notes,
            'createdAt' => $this->created_at->toIso8601String(),
            'updatedAt' => $this->updated_at->toIso8601String(),
        ];
    }
}
