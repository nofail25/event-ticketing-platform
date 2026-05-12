<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'EventTix') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-900 bg-gray-50 flex flex-col min-h-screen">
    <nav class="bg-white border-b border-gray-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="shrink-0 flex items-center">
                        <a href="{{ route('home') }}" class="font-bold text-2xl text-indigo-600">EventTix</a>
                    </div>
                </div>
                <div class="hidden sm:flex sm:items-center sm:ml-6 space-x-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-sm font-medium text-gray-700 hover:text-indigo-600">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 hover:text-indigo-600">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="text-sm font-medium text-white bg-indigo-600 px-4 py-2 rounded-md hover:bg-indigo-700">Register</a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-grow">
        {{ $slot }}
    </main>
    
    <footer class="bg-white text-center py-8 mt-auto border-t text-sm text-gray-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            &copy; {{ date('Y') }} EventTix. All rights reserved.
        </div>
    </footer>
</body>
</html>
