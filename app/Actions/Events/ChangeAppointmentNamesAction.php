<?php

namespace App\Actions\Events;

use App\Enums\UserTypeEnum;
use App\Models\Appointment;

class ChangeAppointmentNamesAction {
    public function __construct(){}
    
    public function execute(array $payload){
        $userName = UserTypeEnum::PATIENT->value === $payload['type'] ? 'patientName' : 'doctorName';
        $userId = UserTypeEnum::PATIENT->value === $payload['type'] ? 'patientId' : 'doctorId';
        Appointment::where($userId, $payload['user_id'])->update([$userName => $payload['first_name']. " " . $payload['last_name']]);
    }
}
