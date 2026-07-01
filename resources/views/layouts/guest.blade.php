<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body class="font-sans antialiased bg-white text-slate-900">
        <div class="flex min-h-screen relative overflow-hidden">
            
            {{-- Background Effects --}}
            <div class="pointer-events-none absolute inset-0"
                 style="background-image: linear-gradient(to right, #80808010 1px, transparent 1px), linear-gradient(to bottom, #80808010 1px, transparent 1px); background-size: 32px 32px;"></div>
            
            {{-- Floating Orbs --}}
            <div class="pointer-events-none absolute -left-32 -top-32 h-96 w-96 rounded-full bg-indigo-400/20 blur-[100px]"></div>
            <div class="pointer-events-none absolute -right-24 top-8 h-80 w-80 rounded-full bg-violet-400/15 blur-[100px]"></div>
            <div class="pointer-events-none absolute bottom-0 left-1/2 h-64 w-64 -translate-x-1/2 rounded-full bg-indigo-300/10 blur-[80px]"></div>
            <!-- Content Area -->
            <div class="flex flex-1 flex-col justify-center px-4 py-12 sm:px-6 lg:flex-none lg:px-20 xl:px-24 w-full lg:w-1/2 relative z-10">
                <div class="mx-auto w-full max-w-md">
                    
                    {{-- Logo & Back --}}
                    <div class="flex items-center justify-between mb-10">
                        <a href="/" class="flex items-center gap-2">
                            <div class="relative flex h-10 w-10 items-center justify-center rounded-xl border border-indigo-600 bg-indigo-600 text-white shadow-lg shadow-indigo-600/30">
                                <span class="material-symbols-outlined text-xl" style="line-height:1;">local_activity</span>
                            </div>
                            <span class="text-xl font-black text-slate-900 tracking-tight">Eventmu</span>
                        </a>
                        
                        <a href="/" class="inline-flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wider text-slate-400 hover:text-indigo-600 transition-colors">
                            <span class="material-symbols-outlined text-sm" style="line-height:1;">arrow_back</span>
                            Kembali
                        </a>
                    </div>

                    {{-- Form Container --}}
                    <div class="rounded-3xl border border-slate-200 bg-white/80 backdrop-blur-xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-8 sm:p-10">
                        {{ $slot }}
                    </div>

                    <div class="mt-8 text-center text-xs font-semibold text-slate-500">
                        &copy; 2026 Eventmu. Premium Ticketing Platform.
                    </div>
                </div>
            </div>

            <!-- Visual Area -->
            <div class="relative hidden w-0 flex-1 lg:block overflow-hidden bg-slate-50">
                <img class="absolute inset-0 h-full w-full object-cover opacity-80 mix-blend-multiply" src="https://images.unsplash.com/photo-1492684223066-81342ee5ff30?q=80&w=2070&auto=format&fit=crop" alt="Event concert crowd">
                <div class="absolute inset-0 bg-gradient-to-r from-white via-white/50 to-transparent"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/60 to-transparent"></div>
                
                <div class="absolute bottom-16 left-16 right-16 text-white max-w-lg z-10">
                    <div class="mb-4 inline-flex items-center rounded-full border border-indigo-400/30 bg-indigo-500/20 px-3 py-1 text-xs font-semibold text-indigo-100 backdrop-blur-md">
                        Mulai Petualangan Anda
                    </div>
                    <h3 class="font-helvetica text-4xl font-black mb-4 leading-tight text-white">Momen tak terlupakan dimulai dari sini.</h3>
                    <p class="text-indigo-100 text-lg">Temukan konser, festival, dan acara terbaik di kota Anda. Dapatkan tiket dengan mudah dan aman.</p>
                </div>
            </div>
        </div>
    </body>
</html>
