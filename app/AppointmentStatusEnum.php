<?php

namespace App;

use App\Exceptions\InvalidAppointmentTransitionException;

enum AppointmentStatusEnum : string {
    case PENDING = 'PENDING';
    case CONFIRMED = 'CONFIRMED';
    case COMPLETED = 'COMPLETED';
    case CANCELED = 'CANCELED';
    case NO_SHOW = 'NO_SHOW';

    public function canTransitionTo(self $newStatus): void {
        $allowed = match($this) {
            self::PENDING => [self::CONFIRMED, self::CANCELED],
            self::CONFIRMED => [self::COMPLETED, self::CANCELED, self::NO_SHOW],
            default => [],
        };
        if (!in_array($newStatus, $allowed)) throw new InvalidAppointmentTransitionException();
    }

    public function values(): array {
        return array_column(self::cases(), 'value');
    }
}
