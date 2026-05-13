<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use App\Http\Requests\Booking\StoreBookingRequest;
use App\Services\BookingService;
use App\Services\CourtService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BookingPageController extends Controller
{
    public function __construct(
        protected BookingService $bookingService,
        protected CourtService $courtService,
    ) {
    }

    public function index(Request $request): View
    {
        $user = $request->user();

        if ($user->role === 'user') {
            return view('pages.bookings.index-user', [
                'bookings' => $this->bookingService->listForUser($user, 12),
                'user' => $user,
            ]);
        }

        return view('pages.bookings.index', [
            'bookings' => $this->bookingService->listForUser($user, 12),
            'user' => $user,
        ]);
    }

    public function create(Request $request): View
    {
        $courts = $this->courtService->listPublic(20);
        $selectedCourtId = (int) $request->integer('court_id', $courts->getCollection()->first()?->id);
        $selectedDate = (string) $request->string('date', now()->toDateString());
        $availability = null;

        if ($selectedCourtId) {
            $availability = $this->bookingService->availability($selectedCourtId, $selectedDate);
        }

        return view('pages.bookings.create', [
            'courts' => $courts->getCollection(),
            'selectedCourtId' => $selectedCourtId,
            'selectedDate' => $selectedDate,
            'availability' => $availability,
        ]);
    }

    public function store(StoreBookingRequest $request): RedirectResponse
    {
        $booking = $this->bookingService->create($request->validated(), $request->user());

        return redirect()
            ->route('bookings.index')
            ->with('status', 'Booking '.$booking->booking_code.' created successfully.');
    }

    public function cancel(Request $request, int $booking): RedirectResponse
    {
        $validated = $request->validate([
            'cancellation_reason' => ['required', 'string', 'max:1000'],
        ]);

        $booking = $this->bookingService->cancel($booking, $validated, $request->user());

        return redirect()
            ->route('bookings.index')
            ->with('status', 'Booking '.$booking->booking_code.' canceled successfully.');
    }

    public function confirm(Request $request, int $booking): RedirectResponse
    {
        $booking = $this->bookingService->confirm($booking, $request->user());

        return redirect()
            ->route('bookings.index')
            ->with('status', 'Booking '.$booking->booking_code.' confirmed successfully.');
    }

    public function finish(Request $request, int $booking): RedirectResponse
    {
        $booking = $this->bookingService->finish($booking, $request->user());

        return redirect()
            ->route('bookings.index')
            ->with('status', 'Booking '.$booking->booking_code.' marked as finished.');
    }
}
