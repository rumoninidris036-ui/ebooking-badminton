<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Court;
use App\Models\Payment;
use App\Models\Recommendation;
use App\Models\Review;
use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class OperationsPageController extends Controller
{
    public function schedules(Request $request): View
    {
        $user = $request->user();
        $courts = $this->visibleCourtsQuery($user)
            ->with('schedules')
            ->withCount([
                'bookings as today_bookings_count' => fn ($query) => $query->whereDate('booking_date', today()),
            ])
            ->limit(6)
            ->get();

        return view('pages.operations.schedules', [
            'courts' => $courts,
            'calendarDays' => collect(range(1, 7))->map(fn (int $day) => [
                'value' => $day,
                'label' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'][$day - 1],
            ]),
        ]);
    }

    public function reviews(Request $request): View
    {
        $user = $request->user();

        return view('pages.operations.reviews', [
            'reviews' => Review::query()
                ->with(['user:id,name', 'court:id,name,location,owner_id'])
                ->when($user->role === 'owner', fn (Builder $query) => $query->whereHas('court', fn (Builder $courtQuery) => $courtQuery->where('owner_id', $user->id)))
                ->latest()
                ->paginate(10),
            'ratingSummary' => [
                'average' => number_format((float) Review::query()
                    ->when($user->role === 'owner', fn (Builder $query) => $query->whereHas('court', fn (Builder $courtQuery) => $courtQuery->where('owner_id', $user->id)))
                    ->avg('rating'), 1),
                'total' => Review::query()
                    ->when($user->role === 'owner', fn (Builder $query) => $query->whereHas('court', fn (Builder $courtQuery) => $courtQuery->where('owner_id', $user->id)))
                    ->count(),
                'positive' => Review::query()
                    ->when($user->role === 'owner', fn (Builder $query) => $query->whereHas('court', fn (Builder $courtQuery) => $courtQuery->where('owner_id', $user->id)))
                    ->where('rating', '>=', 4)
                    ->count(),
            ],
        ]);
    }

    public function notifications(Request $request): View
    {
        return view('pages.operations.notifications', [
            'notifications' => $request->user()->notifications()->paginate(12),
            'summary' => [
                'unread' => $request->user()->notifications()->where('is_read', false)->count(),
                'booking' => $request->user()->notifications()->where('type', 'booking')->count(),
                'payment' => $request->user()->notifications()->where('type', 'payment')->count(),
                'system' => $request->user()->notifications()->where('type', 'system')->count(),
            ],
        ]);
    }

    public function reports(Request $request): View
    {
        $user = $request->user();
        $bookings = $this->visibleBookingsQuery($user);

        return view('pages.operations.reports', [
            'summary' => [
                'total_bookings' => (clone $bookings)->count(),
                'completed_bookings' => (clone $bookings)->where('status', 'finished')->count(),
                'conversion' => (clone $bookings)->count() > 0
                    ? round(((clone $bookings)->whereIn('status', ['paid', 'finished'])->count() / max((clone $bookings)->count(), 1)) * 100)
                    : 0,
                'revenue' => (float) (clone $bookings)->whereIn('status', ['paid', 'finished'])->sum('total_price'),
            ],
            'statusBreakdown' => collect(['pending', 'paid', 'finished', 'canceled'])->map(fn (string $status) => [
                'label' => ucfirst($status),
                'count' => (clone $bookings)->where('status', $status)->count(),
            ]),
            'topCourts' => $this->visibleCourtsQuery($user)
                ->withCount('bookings')
                ->orderByDesc('bookings_count')
                ->limit(5)
                ->get(),
        ]);
    }

    public function profile(Request $request): View
    {
        return view('pages.operations.profile', [
            'user' => $request->user(),
            'unreadNotifications' => $request->user()->notifications()->where('is_read', false)->count(),
        ]);
    }

    public function adminUsers(Request $request): View
    {
        $this->ensureAdmin($request->user());

        return view('pages.operations.admin-users', [
            'title' => 'User Management',
            'description' => 'Manage customers and monitor the growth of player accounts on the platform.',
            'users' => User::query()->where('role', 'user')->latest()->paginate(10),
        ]);
    }

    public function adminOwners(Request $request): View
    {
        $this->ensureAdmin($request->user());

        return view('pages.operations.admin-users', [
            'title' => 'Owner Management',
            'description' => 'Monitor owner accounts, their courts, and operational readiness.',
            'users' => User::query()->where('role', 'owner')->withCount('courts')->latest()->paginate(10),
        ]);
    }

    public function adminAnalytics(Request $request): View
    {
        $this->ensureAdmin($request->user());

        return view('pages.operations.admin-analytics', [
            'cards' => [
                ['label' => 'Total Users', 'value' => User::query()->where('role', 'user')->count()],
                ['label' => 'Total Owners', 'value' => User::query()->where('role', 'owner')->count()],
                ['label' => 'Active Courts', 'value' => Court::query()->where('status', 'active')->count()],
                ['label' => 'System Bookings', 'value' => Booking::query()->count()],
            ],
            'courts' => Court::query()->withCount('bookings')->orderByDesc('bookings_count')->limit(6)->get(),
            'peakHours' => Booking::query()
                ->get(['start_time'])
                ->groupBy(fn ($booking) => substr((string) $booking->start_time, 0, 2).':00')
                ->map(fn ($rows, $label) => ['hour_label' => $label, 'total' => $rows->count()])
                ->sortBy('hour_label')
                ->values(),
        ]);
    }

    public function adminRecommendations(Request $request): View
    {
        $this->ensureAdmin($request->user());

        return view('pages.operations.admin-recommendations', [
            'rows' => Recommendation::query()
                ->with(['user:id,name', 'court:id,name,location'])
                ->orderByDesc('similarity_score')
                ->limit(12)
                ->get(),
        ]);
    }

    public function adminTransactions(Request $request): View
    {
        $this->ensureAdmin($request->user());

        return view('pages.operations.admin-transactions', [
            'transactions' => Payment::query()
                ->with(['booking.court:id,name', 'booking.user:id,name'])
                ->latest()
                ->paginate(10),
        ]);
    }

    public function adminMonitoring(Request $request): View
    {
        $this->ensureAdmin($request->user());

        return view('pages.operations.admin-monitoring', [
            'health' => [
                ['label' => 'Active courts', 'value' => Court::query()->where('status', 'active')->count(), 'tone' => 'success'],
                ['label' => 'Unread notifications', 'value' => UserNotification::query()->where('is_read', false)->count(), 'tone' => 'warning'],
                ['label' => 'Pending bookings', 'value' => Booking::query()->where('status', 'pending')->count(), 'tone' => 'primary'],
                ['label' => 'Payments pending', 'value' => Payment::query()->where('payment_status', 'pending')->count(), 'tone' => 'danger'],
            ],
        ]);
    }

    public function adminSettings(Request $request): View
    {
        $this->ensureAdmin($request->user());

        return view('pages.operations.admin-settings');
    }

    public function ownerRevenue(Request $request): View
    {
        $this->ensureManager($request->user());
        $user = $request->user();
        $bookings = $this->visibleBookingsQuery($user);

        return view('pages.operations.owner-revenue', [
            'summary' => [
                'monthly' => (float) (clone $bookings)->whereMonth('booking_date', now()->month)->whereYear('booking_date', now()->year)->whereIn('status', ['paid', 'finished'])->sum('total_price'),
                'paid' => (clone $bookings)->where('status', 'paid')->count(),
                'completed' => (clone $bookings)->where('status', 'finished')->count(),
                'pending' => (clone $bookings)->where('status', 'pending')->count(),
            ],
            'courts' => $this->visibleCourtsQuery($user)->withCount('bookings')->get(),
        ]);
    }

    public function ownerRequests(Request $request): View
    {
        $this->ensureManager($request->user());
        $user = $request->user();

        return view('pages.operations.owner-requests', [
            'pendingBookings' => $this->visibleBookingsQuery($user)
                ->with(['court:id,name,location', 'user:id,name'])
                ->where('status', 'pending')
                ->latest('booking_date')
                ->paginate(10),
        ]);
    }

    protected function visibleCourtsQuery(User $user): Builder
    {
        return Court::query()
            ->when($user->role === 'owner', fn (Builder $query) => $query->where('owner_id', $user->id));
    }

    protected function visibleBookingsQuery(User $user): Builder
    {
        return Booking::query()
            ->when($user->role === 'owner', fn (Builder $query) => $query->whereHas('court', fn (Builder $courtQuery) => $courtQuery->where('owner_id', $user->id)))
            ->when($user->role === 'user', fn (Builder $query) => $query->where('user_id', $user->id));
    }

    protected function ensureAdmin(User $user): void
    {
        if ($user->role !== 'admin') {
            throw new HttpException(403, 'You are not allowed to access this page.');
        }
    }

    protected function ensureManager(User $user): void
    {
        if (! in_array($user->role, ['admin', 'owner'], true)) {
            throw new HttpException(403, 'You are not allowed to access this page.');
        }
    }
}
