<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            "photo" => ["string","required"],
            "role_id" => ["string","required"],
            "permissions_id" => ["string","required"],
            "name" => ["string","required"],
            "email" => ["string","required","email","unique:users,email",],
            "department_id" => ["string","required","exists:departments,id",]
        ];
    }
}
