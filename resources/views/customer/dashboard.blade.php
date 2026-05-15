<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-3">
                <div class="relative flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl border border-cyan-300/30 bg-cyan-300/10 shadow-lg shadow-cyan-500/20">
                    <span class="absolute -inset-1 rounded-2xl bg-gradient-to-br from-cyan-300/30 via-fuchsia-400/20 to-lime-300/20 blur-md"></span>
                    <svg class="relative h-5 w-5 text-cyan-100" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-black uppercase tracking-[0.28em] text-cyan-200">Customer Hub</p>
                    <h2 class="text-2xl font-black leading-tight text-white">My Tickets</h2>
                </div>
            </div>

            <a href="{{ route('home') }}" class="neon-button-outline w-fit">Explore Events</a>
        </div>
    </x-slot>

    @php
        $allTickets = [];
        foreach($recentOrders as $order) {
            foreach($order->ticketDetails as $ticket) {
                $allTickets[] = [
                    'ticket' => $ticket,
                    'order' => $order,
                    'event' => $ticket->ticketCategory->event,
                    'category' => $ticket->ticketCategory,
                ];
            }
        }

        $cards = [
            ['label' => 'Total Orders', 'value' => $stats['total_orders'], 'tone' => 'cyan', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
            ['label' => 'Paid Orders', 'value' => $stats['paid_orders'], 'tone' => 'lime', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
            ['label' => 'Pending Orders', 'value' => $stats['pending_orders'], 'tone' => 'fuchsia', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
        ];
    @endphp

    <div class="py-10">
        <div class="mx-auto max-w-7xl space-y-8 px-4 sm:px-6 lg:px-8">
            <section class="neon-card p-8 md:p-10">
                <div class="grid gap-8 lg:grid-cols-[1fr_auto] lg:items-end">
                    <div>
                        <div class="neon-chip mb-5 w-fit">
                            <span class="h-2 w-2 rounded-full bg-lime-300 shadow-lg shadow-lime-300/70"></span>
                            Your digital access vault
                        </div>
                        <p class="text-sm font-bold text-slate-400">Hello, Customer</p>
                        <h1 class="mt-2 text-4xl font-black tracking-tight text-white sm:text-5xl">{{ Auth::user()->name }}</h1>
                        <p class="mt-4 max-w-2xl text-slate-300">Kelola tiket digital, cek status order, dan buka QR pass dari satu dashboard yang responsif dan mudah dipindai.</p>
                    </div>
                    <div class="rounded-3xl border border-cyan-300/20 bg-cyan-300/10 p-5 text-right">
                        <p class="text-xs font-black uppercase tracking-widest text-cyan-200">Active Passes</p>
                        <p class="mt-2 text-5xl font-black text-white">{{ count($allTickets) }}</p>
                    </div>
                </div>
            </section>

            <section class="grid grid-cols-1 gap-5 sm:grid-cols-3">
                @foreach($cards as $card)
                    @php
                        $toneClasses = match($card['tone']) {
                            'lime' => 'border-lime-300/20 bg-lime-300/10 text-lime-100 shadow-lime-500/10',
                            'fuchsia' => 'border-fuchsia-300/20 bg-fuchsia-300/10 text-fuchsia-100 shadow-fuchsia-500/10',
                            default => 'border-cyan-300/20 bg-cyan-300/10 text-cyan-100 shadow-cyan-500/10',
                        };
                    @endphp
                    <div class="glass-panel rounded-3xl p-6 transition hover:-translate-y-1">
                        <div class="flex items-center gap-4">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border shadow-lg {{ $toneClasses }}">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $card['icon'] }}"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-black uppercase tracking-widest text-slate-500">{{ $card['label'] }}</p>
                                <p class="mt-1 text-3xl font-black text-white">{{ $card['value'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </section>

            <section class="grid grid-cols-1 gap-8 lg:grid-cols-[0.85fr_1.15fr]">
                <div class="neon-card self-start">
                    <div class="border-b border-white/10 px-6 py-5">
                        <p class="text-xs font-black uppercase tracking-[0.28em] text-fuchsia-200">Recent Orders</p>
                        <h3 class="mt-2 text-2xl font-black text-white">Transaction trail</h3>
                    </div>
                    @forelse($recentOrders->take(6) as $order)
                        @php
                            $statusClass = match($order->payment_status) {
                                'paid' => 'border-lime-300/30 bg-lime-300/10 text-lime-100',
                                'pending' => 'border-amber-300/30 bg-amber-300/10 text-amber-100',
                                'failed' => 'border-rose-300/30 bg-rose-300/10 text-rose-100',
                                default => 'border-white/10 bg-white/10 text-slate-200',
                            };
                        @endphp
                        <div class="flex items-center justify-between gap-4 border-b border-white/5 px-6 py-4 last:border-0 transition hover:bg-white/5">
                            <div class="min-w-0">
                                <p class="truncate font-mono text-sm font-black text-white">{{ $order->invoice_number }}</p>
                                <p class="mt-1 text-xs text-slate-400">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                                @if($order->payment_method)
                                    <p class="mt-1 text-xs font-bold text-cyan-200">Paid via {{ $order->payment_display_label }}</p>
                                @endif
                            </div>
                            <span class="shrink-0 rounded-full border px-3 py-1 text-xs font-black capitalize {{ $statusClass }}">{{ $order->payment_status }}</span>
                        </div>
                    @empty
                        <div class="px-6 py-12 text-center">
                            <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-3xl border border-white/10 bg-white/5 text-slate-400">
                                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                            <p class="text-sm text-slate-400">No orders found.</p>
                        </div>
                    @endforelse
                </div>

                <section id="my-tickets">
                    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                        <div>
                            <p class="text-sm font-black uppercase tracking-[0.28em] text-cyan-200">My E-Tickets</p>
                            <h2 class="mt-2 text-3xl font-black text-white">Your upcoming access</h2>
                        </div>
                        <span class="neon-chip w-fit">{{ count($allTickets) }} passes</span>
                    </div>

                    @if(count($allTickets) > 0)
                        <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
                            @foreach($allTickets as $item)
                                @php
                                    $event = $item['event'];
                                    $ticket = $item['ticket'];
                                    $category = $item['category'];
                                @endphp
                                <article x-data="{ showQR: false }" class="neon-card transition duration-300 hover:-translate-y-1 hover:border-cyan-300/40">
                                    <div class="p-6">
                                        <div class="mb-4 flex items-start justify-between gap-3">
                                            <div>
                                                <span class="text-xs font-black uppercase tracking-[0.28em] text-cyan-200">E-Ticket</span>
                                                <h3 class="mt-3 text-2xl font-black text-white">{{ $event->title }}</h3>
                                            </div>
                                            @if($ticket->is_scanned)
                                                <span class="rounded-full border border-emerald-300/30 bg-emerald-300/10 px-3 py-1 text-xs font-black text-emerald-100">Scanned</span>
                                            @else
                                                <span class="rounded-full border border-cyan-300/30 bg-cyan-300/10 px-3 py-1 text-xs font-black text-cyan-100">Not Used</span>
                                            @endif
                                        </div>

                                        <div class="space-y-3 text-sm text-slate-300">
                                            <div class="flex items-center gap-3 rounded-2xl border border-white/10 bg-white/5 px-4 py-3">
                                                <svg class="h-4 w-4 shrink-0 text-fuchsia-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                <span class="font-bold text-white">{{ $event->start_time->format('F d, Y • h:i A') }}</span>
                                            </div>
                                            <div class="flex items-center gap-3 rounded-2xl border border-white/10 bg-white/5 px-4 py-3">
                                                <svg class="h-4 w-4 shrink-0 text-cyan-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                                                </svg>
                                                <span class="font-bold text-white">{{ $category->name }}</span>
                                            </div>
                                        </div>

                                        <button
                                            @click="showQR = !showQR"
                                            class="mt-5 flex w-full items-center justify-center gap-2 rounded-2xl px-4 py-3 font-black transition-all duration-200"
                                            :class="showQR ? 'bg-cyan-300 text-slate-950 shadow-lg shadow-cyan-500/20' : 'border border-white/10 bg-white/5 text-white hover:bg-white/10'"
                                        >
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path x-show="!showQR" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m6-6H6"></path>
                                                <path x-show="showQR" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12a8 8 0 11-16 0 8 8 0 0116 0zM9 9l3 3m0 0l3-3m0 0l-3-3m0 0l-3 3"></path>
                                            </svg>
                                            <span x-text="showQR ? 'Hide QR Code' : 'View QR Code'"></span>
                                        </button>

                                        <div class="mt-5 border-t border-white/10 pt-4 text-xs text-slate-400">
                                            <p class="font-mono font-black text-white">{{ $item['order']->invoice_number }}</p>
                                            <p class="mt-1">Purchased: {{ $item['order']->created_at->format('M d, Y') }}</p>
                                            @if($item['order']->payment_method)
                                                <p class="mt-1">Paid via {{ $item['order']->payment_display_label }}</p>
                                            @endif
                                        </div>
                                    </div>

                                    <div x-show="showQR" x-transition.opacity class="border-t border-white/10 bg-slate-950/50 p-6">
                                        <p class="mb-4 text-xs font-black uppercase tracking-[0.28em] text-lime-200">QR Code</p>
                                        <div class="flex justify-center">
                                            <div class="rounded-3xl border border-cyan-300/30 bg-white p-4 shadow-lg shadow-cyan-500/20">
                                                <img src="{{ \App\Support\QrCode::svgDataUri($ticket->barcode_string, 160) }}" alt="QR code for ticket {{ $ticket->id }}" class="h-40 w-40">
                                            </div>
                                        </div>
                                        <p class="mt-4 break-all rounded-2xl border border-white/10 bg-white/5 p-3 text-center font-mono text-sm font-bold text-slate-200">{{ $ticket->barcode_string }}</p>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @else
                        <div class="neon-card p-12 text-center">
                            <div class="mx-auto mb-5 flex h-20 w-20 items-center justify-center rounded-3xl border border-cyan-300/30 bg-cyan-300/10 text-cyan-100">
                                <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-black text-white">No E-Tickets Yet</h3>
                            <p class="mx-auto mt-3 max-w-md text-slate-400">You haven't purchased any tickets yet. Explore upcoming events and get your passes today!</p>
                            <a href="{{ route('home') }}" class="neon-button mt-7">Browse Events</a>
                        </div>
                    @endif
                </section>
            </section>

            <section class="glass-panel flex flex-col gap-4 rounded-3xl p-6 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-slate-400">Logged in as</p>
                    <p class="mt-1 font-bold text-white">{{ Auth::user()->email }}</p>
                </div>
                <span class="neon-chip w-fit">
                    <span class="inline-block h-1.5 w-1.5 rounded-full bg-cyan-300"></span>
                    Customer
                </span>
            </section>
        </div>
    </div>
</x-app-layout>