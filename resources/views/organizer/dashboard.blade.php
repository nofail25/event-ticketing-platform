<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="flex-shrink-0 w-9 h-9 rounded-lg bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center shadow">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <h2 class="font-bold text-xl text-gray-800 leading-tight">Event Organizer Dashboard</h2>
                <p class="text-xs text-gray-500 font-medium">Manage your events and ticket categories</p>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Welcome Banner --}}
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-emerald-500 via-teal-500 to-cyan-600 p-8 text-white shadow-lg">
                <div class="absolute -right-10 -top-10 w-48 h-48 bg-white/10 rounded-full blur-xl"></div>
                <div class="relative z-10">
                    <p class="text-emerald-100 text-sm font-medium mb-1">Welcome to your workspace,</p>
                    <h1 class="text-3xl font-bold mb-2">{{ Auth::user()->name }} 🎪</h1>
                    <p class="text-teal-100">You're managing your events as an Event Organizer.</p>
                </div>
            </div>

            {{-- Stats Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                @php
                    $cards = [
                        ['label' => 'My Events',    'value' => $stats['total_events'],   'color' => 'bg-emerald-50 text-emerald-700 border-emerald-200', 'dot' => 'bg-emerald-500'],
                        ['label' => 'Active',        'value' => $stats['active_events'],  'color' => 'bg-blue-50 text-blue-700 border-blue-200',           'dot' => 'bg-blue-500'],
                        ['label' => 'Pending Review','value' => $stats['pending_events'], 'color' => 'bg-amber-50 text-amber-700 border-amber-200',         'dot' => 'bg-amber-500'],
                        ['label' => 'Drafts',        'value' => $stats['draft_events'],   'color' => 'bg-gray-50 text-gray-700 border-gray-200',            'dot' => 'bg-gray-400'],
                    ];
                @endphp

                @foreach($cards as $card)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">{{ $card['label'] }}</p>
                        <span class="w-2.5 h-2.5 rounded-full {{ $card['dot'] }}"></span>
                    </div>
                    <p class="text-4xl font-bold text-gray-800">{{ $card['value'] }}</p>
                </div>
                @endforeach
            </div>

            {{-- Recent Events --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="font-semibold text-gray-800">Recent Events</h3>
                    <span class="text-xs text-gray-400">Latest 5</span>
                </div>
                @forelse($recentEvents as $event)
                <div class="px-6 py-4 flex items-center justify-between border-b border-gray-50 last:border-0 hover:bg-gray-50 transition-colors">
                    <div>
                        <p class="font-medium text-gray-800">{{ $event->title }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $event->location }} · {{ $event->start_time->format('d M Y') }}</p>
                    </div>
                    @php
                        $badge = match($event->status) {
                            'active'    => 'bg-emerald-100 text-emerald-700',
                            'pending'   => 'bg-amber-100 text-amber-700',
                            'draft'     => 'bg-gray-100 text-gray-600',
                            'completed' => 'bg-blue-100 text-blue-700',
                            default     => 'bg-gray-100 text-gray-600'
                        };
                    @endphp
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $badge }} capitalize">{{ $event->status }}</span>
                </div>
                @empty
                <div class="px-6 py-8 text-center text-gray-400 text-sm">No events yet. Create your first event!</div>
                @endforelse
            </div>

            {{-- Role Badge --}}
            <div class="bg-white border border-gray-100 rounded-xl shadow-sm p-6 flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Logged in as</p>
                    <p class="font-semibold text-gray-800 mt-0.5">{{ Auth::user()->email }}</p>
                </div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700 border border-emerald-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 inline-block"></span>
                    Event Organizer
                </span>
            </div>

        </div>
    </div>
</x-app-layout>
