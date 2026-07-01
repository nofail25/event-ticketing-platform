<nav x-data="{ open: false }" class="dark-page-content sticky top-0 z-50 border-b border-cyan-400/10 bg-slate-950/75 shadow-2xl shadow-cyan-950/20 backdrop-blur-2xl">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex min-h-20 items-center justify-between gap-4">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-2">
                        <div class="relative flex h-11 w-11 items-center justify-center rounded-2xl border border-cyan-300/30 bg-cyan-300/10 shadow-lg shadow-cyan-500/20">
                            <span class="absolute -inset-1 rounded-2xl bg-gradient-to-br from-cyan-300/30 via-fuchsia-400/20 to-lime-300/20 blur-md"></span>
                            <span class="material-symbols-outlined relative text-xl text-cyan-100" style="line-height:1;">local_activity</span>
                        </div>
                        <span class="hidden bg-gradient-to-r from-cyan-200 via-fuchsia-200 to-lime-200 bg-clip-text text-sm font-black text-transparent md:block">Eventmu</span>
                    </a>
                </div>

                <!-- Navigation Links (role-based) -->
                <div class="hidden flex-wrap items-center gap-2 md:ms-8 md:flex">

                    @role('Super Admin')
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                            <span class="material-symbols-outlined text-base me-1.5 inline-block" style="line-height:1;">home</span>
                            Dashboard
                        </x-nav-link>
                        <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                            <span class="material-symbols-outlined text-base me-1.5 inline-block" style="line-height:1;">group</span>
                            Kelola Pengguna
                        </x-nav-link>
                        <x-nav-link :href="route('admin.organizers.index')" :active="request()->routeIs('admin.organizers.*')">
                            <span class="material-symbols-outlined text-base me-1.5 inline-block" style="line-height:1;">verified_user</span>
                            Verifikasi Organizer
                        </x-nav-link>
                        <x-nav-link :href="route('admin.events.index')" :active="request()->routeIs('admin.events.*')">
                            <span class="material-symbols-outlined text-base me-1.5 inline-block" style="line-height:1;">calendar_month</span>
                            Kelola Event
                        </x-nav-link>
                        <x-nav-link :href="route('admin.orders.index')" :active="request()->routeIs('admin.orders.*')">
                            <span class="material-symbols-outlined text-base me-1.5 inline-block" style="line-height:1;">receipt_long</span>
                            Transaksi
                        </x-nav-link>
                        <x-nav-link :href="route('admin.withdrawals.index')" :active="request()->routeIs('admin.withdrawals.*')">
                            <span class="material-symbols-outlined text-base me-1.5 inline-block" style="line-height:1;">account_balance_wallet</span>
                            Penarikan Dana
                        </x-nav-link>
                    @endrole

                    @role('Event Organizer')
                        <x-nav-link :href="route('organizer.events.index')" :active="request()->routeIs('organizer.events.*')">
                            <span class="material-symbols-outlined text-base me-1.5 inline-block" style="line-height:1;">calendar_month</span>
                            Event Saya
                        </x-nav-link>
                        <x-nav-link :href="route('organizer.scanners.index')" :active="request()->routeIs('organizer.scanners.*')">
                            <span class="material-symbols-outlined text-base me-1.5 inline-block" style="line-height:1;">qr_code_scanner</span>
                            Pemindai Tiket
                        </x-nav-link>
                        <x-nav-link :href="route('organizer.withdrawals.index')" :active="request()->routeIs('organizer.withdrawals.*')">
                            <span class="material-symbols-outlined text-base me-1.5 inline-block" style="line-height:1;">account_balance_wallet</span>
                            Penarikan Dana
                        </x-nav-link>
                    @endrole

                    @role('Customer')
                        <x-nav-link :href="route('home')" :active="request()->routeIs('home') || request()->routeIs('events.show') || request()->routeIs('checkout.*')">
                            <span class="material-symbols-outlined text-base me-1.5 inline-block" style="line-height:1;">explore</span>
                            Jelajah
                        </x-nav-link>
                        <x-nav-link :href="route('customer.dashboard')" :active="request()->routeIs('customer.*')">
                            <span class="material-symbols-outlined text-base me-1.5 inline-block" style="line-height:1;">local_activity</span>
                            Tiket Saya
                        </x-nav-link>
                    @endrole

                    @role('Gate Scanner')
                        <x-nav-link :href="route('gate.dashboard')" :active="request()->routeIs('gate.*')">
                            <span class="material-symbols-outlined text-base me-1.5 inline-block" style="line-height:1;">qr_code_scanner</span>
                            Scanner
                        </x-nav-link>
                    @endrole

                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-3">

                {{-- Role Badge --}}
                @php
                    $roleConfig = match(true) {
                        Auth::user()->hasRole('Super Admin')     => ['label' => 'Super Admin',    'class' => 'border-violet-300/30 bg-violet-400/10 text-violet-100'],
                        Auth::user()->hasRole('Event Organizer') => ['label' => 'Organizer',      'class' => 'border-emerald-300/30 bg-emerald-400/10 text-emerald-100'],
                        Auth::user()->hasRole('Gate Scanner')    => ['label' => 'Gate Scanner',   'class' => 'border-orange-300/30 bg-orange-400/10 text-orange-100'],
                        default                                  => ['label' => 'Customer',       'class' => 'border-cyan-300/30 bg-cyan-400/10 text-cyan-100'],
                    };
                @endphp
                <span class="hidden lg:inline-flex items-center px-3 py-1.5 rounded-full border text-xs font-black uppercase tracking-wider {{ $roleConfig['class'] }}">
                    {{ $roleConfig['label'] }}
                </span>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button type="button" class="inline-flex items-center gap-2 rounded-2xl border border-white/10 bg-white/5 px-3 py-2 text-sm font-bold leading-4 text-white transition hover:border-fuchsia-300/30 hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-cyan-300/50">
                            <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-gradient-to-br from-cyan-300 to-fuchsia-400 text-xs font-black text-slate-950 shadow-lg shadow-fuchsia-500/20">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <span>{{ Auth::user()->name }}</span>
                            <span class="material-symbols-outlined fill-current text-base text-slate-400" style="line-height:1;">expand_more</span>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-3 border-b border-white/10">
                            <p class="text-sm font-bold text-white">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-slate-400">{{ Auth::user()->email }}</p>
                            <p class="text-xs font-semibold mt-2 {{ $roleConfig['class'] }} w-fit px-2 py-1 rounded-full border">{{ $roleConfig['label'] }}</p>
                        </div>
                        <x-dropdown-link :href="route('profile.edit')">
                            <span class="material-symbols-outlined text-base me-2 inline-block text-gray-400" style="line-height:1;">person</span>
                            Profil
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                <span class="material-symbols-outlined text-base me-2 inline-block text-gray-400" style="line-height:1;">logout</span>
                                Keluar
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button type="button" @click="open = ! open" class="inline-flex items-center justify-center rounded-2xl border border-white/10 bg-white/5 p-2.5 text-slate-200 transition hover:border-cyan-300/40 hover:bg-cyan-300/10 hover:text-cyan-100 focus:outline-none focus:ring-2 focus:ring-cyan-300/50">
                    <span class="material-symbols-outlined text-2xl" style="line-height:1;">menu</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden border-t border-white/10 bg-slate-950/95 px-4 pb-5 pt-3 backdrop-blur-2xl sm:hidden">
        <div class="space-y-2">

            @role('Super Admin')
                <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                    Dashboard
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                    Kelola Pengguna
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.organizers.index')" :active="request()->routeIs('admin.organizers.*')">
                    Verifikasi Organizer
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.events.index')" :active="request()->routeIs('admin.events.*')">
                    Kelola Event
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.orders.index')" :active="request()->routeIs('admin.orders.*')">
                    Transaksi
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.withdrawals.index')" :active="request()->routeIs('admin.withdrawals.*')">
                    Penarikan Dana
                </x-responsive-nav-link>
            @endrole

            @role('Event Organizer')
                <x-responsive-nav-link :href="route('organizer.events.index')" :active="request()->routeIs('organizer.events.*')">
                    Event Saya
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('organizer.scanners.index')" :active="request()->routeIs('organizer.scanners.*')">
                    Pemindai Tiket
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('organizer.withdrawals.index')" :active="request()->routeIs('organizer.withdrawals.*')">
                    Penarikan Dana
                </x-responsive-nav-link>
            @endrole

            @role('Customer')
                <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home') || request()->routeIs('events.show') || request()->routeIs('checkout.*')">
                    Jelajah Event
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('customer.dashboard')" :active="request()->routeIs('customer.*')">
                    Tiket Saya
                </x-responsive-nav-link>
            @endrole

            @role('Gate Scanner')
                <x-responsive-nav-link :href="route('gate.dashboard')" :active="request()->routeIs('gate.*')">
                    Scanner
                </x-responsive-nav-link>
            @endrole

        </div>

        <!-- Responsive Settings Options -->
        <div class="mt-4 rounded-3xl border border-white/10 bg-white/5 p-4">
            <div class="px-4 flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-cyan-300 to-fuchsia-400 flex items-center justify-center text-slate-950 font-black text-sm">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div>
                    <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-slate-400">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    Profil
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                        Keluar
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
