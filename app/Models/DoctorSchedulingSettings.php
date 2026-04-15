<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['doctorId', 'bookingWindowDays', 'slotDurationMinutes', 'isAvailable'])]
class DoctorSchedulingSettings extends Model {
    use HasUuids;

    protected $casts = [
        'bookingWindowDays' => 'integer',
        'slotDurationMinutes' => 'integer',
        'isAvailable' => 'boolean',
    ];
}
