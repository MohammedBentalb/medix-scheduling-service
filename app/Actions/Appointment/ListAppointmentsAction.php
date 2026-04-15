<?php
namespace App\Actions\Appointment;

use App\AppointmentStatusEnum;
use App\Models\Appointment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class ListAppointmentsAction {
    public function forDoctor(string $doctorId, Request $request): LengthAwarePaginator {
        $query = Appointment::forDoctor($doctorId)->latest('appointmentDate');
        $this->applyFilters($query, $request);
        return $query->paginate($request->query('per_page', 15));
    }

    public function forPatient(string $patientId, Request $request): LengthAwarePaginator {
        $query = Appointment::forPatient($patientId)->latest('appointmentDate');
        $this->applyFilters($query, $request);
        return $query->paginate($request->query('per_page', 15));
    }

    private function applyFilters($query, Request $request): void {
        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('patientName', 'ilike', "%$search%")->orWhere('doctorName', 'ilike', "%$search%");
            });
        }

        if ($status = $request->query('status')) $query->where('status', AppointmentStatusEnum::from($status));
        if ($request->query('today')) $query->forToday();
        if ($request->query('upcoming')) $query->forUpcoming();
        if ($request->query('past')) $query->forPast();
    }
}