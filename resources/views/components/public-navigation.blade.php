@props(['backHref' => null, 'backLabel' => null])

@php
    $authUser = Auth::user();
    $accountRoute = $authUser ? ($authUser->hasRole('Customer') ? route('customer.dashboard') : route('dashboard')) : null;
    $accountLabel = $authUser ? ($authUser->hasRole('Customer') ? 'My Tickets' : 'Dashboard') : null;
@endphp

<nav x-data="{ open: false }" class="sticky top-0 z-50 border-b border-cyan-400/10 bg-slate-950/75 shadow-2xl shadow-cyan-950/20 backdrop-blur-2xl">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex min-h-20 items-center justify-between gap-4">
            <div class="flex min-w-0 items-center gap-5">
                <a href="{{ route('home') }}" class="group flex shrink-0 items-center gap-3">
                    <div class="relative flex h-11 w-11 items-center justify-center rounded-2xl border border-cyan-300/30 bg-cyan-300/10 shadow-lg shadow-cyan-500/20 transition group-hover:scale-105">
                        <span class="absolute -inset-1 rounded-2xl bg-gradient-to-br from-cyan-300/30 via-fuchsia-400/20 to-lime-300/20 blur-md"></span>
                        <svg class="relative h-5 w-5 text-cyan-100" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                        </svg>
                    </div>
                    <div class="hidden sm:block leading-tight">
                        <p class="bg-gradient-to-r from-cyan-200 via-fuchsia-200 to-lime-200 bg-clip-text text-base font-black tracking-tight text-transparent">EventTicketing</p>
                        <p class="text-[11px] font-semibold uppercase tracking-[0.24em] text-slate-500">Neon Pass</p>
                    </div>
                </a>

                <div class="hidden items-center gap-2 lg:flex">
                    <a href="{{ route('home') }}" class="rounded-full border {{ request()->routeIs('home') ? 'border-cyan-300/40 bg-cyan-300/10 text-cyan-100' : 'border-transparent text-slate-300 hover:border-white/10 hover:bg-white/10 hover:text-white' }} px-4 py-2 text-sm font-bold transition">Explore Events</a>
                    @if($backHref)
                        <a href="{{ $backHref }}" class="rounded-full border border-transparent px-4 py-2 text-sm font-bold text-slate-300 transition hover:border-fuchsia-300/30 hover:bg-fuchsia-300/10 hover:text-fuchsia-100">{{ $backLabel ?? 'Back' }}</a>
                    @endif
                    @auth
                        <a href="{{ $accountRoute }}" class="rounded-full border {{ request()->routeIs('customer.*') ? 'border-cyan-300/40 bg-cyan-300/10 text-cyan-100' : 'border-transparent text-slate-300 hover:border-white/10 hover:bg-white/10 hover:text-white' }} px-4 py-2 text-sm font-bold transition">{{ $accountLabel }}</a>
                    @endauth
                </div>
            </div>

            <div class="hidden items-center gap-3 sm:flex">
                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center gap-2 rounded-2xl border border-white/10 bg-white/5 px-3 py-2 text-sm font-bold leading-4 text-white transition hover:border-fuchsia-300/30 hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-cyan-300/50">
                                <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-gradient-to-br from-cyan-300 to-fuchsia-400 text-xs font-black text-slate-950 shadow-lg shadow-fuchsia-500/20">
                                    {{ strtoupper(substr($authUser->name, 0, 1)) }}
                                </span>
                                <span class="hidden max-w-28 truncate md:inline">{{ $authUser->name }}</span>
                                <svg class="h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="border-b border-white/10 px-4 py-3">
                                <p class="truncate text-sm font-bold text-white">{{ $authUser->name }}</p>
                                <p class="truncate text-xs text-slate-400">{{ $authUser->email }}</p>
                            </div>
                            <x-dropdown-link :href="$accountRoute">{{ $accountLabel }}</x-dropdown-link>
                            <x-dropdown-link :href="route('profile.edit')">Profile</x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">Log Out</x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <a href="{{ route('login') }}" class="rounded-full px-4 py-2 text-sm font-bold text-slate-300 transition hover:bg-white/10 hover:text-white">Login</a>
                    <a href="{{ route('register') }}" class="neon-button px-4 py-2 text-xs">Register</a>
                @endauth
            </div>

            <button @click="open = ! open" class="inline-flex items-center justify-center rounded-2xl border border-white/10 bg-white/5 p-2.5 text-slate-200 transition hover:border-cyan-300/40 hover:bg-cyan-300/10 hover:text-cyan-100 focus:outline-none focus:ring-2 focus:ring-cyan-300/50 sm:hidden" aria-label="Toggle navigation">
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <div x-cloak x-show="open" x-transition class="border-t border-white/10 bg-slate-950/95 px-4 pb-5 pt-3 backdrop-blur-2xl sm:hidden">
        <div class="space-y-2">
            <a href="{{ route('home') }}" class="block rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-bold text-slate-200">Explore Events</a>
            @if($backHref)
                <a href="{{ $backHref }}" class="block rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-bold text-slate-200">{{ $backLabel ?? 'Back' }}</a>
            @endif
            @auth
                <a href="{{ $accountRoute }}" class="block rounded-2xl border border-cyan-300/30 bg-cyan-300/10 px-4 py-3 text-sm font-bold text-cyan-100">{{ $accountLabel }}</a>
                <a href="{{ route('profile.edit') }}" class="block rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-bold text-slate-200">Profile</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full rounded-2xl border border-fuchsia-300/20 bg-fuchsia-400/10 px-4 py-3 text-left text-sm font-bold text-fuchsia-100">Log Out</button>
                </form>
            @else
                <div class="grid grid-cols-2 gap-2 pt-2">
                    <a href="{{ route('login') }}" class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-center text-sm font-bold text-slate-200">Login</a>
                    <a href="{{ route('register') }}" class="rounded-2xl bg-gradient-to-r from-cyan-300 to-fuchsia-400 px-4 py-3 text-center text-sm font-black text-slate-950">Register</a>
                </div>
            @endauth
        </div>
    </div>
</nav>