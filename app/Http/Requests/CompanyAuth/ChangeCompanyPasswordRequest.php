<?php

namespace App\Http\Requests\CompanyAuth;

use Illuminate\Foundation\Http\FormRequest;

class ChangeCompanyPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'current_password'          => 'required|string',
            'new_password'              => 'required|string|min:6|confirmed',
            'new_password_confirmation' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'new_password.confirmed' => 'The new password confirmation does not match.',
        ];
    }
}
