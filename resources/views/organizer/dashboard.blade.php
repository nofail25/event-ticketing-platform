<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="flex-shrink-0 w-9 h-9 rounded-lg bg-emerald-600 flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-slate-900">Organizer Dashboard</h2>
                <p class="text-xs text-slate-500 font-medium">Manage your events and ticket categories</p>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Welcome Banner -->
        <div class="bg-emerald-600 rounded-xl p-6 text-white shadow-sm">
            <p class="text-emerald-100 text-sm font-medium">Welcome back,</p>
            <h1 class="text-2xl font-bold mt-1">{{ Auth::user()->name }}</h1>
            <p class="text-emerald-100 text-sm mt-1">Manage your events and grow your audience.</p>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">My Events</p>
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                </div>
                <p class="text-3xl font-bold text-slate-900">{{ $stats['total_events'] }}</p>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Active</p>
                    <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                </div>
                <p class="text-3xl font-bold text-slate-900">{{ $stats['active_events'] }}</p>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Pending Review</p>
                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                </div>
                <p class="text-3xl font-bold text-slate-900">{{ $stats['pending_events'] }}</p>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Drafts</p>
                    <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                </div>
                <p class="text-3xl font-bold text-slate-900">{{ $stats['draft_events'] }}</p>
            </div>
        </div>

        @if (session('status'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
                {{ session('status') }}
            </div>
        @endif

        <!-- Wallet & Recent Withdrawals -->
        <div class="grid grid-cols-1 gap-5 lg:grid-cols-3">
            <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">My Wallet</p>
                        <p class="mt-2 text-3xl font-bold text-slate-900">Rp {{ number_format($wallet['current_balance'], 0, ',', '.') }}</p>
                        <p class="mt-1 text-sm text-slate-500">Current balance after {{ $wallet['platform_fee_percentage'] }}% platform fee.</p>
                    </div>
                    <div class="flex gap-2">
                        <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'request-withdrawal')" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white text-sm font-semibold rounded-lg hover:bg-emerald-700 transition focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                            Request Withdrawal
                        </button>
                        <a href="{{ route('organizer.withdrawals.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-slate-700 border border-slate-300 rounded-lg hover:bg-slate-50 transition">
                            History
                        </a>
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-1 gap-3 sm:grid-cols-3">
                    <div class="rounded-lg bg-slate-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Gross Sales</p>
                        <p class="mt-2 font-bold text-slate-900">Rp {{ number_format($wallet['gross_revenue'], 0, ',', '.') }}</p>
                    </div>
                    <div class="rounded-lg bg-slate-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Platform Fee</p>
                        <p class="mt-2 font-bold text-slate-900">Rp {{ number_format($wallet['platform_fee_amount'], 0, ',', '.') }}</p>
                    </div>
                    <div class="rounded-lg bg-slate-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Reserved</p>
                        <p class="mt-2 font-bold text-slate-900">Rp {{ number_format($wallet['reserved_withdrawals'], 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="font-semibold text-slate-900">Recent Withdrawals</h3>
                    <a href="{{ route('organizer.withdrawals.index') }}" class="text-xs font-semibold text-emerald-600 hover:text-emerald-800">View all</a>
                </div>
                @forelse($recentWithdrawals as $withdrawal)
                    @php
                        $statusClass = match($withdrawal->status) {
                            'completed' => 'bg-emerald-50 text-emerald-700',
                            'rejected' => 'bg-red-50 text-red-700',
                            default => 'bg-amber-50 text-amber-700',
                        };
                    @endphp
                    <div class="px-6 py-4 border-b border-slate-50 last:border-0">
                        <div class="flex items-center justify-between gap-3">
                            <p class="font-semibold text-slate-900">Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}</p>
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $statusClass }} capitalize">{{ $withdrawal->status }}</span>
                        </div>
                        <p class="mt-1 text-xs text-slate-500">{{ $withdrawal->created_at->format('d M Y, H:i') }}</p>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-slate-400 text-sm">No withdrawal requests yet.</div>
                @endforelse
            </div>
        </div>

        <!-- Recent Events -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="font-semibold text-slate-900">Recent Events</h3>
                <span class="text-xs text-slate-400">Latest 5</span>
            </div>
            @forelse($recentEvents as $event)
            <div class="px-6 py-4 flex items-center justify-between border-b border-slate-50 last:border-0 hover:bg-slate-50 transition-colors">
                <div>
                    <p class="font-medium text-slate-900">{{ $event->title }}</p>
                    <p class="text-xs text-slate-500 mt-0.5">{{ $event->location }} · {{ $event->start_time->format('d M Y') }}</p>
                </div>
                @php
                    $badge = match($event->status) {
                        'active'    => 'bg-emerald-50 text-emerald-700',
                        'pending'   => 'bg-amber-50 text-amber-700',
                        'draft'     => 'bg-slate-100 text-slate-600',
                        'completed' => 'bg-blue-50 text-blue-700',
                        default     => 'bg-slate-100 text-slate-600'
                    };
                @endphp
                <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $badge }} capitalize">{{ $event->status }}</span>
            </div>
            @empty
            <div class="px-6 py-8 text-center text-slate-400 text-sm">No events yet. Create your first event!</div>
            @endforelse
        </div>

        <!-- Role Badge -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 flex items-center justify-between">
            <div>
                <p class="text-sm text-slate-500">Logged in as</p>
                <p class="font-semibold text-slate-900 mt-0.5">{{ Auth::user()->email }}</p>
            </div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 inline-block"></span>
                Event Organizer
            </span>
        </div>
    </div>

    <x-modal name="request-withdrawal" :show="$errors->any()" focusable>
        <div class="p-6">
            <h3 class="text-lg font-semibold text-slate-900">Request Withdrawal</h3>
            <p class="mt-1 text-sm text-slate-500">Submit your bank details for a manual payout review.</p>
            <div class="mt-6">
                @include('organizer.withdrawals._request-form', ['wallet' => $wallet])
            </div>
        </div>
    </x-modal>
</x-app-layout>