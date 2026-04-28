<?php

namespace App\Actions\Events;

use App\Enums\UserTypeEnum;
use App\Models\Appointment;
use App\Models\OutboxEvent;
use Illuminate\Support\Facades\DB;

class DeleteUserAppointmentAction {
    public function __construct() {}

    public function execute(array $payload) {

    DB::transaction(function() use ($payload) {
        $userIdType = UserTypeEnum::PATIENT->value === $payload['role'] ? 'patientId' : 'doctorId';
        $appointments = Appointment::where($userIdType, $payload['user_id'])->get();
        
        foreach ($appointments as $appointment) {
            OutboxEvent::create([
                'topic' => 'appointment.deleted',
                'payload' => ['appointment_id' => $appointment->id],
            ]);
            $appointment->delete();
        }
    }); 
    }
}
