<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-3">
                <div class="flex-shrink-0 w-9 h-9 rounded-lg bg-emerald-600 flex items-center justify-center">
                    <span class="material-symbols-outlined text-xl text-white" style="line-height:1;">person</span>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-slate-900">Pengaturan Akun</h2>
                    <p class="text-xs text-slate-500 font-medium">Kelola informasi profil dan keamanan akun Anda</p>
                </div>
            </div>
            
            <a href="{{ url('/dashboard') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-slate-600 hover:text-emerald-600 transition-colors">
                <span class="material-symbols-outlined text-lg" style="line-height:1;">arrow_back</span>
                Kembali ke Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(auth()->user()->hasRole('Event Organizer'))
            <!-- Organizer Profile -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                <div class="max-w-xl">
                    @include('profile.partials.update-organizer-profile-form')
                </div>
            </div>
            @endif

            <!-- Profile Information -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Update Password -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Delete Account -->
            <div class="bg-white rounded-xl border border-red-100 shadow-sm p-6">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
