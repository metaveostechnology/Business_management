<?php

namespace App\Http\Requests\Company;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCompanyRequest extends FormRequest
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
        // Resolve the company by route parameter (provided by Route Model Binding)
        $company = $this->route('company');
        $id      = $company?->id;

        return [
            'name'                => ['sometimes', 'required', 'string', 'max:150'],
            'code'                => [
                'sometimes',
                'required',
                'string',
                'max:50',
                Rule::unique('companies', 'code')->ignore($id),
            ],
            'legal_name'          => ['nullable', 'string', 'max:200'],
            'email'               => [
                'nullable',
                'email',
                'max:150',
                Rule::unique('companies', 'email')->ignore($id),
            ],
            'phone'               => ['nullable', 'digits:10'],
            'website'             => ['nullable', 'url', 'max:200'],
            'tax_number'          => ['nullable', 'string', 'max:100'],
            'registration_number' => ['nullable', 'string', 'max:100'],
            'currency_code'       => ['sometimes', 'required', 'string', 'max:10'],
            'timezone'            => ['sometimes', 'required', 'string', 'max:100'],
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
            'code.unique'    => 'This company code is already in use.',
            'email.email'    => 'Please provide a valid email address.',
            'email.unique'   => 'This email address is already registered.',
            'phone.digits'   => 'Phone number must be exactly 10 digits.',
            'website.url'    => 'Please provide a valid website URL.',
        ];
    }
}
