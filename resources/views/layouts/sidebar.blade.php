@php
    $isAdmin = Auth::user()->hasRole('Super Admin');
    $isOrganizer = Auth::user()->hasRole('Event Organizer');
    $isGateScanner = Auth::user()->hasRole('Gate Scanner');

    $roleColor = match(true) {
        $isAdmin       => 'indigo',
        $isOrganizer   => 'emerald',
        $isGateScanner => 'orange',
        default        => 'slate',
    };

    $sidebarBg = 'bg-white border-r border-slate-200';
    $activeBg = match($roleColor) {
        'indigo'  => 'bg-indigo-50 text-indigo-700 border-l-2 border-indigo-600',
        'emerald' => 'bg-emerald-50 text-emerald-700 border-l-2 border-emerald-600',
        'orange'  => 'bg-orange-50 text-orange-700 border-l-2 border-orange-600',
        default   => 'bg-slate-50 text-slate-700 border-l-2 border-slate-600',
    };
    $hoverBg = 'text-slate-600 hover:bg-slate-50 hover:text-slate-900';
    $iconColor = 'text-slate-400';
    $activeIconColor = match($roleColor) {
        'indigo'  => 'text-indigo-600',
        'emerald' => 'text-emerald-600',
        'orange'  => 'text-orange-600',
        default   => 'text-slate-600',
    };
@endphp

<!-- Sidebar -->
<aside class="hidden lg:flex lg:flex-col w-64 {{ $sidebarBg }}">
    <!-- Logo -->
    <div class="h-16 flex items-center gap-3 px-6 border-b border-slate-200">
        <div class="flex h-9 w-9 items-center justify-center rounded-lg {{ $roleColor === 'indigo' ? 'bg-indigo-600' : ($roleColor === 'emerald' ? 'bg-emerald-600' : 'bg-orange-600') }} text-white text-sm font-bold">
            ET
        </div>
        <span class="text-base font-bold text-slate-900">EventTicketing</span>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
        @if($isAdmin)
            <x-sidebar-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')" :color="$roleColor" :active-class="$activeBg" :hover-class="$hoverBg" :icon-color="$iconColor" :active-icon-color="$activeIconColor">
                <x-slot name="icon">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                </x-slot>
                Dashboard
            </x-sidebar-link>

            <div class="pt-4 pb-2">
                <p class="px-3 text-xs font-semibold uppercase tracking-wider text-slate-400">Management</p>
            </div>

            <x-sidebar-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')" :color="$roleColor" :active-class="$activeBg" :hover-class="$hoverBg" :icon-color="$iconColor" :active-icon-color="$activeIconColor">
                <x-slot name="icon">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/></svg>
                </x-slot>
                Users
            </x-sidebar-link>

            <x-sidebar-link :href="route('admin.events.index')" :active="request()->routeIs('admin.events.*')" :color="$roleColor" :active-class="$activeBg" :hover-class="$hoverBg" :icon-color="$iconColor" :active-icon-color="$activeIconColor">
                <x-slot name="icon">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </x-slot>
                Events
            </x-sidebar-link>

            <x-sidebar-link :href="route('admin.orders.index')" :active="request()->routeIs('admin.orders.*')" :color="$roleColor" :active-class="$activeBg" :hover-class="$hoverBg" :icon-color="$iconColor" :active-icon-color="$activeIconColor">
                <x-slot name="icon">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                </x-slot>
                Transactions
            </x-sidebar-link>

            <x-sidebar-link :href="route('admin.withdrawals.index')" :active="request()->routeIs('admin.withdrawals.*')" :color="$roleColor" :active-class="$activeBg" :hover-class="$hoverBg" :icon-color="$iconColor" :active-icon-color="$activeIconColor">
                <x-slot name="icon">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a5 5 0 00-10 0v2M5 9h14l-1 11H6L5 9zm7 4v4"/></svg>
                </x-slot>
                Withdrawals
            </x-sidebar-link>

            <x-sidebar-link :href="route('admin.roles.index')" :active="request()->routeIs('admin.roles.*')" :color="$roleColor" :active-class="$activeBg" :hover-class="$hoverBg" :icon-color="$iconColor" :active-icon-color="$activeIconColor">
                <x-slot name="icon">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </x-slot>
                Roles
            </x-sidebar-link>
        @endif

        @if($isOrganizer)
            <x-sidebar-link :href="route('organizer.dashboard')" :active="request()->routeIs('organizer.dashboard')" :color="$roleColor" :active-class="$activeBg" :hover-class="$hoverBg" :icon-color="$iconColor" :active-icon-color="$activeIconColor">
                <x-slot name="icon">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                </x-slot>
                Dashboard
            </x-sidebar-link>

            <div class="pt-4 pb-2">
                <p class="px-3 text-xs font-semibold uppercase tracking-wider text-slate-400">Management</p>
            </div>

            <x-sidebar-link :href="route('organizer.events.index')" :active="request()->routeIs('organizer.events.*')" :color="$roleColor" :active-class="$activeBg" :hover-class="$hoverBg" :icon-color="$iconColor" :active-icon-color="$activeIconColor">
                <x-slot name="icon">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </x-slot>
                My Events
            </x-sidebar-link>

            <x-sidebar-link :href="route('organizer.withdrawals.index')" :active="request()->routeIs('organizer.withdrawals.*')" :color="$roleColor" :active-class="$activeBg" :hover-class="$hoverBg" :icon-color="$iconColor" :active-icon-color="$activeIconColor">
                <x-slot name="icon">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a5 5 0 00-10 0v2M5 9h14l-1 11H6L5 9zm7 4v4"/></svg>
                </x-slot>
                Withdrawals
            </x-sidebar-link>
        @endif

        @if($isGateScanner)
            <x-sidebar-link :href="route('gate.dashboard')" :active="request()->routeIs('gate.*')" :color="$roleColor" :active-class="$activeBg" :hover-class="$hoverBg" :icon-color="$iconColor" :active-icon-color="$activeIconColor">
                <x-slot name="icon">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                </x-slot>
                Scanner
            </x-sidebar-link>
        @endif
    </nav>

    <!-- User Section -->
    <div class="border-t border-slate-200 p-4">
        <div class="flex items-center gap-3">
            <div class="flex h-9 w-9 items-center justify-center rounded-full bg-slate-200 text-slate-600 text-sm font-bold">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-slate-900 truncate">{{ Auth::user()->name }}</p>
                <p class="text-xs text-slate-500 truncate">{{ Auth::user()->email }}</p>
            </div>
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="p-1 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg>
                </button>
                <div x-show="open" @click.outside="open = false" class="absolute bottom-full left-0 mb-2 w-48 bg-white rounded-lg shadow-lg border border-slate-200 py-1 z-50">
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">Profile</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">Log Out</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</aside>

<!-- Mobile Header -->
<div class="lg:hidden flex items-center justify-between h-16 px-4 bg-white border-b border-slate-200">
    <div class="flex items-center gap-3">
        <div class="flex h-8 w-8 items-center justify-center rounded-lg {{ $roleColor === 'indigo' ? 'bg-indigo-600' : ($roleColor === 'emerald' ? 'bg-emerald-600' : 'bg-orange-600') }} text-white text-xs font-bold">
            ET
        </div>
        <span class="text-base font-bold text-slate-900">EventTicketing</span>
    </div>
    <div class="flex items-center gap-2">
        <span class="text-xs font-medium text-slate-500">{{ Auth::user()->name }}</span>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="p-2 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
            </button>
        </form>
    </div>
</div>