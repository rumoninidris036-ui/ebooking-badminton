<?php

namespace Tests\Feature\Platform;

use App\Models\Booking;
use App\Models\Court;
use App\Models\Facility;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PlatformAlignmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_api_exposes_fields_alias_and_facilities(): void
    {
        $facility = Facility::query()->create([
            'name' => 'Parking',
            'icon' => 'car',
        ]);

        $court = Court::factory()->create([
            'status' => 'active',
        ]);
        $court->facilities()->attach($facility->id);
        $court->schedules()->createMany($this->weeklySchedules());

        $this->getJson('/api/fields')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.items.0.facilities.0.name', 'Parking');
    }

    public function test_booking_cancellation_creates_cancellation_record_and_notification(): void
    {
        $court = Court::factory()->create([
            'status' => 'active',
        ]);
        $court->schedules()->createMany($this->weeklySchedules());
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $create = $this->postJson('/api/bookings', [
            'court_id' => $court->id,
            'booking_date' => now()->addDay()->toDateString(),
            'start_time' => '18:00',
            'duration_hours' => 1,
        ])->assertCreated();

        $bookingId = $create->json('data.id');

        $this->postJson('/api/bookings/'.$bookingId.'/cancel', [
            'cancellation_reason' => 'Schedule changed',
        ])->assertOk()
            ->assertJsonPath('data.status', 'canceled');

        $this->assertDatabaseHas('cancellations', [
            'booking_id' => $bookingId,
            'cancellation_reason' => 'Schedule changed',
        ]);

        $this->assertDatabaseHas('notifications', [
            'user_id' => $user->id,
            'type' => 'booking_canceled',
        ]);
    }

    public function test_completed_booking_can_submit_review_and_owner_can_view_report(): void
    {
        $owner = User::factory()->owner()->create();
        $user = User::factory()->create();
        $court = Court::factory()->for($owner, 'owner')->create([
            'status' => 'active',
        ]);
        $court->schedules()->createMany($this->weeklySchedules());

        Booking::factory()->create([
            'user_id' => $user->id,
            'court_id' => $court->id,
            'schedule_id' => $court->schedules()->first()->id,
            'booking_date' => now()->subDay()->toDateString(),
            'start_time' => '18:00',
            'end_time' => '19:00',
            'duration_hours' => 1,
            'price_per_hour' => $court->price_per_hour,
            'total_price' => $court->price_per_hour,
            'status' => 'finished',
        ]);

        Sanctum::actingAs($user);
        $this->postJson('/api/reviews', [
            'court_id' => $court->id,
            'rating' => 5,
            'comment' => 'Great court',
        ])->assertCreated()
            ->assertJsonPath('data.rating', 5);

        $this->getJson('/api/recommendations')
            ->assertOk()
            ->assertJsonPath('success', true);

        Sanctum::actingAs($owner);
        $this->getJson('/api/reports')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.booking_total', 1);
    }

    public function test_owner_can_confirm_and_finish_booking_lifecycle(): void
    {
        $owner = User::factory()->owner()->create();
        $user = User::factory()->create();
        $court = Court::factory()->for($owner, 'owner')->create([
            'status' => 'active',
        ]);
        $court->schedules()->createMany($this->weeklySchedules());

        $booking = Booking::factory()->create([
            'user_id' => $user->id,
            'court_id' => $court->id,
            'schedule_id' => $court->schedules()->first()->id,
            'status' => 'pending',
        ]);

        Sanctum::actingAs($owner);

        $this->postJson('/api/bookings/'.$booking->id.'/confirm')
            ->assertOk()
            ->assertJsonPath('data.status', 'paid');

        $this->postJson('/api/bookings/'.$booking->id.'/finish')
            ->assertOk()
            ->assertJsonPath('data.status', 'finished');
    }

    protected function weeklySchedules(): array
    {
        return collect(range(1, 7))->map(fn (int $day) => [
            'day_of_week' => $day,
            'is_open' => true,
            'open_time' => '08:00',
            'close_time' => '22:00',
        ])->all();
    }
}
