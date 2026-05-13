<x-dashboard.layout
    :title="'Schedules | '.config('app.name')"
    heading="Schedule Management"
    description="Responsive weekly slot management with clear availability states for quick operational updates."
>
    <section class="grid gap-6">
        @forelse ($courts as $court)
            <article class="dashboard-card p-5 sm:p-6">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-950">{{ $court->name }}</h2>
                        <p class="mt-1 text-sm text-slate-500">{{ $court->location }}</p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <span class="dashboard-filter-chip">{{ $court->today_bookings_count }} bookings today</span>
                        <span class="dashboard-filter-chip">Calendar View</span>
                    </div>
                </div>

                <div class="mt-6 grid gap-3 md:grid-cols-2 xl:grid-cols-7">
                    @foreach ($calendarDays as $day)
                        @php $schedule = $court->schedules->firstWhere('day_of_week', $day['value']); @endphp
                        <div class="rounded-2xl border border-slate-200 p-4">
                            <div class="flex items-center justify-between gap-2">
                                <p class="font-semibold text-slate-900">{{ $day['label'] }}</p>
                                <x-dashboard.status-badge :status="$schedule?->is_open ? 'available' : 'inactive'" />
                            </div>
                            <div class="mt-4 space-y-2">
                                <div class="rounded-xl bg-emerald-50 px-3 py-2 text-xs font-medium text-emerald-700">Available</div>
                                <div class="rounded-xl bg-rose-50 px-3 py-2 text-xs font-medium text-rose-700">Booked</div>
                                <div class="rounded-xl bg-slate-100 px-3 py-2 text-xs font-medium text-slate-500">Inactive</div>
                            </div>
                            <p class="mt-4 text-sm text-slate-600">
                                {{ $schedule?->is_open ? $schedule->open_time.' - '.$schedule->close_time : 'Court closed' }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </article>
        @empty
            <x-dashboard.empty-state title="No schedules available" description="Create a court first to begin operational schedule planning." />
        @endforelse
    </section>
</x-dashboard.layout>
