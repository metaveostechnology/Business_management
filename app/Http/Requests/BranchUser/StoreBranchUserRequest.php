<?php

namespace App\Http\Requests\BranchUser;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreBranchUserRequest extends FormRequest
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
            'branch_id' => [
                'required',
                'integer',
                \Illuminate\Validation\Rule::exists('branches', 'id')->where(function ($query) {
                    return $query->where('company_id', auth()->id());
                }),
            ],
            'role_id' => [
                'required',
                'integer',
                \Illuminate\Validation\Rule::exists('roles', 'id')->where(function ($query) {
                    return $query->where('company_id', auth()->id());
                }),
            ],
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|max:255|unique:branch_users,email',
            'password'  => ['required', 'string', 'min:6', 'confirmed'],
            'phone'     => 'nullable|string|max:20',
            'is_active' => 'sometimes|boolean',
        ];
    }

    /**
     * Custom error messages.
     */
    public function messages(): array
    {
        return [
            'branch_id.exists' => 'The selected branch does not exist.',
            'role_id.exists'   => 'The selected role does not exist.',
            'email.unique'     => 'A branch user with this email already exists.',
            'password.confirmed' => 'Password confirmation does not match.',
        ];
    }
}
