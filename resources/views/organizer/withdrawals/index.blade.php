<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-slate-900">Withdrawals</h2>
                <p class="mt-1 text-sm text-slate-500">Track your wallet balance and payout requests.</p>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        @if (session('status'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
                {{ session('status') }}
            </div>
        @endif

        <div class="grid grid-cols-1 gap-5 lg:grid-cols-3">
            <!-- Balance Card -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Current Balance</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">Rp {{ number_format($wallet['current_balance'], 0, ',', '.') }}</p>
                <div class="mt-5 space-y-2 text-sm text-slate-600">
                    <div class="flex justify-between">
                        <span>Gross paid sales</span>
                        <span class="font-medium text-slate-900">Rp {{ number_format($wallet['gross_revenue'], 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Platform fee ({{ $wallet['platform_fee_percentage'] }}%)</span>
                        <span class="font-medium text-red-600">- Rp {{ number_format($wallet['platform_fee_amount'], 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Pending/paid withdrawals</span>
                        <span class="font-medium text-red-600">- Rp {{ number_format($wallet['reserved_withdrawals'], 0, ',', '.') }}</span>
                    </div>
                </div>
                <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'request-withdrawal')" class="mt-6 w-full inline-flex items-center justify-center px-4 py-2 bg-emerald-600 text-white text-sm font-semibold rounded-lg hover:bg-emerald-700 transition focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                    Request Withdrawal
                </button>
            </div>

            <!-- Withdrawals Table -->
            <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead>
                            <tr class="bg-slate-50">
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Date</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Amount</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Bank</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($withdrawals as $withdrawal)
                                @php
                                    $statusColor = match($withdrawal->status) {
                                        'completed' => 'green',
                                        'rejected' => 'red',
                                        'pending' => 'yellow',
                                        default => 'gray',
                                    };
                                @endphp
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4 text-sm text-slate-700">{{ $withdrawal->created_at->format('d M Y, H:i') }}</td>
                                    <td class="px-6 py-4 text-sm font-semibold text-slate-900">Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-sm text-slate-700">{{ $withdrawal->bank_info['bank_name'] ?? '-' }}</td>
                                    <td class="px-6 py-4">
                                        <x-badge :color="$statusColor" class="capitalize">{{ $withdrawal->status }}</x-badge>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-sm text-slate-500">No withdrawal requests yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($withdrawals->hasPages())
                    <div class="border-t border-slate-200 bg-slate-50 px-6 py-4">
                        {{ $withdrawals->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <x-modal name="request-withdrawal" :show="$errors->any()" focusable>
        <div class="p-6">
            <h3 class="text-lg font-semibold text-slate-900">Request Withdrawal</h3>
            <p class="mt-1 text-sm text-slate-500">The admin will review your bank details and mark the request as paid after manual transfer.</p>
            <div class="mt-6">
                @include('organizer.withdrawals._request-form', ['wallet' => $wallet])
            </div>
        </div>
    </x-modal>
</x-app-layout>