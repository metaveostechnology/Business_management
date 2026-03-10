<?php

namespace App\Http\Requests\SystemSetting;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSystemSettingRequest extends FormRequest
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
            'setting_value' => ['nullable'],
            'value_type' => ['sometimes', 'in:string,integer,float,boolean,json,text'],
            'is_public' => ['boolean'],
        ];
    }

    /**
     * Custom validation error messages.
     */
    public function messages(): array
    {
        return [
            'value_type.in' => 'Value type must be: string, integer, float, boolean, json, or text.',
        ];
    }
}
