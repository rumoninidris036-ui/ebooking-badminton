@props([
    'label',
    'name',
    'value' => '',
])

<div class="space-y-2">
    <label for="{{ $name }}" class="block text-sm font-semibold text-slate-700">
        {{ $label }}
    </label>
    <textarea
        id="{{ $name }}"
        name="{{ $name }}"
        {{ $attributes->class('block min-h-28 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100') }}
    >{{ old($name, $value) }}</textarea>
    @error($name)
        <p class="text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
