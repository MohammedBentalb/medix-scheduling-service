<?php
namespace App\DTOs;

use App\AppointmentTypeEnum;
use App\Http\Requests\BookAppointmentRequest;

class BookAppointmentDTO {
    public function __construct(public string $patientId, public string $patientName, public string $doctorId, public string $doctorName, public string $bookedBy, public string $appointmentDate, public string $startTime, public string $endTime, public AppointmentTypeEnum $type, public ?string $notes) {}

    public static function fromRequest(BookAppointmentRequest $request): self {
        $roles  = explode(',', $request->header('X-User-Roles'));
        $userId = $request->header('X-User-Id');

        return new self(
            patientId: $request->validated('patientId'),
            patientName: $request->validated('patientName'),
            doctorId: $request->validated('doctorId'),
            doctorName: $request->validated('doctorName'),
            bookedBy: $userId ?: $request->validated('patientId'),
            appointmentDate: $request->validated('appointmentDate'),
            startTime: $request->validated('startTime'),
            endTime: $request->validated('endTime'),
            type: AppointmentTypeEnum::from($request->validated('type')),
            notes: $request->validated('notes'),
        );
    }
}