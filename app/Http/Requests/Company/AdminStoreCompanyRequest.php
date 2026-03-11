<?php

namespace App\Http\Requests\Company;

use Illuminate\Foundation\Http\FormRequest;

class AdminStoreCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'                => 'required|string|max:150',
            'email'               => 'required|email|max:150|unique:companies,email',
            'phone'               => 'required|string|max:20',
            'password'            => 'required|string|min:6|confirmed',
            'legal_name'          => 'nullable|string|max:200',
            'website'             => 'nullable|url|max:200',
            'tax_number'          => 'nullable|string|max:100',
            'registration_number' => 'nullable|string|max:100',
            'currency_code'       => 'nullable|string|max:10',
            'timezone'            => 'nullable|string|max:100',
            'address_line1'       => 'nullable|string|max:255',
            'address_line2'       => 'nullable|string|max:255',
            'city'                => 'nullable|string|max:120',
            'state'               => 'nullable|string|max:120',
            'country'             => 'nullable|string|max:120',
            'postal_code'         => 'nullable|string|max:30',
            'address'             => 'nullable|string',
            'logo'                => 'nullable|string|max:255',
            'is_active'           => 'sometimes|boolean',
        ];
    }
}
