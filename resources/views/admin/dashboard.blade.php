<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Admin Dashboard</h2>
            <p class="mt-1 text-sm text-slate-500">Platform-wide oversight for users, events, and transactions.</p>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Welcome Card -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500">Signed in as</p>
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
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total Revenue</p>
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <p class="text-2xl font-bold text-slate-900">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</p>
                <p class="mt-1 text-xs text-slate-500">Paid orders only</p>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                <div class="flex items-center justify-between mb-1">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total Users</p>
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/></svg>
                </div>
                <p class="text-2xl font-bold text-slate-900">{{ number_format($stats['total_users']) }}</p>
                <p class="mt-1 text-xs text-slate-500">Registered accounts</p>
            </div>

            <div class="bg-white rounded-xl border border-amber-200 shadow-sm p-6">
                <div class="flex items-center justify-between mb-1">
                    <p class="text-xs font-semibold uppercase tracking-wider text-amber-700">Pending Events</p>
                    <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <p class="text-2xl font-bold text-amber-900">{{ number_format($stats['total_pending_events']) }}</p>
                <p class="mt-1 text-xs text-amber-600">Awaiting approval</p>
            </div>

            <a href="{{ route('admin.events.index') }}" class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 hover:border-indigo-200 transition-colors">
                <div class="flex items-center justify-between mb-1">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total Events</p>
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <p class="text-2xl font-bold text-slate-900">{{ number_format($stats['total_events']) }}</p>
                <p class="mt-1 text-xs text-slate-500">All statuses</p>
            </a>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-4">
            <a href="{{ route('admin.users.index') }}" class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 hover:border-indigo-200 transition-colors">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-lg bg-indigo-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/></svg>
                    </div>
                    <div>
                        <p class="font-semibold text-slate-900">Manage Users</p>
                        <p class="text-xs text-slate-500">Review names, emails, roles</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.events.index') }}" class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 hover:border-indigo-200 transition-colors">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-lg bg-indigo-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <p class="font-semibold text-slate-900">Manage Events</p>
                        <p class="text-xs text-slate-500">Approve pending submissions</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.orders.index') }}" class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 hover:border-indigo-200 transition-colors">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-lg bg-indigo-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    </div>
                    <div>
                        <p class="font-semibold text-slate-900">Transactions</p>
                        <p class="text-xs text-slate-500">Audit invoice & payment activity</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.withdrawals.index') }}" class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 hover:border-emerald-200 transition-colors">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-lg bg-emerald-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a5 5 0 00-10 0v2M5 9h14l-1 11H6L5 9zm7 4v4"/></svg>
                    </div>
                    <div>
                        <p class="font-semibold text-slate-900">Withdrawals</p>
                        <p class="text-xs text-emerald-700">{{ number_format($stats['pending_withdrawals']) }} pending payout(s)</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</x-app-layout>