<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'slug'                => $this->slug,
            'code'                => $this->code,
            'name'                => $this->name,
            'legal_name'          => $this->legal_name,
            'email'               => $this->email,
            'phone'               => $this->phone,
            'website'             => $this->website,
            'tax_number'          => $this->tax_number,
            'registration_number' => $this->registration_number,
            'currency_code'       => $this->currency_code,
            'timezone'            => $this->timezone,
            'address_line1'       => $this->address_line1,
            'address_line2'       => $this->address_line2,
            'city'                => $this->city,
            'state'               => $this->state,
            'country'             => $this->country,
            'postal_code'         => $this->postal_code,
            'logo_path'           => $this->logo_path,
            'is_active'           => $this->is_active,
            'created_at'          => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at'          => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
