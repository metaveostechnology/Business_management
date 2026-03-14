<?php

namespace App\Http\Requests\BranchUser;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBranchUserRequest extends FormRequest
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
        // Get the branch user's id via slug from route
        $branchUserId = optional($this->route('branch_user_id'))->id;

        return [
            'branch_id'       => 'sometimes|required|integer|exists:branches,id',
            'dept_id'         => 'nullable|integer|exists:departments,id',
            'name'            => 'sometimes|required|string|max:191',
            'email'           => [
                'sometimes',
                'required',
                'email',
                'max:191',
                'unique:branch_users,email,' . ($branchUserId ?? 'NULL') . ',id',
            ],
            'phone'           => 'nullable|string|max:20',
            'is_dept_admin'   => 'sometimes|boolean',
            'is_branch_admin' => 'sometimes|boolean',
            'is_active'       => 'sometimes|boolean',
        ];
    }
}
