<x-dashboard.layout
    :title="'System Settings | '.config('app.name')"
    heading="System Settings"
    description="Structured settings surface for recommendation rules, Telegram bot setup, and future payment gateway configuration."
>
    <section class="grid gap-6 lg:grid-cols-2">
        <article class="dashboard-card p-5 sm:p-6">
            <h2 class="text-lg font-semibold text-slate-950">Recommendation Configuration</h2>
            <p class="mt-2 text-sm text-slate-500">Weight tuning, popularity bias, and facility matching controls can be connected here.</p>
        </article>
        <article class="dashboard-card p-5 sm:p-6">
            <h2 class="text-lg font-semibold text-slate-950">Telegram Bot Config</h2>
            <p class="mt-2 text-sm text-slate-500">Notification routing, bot token fields, and delivery preferences placeholder.</p>
        </article>
        <article class="dashboard-card p-5 sm:p-6">
            <h2 class="text-lg font-semibold text-slate-950">System Settings</h2>
            <p class="mt-2 text-sm text-slate-500">Platform defaults, branding, and operational toggles can live in this section.</p>
        </article>
        <article class="dashboard-card p-5 sm:p-6">
            <h2 class="text-lg font-semibold text-slate-950">Payment Gateway</h2>
            <p class="mt-2 text-sm text-slate-500">Prepared for future Midtrans integration and transaction lifecycle controls.</p>
        </article>
    </section>
</x-dashboard.layout>
