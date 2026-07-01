<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-slate-900">Penarikan Dana</h2>
                <p class="mt-1 text-sm text-slate-500">Lacak saldo dompet dan permintaan pencairan Anda.</p>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        @if (session('status'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
                {{ session('status') }}
            </div>
        @endif

        <div class="rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-800 flex gap-3">
            <svg class="h-5 w-5 shrink-0 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            <div>
                <span class="font-semibold">Keamanan Dana (Escrow):</span> Saldo penjualan tiket untuk event yang masih aktif (belum selesai) tidak dapat dicairkan. Dana baru akan masuk ke saldo yang bisa ditarik setelah waktu event berakhir.
            </div>
        </div>

        <div class="grid grid-cols-1 gap-5 lg:grid-cols-3">
            <!-- Balance Card -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Saldo Saat Ini</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">Rp {{ number_format($wallet['current_balance'], 0, ',', '.') }}</p>
                <div class="mt-5 space-y-2 text-sm text-slate-600">
                    <div class="flex justify-between">
                        <span>Total Penjualan Kotor (Semua Event)</span>
                        <span class="font-medium text-slate-900">Rp {{ number_format($wallet['gross_revenue'], 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Penjualan Kotor (Event Selesai)</span>
                        <span class="font-medium text-slate-900">Rp {{ number_format($wallet['withdrawable_gross'], 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Biaya platform ({{ $wallet['platform_fee_percentage'] }}%) pada event selesai</span>
                        <span class="font-medium text-red-600">- Rp {{ number_format($wallet['withdrawable_fee_amount'], 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Penarikan tertunda/dibayar</span>
                        <span class="font-medium text-red-600">- Rp {{ number_format($wallet['reserved_withdrawals'], 0, ',', '.') }}</span>
                    </div>
                </div>
                <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'request-withdrawal')" class="mt-6 w-full inline-flex items-center justify-center px-4 py-2 bg-emerald-600 text-white text-sm font-semibold rounded-lg hover:bg-emerald-700 transition focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                    Tarik Dana
                </button>
            </div>

            <!-- Withdrawals Table -->
            <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead>
                            <tr class="bg-slate-50">
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Tanggal</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Jumlah</th>
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
                                    <td colspan="4" class="px-6 py-12 text-center text-sm text-slate-500">Belum ada permintaan penarikan dana.</td>
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
            <h3 class="text-lg font-semibold text-slate-900">Permintaan Penarikan Dana</h3>
            <p class="mt-1 text-sm text-slate-500">Admin akan meninjau detail bank Anda dan menandai permintaan sebagai dibayar setelah transfer manual.</p>
            <div class="mt-6">
                @include('organizer.withdrawals._request-form', ['wallet' => $wallet, 'profile' => $profile])
            </div>
        </div>
    </x-modal>
</x-app-layout>