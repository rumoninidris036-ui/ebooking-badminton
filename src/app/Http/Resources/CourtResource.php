<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourtResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'location' => $this->location,
            'price_per_hour' => $this->price_per_hour,
            'cover_image' => $this->cover_image,
            'rating' => $this->rating,
            'status' => $this->status,
            'is_active' => $this->is_active,
            'booking_count' => $this->whenCounted('bookings'),
            'owner' => $this->whenLoaded('owner', fn () => [
                'id' => $this->owner?->id,
                'name' => $this->owner?->name,
            ]),
            'facilities' => FacilityResource::collection($this->whenLoaded('facilities')),
            'schedules' => CourtScheduleResource::collection($this->whenLoaded('schedules')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
