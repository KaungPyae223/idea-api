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
                'final_closure_date' => ['required', 'date','after:idea_closure_date'],
                'academic_year' => ['required', 'string','unique:system_settings,academic_year,'.$this->route('system_setting')],

            ];

    }
}
