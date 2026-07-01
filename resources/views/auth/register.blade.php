<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="name" :value="__('Name')" class="text-xs font-bold uppercase tracking-wider text-slate-500" />
            <x-text-input id="name" class="block mt-1 w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition-all" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" class="text-xs font-bold uppercase tracking-wider text-slate-500" />
            <x-text-input id="email" class="block mt-1 w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition-all" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label :value="__('Account Type')" class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-2 block" />
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <label class="cursor-pointer relative">
                    <input type="radio" name="role" value="Customer" class="peer sr-only" checked>
                    <div class="rounded-xl border-2 border-slate-200 bg-white p-4 hover:bg-slate-50 peer-checked:border-indigo-600 peer-checked:bg-indigo-50 transition-all h-full">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-sm font-bold text-slate-900">Customer</span>
                            <svg class="h-5 w-5 text-indigo-600 opacity-0 peer-checked:opacity-100 transition-opacity" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <p class="text-xs text-slate-500 font-medium">I want to buy tickets and attend events.</p>
                    </div>
                </label>
                
                <label class="cursor-pointer relative">
                    <input type="radio" name="role" value="Event Organizer" class="peer sr-only">
                    <div class="rounded-xl border-2 border-slate-200 bg-white p-4 hover:bg-slate-50 peer-checked:border-indigo-600 peer-checked:bg-indigo-50 transition-all h-full">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-sm font-bold text-slate-900">Organizer</span>
                            <svg class="h-5 w-5 text-indigo-600 opacity-0 peer-checked:opacity-100 transition-opacity" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <p class="text-xs text-slate-500 font-medium">I want to create and manage events.</p>
                    </div>
                </label>
            </div>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Password')" class="text-xs font-bold uppercase tracking-wider text-slate-500" />

            <x-text-input id="password"
                          class="block mt-1 w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition-all"
                          type="password"
                          name="password"
                          required
                          autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-xs font-bold uppercase tracking-wider text-slate-500" />

            <x-text-input id="password_confirmation"
                          class="block mt-1 w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition-all"
                          type="password"
                          name="password_confirmation"
                          required
                          autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="pt-4 space-y-4">
            <button type="submit" class="w-full flex items-center justify-center bg-indigo-600 text-white hover:bg-indigo-700 active:bg-indigo-800 rounded-xl px-6 py-3 text-sm font-bold uppercase tracking-wide transition-colors shadow-md">
                {{ __('Register') }}
            </button>
            
            <div class="text-center text-sm font-semibold text-slate-500">
                {{ __('Already have an account?') }}
                <a href="{{ route('login') }}" class="font-bold text-indigo-600 hover:text-indigo-700 hover:underline">
                    {{ __('Log in') }}
                </a>
            </div>
        </div>
    </form>
</x-guest-layout>
