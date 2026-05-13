<x-dashboard.layout
    :title="'Edit Court | '.config('app.name')"
    :heading="'Edit Court: '.$court->name"
    description="Update pricing, weekly operating hours, and venue visibility without leaving the unified dashboard."
>
    <x-slot:actions>
        <a href="{{ route('courts.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
            Back to Courts
        </a>
    </x-slot:actions>

    <section class="dashboard-card p-5 sm:p-6">
        <form method="POST" action="{{ route('courts.update', $court) }}" class="space-y-6">
            @csrf
            @method('PUT')

            @include('pages.courts._form', ['court' => $court])

            <div class="grid gap-3 sm:grid-cols-2">
                <x-ui.button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 focus-visible:outline-blue-600">
                    Update court
                </x-ui.button>
                <a href="{{ route('courts.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                    Back to list
                </a>
            </div>
        </form>
    </section>
</x-dashboard.layout>
