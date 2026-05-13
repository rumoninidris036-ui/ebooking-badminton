<x-dashboard.layout
    :title="'Dashboard | '.config('app.name')"
    heading="Dashboard"
    :description="in_array($user->role, ['admin', 'owner'], true)
        ? 'Unified operational dashboard for booking control, court management, and analytics visibility.'
        : 'Track your account activity, recent bookings, and recommendation highlights in one place.'"
>
    <x-slot:actions>
        @if ($canBookCourts)
            <a href="{{ route('bookings.create') }}" class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">
                New Booking
            </a>
        @endif
        @if ($canManageCourts)
            <a href="{{ route('courts.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                Manage Courts
            </a>
        @endif
    </x-slot:actions>

    @if (session('status'))
        <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
            {{ session('status') }}
        </div>
    @endif

    <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
        @foreach ($overviewCards as $card)
            <x-dashboard.stat-card :label="$card['label']" :value="$card['value']" :meta="$card['meta']" :tone="$card['tone']" />
        @endforeach
    </section>

    <section class="mt-6 grid gap-6 xl:grid-cols-[1.15fr_0.85fr]">
        <div class="space-y-6">
            <article class="dashboard-card p-5 sm:p-6">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-950">Booking trends</h2>
                        <p class="mt-1 text-sm text-slate-500">Daily reservation movement for the last 7 days.</p>
                    </div>
                    <span class="dashboard-filter-chip">Analytics</span>
                </div>
                <div class="mt-6 grid grid-cols-7 gap-3">
                    @php $maxTrend = max(1, $bookingTrend->max('value')); @endphp
                    @foreach ($bookingTrend as $point)
                        <div class="flex flex-col items-center gap-3">
                            <div class="flex h-40 w-full items-end">
                                <div class="w-full rounded-t-2xl bg-blue-600/85" style="height: {{ max(12, ($point['value'] / $maxTrend) * 100) }}%"></div>
                            </div>
                            <div class="text-center">
                                <p class="text-sm font-semibold text-slate-900">{{ $point['value'] }}</p>
                                <p class="text-xs uppercase tracking-[0.18em] text-slate-400">{{ $point['label'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </article>

            <div class="grid gap-6 lg:grid-cols-2">
                <article class="dashboard-card p-5 sm:p-6">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <h2 class="text-lg font-semibold text-slate-950">Recent bookings</h2>
                            <p class="mt-1 text-sm text-slate-500">Fast access to the latest booking events.</p>
                        </div>
                        <a href="{{ route('bookings.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700">Open all</a>
                    </div>
                    <div class="mt-5 space-y-3">
                        @forelse ($recentBookings as $booking)
                            <div class="rounded-2xl border border-slate-200 px-4 py-4">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="font-semibold text-slate-900">{{ $booking->court->name }}</p>
                                        <p class="mt-1 text-sm text-slate-500">{{ $booking->booking_code }}</p>
                                        <p class="mt-2 text-sm text-slate-600">{{ $booking->booking_date->format('d M Y') }} • {{ substr($booking->start_time, 0, 5) }}</p>
                                    </div>
                                    <x-dashboard.status-badge :status="$booking->status" />
                                </div>
                            </div>
                        @empty
                            <div class="rounded-2xl bg-slate-50 px-4 py-5 text-sm text-slate-500">No booking activity yet.</div>
                        @endforelse
                    </div>
                </article>

                <article class="dashboard-card p-5 sm:p-6">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <h2 class="text-lg font-semibold text-slate-950">Notifications</h2>
                            <p class="mt-1 text-sm text-slate-500">Operational alerts and booking updates.</p>
                        </div>
                        <a href="{{ route('operations.notifications') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700">View center</a>
                    </div>
                    <div class="mt-5 space-y-3">
                        @forelse ($recentNotifications as $notification)
                            <div class="rounded-2xl border border-slate-200 px-4 py-4">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="font-semibold text-slate-900">{{ $notification->title }}</p>
                                        <p class="mt-1 text-sm text-slate-500">{{ $notification->message }}</p>
                                    </div>
                                    <x-dashboard.status-badge :status="$notification->type" />
                                </div>
                            </div>
                        @empty
                            <div class="rounded-2xl bg-slate-50 px-4 py-5 text-sm text-slate-500">No notifications have been recorded yet.</div>
                        @endforelse
                    </div>
                </article>
            </div>
        </div>

        <div class="space-y-6">
            <article class="dashboard-card p-5 sm:p-6">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-950">Popular courts</h2>
                        <p class="mt-1 text-sm text-slate-500">Top venues by booking activity.</p>
                    </div>
                    @if ($canManageCourts)
                        <a href="{{ route('courts.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700">Manage</a>
                    @endif
                </div>
                <div class="mt-5 space-y-3">
                    @forelse ($topCourts as $court)
                        <div class="rounded-2xl border border-slate-200 px-4 py-4">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <p class="font-semibold text-slate-900">{{ $court->name }}</p>
                                    <p class="mt-1 text-sm text-slate-500">{{ $court->location }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-bold text-slate-950">{{ $court->bookings_count }}</p>
                                    <p class="text-xs uppercase tracking-[0.18em] text-slate-400">Bookings</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-2xl bg-slate-50 px-4 py-5 text-sm text-slate-500">Court analytics will appear after booking activity starts.</div>
                    @endforelse
                </div>
            </article>

            <article class="dashboard-card p-5 sm:p-6">
                <div>
                    <h2 class="text-lg font-semibold text-slate-950">Peak booking hours</h2>
                    <p class="mt-1 text-sm text-slate-500">Readable slot demand without heavy chart libraries.</p>
                </div>
                <div class="mt-5 space-y-3">
                    @php $maxHours = max(1, (int) $peakHours->max('total')); @endphp
                    @forelse ($peakHours as $hour)
                        <div>
                            <div class="mb-2 flex items-center justify-between gap-3 text-sm">
                                <span class="font-medium text-slate-700">{{ $hour['label'] }}</span>
                                <span class="text-slate-500">{{ $hour['total'] }} bookings</span>
                            </div>
                            <div class="h-2.5 rounded-full bg-slate-100">
                                <div class="h-2.5 rounded-full bg-emerald-500" style="width: {{ max(8, ($hour['total'] / $maxHours) * 100) }}%"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">Peak hours will appear once booking data is available.</p>
                    @endforelse
                </div>
            </article>

            <article class="dashboard-card p-5 sm:p-6">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-950">Recommendations</h2>
                        <p class="mt-1 text-sm text-slate-500">Role-aware insight cards and recommendation feed.</p>
                    </div>
                    <a href="{{ route('operations.reports') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700">Reports</a>
                </div>
                <div class="mt-5 space-y-3">
                    @forelse ($recommendations as $recommendation)
                        <div class="rounded-2xl border border-slate-200 px-4 py-4">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="font-semibold text-slate-900">{{ $recommendation->court->name }}</p>
                                    <p class="mt-1 text-sm text-slate-500">{{ $recommendation->court->location }}</p>
                                </div>
                                <div class="rounded-xl bg-blue-50 px-3 py-2 text-sm font-semibold text-blue-700">
                                    {{ number_format((float) $recommendation->similarity_score, 1) }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-2xl bg-slate-50 px-4 py-5 text-sm text-slate-500">Recommendation data will appear after user activity starts.</div>
                    @endforelse
                </div>
            </article>
        </div>
    </section>
</x-dashboard.layout>
