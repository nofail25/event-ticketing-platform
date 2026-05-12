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
