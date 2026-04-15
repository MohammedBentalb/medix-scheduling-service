<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingsRequest extends FormRequest {
    public function rules(): array {
        return [
            'slotDurationMinutes' => ['sometimes', 'required', 'integer', 'in:10,15,20,30,45,60'],
            'bookingWindowDays'=> ['sometimes', 'required', 'integer', 'in:30,60'],
        ];
    }
}