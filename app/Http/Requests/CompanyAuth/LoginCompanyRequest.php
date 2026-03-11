<?php

namespace App\Http\Requests\CompanyAuth;

use Illuminate\Foundation\Http\FormRequest;

class LoginCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email'    => 'required|email',
            'password' => 'required|string',
        ];
    }
}
