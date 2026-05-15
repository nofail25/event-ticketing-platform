@props(['notifications' => []])

@php
    $unreadCount = $notifications->where('read_at', null)->count();
@endphp

@if($notifications->count() > 0)
<div class="space-y-3">
    @foreach($notifications->take(5) as $notification)
        @php
            $data = $notification->data;
            $isRead = $notification->read_at !== null;
            $isOrderPaid = $data['type'] ?? null === 'order_paid';
        @endphp
        
        <div class="relative rounded-lg border {{ $isRead ? 'border-gray-200 bg-white' : 'border-blue-200 bg-blue-50' }} p-4 transition-all hover:shadow-sm">
            <!-- Unread Indicator -->
            @if(!$isRead)
            <div class="absolute top-4 right-4">
                <span class="inline-block w-2 h-2 rounded-full bg-blue-500"></span>
            </div>
            @endif

            <!-- Notification Content -->
            <div class="pr-8">
                @if($isOrderPaid)
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center shadow-sm">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="font-semibold text-gray-900 text-sm">Pembayaran Berhasil</h4>
                            <p class="text-gray-600 text-sm mt-1">{{ $data['message'] ?? 'Pembayaran tiket Anda berhasil diproses.' }}</p>
                            <div class="mt-2 flex flex-wrap gap-2 text-xs">
                                <span class="inline-block px-2 py-1 bg-emerald-100 text-emerald-700 rounded">
                                    Invoice: {{ $data['invoice_number'] ?? 'N/A' }}
                                </span>
                                <span class="inline-block px-2 py-1 bg-blue-100 text-blue-700 rounded">
                                    {{ $data['ticket_count'] ?? '0' }} tiket
                                </span>
                                <span class="inline-block px-2 py-1 bg-gray-100 text-gray-700 rounded">
                                    Rp {{ number_format($data['total_amount'] ?? 0, 0, ',', '.') }}
                                </span>
                            </div>
                            <p class="text-gray-500 text-xs mt-2">{{ $notification->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @else
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-sm">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="font-semibold text-gray-900 text-sm">Notifikasi</h4>
                            <p class="text-gray-600 text-sm mt-1">{{ $data['message'] ?? 'Anda memiliki notifikasi baru.' }}</p>
                            <p class="text-gray-500 text-xs mt-2">{{ $notification->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Mark as Read Button -->
            @if(!$isRead)
            <div class="absolute top-4 right-4 flex items-center gap-2">
                <form method="POST" action="{{ route('notifications.mark-read', $notification->id) }}" class="inline">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </button>
                </form>
            </div>
            @endif
        </div>
    @endforeach

    @if($notifications->count() > 5)
    <div class="text-center pt-2">
        <a href="{{ route('notifications.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700">
            Lihat semua notifikasi ({{ $notifications->count() }})
        </a>
    </div>
    @endif
</div>
@else
<div class="rounded-lg border border-gray-200 bg-white p-8 text-center">
    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
    </svg>
    <p class="text-gray-500 text-sm">Tidak ada notifikasi</p>
</div>
@endif
