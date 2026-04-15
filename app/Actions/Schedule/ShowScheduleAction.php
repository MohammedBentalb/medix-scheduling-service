<?php

namespace App\Actions\Schedule;

use App\Models\AvailabilityTemplate;
use App\Models\DoctorSchedulingSettings;

class ShowScheduleAction {
     public function execute(string $doctorId): array {
        $settings  = DoctorSchedulingSettings::firstOrCreate(['doctorId' => $doctorId], ['bookingWindowDays' => 60, 'slotDurationMinutes' => 20, 'isAvailable' => true]);
        $templates = AvailabilityTemplate::where('doctorId', $doctorId)->get();
        return [$templates, $settings];
     }
}
