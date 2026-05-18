<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body class="font-sans antialiased bg-slate-950 text-slate-100">
        <div class="dark-page-shell flex min-h-screen flex-col">
            <div class="dark-page-content flex-1 flex items-center justify-center px-4 py-10">
                <div class="w-full max-w-md">
                    <a href="/" class="mb-6 block w-fit mx-auto">
                        <x-application-logo class="w-20 h-20 fill-current text-cyan-200" />
                    </a>

                    <div class="neon-card p-6 sm:p-8">
                        <div class="space-y-2 text-center">
                            <h1 class="text-3xl font-black tracking-[-0.05em] text-white">
                                {{ config('app.name', 'Event Ticketing') }}
                            </h1>
                            <p class="text-sm text-slate-400">
                                Login atau daftar untuk akses tiket dan dashboard.
                            </p>
                        </div>

                        <div class="mt-6">
                            {{ $slot }}
                        </div>
                    </div>

                    <div class="mt-6 text-center text-xs text-slate-500">
                        &copy; 2026 EventTicketing. Neon access for every experience.
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
