<?php

namespace App\Http\Requests\Court;

use App\Services\CourtService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCourtRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'location' => ['required', 'string', 'max:255'],
            'price_per_hour' => ['required', 'numeric', 'min:0'],
            'cover_image' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', Rule::in(['active', 'inactive'])],
            'is_active' => ['nullable', 'boolean'],
            'facility_ids' => ['nullable', 'array'],
            'facility_ids.*' => ['integer', 'exists:facilities,id'],
            'schedules' => ['required', 'array', 'size:7'],
            'schedules.*.day_of_week' => ['required', 'integer', Rule::in(array_keys(CourtService::DAYS))],
            'schedules.*.is_open' => ['nullable', 'boolean'],
            'schedules.*.open_time' => ['nullable', 'date_format:H:i'],
            'schedules.*.close_time' => ['nullable', 'date_format:H:i'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $schedules = collect($this->input('schedules', []))
            ->map(function ($schedule, $index) {
                return [
                    'day_of_week' => $schedule['day_of_week'] ?? $index + 1,
                    'is_open' => filter_var($schedule['is_open'] ?? false, FILTER_VALIDATE_BOOL),
                    'open_time' => $schedule['open_time'] ?? null,
                    'close_time' => $schedule['close_time'] ?? null,
                ];
            })
            ->values()
            ->all();

        $this->merge([
            'is_active' => $this->boolean('is_active'),
            'status' => $this->input('status', $this->boolean('is_active', true) ? 'active' : 'inactive'),
            'facility_ids' => collect($this->input('facility_ids', []))->filter()->map(fn ($id) => (int) $id)->values()->all(),
            'schedules' => $schedules,
        ]);
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            foreach ($this->input('schedules', []) as $index => $schedule) {
                if (($schedule['is_open'] ?? false) && (! $schedule['open_time'] || ! $schedule['close_time'])) {
                    $validator->errors()->add("schedules.$index.open_time", 'Open and close times are required for active days.');
                }

                if (
                    ($schedule['is_open'] ?? false)
                    && ($schedule['open_time'] ?? null)
                    && ($schedule['close_time'] ?? null)
                    && $schedule['close_time'] <= $schedule['open_time']
                ) {
                    $validator->errors()->add("schedules.$index.close_time", 'Close time must be later than open time.');
                }
            }
        });
    }
}
