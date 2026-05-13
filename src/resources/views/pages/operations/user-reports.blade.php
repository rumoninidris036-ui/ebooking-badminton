<x-layouts.app :title="'Reports | '.config('app.name')">
    <main class="px-4 py-6 sm:px-6">
        <div class="mx-auto flex max-w-5xl flex-col gap-6">
            <section class="glass-panel p-5 sm:p-6">
                <x-ui.badge tone="primary">Reports</x-ui.badge>
                <h1 class="mt-3 text-2xl font-black tracking-tight text-[var(--color-ink-950)]">Ringkasan aktivitas akun saya</h1>
                <p class="mt-2 text-sm leading-7 text-slate-600">Laporan user dibuat lebih sederhana: fokus ke jumlah booking, status, pengeluaran, dan rekomendasi venue.</p>
                <div class="mt-4">
                    <x-user.nav />
                </div>
            </section>

            <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <div class="glass-panel px-5 py-5">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Total booking</p>
                    <p class="mt-3 text-3xl font-black text-[var(--color-ink-950)]">{{ $summary['total_bookings'] }}</p>
                </div>
                <div class="glass-panel px-5 py-5">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Finished</p>
                    <p class="mt-3 text-3xl font-black text-[var(--color-ink-950)]">{{ $summary['finished_bookings'] }}</p>
                </div>
                <div class="glass-panel px-5 py-5">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Active bookings</p>
                    <p class="mt-3 text-3xl font-black text-[var(--color-ink-950)]">{{ $summary['active_bookings'] }}</p>
                </div>
                <div class="glass-panel px-5 py-5">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Total spending</p>
                    <p class="mt-3 text-3xl font-black text-[var(--color-ink-950)]">Rp {{ number_format($summary['spending'], 0, ',', '.') }}</p>
                </div>
            </section>

            <section class="grid gap-6 lg:grid-cols-[0.9fr_1.1fr]">
                <article class="glass-panel px-5 py-5">
                    <h2 class="text-lg font-bold text-[var(--color-ink-950)]">Status booking</h2>
                    <div class="mt-4 space-y-4">
                        @php $maxStatus = max(1, $statusBreakdown->max('count')); @endphp
                        @foreach ($statusBreakdown as $item)
                            <div>
                                <div class="mb-2 flex items-center justify-between gap-3 text-sm">
                                    <span class="font-medium text-slate-700">{{ $item['label'] }}</span>
                                    <span class="text-slate-500">{{ $item['count'] }}</span>
                                </div>
                                <div class="h-3 rounded-full bg-slate-100">
                                    <div class="h-3 rounded-full bg-[var(--color-brand-500)]" style="width: {{ max(8, ($item['count'] / $maxStatus) * 100) }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </article>

                <article class="glass-panel px-5 py-5">
                    <h2 class="text-lg font-bold text-[var(--color-ink-950)]">Recommended courts</h2>
                    <div class="mt-4 space-y-3">
                        @forelse ($recommendedCourts as $recommendation)
                            <div class="rounded-2xl border border-slate-200 px-4 py-4">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="font-semibold text-slate-900">{{ $recommendation->court->name }}</p>
                                        <p class="mt-1 text-sm text-slate-500">{{ $recommendation->court->location }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-bold text-[var(--color-ink-950)]">{{ number_format((float) $recommendation->similarity_score, 1) }}</p>
                                        <p class="text-xs text-slate-400">match score</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">Belum ada rekomendasi yang tersedia.</p>
                        @endforelse
                    </div>
                </article>
            </section>
        </div>
    </main>
</x-layouts.app>
