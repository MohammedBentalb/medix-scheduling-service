<?php
namespace App\Models;

use App\AppointmentStatusEnum;
use App\AppointmentTypeEnum;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['patientId', 'patientName', 'doctorId', 'doctorName', 'bookedBy', 'appointmentDate', 'startTime', 'endTime', 'type', 'status', 'notes'])]
class Appointment extends Model {
    use HasUuids;

    protected $casts = [
        'appointmentDate' => 'date',
        'type' => AppointmentTypeEnum::class,
        'status' => AppointmentStatusEnum::class,
    ];

    public function scopeOcupying(Builder $query) {
        return $query->whereIn('status', [AppointmentStatusEnum::PENDING, AppointmentStatusEnum::CONFIRMED]);
    }

    public function scopeForDoctor(Builder $query, string $doctorId) {
        return $query->where('doctorId', $doctorId);
    }
    public function scopeForPatient(Builder $query, string $patientId) {
        return $query->where('patientId', $patientId);
    }

    public function scopeForUpcoming(Builder $query) {
        return $query->where('appointmentDate', '>=', now()->toDateString());
    }

    public function scopeForPast(Builder $query) {
        return $query->where('appointmentDate', '<', now()->toDateString());
    }

    public function isPending(): bool {
        return $this->status === AppointmentStatusEnum::PENDING;
    }

    public function isConfirmed(): bool {
        return $this->status === AppointmentStatusEnum::CONFIRMED;
    }

    public function isCancellable(): bool {
        return in_array($this->status, [AppointmentStatusEnum::PENDING, AppointmentStatusEnum::CONFIRMED]);
    }
    public function isCompleteable(): bool {
        return $this->status === AppointmentStatusEnum::CONFIRMED;
    }

    public function scoopForToday(Builder $query){
        return $query->where('appointmentDate', now()->toIso8601String());
    }
}
