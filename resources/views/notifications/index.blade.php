<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="flex-shrink-0 w-9 h-9 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </div>
                <div>
                    <h2 class="font-bold text-xl text-gray-800 leading-tight">Notifikasi</h2>
                    <p class="text-xs text-gray-500 font-medium">Kelola semua notifikasi Anda</p>
                </div>
            </div>
            <div class="flex gap-2">
                @if(Auth::user()->unreadNotifications->count() > 0)
                <form method="POST" action="{{ route('notifications.mark-all-read') }}" class="inline">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-blue-600 hover:text-blue-700 border border-blue-200 rounded-lg hover:bg-blue-50 transition-colors">
                        Tandai Semua Dibaca
                    </button>
                </form>
                @endif
                <form method="POST" action="{{ route('notifications.delete-all') }}" class="inline" onsubmit="return confirm('Hapus semua notifikasi?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-red-600 hover:text-red-700 border border-red-200 rounded-lg hover:bg-red-50 transition-colors">
                        Hapus Semua
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Status Messages --}}
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <p class="text-red-800 text-sm font-medium">{{ $errors->first() }}</p>
                </div>
            @endif

            @if (session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-lg">
                    <p class="text-emerald-800 text-sm font-medium">{{ session('success') }}</p>
                </div>
            @endif

            {{-- Unread Count Badge --}}
            @php
                $unreadCount = Auth::user()->unreadNotifications->count();
            @endphp
            @if($unreadCount > 0)
            <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <p class="text-blue-800 text-sm">
                    <span class="font-semibold">{{ $unreadCount }}</span> notifikasi belum dibaca
                </p>
            </div>
            @endif

            {{-- Notifications List --}}
            <div class="space-y-3">
                @forelse($notifications as $notification)
                    @php
                        $data = $notification->data;
                        $isRead = $notification->read_at !== null;
                        $isOrderPaid = ($data['type'] ?? null) === 'order_paid';
                    @endphp
                    
                    <div class="relative rounded-lg border {{ $isRead ? 'border-gray-200 bg-white' : 'border-blue-200 bg-blue-50' }} p-6 transition-all hover:shadow-md">
                        <!-- Unread Indicator -->
                        @if(!$isRead)
                        <div class="absolute top-4 right-4">
                            <span class="inline-block w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                        </div>
                        @endif

                        <!-- Notification Content -->
                        <div class="pr-12">
                            @if($isOrderPaid)
                                <div class="flex items-start gap-4">
                                    <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center shadow-sm">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <h3 class="font-semibold text-gray-900">Pembayaran Berhasil</h3>
                                            <span class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-gray-600 text-sm mt-2">{{ $data['message'] ?? 'Pembayaran tiket Anda berhasil diproses.' }}</p>
                                        <div class="mt-3 grid grid-cols-2 sm:grid-cols-4 gap-2">
                                            <div class="px-3 py-2 bg-emerald-100 text-emerald-700 rounded text-xs">
                                                <p class="text-xs font-semibold">Invoice</p>
                                                <p class="font-mono">{{ $data['invoice_number'] ?? 'N/A' }}</p>
                                            </div>
                                            <div class="px-3 py-2 bg-blue-100 text-blue-700 rounded text-xs">
                                                <p class="text-xs font-semibold">Jumlah Tiket</p>
                                                <p class="font-bold text-lg">{{ $data['ticket_count'] ?? '0' }}</p>
                                            </div>
                                            <div class="px-3 py-2 bg-purple-100 text-purple-700 rounded text-xs">
                                                <p class="text-xs font-semibold">Acara</p>
                                                <p class="truncate font-medium">{{ $data['event_title'] ?? 'N/A' }}</p>
                                            </div>
                                            <div class="px-3 py-2 bg-orange-100 text-orange-700 rounded text-xs">
                                                <p class="text-xs font-semibold">Total</p>
                                                <p class="font-bold">Rp {{ number_format($data['total_amount'] ?? 0, 0, ',', '.') }}</p>
                                            </div>
                                        </div>
                                        @if(! empty($data['ticket_url']))
                                            <a href="{{ $data['ticket_url'] }}" class="mt-4 inline-flex items-center rounded-lg bg-emerald-700 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-800">
                                                Lihat Tiket
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <div class="flex items-start gap-4">
                                    <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-sm">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <h3 class="font-semibold text-gray-900">Notifikasi</h3>
                                            <span class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-gray-600 text-sm mt-2">{{ $data['message'] ?? 'Anda memiliki notifikasi baru.' }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Action Buttons -->
                        <div class="absolute top-4 right-4 flex items-center gap-2">
                            @if(!$isRead)
                            <form method="POST" action="{{ route('notifications.mark-read', $notification->id) }}" class="inline">
                                @csrf
                                @method('PUT')
                                <button type="submit" title="Tandai sebagai dibaca" class="p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </button>
                            </form>
                            @endif
                            <form method="POST" action="{{ route('notifications.destroy', $notification->id) }}" class="inline" onsubmit="return confirm('Hapus notifikasi ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" title="Hapus" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="rounded-lg border border-gray-200 bg-white p-12 text-center">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <p class="text-gray-500 text-lg font-medium">Tidak ada notifikasi</p>
                        <p class="text-gray-400 text-sm mt-1">Anda akan menerima notifikasi ketika ada aktivitas penting.</p>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if($notifications->hasPages())
            <div class="mt-8">
                {{ $notifications->links() }}
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
