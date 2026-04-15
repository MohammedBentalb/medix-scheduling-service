<?php

use App\Enums\UserTypeEnum;

return [
    ['path' => '/api/v1/doctors/{doctorId}/availability', 'method' => 'GET',   'public' => false, 'permissions' => [], 'roles' => [UserTypeEnum::USER->value]],

    ['path' => '/api/v1/doctors/{doctorId}/appointments', 'method' => 'GET',   'public' => false, 'permissions' => [], 'roles' => [UserTypeEnum::USER->value]],
    ['path' => '/api/v1/patients/{patientId}/appointments', 'method' => 'GET',   'public' => false, 'permissions' => [], 'roles' => [UserTypeEnum::USER->value]],
   
    ['path' => '/api/v1/appointments/{appointment}', 'method' => 'GET',   'public' => false, 'permissions' => [], 'roles' => [UserTypeEnum::USER->value]],
    ['path' => '/api/v1/appointments', 'method' => 'POST',  'public' => false, 'permissions' => [], 'roles' => [UserTypeEnum::USER->value]],
    ['path' => '/api/v1/appointments/{appointment}/confirm', 'method' => 'PATCH', 'public' => false, 'permissions' => [], 'roles' => [UserTypeEnum::ASSISTANT->value, UserTypeEnum::DOCTOR->value]],
    ['path' => '/api/v1/appointments/{appointment}/cancel', 'method' => 'PATCH', 'public' => false, 'permissions' => [], 'roles' => [UserTypeEnum::USER->value]],
    ['path' => '/api/v1/appointments/{appointment}/complete', 'method' => 'PATCH', 'public' => false, 'permissions' => [], 'roles' => [UserTypeEnum::ASSISTANT->value, UserTypeEnum::DOCTOR->value]],
    ['path' => '/api/v1/appointments/{appointment}/no-show', 'method' => 'PATCH', 'public' => false, 'permissions' => [], 'roles' => [UserTypeEnum::ASSISTANT->value, UserTypeEnum::DOCTOR->value]],

    ['path' => '/api/v1/doctors/{doctorId}/schedule', 'method' => 'GET',   'public' => false, 'permissions' => [], 'roles' => [UserTypeEnum::USER->value]],
    ['path' => '/api/v1/doctors/{doctorId}/schedule', 'method' => 'PUT',   'public' => false, 'permissions' => [], 'roles' => [UserTypeEnum::DOCTOR->value]],
    ['path' => '/api/v1/doctors/{doctorId}/scheduling-settings', 'method' => 'PUT', 'public' => false, 'permissions' => [], 'roles' => [UserTypeEnum::DOCTOR->value]],
    ['path' => '/api/v1/doctors/{doctorId}/availability-toggle', 'method' => 'POST',  'public' => false, 'permissions' => [], 'roles' => [UserTypeEnum::DOCTOR->value]],
];
