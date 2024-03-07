<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProgramResource extends JsonResource
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
            'campaign_id' => $this->campaign_id,
            'total_points' => $this->total_points,
            'total_quantity' => $this->total_quantity,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'reward_points' => $this->reward_points,
            'reward_name' => $this->reward_name,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user' => new UserResource($this->whenLoaded('user')),
            'campaign' => new CampaignResource($this->whenLoaded('campaign')),
        ];
    }
}
