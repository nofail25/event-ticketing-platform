<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $event->title }} - Event Ticketing Platform</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <!-- Navigation Bar -->
        <nav class="bg-white shadow-sm sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <div class="flex items-center gap-4">
                        <a href="{{ route('home') }}" class="text-2xl font-bold text-blue-600">
                            EventHub
                        </a>
                        <a href="{{ route('home') }}" class="text-gray-600 hover:text-gray-900">
                            ← Back to Events
                        </a>
                    </div>
                    <div class="flex items-center gap-4">
                        @auth
                            <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-gray-900">
                                Dashboard
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-gray-700 hover:text-gray-900">
                                    Logout
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-700 hover:text-gray-900">
                                Login
                            </a>
                            <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                                Register
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Event Detail Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2">
                    <!-- Event Banner Image -->
                    <div class="rounded-lg overflow-hidden mb-6 h-96 bg-gray-200">
                        @if($event->banner_image)
                            <img
                                src="{{ asset('storage/' . $event->banner_image) }}"
                                alt="{{ $event->title }}"
                                class="w-full h-full object-cover"
                            />
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                                <svg class="w-32 h-32 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16m10-16v16M9 4h6m-6 8h6"></path>
                                </svg>
                            </div>
                        @endif
                    </div>

                    <!-- Event Title -->
                    <h1 class="text-4xl font-bold text-gray-900 mb-4">
                        {{ $event->title }}
                    </h1>

                    <!-- Event Meta Information -->
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-8 pb-8 border-b">
                        <!-- Date -->
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Date & Time</p>
                            <p class="text-lg font-semibold text-gray-900">
                                {{ $event->start_time->format('M d, Y') }}
                            </p>
                            <p class="text-sm text-gray-600">
                                {{ $event->start_time->format('h:i A') }} - {{ $event->end_time->format('h:i A') }}
                            </p>
                        </div>

                        <!-- Location -->
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Location</p>
                            <p class="text-lg font-semibold text-gray-900">
                                {{ $event->location }}
                            </p>
                        </div>

                        <!-- Organizer -->
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Organizer</p>
                            <p class="text-lg font-semibold text-gray-900">
                                {{ $event->organizer->name }}
                            </p>
                        </div>
                    </div>

                    <!-- Event Description -->
                    <div class="mb-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">About This Event</h2>
                        <div class="prose prose-sm max-w-none text-gray-700 whitespace-pre-wrap">
                            {{ $event->description }}
                        </div>
                    </div>
                </div>

                <!-- Sidebar - Ticket Selection -->
                <div>
                    <div class="bg-white rounded-lg shadow-md p-6 sticky top-20">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">
                            Select Tickets
                        </h2>

                        @if($event->ticketCategories->count() > 0)
                            <div class="space-y-4">
                                @foreach($event->ticketCategories as $category)
                                    <div class="border rounded-lg p-4 hover:shadow-md transition">
                                        <!-- Category Header -->
                                        <div class="flex justify-between items-start mb-2">
                                            <h3 class="font-bold text-gray-900">
                                                {{ $category->name }}
                                            </h3>
                                            <span class="text-2xl font-bold text-blue-600">
                                                ${{ number_format($category->price, 2) }}
                                            </span>
                                        </div>

                                        <!-- Availability -->
                                        <div class="mb-4">
                                            @php
                                                $available = $category->quota - $category->ticketDetails->count();
                                            @endphp
                                            @if($available > 0)
                                                <p class="text-sm text-green-600 font-semibold">
                                                    ✓ {{ $available }} tickets available
                                                </p>
                                            @else
                                                <p class="text-sm text-red-600 font-semibold">
                                                    ✗ Sold Out
                                                </p>
                                            @endif
                                        </div>

                                        <!-- Buy Ticket Button -->
                                        @if($available > 0)
                                            @auth
                                                <a
                                                    href="{{ route('checkout.create', $category) }}"
                                                    class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg font-semibold text-center hover:bg-blue-700 transition block"
                                                >
                                                    Buy Ticket
                                                </a>
                                            @else
                                                <a
                                                    href="{{ route('login') }}"
                                                    class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg font-semibold text-center hover:bg-blue-700 transition block"
                                                >
                                                    Buy Ticket (Login)
                                                </a>
                                            @endauth
                                        @else
                                            <button
                                                disabled
                                                class="w-full bg-gray-300 text-gray-600 py-2 px-4 rounded-lg font-semibold text-center cursor-not-allowed"
                                            >
                                                Sold Out
                                            </button>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-center">
                                <p class="text-gray-600">
                                    No tickets are currently available for this event.
                                </p>
                            </div>
                        @endif

                        <!-- Important Notice -->
                        <div class="mt-6 bg-gray-50 rounded-lg p-4 text-sm text-gray-600">
                            <p class="font-semibold text-gray-900 mb-2">ℹ Important</p>
                            <ul class="list-disc list-inside space-y-1 text-xs">
                                <li>Tickets are non-refundable</li>
                                <li>One ticket per purchase</li>
                                <li>Digital tickets will be sent via email</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-gray-900 text-gray-300 mt-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="text-center">
                    <p>&copy; {{ date('Y') }} {{ config('app.name', 'Event Ticketing Platform') }}. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </body>
</html>
