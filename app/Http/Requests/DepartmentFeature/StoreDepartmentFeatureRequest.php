<?php

namespace App\Http\Requests\DepartmentFeature;

use Illuminate\Foundation\Http\FormRequest;

class StoreDepartmentFeatureRequest extends FormRequest
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
            'department_id' => ['required', 'exists:departments,id'],
            'feature_id' => ['required', 'exists:features,id'],
            'access_level' => ['sometimes', 'in:view,create,edit,delete,approve,full'],
            'is_enabled' => ['boolean'],
        ];
    }

    /**
     * Custom validation error messages.
     */
    public function messages(): array
    {
        return [
            'department_id.required' => 'Department is required.',
            'department_id.exists' => 'The selected department does not exist.',
            'feature_id.required' => 'Feature is required.',
            'feature_id.exists' => 'The selected feature does not exist.',
            'access_level.in' => 'Access level must be: view, create, edit, delete, approve, or full.',
        ];
    }
}
