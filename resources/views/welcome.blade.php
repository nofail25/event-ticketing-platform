<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Event Ticketing Platform') }} - Discover Events</title>

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
                    <div class="flex items-center">
                        <a href="{{ route('home') }}" class="text-2xl font-bold text-blue-600">
                            EventHub
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

        <!-- Hero Section -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h1 class="text-4xl md:text-5xl font-bold mb-4">
                        Discover Amazing Events
                    </h1>
                    <p class="text-xl text-blue-100 mb-8">
                        Find and book tickets to the best events near you
                    </p>

                    <!-- Search Bar -->
                    <form method="GET" action="{{ route('home') }}" class="flex flex-col sm:flex-row gap-2 justify-center">
                        <input
                            type="text"
                            name="search"
                            placeholder="Search by event title or location..."
                            value="{{ request('search') }}"
                            class="px-4 py-3 rounded-lg text-gray-900 flex-1 sm:max-w-md focus:outline-none focus:ring-2 focus:ring-blue-300"
                        />
                        <button
                            type="submit"
                            class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-blue-50 transition"
                        >
                            Search
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            @if($events->count() > 0)
                <!-- Events Grid -->
                <div class="mb-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-8">
                        @if(request('search'))
                            Search Results
                        @else
                            Featured Events
                        @endif
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($events as $event)
                            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                                <!-- Event Image -->
                                <div class="relative h-48 bg-gray-200 overflow-hidden">
                                    @if($event->banner_image)
                                        <img
                                            src="{{ asset('storage/' . $event->banner_image) }}"
                                            alt="{{ $event->title }}"
                                            class="w-full h-full object-cover hover:scale-105 transition"
                                        />
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                                            <svg class="w-16 h-16 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16m10-16v16M9 4h6m-6 8h6"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                <!-- Event Details -->
                                <div class="p-6">
                                    <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2">
                                        {{ $event->title }}
                                    </h3>

                                    <!-- Location -->
                                    <div class="flex items-center text-gray-600 mb-2">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="text-sm">{{ $event->location }}</span>
                                    </div>

                                    <!-- Date & Time -->
                                    <div class="flex items-center text-gray-600 mb-4">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v2h16V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="text-sm">
                                            {{ $event->start_time->format('M d, Y \a\t h:i A') }}
                                        </span>
                                    </div>

                                    <!-- Starting Price -->
                                    @if($event->ticketCategories->count() > 0)
                                        <div class="border-t pt-4 mb-4">
                                            <div class="flex items-baseline">
                                                <span class="text-gray-600 text-sm">Starting at</span>
                                                <span class="text-2xl font-bold text-blue-600 ml-2">
                                                    ${{ $event->ticketCategories->min('price') }}
                                                </span>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- View Details Button -->
                                    <a
                                        href="{{ route('events.show', $event) }}"
                                        class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg font-semibold text-center hover:bg-blue-700 transition block"
                                    >
                                        View Details
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Pagination -->
                <div class="mt-12">
                    {{ $events->links() }}
                </div>
            @else
                <!-- No Events Found -->
                <div class="text-center py-16">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">
                        No events found
                    </h3>
                    <p class="text-gray-600 mb-6">
                        @if(request('search'))
                            We couldn't find any events matching "{{ request('search') }}". Try searching with different keywords.
                        @else
                            There are no active events available at the moment.
                        @endif
                    </p>
                    @if(request('search'))
                        <a
                            href="{{ route('home') }}"
                            class="inline-block bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition"
                        >
                            Clear Search
                        </a>
                    @endif
                </div>
            @endif
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
