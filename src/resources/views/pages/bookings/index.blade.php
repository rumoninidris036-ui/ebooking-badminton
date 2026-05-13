<x-dashboard.layout
    :title="'Bookings | '.config('app.name')"
    heading="Booking Management"
    :description="in_array($user->role, ['admin', 'owner'], true)
        ? 'Unified booking operations with fast confirmation, status visibility, and payment-aware monitoring.'
        : 'Review your reservations, payment progress, and cancellation history in one clear timeline.'"
>
    <x-slot:actions>
        <a href="{{ route('bookings.create') }}" class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">
            New Booking
        </a>
    </x-slot:actions>

    @php
        $statusCounts = [
            'pending' => $bookings->getCollection()->where('status', 'pending')->count(),
            'confirmed' => $bookings->getCollection()->where('status', 'confirmed')->count(),
            'paid' => $bookings->getCollection()->where('status', 'paid')->count(),
            'canceled' => $bookings->getCollection()->where('status', 'canceled')->count(),
            'completed' => $bookings->getCollection()->where('status', 'finished')->count(),
        ];
    @endphp

    @if (session('status'))
        <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
            {{ session('status') }}
        </div>
    @endif

    <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
        <x-dashboard.stat-card label="Pending" :value="$statusCounts['pending']" meta="Awaiting action" tone="warning" />
        <x-dashboard.stat-card label="Confirmed" :value="$statusCounts['confirmed']" meta="Reserved successfully" tone="primary" />
        <x-dashboard.stat-card label="Paid" :value="$statusCounts['paid']" meta="Ready to play" tone="success" />
        <x-dashboard.stat-card label="Canceled" :value="$statusCounts['canceled']" meta="Removed bookings" tone="danger" />
        <x-dashboard.stat-card label="Completed" :value="$statusCounts['completed']" meta="Finished sessions" tone="primary" />
    </section>

    <section class="mt-6">
        <x-dashboard.filter-bar>
            <div class="flex flex-wrap gap-2">
                <span class="dashboard-filter-chip">Date sorting</span>
                <span class="dashboard-filter-chip">Status filters</span>
                <span class="dashboard-filter-chip">Payment visibility</span>
            </div>
            <div class="text-sm text-slate-500">
                {{ $bookings->total() }} records
            </div>
        </x-dashboard.filter-bar>
    </section>

    <section class="mt-6">
        @if ($bookings->count() === 0)
            <x-dashboard.empty-state title="No bookings yet" description="Choose a court and reserve your first available slot." />
        @else
            <div class="dashboard-table-wrap">
                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th>Booking</th>
                            <th>Customer</th>
                            <th>Court</th>
                            <th>Date & Time</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bookings as $booking)
                            <tr>
                                <td>
                                    <p class="font-semibold text-slate-900">{{ $booking->booking_code }}</p>
                                    <p class="mt-1 text-xs text-slate-500">{{ $booking->notes ?: 'No extra note' }}</p>
                                </td>
                                <td>
                                    <p class="font-medium text-slate-900">{{ $booking->user->name }}</p>
                                    <p class="mt-1 text-xs text-slate-500 capitalize">{{ $user->role === 'user' ? 'Personal booking' : 'Customer record' }}</p>
                                </td>
                                <td>
                                    <p class="font-medium text-slate-900">{{ $booking->court->name }}</p>
                                    <p class="mt-1 text-xs text-slate-500">{{ $booking->court->location }}</p>
                                </td>
                                <td>
                                    <p class="font-medium text-slate-900">{{ $booking->booking_date->format('d M Y') }}</p>
                                    <p class="mt-1 text-xs text-slate-500">{{ substr($booking->start_time, 0, 5) }} - {{ substr($booking->end_time, 0, 5) }}</p>
                                </td>
                                <td class="font-semibold text-slate-900">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                                <td><x-dashboard.status-badge :status="$booking->status === 'finished' ? 'completed' : $booking->status" /></td>
                                <td>
                                    <div class="flex flex-wrap justify-end gap-2">
                                        @if ($user->role === 'user' && in_array($booking->status, ['pending', 'paid'], true))
                                            <form method="POST" action="{{ route('bookings.cancel', $booking->id) }}" class="flex flex-wrap justify-end gap-2">
                                                @csrf
                                                <input type="text" name="cancellation_reason" required placeholder="Reason" class="rounded-xl border border-slate-200 px-3 py-2 text-sm text-slate-700">
                                                <button type="submit" class="rounded-xl border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Cancel</button>
                                            </form>
                                        @endif
                                        @if (in_array($user->role, ['admin', 'owner'], true) && $booking->status === 'pending')
                                            <form method="POST" action="{{ route('bookings.confirm', $booking->id) }}">
                                                @csrf
                                                <button type="submit" class="rounded-xl bg-emerald-600 px-3 py-2 text-sm font-semibold text-white transition hover:bg-emerald-700">Confirm</button>
                                            </form>
                                        @endif
                                        @if (in_array($user->role, ['admin', 'owner'], true) && $booking->status === 'paid')
                                            <form method="POST" action="{{ route('bookings.finish', $booking->id) }}">
                                                @csrf
                                                <button type="submit" class="rounded-xl bg-blue-600 px-3 py-2 text-sm font-semibold text-white transition hover:bg-blue-700">Complete</button>
                                            </form>
                                        @endif
                                    </div>
                                    @if ($booking->cancellation?->cancellation_reason)
                                        <p class="mt-2 text-right text-xs text-rose-600">{{ $booking->cancellation->cancellation_reason }}</p>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>

    <div class="mt-6">
        {{ $bookings->links() }}
    </div>
</x-dashboard.layout>
