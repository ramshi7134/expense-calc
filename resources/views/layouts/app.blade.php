<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ dark: localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches) }" x-init="$watch('dark', value => {
    localStorage.setItem('theme', value ? 'dark' : 'light');
    document.documentElement.classList.toggle('dark', value);
});
document.documentElement.classList.toggle('dark', dark);"
    :class="{ 'dark': dark }">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- ApexCharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <!-- Alpine.js (Required for Dark Mode) -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Tailwind / Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>


<body
    class="font-sans antialiased bg-gray-50 text-gray-900 min-h-screen">

    <div class="min-h-screen flex flex-col">

        <!-- Navigation -->
        @include('layouts.navigation')

        <!-- Optional Page Header -->
        @isset($header)
            <header class="bg-white dark:bg-gray-900 shadow border-b border-gray-200 dark:border-gray-800">
                <div class="max-w-7xl mx-auto py-6 px-6">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Main Content -->
        <main class="flex-1">
            @yield('content')
        </main>

    </div>

</body>

</html>
