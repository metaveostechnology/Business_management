<?php

namespace App\Http\Requests\Feature;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFeatureRequest extends FormRequest
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
        // Resolve the current feature id by slug for the unique ignore rule
        $slug = $this->route('slug');
        $featureId = \App\Models\Feature::where('slug', $slug)->value('id') ?? 'NULL';

        return [
            'code' => ['sometimes', 'required', 'string', 'max:80', "unique:features,code,{$featureId}"],
            'name' => ['sometimes', 'required', 'string', 'max:150'],
            'category' => ['sometimes', 'required', 'string', 'max:80'],
            'description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:80'],
            'sort_order' => ['nullable', 'integer'],
            'is_system' => ['boolean'],
            'is_active' => ['boolean'],
        ];
    }

    /**
     * Custom validation error messages.
     */
    public function messages(): array
    {
        return [
            'code.required' => 'Feature code is required.',
            'code.unique' => 'This feature code already exists.',
            'name.required' => 'Feature name is required.',
            'category.required' => 'Feature category is required.',
        ];
    }
}
