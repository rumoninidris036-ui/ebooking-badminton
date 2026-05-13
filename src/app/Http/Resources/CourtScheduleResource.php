<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourtScheduleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'day_of_week' => $this->day_of_week,
            'day_name' => \App\Services\CourtService::DAYS[$this->day_of_week] ?? null,
            'is_open' => $this->is_open,
            'open_time' => $this->open_time,
            'close_time' => $this->close_time,
        ];
    }
}
