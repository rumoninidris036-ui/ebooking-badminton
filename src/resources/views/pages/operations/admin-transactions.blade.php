<x-dashboard.layout
    :title="'Global Transactions | '.config('app.name')"
    heading="Global Transactions"
    description="UI structure prepared for current payments and future Midtrans integration, complete with export-ready layout."
>
    <section class="mb-6">
        <x-dashboard.filter-bar>
            <div class="flex flex-wrap gap-2">
                <span class="dashboard-filter-chip">Date filter</span>
                <span class="dashboard-filter-chip">Status filter</span>
                <span class="dashboard-filter-chip">Payment method</span>
            </div>
            <button type="button" class="rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">Export Report</button>
        </x-dashboard.filter-bar>
    </section>

    <div class="dashboard-table-wrap">
        <table class="dashboard-table">
            <thead>
                <tr>
                    <th>Transaction ID</th>
                    <th>Booking Code</th>
                    <th>Customer</th>
                    <th>Payment Method</th>
                    <th>Amount</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($transactions as $transaction)
                    <tr>
                        <td class="font-medium text-slate-900">{{ $transaction->transaction_id ?: 'Pending integration' }}</td>
                        <td>{{ $transaction->booking->booking_code }}</td>
                        <td>{{ $transaction->booking->user->name }}</td>
                        <td>{{ $transaction->payment_method ?: 'Manual / future gateway' }}</td>
                        <td>Rp {{ number_format($transaction->amount, 0, ',', '.') }}</td>
                        <td><x-dashboard.status-badge :status="$transaction->payment_status" /></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-sm text-slate-500">No transaction records yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $transactions->links() }}
    </div>
</x-dashboard.layout>
