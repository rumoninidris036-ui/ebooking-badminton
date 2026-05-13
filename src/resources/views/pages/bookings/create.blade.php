<x-layouts.app :title="'Create Booking | '.config('app.name')">
    <main class="shell-container py-6 sm:py-10">
        <div class="space-y-6">
            <section class="glass-panel overflow-hidden bg-[linear-gradient(145deg,#10271d,#1d4b37_52%,#2d6a4f)] text-white">
                <div class="grid gap-6 px-5 py-6 sm:px-8 sm:py-8 lg:grid-cols-[1.1fr_0.9fr] lg:items-end">
                    <div class="space-y-4">
                        <x-ui.badge tone="success" class="bg-white/10 text-emerald-100">User Booking Flow</x-ui.badge>
                        <h1 class="max-w-2xl text-3xl font-black tracking-tight sm:text-4xl">Pilih venue, tentukan tanggal, lalu kunci slot main Anda.</h1>
                        <p class="max-w-2xl text-sm leading-7 text-slate-200">
                            Halaman ini dibuat lebih dekat dengan nuansa katalog user dari desain referensi, tetapi tetap memakai data availability asli dari backend Laravel.
                        </p>
                        <p class="text-sm font-medium text-emerald-100">Book your badminton court</p>
                    </div>

                    <div class="grid gap-3 sm:grid-cols-3">
                        <div class="rounded-[1.5rem] border border-white/12 bg-white/10 px-4 py-4">
                            <p class="text-xs uppercase tracking-[0.18em] text-emerald-100">Step 1</p>
                            <p class="mt-2 text-sm font-semibold">Pilih lapangan</p>
                        </div>
                        <div class="rounded-[1.5rem] border border-white/12 bg-white/10 px-4 py-4">
                            <p class="text-xs uppercase tracking-[0.18em] text-emerald-100">Step 2</p>
                            <p class="mt-2 text-sm font-semibold">Tentukan tanggal</p>
                        </div>
                        <div class="rounded-[1.5rem] border border-white/12 bg-slate-950/20 px-4 py-4">
                            <p class="text-xs uppercase tracking-[0.18em] text-emerald-100">Step 3</p>
                            <p class="mt-2 text-sm font-semibold">Ambil slot</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="grid gap-6 xl:grid-cols-[0.94fr_1.06fr]">
                <div class="glass-panel p-5 sm:p-7">
                    <div class="space-y-3">
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-[var(--color-brand-600)]">Filter booking</p>
                        <h2 class="text-2xl font-black tracking-tight text-[var(--color-ink-950)]">Cari jadwal yang paling pas</h2>
                        <p class="text-sm leading-7 text-slate-600">Pilih lapangan dan tanggal terlebih dulu. Sistem akan menampilkan slot aktif berdasarkan availability sebenarnya.</p>
                    </div>

                    <form method="GET" action="{{ route('bookings.create') }}" class="mt-6 space-y-4">
                        <x-ui.select label="Venue badminton" name="court_id" class="rounded-[1.2rem] border-slate-200 bg-white/90">
                            @foreach ($courts as $court)
                                <option value="{{ $court->id }}" @selected($selectedCourtId === $court->id)>
                                    {{ $court->name }} - Rp {{ number_format($court->price_per_hour, 0, ',', '.') }}/jam
                                </option>
                            @endforeach
                        </x-ui.select>

                        <x-ui.input
                            label="Tanggal bermain"
                            name="date"
                            type="date"
                            :value="$selectedDate"
                            min="{{ now()->toDateString() }}"
                            class="rounded-[1.2rem] border-slate-200 bg-white/90"
                        />

                        <x-ui.button type="submit" class="w-full rounded-[1.2rem] bg-[var(--color-brand-500)] hover:bg-[var(--color-brand-600)] focus-visible:outline-[var(--color-brand-500)]">
                            Cek Ketersediaan
                        </x-ui.button>
                    </form>

                    @if ($availability)
                        <div class="mt-6 rounded-[1.7rem] bg-slate-50 p-5">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Venue terpilih</p>
                            <h3 class="mt-2 text-xl font-bold text-[var(--color-ink-950)]">{{ $availability['court']->name }}</h3>
                            <p class="mt-1 text-sm text-slate-600">{{ $availability['court']->location }}</p>
                            <div class="mt-4 flex items-end justify-between gap-3">
                                <div>
                                    <p class="text-xs uppercase tracking-[0.18em] text-slate-400">Tarif</p>
                                    <p class="mt-1 text-2xl font-black text-[var(--color-ink-950)]">Rp {{ number_format($availability['court']->price_per_hour, 0, ',', '.') }}</p>
                                </div>
                                <div class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                                    {{ $selectedDate }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="glass-panel p-5 sm:p-7">
                    @if ($availability)
                        <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-[var(--color-brand-600)]">Available slots</p>
                                <h2 class="mt-2 text-2xl font-black tracking-tight text-[var(--color-ink-950)]">Pilih jam bermain</h2>
                                <p class="mt-2 text-sm text-slate-600">Status slot akan otomatis mengikuti kondisi venue di tanggal yang dipilih.</p>
                            </div>
                            <a href="{{ route('bookings.index') }}" class="text-sm font-semibold text-[var(--color-brand-600)] hover:text-[var(--color-brand-700)]">Lihat booking saya</a>
                        </div>

                        @if (count($availability['slots']) === 0)
                            <div class="mt-6 rounded-[1.7rem] bg-slate-50 px-5 py-6 text-sm leading-7 text-slate-600">
                                Tidak ada slot yang tersedia di hari ini karena venue sedang tutup.
                            </div>
                        @else
                            <form method="POST" action="{{ route('bookings.store') }}" class="mt-6 space-y-6">
                                @csrf
                                <input type="hidden" name="court_id" value="{{ $selectedCourtId }}">
                                <input type="hidden" name="booking_date" value="{{ $selectedDate }}">
                                <input type="hidden" name="duration_hours" value="1">

                                <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-3">
                                    @foreach ($availability['slots'] as $slot)
                                        @php
                                            $statusClasses = match ($slot['status']) {
                                                'available' => 'border-emerald-200 bg-emerald-50 text-emerald-700',
                                                'booked' => 'border-rose-200 bg-rose-50 text-rose-700',
                                                default => 'border-slate-200 bg-slate-100 text-slate-500',
                                            };
                                        @endphp

                                        <label class="rounded-[1.45rem] border p-4 transition {{ $statusClasses }}">
                                            <div class="flex items-start justify-between gap-3">
                                                <div>
                                                    <p class="text-sm font-bold">{{ $slot['label'] }}</p>
                                                    <p class="mt-1 text-xs uppercase tracking-[0.18em]">{{ $slot['status'] }}</p>
                                                </div>
                                                <input
                                                    type="radio"
                                                    name="start_time"
                                                    value="{{ $slot['start_time'] }}"
                                                    class="mt-1 h-4 w-4 border-slate-300 text-[var(--color-brand-500)] focus:ring-[var(--color-brand-500)]"
                                                    @disabled($slot['status'] !== 'available')
                                                    @checked(old('start_time') === $slot['start_time'])
                                                >
                                            </div>
                                        </label>
                                    @endforeach
                                </div>

                                @error('start_time')
                                    <p class="text-sm text-red-600">{{ $message }}</p>
                                @enderror

                                <x-ui.textarea
                                    label="Catatan tambahan"
                                    name="notes"
                                    placeholder="Opsional: nama pemain, catatan kedatangan, atau kebutuhan lain."
                                    class="rounded-[1.2rem] border-slate-200 bg-white/90"
                                />

                                <div class="grid gap-3 sm:grid-cols-2">
                                    <x-ui.button type="submit" class="w-full rounded-[1.2rem] bg-[var(--color-brand-500)] hover:bg-[var(--color-brand-600)] focus-visible:outline-[var(--color-brand-500)]">
                                        Konfirmasi Booking
                                    </x-ui.button>
                                    <a href="{{ route('bookings.index') }}" class="inline-flex items-center justify-center rounded-[1.2rem] border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                                        Buka Riwayat Booking
                                    </a>
                                </div>
                            </form>
                        @endif
                    @else
                        <div class="flex h-full min-h-72 items-center justify-center rounded-[1.9rem] bg-slate-50 px-6 py-8 text-center">
                            <div class="max-w-md">
                                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">No selection yet</p>
                                <h2 class="mt-3 text-2xl font-black tracking-tight text-[var(--color-ink-950)]">Pilih venue dulu untuk melihat slot tersedia</h2>
                                <p class="mt-3 text-sm leading-7 text-slate-600">Begitu venue dan tanggal dipilih, area ini akan menampilkan jam main yang bisa langsung Anda booking.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </section>
        </div>
    </main>
</x-layouts.app>
