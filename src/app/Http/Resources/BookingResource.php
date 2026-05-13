<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'booking_code' => $this->booking_code,
            'booking_date' => $this->booking_date?->toDateString(),
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'duration_hours' => $this->duration_hours,
            'price_per_hour' => $this->price_per_hour,
            'total_price' => $this->total_price,
            'status' => $this->status,
            'notes' => $this->notes,
            'cancellation' => $this->whenLoaded('cancellation', fn () => [
                'reason' => $this->cancellation?->cancellation_reason,
                'cancelled_at' => $this->cancellation?->cancelled_at,
            ]),
            'court' => $this->whenLoaded('court', fn () => [
                'id' => $this->court?->id,
                'name' => $this->court?->name,
                'location' => $this->court?->location,
            ]),
            'user' => $this->whenLoaded('user', fn () => [
                'id' => $this->user?->id,
                'name' => $this->user?->name,
            ]),
            'created_at' => $this->created_at,
        ];
    }
}
