<x-dashboard.layout
    :title="'Notifications | '.config('app.name')"
    heading="Notification Center"
    description="Compact and categorized notification center for booking alerts, payment updates, and system messages."
>
    <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <x-dashboard.stat-card label="Unread" :value="$summary['unread']" meta="Need attention" tone="warning" />
        <x-dashboard.stat-card label="Booking" :value="$summary['booking']" meta="Reservation events" tone="primary" />
        <x-dashboard.stat-card label="Payment" :value="$summary['payment']" meta="Payment-related updates" tone="success" />
        <x-dashboard.stat-card label="System" :value="$summary['system']" meta="Operational messages" tone="danger" />
    </section>

    <section class="mt-6 grid gap-4">
        @forelse ($notifications as $notification)
            <article class="dashboard-card p-5">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-sm font-semibold text-slate-900">{{ $notification->title }}</p>
                        <p class="mt-2 text-sm leading-7 text-slate-600">{{ $notification->message }}</p>
                    </div>
                    <div class="text-right">
                        <x-dashboard.status-badge :status="$notification->type" />
                        <p class="mt-2 text-xs text-slate-400">{{ $notification->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            </article>
        @empty
            <x-dashboard.empty-state title="No notifications yet" description="Booking, cancellation, payment, and system alerts will appear here." />
        @endforelse
    </section>

    <div class="mt-6">
        {{ $notifications->links() }}
    </div>
</x-dashboard.layout>
