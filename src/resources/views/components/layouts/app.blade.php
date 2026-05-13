<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ?? config('app.name', 'EBooking') }}</title>
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="min-h-screen text-slate-900">
        <div class="min-h-screen pb-20 md:pb-0">
            <header class="sticky top-0 z-40 border-b border-white/70 bg-white/75 backdrop-blur-xl">
                <div class="shell-container flex h-16 items-center justify-between gap-4">
                    <a href="{{ route('landing') }}" class="flex items-center gap-3">
                        <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-[var(--color-brand-500)] text-sm font-black text-white shadow-lg shadow-emerald-900/10">EB</span>
                        <span>
                            <span class="block text-sm font-semibold uppercase tracking-[0.28em] text-[var(--color-brand-600)]">EBooking</span>
                            <span class="block text-xs text-slate-500">Badminton court booking</span>
                        </span>
                    </a>

                    <nav class="hidden items-center gap-6 md:flex">
                        <a href="{{ route('landing') }}#discover" class="text-sm font-medium text-slate-600 transition hover:text-slate-900">Jelajahi</a>
                        <a href="{{ route('landing') }}#why-us" class="text-sm font-medium text-slate-600 transition hover:text-slate-900">Keunggulan</a>
                        <a href="{{ route('landing') }}#reviews" class="text-sm font-medium text-slate-600 transition hover:text-slate-900">Ulasan</a>

                        @auth
                            <a href="{{ route('bookings.create') }}" class="text-sm font-medium text-slate-600 transition hover:text-slate-900">Booking</a>
                            <a href="{{ route('dashboard') }}" class="text-sm font-medium text-slate-600 transition hover:text-slate-900">Dashboard</a>
                            @if (in_array(auth()->user()->role, ['admin', 'owner'], true))
                                <a href="{{ route('courts.index') }}" class="text-sm font-medium text-slate-600 transition hover:text-slate-900">Kelola Lapangan</a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="inline-flex items-center justify-center rounded-full border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                                    Keluar
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-medium text-slate-600 transition hover:text-slate-900">Masuk</a>
                            <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-full bg-[var(--color-brand-500)] px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-emerald-900/10 transition hover:bg-[var(--color-brand-600)]">
                                Mulai Sekarang
                            </a>
                        @endauth
                    </nav>

                    <div class="md:hidden">
                        @auth
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center rounded-full bg-[var(--color-brand-500)] px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-emerald-900/10 transition hover:bg-[var(--color-brand-600)]">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-full bg-[var(--color-brand-500)] px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-emerald-900/10 transition hover:bg-[var(--color-brand-600)]">
                                Masuk
                            </a>
                        @endauth
                    </div>
                </div>
            </header>

            {{ $slot }}

            <nav class="fixed inset-x-4 bottom-4 z-40 md:hidden">
                <div class="mx-auto grid max-w-md grid-cols-4 overflow-hidden rounded-[1.6rem] border border-white/80 bg-white/90 shadow-2xl shadow-slate-900/10 backdrop-blur-xl">
                    <a href="{{ route('landing') }}" class="flex flex-col items-center gap-1 px-2 py-3 text-[11px] font-medium {{ request()->routeIs('landing') ? 'text-[var(--color-brand-600)]' : 'text-slate-500' }}">
                        <span>Home</span>
                    </a>
                    <a href="{{ route('bookings.create') }}" class="flex flex-col items-center gap-1 px-2 py-3 text-[11px] font-medium {{ request()->routeIs('bookings.create') ? 'text-[var(--color-brand-600)]' : 'text-slate-500' }}">
                        <span>Booking</span>
                    </a>
                    <a href="{{ route('dashboard') }}" class="flex flex-col items-center gap-1 px-2 py-3 text-[11px] font-medium {{ request()->routeIs('dashboard') ? 'text-[var(--color-brand-600)]' : 'text-slate-500' }}">
                        <span>Akun</span>
                    </a>
                    @auth
                        <form method="POST" action="{{ route('logout') }}" class="contents">
                            @csrf
                            <button type="submit" class="flex flex-col items-center gap-1 px-2 py-3 text-[11px] font-medium text-slate-500">
                                <span>Keluar</span>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="flex flex-col items-center gap-1 px-2 py-3 text-[11px] font-medium {{ request()->routeIs('login') ? 'text-[var(--color-brand-600)]' : 'text-slate-500' }}">
                            <span>Masuk</span>
                        </a>
                    @endauth
                </div>
            </nav>
        </div>
    </body>
</html>
