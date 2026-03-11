<?php

namespace App\Http\Requests\CompanyAuth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'      => 'required|string|max:150',
            'email'     => 'required|email|max:150|unique:companies,email',
            'phone'     => 'required|string|max:20',
            'password'  => 'required|string|min:6|confirmed',
            'logo'      => 'nullable|string|max:255',
            'address'   => 'nullable|string',
            'website'   => 'nullable|url|max:200',
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique'        => 'A company with this email already exists.',
            'password.confirmed'  => 'Password confirmation does not match.',
        ];
    }
}
