<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $event->title }} - Eventmu</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-slate-50 text-slate-900">
        @php
            $minPrice = $event->ticketCategories->count() > 0 ? $event->ticketCategories->min('price') : null;
            $totalAvailable = $event->ticketCategories->sum(fn ($category) => $category->availableQuota());
        @endphp

        <div class="page-shell flex min-h-screen flex-col">
            <div class="page-content">
                <x-public-navigation />

                <main class="mx-auto w-full max-w-7xl px-4 py-8 sm:px-6 lg:px-8 lg:py-12">
                    @if(session('payment_success'))
                        @php
                            $paymentSuccess = session('payment_success');
                        @endphp
                        <div class="mb-8 rounded-3xl border border-emerald-200 bg-emerald-50 p-5 shadow-sm">
                            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                <div class="flex items-start gap-4">
                                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-700">
                                        <span class="material-symbols-outlined text-2xl" style="line-height:1;">check</span>
                                    </div>
                                    <div>
                                        <h2 class="text-lg font-black text-slate-900">{{ $paymentSuccess['title'] ?? 'Pembayaran Berhasil' }}</h2>
                                        <p class="mt-1 text-sm text-slate-600">{{ $paymentSuccess['message'] ?? 'Tiket Anda sudah aktif dan siap digunakan.' }}</p>
                                        @if(! empty($paymentSuccess['invoice_number']))
                                            <p class="mt-2 text-xs font-bold text-emerald-600">Invoice: {{ $paymentSuccess['invoice_number'] }}</p>
                                        @endif
                                    </div>
                                </div>

                                <a href="{{ $paymentSuccess['ticket_url'] ?? route('customer.dashboard') }}" class="primary-button">
                                    Lihat Tiket
                                </a>
                            </div>
                        </div>
                    @endif

                    <section class="grid gap-8 lg:grid-cols-[1.25fr_0.75fr]">
                        <div class="space-y-6">
                            <a href="{{ route('home') }}" class="inline-flex items-center gap-1 text-sm font-bold text-slate-500 hover:text-slate-900 transition-colors">
                                <span>&larr;</span> Kembali ke Beranda
                            </a>

                            <div class="clean-card overflow-hidden transition-all duration-700 ease-out-ui origin-bottom transform opacity-0 scale-95" x-data="{ show: false }" x-init="setTimeout(() => show = true, 50)" :class="show ? '!opacity-100 !scale-100' : ''">
                                <div class="relative aspect-video w-full overflow-hidden">
                                    @if($event->banner_image)
                                        <img src="{{ asset('storage/' . $event->banner_image) }}" alt="{{ $event->title }}" class="h-full w-full object-cover">
                                    @else
                                        <img src="https://picsum.photos/seed/{{ $event->id }}/1200/600" alt="{{ $event->title }}" class="h-full w-full object-cover">
                                    @endif
                                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-slate-900/20 to-transparent"></div>

                                    <div class="absolute inset-x-0 bottom-0 p-6 sm:p-8">
                                        <div class="mb-4 flex flex-wrap gap-2">
                                            <span class="info-badge bg-white/90 text-slate-900 border-transparent shadow-sm">{{ $event->start_time->format('d M Y') }}</span>
                                            <span class="info-badge bg-white/90 text-slate-900 border-transparent shadow-sm">{{ $event->ticketCategories->count() }} kategori tiket</span>
                                            <span class="info-badge bg-white/90 text-slate-900 border-transparent shadow-sm">{{ $totalAvailable }} tiket tersedia</span>
                                        </div>
                                        <h1 class="max-w-4xl text-4xl font-black tracking-tight text-white sm:text-5xl lg:text-6xl drop-shadow-md">{{ $event->title }}</h1>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                                <div class="glass-panel p-5">
                                    <p class="text-xs font-bold uppercase tracking-widest text-indigo-600">Tanggal & Waktu</p>
                                    <p class="mt-3 text-lg font-black text-slate-900">{{ $event->start_time->format('d M Y') }}</p>
                                    <p class="mt-1 text-sm text-slate-500">{{ $event->start_time->format('H:i') }} - {{ $event->end_time->format('H:i') }} WIB</p>
                                </div>
                                <div class="glass-panel p-5">
                                    <p class="text-xs font-bold uppercase tracking-widest text-indigo-600">Lokasi</p>
                                    <p class="mt-3 text-lg font-black text-slate-900">{{ $event->location }}</p>
                                    <p class="mt-1 text-sm text-slate-500">Detail akses lokasi</p>
                                </div>
                                <div class="glass-panel p-5">
                                    <p class="text-xs font-bold uppercase tracking-widest text-indigo-600">Penyelenggara</p>
                                    <p class="mt-3 text-lg font-black text-slate-900">{{ $event->organizer->name }}</p>
                                    <p class="mt-1 text-sm text-slate-500">Mitra event terverifikasi</p>
                                </div>
                            </div>

                            <section class="clean-card p-6 sm:p-8">
                                <p class="text-sm font-bold uppercase tracking-[0.2em] text-slate-400">Tentang Event Ini</p>
                                <div class="mt-5 whitespace-pre-wrap text-base leading-8 text-slate-600">{{ $event->description }}</div>
                            </section>
                        </div>

                        <aside class="space-y-6 lg:sticky lg:top-28 lg:self-start">
                            <section class="clean-card p-6">
                                <div class="mb-6 flex items-start justify-between gap-4">
                                    <div>
                                        <p class="text-xs font-bold uppercase tracking-[0.2em] text-indigo-600">Tiket</p>
                                        <h2 class="mt-2 text-3xl font-black text-slate-900">Pilih Tiket</h2>
                                    </div>
                                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-3 py-2 text-right">
                                        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-500">Mulai</p>
                                        <p class="text-sm font-black text-slate-900">{{ $minPrice !== null ? 'Rp ' . number_format($minPrice, 0, ',', '.') : 'TBA' }}</p>
                                    </div>
                                </div>

                                @if($event->ticketCategories->count() > 0)
                                    <div class="space-y-4">
                                        @foreach($event->ticketCategories as $category)
                                            @php
                                                $available = $category->availableQuota();
                                            @endphp
                                            <div class="group rounded-3xl border border-slate-200 bg-white p-4 transition-all duration-300 ease-out-ui hover:-translate-y-1 hover:border-indigo-200 hover:shadow-md hover:shadow-indigo-500/10 active:scale-[0.98]">
                                                <div class="flex items-start justify-between gap-3">
                                                    <div>
                                                        <h3 class="font-black text-slate-900">{{ $category->name }}</h3>
                                                        <p class="mt-1 text-sm text-slate-500">Tiket QR Digital</p>
                                                    </div>
                                                    <p class="text-right text-xl font-black text-indigo-600">Rp {{ number_format($category->price, 0, ',', '.') }}</p>
                                                </div>

                                                <div class="mt-4 flex items-center justify-between gap-3">
                                                    @if($available > 0)
                                                        <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-700">{{ $available }} tersedia</span>
                                                    @else
                                                        <span class="rounded-full bg-rose-100 px-3 py-1 text-xs font-bold text-rose-700">Habis</span>
                                                    @endif
                                                </div>

                                                @if($available > 0)
                                                    @auth
                                                        <a href="{{ route('checkout.create', $category) }}" class="primary-button mt-4 w-full">
                                                            Beli Tiket
                                                        </a>
                                                    @else
                                                        <a href="{{ route('login') }}" class="primary-button mt-4 w-full">
                                                            Masuk untuk Beli
                                                        </a>
                                                    @endauth
                                                @else
                                                    <button disabled class="mt-4 w-full cursor-not-allowed rounded-2xl border border-slate-200 bg-slate-100 px-5 py-3 text-sm font-bold uppercase tracking-wide text-slate-400">
                                                        Habis
                                                    </button>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6 text-center text-slate-500">
                                        Belum ada tiket yang tersedia untuk event ini.
                                    </div>
                                @endif
                            </section>



                            <section class="glass-panel p-5 text-sm text-slate-600">
                                <p class="mb-3 font-bold text-slate-900">ℹ Penting</p>
                                <ul class="space-y-2 text-xs leading-6 text-slate-500">
                                    <li>Tiket yang sudah dibeli tidak dapat dikembalikan</li>
                                    <li>Satu tiket per pembelian (untuk saat ini)</li>
                                    <li>Tiket digital dapat diakses di menu Tiket Saya</li>
                                </ul>
                            </section>
                        </aside>
                    </section>
                </main>
            </div>

            <footer class="page-content mt-auto border-t border-slate-200 bg-white text-slate-500">
                <div class="mx-auto max-w-7xl px-4 py-8 text-center sm:px-6 lg:px-8">
                    <p class="text-sm">&copy; 2026 Eventmu. Akses digital yang aman untuk semua.</p>
                </div>
            </footer>
        </div>
    </body>
</html>
