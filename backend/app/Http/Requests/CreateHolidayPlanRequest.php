<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateHolidayPlanRequest extends FormRequest
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
            'title' => 'required|string|max:55',
            'description' => 'required|string|max:255',
            'date' => 'required|date_format:Y-m-d',
            'location' => 'required|string|max:55',
            'participants' => 'nullable|array',
            'participants.*' => 'nullable|string|max:30'
        ];
    }
}
