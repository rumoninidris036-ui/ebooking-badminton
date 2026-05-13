<x-layouts.app :title="'Dashboard | '.config('app.name')">
    <main class="px-4 py-6 sm:px-6">
        <div class="mx-auto flex max-w-5xl flex-col gap-6">
            <section class="rounded-3xl bg-white p-5 shadow-md ring-1 ring-slate-200 sm:p-8">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                    <div class="space-y-3">
                        <x-ui.badge tone="success">Operations Center</x-ui.badge>
                        <div class="space-y-2">
                            <h1 class="text-2xl font-bold tracking-tight text-slate-900">
                                Welcome, {{ $user->name }}
                            </h1>
                            <p class="text-sm leading-6 text-slate-600">
                                Track reservations, review recommendations, and keep badminton field operations moving from one place.
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-col gap-3 sm:items-end">
                        <a href="{{ route('bookings.create') }}" class="inline-flex items-center justify-center rounded-xl bg-green-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-green-700">
                            Book a court
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-ui.button type="submit" variant="secondary">
                                Sign out
                            </x-ui.button>
                        </form>
                    </div>
                </div>

                @if (session('status'))
                    <div class="mt-4 rounded-2xl bg-green-50 px-4 py-3 text-sm text-green-700">
                        {{ session('status') }}
                    </div>
                @endif
            </section>

            <section class="grid gap-4 sm:grid-cols-2">
                <x-ui.feature-card
                    title="Account"
                    description="Your account controls what you can book, manage, review, and monitor across the platform."
                    tone="primary"
                >
                    <dl class="space-y-2 text-sm text-slate-600">
                        <div class="flex items-center justify-between gap-3">
                            <dt>Name</dt>
                            <dd class="font-semibold text-slate-900">{{ $user->name }}</dd>
                        </div>
                        <div class="flex items-center justify-between gap-3">
                            <dt>Email</dt>
                            <dd class="font-semibold text-slate-900">{{ $user->email }}</dd>
                        </div>
                        <div class="flex items-center justify-between gap-3">
                            <dt>Role</dt>
                            <dd class="font-semibold capitalize text-slate-900">{{ $user->role }}</dd>
                        </div>
                    </dl>
                </x-ui.feature-card>

                <x-ui.feature-card
                    title="Booking Operations"
                    description="Booking flow now supports availability checks, reservation status tracking, cancellation, and owner confirmation workflow."
                    tone="success"
                >
                    <div class="rounded-2xl bg-slate-50 px-4 py-3 font-mono text-xs text-slate-600">
                        GET /api/bookings/availability<br>
                        GET /api/bookings<br>
                        POST /api/bookings<br>
                        GET /api/bookings/{booking}<br>
                        POST /api/bookings/{booking}/cancel
                    </div>
                </x-ui.feature-card>
            </section>

            <section class="grid gap-4 lg:grid-cols-2">
                <div class="rounded-3xl bg-white p-5 shadow-sm ring-1 ring-slate-200 sm:p-8">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <h2 class="text-xl font-semibold text-slate-900">Recent bookings</h2>
                            <p class="mt-1 text-sm text-slate-600">Latest reservation activity visible for your role.</p>
                        </div>
                        <a href="{{ route('bookings.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700">Open all</a>
                    </div>

                    <div class="mt-5 space-y-3">
                        @forelse ($recentBookings as $booking)
                            <div class="rounded-2xl border border-slate-200 px-4 py-3">
                                <div class="flex items-center justify-between gap-3">
                                    <div>
                                        <p class="font-semibold text-slate-900">{{ $booking->court->name }}</p>
                                        <p class="text-sm text-slate-600">{{ $booking->booking_date->format('d M Y') }} • {{ substr($booking->start_time, 0, 5) }}</p>
                                    </div>
                                    <x-ui.badge :tone="match($booking->status) { 'paid', 'finished' => 'success', 'canceled' => 'danger', default => 'primary' }">
                                        {{ ucfirst($booking->status) }}
                                    </x-ui.badge>
                                </div>
                            </div>
                        @empty
                            <div class="rounded-2xl bg-slate-50 px-4 py-5 text-sm text-slate-600">
                                No booking activity yet.
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="rounded-3xl bg-white p-5 shadow-sm ring-1 ring-slate-200 sm:p-8">
                    <div>
                        <h2 class="text-xl font-semibold text-slate-900">Recommended courts</h2>
                        <p class="mt-1 text-sm text-slate-600">Generated from location, price pattern, facilities, rating, and popularity.</p>
                    </div>

                    <div class="mt-5 space-y-3">
                        @forelse ($recommendations as $recommendation)
                            <div class="rounded-2xl border border-slate-200 px-4 py-3">
                                <div class="flex items-center justify-between gap-3">
                                    <div>
                                        <p class="font-semibold text-slate-900">{{ $recommendation->court->name }}</p>
                                        <p class="text-sm text-slate-600">{{ $recommendation->court->location }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-semibold text-slate-900">{{ number_format((float) $recommendation->similarity_score, 1) }}</p>
                                        <p class="text-xs text-slate-500">match score</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="rounded-2xl bg-slate-50 px-4 py-5 text-sm text-slate-600">
                                Recommendation data will appear after activity starts.
                            </div>
                        @endforelse
                    </div>
                </div>
            </section>
        </div>
    </main>
</x-layouts.app>
