<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Booking;
use App\Models\Court;
use App\Models\CourtSchedule;
use App\Models\User;
use App\Models\UserNotification;
use App\Services\AuthService;
use App\Services\BookingService;
use App\Services\RecommendationService;
use App\Services\ReportService;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AuthPageController extends Controller
{
    public function __construct(
        protected AuthService $authService,
        protected BookingService $bookingService,
        protected RecommendationService $recommendationService,
        protected ReportService $reportService,
    ) {
    }

    public function showLogin(): View
    {
        return view('pages.auth.login');
    }

    public function showRegister(): View
    {
        return view('pages.auth.register');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $this->authService->loginToSession(
            $request->validated(),
            (bool) $request->boolean('remember'),
        );

        $request->session()->regenerate();

        return redirect()
            ->route('dashboard')
            ->with('status', 'Welcome back, you are now signed in.');
    }

    public function register(RegisterRequest $request): RedirectResponse
    {
        $this->authService->registerToSession($request->validated());

        $request->session()->regenerate();

        return redirect()
            ->route('dashboard')
            ->with('status', 'Account created successfully.');
    }

    public function logout(Request $request): RedirectResponse
    {
        $this->authService->logoutFromSession($request);

        return redirect()
            ->route('login')
            ->with('status', 'You have been signed out.');
    }

    public function dashboard(Request $request): View
    {
        $user = $request->user();
        $canManageCourts = in_array($user->role, ['admin', 'owner'], true);

        if (! $canManageCourts) {
            return view('pages.auth.dashboard-user', [
                'user' => $user,
                'canManageCourts' => false,
                'canBookCourts' => true,
                'recentBookings' => $this->bookingService->listForUser($user, 5),
                'recommendations' => $this->recommendationService->listForUser($user, 4),
                'reportSummary' => null,
            ]);
        }

        $bookingsQuery = Booking::query()
            ->when($user->role === 'owner', fn (Builder $query) => $query->whereHas('court', fn (Builder $courtQuery) => $courtQuery->where('owner_id', $user->id)))
            ->when($user->role === 'user', fn (Builder $query) => $query->where('user_id', $user->id));
        $courtsQuery = Court::query()
            ->when($user->role === 'owner', fn (Builder $query) => $query->where('owner_id', $user->id));
        $notificationsQuery = UserNotification::query()->where('user_id', $user->id);

        $overviewCards = $user->role === 'admin'
            ? [
                ['label' => 'Total Users', 'value' => User::query()->where('role', 'user')->count(), 'meta' => 'Customer accounts', 'tone' => 'primary'],
                ['label' => 'Total Owners', 'value' => User::query()->where('role', 'owner')->count(), 'meta' => 'Court partners', 'tone' => 'success'],
                ['label' => 'Total Bookings', 'value' => (clone $bookingsQuery)->count(), 'meta' => 'All reservations', 'tone' => 'primary'],
                ['label' => 'Total Revenue', 'value' => 'Rp '.number_format((float) (clone $bookingsQuery)->whereIn('status', ['paid', 'finished'])->sum('total_price'), 0, ',', '.'), 'meta' => 'Paid and completed', 'tone' => 'success'],
                ['label' => 'Active Courts', 'value' => Court::query()->where('status', 'active')->count(), 'meta' => 'Live venues', 'tone' => 'primary'],
                ['label' => 'System Activity', 'value' => (clone $notificationsQuery)->where('is_read', false)->count(), 'meta' => 'Unread alerts', 'tone' => 'warning'],
            ]
            : [
                ['label' => 'Total Bookings', 'value' => (clone $bookingsQuery)->count(), 'meta' => 'All reservation records', 'tone' => 'primary'],
                ['label' => 'Today Bookings', 'value' => (clone $bookingsQuery)->whereDate('booking_date', today())->count(), 'meta' => 'Today schedule load', 'tone' => 'primary'],
                ['label' => 'Active Schedules', 'value' => CourtSchedule::query()->where('is_open', true)->when($user->role === 'owner', fn (Builder $query) => $query->whereHas('court', fn (Builder $courtQuery) => $courtQuery->where('owner_id', $user->id)))->count(), 'meta' => 'Open playable slots', 'tone' => 'success'],
                ['label' => 'Monthly Revenue', 'value' => 'Rp '.number_format((float) (clone $bookingsQuery)->whereMonth('booking_date', now()->month)->whereYear('booking_date', now()->year)->whereIn('status', ['paid', 'finished'])->sum('total_price'), 0, ',', '.'), 'meta' => 'Current month', 'tone' => 'success'],
                ['label' => 'Pending Confirmations', 'value' => (clone $bookingsQuery)->where('status', 'pending')->count(), 'meta' => 'Need action', 'tone' => 'warning'],
            ];

        $bookingTrend = collect(range(6, 0))
            ->map(function (int $daysAgo) use ($bookingsQuery) {
                $date = now()->subDays($daysAgo);

                return [
                    'label' => $date->format('D'),
                    'value' => (clone $bookingsQuery)->whereDate('booking_date', $date->toDateString())->count(),
                ];
            });
        $topCourts = $courtsQuery
            ->withCount('bookings')
            ->orderByDesc('bookings_count')
            ->limit(5)
            ->get();
        $peakHours = Booking::query()
            ->when($user->role === 'owner', fn (Builder $query) => $query->whereHas('court', fn (Builder $courtQuery) => $courtQuery->where('owner_id', $user->id)))
            ->get(['start_time'])
            ->groupBy(fn ($booking) => substr((string) $booking->start_time, 0, 2).':00')
            ->map(fn ($rows, $label) => ['label' => $label, 'total' => $rows->count()])
            ->sortBy('label')
            ->take(6)
            ->values();

        return view('pages.auth.dashboard', [
            'user' => $user,
            'canManageCourts' => $canManageCourts,
            'canBookCourts' => true,
            'overviewCards' => $overviewCards,
            'bookingTrend' => $bookingTrend,
            'topCourts' => $topCourts,
            'peakHours' => $peakHours,
            'recentNotifications' => $notificationsQuery->latest('created_at')->limit(6)->get(),
            'recentBookings' => $this->bookingService->listForUser($user, 5),
            'recommendations' => $this->recommendationService->listForUser($user, 4),
            'reportSummary' => $canManageCourts ? $this->reportService->summaryFor($user) : null,
        ]);
    }
}
