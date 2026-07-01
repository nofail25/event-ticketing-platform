<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Admin Dashboard</h2>
            <p class="mt-1 text-sm text-slate-500">Pengawasan di seluruh platform untuk pengguna, event, dan transaksi.</p>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Welcome Card -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500">Masuk sebagai</p>
                    <p class="mt-1 text-lg font-semibold text-slate-900">{{ Auth::user()->name }}</p>
                    <p class="text-sm text-slate-500">{{ Auth::user()->email }}</p>
                </div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 inline-block"></span>
                    Super Admin
                </span>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                <div class="flex items-center justify-between mb-1">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total Pendapatan</p>
                    <span class="material-symbols-outlined text-base text-slate-400" style="line-height:1;">payments</span>
                </div>
                <p class="text-2xl font-bold text-slate-900">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</p>
                <p class="mt-1 text-xs text-slate-500">Hanya pesanan yang dibayar</p>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                <div class="flex items-center justify-between mb-1">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total Pengguna</p>
                    <span class="material-symbols-outlined text-base text-slate-400" style="line-height:1;">group</span>
                </div>
                <p class="text-2xl font-bold text-slate-900">{{ number_format($stats['total_users']) }}</p>
                <p class="mt-1 text-xs text-slate-500">Akun terdaftar</p>
            </div>

            <div class="bg-white rounded-xl border border-amber-200 shadow-sm p-6">
                <div class="flex items-center justify-between mb-1">
                    <p class="text-xs font-semibold uppercase tracking-wider text-amber-700">Event Tertunda</p>
                    <span class="material-symbols-outlined text-base text-amber-500" style="line-height:1;">pending_actions</span>
                </div>
                <p class="text-2xl font-bold text-amber-900">{{ number_format($stats['total_pending_events']) }}</p>
                <p class="mt-1 text-xs text-amber-600">Menunggu persetujuan</p>
            </div>

            <a href="{{ route('admin.events.index') }}" class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 hover:border-indigo-200 transition-colors">
                <div class="flex items-center justify-between mb-1">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total Event</p>
                    <span class="material-symbols-outlined text-base text-slate-400" style="line-height:1;">calendar_month</span>
                </div>
                <p class="text-2xl font-bold text-slate-900">{{ number_format($stats['total_events']) }}</p>
                <p class="mt-1 text-xs text-slate-500">Semua status</p>
            </a>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-4">
            <a href="{{ route('admin.users.index') }}" class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 hover:border-indigo-200 transition-colors">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-lg bg-indigo-50 flex items-center justify-center">
                        <span class="material-symbols-outlined text-xl text-indigo-600" style="line-height:1;">manage_accounts</span>
                    </div>
                    <div>
                        <p class="font-semibold text-slate-900">Kelola Pengguna</p>
                        <p class="text-xs text-slate-500">Tinjau nama, email, peran</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.events.index') }}" class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 hover:border-indigo-200 transition-colors">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-lg bg-indigo-50 flex items-center justify-center">
                        <span class="material-symbols-outlined text-xl text-indigo-600" style="line-height:1;">calendar_month</span>
                    </div>
                    <div>
                        <p class="font-semibold text-slate-900">Kelola Event</p>
                        <p class="text-xs text-slate-500">Setujui pengiriman yang tertunda</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.orders.index') }}" class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 hover:border-indigo-200 transition-colors">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-lg bg-indigo-50 flex items-center justify-center">
                        <span class="material-symbols-outlined text-xl text-indigo-600" style="line-height:1;">receipt_long</span>
                    </div>
                    <div>
                        <p class="font-semibold text-slate-900">Transaksi</p>
                        <p class="text-xs text-slate-500">Audit tagihan & aktivitas pembayaran</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.withdrawals.index') }}" class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 hover:border-emerald-200 transition-colors">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-lg bg-emerald-50 flex items-center justify-center">
                        <span class="material-symbols-outlined text-xl text-emerald-600" style="line-height:1;">account_balance_wallet</span>
                    </div>
                    <div>
                        <p class="font-semibold text-slate-900">Penarikan Dana</p>
                        <p class="text-xs text-emerald-700">{{ number_format($stats['pending_withdrawals']) }} pembayaran tertunda</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</x-app-layout>