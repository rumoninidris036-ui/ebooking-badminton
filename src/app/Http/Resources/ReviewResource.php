<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'rating' => $this->rating,
            'comment' => $this->comment,
            'user' => $this->whenLoaded('user', fn () => [
                'id' => $this->user?->id,
                'name' => $this->user?->name,
            ]),
            'court' => $this->whenLoaded('court', fn () => [
                'id' => $this->court?->id,
                'name' => $this->court?->name,
                'location' => $this->court?->location,
            ]),
            'created_at' => $this->created_at,
        ];
    }
}
