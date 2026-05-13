<x-layouts.app :title="'Register | '.config('app.name')">
    <main class="shell-container py-6 sm:py-10">
        <div class="grid gap-6 lg:grid-cols-[0.92fr_1.08fr] lg:items-stretch">
            <section class="glass-panel overflow-hidden bg-[linear-gradient(150deg,#163729,#255a42_55%,#7bc79f)] text-white">
                <div class="flex h-full flex-col justify-between gap-8 px-6 py-8 sm:px-8 sm:py-10">
                    <div class="space-y-4">
                        <span class="inline-flex items-center rounded-full border border-white/15 bg-white/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.24em] text-emerald-50">
                            Start your booking journey
                        </span>
                        <h1 class="max-w-lg text-4xl font-black leading-tight tracking-tight">Buat akun sekali untuk mulai booking dan mengelola aktivitas lapangan.</h1>
                        <p class="max-w-xl text-sm leading-7 text-slate-100">
                            Pendaftaran baru akan membuat akun dengan role pengguna standar. Jika nanti akun itu punya peran operasional, tampilan login tetap sama dan aksesnya disesuaikan oleh sistem.
                        </p>
                    </div>

                    <div class="grid gap-3 sm:grid-cols-3">
                        <div class="rounded-[1.6rem] border border-white/15 bg-white/10 px-4 py-4 backdrop-blur">
                            <p class="text-xs uppercase tracking-[0.18em] text-emerald-100">1</p>
                            <p class="mt-2 text-sm font-semibold">Daftar akun</p>
                        </div>
                        <div class="rounded-[1.6rem] border border-white/15 bg-white/10 px-4 py-4 backdrop-blur">
                            <p class="text-xs uppercase tracking-[0.18em] text-emerald-100">2</p>
                            <p class="mt-2 text-sm font-semibold">Pilih venue</p>
                        </div>
                        <div class="rounded-[1.6rem] border border-white/15 bg-slate-950/20 px-4 py-4 backdrop-blur">
                            <p class="text-xs uppercase tracking-[0.18em] text-emerald-100">3</p>
                            <p class="mt-2 text-sm font-semibold">Amankan jadwal</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="glass-panel px-5 py-6 sm:px-8 sm:py-8">
                <div class="max-w-lg">
                    <div class="space-y-3">
                        <x-ui.badge tone="primary" class="bg-emerald-100 text-[var(--color-brand-600)]">New Account</x-ui.badge>
                        <h2 class="text-3xl font-black tracking-tight text-[var(--color-ink-950)]">Buat akun baru</h2>
                        <p class="text-sm leading-7 text-slate-600">
                            Setelah terdaftar, Anda bisa langsung memakai login yang sama untuk menjelajah venue dan membuat booking.
                        </p>
                        <p class="text-sm font-medium text-slate-500">Create your e-booking account</p>
                    </div>

                    <form method="POST" action="{{ route('register.store') }}" class="mt-6 space-y-4">
                        @csrf

                        <x-ui.input
                            label="Nama lengkap"
                            name="name"
                            type="text"
                            autocomplete="name"
                            placeholder="Nama pemain atau pengelola"
                            class="rounded-[1.2rem] border-slate-200 bg-white/90"
                        />

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
                            autocomplete="new-password"
                            placeholder="Minimal 8 karakter"
                            class="rounded-[1.2rem] border-slate-200 bg-white/90"
                        />

                        <x-ui.input
                            label="Konfirmasi password"
                            name="password_confirmation"
                            type="password"
                            autocomplete="new-password"
                            placeholder="Ulangi password"
                            class="rounded-[1.2rem] border-slate-200 bg-white/90"
                        />

                        <x-ui.button type="submit" class="w-full rounded-[1.2rem] bg-[var(--color-brand-500)] hover:bg-[var(--color-brand-600)] focus-visible:outline-[var(--color-brand-500)]">
                            Buat Akun
                        </x-ui.button>
                    </form>

                    <div class="mt-5 grid gap-3 sm:grid-cols-2">
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-[1.2rem] border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                            Sudah Punya Akun
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
