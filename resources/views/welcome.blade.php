<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Event Ticketing Platform') }} - Discover Events</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            [x-cloak] { display: none !important; }
        </style>
    </head>
    <body class="font-sans antialiased bg-slate-50 text-slate-900" x-data="{ isSearching: false, searchQuery: '{{ request('search') }}' }">
        <div class="page-shell flex min-h-screen flex-col transition-colors duration-500" :class="isSearching ? 'bg-slate-100' : 'bg-slate-50'">
            <div class="page-content z-10">
                <x-public-navigation />

                <!-- Hero Section -->
                <section class="relative overflow-hidden px-4 py-16 sm:px-6 lg:px-8 lg:py-24 bg-white border-b border-slate-200 shadow-sm rounded-b-3xl">
                    <div class="absolute inset-0 bg-[linear-gradient(to_right,#80808012_1px,transparent_1px),linear-gradient(to_bottom,#80808012_1px,transparent_1px)] bg-[size:24px_24px]"></div>
                    <div class="absolute left-0 right-0 top-0 -z-10 m-auto h-[400px] w-[400px] rounded-full bg-indigo-500 opacity-10 blur-[120px] transition-all duration-1000 ease-in-out" :class="isSearching ? 'scale-150 opacity-20 bg-purple-500' : ''"></div>

                    <div class="relative mx-auto grid max-w-7xl items-center gap-16 lg:grid-cols-[1.1fr_0.9fr]">
                        <div x-data="{ show: false }" x-init="setTimeout(() => show = true, 100)">
                            <div class="info-badge mb-6 w-fit transition-all duration-700 transform translate-y-4 opacity-0" :class="show ? 'translate-y-0 opacity-100' : ''">
                                <span class="h-2 w-2 rounded-full bg-indigo-500 animate-pulse"></span>
                                Live experiences, reimagined
                            </div>
                            <h1 class="max-w-4xl text-5xl font-black tracking-tight text-slate-900 sm:text-6xl lg:text-7xl transition-all duration-700 delay-100 transform translate-y-4 opacity-0" :class="show ? 'translate-y-0 opacity-100' : ''">
                                Find your next
                                <span class="block text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600">unforgettable event.</span>
                            </h1>
                            <p class="mt-6 max-w-2xl text-base leading-8 text-slate-600 sm:text-lg transition-all duration-700 delay-200 transform translate-y-4 opacity-0" :class="show ? 'translate-y-0 opacity-100' : ''">
                                Jelajahi event aktif, pilih tiket digital, dan nikmati alur pembelian yang lebih cepat, rapi, dan modern di semua perangkat.
                            </p>

                            <!-- Smart Search Bar -->
                            <form method="GET" action="{{ route('home') }}" class="mt-9 max-w-2xl transition-all duration-700 delay-300 transform translate-y-4 opacity-0" :class="show ? 'translate-y-0 opacity-100' : ''" @submit="isSearching = true">
                                <div class="relative flex flex-col gap-2 rounded-3xl border border-slate-200 bg-white p-2 shadow-sm transition-all duration-300 focus-within:ring-4 focus-within:ring-indigo-500/20 focus-within:border-indigo-400 sm:flex-row hover:shadow-md">
                                    <div class="relative flex-1 flex items-center">
                                        <svg class="pointer-events-none absolute left-4 h-5 w-5 text-slate-400 transition-colors duration-300" :class="searchQuery.length > 0 ? 'text-indigo-500' : ''" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197M16.5 10.5a6 6 0 11-12 0 6 6 0 0112 0z"/>
                                        </svg>
                                        <input
                                            type="text"
                                            name="search"
                                            placeholder="Search by title or location..."
                                            x-model="searchQuery"
                                            @focus="isSearching = true"
                                            @blur="if(searchQuery.length === 0) isSearching = false"
                                            class="form-input w-full border-transparent bg-transparent pl-12 h-12 text-slate-900 placeholder:text-slate-400 focus:border-transparent focus:ring-0"
                                        />
                                        <!-- Loading Spinner -->
                                        <div x-show="isSearching && searchQuery.length > 0" x-cloak class="absolute right-4 h-5 w-5 animate-spin rounded-full border-2 border-indigo-200 border-t-indigo-600"></div>
                                    </div>
                                    <button type="submit" class="primary-button sm:min-w-36 h-12 relative overflow-hidden group">
                                        <span class="relative z-10 transition-transform duration-300 group-hover:-translate-y-10 block">Search</span>
                                        <span class="absolute inset-0 z-10 flex items-center justify-center transition-transform duration-300 translate-y-10 group-hover:translate-y-0">Let's Go 🚀</span>
                                    </button>
                                </div>
                            </form>

                            <div class="mt-10 flex flex-wrap gap-4 text-sm font-semibold text-slate-500 transition-all duration-700 delay-400 transform translate-y-4 opacity-0" :class="show ? 'translate-y-0 opacity-100' : ''">
                                <span>Popular:</span>
                                <a href="?search=Music" class="hover:text-indigo-600 transition-colors cursor-pointer border-b border-dashed border-slate-300 hover:border-indigo-600">Music Festivals</a>
                                <a href="?search=Tech" class="hover:text-indigo-600 transition-colors cursor-pointer border-b border-dashed border-slate-300 hover:border-indigo-600">Tech Summits</a>
                                <a href="?search=Workshop" class="hover:text-indigo-600 transition-colors cursor-pointer border-b border-dashed border-slate-300 hover:border-indigo-600">Workshops</a>
                            </div>
                        </div>

                        <!-- Bento Grid Radar -->
                        <div class="relative hidden lg:block perspective-1000">
                            <div class="grid grid-cols-2 gap-4 transform-gpu transition-transform duration-700 hover:rotate-y-6 hover:rotate-x-6">
                                <div class="col-span-2 clean-card p-6 bg-gradient-to-br from-indigo-500 to-purple-600 text-white shadow-xl shadow-indigo-500/20 border-0">
                                    <div class="mb-4 flex items-center justify-between">
                                        <div>
                                            <p class="text-xs font-black uppercase tracking-[0.3em] text-indigo-100">Featured</p>
                                            <h2 class="mt-1 text-2xl font-black text-white">Event Radar</h2>
                                        </div>
                                        <span class="rounded-full bg-white/20 backdrop-blur-md px-3 py-1 text-xs font-bold text-white">Live Now</span>
                                    </div>
                                    @if($events->count() > 0)
                                        @php $topEvent = $events->first(); @endphp
                                        <a href="{{ route('events.show', $topEvent) }}" class="group block mt-4">
                                            <div class="aspect-video w-full overflow-hidden rounded-2xl bg-white/10">
                                                @if($topEvent->banner_image)
                                                    <img src="{{ asset('storage/' . $topEvent->banner_image) }}" alt="{{ $topEvent->title }}" class="h-full w-full object-cover transition duration-700 group-hover:scale-105 opacity-90 group-hover:opacity-100">
                                                @endif
                                            </div>
                                            <div class="mt-4 flex items-center justify-between">
                                                <div class="min-w-0 pr-4">
                                                    <p class="truncate font-bold text-lg text-white group-hover:text-indigo-100 transition-colors">{{ $topEvent->title }}</p>
                                                    <p class="mt-1 text-sm text-indigo-100">{{ $topEvent->start_time->format('d M Y') }}</p>
                                                </div>
                                                <div class="h-10 w-10 rounded-full bg-white text-indigo-600 flex items-center justify-center transform group-hover:translate-x-1 transition-transform">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                                </div>
                                            </div>
                                        </a>
                                    @endif
                                </div>
                                
                                @foreach($events->skip(1)->take(2) as $idx => $event)
                                <a href="{{ route('events.show', $event) }}" class="group clean-card p-4 transition-all hover:-translate-y-1 hover:shadow-lg border border-slate-200">
                                    <div class="aspect-video w-full overflow-hidden rounded-xl bg-slate-100 mb-3">
                                        @if($event->banner_image)
                                            <img src="{{ asset('storage/' . $event->banner_image) }}" alt="{{ $event->title }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-110">
                                        @endif
                                    </div>
                                    <p class="truncate font-bold text-sm text-slate-900">{{ $event->title }}</p>
                                    <p class="mt-1 text-xs text-slate-500">{{ $event->location }}</p>
                                </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </section>

                <main class="mx-auto w-full max-w-7xl px-4 py-16 sm:px-6 lg:px-8 relative z-20">
                    @if($events->count() > 0)
                        <div class="mb-10 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between" x-data="{ inView: false }" x-intersect.once="inView = true">
                            <div class="transition-all duration-700 transform translate-y-4 opacity-0" :class="inView ? 'translate-y-0 opacity-100' : ''">
                                <p class="text-sm font-bold uppercase tracking-[0.2em] text-indigo-600">
                                    @if(request('search')) Search Results @else Upcoming @endif
                                </p>
                                <h2 class="mt-2 text-3xl font-black text-slate-900 sm:text-4xl">
                                    @if(request('search')) Matching "{{ request('search') }}" @else Explore Experiences @endif
                                </h2>
                            </div>
                            
                            <!-- Sort Filter -->
                            <div class="flex items-center gap-2 transition-all duration-700 delay-100 transform translate-y-4 opacity-0" :class="inView ? 'translate-y-0 opacity-100' : ''">
                                <span class="text-sm font-semibold text-slate-500">Sort by:</span>
                                <select class="form-select border-slate-200 rounded-xl bg-white shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option>Date (Closest)</option>
                                    <option>Price (Low to High)</option>
                                    <option>Price (High to Low)</option>
                                    <option>Recently Added</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
                            @foreach($events as $index => $event)
                                @php
                                    $startingPrice = $event->ticketCategories->count() > 0 ? $event->ticketCategories->min('price') : null;
                                @endphp
                                <article class="group clean-card border border-slate-200 overflow-hidden flex flex-col h-full bg-white hover:border-indigo-300 hover:shadow-xl transition-all duration-500 transform hover:-translate-y-2"
                                         x-data="{ shown: false }" x-intersect.once="setTimeout(() => shown = true, {{ $index * 100 }})">
                                    
                                    <div class="relative block aspect-video overflow-hidden bg-slate-100">
                                        <a href="{{ route('events.show', $event) }}" class="absolute inset-0 z-10" aria-label="View details for {{ $event->title }}"></a>
                                        @if($event->banner_image)
                                            <img src="{{ asset('storage/' . $event->banner_image) }}" alt="{{ $event->title }}" class="h-full w-full object-cover transition duration-700 group-hover:scale-105 group-hover:rotate-1">
                                        @else
                                            <div class="flex h-full w-full items-center justify-center">
                                                <svg class="h-16 w-16 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                        
                                        <!-- Overlay Gradient -->
                                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                                        
                                        <!-- Floating Date Badge -->
                                        <div class="absolute left-4 top-4 rounded-2xl bg-white/95 px-3 py-2 text-center shadow-lg backdrop-blur">
                                            <p class="text-[10px] font-black uppercase tracking-widest text-indigo-600">{{ $event->start_time->format('M') }}</p>
                                            <p class="text-xl font-black text-slate-900 leading-none">{{ $event->start_time->format('d') }}</p>
                                        </div>
                                    </div>

                                    <div class="p-6 flex flex-col flex-1">
                                        <h3 class="line-clamp-2 text-xl font-black text-slate-900 mb-4 group-hover:text-indigo-600 transition-colors">{{ $event->title }}</h3>

                                        <div class="space-y-3 text-sm text-slate-600 mb-6">
                                            <div class="flex items-start gap-3">
                                                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-slate-50 text-slate-400 group-hover:bg-indigo-50 group-hover:text-indigo-500 transition-colors">
                                                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path></svg>
                                                </span>
                                                <span class="line-clamp-2 mt-1.5">{{ $event->location }}</span>
                                            </div>
                                            <div class="flex items-center gap-3">
                                                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-slate-50 text-slate-400 group-hover:bg-indigo-50 group-hover:text-indigo-500 transition-colors">
                                                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path></svg>
                                                </span>
                                                <span class="mt-1">{{ $event->start_time->format('h:i A') }}</span>
                                            </div>
                                        </div>

                                        <div class="mt-auto flex items-center justify-between border-t border-slate-100 pt-5">
                                            <div>
                                                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-500">Tickets from</p>
                                                <p class="text-lg font-black text-slate-900 group-hover:text-indigo-600 transition-colors">
                                                    @if($startingPrice !== null)
                                                        Rp {{ number_format($startingPrice, 0, ',', '.') }}
                                                    @else
                                                        Free / TBA
                                                    @endif
                                                </p>
                                            </div>
                                            
                                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-50 text-slate-400 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300">
                                                <svg class="w-5 h-5 transform group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>

                        <div class="mt-12 flex justify-center">
                            {{ $events->links() }}
                        </div>
                    @else
                        <div class="clean-card mx-auto max-w-2xl p-12 text-center border-slate-200 bg-white shadow-xl shadow-slate-200/50">
                            <div class="mx-auto mb-6 flex h-24 w-24 items-center justify-center rounded-full bg-indigo-50 text-indigo-500">
                                <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"></path>
                                </svg>
                            </div>
                            <h3 class="text-3xl font-black text-slate-900">No events found</h3>
                            <p class="mt-4 text-slate-500 max-w-md mx-auto leading-relaxed">
                                @if(request('search'))
                                    We couldn't find any events matching "<span class="font-bold text-slate-900">{{ request('search') }}</span>". Try searching with different keywords or exploring categories.
                                @else
                                    There are no active events available at the moment. Please check back later!
                                @endif
                            </p>
                            @if(request('search'))
                                <a href="{{ route('home') }}" class="primary-button mt-8 inline-flex px-8">Clear Filters</a>
                            @endif
                        </div>
                    @endif
                </main>
            </div>

            <footer class="page-content mt-auto border-t border-slate-200 bg-white text-slate-500">
                <div class="mx-auto max-w-7xl px-4 py-8 text-center sm:px-6 lg:px-8">
                    <p class="text-sm font-medium">&copy; 2026 EventTicketing. Crafted with care.</p>
                </div>
            </footer>
        </div>
    </body>
</html>