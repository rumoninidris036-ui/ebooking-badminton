<?php

namespace Tests\Feature\Booking;

use App\Models\Court;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class BookingFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_fetch_slot_availability(): void
    {
        $court = $this->createCourtWithSchedule();

        $response = $this->getJson('/api/bookings/availability?court_id='.$court->id.'&date='.now()->addDay()->toDateString());

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'court',
                    'date',
                    'slots',
                ],
            ]);
    }

    public function test_customer_can_create_booking_via_api(): void
    {
        $court = $this->createCourtWithSchedule();
        $customer = User::factory()->create();
        Sanctum::actingAs($customer);

        $response = $this->postJson('/api/bookings', [
            'court_id' => $court->id,
            'booking_date' => now()->addDay()->toDateString(),
            'start_time' => '18:00',
            'duration_hours' => 1,
            'notes' => 'Evening session',
        ]);

        $response->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.total_price', (string) $court->price_per_hour);

        $this->assertDatabaseHas('bookings', [
            'court_id' => $court->id,
            'user_id' => $customer->id,
            'start_time' => '18:00',
            'end_time' => '19:00',
        ]);
    }

    public function test_double_booking_is_rejected(): void
    {
        $court = $this->createCourtWithSchedule();
        $customer = User::factory()->create();

        Sanctum::actingAs($customer);
        $this->postJson('/api/bookings', [
            'court_id' => $court->id,
            'booking_date' => now()->addDay()->toDateString(),
            'start_time' => '18:00',
            'duration_hours' => 1,
        ])->assertCreated();

        $this->postJson('/api/bookings', [
            'court_id' => $court->id,
            'booking_date' => now()->addDay()->toDateString(),
            'start_time' => '18:00',
            'duration_hours' => 1,
        ])->assertStatus(422);
    }

    public function test_customer_can_open_booking_page(): void
    {
        $court = $this->createCourtWithSchedule();
        $customer = User::factory()->create();

        $this->actingAs($customer)
            ->get(route('bookings.create', [
                'court_id' => $court->id,
                'date' => now()->addDay()->toDateString(),
            ]))
            ->assertOk()
            ->assertSee('Book your badminton court');
    }

    protected function createCourtWithSchedule(): Court
    {
        $court = Court::factory()->create();
        $court->schedules()->createMany(collect(range(1, 7))->map(fn (int $day) => [
            'day_of_week' => $day,
            'is_open' => true,
            'open_time' => '08:00',
            'close_time' => '22:00',
        ])->all());

        return $court->fresh(['schedules']);
    }
}
