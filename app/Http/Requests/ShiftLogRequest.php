<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShiftLogRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'date' => 'required|date',
            'morning_in' => 'nullable|date_format:H:i',
            'morning_out' => 'nullable|date_format:H:i|after:morning_in',
            'afternoon_in' => 'nullable|date_format:H:i',
            'afternoon_out' => 'nullable|date_format:H:i|after:afternoon_in',
            'activity_summary' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'is_overtime' => 'boolean',
        ];
    }
}
