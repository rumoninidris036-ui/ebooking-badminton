<?php

namespace Database\Factories;

use App\Models\Court;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Court>
 */
class CourtFactory extends Factory
{
    protected $model = Court::class;

    public function definition(): array
    {
        $name = fake()->unique()->company().' Court';

        return [
            'owner_id' => User::factory()->owner(),
            'name' => $name,
            'slug' => Str::slug($name).'-'.fake()->unique()->numberBetween(10, 999),
            'description' => fake()->sentence(14),
            'location' => fake()->city().' Sports Hall',
            'price_per_hour' => fake()->numberBetween(70000, 150000),
            'cover_image' => null,
            'rating' => 0,
            'status' => 'active',
            'is_active' => true,
        ];
    }
}
