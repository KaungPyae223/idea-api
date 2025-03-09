<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSystemSettingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true ;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

            return [
                'idea_closure_date' => ['required', 'date' ],
                'final_closure_date' => ['required', 'date'],
                'academic_year' => ['required', 'date'],
                'status' => ['required', 'boolean'], // boolean allow 0-1 numbers.
            ];

    }
}
