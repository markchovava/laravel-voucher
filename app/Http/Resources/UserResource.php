<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'role_level' => $this->role_level,
            'name' => $this->name,
            'id_number' => $this->id_number,
            'dob' => $this->dob,
            'gender' => $this->gender,
            'phone' => $this->phone,
            'email' => $this->email,
            'code' => $this->code,
            'address' => $this->address,
            'created_at' => $this->created_at->format('d M Y H:i a'),
            'updated_at' => $this->updated_at->format('d M Y H:i a'),
            'role' => new RoleResource($this->whenLoaded('role')),
        ];
        
    }
}
