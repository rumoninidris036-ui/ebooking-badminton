@props([
    'tone' => 'primary',
])

@php
    $tones = [
        'primary' => 'bg-blue-100 text-blue-700',
        'success' => 'bg-green-100 text-green-700',
        'danger' => 'bg-red-100 text-red-700',
        'muted' => 'bg-slate-100 text-slate-600',
    ];
@endphp

<span {{ $attributes->class(['inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold', $tones[$tone] ?? $tones['primary']]) }}>
    {{ $slot }}
</span>
