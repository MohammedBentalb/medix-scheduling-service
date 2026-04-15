<?php
namespace App\Http\Requests;

use App\AppointmentTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BookAppointmentRequest extends FormRequest {
    public function rules(): array {
        return [
            'doctorId' => ['required', 'string'],
            'doctorName' => ['required', 'string', 'max:100'],
            'patientId' => ['sometimes', 'required', 'string'],
            'patientName' => ['sometimes', 'required', 'string', 'max:100'],
            'appointmentDate' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
            'startTime' => ['required', 'date_format:H:i'],
            'endTime' => ['required', 'date_format:H:i'],
            'type' => ['required', 'string', Rule::in(AppointmentTypeEnum::values())],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
