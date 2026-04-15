<?php
namespace App\Actions\Appointment;

use App\AppointmentStatusEnum;
use App\DTOs\BookAppointmentDTO;
use App\Enums\OutboxStatusEnum;
use App\Exceptions\DoctorNotAvailableException;
use App\Exceptions\PatientTimeConflictException;
use App\Exceptions\SlotNotAvailableException;
use App\Exceptions\SlotOutsideScheduleException;
use App\Models\Appointment;
use App\Models\AvailabilityTemplate;
use App\Models\DoctorSchedulingSettings;
use App\Models\OutboxEvent;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class BookAppointmentAction {
    public function execute(BookAppointmentDTO $dto): Appointment {
        try {
            $settings = DoctorSchedulingSettings::firstOrCreate(['doctorId' => $dto->doctorId], ['bookingWindowDays' => 60, 'slotDurationMinutes' => 20, 'isAvailable' => true]);
            if (!$settings->isAvailable) throw new DoctorNotAvailableException();

            $this->validateSlot($dto, $settings->slotDurationMinutes);

            $conflict = Appointment::where('patientId', $dto->patientId)->where('appointmentDate', $dto->appointmentDate)->whereIn('status', [AppointmentStatusEnum::PENDING, AppointmentStatusEnum::CONFIRMED])->where('startTime', '<', $dto->endTime)->where('endTime', '>', $dto->startTime)->exists();
            if ($conflict) throw new PatientTimeConflictException();
            
            return DB::transaction(function() use ($dto){
                $appointment = Appointment::create([
                        'patientId' => $dto->patientId,
                        'patientName' => $dto->patientName,
                        'doctorId' => $dto->doctorId,
                        'doctorName' => $dto->doctorName,
                        'bookedBy' => $dto->bookedBy,
                        'appointmentDate' => $dto->appointmentDate,
                        'startTime' => $dto->startTime,
                        'endTime' => $dto->endTime,
                        'type' => $dto->type,
                        'status' => AppointmentStatusEnum::PENDING,
                        'notes' => $dto->notes,
                    ]);
                OutboxEvent::create([
                    'topic' => 'appointment.booked',
                    'payload' => [
                        'doctorId' => $dto->doctorId,
                        'patientId' => $dto->patientId,
                    ],
                    'status' => OutboxStatusEnum::PENDING,
                    'attempts' => 0
                ]);

                return $appointment;
            });
            
        } catch (QueryException $e) {
            if ($e->getCode() === '23505') throw new SlotNotAvailableException();
            throw $e;
        }
    }

    private function validateSlot(BookAppointmentDTO $dto, int $slotDurationMinutes){
        $start = Carbon::createFromFormat("H:i", $dto->startTime);
        $end = Carbon::createFromFormat("H:i", $dto->endTime);
        $dayOfWeek = Carbon::parse($dto->appointmentDate)->dayOfWeekIso - 1;

        if ((int) $start->diffInMinutes($end) !== (int) $slotDurationMinutes) throw new SlotOutsideScheduleException();

        $templateExists = AvailabilityTemplate::where('doctorId', $dto->doctorId)->where('dayOfWeek', $dayOfWeek)->where('isActive', true)->where('startTime', '<=', $dto->startTime)->where('endTime', '>=', $dto->endTime)->exists();
        if (!$templateExists) throw new SlotOutsideScheduleException();
    }

}
