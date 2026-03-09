<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'slug'          => $this->slug,
            'name'          => $this->name,
            'email'         => $this->email,
            'phone'         => $this->phone,
            'username'      => $this->username,
            'status'        => $this->status,
            'last_login_at' => $this->last_login_at?->toDateTimeString(),
            'last_login_ip' => $this->last_login_ip,
            'created_at'    => $this->created_at?->toDateTimeString(),
            'updated_at'    => $this->updated_at?->toDateTimeString(),
            'deleted_at'    => $this->when(
                $this->deleted_at !== null,
                fn() => $this->deleted_at?->toDateTimeString()
            ),
        ];
    }
}
