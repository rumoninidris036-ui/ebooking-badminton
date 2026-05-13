@props([
    'title',
    'description',
    'tone' => 'primary',
])

@php
    $accent = [
        'primary' => 'bg-blue-50 text-blue-700 ring-blue-100',
        'success' => 'bg-green-50 text-green-700 ring-green-100',
        'danger' => 'bg-red-50 text-red-700 ring-red-100',
        'muted' => 'bg-slate-100 text-slate-700 ring-slate-200',
    ];
@endphp

<article {{ $attributes->class('rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200') }}>
    <div class="inline-flex rounded-xl px-3 py-2 text-sm font-semibold ring-1 {{ $accent[$tone] ?? $accent['primary'] }}">
        {{ $title }}
    </div>
    <p class="mt-4 text-sm leading-6 text-slate-600">
        {{ $description }}
    </p>
    @if (trim($slot))
        <div class="mt-4">
            {{ $slot }}
        </div>
    @endif
</article>
