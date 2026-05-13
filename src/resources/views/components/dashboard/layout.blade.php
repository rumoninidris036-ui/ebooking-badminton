@props([
    'title' => config('app.name').' Dashboard',
    'heading' => null,
    'description' => null,
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title }}</title>
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="dashboard-shell">
        <div data-dashboard-root class="min-h-screen lg:flex">
            <div data-sidebar-overlay class="fixed inset-0 z-40 hidden bg-slate-950/40 lg:hidden"></div>

            <aside data-sidebar-drawer class="dashboard-sidebar fixed inset-y-0 left-0 z-50 w-72 -translate-x-full border-r border-slate-800 transition-transform duration-200 lg:static lg:translate-x-0">
                <x-dashboard.sidebar :user="auth()->user()" />
            </aside>

            <div class="min-w-0 flex-1">
                <x-dashboard.topbar :user="auth()->user()" :heading="$heading" />

                <main class="px-4 py-4 sm:px-6 sm:py-6 lg:px-8">
                    @if ($heading || $description || isset($actions))
                        <section class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                            <div>
                                @if ($heading)
                                    <h1 class="text-2xl font-bold tracking-tight text-slate-950 sm:text-3xl">{{ $heading }}</h1>
                                @endif
                                @if ($description)
                                    <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-500">{{ $description }}</p>
                                @endif
                            </div>
                            @if (isset($actions))
                                <div class="flex flex-wrap gap-3">
                                    {{ $actions }}
                                </div>
                            @endif
                        </section>
                    @endif

                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
