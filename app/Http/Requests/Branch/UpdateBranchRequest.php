<?php

namespace App\Http\Requests\Branch;

use App\Models\Branch;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBranchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $branch = Branch::where('slug', $this->route('slug'))
                        ->where('company_id', $this->user()->id)
                        ->first();
                        
        $branchId = $branch ? $branch->id : null;

        return [
            'code'             => [
                'sometimes', 'required', 'string', 'max:50',
                Rule::unique('branches', 'code')->where('company_id', $this->user()->id)->ignore($branchId)
            ],
            'name'             => ['sometimes', 'required', 'string', 'max:150'],
            'email'            => ['nullable', 'email', 'max:150'],
            'phone'            => ['nullable', 'string', 'max:20'],
            'address_line1'    => ['nullable', 'string', 'max:255'],
            'address_line2'    => ['nullable', 'string', 'max:255'],
            'city'             => ['nullable', 'string', 'max:120'],
            'state'            => ['nullable', 'string', 'max:120'],
            'country'          => ['nullable', 'string', 'max:120'],
            'postal_code'      => ['nullable', 'string', 'max:30'],
            'google_map_link'  => ['nullable', 'url', 'max:500'],
            'is_head_office'   => ['sometimes', 'boolean'],
            'is_active'        => ['sometimes', 'boolean'],
        ];
    }
}
