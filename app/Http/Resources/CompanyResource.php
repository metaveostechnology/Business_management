<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                  => $this->id,
            'slug'                => $this->slug,
            'name'                => $this->name,
            'legal_name'          => $this->legal_name,
            'email'               => $this->email,
            'phone'               => $this->phone,
            'website'             => $this->website,
            'logo'                => $this->logo_path,
            'address'             => $this->address,
            'address_line1'       => $this->address_line1,
            'address_line2'       => $this->address_line2,
            'city'                => $this->city,
            'state'               => $this->state,
            'country'             => $this->country,
            'postal_code'         => $this->postal_code,
            'tax_number'          => $this->tax_number,
            'registration_number' => $this->registration_number,
            'currency_code'       => $this->currency_code,
            'timezone'            => $this->timezone,
            'is_active'           => $this->is_active,
            'is_delete'           => $this->is_delete,
            'created_at'          => $this->created_at?->toDateTimeString(),
            'updated_at'          => $this->updated_at?->toDateTimeString(),
              'code'                => $this->code,
        ];
    }
}
