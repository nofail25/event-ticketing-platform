<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Withdrawals</h2>
                <p class="mt-1 text-sm text-gray-500">Track your wallet balance and payout requests.</p>
            </div>
            <a href="{{ route('organizer.dashboard') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">Back to Dashboard</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('status'))
                <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm font-medium text-green-800">
                    {{ session('status') }}
                </div>
            @endif

            <div class="grid grid-cols-1 gap-5 lg:grid-cols-3">
                <x-card class="lg:col-span-1 p-6">
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Current Balance</p>
                    <p class="mt-3 text-3xl font-bold text-gray-900">Rp {{ number_format($wallet['current_balance'], 0, ',', '.') }}</p>
                    <div class="mt-5 space-y-2 text-sm text-gray-600">
                        <div class="flex justify-between">
                            <span>Gross paid sales</span>
                            <span>Rp {{ number_format($wallet['gross_revenue'], 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Platform fee ({{ $wallet['platform_fee_percentage'] }}%)</span>
                            <span>- Rp {{ number_format($wallet['platform_fee_amount'], 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Pending/paid withdrawals</span>
                            <span>- Rp {{ number_format($wallet['reserved_withdrawals'], 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <x-primary-button class="mt-6 w-full" x-data="" x-on:click.prevent="$dispatch('open-modal', 'request-withdrawal')">
                        Request Withdrawal
                    </x-primary-button>
                </x-card>

                <x-card class="lg:col-span-2">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Date</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Amount</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Bank</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @forelse($withdrawals as $withdrawal)
                                    @php
                                        $statusColor = match($withdrawal->status) {
                                            'completed' => 'green',
                                            'rejected' => 'red',
                                            'pending' => 'yellow',
                                            default => 'gray',
                                        };
                                    @endphp
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 text-sm text-gray-700">{{ $withdrawal->created_at->format('d M Y, H:i') }}</td>
                                        <td class="px-6 py-4 text-sm font-semibold text-gray-900">Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-700">{{ $withdrawal->bank_info['bank_name'] ?? '-' }}</td>
                                        <td class="px-6 py-4">
                                            <x-badge :color="$statusColor" class="capitalize">{{ $withdrawal->status }}</x-badge>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 text-center text-sm text-gray-500">No withdrawal requests yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($withdrawals->hasPages())
                        <div class="border-t border-gray-100 bg-gray-50 px-6 py-4">
                            {{ $withdrawals->links() }}
                        </div>
                    @endif
                </x-card>
            </div>
        </div>
    </div>

    <x-modal name="request-withdrawal" :show="$errors->any()" focusable>
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900">Request Withdrawal</h3>
            <p class="mt-1 text-sm text-gray-500">The admin will review your bank details and mark the request as paid after manual transfer.</p>
            <div class="mt-6">
                @include('organizer.withdrawals._request-form', ['wallet' => $wallet])
            </div>
        </div>
    </x-modal>
</x-app-layout>
