<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RecommendationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'similarity_score' => $this->similarity_score,
            'field' => CourtResource::make($this->whenLoaded('court')),
            'created_at' => $this->created_at,
        ];
    }
}
