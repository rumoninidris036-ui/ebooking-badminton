<?php

namespace Tests\Feature\Operations;

use App\Models\Booking;
use App\Models\Court;
use App\Models\CourtSchedule;
use App\Models\Payment;
use App\Models\Recommendation;
use App\Models\Review;
use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OperationsDashboardSmokeTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_open_unified_operations_pages(): void
    {
        $owner = User::factory()->owner()->create();
        $customer = User::factory()->create();
        $court = Court::factory()->create(['owner_id' => $owner->id]);

        CourtSchedule::query()->create([
            'court_id' => $court->id,
            'day_of_week' => 1,
            'open_time' => '08:00',
            'close_time' => '22:00',
            'is_open' => true,
        ]);

        $booking = Booking::factory()->create([
            'user_id' => $customer->id,
            'court_id' => $court->id,
            'status' => 'pending',
        ]);

        Review::query()->create([
            'user_id' => $customer->id,
            'court_id' => $court->id,
            'rating' => 5,
            'comment' => 'Great court',
        ]);

        UserNotification::query()->create([
            'user_id' => $owner->id,
            'type' => 'booking',
            'title' => 'New booking',
            'message' => 'A booking is waiting for confirmation.',
            'channel' => 'in_app',
            'is_read' => false,
            'created_at' => now(),
        ]);

        Recommendation::query()->create([
            'user_id' => $owner->id,
            'court_id' => $court->id,
            'similarity_score' => 89.5,
            'created_at' => now(),
        ]);

        $routes = [
            'dashboard',
            'bookings.index',
            'courts.index',
            'operations.schedules',
            'operations.reviews',
            'operations.notifications',
            'operations.reports',
            'operations.profile',
            'operations.owner.revenue',
            'operations.owner.requests',
        ];

        foreach ($routes as $route) {
            $this->actingAs($owner)
                ->get(route($route))
                ->assertOk();
        }

        $this->actingAs($owner)
            ->get(route('operations.owner.requests'))
            ->assertSee($booking->booking_code);
    }

    public function test_admin_can_open_unified_operations_pages(): void
    {
        $admin = User::factory()->admin()->create();
        $owner = User::factory()->owner()->create();
        $customer = User::factory()->create();
        $court = Court::factory()->create(['owner_id' => $owner->id]);

        $booking = Booking::factory()->create([
            'user_id' => $customer->id,
            'court_id' => $court->id,
            'status' => 'paid',
        ]);

        Payment::query()->create([
            'booking_id' => $booking->id,
            'payment_method' => 'bank_transfer',
            'transaction_id' => 'TRX-001',
            'amount' => 90000,
            'payment_status' => 'paid',
            'paid_at' => now(),
        ]);

        UserNotification::query()->create([
            'user_id' => $admin->id,
            'type' => 'system',
            'title' => 'System status',
            'message' => 'All services operational.',
            'channel' => 'in_app',
            'is_read' => false,
            'created_at' => now(),
        ]);

        Recommendation::query()->create([
            'user_id' => $customer->id,
            'court_id' => $court->id,
            'similarity_score' => 91.2,
            'created_at' => now(),
        ]);

        $routes = [
            'dashboard',
            'bookings.index',
            'courts.index',
            'operations.schedules',
            'operations.reviews',
            'operations.notifications',
            'operations.reports',
            'operations.profile',
            'operations.admin.users',
            'operations.admin.owners',
            'operations.admin.analytics',
            'operations.admin.recommendations',
            'operations.admin.transactions',
            'operations.admin.monitoring',
            'operations.admin.settings',
        ];

        foreach ($routes as $route) {
            $this->actingAs($admin)
                ->get(route($route))
                ->assertOk();
        }
    }

    public function test_user_is_kept_inside_user_friendly_pages_for_shared_routes(): void
    {
        $user = User::factory()->create();
        $court = Court::factory()->create();

        $booking = Booking::factory()->create([
            'user_id' => $user->id,
            'court_id' => $court->id,
            'status' => 'finished',
        ]);

        Review::query()->create([
            'user_id' => $user->id,
            'court_id' => $court->id,
            'rating' => 5,
            'comment' => 'Mantap',
        ]);

        UserNotification::query()->create([
            'user_id' => $user->id,
            'type' => 'booking',
            'title' => 'Booking update',
            'message' => 'Booking Anda sudah selesai.',
            'channel' => 'in_app',
            'is_read' => false,
            'created_at' => now(),
        ]);

        Recommendation::query()->create([
            'user_id' => $user->id,
            'court_id' => $court->id,
            'similarity_score' => 90.1,
            'created_at' => now(),
        ]);

        $routes = [
            'dashboard',
            'bookings.index',
            'operations.schedules',
            'operations.reviews',
            'operations.notifications',
            'operations.reports',
            'operations.profile',
        ];

        foreach ($routes as $route) {
            $this->actingAs($user)
                ->get(route($route))
                ->assertOk()
                ->assertDontSee('Operations Console');
        }

        $this->assertEquals('finished', $booking->status);
    }
}
