<?php
namespace App\Actions\Schedule;

use App\AppointmentStatusEnum;
use App\Models\Appointment;
use App\Models\AvailabilityTemplate;
use App\Models\DoctorSchedulingSettings;
use App\Services\SlotCalculatorService;
use Carbon\Carbon;

class CalculateAvailabilityAction {
    public function __construct(private readonly SlotCalculatorService $calculator) {}

    public function execute(string $doctorId): array {
        $settings = DoctorSchedulingSettings::firstOrCreate(['doctorId' => $doctorId], ['bookingWindowDays' => 60, 'slotDurationMinutes' => 20, 'isAvailable' => true]);
        if (!$settings->isAvailable) return [];

        $from = Carbon::today();
        $to = Carbon::today()->addDays($settings->bookingWindowDays);
        $templates = AvailabilityTemplate::where('doctorId', $doctorId)->where('isActive', true)->get();
        $booked = Appointment::where('doctorId', $doctorId)->whereIn('status', [AppointmentStatusEnum::PENDING, AppointmentStatusEnum::CONFIRMED])
            ->whereBetween('appointmentDate', [$from->toDateString(), $to->toDateString()])->get(['appointmentDate', 'startTime'])
            ->groupBy(fn($a) => $a->appointmentDate->toDateString());
        return $this->calculator->calculate($templates, $booked, $settings->slotDurationMinutes, $from, $to);
    }
}
