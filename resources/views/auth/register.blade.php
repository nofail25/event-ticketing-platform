<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="name" :value="__('Name')" class="text-xs font-black uppercase tracking-[0.28em] text-cyan-200" />
            <x-text-input id="name" class="block mt-1 w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-semibold text-slate-100 focus:outline-none focus:ring-2 focus:ring-cyan-300/30" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" class="text-xs font-black uppercase tracking-[0.28em] text-cyan-200" />
            <x-text-input id="email" class="block mt-1 w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-semibold text-slate-100 focus:outline-none focus:ring-2 focus:ring-cyan-300/30" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Password')" class="text-xs font-black uppercase tracking-[0.28em] text-fuchsia-200" />

            <x-text-input id="password"
                          class="block mt-1 w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-semibold text-slate-100 focus:outline-none focus:ring-2 focus:ring-fuchsia-300/30"
                          type="password"
                          name="password"
                          required
                          autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-xs font-black uppercase tracking-[0.28em] text-fuchsia-200" />

            <x-text-input id="password_confirmation"
                          class="block mt-1 w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-semibold text-slate-100 focus:outline-none focus:ring-2 focus:ring-fuchsia-300/30"
                          type="password"
                          name="password_confirmation"
                          required
                          autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="pt-2 space-y-3">
            <div class="flex items-center justify-between gap-4">
                <a href="{{ route('login') }}"
                   class="text-sm font-black text-slate-300 hover:text-white underline decoration-white/20 hover:decoration-white/50 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    {{ __('Already have an account? Log in') }}
                </a>

                <x-primary-button class="rounded-2xl px-6 py-3 text-sm font-black uppercase tracking-wide">
                    {{ __('Register') }}
                </x-primary-button>
            </div>
        </div>
    </form>
</x-guest-layout>
