<x-dashboard.layout
    :title="'Platform Monitoring | '.config('app.name')"
    heading="Platform Monitoring"
    description="High-level system health cards for operational awareness without introducing heavy monitoring libraries."
>
    <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        @foreach ($health as $item)
            <x-dashboard.stat-card :label="$item['label']" :value="$item['value']" meta="Live system signal" :tone="$item['tone']" />
        @endforeach
    </section>
</x-dashboard.layout>
