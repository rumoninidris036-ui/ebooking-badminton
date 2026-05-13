<x-dashboard.layout
    :title="'Courts | '.config('app.name')"
    heading="Court Management"
    description="Manage badminton courts, availability, pricing, and operational readiness inside the unified owner and admin dashboard."
>
    <x-slot:actions>
        <a href="{{ route('courts.create') }}" class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">
            Add Court
        </a>
    </x-slot:actions>

    @php
        $cards = [
            ['label' => 'Total Courts', 'value' => $courts->total(), 'meta' => 'Managed venues', 'tone' => 'primary'],
            ['label' => 'Active Courts', 'value' => $courts->getCollection()->where('is_active', true)->count(), 'meta' => 'Publicly visible', 'tone' => 'success'],
            ['label' => 'Inactive Courts', 'value' => $courts->getCollection()->where('is_active', false)->count(), 'meta' => 'Need attention', 'tone' => 'warning'],
            ['label' => 'Average Price', 'value' => 'Rp '.number_format((float) $courts->getCollection()->avg('price_per_hour'), 0, ',', '.'), 'meta' => 'Per hour', 'tone' => 'primary'],
        ];
    @endphp

    @if (session('status'))
        <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
            {{ session('status') }}
        </div>
    @endif

    <p class="mb-6 text-sm font-medium text-slate-500">Manage badminton courts</p>

    <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        @foreach ($cards as $card)
            <x-dashboard.stat-card :label="$card['label']" :value="$card['value']" :meta="$card['meta']" :tone="$card['tone']" />
        @endforeach
    </section>

    <section class="mt-6">
        <x-dashboard.filter-bar>
            <div class="flex flex-wrap gap-2">
                <span class="dashboard-filter-chip">Pricing</span>
                <span class="dashboard-filter-chip">Availability</span>
                <span class="dashboard-filter-chip">Facilities</span>
                <span class="dashboard-filter-chip">Status</span>
            </div>
            <div class="text-sm text-slate-500">Manage badminton courts</div>
        </x-dashboard.filter-bar>
    </section>

    <section class="mt-6">
        @if ($courts->count() === 0)
            <x-dashboard.empty-state title="No courts yet" description="Create your first court to start defining schedules and pricing." />
        @else
            <div class="dashboard-table-wrap">
                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th>Court</th>
                            <th>Location</th>
                            <th>Price</th>
                            <th>Rating</th>
                            <th>Status</th>
                            <th>Availability</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($courts as $court)
                            <tr>
                                <td>
                                    <div class="flex items-start gap-3">
                                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-xs font-semibold text-slate-500">
                                            IMG
                                        </div>
                                        <div>
                                            <p class="font-semibold text-slate-900">{{ $court->name }}</p>
                                            <p class="mt-1 text-xs text-slate-500">{{ \Illuminate\Support\Str::limit($court->description, 64) }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $court->location }}</td>
                                <td class="font-semibold text-slate-900">Rp {{ number_format($court->price_per_hour, 0, ',', '.') }}</td>
                                <td>{{ number_format((float) $court->rating, 1) }}</td>
                                <td><x-dashboard.status-badge :status="$court->is_active ? 'active' : 'inactive'" /></td>
                                <td>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach ($court->schedules->take(3) as $schedule)
                                            <span class="rounded-full px-3 py-1 text-xs font-medium {{ $schedule->is_open ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                                                {{ \App\Services\CourtService::DAYS[$schedule->day_of_week] }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                                <td>
                                    <div class="flex flex-wrap justify-end gap-2">
                                        <a href="{{ route('courts.edit', $court) }}" class="rounded-xl border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                                            Edit
                                        </a>
                                        <a href="{{ route('operations.schedules') }}" class="rounded-xl border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                                            Manage Schedule
                                        </a>
                                        <form method="POST" action="{{ route('courts.destroy', $court) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-xl bg-rose-600 px-3 py-2 text-sm font-semibold text-white transition hover:bg-rose-700">
                                                Deactivate
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>

    <div class="mt-6">
        {{ $courts->links() }}
    </div>
</x-dashboard.layout>
