<x-layouts.app :title="config('app.name') . ' | Booking Lapangan Badminton'">
    <main>
        <section class="shell-container pt-6 pb-10 sm:pt-10 sm:pb-16">
            <div class="hero-grid glass-panel overflow-hidden bg-[linear-gradient(135deg,rgba(15,23,32,0.96),rgba(25,65,48,0.88))] text-white">
                <div class="grid gap-10 px-5 py-6 sm:px-8 sm:py-8 lg:grid-cols-[1.15fr_0.85fr] lg:items-center lg:px-10 lg:py-12">
                    <div class="space-y-6">
                        <span class="inline-flex items-center rounded-full border border-white/15 bg-white/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.28em] text-emerald-100">
                            Instant booking confirmation
                        </span>

                        <div class="space-y-4">
                            <h1 class="max-w-3xl text-4xl font-black leading-tight tracking-tight sm:text-5xl lg:text-6xl">
                                Booking lapangan badminton terasa ringan, cepat, dan rapi dari satu tempat.
                            </h1>
                            <p class="max-w-2xl text-sm leading-7 text-slate-200 sm:text-base">
                                Temukan venue aktif, bandingkan harga dan lokasi, lalu amankan slot main tanpa perlu pindah-pindah chat. Satu login yang sama bisa dipakai untuk user, admin, dan pemilik lapangan.
                            </p>
                        </div>

                        <div class="flex flex-wrap gap-3">
                            <a href="{{ auth()->check() ? route('bookings.create') : route('login') }}" class="inline-flex items-center justify-center rounded-full bg-[var(--color-brand-300)] px-6 py-3 text-sm font-bold text-[var(--color-ink-950)] shadow-lg shadow-emerald-950/20 transition hover:bg-[var(--color-brand-200)]">
                                Booking Sekarang
                            </a>
                            <a href="#discover" class="inline-flex items-center justify-center rounded-full border border-white/15 bg-white/10 px-6 py-3 text-sm font-semibold text-white transition hover:bg-white/15">
                                Lihat Venue
                            </a>
                        </div>

                        <dl class="grid max-w-2xl gap-3 sm:grid-cols-3">
                            <div class="rounded-3xl border border-white/10 bg-white/8 px-4 py-4">
                                <dt class="text-xs uppercase tracking-[0.22em] text-emerald-100/80">Venue aktif</dt>
                                <dd class="mt-2 text-2xl font-bold">{{ $featuredCourts->count() }}+</dd>
                            </div>
                            <div class="rounded-3xl border border-white/10 bg-white/8 px-4 py-4">
                                <dt class="text-xs uppercase tracking-[0.22em] text-emerald-100/80">Proses booking</dt>
                                <dd class="mt-2 text-2xl font-bold">&lt; 1 menit</dd>
                            </div>
                            <div class="rounded-3xl border border-white/10 bg-white/8 px-4 py-4">
                                <dt class="text-xs uppercase tracking-[0.22em] text-emerald-100/80">Satu akun</dt>
                                <dd class="mt-2 text-2xl font-bold">3 role</dd>
                            </div>
                        </dl>
                    </div>

                    <div class="relative">
                        <div class="overflow-hidden rounded-[2rem] border border-white/10 bg-white/10 p-3 shadow-2xl shadow-black/20">
                            <div class="rounded-[1.65rem] bg-white p-4 text-[var(--color-ink-900)] sm:p-5">
                                <div class="flex items-center justify-between gap-3">
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-[var(--color-brand-600)]">Today spotlight</p>
                                        <h2 class="mt-2 text-2xl font-bold">Prime Evening Slots</h2>
                                    </div>
                                    <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">Hot Pick</span>
                                </div>

                                <div class="mt-5 aspect-[4/3] rounded-[1.75rem] bg-[radial-gradient(circle_at_top_left,rgba(31,157,103,0.3),transparent_34%),linear-gradient(135deg,#1b4332,#2d6a4f_55%,#95d5b2)] p-5 text-white">
                                    <div class="flex h-full flex-col justify-between">
                                        <div class="flex items-start justify-between gap-3">
                                            <div>
                                                <p class="text-sm font-semibold">Court ready tonight</p>
                                                <p class="mt-1 text-xs text-emerald-100">Pilih jadwal, bayar, lalu main tanpa antre panjang.</p>
                                            </div>
                                            <span class="rounded-full border border-white/15 bg-white/10 px-3 py-1 text-xs font-medium">18.00 - 22.00</span>
                                        </div>
                                        <div class="grid gap-3 sm:grid-cols-2">
                                            <div class="rounded-2xl bg-white/12 px-4 py-3 backdrop-blur">
                                                <p class="text-xs uppercase tracking-[0.18em] text-emerald-100">Rekomendasi</p>
                                                <p class="mt-2 text-lg font-bold">Lokasi strategis</p>
                                            </div>
                                            <div class="rounded-2xl bg-slate-950/25 px-4 py-3 backdrop-blur">
                                                <p class="text-xs uppercase tracking-[0.18em] text-emerald-100">Harga mulai</p>
                                                <p class="mt-2 text-lg font-bold">Rp 50.000/jam</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4 grid gap-3 sm:grid-cols-2">
                                    <div class="rounded-2xl bg-slate-100 px-4 py-4">
                                        <p class="text-xs uppercase tracking-[0.18em] text-slate-500">Pencarian cepat</p>
                                        <p class="mt-2 text-sm font-semibold">Cari lapangan dari lokasi, tanggal, dan jam bermain.</p>
                                    </div>
                                    <div class="rounded-2xl bg-emerald-50 px-4 py-4">
                                        <p class="text-xs uppercase tracking-[0.18em] text-[var(--color-brand-600)]">Akun terpadu</p>
                                        <p class="mt-2 text-sm font-semibold">Role dibedakan oleh sistem, bukan oleh form login yang terpisah.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="absolute -bottom-5 -left-3 hidden rounded-3xl border border-white/20 bg-white/10 px-4 py-3 text-sm text-white shadow-lg backdrop-blur sm:block">
                            <p class="font-semibold">12.000+ slot</p>
                            <p class="text-xs text-slate-200">berhasil dibooking bulan ini</p>
                        </div>
                    </div>
                </div>

                <div class="border-t border-white/10 px-5 py-5 sm:px-8 lg:px-10">
                    <form method="GET" action="{{ auth()->check() ? route('bookings.create') : route('login') }}" class="grid gap-3 lg:grid-cols-[1.1fr_0.8fr_0.8fr_auto]">
                        <label class="rounded-[1.35rem] bg-white/10 px-4 py-3">
                            <span class="block text-[11px] font-semibold uppercase tracking-[0.2em] text-emerald-100">Lokasi atau venue</span>
                            <input type="text" name="location" placeholder="Mis. Abepura, Sentani, Kota Jayapura" class="mt-1 w-full bg-transparent text-sm text-white outline-none placeholder:text-slate-300">
                        </label>
                        <label class="rounded-[1.35rem] bg-white/10 px-4 py-3">
                            <span class="block text-[11px] font-semibold uppercase tracking-[0.2em] text-emerald-100">Tanggal main</span>
                            <input type="date" name="date" value="{{ now()->toDateString() }}" class="mt-1 w-full bg-transparent text-sm text-white outline-none">
                        </label>
                        <label class="rounded-[1.35rem] bg-white/10 px-4 py-3">
                            <span class="block text-[11px] font-semibold uppercase tracking-[0.2em] text-emerald-100">Jam favorit</span>
                            <input type="time" name="time" value="18:00" class="mt-1 w-full bg-transparent text-sm text-white outline-none">
                        </label>
                        <button type="submit" class="inline-flex items-center justify-center rounded-[1.35rem] bg-[var(--color-brand-300)] px-5 py-3 text-sm font-bold text-[var(--color-ink-950)] transition hover:bg-[var(--color-brand-200)]">
                            Cari Slot
                        </button>
                    </form>
                </div>
            </div>
        </section>

        <section id="discover" class="shell-container pb-8 sm:pb-12">
            <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.24em] text-[var(--color-brand-600)]">Recommended for you</p>
                    <h2 class="mt-2 text-2xl font-black tracking-tight text-[var(--color-ink-950)] sm:text-3xl">Venue pilihan yang siap dibooking</h2>
                    <p class="mt-2 max-w-2xl text-sm leading-7 text-slate-600">Tampilan ini diadaptasi dari referensi `ecourt`, lalu diterjemahkan ke Blade agar user bisa langsung menjelajah venue dengan kesan yang lebih premium dan ringan.</p>
                </div>
                <a href="{{ auth()->check() ? route('bookings.create') : route('login') }}" class="inline-flex items-center justify-center rounded-full border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
                    Lanjut ke Booking
                </a>
            </div>

            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                @forelse ($featuredCourts as $index => $court)
                    <article class="glass-panel overflow-hidden">
                        <div class="aspect-[4/3] p-3">
                            <div class="relative flex h-full flex-col justify-between overflow-hidden rounded-[1.65rem] bg-[linear-gradient(160deg,#133b2d,#2d6a4f_55%,#b7e4c7)] p-5 text-white">
                                <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(255,255,255,0.16),transparent_28%)]"></div>
                                <div class="relative flex items-start justify-between gap-3">
                                    <x-ui.badge tone="success" class="bg-white/15 text-white">{{ $index < 2 ? 'Recommended' : ucfirst($court->status) }}</x-ui.badge>
                                    <span class="rounded-full border border-white/15 bg-white/10 px-3 py-1 text-xs font-semibold">
                                        {{ number_format((float) $court->rating, 1) }} / 5
                                    </span>
                                </div>
                                <div class="relative">
                                    <h3 class="text-2xl font-bold leading-tight">{{ $court->name }}</h3>
                                    <p class="mt-2 max-w-xs text-sm text-emerald-50">{{ $court->location }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4 px-5 pb-5">
                            <p class="text-sm leading-7 text-slate-600">{{ \Illuminate\Support\Str::limit($court->description ?: 'Venue badminton aktif dengan alur booking cepat dan jadwal yang mudah dicek dari dashboard pengguna.', 110) }}</p>

                            <div class="flex flex-wrap gap-2">
                                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">Indoor</span>
                                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">Prime time</span>
                                <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-medium text-[var(--color-brand-600)]">User friendly</span>
                            </div>

                            <div class="flex items-end justify-between gap-3">
                                <div>
                                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Mulai dari</p>
                                    <p class="mt-1 text-2xl font-black text-[var(--color-ink-950)]">Rp {{ number_format($court->price_per_hour, 0, ',', '.') }}</p>
                                    <p class="text-xs text-slate-500">per jam</p>
                                </div>
                                <a href="{{ auth()->check() ? route('bookings.create', ['court_id' => $court->id]) : route('login') }}" class="inline-flex items-center justify-center rounded-full bg-[var(--color-brand-500)] px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-emerald-900/10 transition hover:bg-[var(--color-brand-600)]">
                                    Pilih Venue
                                </a>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="glass-panel px-6 py-8 text-sm text-slate-600 md:col-span-2 xl:col-span-3">
                        Belum ada venue aktif yang dipublikasikan.
                    </div>
                @endforelse
            </div>
        </section>

        <section id="why-us" class="shell-container py-8 sm:py-12">
            <div class="grid gap-4 lg:grid-cols-3">
                <article class="glass-panel p-6">
                    <p class="text-xs font-semibold uppercase tracking-[0.24em] text-[var(--color-brand-600)]">Fast booking</p>
                    <h3 class="mt-3 text-2xl font-bold text-[var(--color-ink-950)]">Dari landing ke slot aktif dalam alur yang pendek</h3>
                    <p class="mt-3 text-sm leading-7 text-slate-600">User bisa langsung bergerak dari eksplor venue ke pemilihan jadwal tanpa merasa masuk ke area admin.</p>
                </article>
                <article class="glass-panel p-6">
                    <p class="text-xs font-semibold uppercase tracking-[0.24em] text-[var(--color-brand-600)]">Single login</p>
                    <h3 class="mt-3 text-2xl font-bold text-[var(--color-ink-950)]">Satu pintu autentikasi untuk semua role</h3>
                    <p class="mt-3 text-sm leading-7 text-slate-600">Admin, user, dan pemilik lapangan memakai halaman login yang sama, lalu sistem yang menentukan hak aksesnya.</p>
                </article>
                <article class="glass-panel p-6">
                    <p class="text-xs font-semibold uppercase tracking-[0.24em] text-[var(--color-brand-600)]">Operational ready</p>
                    <h3 class="mt-3 text-2xl font-bold text-[var(--color-ink-950)]">Tetap enak dipakai sambil menjaga alur backend yang ada</h3>
                    <p class="mt-3 text-sm leading-7 text-slate-600">Implementasi ini sengaja mengikuti struktur Blade dan route Laravel yang sekarang agar aman untuk iterasi berikutnya.</p>
                </article>
            </div>
        </section>

        <section id="reviews" class="shell-container pt-4 pb-12 sm:pb-16">
            <div class="glass-panel overflow-hidden">
                <div class="grid gap-0 lg:grid-cols-[0.95fr_1.05fr]">
                    <div class="bg-[linear-gradient(145deg,#153b2d,#214f3b_55%,#2d6a4f)] px-6 py-8 text-white sm:px-8">
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-emerald-100">Trusted by players</p>
                        <h2 class="mt-3 text-3xl font-black tracking-tight">Tampilan yang lebih hidup untuk user side</h2>
                        <p class="mt-4 max-w-xl text-sm leading-7 text-slate-200">Referensi `ecourt` punya ritme visual yang kuat di landing page. Di sini saya pakai arah yang sama, tetapi disesuaikan supaya tetap cocok dengan stack Laravel Blade + Tailwind.</p>
                    </div>
                    <div class="grid gap-4 p-6 sm:p-8 md:grid-cols-2">
                        <figure class="rounded-[1.8rem] bg-slate-50 p-5">
                            <p class="text-base font-semibold text-[var(--color-ink-950)]">“Booking lebih cepat dan tampilannya lebih meyakinkan.”</p>
                            <figcaption class="mt-4 text-sm text-slate-500">Andi, pemain mingguan</figcaption>
                        </figure>
                        <figure class="rounded-[1.8rem] bg-emerald-50 p-5">
                            <p class="text-base font-semibold text-[var(--color-ink-950)]">“Alur login satu pintu jauh lebih simpel untuk semua jenis pengguna.”</p>
                            <figcaption class="mt-4 text-sm text-slate-500">Sari, pengelola klub</figcaption>
                        </figure>
                        <figure class="rounded-[1.8rem] bg-slate-50 p-5 md:col-span-2">
                            <p class="text-base font-semibold text-[var(--color-ink-950)]">“Transisi dari landing page ke booking terasa lebih nyambung, jadi user tidak seperti dilempar ke halaman sistem yang berbeda.”</p>
                            <figcaption class="mt-4 text-sm text-slate-500">Reza, pengguna baru</figcaption>
                        </figure>
                    </div>
                </div>
            </div>
        </section>
    </main>
</x-layouts.app>
