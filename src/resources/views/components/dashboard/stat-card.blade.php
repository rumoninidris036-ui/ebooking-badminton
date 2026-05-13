@props([
    'label',
    'value',
    'meta' => null,
    'tone' => 'primary',
])

@php
    $tones = [
        'primary' => 'bg-blue-50 text-blue-700',
        'success' => 'bg-emerald-50 text-emerald-700',
        'warning' => 'bg-amber-50 text-amber-700',
        'danger' => 'bg-rose-50 text-rose-700',
    ];
@endphp

<article class="dashboard-card p-5">
    <div class="flex items-start justify-between gap-3">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">{{ $label }}</p>
            <p class="mt-3 text-2xl font-bold tracking-tight text-slate-950">{{ $value }}</p>
        </div>
        <span class="rounded-xl px-3 py-2 text-xs font-semibold {{ $tones[$tone] ?? $tones['primary'] }}">
            Live
        </span>
    </div>
    @if ($meta)
        <p class="mt-3 text-sm text-slate-500">{{ $meta }}</p>
    @endif
</article>
