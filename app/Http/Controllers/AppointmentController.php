<?php

namespace App\Http\Controllers;

use App\Actions\Appointment\BookAppointmentAction;
use App\Actions\Appointment\ChangeAppointmentStatusAction;
use App\Actions\Appointment\ListAppointmentsAction;
use App\AppointmentStatusEnum;
use App\DTOs\BookAppointmentDTO;
use App\Http\Requests\BookAppointmentRequest;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use App\Response\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppointmentController extends Controller {

    public function __construct(private ListAppointmentsAction $listAction, private BookAppointmentAction $bookAction, private ChangeAppointmentStatusAction $statusAction) {}

    public function getDoctorAppointments(string $doctorId, Request $request): JsonResponse {
        return ApiResponse::appointmentsWithPaginationResponse($this->listAction->forDoctor($doctorId, $request));
    }

    public function getPatientAppointments(string $patientId, Request $request): JsonResponse {
        return ApiResponse::appointmentsWithPaginationResponse($this->listAction->forPatient($patientId, $request));
    }

    public function show(Appointment $appointment): JsonResponse {
        return ApiResponse::success(new AppointmentResource($appointment));
    }

    public function store(BookAppointmentRequest $request): JsonResponse {
        $appointment = $this->bookAction->execute(BookAppointmentDTO::fromRequest($request));
        return ApiResponse::success(new AppointmentResource($appointment), 201);
    }

    public function confirm(Appointment $appointment): JsonResponse {
        return ApiResponse::success(new AppointmentResource($this->statusAction->execute($appointment, AppointmentStatusEnum::CONFIRMED)));
    }

    public function cancel(Appointment $appointment): JsonResponse {
        return ApiResponse::success(new AppointmentResource($this->statusAction->execute($appointment, AppointmentStatusEnum::CANCELED)));
    }

    public function complete(Appointment $appointment): JsonResponse {
        return ApiResponse::success(new AppointmentResource($this->statusAction->execute($appointment, AppointmentStatusEnum::COMPLETED)));
    }

    public function noShow(Appointment $appointment): JsonResponse {
        return ApiResponse::success(new AppointmentResource($this->statusAction->execute($appointment, AppointmentStatusEnum::NO_SHOW)));
    }

    public function getPatientHistory(){
        
    }
}