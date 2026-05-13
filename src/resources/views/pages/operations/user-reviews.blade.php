<x-layouts.app :title="'Reviews | '.config('app.name')">
    <main class="px-4 py-6 sm:px-6">
        <div class="mx-auto flex max-w-5xl flex-col gap-6">
            <section class="glass-panel p-5 sm:p-6">
                <x-ui.badge tone="primary">Reviews</x-ui.badge>
                <h1 class="mt-3 text-2xl font-black tracking-tight text-[var(--color-ink-950)]">Ulasan dan venue yang pernah saya selesaikan</h1>
                <p class="mt-2 text-sm leading-7 text-slate-600">User tetap melihat halaman review dalam pola yang konsisten dengan dashboard user, bukan panel admin.</p>
                <div class="mt-4">
                    <x-user.nav />
                </div>
            </section>

            <section class="grid gap-6 lg:grid-cols-[1.1fr_0.9fr]">
                <div class="space-y-4">
                    @forelse ($reviews as $review)
                        <article class="glass-panel px-5 py-5">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="text-lg font-bold text-[var(--color-ink-950)]">{{ $review->court->name }}</p>
                                    <p class="mt-1 text-sm text-slate-500">{{ $review->court->location }}</p>
                                </div>
                                <span class="rounded-full bg-amber-100 px-3 py-1 text-sm font-semibold text-amber-700">{{ $review->rating }}/5</span>
                            </div>
                            <p class="mt-4 text-sm leading-7 text-slate-600">{{ $review->comment }}</p>
                        </article>
                    @empty
                        <div class="glass-panel px-6 py-10 text-center">
                            <p class="text-lg font-semibold text-[var(--color-ink-950)]">Belum ada ulasan</p>
                            <p class="mt-2 text-sm text-slate-500">Ulasan Anda akan muncul setelah sesi selesai dan Anda mengirim review.</p>
                        </div>
                    @endforelse
                </div>

                <aside class="glass-panel px-5 py-5">
                    <h2 class="text-lg font-bold text-[var(--color-ink-950)]">Booking selesai yang siap direview</h2>
                    <div class="mt-4 space-y-3">
                        @forelse ($completedBookings as $booking)
                            <div class="rounded-2xl border border-slate-200 px-4 py-4">
                                <p class="font-semibold text-slate-900">{{ $booking->court->name }}</p>
                                <p class="mt-1 text-sm text-slate-500">{{ $booking->booking_date->format('d M Y') }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">Belum ada booking selesai yang bisa direview.</p>
                        @endforelse
                    </div>
                </aside>
            </section>

            <div>
                {{ $reviews->links() }}
            </div>
        </div>
    </main>
</x-layouts.app>
