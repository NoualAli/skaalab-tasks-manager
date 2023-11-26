<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'full_name' => $this->full_name_with_martial,
            'phone' => $this->phone ?: '-',
            'email' => $this->email,
            'role' => $this->role?->name,
            'role_code' => $this->role?->code,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'last_login' => $this->last_login?->last_activity ?: '-',
        ];
    }
}
