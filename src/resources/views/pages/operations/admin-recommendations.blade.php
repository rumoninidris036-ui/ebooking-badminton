<x-dashboard.layout
    :title="'Recommendation System | '.config('app.name')"
    heading="Recommendation System"
    description="Admin-facing visibility into recommendation outputs, match scores, and surfaced venues."
>
    <div class="dashboard-table-wrap">
        <table class="dashboard-table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Court</th>
                    <th>Location</th>
                    <th>Similarity Score</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rows as $row)
                    <tr>
                        <td class="font-medium text-slate-900">{{ $row->user->name }}</td>
                        <td>{{ $row->court->name }}</td>
                        <td>{{ $row->court->location }}</td>
                        <td><span class="rounded-xl bg-blue-50 px-3 py-2 text-sm font-semibold text-blue-700">{{ number_format((float) $row->similarity_score, 1) }}</span></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-dashboard.layout>
