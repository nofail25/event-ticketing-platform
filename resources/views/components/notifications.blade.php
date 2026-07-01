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
            $isOrderPaid = ($data['type'] ?? null) === 'order_paid';
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
                            <span class="material-symbols-outlined text-xl text-white" style="line-height:1;">check_circle</span>
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
                            @if(! empty($data['ticket_url']))
                                <a href="{{ $data['ticket_url'] }}" class="mt-3 inline-flex text-sm font-semibold text-blue-600 hover:text-blue-700">
                                    Lihat Tiket
                                </a>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-sm">
                            <span class="material-symbols-outlined text-xl text-white" style="line-height:1;">notifications</span>
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
                        <span class="material-symbols-outlined text-base" style="line-height:1;">check</span>
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
    <span class="material-symbols-outlined text-5xl text-gray-300 mx-auto mb-3" style="line-height:1;">notifications</span>
    <p class="text-gray-500 text-sm">Tidak ada notifikasi</p>
</div>
@endif
