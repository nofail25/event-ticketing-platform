<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-3">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-indigo-100 text-indigo-600 shadow-inner">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] font-black uppercase tracking-[0.2em] text-indigo-600">Account Settings</p>
                    <h2 class="text-2xl font-black leading-tight text-slate-900">My Profile</h2>
                </div>
            </div>
            <a href="{{ route('customer.dashboard') }}" class="secondary-button w-fit group">
                <span class="inline-block transform group-hover:-translate-x-1 transition-transform mr-1">←</span>
                Back to Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-10 bg-slate-50/50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-10">
            <!-- Profile Information -->
            <div class="clean-card bg-white border border-slate-200 shadow-sm relative overflow-hidden group">
                <div class="absolute -right-10 -top-10 w-32 h-32 bg-indigo-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700 pointer-events-none"></div>
                <div class="p-6 sm:p-10 relative z-10">
                    <div class="max-w-xl">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>
            </div>

            <!-- Update Password -->
            <div class="clean-card bg-white border border-slate-200 shadow-sm relative overflow-hidden group">
                <div class="absolute -right-10 -top-10 w-32 h-32 bg-purple-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700 pointer-events-none"></div>
                <div class="p-6 sm:p-10 relative z-10">
                    <div class="max-w-xl">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
            </div>

            <!-- Delete Account -->
            <div class="clean-card bg-white border border-red-100 shadow-sm relative overflow-hidden group">
                <div class="absolute -right-10 -top-10 w-32 h-32 bg-red-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700 pointer-events-none"></div>
                <div class="p-6 sm:p-10 relative z-10">
                    <div class="max-w-xl">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
