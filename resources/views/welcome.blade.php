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
        <nav class="bg-gradient-to-r from-indigo-700 via-indigo-700 to-purple-700 shadow-lg shadow-indigo-900/10 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <div class="flex items-center">
                        <a href="{{ route('home') }}" class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg bg-white/15 ring-1 ring-white/25 flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                                </svg>
                            </div>
                            <span class="text-sm md:text-base font-bold text-white">EventTicketing</span>
                        </a>
                    </div>
                    <div class="flex items-center gap-4">
                        @auth
                            @role('Customer')
                                <a href="{{ route('customer.dashboard') }}" class="hidden sm:inline-flex items-center border-b-2 border-transparent px-1 pt-1 text-sm font-medium text-indigo-100 hover:border-white/60 hover:text-white transition">
                                    My Tickets
                                </a>
                            @endrole

                            @php
                                $accountRoute = Auth::user()->hasRole('Customer') ? route('customer.dashboard') : route('dashboard');
                                $accountLabel = Auth::user()->hasRole('Customer') ? 'My Tickets' : 'Dashboard';
                            @endphp

                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center gap-2 px-3 py-2 border border-white/20 text-sm leading-4 font-medium rounded-lg text-white bg-white/10 hover:bg-white/15 focus:outline-none transition ease-in-out duration-150">
                                        <div class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center text-white font-bold text-xs">
                                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                        </div>
                                        <span class="hidden sm:inline">{{ Auth::user()->name }}</span>
                                        <svg class="fill-current h-4 w-4 text-indigo-100" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    <div class="px-4 py-2 border-b border-gray-100">
                                        <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                                        <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                                    </div>
                                    <x-dropdown-link :href="$accountRoute">
                                        <svg class="w-4 h-4 me-2 inline-block text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                                        </svg>
                                        {{ $accountLabel }}
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('profile.edit')">
                                        <svg class="w-4 h-4 me-2 inline-block text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        Profile
                                    </x-dropdown-link>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                            <svg class="w-4 h-4 me-2 inline-block text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                            </svg>
                                            Log Out
                                        </x-dropdown-link>
                                    </form>
                                </x-slot>
                            </x-dropdown>
                        @else
                            <a href="{{ route('login') }}" class="text-indigo-100 hover:text-white transition">
                                Login
                            </a>
                            <a href="{{ route('register') }}" class="bg-white text-indigo-700 px-4 py-2 rounded-lg font-semibold hover:bg-indigo-50 transition">
                                Register
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <div class="relative overflow-hidden bg-gradient-to-r from-indigo-700 via-indigo-600 to-purple-700 text-white py-20">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(255,255,255,0.18),transparent_32rem)]"></div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="relative text-center">
                    <h1 class="text-4xl md:text-5xl font-bold mb-4">
                        Discover Amazing Events
                    </h1>
                    <p class="text-lg md:text-xl text-indigo-100 mb-8">
                        Find and book tickets to the best events near you
                    </p>

                    <!-- Search Bar -->
                    <form method="GET" action="{{ route('home') }}" class="flex flex-col sm:flex-row gap-2 justify-center">
                        <input
                            type="text"
                            name="search"
                            placeholder="Search by event title or location..."
                            value="{{ request('search') }}"
                            class="px-4 py-3 rounded-lg text-gray-900 flex-1 sm:max-w-md focus:outline-none focus:ring-2 focus:ring-white/70"
                        />
                        <button
                            type="submit"
                            class="bg-white text-indigo-700 px-8 py-3 rounded-lg font-semibold hover:bg-indigo-50 transition"
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
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between mb-8">
                        <h2 class="text-3xl font-bold text-gray-900">
                            @if(request('search'))
                                Search Results
                            @else
                                Featured Events
                            @endif
                        </h2>
                        <x-badge color="indigo">{{ $events->total() }} events</x-badge>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($events as $event)
                            <x-card class="hover:shadow-xl transition">
                                <!-- Event Image -->
                                <a
                                    href="{{ route('events.show', $event) }}"
                                    class="relative h-48 bg-gray-200 overflow-hidden block focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                    aria-label="View details for {{ $event->title }}"
                                >
                                    @if($event->banner_image)
                                        <img
                                            src="{{ asset('storage/' . $event->banner_image) }}"
                                            alt="{{ $event->title }}"
                                            class="w-full h-full object-cover hover:scale-105 transition"
                                        />
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                                            <svg class="w-16 h-16 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16m10-16v16M9 4h6m-6 8h6"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </a>

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
                                                <span class="text-2xl font-bold text-indigo-600 ml-2">
                                                    ${{ $event->ticketCategories->min('price') }}
                                                </span>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- View Details Button -->
                                    <a
                                        href="{{ route('events.show', $event) }}"
                                        class="w-full bg-gradient-to-r from-indigo-600 to-indigo-700 text-white py-2 px-4 rounded-lg font-semibold text-center hover:from-indigo-700 hover:to-purple-700 transition block"
                                    >
                                        View Details
                                    </a>
                                </div>
                            </x-card>
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
                            class="inline-block bg-gradient-to-r from-indigo-600 to-indigo-700 text-white px-6 py-2 rounded-lg hover:from-indigo-700 hover:to-purple-700 transition"
                        >
                            Clear Search
                        </a>
                    @endif
                </div>
            @endif
        </div>

        <!-- Footer -->
        <footer class="bg-gray-950 text-gray-300 mt-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="text-center">
                    <p class="text-sm">&copy; 2026 EventTicketing. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </body>
</html>
