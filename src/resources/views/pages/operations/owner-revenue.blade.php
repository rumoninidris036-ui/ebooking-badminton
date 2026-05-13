<x-dashboard.layout
    :title="'Revenue | '.config('app.name')"
    heading="Revenue Overview"
    description="Owner-focused revenue visibility with booking outcomes and court-by-court performance blocks."
>
    <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <x-dashboard.stat-card label="Monthly Revenue" :value="'Rp '.number_format($summary['monthly'], 0, ',', '.')" meta="Current month" tone="success" />
        <x-dashboard.stat-card label="Paid Bookings" :value="$summary['paid']" meta="Ready revenue" tone="primary" />
        <x-dashboard.stat-card label="Completed" :value="$summary['completed']" meta="Finished sessions" tone="success" />
        <x-dashboard.stat-card label="Pending" :value="$summary['pending']" meta="Need confirmation" tone="warning" />
    </section>

    <section class="mt-6 grid gap-4 md:grid-cols-2">
        @foreach ($courts as $court)
            <article class="dashboard-card p-5">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-950">{{ $court->name }}</h2>
                        <p class="mt-1 text-sm text-slate-500">{{ $court->location }}</p>
                    </div>
                    <div class="rounded-xl bg-blue-50 px-3 py-2 text-sm font-semibold text-blue-700">
                        {{ $court->bookings_count }} bookings
                    </div>
                </div>
                <p class="mt-4 text-sm text-slate-500">Use this section to compare venue performance and prioritize operational improvements.</p>
            </article>
        @endforeach
    </section>
</x-dashboard.layout>
