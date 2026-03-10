<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BranchResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'company_id'      => $this->company_id,
            'code'            => $this->code,
            'name'            => $this->name,
            'slug'            => $this->slug,
            'email'           => $this->email,
            'phone'           => $this->phone,
            'manager_user_id' => $this->manager_user_id,
            'address_line1'   => $this->address_line1,
            'address_line2'   => $this->address_line2,
            'city'            => $this->city,
            'state'           => $this->state,
            'country'         => $this->country,
            'postal_code'     => $this->postal_code,
            'google_map_link' => $this->google_map_link,
            'is_head_office'  => $this->is_head_office,
            'is_active'       => $this->is_active,
            'created_at'      => $this->created_at?->toDateTimeString(),
            'updated_at'      => $this->updated_at?->toDateTimeString(),
        ];
    }
}
