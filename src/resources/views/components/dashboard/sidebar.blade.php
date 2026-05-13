@props([
    'user',
])

@php
    $shared = [
        ['label' => 'Dashboard', 'route' => 'dashboard'],
        ['label' => 'Courts', 'route' => 'courts.index', 'roles' => ['admin', 'owner']],
        ['label' => 'Schedules', 'route' => 'operations.schedules'],
        ['label' => 'Bookings', 'route' => 'bookings.index'],
        ['label' => 'Reviews', 'route' => 'operations.reviews'],
        ['label' => 'Notifications', 'route' => 'operations.notifications'],
        ['label' => 'Reports', 'route' => 'operations.reports'],
        ['label' => 'Profile Settings', 'route' => 'operations.profile'],
    ];

    $adminOnly = [
        ['label' => 'User Management', 'route' => 'operations.admin.users'],
        ['label' => 'Owner Management', 'route' => 'operations.admin.owners'],
        ['label' => 'System Analytics', 'route' => 'operations.admin.analytics'],
        ['label' => 'Recommendation System', 'route' => 'operations.admin.recommendations'],
        ['label' => 'Global Transactions', 'route' => 'operations.admin.transactions'],
        ['label' => 'Platform Monitoring', 'route' => 'operations.admin.monitoring'],
        ['label' => 'System Settings', 'route' => 'operations.admin.settings'],
    ];

    $ownerOnly = [
        ['label' => 'My Courts', 'route' => 'courts.index'],
        ['label' => 'Revenue', 'route' => 'operations.owner.revenue'],
        ['label' => 'Booking Requests', 'route' => 'operations.owner.requests'],
        ['label' => 'Operational Schedule', 'route' => 'operations.schedules'],
    ];

    $userOnly = [
        ['label' => 'My Bookings', 'route' => 'bookings.index'],
        ['label' => 'Notifications', 'route' => 'operations.notifications'],
        ['label' => 'Profile Settings', 'route' => 'operations.profile'],
    ];
@endphp

<div class="flex h-full flex-col">
    <div class="flex items-center justify-between border-b border-slate-800 px-5 py-5">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
            <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-blue-600 text-sm font-black text-white">EB</span>
            <span>
                <span class="block text-sm font-semibold text-white">EBooking Ops</span>
                <span class="block text-xs text-slate-400">Admin & owner workspace</span>
            </span>
        </a>
        <button type="button" data-sidebar-toggle class="rounded-xl border border-slate-700 px-3 py-2 text-xs font-semibold text-slate-300 lg:hidden">
            Close
        </button>
    </div>

    <div class="border-b border-slate-800 px-5 py-4">
        <div class="rounded-2xl bg-slate-800/80 px-4 py-4">
            <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Signed in as</p>
            <p class="mt-2 text-sm font-semibold text-white">{{ $user->name }}</p>
            <p class="mt-1 text-xs capitalize text-slate-300">{{ $user->role }}</p>
        </div>
    </div>

    <div class="flex-1 overflow-y-auto px-4 py-5">
        <div class="space-y-6">
            <div>
                <p class="px-2 text-[11px] font-semibold uppercase tracking-[0.2em] text-slate-500">Workspace</p>
                <nav class="mt-3 space-y-1">
                    @foreach ($shared as $item)
                        @continue(isset($item['roles']) && ! in_array($user->role, $item['roles'], true))
                        @php $active = request()->routeIs($item['route']); @endphp
                        <a href="{{ route($item['route']) }}" class="flex items-center rounded-xl px-3 py-2.5 text-sm font-medium transition {{ $active ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800/70 hover:text-white' }}">
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </nav>
            </div>

            @if ($user->role === 'admin')
                <div>
                    <p class="px-2 text-[11px] font-semibold uppercase tracking-[0.2em] text-slate-500">Platform</p>
                    <nav class="mt-3 space-y-1">
                        @foreach ($adminOnly as $item)
                            <a href="{{ route($item['route']) }}" class="flex items-center rounded-xl px-3 py-2.5 text-sm font-medium transition {{ request()->routeIs($item['route']) ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800/70 hover:text-white' }}">
                                {{ $item['label'] }}
                            </a>
                        @endforeach
                    </nav>
                </div>
            @endif

            @if ($user->role === 'owner')
                <div>
                    <p class="px-2 text-[11px] font-semibold uppercase tracking-[0.2em] text-slate-500">Owner Tools</p>
                    <nav class="mt-3 space-y-1">
                        @foreach ($ownerOnly as $item)
                            <a href="{{ route($item['route']) }}" class="flex items-center rounded-xl px-3 py-2.5 text-sm font-medium transition {{ request()->routeIs($item['route']) ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800/70 hover:text-white' }}">
                                {{ $item['label'] }}
                            </a>
                        @endforeach
                    </nav>
                </div>
            @endif

            @if ($user->role === 'user')
                <div>
                    <p class="px-2 text-[11px] font-semibold uppercase tracking-[0.2em] text-slate-500">My Space</p>
                    <nav class="mt-3 space-y-1">
                        @foreach ($userOnly as $item)
                            <a href="{{ route($item['route']) }}" class="flex items-center rounded-xl px-3 py-2.5 text-sm font-medium transition {{ request()->routeIs($item['route']) ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800/70 hover:text-white' }}">
                                {{ $item['label'] }}
                            </a>
                        @endforeach
                    </nav>
                </div>
            @endif
        </div>
    </div>

    <div class="border-t border-slate-800 px-4 py-4">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex w-full items-center justify-center rounded-xl border border-slate-700 px-4 py-3 text-sm font-semibold text-slate-200 transition hover:bg-slate-800">
                Sign out
            </button>
        </form>
    </div>
</div>
