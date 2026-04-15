<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['doctorId', 'dayOfWeek', 'startTime', 'endTime', 'isActive'])]
class AvailabilityTemplate extends Model {
    use HasUuids;

    protected $casts = [
        'dayOfWeek' => 'integer',
        'isActive'  => 'boolean',
    ];

    public function scopeActive(Builder $query) {
        return $query->where('isActive', true);
    }
    public function scopeForDay(Builder $query, int $dayOfWeek) {
        return $query->where('dayOfWeek', $dayOfWeek);
    }
}
