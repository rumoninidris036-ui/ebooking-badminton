@props([
    'status',
])

@php
    $normalized = strtolower((string) $status);
    $classes = match ($normalized) {
        'paid', 'completed', 'finished', 'active', 'available' => 'bg-emerald-100 text-emerald-700',
        'pending', 'warning' => 'bg-amber-100 text-amber-700',
        'confirmed' => 'bg-blue-100 text-blue-700',
        'canceled', 'inactive', 'booked' => 'bg-rose-100 text-rose-700',
        default => 'bg-slate-100 text-slate-600',
    };
@endphp

<span {{ $attributes->class("inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {$classes}") }}>
    {{ ucfirst($status) }}
</span>
