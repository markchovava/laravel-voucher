<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CampaignResource extends JsonResource
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
            'user_id' => $this->user_id,
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
            'vouchers_quantity' => $this->vouchers_quantity,
            'points_per_voucher' => $this->points_per_voucher,
            'price_of_voucher' => $this->price_of_voucher,
            'total_cost' => $this->total_cost,
            'reward_name' => $this->reward_name,
            'reward_points' => $this->reward_points,
            'company_name' => $this->company_name,
            'company_email' => $this->company_email,
            'company_phone' => $this->company_phone,
            'company_address' => $this->company_address,
            'company_website' => $this->company_website,
            'end_date' => $this->end_date,
            'start_date' => $this->start_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user' => new UserResource($this->whenLoaded('user')),
        ];
        
    }
}
