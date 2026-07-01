<x-app-layout>

    @php
        $allTickets = [];
        foreach($recentOrders as $order) {
            foreach($order->ticketDetails as $ticket) {
                $allTickets[] = [
                    'ticket' => $ticket,
                    'order' => $order,
                    'event' => $ticket->ticketCategory->event,
                    'category' => $ticket->ticketCategory,
                    'is_scanned' => $ticket->is_scanned
                ];
            }
        }
        $unusedCount = collect($allTickets)->where('is_scanned', false)->count();
        $scannedCount = collect($allTickets)->where('is_scanned', true)->count();

        // Group tickets by event id
        $groupedTickets = collect($allTickets)->groupBy(function($item) {
            return $item['event']->id;
        });
    @endphp

    <div class="py-12 bg-[#f8f8fc] min-h-screen">
        <div class="mx-auto max-w-7xl space-y-10 px-4 sm:px-6 lg:px-8">

            <!-- Dashboard Stats -->
            <section class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                <!-- Unused Tickets -->
                <div class="clean-card p-6 relative overflow-hidden group">
                    <div class="absolute -right-6 -top-6 w-32 h-32 bg-indigo-500/10 rounded-full blur-2xl group-hover:scale-125 transition-transform duration-700 ease-out-ui"></div>
                    <div class="relative z-10">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 010 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 010-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375z"/></svg>
                            </div>
                            <p class="text-[11px] font-black uppercase tracking-widest text-slate-500">Belum Digunakan</p>
                        </div>
                        <p class="font-helvetica text-4xl font-black text-slate-900">{{ $unusedCount }}</p>
                    </div>
                </div>

                <!-- Used Tickets -->
                <div class="clean-card p-6 relative overflow-hidden group">
                    <div class="absolute -right-6 -top-6 w-32 h-32 bg-emerald-500/10 rounded-full blur-2xl group-hover:scale-125 transition-transform duration-700 ease-out-ui"></div>
                    <div class="relative z-10">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <p class="text-[11px] font-black uppercase tracking-widest text-slate-500">Tiket Terpakai</p>
                        </div>
                        <p class="font-helvetica text-4xl font-black text-slate-900">{{ $scannedCount }}</p>
                    </div>
                </div>

                <!-- Total Orders -->
                <div class="clean-card p-6 relative overflow-hidden group">
                    <div class="absolute -right-6 -top-6 w-32 h-32 bg-violet-500/10 rounded-full blur-2xl group-hover:scale-125 transition-transform duration-700 ease-out-ui"></div>
                    <div class="relative z-10">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-violet-50 text-violet-600">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                            </div>
                            <p class="text-[11px] font-black uppercase tracking-widest text-slate-500">Total Pesanan</p>
                        </div>
                        <p class="font-helvetica text-4xl font-black text-slate-900">{{ $stats['total_orders'] }}</p>
                    </div>
                </div>
            </section>

            <div class="grid grid-cols-1 gap-10 lg:grid-cols-[1fr_380px]">
                <!-- Digital Wallet Section (Tickets) -->
                <section id="my-tickets" x-data="{ filter: 'all', activeTicket: null }">
                    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-indigo-500 mb-1">Dompet Tiket</p>
                            <h2 class="font-helvetica text-3xl font-black text-slate-900">Tiket Anda</h2>
                        </div>

                        <!-- Tab Filters -->
                        <div class="flex items-center gap-1 rounded-full border border-slate-200/80 bg-white p-1 shadow-sm">
                            <button @click="filter = 'all'" :class="filter === 'all' ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'" class="px-5 py-2 text-sm font-semibold rounded-full transition-all duration-200 ease-out-ui active:scale-[0.96]">Semua</button>
                            <button @click="filter = 'unused'" :class="filter === 'unused' ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'" class="px-5 py-2 text-sm font-semibold rounded-full transition-all duration-200 ease-out-ui active:scale-[0.96]">Belum Digunakan</button>
                            <button @click="filter = 'scanned'" :class="filter === 'scanned' ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'" class="px-5 py-2 text-sm font-semibold rounded-full transition-all duration-200 ease-out-ui active:scale-[0.96]">Terpakai</button>
                        </div>
                    </div>

                    @if($groupedTickets->count() > 0)
                        <div class="space-y-6">
                            @foreach($groupedTickets as $eventId => $group)
                                @php
                                    $event = $group->first()['event'];
                                    $totalInGroup = $group->count();
                                    $unusedInGroup = $group->where('is_scanned', false)->count();
                                    $scannedInGroup = $group->where('is_scanned', true)->count();
                                @endphp

                                <div x-data="{
                                        expanded: false,
                                        hasVisibleTickets() {
                                            if (this.filter === 'all') return true;
                                            if (this.filter === 'unused' && {{ $unusedInGroup }} > 0) return true;
                                            if (this.filter === 'scanned' && {{ $scannedInGroup }} > 0) return true;
                                            return false;
                                        }
                                     }"
                                     x-show="hasVisibleTickets()"
                                     x-transition:enter="transition ease-out-ui duration-300"
                                     x-transition:enter-start="opacity-0 translate-y-4"
                                     x-transition:enter-end="opacity-100 translate-y-0"
                                     class="clean-card bg-white rounded-3xl overflow-hidden group/accordion">

                                    <!-- Accordion Header -->
                                    <button @click="expanded = !expanded" class="w-full px-6 py-5 flex items-center justify-between bg-white focus:outline-none transition-colors hover:bg-slate-50/50">
                                        <div class="flex items-center gap-5 text-left">
                                            <div class="relative h-14 w-14 rounded-2xl overflow-hidden bg-slate-100 shrink-0 shadow-sm">
                                                @if($event->banner_image)
                                                    <img src="{{ asset('storage/' . $event->banner_image) }}" alt="" class="w-full h-full object-cover">
                                                @else
                                                    <img src="https://picsum.photos/seed/{{ $event->id }}/200/200" alt="" class="w-full h-full object-cover">
                                                @endif
                                                <div class="absolute inset-0 bg-black/10"></div>
                                            </div>
                                            <div>
                                                <h3 class="font-helvetica text-lg font-bold text-slate-900 group-hover/accordion:text-indigo-600 transition-colors">{{ $event->title }}</h3>
                                                <div class="flex flex-wrap items-center gap-2 mt-1.5">
                                                    <span class="text-xs font-semibold text-slate-500">{{ $totalInGroup }} Tiket</span>
                                                    @if($unusedInGroup > 0)
                                                        <span class="badge badge--green text-[10px]">{{ $unusedInGroup }} Berlaku</span>
                                                    @endif
                                                    @if($scannedInGroup > 0)
                                                        <span class="badge badge--indigo text-[10px] bg-slate-100 text-slate-600">{{ $scannedInGroup }} Terpakai</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="shrink-0 ml-4 flex h-10 w-10 items-center justify-center rounded-full bg-slate-50 text-slate-400 transition-all duration-300 ease-out-ui group-hover/accordion:bg-indigo-50 group-hover/accordion:text-indigo-600" :class="expanded ? 'rotate-180 bg-indigo-50 text-indigo-600' : ''">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                                        </div>
                                    </button>

                                    <!-- Accordion Body -->
                                    <div x-show="expanded" x-collapse>
                                        <div class="border-t border-slate-100 bg-[#f8f8fc] p-6">
                                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                                                @foreach($group as $item)
                                                    @php
                                                        $ticket = $item['ticket'];
                                                        $category = $item['category'];
                                                        $filterType = $ticket->is_scanned ? 'scanned' : 'unused';
                                                    @endphp
                                                    <div x-show="filter === 'all' || filter === '{{ $filterType }}'"
                                                         x-transition:enter="transition ease-out-ui duration-300"
                                                         x-transition:enter-start="opacity-0 scale-95"
                                                         x-transition:enter-end="opacity-100 scale-100"
                                                         class="relative flex overflow-hidden rounded-2xl bg-white shadow-sm border border-slate-200/80 transition-all hover:shadow-md hover:-translate-y-1 ease-out-ui duration-200 group/ticket">

                                                        <!-- Perforated left edge decoration -->
                                                        <div class="w-2 flex flex-col justify-between items-center py-2 border-r border-dashed border-slate-200 bg-slate-50 shrink-0"></div>

                                                        <div class="flex flex-1 items-center justify-between p-4 pl-3">
                                                            <div class="min-w-0 pr-3">
                                                                <p class="font-bold text-sm text-slate-900 truncate">{{ $category->name }}</p>
                                                                <div class="flex items-center gap-2 mt-1">
                                                                    <p class="text-xs font-mono text-slate-500">{{ substr($ticket->barcode_string, 0, 8) }}...</p>
                                                                    @if($ticket->is_scanned)
                                                                        <span class="inline-flex h-2 w-2 rounded-full bg-slate-300" title="Terpakai"></span>
                                                                    @else
                                                                        <span class="inline-flex h-2 w-2 rounded-full bg-emerald-500 animate-pulse" title="Belum Digunakan"></span>
                                                                    @endif
                                                                </div>
                                                            </div>

                                                            <button @click="activeTicket = {
                                                                category: @js($category->name),
                                                                event: @js($event->title),
                                                                location: @js($event->location),
                                                                name: @js(Auth::user()->name),
                                                                barcode: @js($ticket->barcode_string),
                                                                qr: @js(\App\Support\QrCode::svgDataUri($ticket->barcode_string, 200)),
                                                                time: @js($event->start_time->format('d M Y, H:i'))
                                                            }" class="shrink-0 flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 transition-all duration-150 ease-out-ui group-hover/ticket:bg-indigo-600 group-hover/ticket:text-white group-hover/ticket:shadow-md active:scale-[0.95]" title="Lihat E-Tiket">
                                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5zM18 14.625v3m-3-3h6m-3-3v3m-3-3h3"/></svg>
                                                            </button>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="clean-card p-12 text-center border border-slate-200">
                            <div class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-3xl bg-indigo-50 text-indigo-500">
                                <svg class="h-10 w-10" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 010 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 010-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375z"/></svg>
                            </div>
                            <h3 class="font-helvetica text-2xl font-black text-slate-900">Dompet Tiket Kosong</h3>
                            <p class="mx-auto mt-3 max-w-md text-slate-500 leading-relaxed">Anda belum memiliki tiket aktif. Mari temukan event menarik berikutnya!</p>
                            <a href="{{ route('home') }}" class="primary-button mt-8">Cari Event Sekarang</a>
                        </div>
                    @endif

                    <!-- Premium E-Ticket Modal -->
                    <div x-cloak x-show="activeTicket"
                         class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 p-4 backdrop-blur-sm"
                         x-transition:enter="transition ease-out-ui duration-300"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="transition ease-out-ui duration-200"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                    >
                        <div class="relative w-full max-w-sm outline-none" @click.outside="activeTicket = null" tabindex="0" @keydown.escape.window="activeTicket = null">
                            <!-- Ticket Card -->
                            <div class="rounded-[2rem] bg-white shadow-2xl overflow-hidden transform"
                                 x-show="activeTicket"
                                 x-transition:enter="transition ease-out-ui duration-400 delay-75"
                                 x-transition:enter-start="opacity-0 translate-y-12 scale-95 rotate-x-6"
                                 x-transition:enter-end="opacity-100 translate-y-0 scale-100 rotate-x-0"
                                 x-transition:leave="transition ease-out-ui duration-200"
                                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave-end="opacity-0 translate-y-8 scale-95"
                                 style="perspective: 1000px;"
                            >
                                <!-- Header Ticket (Gradient) -->
                                <div class="bg-gradient-to-br from-indigo-600 via-violet-600 to-purple-700 px-6 pt-10 pb-12 text-center text-white relative">
                                    <!-- Close Button -->
                                    <button @click="activeTicket = null" class="absolute top-5 right-5 w-8 h-8 flex items-center justify-center rounded-full bg-white/10 hover:bg-white/25 transition-colors text-white focus:outline-none focus:ring-2 focus:ring-white">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>

                                    <p class="text-[10px] font-black uppercase tracking-[0.3em] text-indigo-200 mb-2">Event Pass</p>
                                    <h3 class="font-helvetica text-2xl font-black leading-tight text-balance" x-text="activeTicket?.event"></h3>
                                    <p class="mt-2 text-sm text-indigo-100" x-text="activeTicket?.time"></p>
                                </div>

                                <!-- Body Ticket -->
                                <div class="bg-white relative px-6 pb-10 pt-8">
                                    <!-- Dashed cutout line -->
                                    <div class="absolute -top-[1px] left-0 right-0 border-t-2 border-dashed border-slate-200"></div>

                                    <!-- Punch holes -->
                                    <div class="absolute -top-4 -left-4 w-8 h-8 rounded-full bg-slate-900/60 shadow-[inset_0_-2px_4px_rgba(0,0,0,0.1)]"></div>
                                    <div class="absolute -top-4 -right-4 w-8 h-8 rounded-full bg-slate-900/60 shadow-[inset_0_-2px_4px_rgba(0,0,0,0.1)]"></div>

                                    <div class="flex justify-center mb-6 relative z-10">
                                        <div class="p-4 bg-white border border-slate-200 rounded-3xl shadow-sm">
                                            <img :src="activeTicket?.qr" alt="QR code" class="h-44 w-44" draggable="false">
                                        </div>
                                    </div>
                                    <p class="text-center font-mono text-sm font-black tracking-[0.2em] text-slate-800" x-text="activeTicket?.barcode"></p>

                                    <div class="mt-8 grid grid-cols-2 gap-y-5 gap-x-4 border-t border-slate-100 pt-6">
                                        <div>
                                            <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-1">Pemegang Tiket</p>
                                            <p class="text-sm font-bold text-slate-900 truncate" x-text="activeTicket?.name"></p>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-1">Kategori</p>
                                            <p class="text-sm font-bold text-slate-900 truncate" x-text="activeTicket?.category"></p>
                                        </div>
                                        <div class="col-span-2">
                                            <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-1">Lokasi</p>
                                            <p class="text-sm font-bold text-slate-900 line-clamp-2 leading-snug" x-text="activeTicket?.location"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Order History Sidebar -->
                <aside>
                    <div class="clean-card sticky top-24">
                        <div class="border-b border-slate-100 px-6 py-5 flex items-center justify-between">
                            <h3 class="font-helvetica text-lg font-black text-slate-900">Pesanan Terakhir</h3>
                            <span class="badge badge--indigo text-[10px]">{{ $recentOrders->count() }} Total</span>
                        </div>

                        <div class="divide-y divide-slate-100 max-h-[600px] overflow-y-auto scrollbar-hide">
                            @forelse($recentOrders->take(5) as $order)
                                @php
                                    $statusClass = match($order->payment_status) {
                                        'paid' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                        'pending' => 'bg-amber-50 text-amber-700 border-amber-100',
                                        'failed' => 'bg-rose-50 text-rose-700 border-rose-100',
                                        default => 'bg-slate-50 text-slate-600 border-slate-200',
                                    };
                                @endphp
                                <div class="px-6 py-5 hover:bg-slate-50 transition-colors group">
                                    <div class="flex items-center justify-between mb-2.5">
                                        <p class="font-mono text-sm font-black text-slate-900">{{ $order->invoice_number }}</p>
                                        <span class="inline-flex rounded-full px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider border {{ $statusClass }}">{{ $order->payment_status }}</span>
                                    </div>
                                    <p class="text-xs font-semibold text-slate-500 mb-2 flex items-center gap-1.5">
                                        <svg class="h-3.5 w-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        {{ $order->created_at->format('d M Y • H:i') }}
                                    </p>
                                    <div class="flex items-end justify-between mt-3 pt-3 border-t border-slate-50 border-dashed">
                                        <div>
                                            <p class="text-xs font-bold text-slate-400">{{ $order->ticketDetails->count() }} Tiket</p>
                                            <p class="text-sm font-black text-slate-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                                        </div>
                                        @if($order->payment_status === 'pending')
                                            @if($order->created_at->diffInMinutes(now()) <= 15)
                                                <a href="{{ route('checkout.payment', $order) }}" class="inline-flex items-center justify-center px-3 py-1.5 text-[10px] font-bold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors uppercase tracking-wider shadow-sm">Lanjut Bayar</a>
                                            @else
                                                <span class="text-[10px] font-bold text-rose-500 uppercase tracking-wider">Kedaluwarsa</span>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="px-6 py-12 text-center">
                                    <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-slate-50 text-slate-300">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/></svg>
                                    </div>
                                    <p class="text-sm font-medium text-slate-500">Belum ada transaksi.</p>
                                </div>
                            @endforelse
                        </div>

                        @if($recentOrders->count() > 5)
                            <div class="border-t border-slate-100 p-4">
                                <a href="#" class="secondary-button w-full">
                                    Lihat Semua Pesanan
                                </a>
                            </div>
                        @endif
                    </div>
                </aside>
            </div>
        </div>
    </div>
</x-app-layout>