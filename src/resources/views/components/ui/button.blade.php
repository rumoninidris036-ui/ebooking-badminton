@props([
    'variant' => 'primary',
    'type' => 'button',
])

@php
    $variants = [
        'primary' => 'bg-blue-600 text-white hover:bg-blue-700 focus-visible:outline-blue-600',
        'secondary' => 'border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 focus-visible:outline-slate-300',
        'success' => 'bg-green-600 text-white hover:bg-green-700 focus-visible:outline-green-600',
    ];
@endphp

<button
    type="{{ $type }}"
    {{ $attributes->class([
        'inline-flex items-center justify-center rounded-xl px-4 py-3 text-sm font-semibold shadow-sm transition focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2',
        $variants[$variant] ?? $variants['primary'],
    ]) }}
>
    {{ $slot }}
</button>
