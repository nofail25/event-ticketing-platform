<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Withdrawal Requests</h2>
                <p class="mt-1 text-sm text-gray-500">Review organizer payout requests and mark manual transfers as paid.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">Back to Dashboard</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if (session('status'))
                <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm font-medium text-green-800">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-800">
                    {{ $errors->first() }}
                </div>
            @endif

            <x-card>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Organizer</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Amount</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Bank Details</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Requested</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
                                <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">Action</th>
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
                                    <td class="px-6 py-4">
                                        <p class="text-sm font-semibold text-gray-900">{{ $withdrawal->user?->name ?? 'Unknown Organizer' }}</p>
                                        <p class="text-xs text-gray-500">{{ $withdrawal->user?->email }}</p>
                                    </td>
                                    <td class="px-6 py-4 text-sm font-semibold text-gray-900">Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        <p class="font-medium text-gray-900">{{ $withdrawal->bank_info['bank_name'] ?? '-' }}</p>
                                        <p>{{ $withdrawal->bank_info['account_number'] ?? '-' }}</p>
                                        <p class="text-xs text-gray-500">{{ $withdrawal->bank_info['account_holder'] ?? '-' }}</p>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $withdrawal->created_at->format('d M Y, H:i') }}</td>
                                    <td class="px-6 py-4">
                                        <x-badge :color="$statusColor" class="capitalize">{{ $withdrawal->status }}</x-badge>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        @if($withdrawal->status === 'pending')
                                            <form method="POST" action="{{ route('admin.withdrawals.complete', $withdrawal) }}">
                                                @csrf
                                                @method('PATCH')
                                                <x-primary-button>
                                                    Mark as Paid
                                                </x-primary-button>
                                            </form>
                                        @else
                                            <span class="text-sm text-gray-400">No action</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-sm text-gray-500">No withdrawal requests found.</td>
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
</x-app-layout>
