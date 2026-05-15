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
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-indigo-700 via-indigo-600 to-purple-700 p-8 md:p-10 text-white shadow-lg">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(255,255,255,0.18),transparent_32rem)]"></div>
                <div class="relative z-10">
                    <p class="text-indigo-100 text-sm font-medium mb-1">Hello, Customer</p>
                    <h1 class="text-2xl md:text-3xl font-bold mb-2">{{ Auth::user()->name }}</h1>
                    <p class="text-indigo-100 text-base md:text-lg">Welcome to your ticket hub. Explore events and manage your orders.</p>
                    <x-primary-button href="{{ route('home') }}" class="mt-5">
                        Browse More Events
                    </x-primary-button>
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
                <x-card class="p-6 flex items-center gap-4 hover:shadow-md transition-shadow duration-200">
                    <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-gradient-to-br {{ $card['from'] }} {{ $card['to'] }} flex items-center justify-center shadow-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $card['icon'] }}"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">{{ $card['label'] }}</p>
                        <p class="text-3xl font-bold text-gray-800 mt-0.5">{{ $card['value'] }}</p>
                    </div>
                </x-card>
                @endforeach
            </div>

            {{-- Notifications Section --}}
            @if($notifications->count() > 0)
            <div>
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-2xl font-bold text-gray-900">Notifikasi Terbaru</h2>
                    <a href="{{ route('notifications.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700">
                        Lihat semua →
                    </a>
                </div>
                <x-card class="p-6">
                    <x-notifications :notifications="$notifications" />
                </x-card>
            </div>
            @endif

            {{-- Recent Orders --}}
            <x-card>
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-800">Recent Orders</h3>
                </div>
                @forelse($recentOrders as $order)
                <div class="px-6 py-4 flex items-center justify-between border-b border-gray-50 last:border-0 hover:bg-gray-50 transition-colors">
                    <div>
                        <p class="font-medium text-gray-800 font-mono text-sm">{{ $order->invoice_number }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                        @if($order->payment_method)
                            <p class="text-xs font-semibold text-blue-700 mt-1">Paid via {{ $order->payment_display_label }}</p>
                        @endif
                    </div>
                    @php
                        $badgeColor = match($order->payment_status) {
                            'paid'    => 'green',
                            'pending' => 'yellow',
                            'failed'  => 'red',
                            default   => 'gray'
                        };
                    @endphp
                    <x-badge :color="$badgeColor" class="capitalize">{{ $order->payment_status }}</x-badge>
                </div>
                @empty
                <div class="px-6 py-12 text-center">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <p class="text-gray-500 text-sm">No orders found.</p>
                </div>
                @endforelse
            </x-card>

            {{-- My E-Tickets Section --}}
            <div>
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">My E-Tickets</h2>
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
                            <div x-data="{ showQR: false }" class="bg-gradient-to-br from-blue-600 to-indigo-600 rounded-xl shadow-lg overflow-hidden transition-all duration-300 text-white relative">
                                <!-- Background decoration -->
                                <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full"></div>
                                <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-white/5 rounded-full"></div>

                                <div class="relative z-10">
                                    <!-- Compact View (Always Visible) -->
                                    <div class="p-6">
                                        <!-- Ticket Header -->
                                        <div class="mb-4">
                                            <div class="flex justify-between items-start mb-3">
                                                <span class="text-xs font-semibold uppercase tracking-widest text-blue-100">E-TICKET</span>
                                                @if($ticket->is_scanned)
                                                    <span class="text-xs font-bold px-2 py-1 rounded-full bg-green-400 text-green-900">✓ Scanned</span>
                                                @else
                                                    <span class="text-xs font-bold px-2 py-1 rounded-full bg-white/20">Not Used</span>
                                                @endif
                                            </div>
                                            <h3 class="text-xl font-bold mb-3">{{ $event->title }}</h3>
                                        </div>

                                        <!-- Event Details (Compact) -->
                                        <div class="space-y-2 mb-6 text-sm text-blue-100">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                <span class="text-white font-semibold">{{ $event->start_time->format('F d, Y • h:i A') }}</span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                                                </svg>
                                                <span class="text-white font-semibold">{{ $category->name }}</span>
                                            </div>
                                        </div>

                                        <!-- Toggle Button -->
                                        <button
                                            @click="showQR = !showQR"
                                            class="w-full px-4 py-3 font-semibold rounded-lg transition-all duration-200 flex items-center justify-center gap-2"
                                            :class="showQR ? 'bg-white text-indigo-600 hover:bg-blue-50' : 'bg-white/20 text-white hover:bg-white/30 border border-white/30'"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path x-show="!showQR" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m0 0h6m0 0h-6m0-6H6"></path>
                                                <path x-show="showQR" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12a8 8 0 11-16 0 8 8 0 0116 0zM9 9l3 3m0 0l3-3m0 0l-3-3m0 0l-3 3"></path>
                                            </svg>
                                            <span x-text="showQR ? 'Hide QR Code' : 'View QR Code'"></span>
                                        </button>

                                        <!-- Invoice Info -->
                                        <div class="text-xs text-blue-100 mt-4 pt-4 border-t border-white/20">
                                            <p class="font-semibold text-white mb-1">{{ $item['order']->invoice_number }}</p>
                                            <p>Purchased: {{ $item['order']->created_at->format('M d, Y') }}</p>
                                            @if($item['order']->payment_method)
                                                <p class="mt-1">Paid via {{ $item['order']->payment_display_label }}</p>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Expanded QR Code Section -->
                                    <div
                                        x-show="showQR"
                                        x-transition:enter="transition ease-out duration-300"
                                        x-transition:enter-start="opacity-0"
                                        x-transition:enter-end="opacity-100"
                                        x-transition:leave="transition ease-in duration-200"
                                        x-transition:leave-start="opacity-100"
                                        x-transition:leave-end="opacity-0"
                                        class="bg-white/10 border-t border-white/20 p-6 space-y-4"
                                    >
                                        <div>
                                            <p class="text-xs font-semibold uppercase tracking-widest text-blue-100 mb-3">QR Code</p>
                                            <div class="flex justify-center">
                                                <div class="bg-white p-4 rounded-lg shadow-lg">
                                                    <img
                                                        src="{{ \App\Support\QrCode::svgDataUri($ticket->barcode_string, 160) }}"
                                                        alt="QR code for ticket {{ $ticket->id }}"
                                                        class="w-40 h-40"
                                                    >
                                                </div>
                                            </div>
                                        </div>

                                        <div>
                                            <p class="text-xs font-semibold uppercase tracking-widest text-blue-100 mb-2">Ticket Code</p>
                                            <p class="font-mono text-sm font-semibold text-white break-all bg-white/10 p-3 rounded-lg border border-white/20">{{ $ticket->barcode_string }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <x-card class="p-12 text-center bg-gray-50/50">
                        <div class="flex flex-col items-center justify-center">
                            <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mb-5">
                                <svg class="w-10 h-10 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">No E-Tickets Yet</h3>
                            <p class="text-gray-500 mb-6 max-w-md">You haven't purchased any tickets yet. Explore upcoming events and get your passes today!</p>
                            <x-primary-button href="{{ route('home') }}">
                                Browse Events
                            </x-primary-button>
                        </div>
                    </x-card>
                @endif
            </div>

            {{-- Role Badge --}}
            <x-card class="p-6 flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Logged in as</p>
                    <p class="font-semibold text-gray-800 mt-0.5">{{ Auth::user()->email }}</p>
                </div>
                <x-badge color="blue" class="border border-blue-200 gap-1.5 px-3 py-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500 inline-block"></span>
                    Customer
                </x-badge>
            </x-card>

        </div>
    </div>
</x-app-layout>
