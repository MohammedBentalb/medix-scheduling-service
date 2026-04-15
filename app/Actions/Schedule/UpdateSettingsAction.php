<?php
namespace App\Actions\Schedule;

use App\Models\DoctorSchedulingSettings;

class UpdateSettingsAction {
    public function execute(string $doctorId, array $data): DoctorSchedulingSettings {
        $settings = DoctorSchedulingSettings::firstOrCreate(['doctorId' => $doctorId], ['bookingWindowDays' => 60, 'slotDurationMinutes' => 20, 'isAvailable' => true]);
        $settings->update(array_filter($data, fn($v) => $v !== null));
        return $settings->fresh();
    }
}
