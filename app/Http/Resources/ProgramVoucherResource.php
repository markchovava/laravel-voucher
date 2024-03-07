<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProgramVoucherResource extends JsonResource
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
            'generated_voucher_id' => $this->generated_voucher_id,
            'points' => $this->points,
            'code' => $this->code,
            'created_at' => $this->created_at->format('d/m/Y'),
            'updated_at' => $this->updated_at->format('d/m/Y'),
            'user' => new UserResource($this->whenLoaded('user')),
            'campaign' => new CampaignResource($this->whenLoaded('campaign')),
            'program' => new ProgramResource($this->whenLoaded('program')),
        ];
    }
}
