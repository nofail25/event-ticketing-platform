<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-slate-900">Permintaan Penarikan Dana</h2>
                <p class="mt-1 text-sm text-slate-500">Tinjau permintaan pencairan penyelenggara dan tandai transfer sebagai dibayar.</p>
            </div>
        </div>
    </x-slot>

    <div class="space-y-4">
        @if (session('status'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-800">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead>
                        <tr class="bg-slate-50">
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Penyelenggara</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Jumlah</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Detail Bank</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Diminta</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Status</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">Aksi</th>
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
                                <td class="px-6 py-4">
                                    <p class="text-sm font-semibold text-slate-900">{{ $withdrawal->user?->name ?? 'Penyelenggara Tidak Diketahui' }}</p>
                                    <p class="text-xs text-slate-500">{{ $withdrawal->user?->email }}</p>
                                </td>
                                <td class="px-6 py-4 text-sm font-semibold text-slate-900">Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-sm text-slate-700">
                                    <p class="font-medium text-slate-900">{{ $withdrawal->bank_info['bank_name'] ?? '-' }}</p>
                                    <p>{{ $withdrawal->bank_info['account_number'] ?? '-' }}</p>
                                    <p class="text-xs text-slate-500">{{ $withdrawal->bank_info['account_holder'] ?? '-' }}</p>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-700">{{ $withdrawal->created_at->format('d M Y, H:i') }}</td>
                                <td class="px-6 py-4">
                                    <x-badge :color="$statusColor" class="capitalize">{{ $withdrawal->status }}</x-badge>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    @if($withdrawal->status === 'pending')
                                        <form method="POST" action="{{ route('admin.withdrawals.complete', $withdrawal) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-xs font-semibold uppercase tracking-wider rounded-lg hover:bg-indigo-700 transition focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                                Tandai sebagai Dibayar
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-sm text-slate-400">Tidak ada aksi</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-sm text-slate-500">Tidak ada permintaan penarikan dana ditemukan.</td>
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
</x-app-layout>