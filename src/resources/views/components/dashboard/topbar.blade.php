@props([
    'user',
    'heading' => null,
])

<header class="sticky top-0 z-30 border-b border-slate-200 bg-white/95 backdrop-blur">
    <div class="flex flex-wrap items-center justify-between gap-3 px-4 py-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-3">
            <button type="button" data-sidebar-toggle class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-700 lg:hidden">
                Menu
            </button>
            <div>
                <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Operations Console</p>
                <p class="text-sm font-semibold text-slate-900">{{ $heading ?? 'Dashboard' }}</p>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <label class="hidden items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 md:flex">
                <span class="text-xs uppercase tracking-[0.18em] text-slate-400">Search</span>
                <input type="text" placeholder="Bookings, courts, users" class="w-48 bg-transparent text-sm text-slate-700 outline-none placeholder:text-slate-400">
            </label>
            <div class="hidden rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-600 md:block">
                {{ now()->format('d M Y') }}
            </div>
            <div class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-right">
                <p class="text-xs uppercase tracking-[0.18em] text-slate-400">{{ ucfirst($user->role) }}</p>
                <p class="text-sm font-semibold text-slate-900">{{ $user->email }}</p>
            </div>
        </div>
    </div>
</header>
