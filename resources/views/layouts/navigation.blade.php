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
                            <svg class="relative h-5 w-5 text-cyan-100" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                            </svg>
                        </div>
                        <span class="hidden bg-gradient-to-r from-cyan-200 via-fuchsia-200 to-lime-200 bg-clip-text text-sm font-black text-transparent md:block">EventTicketing</span>
                    </a>
                </div>

                <!-- Navigation Links (role-based) -->
                <div class="hidden flex-wrap items-center gap-2 md:ms-8 md:flex">

                    @role('Super Admin')
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                            <svg class="w-4 h-4 me-1.5 inline-block" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            Dashboard
                        </x-nav-link>
                        <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                            <svg class="w-4 h-4 me-1.5 inline-block" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-4.13a4 4 0 11-8 0 4 4 0 018 0zm6 2a3 3 0 100-6 3 3 0 000 6z"/></svg>
                            Manage Users
                        </x-nav-link>
                        <x-nav-link :href="route('admin.events.index')" :active="request()->routeIs('admin.events.*')">
                            <svg class="w-4 h-4 me-1.5 inline-block" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Manage Events
                        </x-nav-link>
                        <x-nav-link :href="route('admin.orders.index')" :active="request()->routeIs('admin.orders.*')">
                            <svg class="w-4 h-4 me-1.5 inline-block" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 14h6m-6-4h6M5 6h14M7 6v14l5-2 5 2V6"/></svg>
                            Transactions
                        </x-nav-link>
                        <x-nav-link :href="route('admin.withdrawals.index')" :active="request()->routeIs('admin.withdrawals.*')">
                            <svg class="w-4 h-4 me-1.5 inline-block" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a5 5 0 00-10 0v2M5 9h14l-1 11H6L5 9zm7 4v4"/></svg>
                            Withdrawals
                        </x-nav-link>
                    @endrole

                    @role('Event Organizer')
                        <x-nav-link :href="route('organizer.events.index')" :active="request()->routeIs('organizer.events.*')">
                            <svg class="w-4 h-4 me-1.5 inline-block" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            My Events
                        </x-nav-link>
                        <x-nav-link :href="route('organizer.withdrawals.index')" :active="request()->routeIs('organizer.withdrawals.*')">
                            <svg class="w-4 h-4 me-1.5 inline-block" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a5 5 0 00-10 0v2M5 9h14l-1 11H6L5 9zm7 4v4"/></svg>
                            Withdrawals
                        </x-nav-link>
                    @endrole

                    @role('Customer')
                        <x-nav-link :href="route('home')" :active="request()->routeIs('home') || request()->routeIs('events.show') || request()->routeIs('checkout.*')">
                            <svg class="w-4 h-4 me-1.5 inline-block" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197M16.5 10.5a6 6 0 11-12 0 6 6 0 0112 0z"/></svg>
                            Explore
                        </x-nav-link>
                        <x-nav-link :href="route('customer.dashboard')" :active="request()->routeIs('customer.*')">
                            <svg class="w-4 h-4 me-1.5 inline-block" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                            My Tickets
                        </x-nav-link>
                    @endrole

                    @role('Gate Scanner')
                        <x-nav-link :href="route('gate.dashboard')" :active="request()->routeIs('gate.*')">
                            <svg class="w-4 h-4 me-1.5 inline-block" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
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
                        <button class="inline-flex items-center gap-2 rounded-2xl border border-white/10 bg-white/5 px-3 py-2 text-sm font-bold leading-4 text-white transition hover:border-fuchsia-300/30 hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-cyan-300/50">
                            <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-gradient-to-br from-cyan-300 to-fuchsia-400 text-xs font-black text-slate-950 shadow-lg shadow-fuchsia-500/20">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <span>{{ Auth::user()->name }}</span>
                            <svg class="fill-current h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-3 border-b border-white/10">
                            <p class="text-sm font-bold text-white">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-slate-400">{{ Auth::user()->email }}</p>
                            <p class="text-xs font-semibold mt-2 {{ $roleConfig['class'] }} w-fit px-2 py-1 rounded-full border">{{ $roleConfig['label'] }}</p>
                        </div>
                        <x-dropdown-link :href="route('profile.edit')">
                            <svg class="w-4 h-4 me-2 inline-block text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                <svg class="w-4 h-4 me-2 inline-block text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center rounded-2xl border border-white/10 bg-white/5 p-2.5 text-slate-200 transition hover:border-cyan-300/40 hover:bg-cyan-300/10 hover:text-cyan-100 focus:outline-none focus:ring-2 focus:ring-cyan-300/50">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
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
                    Manage Users
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.events.index')" :active="request()->routeIs('admin.events.*')">
                    Manage Events
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.orders.index')" :active="request()->routeIs('admin.orders.*')">
                    Transactions
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.withdrawals.index')" :active="request()->routeIs('admin.withdrawals.*')">
                    Withdrawals
                </x-responsive-nav-link>
            @endrole

            @role('Event Organizer')
                <x-responsive-nav-link :href="route('organizer.events.index')" :active="request()->routeIs('organizer.events.*')">
                    My Events
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('organizer.withdrawals.index')" :active="request()->routeIs('organizer.withdrawals.*')">
                    Withdrawals
                </x-responsive-nav-link>
            @endrole

            @role('Customer')
                <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home') || request()->routeIs('events.show') || request()->routeIs('checkout.*')">
                    Explore Events
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('customer.dashboard')" :active="request()->routeIs('customer.*')">
                    My Tickets
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
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
