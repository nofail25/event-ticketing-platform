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
        <div class="flex h-9 w-9 items-center justify-center rounded-lg {{ $roleColor === 'indigo' ? 'bg-indigo-600' : ($roleColor === 'emerald' ? 'bg-emerald-600' : 'bg-orange-600') }} text-white text-sm font-bold shadow-sm">
            <span class="material-symbols-outlined text-[20px]" style="line-height:1;">local_activity</span>
        </div>
        <span class="text-base font-bold text-slate-900">Eventmu</span>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
        @if($isAdmin)
            <x-sidebar-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')" :color="$roleColor" :active-class="$activeBg" :hover-class="$hoverBg" :icon-color="$iconColor" :active-icon-color="$activeIconColor">
                <x-slot name="icon">
                    <span class="material-symbols-outlined text-xl" style="line-height:1;">home</span>
                </x-slot>
                Dashboard
            </x-sidebar-link>

            <div class="pt-4 pb-2">
                <p class="px-3 text-xs font-semibold uppercase tracking-wider text-slate-400">Manajemen</p>
            </div>

            <x-sidebar-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')" :color="$roleColor" :active-class="$activeBg" :hover-class="$hoverBg" :icon-color="$iconColor" :active-icon-color="$activeIconColor">
                <x-slot name="icon">
                    <span class="material-symbols-outlined text-xl" style="line-height:1;">group</span>
                </x-slot>
                Pengguna
            </x-sidebar-link>

            <x-sidebar-link :href="route('admin.organizers.index')" :active="request()->routeIs('admin.organizers.*')" :color="$roleColor" :active-class="$activeBg" :hover-class="$hoverBg" :icon-color="$iconColor" :active-icon-color="$activeIconColor">
                <x-slot name="icon">
                    <span class="material-symbols-outlined text-xl" style="line-height:1;">verified_user</span>
                </x-slot>
                Organizer
            </x-sidebar-link>

            <x-sidebar-link :href="route('admin.events.index')" :active="request()->routeIs('admin.events.*')" :color="$roleColor" :active-class="$activeBg" :hover-class="$hoverBg" :icon-color="$iconColor" :active-icon-color="$activeIconColor">
                <x-slot name="icon">
                    <span class="material-symbols-outlined text-xl" style="line-height:1;">calendar_month</span>
                </x-slot>
                Event
            </x-sidebar-link>

            <x-sidebar-link :href="route('admin.orders.index')" :active="request()->routeIs('admin.orders.*')" :color="$roleColor" :active-class="$activeBg" :hover-class="$hoverBg" :icon-color="$iconColor" :active-icon-color="$activeIconColor">
                <x-slot name="icon">
                    <span class="material-symbols-outlined text-xl" style="line-height:1;">receipt_long</span>
                </x-slot>
                Transaksi
            </x-sidebar-link>

            <x-sidebar-link :href="route('admin.withdrawals.index')" :active="request()->routeIs('admin.withdrawals.*')" :color="$roleColor" :active-class="$activeBg" :hover-class="$hoverBg" :icon-color="$iconColor" :active-icon-color="$activeIconColor">
                <x-slot name="icon">
                    <span class="material-symbols-outlined text-xl" style="line-height:1;">account_balance_wallet</span>
                </x-slot>
                Penarikan Dana
            </x-sidebar-link>

            <x-sidebar-link :href="route('admin.roles.index')" :active="request()->routeIs('admin.roles.*')" :color="$roleColor" :active-class="$activeBg" :hover-class="$hoverBg" :icon-color="$iconColor" :active-icon-color="$activeIconColor">
                <x-slot name="icon">
                    <span class="material-symbols-outlined text-xl" style="line-height:1;">lock</span>
                </x-slot>
                Peran
            </x-sidebar-link>
        @endif

        @if($isOrganizer)
            <x-sidebar-link :href="route('organizer.dashboard')" :active="request()->routeIs('organizer.dashboard')" :color="$roleColor" :active-class="$activeBg" :hover-class="$hoverBg" :icon-color="$iconColor" :active-icon-color="$activeIconColor">
                <x-slot name="icon">
                    <span class="material-symbols-outlined text-xl" style="line-height:1;">home</span>
                </x-slot>
                Dashboard
            </x-sidebar-link>

            <div class="pt-4 pb-2">
                <p class="px-3 text-xs font-semibold uppercase tracking-wider text-slate-400">Manajemen</p>
            </div>

            <x-sidebar-link :href="route('organizer.events.index')" :active="request()->routeIs('organizer.events.*')" :color="$roleColor" :active-class="$activeBg" :hover-class="$hoverBg" :icon-color="$iconColor" :active-icon-color="$activeIconColor">
                <x-slot name="icon">
                    <span class="material-symbols-outlined text-xl" style="line-height:1;">calendar_month</span>
                </x-slot>
                Event Saya
            </x-sidebar-link>

            <x-sidebar-link :href="route('organizer.scanners.index')" :active="request()->routeIs('organizer.scanners.*')" :color="$roleColor" :active-class="$activeBg" :hover-class="$hoverBg" :icon-color="$iconColor" :active-icon-color="$activeIconColor">
                <x-slot name="icon">
                    <span class="material-symbols-outlined text-xl" style="line-height:1;">qr_code_scanner</span>
                </x-slot>
                Pemindai Tiket
            </x-sidebar-link>

            <x-sidebar-link :href="route('organizer.withdrawals.index')" :active="request()->routeIs('organizer.withdrawals.*')" :color="$roleColor" :active-class="$activeBg" :hover-class="$hoverBg" :icon-color="$iconColor" :active-icon-color="$activeIconColor">
                <x-slot name="icon">
                    <span class="material-symbols-outlined text-xl" style="line-height:1;">account_balance_wallet</span>
                </x-slot>
                Penarikan Dana
            </x-sidebar-link>
        @endif

        @if($isGateScanner)
            <x-sidebar-link :href="route('gate.dashboard')" :active="request()->routeIs('gate.*')" :color="$roleColor" :active-class="$activeBg" :hover-class="$hoverBg" :icon-color="$iconColor" :active-icon-color="$activeIconColor">
                <x-slot name="icon">
                    <span class="material-symbols-outlined text-xl" style="line-height:1;">qr_code_scanner</span>
                </x-slot>
                Pemindai
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
                    <span class="material-symbols-outlined text-xl" style="line-height:1;">more_vert</span>
                </button>
                <div x-show="open" @click.outside="open = false" class="absolute bottom-full left-0 mb-2 w-48 bg-white rounded-lg shadow-lg border border-slate-200 py-1 z-50">
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">Profil</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">Keluar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</aside>

<!-- Mobile Header -->
<div class="lg:hidden flex items-center justify-between h-16 w-full shrink-0 px-4 bg-white border-b border-slate-200">
    <div class="flex items-center gap-3">
        <div class="flex h-8 w-8 items-center justify-center rounded-lg {{ $roleColor === 'indigo' ? 'bg-indigo-600' : ($roleColor === 'emerald' ? 'bg-emerald-600' : 'bg-orange-600') }} text-white text-xs font-bold shadow-sm">
            <span class="material-symbols-outlined text-[16px]" style="line-height:1;">local_activity</span>
        </div>
        <span class="text-base font-bold text-slate-900">Eventmu</span>
    </div>
    <div class="flex items-center gap-2">
        <span class="text-xs font-medium text-slate-500">{{ Auth::user()->name }}</span>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="p-2 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition">
                <span class="material-symbols-outlined text-xl" style="line-height:1;">logout</span>
            </button>
        </form>
    </div>
</div>