<x-dashboard.layout
    :title="'Profile Settings | '.config('app.name')"
    heading="Profile Settings"
    description="Shared settings screen for account profile, password reminders, and notification preferences."
>
    <section class="grid gap-6 xl:grid-cols-[0.9fr_1.1fr]">
        <article class="dashboard-card p-5 sm:p-6">
            <h2 class="text-lg font-semibold text-slate-950">Profile</h2>
            <dl class="mt-5 space-y-4 text-sm">
                <div class="flex items-center justify-between gap-3">
                    <dt class="text-slate-500">Name</dt>
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
                    <dt class="text-slate-500">Phone</dt>
                    <dd class="font-semibold text-slate-900">{{ $user->phone ?: 'Not set yet' }}</dd>
                </div>
                <div class="flex items-center justify-between gap-3">
                    <dt class="text-slate-500">Address</dt>
                    <dd class="font-semibold text-slate-900">{{ $user->address ?: 'Not set yet' }}</dd>
                </div>
            </dl>
        </article>

        <div class="space-y-6">
            <article class="dashboard-card p-5 sm:p-6">
                <h2 class="text-lg font-semibold text-slate-950">Notification Preferences</h2>
                <div class="mt-5 space-y-3">
                    <div class="flex items-center justify-between rounded-2xl border border-slate-200 px-4 py-4">
                        <div>
                            <p class="font-medium text-slate-900">Booking alerts</p>
                            <p class="mt-1 text-sm text-slate-500">Receive important booking changes.</p>
                        </div>
                        <span class="dashboard-filter-chip">Enabled</span>
                    </div>
                    <div class="flex items-center justify-between rounded-2xl border border-slate-200 px-4 py-4">
                        <div>
                            <p class="font-medium text-slate-900">Payment alerts</p>
                            <p class="mt-1 text-sm text-slate-500">Get notified about transaction state changes.</p>
                        </div>
                        <span class="dashboard-filter-chip">Enabled</span>
                    </div>
                    <div class="flex items-center justify-between rounded-2xl border border-slate-200 px-4 py-4">
                        <div>
                            <p class="font-medium text-slate-900">Unread center</p>
                            <p class="mt-1 text-sm text-slate-500">Current unread notifications on your account.</p>
                        </div>
                        <span class="dashboard-filter-chip">{{ $unreadNotifications }}</span>
                    </div>
                </div>
            </article>

            <article class="dashboard-card p-5 sm:p-6">
                <h2 class="text-lg font-semibold text-slate-950">Security</h2>
                <p class="mt-2 text-sm text-slate-500">Password change and account hardening UI can be connected here in the next iteration.</p>
            </article>
        </div>
    </section>
</x-dashboard.layout>
