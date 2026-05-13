<x-dashboard.layout
    :title="'Reports | '.config('app.name')"
    heading="Reports & Analytics"
    description="Readable reporting cards and lightweight analytics blocks for operational and revenue decision-making."
>
    <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <x-dashboard.stat-card label="Total Bookings" :value="$summary['total_bookings']" meta="All visible booking records" tone="primary" />
        <x-dashboard.stat-card label="Completed" :value="$summary['completed_bookings']" meta="Finished sessions" tone="success" />
        <x-dashboard.stat-card label="Conversion" :value="$summary['conversion'].'%'" meta="Paid and finished share" tone="warning" />
        <x-dashboard.stat-card label="Revenue" :value="'Rp '.number_format($summary['revenue'], 0, ',', '.')" meta="Recognized income" tone="success" />
    </section>

    <section class="mt-6 grid gap-6 xl:grid-cols-[0.9fr_1.1fr]">
        <article class="dashboard-card p-5 sm:p-6">
            <h2 class="text-lg font-semibold text-slate-950">Status breakdown</h2>
            <p class="mt-1 text-sm text-slate-500">Operational state overview for current visible bookings.</p>
            @php $maxStatus = max(1, $statusBreakdown->max('count')); @endphp
            <div class="mt-5 space-y-4">
                @foreach ($statusBreakdown as $item)
                    <div>
                        <div class="mb-2 flex items-center justify-between gap-3 text-sm">
                            <span class="font-medium text-slate-700">{{ $item['label'] }}</span>
                            <span class="text-slate-500">{{ $item['count'] }}</span>
                        </div>
                        <div class="h-3 rounded-full bg-slate-100">
                            <div class="h-3 rounded-full bg-blue-600" style="width: {{ max(8, ($item['count'] / $maxStatus) * 100) }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </article>

        <article class="dashboard-card p-5 sm:p-6">
            <h2 class="text-lg font-semibold text-slate-950">Top courts</h2>
            <p class="mt-1 text-sm text-slate-500">Popular venues by booking count.</p>
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
                    <p class="text-sm text-slate-500">No court analytics data is available yet.</p>
                @endforelse
            </div>
        </article>
    </section>
</x-dashboard.layout>
