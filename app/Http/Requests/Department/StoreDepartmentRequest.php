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
            'code'                     => [
                'required', 
                'string', 
                'max:50', 
                \Illuminate\Validation\Rule::unique('departments')->where(function ($query) {
                    return $query->where('company_id', auth()->id());
                })
            ],
            'name'                     => ['required', 'string', 'max:150'],
            'branch_id'                => ['nullable', 'exists:branches,id'],
            'parent_department_id'     => ['nullable', 'exists:departments,id'],
            'reports_to_department_id' => ['nullable', 'exists:departments,id'],
            'level_no'                 => ['nullable', 'integer', 'min:1'],
            'approval_mode'            => ['nullable', 'string', 'in:single,multi,hierarchical'],
            'escalation_mode'          => ['nullable', 'string', 'in:none,manager_to_ceo,full_chain,custom'],
            'can_create_tasks'         => ['nullable', 'boolean'],
            'can_receive_tasks'        => ['nullable', 'boolean'],
            'is_active'                => ['nullable', 'boolean'],
        ];
    }
}
