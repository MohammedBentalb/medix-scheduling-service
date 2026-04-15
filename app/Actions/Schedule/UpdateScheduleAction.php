<?php
namespace App\Actions\Schedule;

use App\DTOs\UpdateScheduleDTO;
use App\Models\AvailabilityTemplate;
use App\Models\DoctorSchedulingSettings;
use Illuminate\Support\Facades\DB;

class UpdateScheduleAction {
    public function execute(string $doctorId, UpdateScheduleDTO $dto): array {
        return DB::transaction(function () use ($doctorId, $dto) {
            $settings = DoctorSchedulingSettings::updateOrCreate(['doctorId' => $doctorId], ['slotDurationMinutes' => $dto->slotDurationMinutes, 'bookingWindowDays' => $dto->bookingWindowDays]);
            AvailabilityTemplate::where('doctorId', $doctorId)->delete();
            foreach ($dto->templates as $template) {
                AvailabilityTemplate::create([
                    'doctorId' => $doctorId,
                    'dayOfWeek' => $template['dayOfWeek'],
                    'startTime' => $template['startTime'],
                    'endTime' => $template['endTime'],
                    'isActive' => true,
                ]);
            }
            return ['settings' => $settings->fresh(), 'templates' => AvailabilityTemplate::where('doctorId', $doctorId)->get()];
        });
    }
}