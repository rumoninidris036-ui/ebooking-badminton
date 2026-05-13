@props([
    'label',
    'name',
])

<div class="space-y-2">
    <label for="{{ $name }}" class="block text-sm font-semibold text-slate-700">
        {{ $label }}
    </label>
    <select
        id="{{ $name }}"
        name="{{ $name }}"
        {{ $attributes->class('block w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100') }}
    >
        {{ $slot }}
    </select>
    @error($name)
        <p class="text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
