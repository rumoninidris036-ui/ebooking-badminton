@php
    $scheduleMap = isset($court)
        ? $court->schedules->keyBy('day_of_week')
        : collect();
@endphp

<div class="space-y-6">
    <div class="grid gap-4">
        <x-ui.input
            label="Court name"
            name="name"
            type="text"
            :value="$court->name ?? ''"
            placeholder="Court A"
        />

        <x-ui.input
            label="Location"
            name="location"
            type="text"
            :value="$court->location ?? ''"
            placeholder="Main sports hall"
        />

        <x-ui.input
            label="Price per hour"
            name="price_per_hour"
            type="number"
            step="0.01"
            min="0"
            :value="$court->price_per_hour ?? ''"
            placeholder="80000"
        />

        <x-ui.textarea
            label="Description"
            name="description"
            :value="$court->description ?? ''"
            placeholder="Short court details for players and mobile clients."
        />

        <label class="flex items-center gap-3 rounded-2xl bg-slate-50 px-4 py-3 text-sm text-slate-600">
            <input
                type="checkbox"
                name="is_active"
                value="1"
                class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                @checked(old('is_active', $court->is_active ?? true))
            >
            Show this court as available for booking
        </label>
    </div>

    <section class="rounded-3xl bg-slate-50 p-4 ring-1 ring-slate-200">
        <div class="space-y-2">
            <h2 class="text-lg font-semibold text-slate-900">Weekly availability</h2>
            <p class="text-sm text-slate-600">Set the weekly operating hours used later by the booking module.</p>
        </div>

        <div class="mt-4 grid gap-3">
            @foreach ($days as $dayNumber => $dayName)
                @php
                    $saved = $scheduleMap->get($dayNumber);
                    $oldSchedule = old("schedules.$loop->index", []);
                    $isOpen = data_get($oldSchedule, 'is_open', $saved->is_open ?? true);
                @endphp

                <div class="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-slate-200">
                    <input type="hidden" name="schedules[{{ $loop->index }}][day_of_week]" value="{{ $dayNumber }}">

                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="text-sm font-semibold text-slate-900">{{ $dayName }}</h3>
                            <p class="text-xs text-slate-500">Configure availability for this day.</p>
                        </div>

                        <label class="inline-flex items-center gap-3 text-sm text-slate-600">
                            <input
                                type="checkbox"
                                name="schedules[{{ $loop->index }}][is_open]"
                                value="1"
                                class="h-4 w-4 rounded border-slate-300 text-green-600 focus:ring-green-500"
                                @checked($isOpen)
                            >
                            Open
                        </label>
                    </div>

                    <div class="mt-3 grid gap-3 sm:grid-cols-2">
                        <x-ui.input
                            :label="'Open time'"
                            :name="'schedules['.$loop->index.'][open_time]'"
                            type="time"
                            :value="data_get($oldSchedule, 'open_time', $saved->open_time ?? '08:00')"
                        />

                        <x-ui.input
                            :label="'Close time'"
                            :name="'schedules['.$loop->index.'][close_time]'"
                            type="time"
                            :value="data_get($oldSchedule, 'close_time', $saved->close_time ?? '22:00')"
                        />
                    </div>
                </div>
            @endforeach
        </div>
    </section>
</div>
