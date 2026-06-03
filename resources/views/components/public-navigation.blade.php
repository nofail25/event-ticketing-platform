@props(['backHref' => null, 'backLabel' => null])

@php
    $authUser = Auth::user();
    $accountRoute = $authUser ? ($authUser->hasRole('Customer') ? route('customer.dashboard') : route('dashboard')) : null;
    $accountLabel = $authUser ? ($authUser->hasRole('Customer') ? 'My Tickets' : 'Dashboard') : null;
@endphp

<nav x-data="{ open: false }" class="sticky top-0 z-50 border-b border-slate-200 bg-white/80 shadow-sm backdrop-blur-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex min-h-20 items-center justify-between gap-4">
            <div class="flex min-w-0 items-center gap-5">
                <a href="{{ route('home') }}" class="group flex shrink-0 items-center gap-3">
                    <div class="relative flex h-11 w-11 items-center justify-center rounded-2xl bg-indigo-600 shadow-sm transition group-hover:scale-105 group-hover:bg-indigo-700">
                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                        </svg>
                    </div>
                    <div class="hidden sm:block leading-tight">
                        <p class="text-base font-black tracking-tight text-slate-900">EventTicketing</p>
                        <p class="text-[11px] font-semibold uppercase tracking-[0.24em] text-indigo-500">Modern Pass</p>
                    </div>
                </a>

                <div class="hidden items-center gap-2 lg:flex">
                    <a href="{{ route('home') }}" class="rounded-full px-4 py-2 text-sm font-bold transition {{ request()->routeIs('home') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">Explore Events</a>
                    @if($backHref)
                        <a href="{{ $backHref }}" class="rounded-full px-4 py-2 text-sm font-bold text-slate-600 transition hover:bg-slate-100 hover:text-slate-900">{{ $backLabel ?? 'Back' }}</a>
                    @endif
                    @auth
                        <a href="{{ $accountRoute }}" class="rounded-full px-4 py-2 text-sm font-bold transition {{ request()->routeIs('customer.*') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">{{ $accountLabel }}</a>
                    @endauth
                </div>
            </div>

            <div class="hidden items-center gap-3 sm:flex">
                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm font-bold leading-4 text-slate-700 transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-indigo-100 text-xs font-black text-indigo-700">
                                    {{ strtoupper(substr($authUser->name, 0, 1)) }}
                                </span>
                                <span class="hidden max-w-28 truncate md:inline">{{ $authUser->name }}</span>
                                <svg class="h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="border-b border-slate-100 px-4 py-3">
                                <p class="truncate text-sm font-bold text-slate-900">{{ $authUser->name }}</p>
                                <p class="truncate text-xs text-slate-500">{{ $authUser->email }}</p>
                            </div>
                            <x-dropdown-link :href="$accountRoute">{{ $accountLabel }}</x-dropdown-link>
                            <x-dropdown-link :href="route('profile.edit')">Profile</x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="text-red-600 hover:bg-red-50 hover:text-red-700">Log Out</x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <a href="{{ route('login') }}" class="rounded-full px-4 py-2 text-sm font-bold text-slate-600 transition hover:bg-slate-100 hover:text-slate-900">Login</a>
                    <a href="{{ route('register') }}" class="primary-button px-4 py-2 text-xs">Register</a>
                @endauth
            </div>

            <button @click="open = ! open" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white p-2.5 text-slate-500 transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 sm:hidden" aria-label="Toggle navigation">
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <div x-cloak x-show="open" x-transition class="border-t border-slate-200 bg-white px-4 pb-5 pt-3 sm:hidden">
        <div class="space-y-2">
            <a href="{{ route('home') }}" class="block rounded-2xl px-4 py-3 text-sm font-bold {{ request()->routeIs('home') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-700 hover:bg-slate-50' }}">Explore Events</a>
            @if($backHref)
                <a href="{{ $backHref }}" class="block rounded-2xl px-4 py-3 text-sm font-bold text-slate-700 hover:bg-slate-50">{{ $backLabel ?? 'Back' }}</a>
            @endif
            @auth
                <a href="{{ $accountRoute }}" class="block rounded-2xl px-4 py-3 text-sm font-bold {{ request()->routeIs('customer.*') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-700 hover:bg-slate-50' }}">{{ $accountLabel }}</a>
                <a href="{{ route('profile.edit') }}" class="block rounded-2xl px-4 py-3 text-sm font-bold text-slate-700 hover:bg-slate-50">Profile</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full rounded-2xl bg-red-50 px-4 py-3 text-left text-sm font-bold text-red-600 hover:bg-red-100">Log Out</button>
                </form>
            @else
                <div class="grid grid-cols-2 gap-2 pt-2">
                    <a href="{{ route('login') }}" class="rounded-2xl border border-slate-200 bg-white px-4 py-3 text-center text-sm font-bold text-slate-700 hover:bg-slate-50">Login</a>
                    <a href="{{ route('register') }}" class="rounded-2xl bg-indigo-600 px-4 py-3 text-center text-sm font-bold text-white hover:bg-indigo-700">Register</a>
                </div>
            @endauth
        </div>
    </div>
</nav>