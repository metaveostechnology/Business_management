<?php

namespace App\Http\Requests\CompanyAuth;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCompanyProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $companyId = auth('company')->id();

        return [
            'name'    => 'sometimes|required|string|max:150',
            'phone'   => 'sometimes|required|string|max:20',
            'email'   => 'sometimes|required|email|max:150|unique:companies,email,' . $companyId,
            'address' => 'nullable|string',
            'logo'    => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
            'website' => 'nullable|url|max:200',
        ];
    }
}
