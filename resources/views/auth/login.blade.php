<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" class="text-xs font-black uppercase tracking-[0.28em] text-cyan-200" />
            <x-text-input id="email" class="block mt-1 w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-semibold text-slate-100 focus:outline-none focus:ring-2 focus:ring-cyan-300/30" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Password')" class="text-xs font-black uppercase tracking-[0.28em] text-fuchsia-200" />

            <x-text-input
                id="password"
                class="block mt-1 w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-semibold text-slate-100 focus:outline-none focus:ring-2 focus:ring-fuchsia-300/30"
                type="password"
                name="password"
                required
                autocomplete="current-password"
            />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between pt-1">
            <label for="remember_me" class="inline-flex items-center gap-2 text-sm text-slate-300">
                <input id="remember_me" type="checkbox" class="h-4 w-4 rounded border-white/20 bg-white/5 text-cyan-300/60 focus:ring-cyan-300/30" name="remember">
                <span>{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm font-black text-slate-300 hover:text-white underline decoration-white/20 hover:decoration-white/50"
                    href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif
        </div>

        <div class="pt-2 space-y-3">
            <x-primary-button class="w-full rounded-2xl px-6 py-3 text-sm font-black uppercase tracking-wide">
                {{ __('Log in') }}
            </x-primary-button>

            <div class="text-center text-sm font-semibold text-slate-500">
                {{ __("Don't have an account?") }}
                <a href="{{ route('register') }}" class="font-black text-cyan-200 hover:underline decoration-white/20">
                    {{ __('Register') }}
                </a>
            </div>
        </div>
    </form>
</x-guest-layout>
