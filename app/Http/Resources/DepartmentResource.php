<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DepartmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'company_id' => $this->company_id,
            'branch_id' => $this->branch_id,
            'slug' => $this->slug,
            'parent_department_id' => $this->parent_department_id,
            'code' => $this->code,
            'name' => $this->name,
            'description' => $this->description,
            'head_user_id' => $this->head_user_id,
            'level_no' => $this->level_no,
            'reports_to_department_id' => $this->reports_to_department_id,
            'approval_mode' => $this->approval_mode,
            'escalation_mode' => $this->escalation_mode,
            'can_create_tasks' => (bool)$this->can_create_tasks,
            'can_receive_tasks' => (bool)$this->can_receive_tasks,
            'is_system_default' => (bool)$this->is_system_default,
            'is_active' => (bool)$this->is_active,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
