<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ dark: localStorage.getItem('theme') === 'dark' }" x-init="$watch('dark', val => localStorage.setItem('theme', val ? 'dark' : 'light'))" :class="{ 'dark': dark }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'Menu' }}</title>

    <!-- Tailwind via Vite -->
    @vite('resources/css/app.css')

    <!-- Livewire Styles -->
    @livewireStyles
</head>
<body class="bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 antialiased font-sans transition-colors duration-300">

<!-- App Wrapper -->
<div class="min-h-screen flex flex-col">
    <!-- Header / App Bar -->
    <header class="bg-white dark:bg-gray-800 shadow-sm sticky top-0 z-50">
        <div class="max-w-4xl mx-auto px-4 py-4 flex items-center justify-between">
            <h1 class="text-xl sm:text-2xl font-bold tracking-tight dark:text-white">🍽️ Ignition Club Menu</h1>

            <!-- Theme Toggle Switch -->
            <button
                @click="dark = !dark"
                class="rounded-full border border-gray-300 dark:border-gray-600 p-2 text-sm text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition"
                title="Toggle Dark Mode">
                <svg x-show="!dark" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m8.66-9H21m-16.66 0H3m12.02-7.02l.71.71m-9.9 9.9l.71.71m12.02 0l-.71.71m-9.9-9.9l-.71.71M12 5a7 7 0 100 14a7 7 0 000-14z" />
                </svg>
                <svg x-show="dark" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 2a8 8 0 100 16 8 8 0 000-16zM9 4a6 6 0 010 12V4z" />
                </svg>
            </button>
        </div>
    </header>

    <!-- Main Slot for Livewire Page -->
    <main class="flex-1">
        {{ $slot }}
    </main>

    <!-- Bottom Nav (Optional for mobile) -->
    <footer class="text-center text-xs text-gray-400 dark:text-gray-500 py-4 bg-gray-50 dark:bg-gray-800">
        &copy; {{ date('Y') }} Ignition Club. All rights reserved.
    </footer>
</div>

<!-- Scripts -->
@livewireScripts
@vite('resources/js/app.js')
</body>
</html>
