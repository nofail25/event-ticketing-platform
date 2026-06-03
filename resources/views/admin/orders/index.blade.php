<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-slate-900">Transactions</h2>
                <p class="mt-1 text-sm text-slate-500">Global ticket order activity across the platform.</p>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead>
                        <tr class="bg-slate-50">
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Invoice</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Event Name</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Customer</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Total Amount</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Payment Method</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
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
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 font-mono text-sm font-semibold text-slate-900">{{ $order->invoice_number }}</td>
                                <td class="px-6 py-4 text-sm text-slate-700">{{ $eventNames ?: 'No event found' }}</td>
                                <td class="px-6 py-4 text-sm text-slate-700">{{ $order->user?->name ?? 'Unknown Customer' }}</td>
                                <td class="px-6 py-4 text-sm font-semibold text-slate-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-sm text-slate-700">{{ $order->payment_method ? $order->payment_display_label : '-' }}</td>
                                <td class="px-6 py-4">
                                    <x-badge :color="$paymentColor" class="capitalize">{{ $order->payment_status }}</x-badge>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-sm text-slate-500">No transactions found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($orders->hasPages())
                <div class="border-t border-slate-200 bg-slate-50 px-6 py-4">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>