@props([
    'title',
    'description',
])

<div class="dashboard-card px-6 py-10 text-center">
    <p class="text-lg font-semibold text-slate-950">{{ $title }}</p>
    <p class="mt-2 text-sm text-slate-500">{{ $description }}</p>
</div>
