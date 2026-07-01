<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" class="text-xs font-bold uppercase tracking-wider text-slate-500" />
            <x-text-input id="email" class="block mt-1 w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition-all" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Password')" class="text-xs font-bold uppercase tracking-wider text-slate-500" />

            <x-text-input
                id="password"
                class="block mt-1 w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition-all"
                type="password"
                name="password"
                required
                autocomplete="current-password"
            />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between pt-1">
            <label for="remember_me" class="inline-flex items-center gap-2 text-sm text-slate-600">
                <input id="remember_me" type="checkbox" class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" name="remember">
                <span>{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm font-bold text-slate-500 hover:text-indigo-600 transition-colors"
                    href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif
        </div>

        <div class="pt-4 space-y-4">
            <button type="submit" class="w-full flex items-center justify-center bg-indigo-600 text-white hover:bg-indigo-700 active:bg-indigo-800 rounded-xl px-6 py-3 text-sm font-bold uppercase tracking-wide transition-colors shadow-lg shadow-indigo-500/30">
                {{ __('Log in') }}
            </button>

            <div class="text-center text-sm font-semibold text-slate-500">
                {{ __("Don't have an account?") }}
                <a href="{{ route('register') }}" class="font-bold text-indigo-600 hover:text-indigo-700 hover:underline">
                    {{ __('Register') }}
                </a>
            </div>
        </div>
    </form>
</x-guest-layout>
