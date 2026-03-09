<?php

namespace App\Http\Requests\Admin;

use App\Models\Admin;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAdminRequest extends FormRequest
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
        // Resolve admin via route slug (route param is {slug}, not {admin})
        $slug  = $this->route('slug');
        $admin = Admin::where('slug', $slug)->first();

        return [
            'name'     => ['sometimes', 'string', 'max:150'],
            'email'    => [
                'sometimes',
                'email',
                'max:150',
                Rule::unique('admins', 'email')->ignore($admin?->id)->whereNull('deleted_at'),
            ],
            'phone'    => ['nullable', 'digits:10'],
            'username' => [
                'sometimes',
                'nullable',
                'string',
                'max:100',
                'alpha_dash',
                Rule::unique('admins', 'username')->ignore($admin?->id)->whereNull('deleted_at'),
            ],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'status'   => ['sometimes', 'in:active,inactive,blocked'],
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'email.email'           => 'Please provide a valid email address.',
            'email.unique'          => 'This email address is already in use.',
            'username.unique'       => 'This username is already taken.',
            'username.alpha_dash'   => 'Username may only contain letters, numbers, dashes and underscores.',
            'phone.digits'          => 'Phone number must be exactly 10 digits.',
            'password.min'          => 'Password must be at least 8 characters.',
            'password.confirmed'    => 'Password confirmation does not match.',
            'status.in'             => 'Status must be one of: active, inactive, blocked.',
        ];
    }
}
