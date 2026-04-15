<?php

namespace App\Actions\Appointment;

use App\AppointmentStatusEnum;
use App\Models\Appointment;

class ChangeAppointmentStatusAction {

    public function execute(Appointment $appointment, AppointmentStatusEnum $status): Appointment {
        $appointment->status->canTransitionTo($status);
        $appointment->update(['status' => $status]);
        return $appointment->fresh();
    }
}