<x-layouts.app :title="'My Bookings | '.config('app.name')">
    <main class="px-4 py-6 sm:px-6">
        <div class="mx-auto flex max-w-5xl flex-col gap-6">
            <section class="glass-panel p-5 sm:p-6">
                <div class="space-y-4">
                    <div>
                        <x-ui.badge tone="primary">My Bookings</x-ui.badge>
                        <h1 class="mt-3 text-2xl font-black tracking-tight text-[var(--color-ink-950)]">Riwayat booking yang tetap mobile-friendly</h1>
                        <p class="mt-2 text-sm leading-7 text-slate-600">Semua aktivitas booking user ditampilkan dalam kartu yang lebih mudah dipindai di ponsel, tanpa masuk ke dashboard admin/owner.</p>
                    </div>
                    <x-user.nav />
                </div>
            </section>

            @if (session('status'))
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                    {{ session('status') }}
                </div>
            @endif

            <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <div class="glass-panel px-5 py-5">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Total booking</p>
                    <p class="mt-3 text-3xl font-black text-[var(--color-ink-950)]">{{ $bookings->total() }}</p>
                </div>
                <div class="glass-panel px-5 py-5">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Pending</p>
                    <p class="mt-3 text-3xl font-black text-[var(--color-ink-950)]">{{ $bookings->getCollection()->where('status', 'pending')->count() }}</p>
                </div>
                <div class="glass-panel px-5 py-5">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Paid</p>
                    <p class="mt-3 text-3xl font-black text-[var(--color-ink-950)]">{{ $bookings->getCollection()->where('status', 'paid')->count() }}</p>
                </div>
                <div class="glass-panel px-5 py-5">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Finished</p>
                    <p class="mt-3 text-3xl font-black text-[var(--color-ink-950)]">{{ $bookings->getCollection()->where('status', 'finished')->count() }}</p>
                </div>
            </section>

            <section class="grid gap-4">
                @forelse ($bookings as $booking)
                    <article class="glass-panel px-5 py-5">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                            <div>
                                <div class="flex flex-wrap items-center gap-2">
                                    <h2 class="text-xl font-bold text-[var(--color-ink-950)]">{{ $booking->court->name }}</h2>
                                    <x-dashboard.status-badge :status="$booking->status === 'finished' ? 'completed' : $booking->status" />
                                </div>
                                <p class="mt-2 text-sm text-slate-600">{{ $booking->court->location }}</p>
                                <p class="mt-1 text-xs uppercase tracking-[0.18em] text-slate-400">{{ $booking->booking_code }}</p>
                            </div>
                            <div class="text-left sm:text-right">
                                <p class="text-sm font-semibold text-slate-900">{{ $booking->booking_date->format('d M Y') }}</p>
                                <p class="mt-1 text-sm text-slate-500">{{ substr($booking->start_time, 0, 5) }} - {{ substr($booking->end_time, 0, 5) }}</p>
                                <p class="mt-2 text-lg font-black text-[var(--color-ink-950)]">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</p>
                            </div>
                        </div>

                        @if ($booking->notes)
                            <div class="mt-4 rounded-2xl bg-slate-50 px-4 py-3 text-sm text-slate-600">
                                {{ $booking->notes }}
                            </div>
                        @endif

                        @if ($booking->cancellation?->cancellation_reason)
                            <div class="mt-4 rounded-2xl bg-rose-50 px-4 py-3 text-sm text-rose-700">
                                Alasan pembatalan: {{ $booking->cancellation->cancellation_reason }}
                            </div>
                        @endif

                        @if (in_array($booking->status, ['pending', 'paid'], true))
                            <form method="POST" action="{{ route('bookings.cancel', $booking->id) }}" class="mt-4 grid gap-3 sm:grid-cols-[1fr_auto]">
                                @csrf
                                <input type="text" name="cancellation_reason" required placeholder="Tulis alasan pembatalan" class="rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 outline-none">
                                <button type="submit" class="rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                                    Cancel Booking
                                </button>
                            </form>
                        @endif
                    </article>
                @empty
                    <div class="glass-panel px-6 py-10 text-center">
                        <p class="text-lg font-semibold text-[var(--color-ink-950)]">Belum ada booking</p>
                        <p class="mt-2 text-sm text-slate-500">Pilih venue dan buat booking pertama Anda.</p>
                    </div>
                @endforelse
            </section>

            <div>
                {{ $bookings->links() }}
            </div>
        </div>
    </main>
</x-layouts.app>
