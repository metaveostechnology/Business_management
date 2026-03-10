<?php

namespace App\Http\Requests\SystemSetting;

use Illuminate\Foundation\Http\FormRequest;

class StoreSystemSettingRequest extends FormRequest
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
            'setting_group' => ['required', 'string', 'max:80'],
            'setting_key' => ['required', 'string', 'max:100'],
            'setting_value' => ['nullable'],
            'value_type' => ['sometimes', 'in:string,integer,float,boolean,json,text'],
            'branch_id' => ['nullable', 'exists:branches,id'],
            'is_public' => ['boolean'],
        ];
    }

    /**
     * Custom validation error messages.
     */
    public function messages(): array
    {
        return [
            'setting_group.required' => 'Setting group is required.',
            'setting_key.required' => 'Setting key is required.',
            'branch_id.exists' => 'The selected branch does not exist.',
            'value_type.in' => 'Value type must be: string, integer, float, boolean, json, or text.',
        ];
    }
}
