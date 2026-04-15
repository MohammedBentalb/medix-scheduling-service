<?php
namespace App\Actions\Schedule;

use App\AppointmentStatusEnum;
use App\Models\Appointment;
use App\Models\DoctorSchedulingSettings;
use Illuminate\Support\Facades\DB;

class ToggleAvailabilityAction {
    public function execute(string $doctorId): DoctorSchedulingSettings {
        return DB::transaction(function () use ($doctorId) {
            $settings = DoctorSchedulingSettings::firstOrCreate(['doctorId' => $doctorId], ['bookingWindowDays' => 60, 'slotDurationMinutes' => 20, 'isAvailable' => true]);
            $newState = !$settings->isAvailable;
            if (!$newState) Appointment::where('doctorId', $doctorId)->whereIn('status', [AppointmentStatusEnum::PENDING->value, AppointmentStatusEnum::CONFIRMED->value])->update(['status' => AppointmentStatusEnum::CANCELED->value]);
            $settings->update(['isAvailable' => $newState]);
            return $settings->fresh();
        });
    }
}