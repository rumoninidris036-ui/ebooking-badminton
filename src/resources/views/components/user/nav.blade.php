<div class="overflow-x-auto pb-1">
    <nav class="flex min-w-max gap-2">
        <a href="{{ route('dashboard') }}" class="rounded-full px-4 py-2 text-sm font-semibold {{ request()->routeIs('dashboard') ? 'bg-[var(--color-brand-500)] text-white' : 'border border-slate-200 bg-white text-slate-600' }}">
            Dashboard
        </a>
        <a href="{{ route('operations.schedules') }}" class="rounded-full px-4 py-2 text-sm font-semibold {{ request()->routeIs('operations.schedules') ? 'bg-[var(--color-brand-500)] text-white' : 'border border-slate-200 bg-white text-slate-600' }}">
            Schedules
        </a>
        <a href="{{ route('bookings.index') }}" class="rounded-full px-4 py-2 text-sm font-semibold {{ request()->routeIs('bookings.index') ? 'bg-[var(--color-brand-500)] text-white' : 'border border-slate-200 bg-white text-slate-600' }}">
            Bookings
        </a>
        <a href="{{ route('operations.reviews') }}" class="rounded-full px-4 py-2 text-sm font-semibold {{ request()->routeIs('operations.reviews') ? 'bg-[var(--color-brand-500)] text-white' : 'border border-slate-200 bg-white text-slate-600' }}">
            Reviews
        </a>
        <a href="{{ route('operations.notifications') }}" class="rounded-full px-4 py-2 text-sm font-semibold {{ request()->routeIs('operations.notifications') ? 'bg-[var(--color-brand-500)] text-white' : 'border border-slate-200 bg-white text-slate-600' }}">
            Notifications
        </a>
        <a href="{{ route('operations.reports') }}" class="rounded-full px-4 py-2 text-sm font-semibold {{ request()->routeIs('operations.reports') ? 'bg-[var(--color-brand-500)] text-white' : 'border border-slate-200 bg-white text-slate-600' }}">
            Reports
        </a>
        <a href="{{ route('operations.profile') }}" class="rounded-full px-4 py-2 text-sm font-semibold {{ request()->routeIs('operations.profile') ? 'bg-[var(--color-brand-500)] text-white' : 'border border-slate-200 bg-white text-slate-600' }}">
            Profile Settings
        </a>
    </nav>
</div>
