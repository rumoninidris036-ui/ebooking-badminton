<?php

namespace App\Services;

use App\Events\Booking\BookingCanceled;
use App\Events\Booking\BookingCreated;
use App\Models\Booking;
use App\Models\Court;
use App\Models\User;
use App\Repositories\BookingRepository;
use App\Repositories\CourtRepository;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class BookingService
{
    public function __construct(
        protected BookingRepository $bookings,
        protected CourtRepository $courts,
        protected CourtService $courtService,
        protected CancellationService $cancellationService,
    ) {
    }

    public function listForUser(User $user, int $perPage = 10): LengthAwarePaginator
    {
        return $this->bookings->paginateForUser($user, $perPage);
    }

    public function findVisibleOrFail(int $id, User $user): Booking
    {
        return $this->bookings->findVisibleByUser($id, $user) ?? throw new ModelNotFoundException();
    }

    public function availability(int $courtId, string $date): array
    {
        $court = $this->courtService->findPublicOrFail($courtId);

        return [
            'court' => $court,
            'date' => $date,
            'slots' => $this->buildSlots($court, $date),
        ];
    }

    public function create(array $attributes, User $user): Booking
    {
        $court = $this->courtService->findPublicOrFail((int) $attributes['court_id']);

        $bookingDate = Carbon::parse($attributes['booking_date'])->toDateString();
        $startTime = $attributes['start_time'];
        $duration = (int) $attributes['duration_hours'];
        $endTime = Carbon::createFromFormat('H:i', $startTime)->addHours($duration)->format('H:i');

        $this->validateSlotAgainstSchedule($court, $bookingDate, $startTime, $endTime);

        try {
            return DB::transaction(function () use ($court, $bookingDate, $startTime, $endTime, $duration, $attributes, $user) {
                Court::query()->whereKey($court->id)->lockForUpdate()->first();

                $overlaps = $this->bookings->overlappingBookings($court, $bookingDate, $startTime, $endTime);

                if ($overlaps->isNotEmpty()) {
                    throw ValidationException::withMessages([
                        'start_time' => ['The selected slot has just been booked by another player.'],
                    ]);
                }

                $booking = $this->bookings->create([
                    'booking_code' => $this->generateBookingCode(),
                    'user_id' => $user->id,
                    'court_id' => $court->id,
                    'schedule_id' => $court->schedules->firstWhere('day_of_week', (int) Carbon::parse($bookingDate)->isoWeekday())?->id,
                    'booking_date' => $bookingDate,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'duration_hours' => $duration,
                    'price_per_hour' => $court->price_per_hour,
                    'total_price' => (float) $court->price_per_hour * $duration,
                    'status' => 'pending',
                    'notes' => $attributes['notes'] ?? null,
                ])->fresh(['court:id,name,location,owner_id', 'user:id,name']);

                BookingCreated::dispatch($booking);

                return $booking;
            });
        } catch (QueryException $exception) {
            if ((string) $exception->getCode() === '23000') {
                throw ValidationException::withMessages([
                    'start_time' => ['This booking slot is no longer available.'],
                ]);
            }

            throw $exception;
        }
    }

    public function cancel(int $bookingId, array $attributes, User $user): Booking
    {
        $booking = $this->bookings->findById($bookingId) ?? throw new ModelNotFoundException();

        if (! $this->canAccess($booking, $user)) {
            throw new HttpException(403, 'You are not allowed to cancel this booking.');
        }

        if (! in_array($booking->status, ['pending', 'paid'], true)) {
            throw ValidationException::withMessages([
                'status' => ['Only pending or paid bookings can be canceled.'],
            ]);
        }

        return DB::transaction(function () use ($booking, $attributes, $user) {
            $booking = $this->bookings->update($booking, [
                'status' => 'canceled',
            ]);

            $this->cancellationService->store($booking, $attributes['cancellation_reason'], $user);

            $booking = $booking->fresh(['court:id,name,location,owner_id', 'user:id,name,email', 'cancellation']);
            BookingCanceled::dispatch($booking);

            return $booking;
        });
    }

    public function confirm(int $bookingId, User $user): Booking
    {
        $booking = $this->bookings->findById($bookingId) ?? throw new ModelNotFoundException();

        if (! $this->canManage($booking, $user)) {
            throw new HttpException(403, 'You are not allowed to confirm this booking.');
        }

        if ($booking->status !== 'pending') {
            throw ValidationException::withMessages([
                'status' => ['Only pending bookings can be confirmed.'],
            ]);
        }

        return $this->bookings->update($booking, [
            'status' => 'paid',
        ]);
    }

    public function finish(int $bookingId, User $user): Booking
    {
        $booking = $this->bookings->findById($bookingId) ?? throw new ModelNotFoundException();

        if (! $this->canManage($booking, $user)) {
            throw new HttpException(403, 'You are not allowed to finish this booking.');
        }

        if ($booking->status !== 'paid') {
            throw ValidationException::withMessages([
                'status' => ['Only paid bookings can be marked as finished.'],
            ]);
        }

        return $this->bookings->update($booking, [
            'status' => 'finished',
        ]);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    protected function buildSlots(Court $court, string $date): array
    {
        $bookingDate = Carbon::parse($date);
        $dayNumber = (int) $bookingDate->isoWeekday();
        $schedule = $court->schedules->firstWhere('day_of_week', $dayNumber);

        if (! $schedule || ! $schedule->is_open || ! $schedule->open_time || ! $schedule->close_time) {
            return [];
        }

        $existing = $court->bookings()
            ->whereDate('booking_date', $bookingDate->toDateString())
            ->whereIn('status', ['pending', 'paid'])
            ->get(['start_time', 'end_time']);

        $open = $this->timeToCarbon($schedule->open_time);
        $close = $this->timeToCarbon($schedule->close_time);
        $now = now();
        $slots = [];

        while ($open->lt($close)) {
            $slotStart = $open->copy();
            $slotEnd = $open->copy()->addHour();

            if ($slotEnd->gt($close)) {
                break;
            }

            $isBooked = $existing->contains(fn ($booking) => $booking->start_time < $slotEnd->format('H:i:s') && $booking->end_time > $slotStart->format('H:i:s'));
            $isPast = $bookingDate->copy()->setTimeFromTimeString($slotStart->format('H:i:s'))->lt($now);

            $slots[] = [
                'start_time' => $slotStart->format('H:i'),
                'end_time' => $slotEnd->format('H:i'),
                'status' => $isPast ? 'disabled' : ($isBooked ? 'booked' : 'available'),
                'label' => $slotStart->format('H:i').' - '.$slotEnd->format('H:i'),
            ];

            $open->addHour();
        }

        return $slots;
    }

    protected function validateSlotAgainstSchedule(Court $court, string $date, string $startTime, string $endTime): void
    {
        $bookingDate = Carbon::parse($date);

        if ($bookingDate->isPast() && ! $bookingDate->isToday()) {
            throw ValidationException::withMessages([
                'booking_date' => ['Booking date must be today or later.'],
            ]);
        }

        $schedule = $court->schedules->firstWhere('day_of_week', (int) $bookingDate->isoWeekday());

        if (! $schedule || ! $schedule->is_open) {
            throw ValidationException::withMessages([
                'booking_date' => ['This court is closed on the selected date.'],
            ]);
        }

        $scheduleOpen = $this->timeToCarbon($schedule->open_time)->format('H:i');
        $scheduleClose = $this->timeToCarbon($schedule->close_time)->format('H:i');

        if ($startTime < $scheduleOpen || $endTime > $scheduleClose) {
            throw ValidationException::withMessages([
                'start_time' => ['The selected slot is outside the court operating hours.'],
            ]);
        }

        if ($bookingDate->isToday() && Carbon::createFromFormat('H:i', $startTime)->lt(now())) {
            throw ValidationException::withMessages([
                'start_time' => ['The selected slot is already in the past.'],
            ]);
        }
    }

    protected function generateBookingCode(): string
    {
        do {
            $code = 'BK-'.now()->format('ymd').'-'.Str::upper(Str::random(6));
        } while (Booking::query()->where('booking_code', $code)->exists());

        return $code;
    }

    protected function timeToCarbon(string $time): Carbon
    {
        $format = str($time)->contains(':') && substr_count($time, ':') === 2 ? 'H:i:s' : 'H:i';

        return Carbon::createFromFormat($format, $time);
    }

    protected function canAccess(Booking $booking, User $user): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'owner') {
            return (int) $booking->court?->owner_id === $user->id;
        }

        return $booking->user_id === $user->id;
    }

    protected function canManage(Booking $booking, User $user): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        return $user->role === 'owner' && (int) $booking->court?->owner_id === $user->id;
    }
}
