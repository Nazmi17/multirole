<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">
        <style>
            /* Custom Style agar cocok dengan Tailwind */
            .ts-control { border-radius: 0.375rem; padding: 0.5rem 0.75rem; border-color: #d1d5db; }
            .ts-control.focus { border-color: #6366f1; box-shadow: 0 0 0 1px #6366f1; }
        </style>


    </head>
    <body class="font-sans antialiased">
        <div x-data="{ sidebarOpen: window.innerWidth >= 768 }" class="flex h-screen bg-gray-100">
            
            <div x-show="sidebarOpen" 
                 x-transition:enter="transition-opacity ease-linear duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-linear duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 z-20 bg-black bg-opacity-50 md:hidden" 
                 @click="sidebarOpen = false"></div>
            
            <div :class="sidebarOpen ? 'translate-x-0 md:w-64' : '-translate-x-full md:translate-x-0 md:w-0 md:overflow-hidden'"
                 class="fixed inset-y-0 left-0 z-30 w-64 transition-all duration-300 transform bg-white border-r border-gray-200 md:static md:inset-auto">
                
                {{-- Kita bungkus include ini agar saat width 0 di desktop, kontennya tidak 'gepeng' tapi tersembunyi --}}
                <div class="w-64 h-full">
                    @include('layouts.navigation')
                </div>
            </div>

            <div class="flex flex-col flex-1 overflow-hidden">
                
                <header class="flex items-center justify-between px-6 py-4 bg-white border-b border-gray-200 shadow-sm">
                    <div class="flex items-center">
                        <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 focus:outline-none focus:text-gray-700">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                        
                        <span class="ml-4 text-xl font-semibold text-gray-700">
                             @isset($header)
                                {{ $header }}
                             @else
                                {{ config('app.name') }}
                             @endisset
                        </span>
                    </div>

                    </header>

                <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
                    {{ $slot }}
                </main>
            </div>
        </div>

        {{-- 2. TAMBAHKAN JS TOM SELECT DI SINI (Sebelum body tutup) --}}
        <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
    </body>
</html>