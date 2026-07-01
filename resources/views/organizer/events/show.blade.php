<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold text-slate-900">{{ $event->title }}</h2>
                <p class="mt-1 text-sm text-slate-500">Kelola detail event, tiket, dan pengaturan.</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('organizer.events.edit', $event) }}" class="inline-flex items-center px-4 py-2 bg-white border border-slate-300 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50 transition focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                    Edit Event
                </a>
                <a href="{{ route('organizer.events.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-800 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-slate-700 transition focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                    Kembali ke Daftar
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">


        <!-- Event Details -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            @if($event->banner_image)
                <img src="{{ Storage::url($event->banner_image) }}" alt="{{ $event->title }}" class="w-full aspect-video object-cover">
            @else
                <div class="w-full aspect-video bg-slate-100 flex items-center justify-center text-slate-400">Tanpa Gambar Spanduk</div>
            @endif
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-2">
                        <h3 class="text-lg font-bold text-slate-900 mb-2">Deskripsi</h3>
                        <p class="text-slate-700 whitespace-pre-line">{{ $event->description ?: 'Tidak ada deskripsi.' }}</p>
                    </div>
                    <div>
                        <div class="bg-slate-50 p-4 rounded-lg">
                            <h3 class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-2">Detail</h3>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-xs text-slate-500">Status</p>
                                    <p class="font-medium">
                                        @php
                                            $statusColor = match($event->status) {
                                                'active' => 'green',
                                                'pending', 'draft' => 'yellow',
                                                default => 'gray',
                                            };
                                        @endphp
                                        <x-badge :color="$statusColor" class="capitalize">{{ $event->status }}</x-badge>
                                    </p>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500">Lokasi</p>
                                    <p class="font-medium text-sm">{{ $event->location }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500">Waktu Mulai</p>
                                    <p class="font-medium text-sm">{{ $event->start_time->format('d M Y H:i') }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500">Waktu Selesai</p>
                                    <p class="font-medium text-sm">{{ $event->end_time->format('d M Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ticket Categories -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-6 text-slate-900 border-b border-slate-100 flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-bold text-slate-900">Kategori Tiket</h3>
                    <p class="text-sm text-slate-500 mt-1">Kelola harga dan ketersediaan untuk setiap jenis tiket.</p>
                </div>
                <a href="{{ route('organizer.events.ticket-categories.create', $event) }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white text-xs font-semibold uppercase tracking-wider rounded-lg hover:bg-emerald-700 transition focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                    <span class="material-symbols-outlined text-base me-1.5" style="line-height:1;">add_circle</span>
                    Tambah Kategori
                </a>
            </div>
            <div class="p-6">
                @if($event->ticketCategories->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($event->ticketCategories as $category)
                            <div class="border border-slate-200 rounded-lg p-4 shadow-sm hover:shadow-md transition">
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="font-bold text-lg text-slate-900">{{ $category->name }}</h4>
                                    <div class="flex gap-2">
                                        <a href="{{ route('organizer.events.ticket-categories.edit', [$event, $category]) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">Edit</a>
                                        <form action="{{ route('organizer.events.ticket-categories.destroy', [$event, $category]) }}" method="POST" onsubmit="return confirm('Hapus kategori tiket ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-medium">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-slate-500">Harga: <span class="font-medium text-slate-900">Rp {{ number_format($category->price, 0, ',', '.') }}</span></span>
                                    <span class="text-slate-500">Kuota: <span class="font-medium text-slate-900">{{ $category->quota }}</span></span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-slate-500 text-center py-4">Belum ada kategori tiket yang ditambahkan.</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>