<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    @php
        $isCustomerExperience = Auth::check()
            && Auth::user()->hasRole('Customer')
            && request()->routeIs('customer.*');
    @endphp
    <body class="font-sans antialiased {{ $isCustomerExperience ? 'bg-slate-950 text-slate-100' : '' }}">
        <div class="{{ $isCustomerExperience ? 'dark-page-shell' : 'min-h-screen bg-gray-100' }} flex flex-col">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="{{ $isCustomerExperience ? 'dark-page-content border-b border-white/10 bg-slate-950/55 shadow-2xl shadow-cyan-950/20 backdrop-blur-xl' : 'bg-white shadow' }}">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="{{ $isCustomerExperience ? 'dark-page-content' : '' }} flex-1">
                {{ $slot }}
            </main>

            <footer class="{{ $isCustomerExperience ? 'dark-page-content border-t border-white/10 bg-slate-950/75 text-slate-400 backdrop-blur-xl' : 'bg-gray-950 text-gray-300' }}">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                    <p class="text-center text-sm">&copy; 2026 EventTicketing. {{ $isCustomerExperience ? 'Neon access for every experience.' : 'All rights reserved.' }}</p>
                </div>
            </footer>
        </div>
    </body>
</html>
