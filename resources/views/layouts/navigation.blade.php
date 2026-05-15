<nav x-data="{ open: false }" class="bg-gradient-to-r from-indigo-700 via-indigo-700 to-purple-700 shadow-lg shadow-indigo-900/10">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-white/15 ring-1 ring-white/25 flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                            </svg>
                        </div>
                        <span class="font-bold text-white text-sm hidden md:block">EventTicketing</span>
                    </a>
                </div>

                <!-- Navigation Links (role-based) -->
                <div class="hidden space-x-1 sm:-my-px sm:ms-8 sm:flex items-center">

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
                        Auth::user()->hasRole('Super Admin')     => ['label' => 'Super Admin',    'class' => 'bg-violet-100 text-violet-700'],
                        Auth::user()->hasRole('Event Organizer') => ['label' => 'Organizer',      'class' => 'bg-emerald-100 text-emerald-700'],
                        Auth::user()->hasRole('Gate Scanner')    => ['label' => 'Gate Scanner',   'class' => 'bg-orange-100 text-orange-700'],
                        default                                  => ['label' => 'Customer',       'class' => 'bg-blue-100 text-blue-700'],
                    };
                @endphp
                <span class="hidden lg:inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $roleConfig['class'] }}">
                    {{ $roleConfig['label'] }}
                </span>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-2 px-3 py-2 border border-white/20 text-sm leading-4 font-medium rounded-lg text-white bg-white/10 hover:bg-white/15 focus:outline-none transition ease-in-out duration-150">
                            <div class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center text-white font-bold text-xs">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <span>{{ Auth::user()->name }}</span>
                            <svg class="fill-current h-4 w-4 text-indigo-100" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-2 border-b border-gray-100">
                            <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                            <p class="text-xs font-semibold mt-0.5 {{ $roleConfig['class'] }} w-fit px-1.5 py-0.5 rounded">{{ $roleConfig['label'] }}</p>
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
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-indigo-100 hover:text-white hover:bg-white/10 focus:outline-none focus:bg-white/10 focus:text-white transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">

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
        <div class="pt-4 pb-1 border-t border-white/15">
            <div class="px-4 flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center text-white font-bold text-sm">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div>
                    <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-indigo-100">{{ Auth::user()->email }}</div>
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
