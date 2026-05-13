<x-layouts.app :title="'Login | '.config('app.name')">
    <main class="shell-container py-6 sm:py-10">
        <div class="grid gap-6 lg:grid-cols-[0.92fr_1.08fr] lg:items-stretch">
            <section class="glass-panel overflow-hidden bg-[linear-gradient(145deg,#122e23,#1f4e39_52%,#2d6a4f)] text-white">
                <div class="flex h-full flex-col justify-between gap-10 px-6 py-8 sm:px-8 sm:py-10">
                    <div class="space-y-4">
                        <span class="inline-flex items-center rounded-full border border-white/15 bg-white/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.24em] text-emerald-100">
                            Satu login untuk semua role
                        </span>
                        <h1 class="max-w-lg text-4xl font-black leading-tight tracking-tight">Masuk sekali, lalu sistem arahkan sesuai peran akun Anda.</h1>
                        <p class="max-w-xl text-sm leading-7 text-slate-200">
                            Tidak perlu halaman login terpisah untuk admin, user, atau pemilik lapangan. Form ini menjadi pintu masuk bersama agar pengalaman terasa lebih sederhana.
                        </p>
                    </div>

                    <div class="grid gap-3 sm:grid-cols-2">
                        <div class="rounded-[1.7rem] border border-white/12 bg-white/10 px-4 py-4 backdrop-blur">
                            <p class="text-xs uppercase tracking-[0.18em] text-emerald-100">Untuk user</p>
                            <p class="mt-2 text-sm font-semibold">Cari venue, cek jadwal, dan booking slot favorit.</p>
                        </div>
                        <div class="rounded-[1.7rem] border border-white/12 bg-slate-950/20 px-4 py-4 backdrop-blur">
                            <p class="text-xs uppercase tracking-[0.18em] text-emerald-100">Untuk owner/admin</p>
                            <p class="mt-2 text-sm font-semibold">Kelola lapangan dan pantau reservasi dari dashboard yang sama.</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="glass-panel px-5 py-6 sm:px-8 sm:py-8">
                <div class="max-w-lg">
                    <div class="space-y-3">
                        <x-ui.badge tone="success" class="bg-emerald-100 text-[var(--color-brand-600)]">Unified Access</x-ui.badge>
                        <h2 class="text-3xl font-black tracking-tight text-[var(--color-ink-950)]">Masuk ke akun Anda</h2>
                        <p class="text-sm leading-7 text-slate-600">
                            Gunakan email dan password yang sama untuk masuk ke alur pengguna biasa maupun area operasional sesuai role akun.
                        </p>
                        <p class="text-sm font-medium text-slate-500">Sign in to manage bookings</p>
                    </div>

                    @if (session('status'))
                        <div class="mt-5 rounded-[1.4rem] bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login.store') }}" class="mt-6 space-y-4">
                        @csrf

                        <x-ui.input
                            label="Email"
                            name="email"
                            type="email"
                            autocomplete="email"
                            placeholder="nama@contoh.com"
                            class="rounded-[1.2rem] border-slate-200 bg-white/90"
                        />

                        <x-ui.input
                            label="Password"
                            name="password"
                            type="password"
                            autocomplete="current-password"
                            placeholder="Masukkan password"
                            class="rounded-[1.2rem] border-slate-200 bg-white/90"
                        />

                        <label class="flex items-center gap-3 rounded-[1.2rem] bg-slate-50 px-4 py-3 text-sm text-slate-600">
                            <input type="checkbox" name="remember" value="1" class="h-4 w-4 rounded border-slate-300 text-[var(--color-brand-500)] focus:ring-[var(--color-brand-500)]">
                            Tetap masuk di perangkat ini
                        </label>

                        <x-ui.button type="submit" class="w-full rounded-[1.2rem] bg-[var(--color-brand-500)] hover:bg-[var(--color-brand-600)] focus-visible:outline-[var(--color-brand-500)]">
                            Masuk Sekarang
                        </x-ui.button>
                    </form>

                    <div class="mt-5 grid gap-3 sm:grid-cols-2">
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-[1.2rem] border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                            Buat Akun
                        </a>
                        <a href="{{ route('landing') }}" class="inline-flex items-center justify-center rounded-[1.2rem] border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                            Kembali ke Beranda
                        </a>
                    </div>
                </div>
            </section>
        </div>
    </main>
</x-layouts.app>
