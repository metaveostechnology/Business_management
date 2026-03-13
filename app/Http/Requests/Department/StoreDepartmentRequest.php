<?php

namespace App\Http\Requests\Department;

use Illuminate\Foundation\Http\FormRequest;

class StoreDepartmentRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'branch_id'                => ['required', 'exists:branches,id'],
            'parent_department_id'     => ['nullable', 'exists:departments,id'],
            'code'                     => ['required', 'string', 'max:50'],
            'name'                     => ['required', 'string', 'max:100'],
            'description'              => ['nullable', 'string', 'max:500'],
            'head_user_id'             => ['nullable', 'exists:branch_users,id'],
            'level_no'                 => ['nullable', 'integer', 'min:1'],
            'reports_to_department_id' => ['nullable', 'exists:departments,id'],
            'approval_mode'            => ['nullable', 'string', 'in:auto,manual'],
            'escalation_mode'          => ['nullable', 'string', 'in:auto,manual'],
            'can_create_tasks'         => ['nullable', 'boolean'],
            'can_receive_tasks'        => ['nullable', 'boolean'],
            'is_system_default'        => ['nullable', 'boolean'],
            'is_active'                => ['nullable', 'boolean'],
        ];
    }
}
