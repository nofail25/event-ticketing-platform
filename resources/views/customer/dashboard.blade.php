<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="flex-shrink-0 w-9 h-9 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                </svg>
            </div>
            <div>
                <h2 class="font-bold text-xl text-gray-800 leading-tight">Customer Dashboard</h2>
                <p class="text-xs text-gray-500 font-medium">Browse events and manage your tickets</p>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Welcome Banner --}}
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-600 p-8 text-white shadow-lg">
                <div class="absolute -right-10 -top-10 w-48 h-48 bg-white/10 rounded-full blur-xl"></div>
                <div class="relative z-10">
                    <p class="text-blue-100 text-sm font-medium mb-1">Hello,</p>
                    <h1 class="text-3xl font-bold mb-2">{{ Auth::user()->name }} 🎟️</h1>
                    <p class="text-indigo-100">Welcome to your ticket hub. Explore events and manage your orders.</p>
                </div>
            </div>

            {{-- Stats Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                @php
                    $cards = [
                        ['label' => 'Total Orders',   'value' => $stats['total_orders'],   'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'from' => 'from-blue-500', 'to' => 'to-blue-600'],
                        ['label' => 'Paid Orders',    'value' => $stats['paid_orders'],    'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'from' => 'from-emerald-500', 'to' => 'to-emerald-600'],
                        ['label' => 'Pending Orders', 'value' => $stats['pending_orders'], 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'from' => 'from-amber-500', 'to' => 'to-amber-600'],
                    ];
                @endphp

                @foreach($cards as $card)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center gap-4 hover:shadow-md transition-shadow duration-200">
                    <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-gradient-to-br {{ $card['from'] }} {{ $card['to'] }} flex items-center justify-center shadow-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $card['icon'] }}"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">{{ $card['label'] }}</p>
                        <p class="text-3xl font-bold text-gray-800 mt-0.5">{{ $card['value'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Recent Orders --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-800">Recent Orders</h3>
                </div>
                @forelse($recentOrders as $order)
                <div class="px-6 py-4 flex items-center justify-between border-b border-gray-50 last:border-0 hover:bg-gray-50 transition-colors">
                    <div>
                        <p class="font-medium text-gray-800 font-mono text-sm">{{ $order->invoice_number }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                    </div>
                    @php
                        $badge = match($order->payment_status) {
                            'paid'    => 'bg-emerald-100 text-emerald-700',
                            'pending' => 'bg-amber-100 text-amber-700',
                            'failed'  => 'bg-red-100 text-red-700',
                            default   => 'bg-gray-100 text-gray-600'
                        };
                    @endphp
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $badge }} capitalize">{{ $order->payment_status }}</span>
                </div>
                @empty
                <div class="px-6 py-8 text-center text-gray-400 text-sm">No orders yet. Explore events and get your tickets!</div>
                @endforelse
            </div>

            {{-- My E-Tickets Section --}}
            <div>
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">🎟️ My E-Tickets</h2>
                    <p class="text-gray-600 text-sm mt-1">Your digital tickets for upcoming events</p>
                </div>

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
                @endphp

                @if(count($allTickets) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($allTickets as $item)
                            @php
                                $event = $item['event'];
                                $ticket = $item['ticket'];
                                $category = $item['category'];
                            @endphp
                            <div class="bg-gradient-to-br from-blue-600 to-indigo-600 rounded-xl shadow-lg p-6 text-white relative overflow-hidden">
                                <!-- Background decoration -->
                                <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full"></div>
                                <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-white/5 rounded-full"></div>

                                <div class="relative z-10">
                                    <!-- Ticket Header -->
                                    <div class="mb-4 pb-4 border-b border-white/20">
                                        <div class="flex justify-between items-start mb-2">
                                            <span class="text-xs font-semibold uppercase tracking-widest text-blue-100">E-TICKET</span>
                                            @if($ticket->is_scanned)
                                                <span class="text-xs font-bold px-2 py-1 rounded-full bg-green-400 text-green-900">✓ Scanned</span>
                                            @else
                                                <span class="text-xs font-bold px-2 py-1 rounded-full bg-white/20">Not Used</span>
                                            @endif
                                        </div>
                                        <h3 class="text-2xl font-bold">{{ $event->title }}</h3>
                                    </div>

                                    <!-- Event Details -->
                                    <div class="space-y-2 mb-6 text-sm text-blue-100">
                                        <div>
                                            <p class="text-xs opacity-75">Event Date</p>
                                            <p class="font-semibold text-white">{{ $event->start_time->format('F d, Y • h:i A') }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs opacity-75">Location</p>
                                            <p class="font-semibold text-white">{{ $event->location }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs opacity-75">Ticket Type</p>
                                            <p class="font-semibold text-white">{{ $category->name }}</p>
                                        </div>
                                    </div>

                                    <!-- Barcode Section -->
                                    <div class="bg-white/10 rounded-lg p-4 mb-4 border border-white/20">
                                        <p class="text-xs font-semibold uppercase tracking-widest text-blue-100 mb-2">Barcode</p>
                                        <p class="font-mono text-lg font-bold text-white break-all">{{ $ticket->barcode_string }}</p>
                                    </div>

                                    <!-- Order Info -->
                                    <div class="text-xs text-blue-100 border-t border-white/20 pt-3">
                                        <p class="font-semibold text-white">Invoice: {{ $item['order']->invoice_number }}</p>
                                        <p>Purchased: {{ $item['order']->created_at->format('M d, Y') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-blue-50 border border-blue-200 rounded-xl shadow-sm p-8 text-center">
                        <svg class="w-16 h-16 text-blue-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                        </svg>
                        <h3 class="text-xl font-semibold text-blue-900 mb-2">No E-Tickets Yet</h3>
                        <p class="text-blue-700 mb-4">You haven't purchased any tickets yet. Start exploring events!</p>
                        <a href="{{ route('home') }}" class="inline-block bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700 transition">
                            Browse Events
                        </a>
                    </div>
                @endif
            </div>

            {{-- Role Badge --}}
            <div class="bg-white border border-gray-100 rounded-xl shadow-sm p-6 flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Logged in as</p>
                    <p class="font-semibold text-gray-800 mt-0.5">{{ Auth::user()->email }}</p>
                </div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-700 border border-blue-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500 inline-block"></span>
                    Customer
                </span>
            </div>

        </div>
    </div>
</x-app-layout>
