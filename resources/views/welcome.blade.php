<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Event Ticketing Platform') }} - Discover Events</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-slate-950 text-slate-100">
        <div class="dark-page-shell flex min-h-screen flex-col">
            <div class="dark-page-content">
                <x-public-navigation />

                <section class="relative overflow-hidden px-4 py-16 sm:px-6 lg:px-8 lg:py-24">
                    <div class="absolute left-1/2 top-8 h-72 w-72 -translate-x-1/2 rounded-full bg-cyan-400/20 orb-glow"></div>
                    <div class="absolute right-10 top-28 h-44 w-44 rounded-full bg-fuchsia-500/20 orb-glow"></div>

                    <div class="mx-auto grid max-w-7xl items-center gap-12 lg:grid-cols-[1.05fr_0.95fr]">
                        <div>
                            <div class="neon-chip mb-6 w-fit">
                                <span class="h-2 w-2 rounded-full bg-lime-300 shadow-lg shadow-lime-300/70"></span>
                                Live experiences, reimagined
                            </div>
                            <h1 class="max-w-4xl text-5xl font-black tracking-tight text-white sm:text-6xl lg:text-7xl">
                                Find your next
                                <span class="block bg-gradient-to-r from-cyan-200 via-fuchsia-300 to-lime-200 bg-clip-text text-transparent">neon-worthy event.</span>
                            </h1>
                            <p class="mt-6 max-w-2xl text-base leading-8 text-slate-300 sm:text-lg">
                                Jelajahi event aktif, pilih tiket digital, dan nikmati alur pembelian yang lebih cepat, rapi, dan terasa futuristik di semua perangkat.
                            </p>

                            <form method="GET" action="{{ route('home') }}" class="mt-9 max-w-2xl rounded-3xl border border-white/10 bg-white/5 p-2 shadow-2xl shadow-cyan-950/30 backdrop-blur-xl">
                                <div class="flex flex-col gap-2 sm:flex-row">
                                    <div class="relative flex-1">
                                        <svg class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-cyan-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197M16.5 10.5a6 6 0 11-12 0 6 6 0 0112 0z"/>
                                        </svg>
                                        <input
                                            type="text"
                                            name="search"
                                            placeholder="Search by title or location..."
                                            value="{{ request('search') }}"
                                            class="dark-form-input pl-12"
                                        />
                                    </div>
                                    <button type="submit" class="neon-button sm:min-w-36">
                                        Search
                                    </button>
                                </div>
                            </form>

                            <div class="mt-8 grid max-w-lg grid-cols-3 gap-3 text-center">
                                <div class="rounded-2xl border border-white/10 bg-white/5 p-4 backdrop-blur">
                                    <p class="text-2xl font-black text-cyan-200">{{ $events->total() }}</p>
                                    <p class="mt-1 text-[11px] font-bold uppercase tracking-wider text-slate-500">Events</p>
                                </div>
                                <div class="rounded-2xl border border-white/10 bg-white/5 p-4 backdrop-blur">
                                    <p class="text-2xl font-black text-fuchsia-200">24/7</p>
                                    <p class="mt-1 text-[11px] font-bold uppercase tracking-wider text-slate-500">Access</p>
                                </div>
                                <div class="rounded-2xl border border-white/10 bg-white/5 p-4 backdrop-blur">
                                    <p class="text-2xl font-black text-lime-200">QR</p>
                                    <p class="mt-1 text-[11px] font-bold uppercase tracking-wider text-slate-500">Tickets</p>
                                </div>
                            </div>
                        </div>

                        <div class="relative hidden lg:block">
                            <div class="neon-card p-6">
                                <div class="mb-6 flex items-center justify-between">
                                    <div>
                                        <p class="text-xs font-black uppercase tracking-[0.3em] text-cyan-200">Tonight</p>
                                        <h2 class="mt-1 text-2xl font-black text-white">Event Radar</h2>
                                    </div>
                                    <span class="rounded-full border border-lime-300/30 bg-lime-300/10 px-3 py-1 text-xs font-black text-lime-100">Online</span>
                                </div>
                                <div class="space-y-4">
                                    @foreach($events->take(3) as $event)
                                        <a href="{{ route('events.show', $event) }}" class="group flex gap-4 rounded-3xl border border-white/10 bg-slate-950/60 p-3 transition hover:border-cyan-300/40 hover:bg-cyan-300/10">
                                            <div class="h-20 w-20 shrink-0 overflow-hidden rounded-2xl bg-gradient-to-br from-cyan-400 to-fuchsia-500">
                                                @if($event->banner_image)
                                                    <img src="{{ asset('storage/' . $event->banner_image) }}" alt="{{ $event->title }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-110">
                                                @endif
                                            </div>
                                            <div class="min-w-0 py-1">
                                                <p class="truncate font-bold text-white">{{ $event->title }}</p>
                                                <p class="mt-1 truncate text-sm text-slate-400">{{ $event->location }}</p>
                                                <p class="mt-2 text-xs font-bold text-cyan-200">{{ $event->start_time->format('d M Y • h:i A') }}</p>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <main class="mx-auto w-full max-w-7xl px-4 pb-16 sm:px-6 lg:px-8">
                    @if($events->count() > 0)
                        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                            <div>
                                <p class="text-sm font-bold uppercase tracking-[0.28em] text-fuchsia-200">
                                    @if(request('search')) Search Results @else Featured Drops @endif
                                </p>
                                <h2 class="mt-2 text-3xl font-black text-white sm:text-4xl">
                                    @if(request('search')) Matching "{{ request('search') }}" @else Curated event passes @endif
                                </h2>
                            </div>
                            <span class="neon-chip w-fit">{{ $events->total() }} events available</span>
                        </div>

                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                            @foreach($events as $event)
                                @php
                                    $startingPrice = $event->ticketCategories->count() > 0 ? $event->ticketCategories->min('price') : null;
                                @endphp
                                <article class="group neon-card transition duration-500 hover:-translate-y-2 hover:border-cyan-300/40 hover:shadow-cyan-500/20">
                                    <a href="{{ route('events.show', $event) }}" class="relative block h-56 overflow-hidden" aria-label="View details for {{ $event->title }}">
                                        @if($event->banner_image)
                                            <img src="{{ asset('storage/' . $event->banner_image) }}" alt="{{ $event->title }}" class="h-full w-full object-cover transition duration-700 group-hover:scale-110">
                                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/30 to-transparent"></div>
                                        @else
                                            <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-cyan-500 via-fuchsia-600 to-violet-800">
                                                <svg class="h-16 w-16 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16m10-16v16M9 4h6m-6 8h6"></path>
                                                </svg>
                                            </div>
                                        @endif
                                        <div class="absolute left-4 top-4 rounded-full border border-white/15 bg-slate-950/70 px-3 py-1 text-xs font-black uppercase tracking-wider text-cyan-100 backdrop-blur">
                                            {{ $event->start_time->format('d M') }}
                                        </div>
                                    </a>

                                    <div class="p-6">
                                        <h3 class="line-clamp-2 text-xl font-black text-white">{{ $event->title }}</h3>

                                        <div class="mt-5 space-y-3 text-sm text-slate-300">
                                            <div class="flex items-center gap-3">
                                                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-cyan-300/10 text-cyan-200">
                                                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path></svg>
                                                </span>
                                                <span class="truncate">{{ $event->location }}</span>
                                            </div>
                                            <div class="flex items-center gap-3">
                                                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-fuchsia-300/10 text-fuchsia-200">
                                                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v2h16V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
                                                </span>
                                                <span>{{ $event->start_time->format('M d, Y \a\t h:i A') }}</span>
                                            </div>
                                        </div>

                                        <div class="mt-6 flex items-end justify-between border-t border-white/10 pt-5">
                                            <div>
                                                <p class="text-xs font-bold uppercase tracking-widest text-slate-500">Starting at</p>
                                                <p class="mt-1 text-2xl font-black text-cyan-200">
                                                    @if($startingPrice !== null)
                                                        Rp {{ number_format($startingPrice, 0, ',', '.') }}
                                                    @else
                                                        TBA
                                                    @endif
                                                </p>
                                            </div>
                                            <a href="{{ route('events.show', $event) }}" class="rounded-2xl border border-fuchsia-300/30 bg-fuchsia-300/10 px-4 py-3 text-sm font-black text-fuchsia-100 transition hover:bg-fuchsia-300/20">
                                                Details
                                            </a>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>

                        <div class="pagination-dark mt-12">
                            {{ $events->links() }}
                        </div>
                    @else
                        <div class="neon-card mx-auto max-w-2xl p-10 text-center">
                            <div class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-3xl border border-cyan-300/30 bg-cyan-300/10 text-cyan-100">
                                <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                </svg>
                            </div>
                            <h3 class="text-3xl font-black text-white">No events found</h3>
                            <p class="mt-3 text-slate-400">
                                @if(request('search'))
                                    We couldn't find any events matching "{{ request('search') }}". Try searching with different keywords.
                                @else
                                    There are no active events available at the moment.
                                @endif
                            </p>
                            @if(request('search'))
                                <a href="{{ route('home') }}" class="neon-button mt-7">Clear Search</a>
                            @endif
                        </div>
                    @endif
                </main>
            </div>

            <footer class="dark-page-content mt-auto border-t border-white/10 bg-slate-950/75 text-slate-400 backdrop-blur-xl">
                <div class="mx-auto max-w-7xl px-4 py-8 text-center sm:px-6 lg:px-8">
                    <p class="text-sm">&copy; 2026 EventTicketing. Built for smooth neon journeys.</p>
                </div>
            </footer>
        </div>
    </body>
</html>