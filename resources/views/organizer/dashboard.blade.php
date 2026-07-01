<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="flex-shrink-0 w-9 h-9 rounded-lg bg-emerald-600 flex items-center justify-center">
                <span class="material-symbols-outlined text-xl text-white" style="line-height:1;">calendar_month</span>
            </div>
            <div>
                <h2 class="text-xl font-bold text-slate-900">Dashboard Penyelenggara</h2>
                <p class="text-xs text-slate-500 font-medium">Kelola event dan kategori tiket Anda</p>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Welcome Banner -->
        <div class="bg-emerald-600 rounded-xl p-6 text-white shadow-sm">
            <p class="text-emerald-100 text-sm font-medium">Selamat datang kembali,</p>
            <h1 class="text-2xl font-bold mt-1">{{ Auth::user()->name }}</h1>
            <p class="text-emerald-100 text-sm mt-1">Kelola event Anda dan kembangkan audiens Anda.</p>
        </div>

        <!-- Verification Banner -->
        @if(!$profile || $profile->verification_status !== 'verified')
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded shadow-sm" role="alert">
                <div class="flex items-start">
                    <div class="py-1"><svg class="fill-current h-6 w-6 text-yellow-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/></svg></div>
                    <div>
                        <p class="font-bold">Aksi Diperlukan: Verifikasi Profil Organizer</p>
                        <p class="text-sm mt-1">
                            @if(!$profile || $profile->verification_status === 'unverified')
                                Anda harus melengkapi profil Organizer dan data Rekening Bank sebelum dapat membuat Event.
                            @elseif($profile->verification_status === 'pending')
                                Profil Anda sedang ditinjau oleh Admin. Anda akan dapat membuat Event setelah profil disetujui.
                            @elseif($profile->verification_status === 'rejected')
                                Profil Anda ditolak oleh Admin. Silakan perbarui data Anda.
                            @endif
                        </p>
                        <div class="mt-3">
                            <a href="{{ route('profile.edit') }}" class="inline-block bg-yellow-500 hover:bg-yellow-600 text-white text-xs font-bold py-2 px-4 rounded transition-colors">
                                Kelola Profil Organizer
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Event Saya</p>
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                </div>
                <p class="text-3xl font-bold text-slate-900">{{ $stats['total_events'] }}</p>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Aktif</p>
                    <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                </div>
                <p class="text-3xl font-bold text-slate-900">{{ $stats['active_events'] }}</p>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Menunggu Ulasan</p>
                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                </div>
                <p class="text-3xl font-bold text-slate-900">{{ $stats['pending_events'] }}</p>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Draf</p>
                    <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                </div>
                <p class="text-3xl font-bold text-slate-900">{{ $stats['draft_events'] }}</p>
            </div>
        </div>

        @if (session('status'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
                {{ session('status') }}
            </div>
        @endif

        <!-- Wallet & Recent Withdrawals -->
        <div class="grid grid-cols-1 gap-5 lg:grid-cols-3">
            <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Dompet Saya</p>
                        <p class="mt-2 text-3xl font-bold text-slate-900">Rp {{ number_format($wallet['current_balance'], 0, ',', '.') }}</p>
                        <p class="mt-1 text-sm text-slate-500">Saldo saat ini setelah potongan biaya platform {{ $wallet['platform_fee_percentage'] }}%.</p>
                    </div>
                    <div class="flex gap-2">
                        <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'request-withdrawal')" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white text-sm font-semibold rounded-lg hover:bg-emerald-700 transition focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                            Tarik Dana
                        </button>
                        <a href="{{ route('organizer.withdrawals.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-slate-700 border border-slate-300 rounded-lg hover:bg-slate-50 transition">
                            Riwayat
                        </a>
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-1 gap-3 sm:grid-cols-3">
                    <div class="rounded-lg bg-slate-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Penjualan Kotor</p>
                        <p class="mt-2 font-bold text-slate-900">Rp {{ number_format($wallet['gross_revenue'], 0, ',', '.') }}</p>
                    </div>
                    <div class="rounded-lg bg-slate-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Biaya Platform</p>
                        <p class="mt-2 font-bold text-slate-900">Rp {{ number_format($wallet['platform_fee_amount'], 0, ',', '.') }}</p>
                    </div>
                    <div class="rounded-lg bg-slate-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Dipesan</p>
                        <p class="mt-2 font-bold text-slate-900">Rp {{ number_format($wallet['reserved_withdrawals'], 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="font-semibold text-slate-900">Penarikan Terakhir</h3>
                    <a href="{{ route('organizer.withdrawals.index') }}" class="text-xs font-semibold text-emerald-600 hover:text-emerald-800">Lihat semua</a>
                </div>
                @forelse($recentWithdrawals as $withdrawal)
                    @php
                        $statusClass = match($withdrawal->status) {
                            'completed' => 'bg-emerald-50 text-emerald-700',
                            'rejected' => 'bg-red-50 text-red-700',
                            default => 'bg-amber-50 text-amber-700',
                        };
                    @endphp
                    <div class="px-6 py-4 border-b border-slate-50 last:border-0">
                        <div class="flex items-center justify-between gap-3">
                            <p class="font-semibold text-slate-900">Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}</p>
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $statusClass }} capitalize">{{ $withdrawal->status }}</span>
                        </div>
                        <p class="mt-1 text-xs text-slate-500">{{ $withdrawal->created_at->format('d M Y, H:i') }}</p>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-slate-400 text-sm">Belum ada permintaan penarikan dana.</div>
                @endforelse
            </div>
        </div>

        <!-- Recent Events -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="font-semibold text-slate-900">Event Terakhir</h3>
                <span class="text-xs text-slate-400">5 Terbaru</span>
            </div>
            @forelse($recentEvents as $event)
            <div class="px-6 py-4 flex items-center justify-between border-b border-slate-50 last:border-0 hover:bg-slate-50 transition-colors">
                <div>
                    <p class="font-medium text-slate-900">{{ $event->title }}</p>
                    <p class="text-xs text-slate-500 mt-0.5">{{ $event->location }} · {{ $event->start_time->format('d M Y') }}</p>
                </div>
                @php
                    $badge = match($event->status) {
                        'active'    => 'bg-emerald-50 text-emerald-700',
                        'pending'   => 'bg-amber-50 text-amber-700',
                        'draft'     => 'bg-slate-100 text-slate-600',
                        'completed' => 'bg-blue-50 text-blue-700',
                        default     => 'bg-slate-100 text-slate-600'
                    };
                @endphp
                <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $badge }} capitalize">{{ $event->status }}</span>
            </div>
            @empty
            <div class="px-6 py-8 text-center text-slate-400 text-sm">Belum ada event. Buat event pertama Anda!</div>
            @endforelse
        </div>

        <!-- Role Badge -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 flex items-center justify-between">
            <div>
                <p class="text-sm text-slate-500">Masuk sebagai</p>
                <p class="font-semibold text-slate-900 mt-0.5">{{ Auth::user()->email }}</p>
            </div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 inline-block"></span>
                Penyelenggara Event
            </span>
        </div>
    </div>

    <x-modal name="request-withdrawal" :show="$errors->any()" focusable>
        <div class="p-6">
            <h3 class="text-lg font-semibold text-slate-900">Permintaan Penarikan Dana</h3>
            <p class="mt-1 text-sm text-slate-500">Kirim detail bank Anda untuk peninjauan pencairan manual.</p>
            <div class="mt-6">
                @include('organizer.withdrawals._request-form', ['wallet' => $wallet, 'profile' => $profile])
            </div>
        </div>
    </x-modal>
</x-app-layout>