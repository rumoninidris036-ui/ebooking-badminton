<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Court;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Booking>
 */
class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition(): array
    {
        $date = fake()->dateTimeBetween('+1 day', '+30 days');

        return [
            'booking_code' => 'BK-'.fake()->unique()->numerify('######'),
            'user_id' => User::factory(),
            'court_id' => Court::factory(),
            'schedule_id' => null,
            'booking_date' => $date->format('Y-m-d'),
            'start_time' => '18:00',
            'end_time' => '19:00',
            'duration_hours' => 1,
            'price_per_hour' => 90000,
            'total_price' => 90000,
            'status' => 'pending',
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
