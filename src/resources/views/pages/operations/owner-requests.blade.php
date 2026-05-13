<x-dashboard.layout
    :title="'Booking Requests | '.config('app.name')"
    heading="Booking Requests"
    description="Owner-facing queue of incoming reservations that need fast confirmation and clear status handling."
>
    <div class="dashboard-table-wrap">
        <table class="dashboard-table">
            <thead>
                <tr>
                    <th>Booking Code</th>
                    <th>Customer</th>
                    <th>Court</th>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th class="text-right">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pendingBookings as $booking)
                    <tr>
                        <td class="font-semibold text-slate-900">{{ $booking->booking_code }}</td>
                        <td>{{ $booking->user->name }}</td>
                        <td>
                            <p class="font-medium text-slate-900">{{ $booking->court->name }}</p>
                            <p class="mt-1 text-xs text-slate-500">{{ $booking->court->location }}</p>
                        </td>
                        <td>{{ $booking->booking_date->format('d M Y') }} • {{ substr($booking->start_time, 0, 5) }}</td>
                        <td>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                        <td><x-dashboard.status-badge :status="$booking->status" /></td>
                        <td class="text-right">
                            <form method="POST" action="{{ route('bookings.confirm', $booking->id) }}">
                                @csrf
                                <button type="submit" class="rounded-xl bg-emerald-600 px-3 py-2 text-sm font-semibold text-white transition hover:bg-emerald-700">
                                    Confirm
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-sm text-slate-500">No pending booking requests.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $pendingBookings->links() }}
    </div>
</x-dashboard.layout>
