<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Nazmi Restaurant') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="h-full bg-primary text-foreground font-paragraph antialiased">
    <div class="min-h-screen">
        @include('components.header')

        <main>
            @yield('content')
        </main>

        @include('components.footer')
    </div>
</body>
</html>
