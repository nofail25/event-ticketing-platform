<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    @php
        $isAdmin = Auth::check() && Auth::user()->hasRole('Super Admin');
        $isOrganizer = Auth::check() && Auth::user()->hasRole('Event Organizer');
        $isCustomer = Auth::check() && Auth::user()->hasRole('Customer');
        $isGateScanner = Auth::check() && Auth::user()->hasRole('Gate Scanner');

        $isBackend = $isAdmin || $isOrganizer || $isGateScanner;
    @endphp
    <body class="font-sans antialiased bg-slate-50 text-slate-800">
        @if($isBackend)
            <div class="min-h-screen flex">
                @include('layouts.sidebar')

                <!-- Main Content -->
                <div class="flex-1 flex flex-col min-h-screen">
                    <!-- Page Heading -->
                    @isset($header)
                        <header class="bg-white border-b border-slate-200">
                            <div class="max-w-7xl mx-auto py-5 px-4 sm:px-6 lg:px-8">
                                {{ $header }}
                            </div>
                        </header>
                    @endisset

                    <!-- Page Content -->
                    <main class="p-6 flex-1">
                        {{ $slot }}
                    </main>
                </div>
            </div>
        @else
            <div class="min-h-screen bg-slate-50 flex flex-col">
                <x-public-navigation />

                <!-- Page Heading -->
                @isset($header)
                    <header class="bg-white border-b border-slate-200">
                        <div class="max-w-7xl mx-auto py-5 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <main class="flex-1">
                    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
                        {{ $slot }}
                    </div>
                </main>

                <footer class="bg-white border-t border-slate-200 text-slate-500">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 text-center">
                        <p class="text-sm">&copy; 2026 EventTicketing. All rights reserved.</p>
                    </div>
                </footer>
            </div>
        @endif
    </body>
</html>