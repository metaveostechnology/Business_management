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
        $slug = $this->route('slug');
        $branchUserId = \App\Models\BranchUser::where('slug', $slug)->where('company_id', auth()->id())->value('id');

        return [
            'branch_id' => [
                'sometimes',
                'required',
                'integer',
                \Illuminate\Validation\Rule::exists('branches', 'id')->where(function ($query) {
                    return $query->where('company_id', auth()->id());
                }),
            ],
            'role_id' => [
                'sometimes',
                'required',
                'integer',
                \Illuminate\Validation\Rule::exists('roles', 'id')->where(function ($query) {
                    return $query->where('company_id', auth()->id());
                }),
            ],
            'name'      => 'sometimes|required|string|max:255',
            'email'     => [
                'sometimes',
                'required',
                'email',
                'max:255',
                'unique:branch_users,email,' . ($branchUserId ?? 'NULL') . ',id',
            ],
            'phone'     => 'nullable|string|max:20',
            'is_active' => 'sometimes|boolean',
        ];
    }
}
