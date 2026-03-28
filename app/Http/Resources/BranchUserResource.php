<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BranchUserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'company_id'      => $this->company_id,
            'emp_id'          => $this->emp_id,
            'branch'          => [
                'id'   => $this->branch?->id,
                'name' => $this->branch?->name,
                'slug' => $this->branch?->slug,
            ],
            'department'      => [
                'id'   => $this->department?->id,
                'name' => $this->department?->name,
                'slug' => $this->department?->slug,
            ],
            'profile_image' => $this->profile_image
                ? asset('storage/' . $this->profile_image)
                : null,
            'name'            => $this->name,
            'email'           => $this->email,
            'phone'           => $this->phone,
            'slug'            => $this->slug,
            'is_dept_admin'   => $this->is_dept_admin,
            'is_branch_admin' => $this->is_branch_admin,
            'is_active'       => $this->is_active,
            'is_delete'       => $this->is_delete,
            'created_by'      => $this->created_by,
            'created_at'      => $this->created_at?->toDateTimeString(),
            'updated_at'      => $this->updated_at?->toDateTimeString(),
        ];
    }
}
