<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateScheduleRequest extends FormRequest {
    public function rules(): array {
        return [
            'slotDurationMinutes' => ['required', 'integer', 'in:10,15,20,30,45,60'],
            'bookingWindowDays' => ['required', 'integer', 'in:30,60'],
            'templates' => ['required', 'array', 'min:1'],
            'templates.*.dayOfWeek' => ['required', 'integer', 'min:0', 'max:6'],
            'templates.*.startTime' => ['required', 'date_format:H:i'],
            'templates.*.endTime' => ['required', 'date_format:H:i', 'after:templates.*.startTime'],
        ];
    }
}
