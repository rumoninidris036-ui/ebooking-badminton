<?php

namespace Tests\Feature\Court;

use App\Models\Court;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CourtManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_api_can_list_active_courts(): void
    {
        $court = Court::factory()->create();
        $court->schedules()->createMany($this->weeklySchedules());

        $response = $this->getJson('/api/courts');

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonCount(1, 'data.items')
            ->assertJsonPath('data.items.0.name', $court->name);
    }

    public function test_owner_can_create_court_via_api(): void
    {
        $owner = User::factory()->owner()->create();
        Sanctum::actingAs($owner);

        $response = $this->postJson('/api/courts', [
            'name' => 'Arena Court',
            'description' => 'Indoor court for prime time sessions.',
            'location' => 'Central Hall',
            'price_per_hour' => 95000,
            'is_active' => true,
            'schedules' => $this->weeklySchedules(),
        ]);

        $response->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.name', 'Arena Court');

        $this->assertDatabaseHas('courts', [
            'name' => 'Arena Court',
            'owner_id' => $owner->id,
        ]);
    }

    public function test_customer_cannot_create_court_via_api(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->postJson('/api/courts', [
            'name' => 'Blocked Court',
            'location' => 'Hall',
            'price_per_hour' => 80000,
            'schedules' => $this->weeklySchedules(),
        ])->assertForbidden();
    }

    public function test_owner_can_access_court_management_page(): void
    {
        $owner = User::factory()->owner()->create();

        $this->actingAs($owner)
            ->get(route('courts.index'))
            ->assertOk()
            ->assertSee('Manage badminton courts');
    }

    /**
     * @return array<int, array<string, mixed>>
     */
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
