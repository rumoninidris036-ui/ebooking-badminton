<x-dashboard.layout :title="$title.' | '.config('app.name')" :heading="$title" :description="$description">
    <div class="dashboard-table-wrap">
        <table class="dashboard-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Phone</th>
                    <th>Courts</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $row)
                    <tr>
                        <td class="font-semibold text-slate-900">{{ $row->name }}</td>
                        <td>{{ $row->email }}</td>
                        <td><x-dashboard.status-badge :status="$row->role" /></td>
                        <td>{{ $row->phone ?: 'Not set' }}</td>
                        <td>{{ $row->courts_count ?? '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $users->links() }}
    </div>
</x-dashboard.layout>
