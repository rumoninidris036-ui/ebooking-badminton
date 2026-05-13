<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Court;
use App\Models\User;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ReportService
{
    public function summaryFor(User $user): array
    {
        if (! in_array($user->role, ['admin', 'owner'], true)) {
            throw new HttpException(403, 'You are not allowed to access reports.');
        }

        $bookings = Booking::query()
            ->when($user->role === 'owner', fn ($query) => $query->whereHas('court', fn ($courtQuery) => $courtQuery->where('owner_id', $user->id)));

        return [
            'booking_total' => (clone $bookings)->count(),
            'pending_bookings' => (clone $bookings)->where('status', 'pending')->count(),
            'paid_bookings' => (clone $bookings)->where('status', 'paid')->count(),
            'canceled_bookings' => (clone $bookings)->where('status', 'canceled')->count(),
            'revenue_total' => (float) (clone $bookings)->whereIn('status', ['paid', 'finished'])->sum('total_price'),
            'active_fields' => Court::query()
                ->when($user->role === 'owner', fn ($query) => $query->where('owner_id', $user->id))
                ->where('status', 'active')
                ->count(),
        ];
    }
}
