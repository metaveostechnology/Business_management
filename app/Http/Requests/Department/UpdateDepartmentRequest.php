<?php

namespace App\Http\Requests\Department;

use App\Models\Department;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDepartmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $slug = $this->route('slug'); // correct route parameter
        $departmentId = Department::where('slug', $slug)->value('id');

        return [
            'code' => [
                'sometimes',
                'required',
                'string',
                'max:50',
                Rule::unique('departments', 'code')
                    ->where(function ($query) {
                        return $query->where('company_id', auth()->id());
                    })
                    ->ignore($departmentId),
            ],
            'name'                     => ['sometimes', 'required', 'string', 'max:150'],
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