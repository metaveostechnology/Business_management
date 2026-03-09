<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
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
        $adminId = auth('sanctum')->id();

        return [
            'name'                  => ['sometimes', 'required', 'string', 'max:150'],
            'phone'                 => ['nullable', 'digits:10'],
            'email'                 => [
                'sometimes',
                'required',
                'email',
                'max:150',
                Rule::unique('admins', 'email')->ignore($adminId)->whereNull('deleted_at'),
            ],
            'username'              => [
                'nullable',
                'string',
                'max:100',
                'alpha_dash',
                Rule::unique('admins', 'username')->ignore($adminId)->whereNull('deleted_at'),
            ],
            'current_password'      => ['required_with:password', 'string'],
            'password'              => ['nullable', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['nullable', 'string'],
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'email.unique'                   => 'This email address is already in use.',
            'username.unique'                => 'This username is already taken.',
            'phone.digits'                   => 'Phone number must be exactly 10 digits.',
            'current_password.required_with' => 'Current password is required when changing password.',
            'password.min'                   => 'New password must be at least 8 characters.',
            'password.confirmed'             => 'Password confirmation does not match.',
        ];
    }
}
