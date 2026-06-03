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
    <body class="font-sans antialiased bg-slate-50 text-slate-900">
        @php
            $minPrice = $event->ticketCategories->count() > 0 ? $event->ticketCategories->min('price') : null;
            $totalAvailable = $event->ticketCategories->sum(fn ($category) => max($category->quota - $category->ticketDetails->count(), 0));
        @endphp

        <div class="page-shell flex min-h-screen flex-col">
            <div class="page-content">
                <x-public-navigation />

                <main class="mx-auto w-full max-w-7xl px-4 py-8 sm:px-6 lg:px-8 lg:py-12">
                    @if(session('payment_success'))
                        @php
                            $paymentSuccess = session('payment_success');
                        @endphp
                        <div class="mb-8 rounded-3xl border border-emerald-200 bg-emerald-50 p-5 shadow-sm">
                            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                <div class="flex items-start gap-4">
                                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-700">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h2 class="text-lg font-black text-slate-900">{{ $paymentSuccess['title'] ?? 'Pembayaran Berhasil' }}</h2>
                                        <p class="mt-1 text-sm text-slate-600">{{ $paymentSuccess['message'] ?? 'Tiket Anda sudah aktif dan siap digunakan.' }}</p>
                                        @if(! empty($paymentSuccess['invoice_number']))
                                            <p class="mt-2 text-xs font-bold text-emerald-600">Invoice: {{ $paymentSuccess['invoice_number'] }}</p>
                                        @endif
                                    </div>
                                </div>

                                <a href="{{ $paymentSuccess['ticket_url'] ?? route('customer.dashboard') }}" class="primary-button">
                                    Lihat Tiket
                                </a>
                            </div>
                        </div>
                    @endif

                    <section class="grid gap-8 lg:grid-cols-[1.25fr_0.75fr]">
                        <div class="space-y-6">
                            <a href="{{ route('home') }}" class="inline-flex items-center gap-1 text-sm font-bold text-slate-500 hover:text-slate-900 transition-colors">
                                <span>&larr;</span> Back to Events
                            </a>

                            <div class="clean-card overflow-hidden">
                                <div class="relative aspect-video w-full overflow-hidden">
                                    @if($event->banner_image)
                                        <img src="{{ asset('storage/' . $event->banner_image) }}" alt="{{ $event->title }}" class="h-full w-full object-cover">
                                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-slate-900/20 to-transparent"></div>
                                    @else
                                        <div class="flex h-full w-full items-center justify-center bg-slate-200">
                                            <svg class="h-32 w-32 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16m10-16v16M9 4h6m-6 8h6"></path>
                                            </svg>
                                        </div>
                                    @endif

                                    <div class="absolute inset-x-0 bottom-0 p-6 sm:p-8">
                                        <div class="mb-4 flex flex-wrap gap-2">
                                            <span class="info-badge bg-white/90 text-slate-900 border-transparent shadow-sm">{{ $event->start_time->format('d M Y') }}</span>
                                            <span class="info-badge bg-white/90 text-slate-900 border-transparent shadow-sm">{{ $event->ticketCategories->count() }} ticket types</span>
                                            <span class="info-badge bg-white/90 text-slate-900 border-transparent shadow-sm">{{ $totalAvailable }} available</span>
                                        </div>
                                        <h1 class="max-w-4xl text-4xl font-black tracking-tight text-white sm:text-5xl lg:text-6xl drop-shadow-md">{{ $event->title }}</h1>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                                <div class="glass-panel p-5">
                                    <p class="text-xs font-bold uppercase tracking-widest text-indigo-600">Date & Time</p>
                                    <p class="mt-3 text-lg font-black text-slate-900">{{ $event->start_time->format('M d, Y') }}</p>
                                    <p class="mt-1 text-sm text-slate-500">{{ $event->start_time->format('h:i A') }} - {{ $event->end_time->format('h:i A') }}</p>
                                </div>
                                <div class="glass-panel p-5">
                                    <p class="text-xs font-bold uppercase tracking-widest text-indigo-600">Location</p>
                                    <p class="mt-3 text-lg font-black text-slate-900">{{ $event->location }}</p>
                                    <p class="mt-1 text-sm text-slate-500">Venue access details after purchase</p>
                                </div>
                                <div class="glass-panel p-5">
                                    <p class="text-xs font-bold uppercase tracking-widest text-indigo-600">Organizer</p>
                                    <p class="mt-3 text-lg font-black text-slate-900">{{ $event->organizer->name }}</p>
                                    <p class="mt-1 text-sm text-slate-500">Verified event partner</p>
                                </div>
                            </div>

                            <section class="clean-card p-6 sm:p-8">
                                <p class="text-sm font-bold uppercase tracking-[0.2em] text-slate-400">About This Event</p>
                                <div class="mt-5 whitespace-pre-wrap text-base leading-8 text-slate-600">{{ $event->description }}</div>
                            </section>
                        </div>

                        <aside class="space-y-6 lg:sticky lg:top-28 lg:self-start">
                            <section class="clean-card p-6">
                                <div class="mb-6 flex items-start justify-between gap-4">
                                    <div>
                                        <p class="text-xs font-bold uppercase tracking-[0.2em] text-indigo-600">Tickets</p>
                                        <h2 class="mt-2 text-3xl font-black text-slate-900">Choose Pass</h2>
                                    </div>
                                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-3 py-2 text-right">
                                        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-500">From</p>
                                        <p class="text-sm font-black text-slate-900">{{ $minPrice !== null ? 'Rp ' . number_format($minPrice, 0, ',', '.') : 'TBA' }}</p>
                                    </div>
                                </div>

                                @if($event->ticketCategories->count() > 0)
                                    <div class="space-y-4">
                                        @foreach($event->ticketCategories as $category)
                                            @php
                                                $available = $category->quota - $category->ticketDetails->count();
                                            @endphp
                                            <div class="rounded-3xl border border-slate-200 bg-white p-4 transition hover:border-indigo-200 hover:shadow-sm">
                                                <div class="flex items-start justify-between gap-3">
                                                    <div>
                                                        <h3 class="font-black text-slate-900">{{ $category->name }}</h3>
                                                        <p class="mt-1 text-sm text-slate-500">Digital QR pass</p>
                                                    </div>
                                                    <p class="text-right text-xl font-black text-indigo-600">Rp {{ number_format($category->price, 0, ',', '.') }}</p>
                                                </div>

                                                <div class="mt-4 flex items-center justify-between gap-3">
                                                    @if($available > 0)
                                                        <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-700">{{ $available }} available</span>
                                                    @else
                                                        <span class="rounded-full bg-rose-100 px-3 py-1 text-xs font-bold text-rose-700">Sold Out</span>
                                                    @endif
                                                </div>

                                                @if($available > 0)
                                                    @auth
                                                        <a href="{{ route('checkout.create', $category) }}" class="primary-button mt-4 w-full">
                                                            Buy Ticket
                                                        </a>
                                                    @else
                                                        <a href="{{ route('login') }}" class="primary-button mt-4 w-full">
                                                            Login to Buy
                                                        </a>
                                                    @endauth
                                                @else
                                                    <button disabled class="mt-4 w-full cursor-not-allowed rounded-2xl border border-slate-200 bg-slate-100 px-5 py-3 text-sm font-bold uppercase tracking-wide text-slate-400">
                                                        Sold Out
                                                    </button>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6 text-center text-slate-500">
                                        No tickets are currently available for this event.
                                    </div>
                                @endif
                            </section>

                            @if($userEventTickets->isNotEmpty())
                                @php
                                    $unusedTicketCount = $userEventTickets->where('is_scanned', false)->count();
                                    $scannedTicketCount = $userEventTickets->where('is_scanned', true)->count();
                                @endphp
                                <section class="clean-card p-6" x-data="{ activeTicket: null }">
                                    <div class="mb-4 flex items-start justify-between gap-3">
                                        <div>
                                            <p class="text-xs font-bold uppercase tracking-widest text-indigo-600">Your Tickets</p>
                                            <h3 class="mt-2 text-xl font-black text-slate-900">{{ $unusedTicketCount }} unused / {{ $userEventTickets->count() }} total</h3>
                                        </div>
                                        @if($scannedTicketCount > 0)
                                            <span class="rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-xs font-bold text-slate-500">{{ $scannedTicketCount }} scanned</span>
                                        @endif
                                    </div>

                                    <div class="max-h-80 space-y-3 overflow-y-auto pr-1">
                                        @foreach($userEventTickets as $ticket)
                                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-3">
                                                <div class="flex items-center gap-3">
                                                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-indigo-100 text-indigo-600">
                                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                                                        </svg>
                                                    </div>

                                                    <div class="min-w-0 flex-1">
                                                        <div class="flex items-center gap-2">
                                                            <p class="truncate text-sm font-black text-slate-900">{{ $ticket->ticketCategory->name }}</p>
                                                            @if($ticket->is_scanned)
                                                                <span class="shrink-0 rounded-full bg-emerald-100 px-2 py-0.5 text-[11px] font-bold text-emerald-700">Scanned</span>
                                                            @else
                                                                <span class="shrink-0 rounded-full bg-indigo-100 px-2 py-0.5 text-[11px] font-bold text-indigo-700">Not Used</span>
                                                            @endif
                                                        </div>
                                                        <p class="truncate text-xs text-slate-500">{{ $ticket->order->invoice_number }}</p>
                                                        <p class="mt-0.5 truncate font-mono text-[11px] text-slate-400">{{ $ticket->barcode_string }}</p>
                                                    </div>

                                                    <button
                                                        type="button"
                                                        class="shrink-0 rounded-xl border border-indigo-200 bg-indigo-50 px-3 py-2 text-xs font-bold text-indigo-700 transition hover:bg-indigo-100"
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
                                        class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/80 px-4 py-6 backdrop-blur-sm"
                                        @keydown.escape.window="activeTicket = null"
                                    >
                                        <div class="w-full max-w-sm rounded-3xl bg-white p-5 shadow-2xl" @click.outside="activeTicket = null">
                                            <div class="mb-4 flex items-start justify-between gap-3">
                                                <div class="min-w-0">
                                                    <p class="truncate text-base font-black text-slate-900" x-text="activeTicket?.category"></p>
                                                    <p class="mt-1 truncate text-xs text-slate-500" x-text="activeTicket?.invoice"></p>
                                                </div>
                                                <button type="button" class="rounded-xl p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-600" @click="activeTicket = null" aria-label="Close QR code">
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                                    </svg>
                                                </button>
                                            </div>

                                            <div class="flex justify-center">
                                                <div class="rounded-3xl border border-slate-200 bg-white p-4 shadow-sm">
                                                    <img :src="activeTicket?.qr" alt="QR code for selected ticket" class="h-56 w-56">
                                                </div>
                                            </div>
                                            <p class="mt-4 break-all rounded-2xl border border-slate-200 bg-slate-50 p-3 text-center font-mono text-xs text-slate-600" x-text="activeTicket?.barcode"></p>
                                            <p class="mt-3 text-center text-xs font-bold uppercase tracking-widest text-indigo-600" x-text="activeTicket?.status"></p>
                                        </div>
                                    </div>
                                </section>
                            @endif

                            <section class="glass-panel p-5 text-sm text-slate-600">
                                <p class="mb-3 font-bold text-slate-900">ℹ Important</p>
                                <ul class="space-y-2 text-xs leading-6 text-slate-500">
                                    <li>Tickets are non-refundable</li>
                                    <li>One ticket per purchase</li>
                                    <li>Digital tickets will be sent via email</li>
                                </ul>
                            </section>
                        </aside>
                    </section>
                </main>
            </div>

            <footer class="page-content mt-auto border-t border-slate-200 bg-white text-slate-500">
                <div class="mx-auto max-w-7xl px-4 py-8 text-center sm:px-6 lg:px-8">
                    <p class="text-sm">&copy; 2026 EventTicketing. Secure modern passes for every crowd.</p>
                </div>
            </footer>
        </div>
    </body>
</html>
