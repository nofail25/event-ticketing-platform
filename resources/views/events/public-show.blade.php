<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $event->title }} - Event Ticketing Platform</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-slate-950 text-slate-100">
        @php
            $minPrice = $event->ticketCategories->count() > 0 ? $event->ticketCategories->min('price') : null;
            $totalAvailable = $event->ticketCategories->sum(fn ($category) => max($category->quota - $category->ticketDetails->count(), 0));
        @endphp

        <div class="dark-page-shell flex min-h-screen flex-col">
            <div class="dark-page-content">
                <x-public-navigation :back-href="route('home')" back-label="Back to Events" />

                <main class="mx-auto w-full max-w-7xl px-4 py-8 sm:px-6 lg:px-8 lg:py-12">
                    @if(session('payment_success'))
                        @php($paymentSuccess = session('payment_success'))
                        <div class="mb-8 rounded-3xl border border-emerald-300/30 bg-emerald-400/10 p-5 shadow-2xl shadow-emerald-950/20 backdrop-blur-xl">
                            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                <div class="flex items-start gap-4">
                                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-emerald-300 text-emerald-950 shadow-lg shadow-emerald-400/30">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h2 class="text-lg font-black text-white">{{ $paymentSuccess['title'] ?? 'Pembayaran Berhasil' }}</h2>
                                        <p class="mt-1 text-sm text-emerald-100">{{ $paymentSuccess['message'] ?? 'Tiket Anda sudah aktif dan siap digunakan.' }}</p>
                                        @if(! empty($paymentSuccess['invoice_number']))
                                            <p class="mt-2 text-xs font-bold text-emerald-200">Invoice: {{ $paymentSuccess['invoice_number'] }}</p>
                                        @endif
                                    </div>
                                </div>

                                <a href="{{ $paymentSuccess['ticket_url'] ?? route('customer.dashboard') }}" class="neon-button">
                                    Lihat Tiket
                                </a>
                            </div>
                        </div>
                    @endif

                    <section class="grid gap-8 lg:grid-cols-[1.25fr_0.75fr]">
                        <div class="space-y-8">
                            <div class="neon-card overflow-hidden">
                                <div class="relative h-[24rem] overflow-hidden sm:h-[30rem]">
                                    @if($event->banner_image)
                                        <img src="{{ asset('storage/' . $event->banner_image) }}" alt="{{ $event->title }}" class="h-full w-full object-cover">
                                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/45 to-transparent"></div>
                                    @else
                                        <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-cyan-500 via-fuchsia-600 to-violet-900">
                                            <svg class="h-32 w-32 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16m10-16v16M9 4h6m-6 8h6"></path>
                                            </svg>
                                        </div>
                                    @endif

                                    <div class="absolute inset-x-0 bottom-0 p-6 sm:p-8">
                                        <div class="mb-4 flex flex-wrap gap-2">
                                            <span class="neon-chip">{{ $event->start_time->format('d M Y') }}</span>
                                            <span class="neon-chip">{{ $event->ticketCategories->count() }} ticket types</span>
                                            <span class="neon-chip">{{ $totalAvailable }} available</span>
                                        </div>
                                        <h1 class="max-w-4xl text-4xl font-black tracking-tight text-white sm:text-5xl lg:text-6xl">{{ $event->title }}</h1>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                                <div class="glass-panel rounded-3xl p-5">
                                    <p class="text-xs font-black uppercase tracking-widest text-cyan-200">Date & Time</p>
                                    <p class="mt-3 text-lg font-black text-white">{{ $event->start_time->format('M d, Y') }}</p>
                                    <p class="mt-1 text-sm text-slate-400">{{ $event->start_time->format('h:i A') }} - {{ $event->end_time->format('h:i A') }}</p>
                                </div>
                                <div class="glass-panel rounded-3xl p-5">
                                    <p class="text-xs font-black uppercase tracking-widest text-fuchsia-200">Location</p>
                                    <p class="mt-3 text-lg font-black text-white">{{ $event->location }}</p>
                                    <p class="mt-1 text-sm text-slate-400">Venue access details after purchase</p>
                                </div>
                                <div class="glass-panel rounded-3xl p-5">
                                    <p class="text-xs font-black uppercase tracking-widest text-lime-200">Organizer</p>
                                    <p class="mt-3 text-lg font-black text-white">{{ $event->organizer->name }}</p>
                                    <p class="mt-1 text-sm text-slate-400">Verified event partner</p>
                                </div>
                            </div>

                            <section class="neon-card p-6 sm:p-8">
                                <p class="text-sm font-black uppercase tracking-[0.28em] text-fuchsia-200">About This Event</p>
                                <div class="mt-5 whitespace-pre-wrap text-base leading-8 text-slate-300">{{ $event->description }}</div>
                            </section>
                        </div>

                        <aside class="space-y-6 lg:sticky lg:top-28 lg:self-start">
                            <section class="neon-card p-6">
                                <div class="mb-6 flex items-start justify-between gap-4">
                                    <div>
                                        <p class="text-xs font-black uppercase tracking-[0.28em] text-cyan-200">Tickets</p>
                                        <h2 class="mt-2 text-3xl font-black text-white">Choose Pass</h2>
                                    </div>
                                    <div class="rounded-2xl border border-cyan-300/20 bg-cyan-300/10 px-3 py-2 text-right">
                                        <p class="text-[10px] font-black uppercase tracking-widest text-cyan-200">From</p>
                                        <p class="text-sm font-black text-white">{{ $minPrice !== null ? 'Rp ' . number_format($minPrice, 0, ',', '.') : 'TBA' }}</p>
                                    </div>
                                </div>

                                @if($event->ticketCategories->count() > 0)
                                    <div class="space-y-4">
                                        @foreach($event->ticketCategories as $category)
                                            @php($available = $category->quota - $category->ticketDetails->count())
                                            <div class="rounded-3xl border border-white/10 bg-slate-950/60 p-4 transition hover:border-cyan-300/30 hover:bg-cyan-300/5">
                                                <div class="flex items-start justify-between gap-3">
                                                    <div>
                                                        <h3 class="font-black text-white">{{ $category->name }}</h3>
                                                        <p class="mt-1 text-sm text-slate-400">Digital QR pass</p>
                                                    </div>
                                                    <p class="text-right text-xl font-black text-cyan-200">Rp {{ number_format($category->price, 0, ',', '.') }}</p>
                                                </div>

                                                <div class="mt-4 flex items-center justify-between gap-3">
                                                    @if($available > 0)
                                                        <span class="rounded-full border border-lime-300/30 bg-lime-300/10 px-3 py-1 text-xs font-black text-lime-100">{{ $available }} available</span>
                                                    @else
                                                        <span class="rounded-full border border-rose-300/30 bg-rose-300/10 px-3 py-1 text-xs font-black text-rose-100">Sold Out</span>
                                                    @endif
                                                </div>

                                                @if($available > 0)
                                                    @auth
                                                        <a href="{{ route('checkout.create', $category) }}" class="neon-button mt-4 w-full">
                                                            Buy Ticket
                                                        </a>
                                                    @else
                                                        <a href="{{ route('login') }}" class="neon-button mt-4 w-full">
                                                            Login to Buy
                                                        </a>
                                                    @endauth
                                                @else
                                                    <button disabled class="mt-4 w-full cursor-not-allowed rounded-2xl border border-white/10 bg-white/5 px-5 py-3 text-sm font-black uppercase tracking-wide text-slate-500">
                                                        Sold Out
                                                    </button>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="rounded-3xl border border-white/10 bg-white/5 p-6 text-center text-slate-400">
                                        No tickets are currently available for this event.
                                    </div>
                                @endif
                            </section>

                            @if($userEventTickets->isNotEmpty())
                                @php
                                    $unusedTicketCount = $userEventTickets->where('is_scanned', false)->count();
                                    $scannedTicketCount = $userEventTickets->where('is_scanned', true)->count();
                                @endphp
                                <section class="neon-card p-6" x-data="{ activeTicket: null }">
                                    <div class="mb-4 flex items-start justify-between gap-3">
                                        <div>
                                            <p class="text-xs font-black uppercase tracking-[0.28em] text-lime-200">Your Tickets</p>
                                            <h3 class="mt-2 text-xl font-black text-white">{{ $unusedTicketCount }} unused / {{ $userEventTickets->count() }} total</h3>
                                        </div>
                                        @if($scannedTicketCount > 0)
                                            <span class="rounded-full border border-white/10 bg-white/10 px-3 py-1 text-xs font-bold text-slate-300">{{ $scannedTicketCount }} scanned</span>
                                        @endif
                                    </div>

                                    <div class="max-h-80 space-y-3 overflow-y-auto pr-1">
                                        @foreach($userEventTickets as $ticket)
                                            <div class="rounded-2xl border border-white/10 bg-slate-950/60 p-3">
                                                <div class="flex items-center gap-3">
                                                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-cyan-300/10 text-cyan-100">
                                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                                                        </svg>
                                                    </div>

                                                    <div class="min-w-0 flex-1">
                                                        <div class="flex items-center gap-2">
                                                            <p class="truncate text-sm font-black text-white">{{ $ticket->ticketCategory->name }}</p>
                                                            @if($ticket->is_scanned)
                                                                <span class="shrink-0 rounded-full bg-emerald-300/15 px-2 py-0.5 text-[11px] font-bold text-emerald-100">Scanned</span>
                                                            @else
                                                                <span class="shrink-0 rounded-full bg-cyan-300/15 px-2 py-0.5 text-[11px] font-bold text-cyan-100">Not Used</span>
                                                            @endif
                                                        </div>
                                                        <p class="truncate text-xs text-slate-500">{{ $ticket->order->invoice_number }}</p>
                                                        <p class="mt-0.5 truncate font-mono text-[11px] text-slate-600">{{ $ticket->barcode_string }}</p>
                                                    </div>

                                                    <button
                                                        type="button"
                                                        class="shrink-0 rounded-xl border border-cyan-300/30 bg-cyan-300/10 px-3 py-2 text-xs font-black text-cyan-100 transition hover:bg-cyan-300/20"
                                                        @click="activeTicket = {
                                                            category: @js($ticket->ticketCategory->name),
                                                            invoice: @js($ticket->order->invoice_number),
                                                            barcode: @js($ticket->barcode_string),
                                                            status: @js($ticket->is_scanned ? 'Scanned' : 'Not Used'),
                                                            qr: @js(\App\Support\QrCode::svgDataUri($ticket->barcode_string, 220))
                                                        }"
                                                    >
                                                        QR
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div
                                        x-cloak
                                        x-show="activeTicket"
                                        x-transition.opacity
                                        class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/80 px-4 py-6 backdrop-blur"
                                        @keydown.escape.window="activeTicket = null"
                                    >
                                        <div class="w-full max-w-sm rounded-3xl border border-white/10 bg-slate-950 p-5 shadow-2xl shadow-cyan-950/40" @click.outside="activeTicket = null">
                                            <div class="mb-4 flex items-start justify-between gap-3">
                                                <div class="min-w-0">
                                                    <p class="truncate text-base font-black text-white" x-text="activeTicket?.category"></p>
                                                    <p class="mt-1 truncate text-xs text-slate-400" x-text="activeTicket?.invoice"></p>
                                                </div>
                                                <button type="button" class="rounded-xl p-2 text-slate-400 transition hover:bg-white/10 hover:text-white" @click="activeTicket = null" aria-label="Close QR code">
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                                    </svg>
                                                </button>
                                            </div>

                                            <div class="flex justify-center">
                                                <div class="rounded-3xl border border-cyan-300/30 bg-white p-4 shadow-lg shadow-cyan-500/20">
                                                    <img :src="activeTicket?.qr" alt="QR code for selected ticket" class="h-56 w-56">
                                                </div>
                                            </div>
                                            <p class="mt-4 break-all rounded-2xl border border-white/10 bg-white/5 p-3 text-center font-mono text-xs text-slate-300" x-text="activeTicket?.barcode"></p>
                                            <p class="mt-3 text-center text-xs font-black uppercase tracking-widest text-cyan-200" x-text="activeTicket?.status"></p>
                                        </div>
                                    </div>
                                </section>
                            @endif

                            <section class="glass-panel rounded-3xl p-5 text-sm text-slate-300">
                                <p class="mb-3 font-black text-white">ℹ Important</p>
                                <ul class="space-y-2 text-xs leading-6 text-slate-400">
                                    <li>Tickets are non-refundable</li>
                                    <li>One ticket per purchase</li>
                                    <li>Digital tickets will be sent via email</li>
                                </ul>
                            </section>
                        </aside>
                    </section>
                </main>
            </div>

            <footer class="dark-page-content mt-auto border-t border-white/10 bg-slate-950/75 text-slate-400 backdrop-blur-xl">
                <div class="mx-auto max-w-7xl px-4 py-8 text-center sm:px-6 lg:px-8">
                    <p class="text-sm">&copy; 2026 EventTicketing. Secure neon passes for every crowd.</p>
                </div>
            </footer>
        </div>
    </body>
</html>