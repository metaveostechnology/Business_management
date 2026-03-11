<?php

namespace App\Http\Requests\BranchUser;

use Illuminate\Foundation\Http\FormRequest;

class ChangeBranchUserPasswordRequest extends FormRequest
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
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'current_password'  => 'nullable|string',
            'new_password'      => 'required|string|min:6',
            'confirm_password'  => 'required|string|same:new_password',
        ];
    }

    /**
     * Custom error messages.
     */
    public function messages(): array
    {
        return [
            'new_password.required'      => 'A new password is required.',
            'new_password.min'           => 'The new password must be at least 6 characters.',
            'confirm_password.required'  => 'Please confirm the new password.',
            'confirm_password.same'      => 'The password confirmation does not match the new password.',
        ];
    }
}
