<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            {{-- Shield icon --}}
            <div class="flex-shrink-0 w-9 h-9 rounded-lg bg-gradient-to-br from-violet-600 to-indigo-700 flex items-center justify-center shadow">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <div>
                <h2 class="font-bold text-xl text-gray-800 leading-tight">Super Admin Dashboard</h2>
                <p class="text-xs text-gray-500 font-medium">Full system control & oversight</p>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Welcome Banner --}}
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-violet-600 via-indigo-600 to-blue-600 p-8 text-white shadow-lg">
                <div class="absolute -right-10 -top-10 w-48 h-48 bg-white/10 rounded-full blur-xl"></div>
                <div class="absolute -left-6 bottom-0 w-32 h-32 bg-white/5 rounded-full blur-lg"></div>
                <div class="relative z-10">
                    <p class="text-violet-200 text-sm font-medium mb-1">Welcome back,</p>
                    <h1 class="text-3xl font-bold mb-2">{{ Auth::user()->name }} 👋</h1>
                    <p class="text-indigo-200">You have full administrative access to the Event Ticketing Platform.</p>
                </div>
                <div class="absolute top-4 right-6 opacity-20">
                    <svg class="w-24 h-24 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                    </svg>
                </div>
            </div>

            {{-- Stats Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                @php
                    $cards = [
                        ['label' => 'Total Users',   'value' => number_format($stats['total_users']),   'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', 'from' => 'from-blue-500',   'to' => 'to-blue-600'],
                        ['label' => 'Total Events',  'value' => number_format($stats['total_events']),  'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'from' => 'from-emerald-500', 'to' => 'to-emerald-600'],
                        ['label' => 'Total Orders',  'value' => number_format($stats['total_orders']),  'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'from' => 'from-amber-500',   'to' => 'to-amber-600'],
                        ['label' => 'Total Revenue', 'value' => 'Rp ' . number_format($stats['total_revenue'], 0, ',', '.'), 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'from' => 'from-rose-500',    'to' => 'to-rose-600'],
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
                        <p class="text-2xl font-bold text-gray-800 mt-0.5">{{ $card['value'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Role Badge --}}
            <div class="bg-white border border-gray-100 rounded-xl shadow-sm p-6 flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Logged in as</p>
                    <p class="font-semibold text-gray-800 mt-0.5">{{ Auth::user()->email }}</p>
                </div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-violet-100 text-violet-700 border border-violet-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-violet-500 inline-block"></span>
                    Super Admin
                </span>
            </div>

        </div>
    </div>
</x-app-layout>
