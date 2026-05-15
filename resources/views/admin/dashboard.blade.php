<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Super Admin Dashboard</h2>
            <p class="mt-1 text-sm text-gray-500">Platform-wide users, events, and transaction oversight.</p>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <div class="bg-white border border-gray-100 shadow-sm sm:rounded-lg p-6">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Signed in as</p>
                        <p class="mt-1 text-lg font-semibold text-gray-900">{{ Auth::user()->name }}</p>
                        <p class="text-sm text-gray-500">{{ Auth::user()->email }}</p>
                    </div>
                    <span class="inline-flex w-fit items-center rounded-full border border-indigo-200 bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-700">
                        Super Admin
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
                <a href="{{ route('admin.orders.index') }}" class="block rounded-lg border border-gray-100 bg-white p-6 shadow-sm transition hover:border-indigo-200 hover:shadow-md">
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Total Revenue</p>
                    <p class="mt-3 text-2xl font-bold text-gray-900">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</p>
                    <p class="mt-1 text-sm text-gray-500">Paid orders only</p>
                </a>

                <a href="{{ route('admin.users.index') }}" class="block rounded-lg border border-gray-100 bg-white p-6 shadow-sm transition hover:border-indigo-200 hover:shadow-md">
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Total Users</p>
                    <p class="mt-3 text-2xl font-bold text-gray-900">{{ number_format($stats['total_users']) }}</p>
                    <p class="mt-1 text-sm text-gray-500">Registered accounts</p>
                </a>

                <a href="{{ route('admin.events.index') }}" class="block rounded-lg border border-yellow-200 bg-yellow-50 p-6 shadow-sm transition hover:border-yellow-300 hover:shadow-md">
                    <p class="text-xs font-semibold uppercase tracking-wide text-yellow-700">Pending Events</p>
                    <p class="mt-3 text-2xl font-bold text-yellow-900">{{ number_format($stats['total_pending_events']) }}</p>
                    <p class="mt-1 text-sm text-yellow-700">Awaiting approval</p>
                </a>

                <a href="{{ route('admin.events.index') }}" class="block rounded-lg border border-gray-100 bg-white p-6 shadow-sm transition hover:border-indigo-200 hover:shadow-md">
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Total Events</p>
                    <p class="mt-3 text-2xl font-bold text-gray-900">{{ number_format($stats['total_events']) }}</p>
                    <p class="mt-1 text-sm text-gray-500">All statuses</p>
                </a>
            </div>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-4">
                <a href="{{ route('admin.users.index') }}" class="rounded-lg border border-gray-100 bg-white p-5 shadow-sm transition hover:border-indigo-200 hover:shadow-md">
                    <p class="font-semibold text-gray-900">Manage Users</p>
                    <p class="mt-1 text-sm text-gray-500">Review names, emails, roles, and join dates.</p>
                </a>

                <a href="{{ route('admin.events.index') }}" class="rounded-lg border border-gray-100 bg-white p-5 shadow-sm transition hover:border-indigo-200 hover:shadow-md">
                    <p class="font-semibold text-gray-900">Manage Events</p>
                    <p class="mt-1 text-sm text-gray-500">Approve pending organizer submissions.</p>
                </a>

                <a href="{{ route('admin.orders.index') }}" class="rounded-lg border border-gray-100 bg-white p-5 shadow-sm transition hover:border-indigo-200 hover:shadow-md">
                    <p class="font-semibold text-gray-900">Transactions</p>
                    <p class="mt-1 text-sm text-gray-500">Audit global invoice and payment activity.</p>
                </a>

                <a href="{{ route('admin.withdrawals.index') }}" class="rounded-lg border border-emerald-100 bg-emerald-50 p-5 shadow-sm transition hover:border-emerald-200 hover:shadow-md">
                    <p class="font-semibold text-emerald-900">Withdrawals</p>
                    <p class="mt-1 text-sm text-emerald-700">{{ number_format($stats['pending_withdrawals']) }} pending payout request(s).</p>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
