<x-layouts.app :title="'Schedules | '.config('app.name')">
    <main class="px-4 py-6 sm:px-6">
        <div class="mx-auto flex max-w-5xl flex-col gap-6">
            <section class="glass-panel p-5 sm:p-6">
                <x-ui.badge tone="success">Schedules</x-ui.badge>
                <h1 class="mt-3 text-2xl font-black tracking-tight text-[var(--color-ink-950)]">Jadwal bermain saya</h1>
                <p class="mt-2 text-sm leading-7 text-slate-600">Halaman ini khusus user untuk melihat jadwal booking mendatang dengan alur yang tetap ringan di mobile.</p>
                <div class="mt-4">
                    <x-user.nav />
                </div>
            </section>

            <section class="grid gap-4">
                @forelse ($upcomingBookings as $booking)
                    <article class="glass-panel px-5 py-5">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-lg font-bold text-[var(--color-ink-950)]">{{ $booking->court->name }}</p>
                                <p class="mt-1 text-sm text-slate-600">{{ $booking->court->location }}</p>
                                <p class="mt-3 text-sm font-semibold text-slate-900">{{ $booking->booking_date->format('l, d M Y') }}</p>
                                <p class="mt-1 text-sm text-slate-500">{{ substr($booking->start_time, 0, 5) }} - {{ substr($booking->end_time, 0, 5) }}</p>
                            </div>
                            <x-dashboard.status-badge :status="$booking->status === 'finished' ? 'completed' : $booking->status" />
                        </div>
                    </article>
                @empty
                    <div class="glass-panel px-6 py-10 text-center">
                        <p class="text-lg font-semibold text-[var(--color-ink-950)]">Belum ada jadwal bermain</p>
                        <p class="mt-2 text-sm text-slate-500">Jadwal akan muncul setelah Anda membuat booking.</p>
                    </div>
                @endforelse
            </section>

            <div>
                {{ $upcomingBookings->links() }}
            </div>
        </div>
    </main>
</x-layouts.app>
