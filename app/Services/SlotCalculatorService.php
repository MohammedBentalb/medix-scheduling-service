<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Collection;

class SlotCalculatorService {

    public function calculate( Collection $templates, Collection $bookedSlots, int $slotDuration, Carbon $from, Carbon $to): array {
        $result = [];
        $current = $from->copy()->startOfDay();
        $end = $to->copy()->startOfDay();

        while ($current->lte($end)) {
            $dayOfWeek  = $current->dayOfWeekIso - 1;
            $dateString = $current->toDateString();
            $monthKey = $current->format('Y-m');
            $dayTemplates = $templates->filter(fn($t) => $t->dayOfWeek === $dayOfWeek && $t->isActive);

            if ($dayTemplates->isEmpty()) {
                // $result[$monthKey][$dateString] = [];
                $current->addDay();
                continue;
            }

            $booked = $bookedSlots->get($dateString, collect())->map(fn($a) => substr($a->startTime, 0, 5))->toArray();
            $slots = [];
            foreach ($dayTemplates as $template) {
                $slotStart = Carbon::createFromFormat('H:i:s', $template->startTime);
                $blockEnd = Carbon::createFromFormat('H:i:s', $template->endTime);

                while ($slotStart->copy()->addMinutes($slotDuration)->lte($blockEnd)) {
                    $slotEnd = $slotStart->copy()->addMinutes($slotDuration);
                    $startStr = $slotStart->format('H:i');

                    if (!in_array($startStr, $booked)) {
                        $slots[] = [
                            'start' => $startStr,
                            'end' => $slotEnd->format('H:i'),
                        ];
                    }
                    $slotStart->addMinutes($slotDuration);
                }
            }
            $result[$monthKey][$dateString] = $slots;
            $current->addDay();
        }
        return $result;
    }
}
