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
    @endphp

    <div class="py-10 bg-slate-50/50">
        <div class="mx-auto max-w-7xl space-y-10 px-4 sm:px-6 lg:px-8">
            
            <!-- Dashboard Stats -->
            <section class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                <div class="clean-card p-6 border border-slate-200 bg-white relative overflow-hidden group">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-indigo-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700"></div>
                    <div class="relative z-10">
                        <p class="text-xs font-bold uppercase tracking-widest text-slate-500">Unused Passes</p>
                        <p class="mt-2 text-4xl font-black text-indigo-600">{{ $unusedCount }}</p>
                    </div>
                </div>
                <div class="clean-card p-6 border border-slate-200 bg-white relative overflow-hidden group">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700"></div>
                    <div class="relative z-10">
                        <p class="text-xs font-bold uppercase tracking-widest text-slate-500">Scanned Tickets</p>
                        <p class="mt-2 text-4xl font-black text-emerald-600">{{ $scannedCount }}</p>
                    </div>
                </div>
                <div class="clean-card p-6 border border-slate-200 bg-white relative overflow-hidden group">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-purple-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700"></div>
                    <div class="relative z-10">
                        <p class="text-xs font-bold uppercase tracking-widest text-slate-500">Total Orders</p>
                        <p class="mt-2 text-4xl font-black text-purple-600">{{ $stats['total_orders'] }}</p>
                    </div>
                </div>
            </section>

            <div class="grid grid-cols-1 gap-10 lg:grid-cols-[1fr_380px]">
                <!-- Digital Wallet Section (Tickets) -->
                <section id="my-tickets" x-data="{ filter: 'all', activeModal: null }">
                    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h2 class="text-2xl font-black text-slate-900">Your Tickets</h2>
                        </div>
                        
                        <!-- Tab Filters -->
                        <div class="flex items-center gap-1 rounded-2xl border border-slate-200 bg-white p-1 shadow-sm">
                            <button @click="filter = 'all'" :class="filter === 'all' ? 'bg-indigo-50 text-indigo-700 font-bold' : 'text-slate-500 hover:text-slate-700'" class="px-4 py-2 text-sm rounded-xl transition-all">All Passes</button>
                            <button @click="filter = 'unused'" :class="filter === 'unused' ? 'bg-indigo-50 text-indigo-700 font-bold' : 'text-slate-500 hover:text-slate-700'" class="px-4 py-2 text-sm rounded-xl transition-all">Unused</button>
                            <button @click="filter = 'scanned'" :class="filter === 'scanned' ? 'bg-slate-100 text-slate-700 font-bold' : 'text-slate-500 hover:text-slate-700'" class="px-4 py-2 text-sm rounded-xl transition-all">Scanned</button>
                        </div>
                    </div>

                    @if(count($allTickets) > 0)
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            @foreach($allTickets as $item)
                                @php
                                    $event = $item['event'];
                                    $ticket = $item['ticket'];
                                    $category = $item['category'];
                                    $filterType = $ticket->is_scanned ? 'scanned' : 'unused';
                                @endphp
                                <article x-show="filter === 'all' || filter === '{{ $filterType }}'" x-transition.opacity.duration.300ms class="clean-card bg-white border border-slate-200 hover:border-indigo-300 hover:shadow-lg transition-all duration-300 flex flex-col relative overflow-hidden group">
                                    <!-- Ticket Decorative Edge -->
                                    <div class="absolute top-1/2 -left-3 w-6 h-6 bg-slate-50 rounded-full border-r border-slate-200 transform -translate-y-1/2 z-10"></div>
                                    <div class="absolute top-1/2 -right-3 w-6 h-6 bg-slate-50 rounded-full border-l border-slate-200 transform -translate-y-1/2 z-10"></div>
                                    <div class="absolute top-1/2 left-4 right-4 border-t-2 border-dashed border-slate-100 transform -translate-y-1/2"></div>
                                    
                                    <div class="p-6 pb-8 relative z-20">
                                        <div class="flex items-start justify-between gap-3">
                                            <div>
                                                <h3 class="text-xl font-black text-slate-900 group-hover:text-indigo-600 transition-colors">{{ $event->title }}</h3>
                                                <p class="text-sm font-semibold text-slate-500 mt-1">{{ $category->name }}</p>
                                            </div>
                                            @if($ticket->is_scanned)
                                                <span class="shrink-0 rounded-full bg-slate-100 px-2 py-1 text-[10px] font-black uppercase tracking-widest text-slate-500">Scanned</span>
                                            @else
                                                <span class="shrink-0 rounded-full bg-emerald-100 px-2 py-1 text-[10px] font-black uppercase tracking-widest text-emerald-700">Valid</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="p-6 pt-8 mt-auto relative z-20 bg-slate-50/50">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Date & Time</p>
                                                <p class="text-sm font-bold text-slate-800">{{ $event->start_time->format('M d, Y • H:i') }}</p>
                                            </div>
                                            <button 
                                                @click="activeModal = '{{ $ticket->id }}'"
                                                class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white transition-all shadow-sm"
                                                aria-label="View QR Code"
                                            >
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Digital Wallet Ticket Modal -->
                                    <div x-cloak x-show="activeModal === '{{ $ticket->id }}'" 
                                         class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 p-4 backdrop-blur-sm"
                                         x-transition:enter="transition ease-out duration-300"
                                         x-transition:enter-start="opacity-0"
                                         x-transition:enter-end="opacity-100"
                                         x-transition:leave="transition ease-in duration-200"
                                         x-transition:leave-start="opacity-100"
                                         x-transition:leave-end="opacity-0"
                                    >
                                        <div class="relative w-full max-w-sm" @click.outside="activeModal = null">
                                            <!-- Ticket Body -->
                                            <div class="rounded-3xl bg-white shadow-2xl overflow-hidden transform transition-all"
                                                 x-transition:enter="transition ease-out duration-300"
                                                 x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                                                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                                 x-transition:leave="transition ease-in duration-200"
                                                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                                 x-transition:leave-end="opacity-0 translate-y-8 scale-95"
                                            >
                                                <div class="bg-indigo-600 px-6 py-8 text-center text-white relative">
                                                    <button @click="activeModal = null" class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-full bg-white/20 hover:bg-white/30 transition text-white">
                                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                                    </button>
                                                    <p class="text-xs font-black uppercase tracking-[0.2em] text-indigo-200 mb-2">E-Ticket</p>
                                                    <h3 class="text-2xl font-black leading-tight">{{ $event->title }}</h3>
                                                </div>
                                                
                                                <div class="px-6 py-8 bg-white relative">
                                                    <!-- Zigzag top -->
                                                    <div class="absolute -top-2 left-0 right-0 h-4 bg-white" style="mask-image: radial-gradient(circle at 10px 0, transparent 10px, black 11px); mask-size: 20px 20px; mask-repeat: repeat-x; -webkit-mask-image: radial-gradient(circle at 10px 0, transparent 10px, black 11px); -webkit-mask-size: 20px 20px; -webkit-mask-repeat: repeat-x;"></div>
                                                    
                                                    <div class="flex justify-center mb-6">
                                                        <div class="p-3 bg-white border-2 border-slate-100 rounded-2xl shadow-sm">
                                                            <img src="{{ \App\Support\QrCode::svgDataUri($ticket->barcode_string, 200) }}" alt="QR code" class="h-48 w-48" draggable="false">
                                                        </div>
                                                    </div>
                                                    <p class="text-center font-mono text-sm font-black tracking-widest text-slate-800">{{ $ticket->barcode_string }}</p>
                                                    
                                                    <div class="mt-8 grid grid-cols-2 gap-4 border-t border-slate-100 pt-6">
                                                        <div>
                                                            <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Name</p>
                                                            <p class="text-sm font-bold text-slate-900 truncate">{{ Auth::user()->name }}</p>
                                                        </div>
                                                        <div>
                                                            <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Category</p>
                                                            <p class="text-sm font-bold text-slate-900 truncate">{{ $category->name }}</p>
                                                        </div>
                                                        <div class="col-span-2">
                                                            <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Location</p>
                                                            <p class="text-sm font-bold text-slate-900 truncate">{{ $event->location }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @else
                        <div class="clean-card p-12 text-center bg-white border border-slate-200">
                            <div class="mx-auto mb-5 flex h-20 w-20 items-center justify-center rounded-3xl bg-indigo-50 text-indigo-500">
                                <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-black text-slate-900">Dompet Tiket Kosong</h3>
                            <p class="mx-auto mt-3 max-w-md text-slate-500">Anda belum memiliki tiket aktif. Mari temukan event menarik berikutnya!</p>
                            <a href="{{ route('home') }}" class="primary-button mt-7">Cari Event</a>
                        </div>
                    @endif
                </section>

                <!-- Order History Sidebar -->
                <aside>
                    <div class="clean-card border border-slate-200 bg-white">
                        <div class="border-b border-slate-100 px-6 py-5 flex items-center justify-between">
                            <h3 class="text-lg font-black text-slate-900">Recent Orders</h3>
                            <span class="text-xs font-bold text-indigo-600 bg-indigo-50 px-2 py-1 rounded-full">{{ $recentOrders->count() }} total</span>
                        </div>
                        
                        <div class="divide-y divide-slate-100 max-h-[600px] overflow-y-auto">
                            @forelse($recentOrders->take(10) as $order)
                                @php
                                    $statusClass = match($order->payment_status) {
                                        'paid' => 'bg-emerald-50 text-emerald-700',
                                        'pending' => 'bg-amber-50 text-amber-700',
                                        'failed' => 'bg-rose-50 text-rose-700',
                                        default => 'bg-slate-50 text-slate-600',
                                    };
                                @endphp
                                <div class="px-6 py-4 hover:bg-slate-50 transition-colors group">
                                    <div class="flex items-center justify-between mb-2">
                                        <p class="font-mono text-sm font-black text-slate-900">{{ $order->invoice_number }}</p>
                                        <span class="shrink-0 rounded-full px-2 py-0.5 text-[10px] font-black uppercase tracking-wider {{ $statusClass }}">{{ $order->payment_status }}</span>
                                    </div>
                                    <p class="text-xs font-semibold text-slate-500 mb-1">{{ $order->created_at->format('d M Y • H:i') }}</p>
                                    <div class="flex items-end justify-between">
                                        <p class="text-xs font-bold text-slate-400">{{ $order->ticketDetails->count() }} passes</p>
                                        <p class="text-sm font-black text-slate-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            @empty
                                <div class="px-6 py-12 text-center">
                                    <p class="text-sm text-slate-500">Belum ada riwayat transaksi.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</x-app-layout>