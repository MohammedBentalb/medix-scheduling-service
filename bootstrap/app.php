<?php

use App\Exceptions\DoctorNotAvailableException;
use App\Exceptions\InvalidAppointmentTransitionException;
use App\Exceptions\PatientTimeConflictException;
use App\Exceptions\SlotNotAvailableException;
use App\Exceptions\SlotOutsideScheduleException;
use App\Http\Middleware\InternalServiceMiddleware;
use App\Response\ApiResponse;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__.'/../routes/api.php',
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias(['internal' => InternalServiceMiddleware::class]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(fn() => true);

        $exceptions->render(function (PatientTimeConflictException $e) {
            return ApiResponse::error('PATIENT_TIME_CONFLICT', $e->getMessage(), $e->getCode());
        });

        $exceptions->render(function (SlotNotAvailableException $e) {
            return ApiResponse::error('SLOT_NOT_AVAILABLE', $e->getMessage(), $e->getCode());
        });

        $exceptions->render(function (InvalidAppointmentTransitionException $e) {
            return ApiResponse::error('INVALID_APPOINTMENT_TRANSITION', $e->getMessage(), $e->getCode());
        });

        $exceptions->render(function (DoctorNotAvailableException $e) {
            return ApiResponse::error('DOCTOR_NOT_AVAILABLE', $e->getMessage(), $e->getCode());
        });

        $exceptions->render(function (SlotOutsideScheduleException $e) {
            return ApiResponse::error('SLOT_OUTSIDE_SCHEDULE', $e->getMessage(), $e->getCode());
        });
    })->create();
