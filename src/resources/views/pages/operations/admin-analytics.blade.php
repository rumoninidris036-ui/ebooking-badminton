<x-dashboard.layout
    :title="'System Analytics | '.config('app.name')"
    heading="System Analytics"
    description="Platform-wide analytics for admins with lightweight visual summaries and operational readability."
>
    <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        @foreach ($cards as $card)
            <x-dashboard.stat-card :label="$card['label']" :value="$card['value']" meta="Platform scope" tone="primary" />
        @endforeach
    </section>

    <section class="mt-6 grid gap-6 xl:grid-cols-[1.05fr_0.95fr]">
        <article class="dashboard-card p-5 sm:p-6">
            <h2 class="text-lg font-semibold text-slate-950">Peak booking hours</h2>
            <div class="mt-5 space-y-4">
                @php $maxHours = max(1, (int) $peakHours->max('total')); @endphp
                @foreach ($peakHours as $hour)
                    <div>
                        <div class="mb-2 flex items-center justify-between gap-3 text-sm">
                            <span class="font-medium text-slate-700">{{ $hour['hour_label'] }}</span>
                            <span class="text-slate-500">{{ $hour['total'] }}</span>
                        </div>
                        <div class="h-3 rounded-full bg-slate-100">
                            <div class="h-3 rounded-full bg-blue-600" style="width: {{ max(8, ($hour['total'] / $maxHours) * 100) }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </article>

        <article class="dashboard-card p-5 sm:p-6">
            <h2 class="text-lg font-semibold text-slate-950">Popular courts</h2>
            <div class="mt-5 space-y-3">
                @foreach ($courts as $court)
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
                @endforeach
            </div>
        </article>
    </section>
</x-dashboard.layout>
