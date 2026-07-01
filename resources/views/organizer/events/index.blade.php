<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold text-slate-900">Event Saya</h2>
                <p class="mt-1 text-sm text-slate-500">Kelola daftar event dan kategori tiket Anda.</p>
            </div>
            @if($profile && $profile->verification_status === 'verified')
                <a href="{{ route('organizer.events.create') }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white text-xs font-semibold uppercase tracking-wider rounded-lg hover:bg-emerald-700 transition focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                    <span class="material-symbols-outlined text-base me-1.5" style="line-height:1;">add_circle</span>
                    Buat Event
                </a>
            @else
                <button type="button" onclick="alert('Anda harus melengkapi profil dan diverifikasi oleh admin sebelum dapat membuat event.');" class="inline-flex items-center px-4 py-2 bg-slate-400 text-white text-xs font-semibold uppercase tracking-wider rounded-lg cursor-not-allowed">
                    <span class="material-symbols-outlined text-base me-1.5" style="line-height:1;">add_circle</span>
                    Buat Event
                </button>
            @endif
        </div>
    </x-slot>

    <div class="space-y-6">


        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead>
                        <tr class="bg-slate-50">
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Banner</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Judul</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Tanggal</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Status</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($events as $event)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($event->banner_image)
                                        <img src="{{ Storage::url($event->banner_image) }}" alt="Banner" class="h-12 aspect-video object-cover rounded-lg shadow-sm">
                                    @else
                                        <div class="h-12 aspect-video bg-slate-100 rounded-lg flex items-center justify-center text-[10px] text-slate-400 border border-slate-200 text-center leading-tight">Tanpa Gbr</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-semibold text-slate-900">{{ $event->title }}</div>
                                    <div class="text-xs text-slate-500 mt-1 flex items-center gap-1">
                                        <span class="material-symbols-outlined w-3.5 h-3.5" style="line-height:1;">location_on</span>
                                        {{ $event->location }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-slate-900 font-medium">{{ $event->start_time->format('d M Y') }}</div>
                                    <div class="text-xs text-slate-500 mt-1">{{ $event->start_time->format('H:i') }} - {{ $event->end_time->format('H:i') }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusColor = match($event->status) {
                                            'active' => 'green',
                                            'pending', 'draft' => 'yellow',
                                            default => 'gray',
                                        };
                                    @endphp
                                    <x-badge :color="$statusColor" class="capitalize">{{ $event->status }}</x-badge>
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-medium space-x-2">
                                    <a href="{{ route('organizer.events.show', $event) }}" class="inline-flex items-center px-3 py-1.5 bg-emerald-50 text-emerald-700 font-medium rounded-lg hover:bg-emerald-100 transition text-sm">Kelola</a>
                                    <a href="{{ route('organizer.events.edit', $event) }}" class="inline-flex items-center px-3 py-1.5 bg-indigo-50 text-indigo-700 font-medium rounded-lg hover:bg-indigo-100 transition text-sm">Edit</a>
                                    <form action="{{ route('organizer.events.destroy', $event) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus event ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-50 text-red-700 font-medium rounded-lg hover:bg-red-100 transition text-sm">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-20 h-20 bg-emerald-50 rounded-full flex items-center justify-center mb-4">
                                            <span class="material-symbols-outlined text-4xl text-emerald-300" style="line-height:1;">calendar_month</span>
                                        </div>
                                        <h3 class="text-lg font-bold text-slate-900 mb-1">Event tidak ditemukan</h3>
                                        <p class="text-slate-500 text-sm mb-6 max-w-sm">Anda belum membuat event apa pun. Mulailah dengan membuat event pertama Anda!</p>
                                        @if($profile && $profile->verification_status === 'verified')
                                            <a href="{{ route('organizer.events.create') }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white text-xs font-semibold uppercase tracking-wider rounded-lg hover:bg-emerald-700 transition">
                                                Buat Event Pertama Anda
                                            </a>
                                        @else
                                            <a href="{{ route('profile.edit') }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white text-xs font-bold uppercase tracking-wider rounded-lg hover:bg-yellow-600 transition">
                                                Lengkapi Profil Untuk Mulai
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($events->hasPages())
                <div class="px-6 py-4 border-t border-slate-200 bg-slate-50">
                    {{ $events->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>