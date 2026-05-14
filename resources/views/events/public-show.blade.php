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
        <nav class="bg-gradient-to-r from-indigo-700 via-indigo-700 to-purple-700 shadow-lg shadow-indigo-900/10 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <div class="flex items-center gap-4">
                        <a href="{{ route('home') }}" class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg bg-white/15 ring-1 ring-white/25 flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                                </svg>
                            </div>
                            <span class="text-sm md:text-base font-bold text-white">EventTicketing</span>
                        </a>
                        <a href="{{ route('home') }}" class="hidden sm:inline-flex text-sm font-medium text-indigo-100 hover:text-white transition">
                            Back to Events
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
                            <div class="w-full h-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
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
                                            <span class="text-2xl font-bold text-indigo-600">
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
                                                    class="w-full bg-gradient-to-r from-indigo-600 to-indigo-700 text-white py-2 px-4 rounded-lg font-semibold text-center hover:from-indigo-700 hover:to-purple-700 transition block"
                                                >
                                                    Buy Ticket
                                                </a>
                                            @else
                                                <a
                                                    href="{{ route('login') }}"
                                                    class="w-full bg-gradient-to-r from-indigo-600 to-indigo-700 text-white py-2 px-4 rounded-lg font-semibold text-center hover:from-indigo-700 hover:to-purple-700 transition block"
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
                            <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4 text-center">
                                <p class="text-gray-600">
                                    No tickets are currently available for this event.
                                </p>
                            </div>
                        @endif

                        @if($userEventTickets->isNotEmpty())
                            <div class="mt-6 bg-indigo-50 border border-indigo-100 rounded-lg p-4">
                                <h3 class="font-semibold text-gray-900 mb-3">Your Tickets</h3>
                                <div class="space-y-4">
                                    @foreach($userEventTickets as $ticket)
                                        <div class="bg-white rounded-lg border border-indigo-100 p-4">
                                            <div class="flex items-start justify-between gap-3 mb-3">
                                                <div>
                                                    <p class="text-sm font-semibold text-gray-900">{{ $ticket->ticketCategory->name }}</p>
                                                    <p class="text-xs text-gray-500">{{ $ticket->order->invoice_number }}</p>
                                                </div>
                                                @if($ticket->is_scanned)
                                                    <span class="text-xs font-semibold px-2 py-1 rounded-full bg-green-100 text-green-700">Scanned</span>
                                                @else
                                                    <span class="text-xs font-semibold px-2 py-1 rounded-full bg-indigo-100 text-indigo-700">Not Used</span>
                                                @endif
                                            </div>

                                            <div class="flex flex-col items-center">
                                                <div class="bg-white p-2 rounded-lg border border-gray-200 shadow-sm">
                                                    <img
                                                        src="{{ \App\Support\QrCode::svgDataUri($ticket->barcode_string, 160) }}"
                                                        alt="QR code for ticket {{ $ticket->id }}"
                                                        class="w-40 h-40"
                                                    >
                                                </div>
                                                <p class="font-mono text-xs text-gray-600 break-all mt-3 text-center">{{ $ticket->barcode_string }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
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
        <footer class="bg-gray-950 text-gray-300 mt-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="text-center">
                    <p class="text-sm">&copy; 2026 EventTicketing. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </body>
</html>
