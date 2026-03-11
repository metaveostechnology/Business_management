<?php

namespace App\Http\Requests\Company;

use Illuminate\Foundation\Http\FormRequest;

class StoreCompanyRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'name'                => ['required', 'string', 'max:150'],
            'code'                => ['required', 'string', 'max:50', 'unique:companies,code'],
            'legal_name'          => ['nullable', 'string', 'max:200'],
            'email'               => ['required', 'email', 'max:150', 'unique:companies,email'],
            'password'            => ['required', 'string', 'min:8'],
            'phone'               => ['nullable', 'digits:10'],
            'website'             => ['nullable', 'url', 'max:200'],
            'tax_number'          => ['nullable', 'string', 'max:100'],
            'registration_number' => ['nullable', 'string', 'max:100'],
            'currency_code'       => ['required', 'string', 'max:10'],
            'timezone'            => ['required', 'string', 'max:100'],
            'address_line1'       => ['nullable', 'string', 'max:255'],
            'address_line2'       => ['nullable', 'string', 'max:255'],
            'city'                => ['nullable', 'string', 'max:120'],
            'state'               => ['nullable', 'string', 'max:120'],
            'country'             => ['nullable', 'string', 'max:120'],
            'postal_code'         => ['nullable', 'string', 'max:30'],
            'logo'                => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
            'is_active'           => ['boolean'],
        ];
    }

    /**
     * Custom validation error messages.
     */
    public function messages(): array
    {
        return [
            'name.required'         => 'Company name is required.',
            'code.required'         => 'Company code is required.',
            'code.unique'           => 'This company code is already in use.',
            'email.required'        => 'Email address is required for login access.',
            'email.email'           => 'Please provide a valid email address.',
            'email.unique'          => 'This email address is already registered.',
            'password.required'     => 'A password is required to create a login account for the company.',
            'phone.digits'          => 'Phone number must be exactly 10 digits.',
            'website.url'           => 'Please provide a valid website URL.',
            'currency_code.required' => 'Currency code is required.',
            'timezone.required'     => 'Timezone is required.',
        ];
    }
}
