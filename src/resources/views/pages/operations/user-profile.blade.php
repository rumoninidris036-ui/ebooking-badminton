<x-layouts.app :title="'Profile Settings | '.config('app.name')">
    <main class="px-4 py-6 sm:px-6">
        <div class="mx-auto flex max-w-5xl flex-col gap-6">
            <section class="glass-panel p-5 sm:p-6">
                <x-ui.badge tone="success">Profile Settings</x-ui.badge>
                <h1 class="mt-3 text-2xl font-black tracking-tight text-[var(--color-ink-950)]">Pengaturan akun user</h1>
                <p class="mt-2 text-sm leading-7 text-slate-600">Halaman profile user tetap dipertahankan dalam pengalaman mobile-first dan tidak masuk ke dashboard admin/owner.</p>
                <div class="mt-4">
                    <x-user.nav />
                </div>
            </section>

            <section class="grid gap-6 lg:grid-cols-[0.92fr_1.08fr]">
                <article class="glass-panel px-5 py-5">
                    <h2 class="text-lg font-bold text-[var(--color-ink-950)]">Informasi akun</h2>
                    <dl class="mt-5 space-y-4 text-sm">
                        <div class="flex items-center justify-between gap-3">
                            <dt class="text-slate-500">Nama</dt>
                            <dd class="font-semibold text-slate-900">{{ $user->name }}</dd>
                        </div>
                        <div class="flex items-center justify-between gap-3">
                            <dt class="text-slate-500">Email</dt>
                            <dd class="font-semibold text-slate-900">{{ $user->email }}</dd>
                        </div>
                        <div class="flex items-center justify-between gap-3">
                            <dt class="text-slate-500">Role</dt>
                            <dd class="font-semibold capitalize text-slate-900">{{ $user->role }}</dd>
                        </div>
                        <div class="flex items-center justify-between gap-3">
                            <dt class="text-slate-500">Telepon</dt>
                            <dd class="font-semibold text-slate-900">{{ $user->phone ?: 'Belum diisi' }}</dd>
                        </div>
                        <div class="flex items-center justify-between gap-3">
                            <dt class="text-slate-500">Alamat</dt>
                            <dd class="font-semibold text-slate-900">{{ $user->address ?: 'Belum diisi' }}</dd>
                        </div>
                    </dl>
                </article>

                <div class="space-y-6">
                    <article class="glass-panel px-5 py-5">
                        <h2 class="text-lg font-bold text-[var(--color-ink-950)]">Preferensi notifikasi</h2>
                        <div class="mt-4 rounded-2xl bg-slate-50 px-4 py-4">
                            <p class="text-sm text-slate-600">Anda memiliki <span class="font-semibold text-slate-900">{{ $unreadNotifications }}</span> notifikasi yang belum dibaca.</p>
                        </div>
                    </article>

                    <article class="glass-panel px-5 py-5">
                        <h2 class="text-lg font-bold text-[var(--color-ink-950)]">Booking terbaru</h2>
                        <div class="mt-4 space-y-3">
                            @forelse ($recentBookings as $booking)
                                <div class="rounded-2xl border border-slate-200 px-4 py-4">
                                    <p class="font-semibold text-slate-900">{{ $booking->court->name }}</p>
                                    <p class="mt-1 text-sm text-slate-500">{{ $booking->booking_date->format('d M Y') }} • {{ $booking->court->location }}</p>
                                </div>
                            @empty
                                <p class="text-sm text-slate-500">Belum ada booking terbaru.</p>
                            @endforelse
                        </div>
                    </article>
                </div>
            </section>
        </div>
    </main>
</x-layouts.app>
