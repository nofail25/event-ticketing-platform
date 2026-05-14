<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Transactions</h2>
                <p class="mt-1 text-sm text-gray-500">Global ticket order activity across the platform.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">Back to Dashboard</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-card>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Invoice Number</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Event Name</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Customer Name</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Total Amount</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Payment Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($orders as $order)
                                @php
                                    $eventNames = $order->ticketDetails
                                        ->map(fn ($ticket) => $ticket->ticketCategory?->event?->title)
                                        ->filter()
                                        ->unique()
                                        ->join(', ');

                                    $paymentColor = match($order->payment_status) {
                                        'paid' => 'green',
                                        'pending' => 'yellow',
                                        'failed' => 'red',
                                        default => 'gray',
                                    };
                                @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 font-mono text-sm font-semibold text-gray-900">{{ $order->invoice_number }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $eventNames ?: 'No event found' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $order->user?->name ?? 'Unknown Customer' }}</td>
                                    <td class="px-6 py-4 text-sm font-semibold text-gray-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4">
                                        <x-badge :color="$paymentColor" class="capitalize">{{ $order->payment_status }}</x-badge>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-500">No transactions found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($orders->hasPages())
                    <div class="border-t border-gray-100 bg-gray-50 px-6 py-4">
                        {{ $orders->links() }}
                    </div>
                @endif
            </x-card>
        </div>
    </div>
</x-app-layout>
