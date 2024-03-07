<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RedeemVoucherResource extends JsonResource
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
            'program_id' => $this->program_id,
            'reward_points' => $this->reward_points,
            'status' => $this->status,
            'code' => $this->code,
            'created_at' => $this->created_at->format('d/m/Y'),
            'updated_at' => $this->updated_at->format('d/m/Y'),
            'campaign' => new CampaignResource($this->whenLoaded('campaign')),
            'program' => new ProgramResource($this->whenLoaded('program')),
            'user' => new UserResource($this->whenLoaded('user')),
        ];
    }



}
