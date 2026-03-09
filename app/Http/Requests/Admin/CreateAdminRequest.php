<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CreateAdminRequest extends FormRequest
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
            'name'                  => ['required', 'string', 'max:150'],
            'email'                 => ['required', 'email', 'max:150', 'unique:admins,email'],
            'phone'                 => ['nullable', 'digits:10'],
            'username'              => ['nullable', 'string', 'max:100', 'unique:admins,username', 'alpha_dash'],
            'password'              => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required', 'string'],
            'status'                => ['required', 'in:active,inactive,blocked'],
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'name.required'          => 'Admin name is required.',
            'email.required'         => 'Email address is required.',
            'email.unique'           => 'This email address is already in use.',
            'username.unique'        => 'This username is already taken.',
            'phone.digits'           => 'Phone number must be exactly 10 digits.',
            'password.required'      => 'Password is required.',
            'password.min'           => 'Password must be at least 8 characters.',
            'password.confirmed'     => 'Password confirmation does not match.',
            'status.required'        => 'Status is required.',
            'status.in'              => 'Status must be one of: active, inactive, blocked.',
        ];
    }
}
