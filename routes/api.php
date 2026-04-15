<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\ScheduleController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware('internal')->group(function () {
    Route::get('/doctors/{doctorId}/availability', [AvailabilityController::class, 'show']);

    Route::get('/doctors/{doctorId}/appointments', [AppointmentController::class, 'getDoctorAppointments']);
    Route::get('/patients/{patientId}/appointments', [AppointmentController::class, 'getPatientAppointments']);
    Route::get('/appointments/{appointment}', [AppointmentController::class, 'show']);
    Route::post('/appointments', [AppointmentController::class, 'store']);
    Route::patch('/appointments/{appointment}/confirm',[AppointmentController::class, 'confirm']);
    Route::patch('/appointments/{appointment}/cancel', [AppointmentController::class, 'cancel']);
    Route::patch('/appointments/{appointment}/complete', [AppointmentController::class, 'complete']);
    Route::patch('/appointments/{appointment}/no-show', [AppointmentController::class, 'noShow']);

    Route::get('/doctors/{doctorId}/schedule', [ScheduleController::class, 'show']);
    Route::put('/doctors/{doctorId}/schedule', [ScheduleController::class, 'update']);
    Route::put('/doctors/{doctorId}/scheduling-settings', [ScheduleController::class, 'updateSettings']);
    Route::post('/doctors/{doctorId}/availability-toggle', [ScheduleController::class, 'toggleAvailability']);
});