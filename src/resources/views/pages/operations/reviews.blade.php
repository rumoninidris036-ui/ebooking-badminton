<x-dashboard.layout
    :title="'Reviews | '.config('app.name')"
    heading="Review Management"
    description="Monitor customer sentiment, rating distribution, and venue feedback inside the same operational workspace."
>
    <section class="grid gap-4 md:grid-cols-3">
        <x-dashboard.stat-card label="Average Rating" :value="$ratingSummary['average']" meta="Current visible review average" tone="primary" />
        <x-dashboard.stat-card label="Total Reviews" :value="$ratingSummary['total']" meta="All submitted reviews" tone="success" />
        <x-dashboard.stat-card label="Positive Reviews" :value="$ratingSummary['positive']" meta="Rating 4 and above" tone="warning" />
    </section>

    <section class="mt-6 grid gap-4">
        @forelse ($reviews as $review)
            <article class="dashboard-card p-5">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-950">{{ $review->court->name }}</h2>
                        <p class="mt-1 text-sm text-slate-500">{{ $review->court->location }} • {{ $review->user->name }}</p>
                    </div>
                    <div class="rounded-xl bg-amber-50 px-3 py-2 text-sm font-semibold text-amber-700">
                        {{ $review->rating }}/5 stars
                    </div>
                </div>
                <p class="mt-4 text-sm leading-7 text-slate-600">{{ $review->comment }}</p>
            </article>
        @empty
            <x-dashboard.empty-state title="No reviews yet" description="Reviews will appear here after completed bookings receive feedback." />
        @endforelse
    </section>

    <div class="mt-6">
        {{ $reviews->links() }}
    </div>
</x-dashboard.layout>
