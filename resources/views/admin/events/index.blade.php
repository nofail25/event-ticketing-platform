<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-slate-900">Kelola Event</h2>
                <p class="mt-1 text-sm text-slate-500">Tinjau kiriman penyelenggara dan terbitkan event yang disetujui.</p>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">


        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead>
                        <tr class="bg-slate-50">
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Judul Event</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Penyelenggara</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Tanggal Mulai</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Status</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($events as $event)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4">
                                    <p class="font-semibold text-slate-900">{{ $event->title }}</p>
                                    <p class="mt-0.5 text-xs text-slate-500">{{ $event->location }}</p>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-700">
                                    {{ $event->organizer?->name ?? 'Penyelenggara Tidak Diketahui' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-700">
                                    <span class="font-medium">{{ $event->start_time->format('d M Y') }}</span>
                                    <span class="block text-xs text-slate-400">{{ $event->start_time->format('H:i') }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusColor = match($event->status) {
                                            'pending' => 'yellow',
                                            'active' => 'green',
                                            'rejected' => 'red',
                                            default => 'gray',
                                        };
                                    @endphp
                                    <x-badge :color="$statusColor" class="capitalize">{{ $event->status }}</x-badge>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    @if($event->status === 'pending')
                                        <div class="flex items-center justify-end space-x-2">
                                            <form method="POST" action="{{ route('admin.events.approve', $event) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white text-xs font-semibold uppercase tracking-wider rounded-lg hover:bg-emerald-700 transition focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                                                    Setujui
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.events.reject', $event) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-rose-600 text-white text-xs font-semibold uppercase tracking-wider rounded-lg hover:bg-rose-700 transition focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-2" onclick="return confirm('Apakah Anda yakin ingin menolak event ini?');">
                                                    Tolak
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="text-sm text-slate-400">Tidak ada aksi</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-sm text-slate-500">Tidak ada event ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($events->hasPages())
                <div class="border-t border-slate-200 bg-slate-50 px-6 py-4">
                    {{ $events->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>