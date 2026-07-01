@props(['backHref' => null, 'backLabel' => null])

@php
    $authUser = Auth::user();
    $accountRoute = $authUser ? ($authUser->hasRole('Customer') ? route('customer.dashboard') : route('dashboard')) : null;
    $accountLabel = $authUser ? ($authUser->hasRole('Customer') ? 'My Tickets' : 'Dashboard') : null;
@endphp

<nav x-data="{ 
    open: false,
    isSearching: false, 
    searchQuery: '{{ request('search') }}',
    suggestions: [],
    showSuggestions: false,
    fetchSuggestions() {
        if(this.searchQuery.length === 0) {
            this.suggestions = [];
            this.showSuggestions = false;
            return;
        }
        this.isSearching = true;
        fetch('/api/search-events?q=' + encodeURIComponent(this.searchQuery))
            .then(res => res.json())
            .then(data => {
                this.suggestions = data;
                this.showSuggestions = true;
                this.isSearching = false;
            });
    }
}" class="sticky top-0 z-50 border-b border-slate-200 bg-white/80 shadow-sm backdrop-blur-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex min-h-20 items-center justify-between gap-4">
            <div class="flex min-w-0 items-center gap-5">
                <a href="{{ route('home') }}" class="group flex shrink-0 items-center gap-3">
                    <div class="relative flex h-11 w-11 items-center justify-center rounded-2xl bg-indigo-600 shadow-sm transition group-hover:scale-105 group-hover:bg-indigo-700">
                        <span class="material-symbols-outlined text-xl text-white" style="line-height:1;">local_activity</span>
                    </div>
                    <div class="hidden sm:block leading-tight">
                        <p class="text-base font-black tracking-tight text-slate-900">Eventmu</p>
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
                            <button type="button" class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm font-bold leading-4 text-slate-700 transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-indigo-100 text-xs font-black text-indigo-700">
                                    {{ strtoupper(substr($authUser->name, 0, 1)) }}
                                </span>
                                <span class="hidden max-w-28 truncate md:inline">{{ $authUser->name }}</span>
                                <span class="material-symbols-outlined text-base text-slate-400" style="line-height:1;">expand_more</span>
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

            <button type="button" @click="open = ! open" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white p-2.5 text-slate-500 transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 sm:hidden" aria-label="Toggle navigation">
                <span class="material-symbols-outlined text-2xl" style="line-height:1;">menu</span>
            </button>
        </div>
    </div>

    <div x-cloak x-show="open" 
         x-transition:enter="transition ease-out-ui duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-out-ui duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="border-t border-slate-200 bg-white px-4 pb-5 pt-3 sm:hidden">
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