<x-layouts.app :title="'Notifications | '.config('app.name')">
    <main class="px-4 py-6 sm:px-6">
        <div class="mx-auto flex max-w-5xl flex-col gap-6">
            <section class="glass-panel p-5 sm:p-6">
                <x-ui.badge tone="success">Notifications</x-ui.badge>
                <h1 class="mt-3 text-2xl font-black tracking-tight text-[var(--color-ink-950)]">Pusat notifikasi user</h1>
                <p class="mt-2 text-sm leading-7 text-slate-600">Semua alert booking, pembayaran, dan update akun tetap ditampilkan dalam UI yang ramah mobile.</p>
                <div class="mt-4">
                    <x-user.nav />
                </div>
            </section>

            <section class="grid gap-4 sm:grid-cols-3">
                <div class="glass-panel px-5 py-5">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Unread</p>
                    <p class="mt-3 text-3xl font-black text-[var(--color-ink-950)]">{{ $summary['unread'] }}</p>
                </div>
                <div class="glass-panel px-5 py-5">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Booking</p>
                    <p class="mt-3 text-3xl font-black text-[var(--color-ink-950)]">{{ $summary['booking'] }}</p>
                </div>
                <div class="glass-panel px-5 py-5">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Payment</p>
                    <p class="mt-3 text-3xl font-black text-[var(--color-ink-950)]">{{ $summary['payment'] }}</p>
                </div>
            </section>

            <section class="grid gap-4">
                @forelse ($notifications as $notification)
                    <article class="glass-panel px-5 py-5">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-base font-bold text-[var(--color-ink-950)]">{{ $notification->title }}</p>
                                <p class="mt-2 text-sm leading-7 text-slate-600">{{ $notification->message }}</p>
                            </div>
                            <x-dashboard.status-badge :status="$notification->type" />
                        </div>
                        <p class="mt-3 text-xs uppercase tracking-[0.18em] text-slate-400">{{ $notification->created_at->diffForHumans() }}</p>
                    </article>
                @empty
                    <div class="glass-panel px-6 py-10 text-center">
                        <p class="text-lg font-semibold text-[var(--color-ink-950)]">Belum ada notifikasi</p>
                        <p class="mt-2 text-sm text-slate-500">Notifikasi booking dan pembayaran akan muncul di sini.</p>
                    </div>
                @endforelse
            </section>

            <div>
                {{ $notifications->links() }}
            </div>
        </div>
    </main>
</x-layouts.app>
