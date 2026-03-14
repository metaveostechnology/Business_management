<?php

namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleRequest extends FormRequest
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
        $roleSlug = $this->route('slug');
        $roleId = \App\Models\Role::where('slug', $roleSlug)->where('company_id', auth()->id())->value('id');

        return [
            'name' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                \Illuminate\Validation\Rule::unique('roles')->where(function ($query) {
                    return $query->where('company_id', auth()->id());
                })->ignore($roleId)
            ],
            'description' => 'nullable|string|max:1000',
            'is_active'   => 'sometimes|boolean',
        ];
    }
}
