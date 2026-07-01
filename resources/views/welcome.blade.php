<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Jelajahi event aktif, pilih tiket digital, dan nikmati alur pembelian yang lebih cepat di Eventmu.">

    <title>{{ config('app.name', 'Eventmu') }} — Temukan Event Tak Terlupakan</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }

        /* Per-character hover — works on both plain and gradient text */
        .char-split {
            display: inline-block;
            cursor: default;
            user-select: none;
            will-change: transform;
            transition: transform 250ms cubic-bezier(0.23, 1, 0.32, 1),
                        filter 250ms cubic-bezier(0.23, 1, 0.32, 1);
        }
        @media (hover: hover) and (pointer: fine) {
            .char-split:hover {
                transform: scale(1.35) translateY(-5px) rotate(var(--char-rot, -3deg));
                filter: brightness(1.4) drop-shadow(0 4px 12px rgba(99, 102, 241, 0.55));
                position: relative;
                z-index: 10;
            }
        }
    </style>
</head>
<body class="font-sans antialiased">
<div class="page-shell flex min-h-screen flex-col">
    <div class="page-content">

        {{-- Navigation --}}
        <x-public-navigation />

        {{-- ═══════════════════════════════════════════
             HERO SECTION
        ════════════════════════════════════════════ --}}
        <section class="relative overflow-hidden bg-white pt-16 pb-20 lg:pt-24 lg:pb-28">

            {{-- Grid background --}}
            <div class="pointer-events-none absolute inset-0"
                 style="background-image: linear-gradient(to right, #80808010 1px, transparent 1px), linear-gradient(to bottom, #80808010 1px, transparent 1px); background-size: 32px 32px;"></div>

            {{-- Floating orbs --}}
            <div class="pointer-events-none absolute -left-32 -top-32 h-96 w-96 rounded-full bg-indigo-400/20 blur-[100px] orb-a"></div>
            <div class="pointer-events-none absolute -right-24 top-8 h-80 w-80 rounded-full bg-violet-400/15 blur-[100px] orb-b"></div>
            <div class="pointer-events-none absolute bottom-0 left-1/2 h-64 w-64 -translate-x-1/2 rounded-full bg-indigo-300/10 blur-[80px]"></div>

            <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

                {{-- Hero headline --}}
                <div class="mx-auto max-w-3xl text-center">


                    <h1 class="font-helvetica text-5xl font-black tracking-tight text-slate-900 sm:text-6xl lg:text-7xl cursor-default select-none">
                        <span class="hero-line block hover-text-split" style="--hero-delay:100ms">Temukan event</span>
                        <span class="hero-line block text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 via-violet-600 to-purple-600 hover-text-split" style="--hero-delay:180ms">tak terlupakan</span>
                        <span class="hero-line block text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 via-violet-600 to-purple-600 hover-text-split" style="--hero-delay:240ms">selanjutnya.</span>
                    </h1>

                    <p class="hero-fade mx-auto mt-6 max-w-lg text-base leading-7 text-slate-500 sm:text-lg text-balance hover-text-split cursor-default select-none" style="--hero-delay:320ms">
                        Jelajahi event aktif, pilih tiket digital, dan nikmati alur pembelian yang lebih cepat, rapi, dan modern di semua perangkat.
                    </p>

                    {{-- Search bar --}}
                    <form method="GET" action="{{ route('home') }}" class="hero-fade mx-auto mt-8 max-w-xl relative" style="--hero-delay:400ms"
                          x-data="eventSearch()"
                          @click.away="showDropdown = false">
                        <div class="search-bar relative z-20">
                            <svg class="h-5 w-5 shrink-0 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                            </svg>
                            <input
                                type="search"
                                name="search"
                                id="hero-search"
                                value="{{ request('search') }}"
                                placeholder="Cari event, konser, workshop..."
                                autocomplete="off"
                                x-model="query"
                                @input.debounce.300ms="fetchSuggestions"
                                @focus="if(query.length > 0) showDropdown = true"
                            >
                            <button type="submit" class="shrink-0 rounded-xl bg-indigo-600 px-4 py-1.5 text-xs font-semibold text-white" style="transition: background-color 150ms ease, transform 100ms ease;" onmousedown="this.style.transform='scale(0.96)'" onmouseup="this.style.transform=''" onmouseleave="this.style.transform=''">
                                Cari
                            </button>
                        </div>

                        <!-- Dropdown Results -->
                        <div x-show="showDropdown" x-transition.opacity.duration.200ms style="display: none;" class="absolute left-0 right-0 mt-3 bg-white/95 backdrop-blur-xl rounded-2xl shadow-[0_10px_40px_-10px_rgba(0,0,0,0.15)] border border-slate-200/50 overflow-hidden z-30 text-left">
                            <template x-if="isLoading">
                                <div class="p-5 text-sm text-slate-500 flex flex-col items-center justify-center gap-3">
                                    <svg class="animate-spin h-5 w-5 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    <span>Sedang mencari...</span>
                                </div>
                            </template>
                            
                            <template x-if="!isLoading && results.length === 0 && query.length > 0">
                                <div class="p-6 text-center">
                                    <div class="mx-auto w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center mb-3">
                                        <span class="material-symbols-outlined text-slate-400">search_off</span>
                                    </div>
                                    <p class="text-sm text-slate-600">Tidak menemukan event <br/>"<span x-text="query" class="font-bold text-slate-900"></span>"</p>
                                </div>
                            </template>

                            <ul x-show="!isLoading && results.length > 0" class="divide-y divide-slate-100 max-h-80 overflow-y-auto">
                                <template x-for="event in results" :key="event.id">
                                    <li>
                                        <a :href="event.url" class="group flex items-center gap-4 p-4 hover:bg-indigo-50/50 transition-colors">
                                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-indigo-600 group-hover:bg-indigo-100 transition-colors">
                                                <span class="material-symbols-outlined text-xl">local_activity</span>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <h4 class="text-sm font-bold text-slate-900 truncate" x-text="event.title"></h4>
                                                <div class="mt-1 flex items-center gap-3 text-xs text-slate-500">
                                                    <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">calendar_today</span> <span x-text="event.start_time"></span></span>
                                                    <span class="flex items-center gap-1 truncate"><span class="material-symbols-outlined text-[14px]">location_on</span> <span x-text="event.location" class="truncate"></span></span>
                                                </div>
                                            </div>
                                            <div class="shrink-0 text-slate-400 group-hover:text-indigo-600 transition-colors">
                                                <span class="material-symbols-outlined text-lg">chevron_right</span>
                                            </div>
                                        </a>
                                    </li>
                                </template>
                            </ul>
                            
                            <template x-if="!isLoading && results.length > 0">
                                <div class="bg-slate-50 p-3 text-center border-t border-slate-100">
                                    <button type="submit" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 transition-colors">
                                        Lihat semua hasil untuk "<span x-text="query"></span>" &rarr;
                                    </button>
                                </div>
                            </template>
                        </div>
                    </form>

                    <script>
                        document.addEventListener('alpine:init', () => {
                            Alpine.data('eventSearch', () => ({
                                query: {!! json_encode(request('search') ?? '') !!},
                                results: [],
                                showDropdown: false,
                                isLoading: false,
                                
                                async fetchSuggestions() {
                                    if (this.query.trim() === '') {
                                        this.results = [];
                                        this.showDropdown = false;
                                        return;
                                    }
                                    
                                    this.showDropdown = true;
                                    this.isLoading = true;
                                    
                                    try {
                                        const response = await fetch(`/api/search-events?q=${encodeURIComponent(this.query)}`);
                                        if (response.ok) {
                                            this.results = await response.json();
                                        }
                                    } catch (error) {
                                        console.error("Failed to fetch suggestions", error);
                                    } finally {
                                        this.isLoading = false;
                                    }
                                }
                            }));
                        });
                    </script>

                    {{-- Category quick-filters --}}
                    <div class="hero-fade mt-5 flex flex-wrap justify-center gap-2" style="--hero-delay:480ms">
                        <a href="{{ route('home') }}" class="filter-chip {{ !request('search') ? 'filter-chip--active' : 'filter-chip--default' }}">
                            Semua
                        </a>
                        @foreach ([
                            ['label' => '🎵 Music',     'q' => 'Music'],
                            ['label' => '💻 Tech',      'q' => 'Tech'],
                            ['label' => '🎭 Workshop',  'q' => 'Workshop'],
                            ['label' => '🍽️ Kuliner',   'q' => 'Culinary'],
                            ['label' => '🎮 Gaming',    'q' => 'E-Sports'],
                            ['label' => '🎨 Seni',      'q' => 'Seni'],
                            ['label' => '🏃 Olahraga',  'q' => 'Lari'],
                        ] as $chip)
                            <a href="?search={{ urlencode($chip['q']) }}"
                               class="filter-chip {{ request('search') === $chip['q'] ? 'filter-chip--active' : 'filter-chip--default' }}">
                                {{ $chip['label'] }}
                            </a>
                        @endforeach
                    </div>
                </div>

                {{-- Featured event spotlight (hero card) --}}
                @if($events->count() > 0)
                    @php $featured = $events->first(); @endphp
                    <div class="hero-fade mx-auto mt-14 max-w-2xl" style="--hero-delay:560ms">
                        <a href="{{ route('events.show', $featured) }}" class="tilt-card group relative block overflow-hidden rounded-3xl" style="box-shadow: 0 32px 64px -12px rgba(79,70,229,0.25), 0 12px 24px -8px rgba(0,0,0,0.12); transform-style: preserve-3d;">
                            <div class="aspect-[16/7] w-full overflow-hidden bg-slate-200">
                                @if($featured->banner_image)
                                    <img src="{{ asset('storage/' . $featured->banner_image) }}"
                                         alt="{{ $featured->title }}"
                                         class="event-card__image"
                                         loading="eager">
                                @else
                                    <img src="https://picsum.photos/seed/{{ $featured->id }}/1200/525"
                                         alt="{{ $featured->title }}"
                                         class="event-card__image"
                                         loading="eager">
                                @endif
                                {{-- Gradient overlay --}}
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-slate-900/30 to-transparent"></div>
                            </div>

                            {{-- Featured badge --}}
                            <div class="absolute left-5 top-5 flex items-center gap-2">
                                <span class="badge badge--live text-[10px] font-black uppercase tracking-widest px-2.5 py-1">Unggulan</span>
                            </div>

                            {{-- Info overlay --}}
                            <div class="absolute inset-x-0 bottom-0 p-6">
                                <div class="flex items-end justify-between gap-4">
                                    <div class="min-w-0">
                                        <p class="text-xs font-semibold uppercase tracking-widest text-indigo-300 mb-1">{{ $featured->start_time->format('d M Y') }}</p>
                                        <h2 class="font-helvetica text-xl font-black text-white truncate sm:text-2xl" style="transition: color 200ms ease;">{{ $featured->title }}</h2>
                                        <p class="mt-1 flex items-center gap-1.5 text-sm text-slate-300 truncate">
                                            <svg class="h-3.5 w-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                                            {{ $featured->location ?? 'Online' }}
                                        </p>
                                    </div>
                                    <div class="shrink-0">
                                        <div class="flex h-11 w-11 items-center justify-center rounded-full bg-white text-indigo-600" style="transition: transform 200ms var(--ease-out-ui), box-shadow 200ms ease; box-shadow: 0 4px 12px rgba(0,0,0,0.2);">
                                            <svg class="h-5 w-5 translate-x-0 group-hover:translate-x-0.5" style="transition: transform 200ms var(--ease-out-ui);" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endif

            </div>
        </section>

        {{-- ═══════════════════════════════════════════
             CATALOG SECTION
        ════════════════════════════════════════════ --}}
        <main class="relative mx-auto w-full max-w-7xl px-4 py-16 sm:px-6 lg:px-8" id="catalog">

            @if($events->count() > 0)

                {{-- Section header --}}
                <div class="mb-10 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        @if(request('search'))
                            <p class="text-sm font-medium text-slate-500">Hasil pencarian untuk</p>
                            <h2 class="font-helvetica mt-0.5 text-2xl font-bold text-slate-900">
                                "{{ request('search') }}"
                                <span class="text-base font-normal text-slate-400 ml-2">{{ $events->total() ?? $events->count() }} event</span>
                            </h2>
                        @else
                            <p class="text-xs font-bold uppercase tracking-widest text-indigo-500 mb-1">Semua Event</p>
                            <h2 class="font-helvetica text-2xl font-bold text-slate-900 sm:text-3xl">
                                Event Pilihan Untuk Anda
                            </h2>
                        @endif
                    </div>

                    @if(request('search'))
                        <a href="{{ route('home') }}" class="secondary-button self-start sm:self-auto">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            Hapus Filter
                        </a>
                    @endif
                </div>

                {{-- Event Grid --}}
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4" id="events-grid">
                    @foreach($events as $index => $event)
                        @php
                            $startingPrice = $event->ticketCategories->count() > 0
                                ? $event->ticketCategories->min('price')
                                : null;
                            $delayMs = min($index % 8, 7) * 60;
                        @endphp

                        <article
                            class="event-card card-reveal tilt-card"
                            style="--reveal-delay: {{ $delayMs }}ms; transform-style: preserve-3d;"
                        >
                            {{-- Image --}}
                            <a href="{{ route('events.show', $event) }}" class="relative block aspect-[4/3] overflow-hidden bg-slate-100" tabindex="-1" aria-hidden="true">
                                @if($event->banner_image)
                                    <img
                                        src="{{ asset('storage/' . $event->banner_image) }}"
                                        alt="{{ $event->title }}"
                                        class="event-card__image"
                                        loading="{{ $index < 4 ? 'eager' : 'lazy' }}"
                                    >
                                @else
                                    <img
                                        src="https://picsum.photos/seed/{{ $event->id }}/800/600"
                                        alt="{{ $event->title }}"
                                        class="event-card__image"
                                        loading="{{ $index < 4 ? 'eager' : 'lazy' }}"
                                    >
                                @endif
                                {{-- Subtle gradient on image --}}
                                <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100" style="transition: opacity 300ms ease;"></div>

                                {{-- Price badge on image --}}
                                <div class="absolute right-3 top-3">
                                    @if($startingPrice !== null && $startingPrice > 0)
                                        <span class="inline-flex items-center rounded-lg bg-white/90 px-2.5 py-1 text-xs font-bold text-slate-900 backdrop-blur-sm" style="box-shadow: 0 2px 8px rgba(0,0,0,0.12);">
                                            Rp {{ number_format($startingPrice, 0, ',', '.') }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-lg bg-emerald-500 px-2.5 py-1 text-xs font-bold text-white" style="box-shadow: 0 2px 8px rgba(16,185,129,0.3);">
                                            Gratis
                                        </span>
                                    @endif
                                </div>
                            </a>

                            {{-- Content --}}
                            <div class="flex flex-1 flex-col p-4">
                                {{-- Title --}}
                                <a href="{{ route('events.show', $event) }}" class="group/title">
                                    <h3 class="line-clamp-2 text-sm font-semibold leading-snug text-slate-900" style="transition: color 150ms ease; min-height: 2.4em;">
                                        {{ $event->title }}
                                    </h3>
                                </a>

                                {{-- Meta --}}
                                <div class="mt-3 flex flex-col gap-1.5">
                                    <div class="flex items-center gap-2 text-xs text-slate-500">
                                        <svg class="h-3.5 w-3.5 shrink-0 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 9v7.5"/>
                                        </svg>
                                        <span class="truncate">{{ $event->start_time->format('d M Y') }}</span>
                                    </div>
                                    <div class="flex items-center gap-2 text-xs text-slate-500">
                                        <svg class="h-3.5 w-3.5 shrink-0 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/>
                                        </svg>
                                        <span class="truncate">{{ $event->location ?? 'Online' }}</span>
                                    </div>
                                </div>

                                {{-- Footer --}}
                                <div class="mt-auto pt-4">
                                    <div class="h-px bg-slate-100 mb-3"></div>
                                    <div class="flex items-center justify-between gap-2">
                                        <div class="min-w-0">
                                            <p class="text-[10px] uppercase tracking-wider text-slate-400 font-medium">Mulai dari</p>
                                            <p class="text-sm font-bold text-slate-900">
                                                @if($startingPrice !== null && $startingPrice > 0)
                                                    Rp {{ number_format($startingPrice, 0, ',', '.') }}
                                                @else
                                                    <span class="text-emerald-600">Gratis</span>
                                                @endif
                                            </p>
                                        </div>
                                        <a href="{{ route('events.show', $event) }}"
                                           id="btn-ticket-{{ $event->id }}"
                                           class="shrink-0 rounded-xl bg-indigo-600 px-4 py-2 text-xs font-semibold text-white"
                                           style="transition: background-color 150ms ease, transform 100ms ease, box-shadow 150ms ease; box-shadow: 0 2px 8px rgba(79,70,229,0.2);"
                                           onmouseenter="this.style.backgroundColor='#4338ca'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px rgba(79,70,229,0.35)';"
                                           onmouseleave="this.style.backgroundColor=''; this.style.transform=''; this.style.boxShadow='0 2px 8px rgba(79,70,229,0.2)';"
                                           onmousedown="this.style.transform='scale(0.96)'"
                                           onmouseup="this.style.transform=''">
                                            Beli Tiket
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if($events->hasPages())
                    <div class="mt-14 flex justify-center pagination-light">
                        {{ $events->links() }}
                    </div>
                @endif

            @else
                {{-- Empty state --}}
                <div class="mx-auto max-w-md py-24 text-center">
                    <div class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-full bg-slate-100">
                        <svg class="h-10 w-10 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900">
                        @if(request('search'))
                            Tidak ada hasil untuk "{{ request('search') }}"
                        @else
                            Belum ada event tersedia
                        @endif
                    </h3>
                    <p class="mt-3 text-sm text-slate-500 leading-relaxed">
                        @if(request('search'))
                            Coba kata kunci lain atau jelajahi semua event yang tersedia.
                        @else
                            Event baru akan segera hadir. Pantau terus halaman ini!
                        @endif
                    </p>
                    @if(request('search'))
                        <a href="{{ route('home') }}" class="primary-button mt-8 inline-flex">
                            Lihat Semua Event
                        </a>
                    @endif
                </div>
            @endif
        </main>

    </div>{{-- /.page-content --}}

    {{-- Footer --}}
    <footer class="mt-auto border-t border-slate-200/80 bg-white">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <div class="flex flex-col items-center gap-2 sm:flex-row sm:justify-between">
                <p class="text-sm text-slate-500">&copy; {{ date('Y') }} Eventmu. Dibuat dengan sepenuh hati.</p>
                <div class="flex items-center gap-1 text-xs text-slate-400">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-400 inline-block"></span>
                    <span>Semua sistem berjalan normal</span>
                </div>
            </div>
        </div>
    </footer>

</div>{{-- /.page-shell --}}

<script>
(function () {
    'use strict';

    var prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    
    var observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.08,
        rootMargin: '0px 0px -40px 0px'
    });

    function initReveal() {
        if (prefersReduced) {
            document.querySelectorAll('.card-reveal').forEach(function (el) {
                el.classList.add('is-visible');
            });
            return;
        }
        document.querySelectorAll('.card-reveal:not(.is-visible)').forEach(function (el) {
            observer.observe(el);
        });
    }

    // Initialize on load
    initReveal();
    initTiltCards();
    initHoverTextSplit();

    // AJAX Pagination
    document.addEventListener('click', function(e) {
        var link = e.target.closest('.pagination-light a');
        if (!link) return;

        e.preventDefault();
        var url = link.href;

        var catalog = document.getElementById('catalog');
        if (!catalog) return;

        // Visual feedback during loading
        catalog.style.opacity = '0.5';
        catalog.style.pointerEvents = 'none';
        catalog.style.transition = 'opacity 200ms ease';

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            var parser = new DOMParser();
            var doc = parser.parseFromString(html, 'text/html');
            var newCatalog = doc.getElementById('catalog');

            if (newCatalog) {
                // Replace content
                catalog.innerHTML = newCatalog.innerHTML;
                
                // Reset visual state
                catalog.style.opacity = '1';
                catalog.style.pointerEvents = 'auto';

                // Update URL without page reload
                window.history.pushState({path: url}, '', url);

                // Scroll smoothly to the top of the catalog
                const yOffset = -80; // offset for sticky navbar
                const y = catalog.getBoundingClientRect().top + window.pageYOffset + yOffset;
                window.scrollTo({top: y, behavior: 'smooth'});

                // Re-initialize animations for newly added cards
                initReveal();
                initTiltCards();
            }
        })
        .catch(err => {
            console.error('AJAX Pagination error:', err);
            window.location.href = url; // Fallback to normal navigation
        });
    });

    // Handle browser back/forward buttons for AJAX state
    window.addEventListener('popstate', function() {
        window.location.reload();
    });

    // Featured hero card arrow animation
    function initFeaturedCard() {
        var featuredCard = document.querySelector('[data-featured-arrow]');
        if (featuredCard) {
            featuredCard.addEventListener('mouseenter', function () {
                var arrow = this.querySelector('[data-featured-arrow]');
                if (arrow) arrow.style.transform = 'translateX(3px)';
            });
            featuredCard.addEventListener('mouseleave', function () {
                var arrow = this.querySelector('[data-featured-arrow]');
                if (arrow) arrow.style.transform = '';
            });
        }
    }
    initFeaturedCard();

    // 3D Tilt Hover Effect
    function initTiltCards() {
        if (prefersReduced) return;
        
        // Only run on fine pointers (desktops/mice)
        if (!window.matchMedia("(pointer: fine)").matches) return;

        document.querySelectorAll('.tilt-card:not(.tilt-initialized)').forEach(function (card) {
            card.classList.add('tilt-initialized');
            
            // Create glare
            var glare = document.createElement('div');
            glare.className = 'absolute inset-0 pointer-events-none z-20 mix-blend-overlay rounded-[inherit]';
            glare.style.opacity = '0';
            glare.style.background = 'radial-gradient(circle at 50% 50%, rgba(255,255,255,0.4) 0%, transparent 60%)';
            card.appendChild(glare);

            var bounds;
            
            function onMouseEnter() {
                bounds = card.getBoundingClientRect();
                card.style.transition = 'transform 150ms cubic-bezier(0.23, 1, 0.32, 1), box-shadow 150ms ease';
                glare.style.transition = 'opacity 300ms ease';
                glare.style.opacity = '1';
            }
            
            function onMouseMove(e) {
                if (!bounds) bounds = card.getBoundingClientRect();
                var mouseX = e.clientX - bounds.left;
                var mouseY = e.clientY - bounds.top;
                
                var xPct = (mouseX / bounds.width) - 0.5; // -0.5 to 0.5
                var yPct = (mouseY / bounds.height) - 0.5;
                
                var rotateX = yPct * -10; // Max 10 deg
                var rotateY = xPct * 10;
                
                card.style.transform = 'perspective(1000px) rotateX(' + rotateX + 'deg) rotateY(' + rotateY + 'deg) scale3d(1.02, 1.02, 1.02)';
                
                glare.style.background = 'radial-gradient(circle at ' + mouseX + 'px ' + mouseY + 'px, rgba(255,255,255,0.6) 0%, transparent 60%)';
            }
            
            function onMouseLeave() {
                card.style.transition = 'transform 500ms cubic-bezier(0.23, 1, 0.32, 1), box-shadow 500ms ease';
                card.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg) scale3d(1, 1, 1)';
                glare.style.transition = 'opacity 500ms ease';
                glare.style.opacity = '0';
                bounds = null;
            }

            card.addEventListener('mouseenter', onMouseEnter);
            card.addEventListener('mousemove', onMouseMove);
            card.addEventListener('mouseleave', onMouseLeave);
        });
    }

    // Per-character hover effect — uses CSS .char-split:hover (works on gradient text too)
    function initHoverTextSplit() {
        if (prefersReduced) return;
        if (!window.matchMedia('(pointer: fine)').matches) return;

        var rotations = [-6, -4, -2, 0, 2, 4, 6, 3, -3, 5, -5];

        document.querySelectorAll('.hover-text-split:not(.split-done)').forEach(function(el) {
            el.classList.add('split-done');

            var text = el.textContent.trim();
            if (!text) return;

            el.innerHTML = '';
            var words = text.split(' ');

            words.forEach(function(word, wordIndex) {
                var wordSpan = document.createElement('span');
                wordSpan.style.display = 'inline-block';
                wordSpan.style.whiteSpace = 'nowrap';

                for (var i = 0; i < word.length; i++) {
                    var charSpan = document.createElement('span');
                    charSpan.textContent = word[i];
                    charSpan.className = 'char-split';
                    // Random rotation per character so hover tilt feels organic
                    var rot = rotations[(wordIndex * word.length + i) % rotations.length] + 'deg';
                    charSpan.style.setProperty('--char-rot', rot);
                    wordSpan.appendChild(charSpan);
                }

                el.appendChild(wordSpan);
                if (wordIndex < words.length - 1) {
                    el.appendChild(document.createTextNode('\u00A0')); // non-breaking space
                }
            });
        });
    }

}());
</script>

</body>
</html>