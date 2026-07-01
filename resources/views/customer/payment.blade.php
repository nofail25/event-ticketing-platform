<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Menunggu Pembayaran — {{ $order->invoice_number }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            [x-cloak] { display: none !important; }

            /* QR Code pulse ring */
            @keyframes qr-pulse {
                0%, 100% { box-shadow: 0 0 0 0 rgba(99, 102, 241, 0.4); }
                50% { box-shadow: 0 0 0 16px rgba(99, 102, 241, 0); }
            }
            .qr-pulse { animation: qr-pulse 2.5s ease-in-out infinite; }

            /* Timer ring */
            .timer-ring-bg { stroke: #e2e8f0; }
            .timer-ring-progress {
                stroke: #f59e0b;
                stroke-linecap: round;
                transform: rotate(-90deg);
                transform-origin: 50% 50%;
                transition: stroke-dashoffset 1s linear, stroke 0.5s;
            }

            /* VA number shimmer copy */
            .va-number {
                font-family: 'Courier New', Courier, monospace;
                letter-spacing: 0.15em;
            }

            /* Glassmorphism bank card */
            .bank-card {
                background: linear-gradient(135deg, #1e3a5f 0%, #0f2744 60%, #162d4e 100%);
                border-radius: 18px;
                position: relative;
                overflow: hidden;
            }
            .bank-card::before {
                content: '';
                position: absolute;
                top: -40%;
                right: -20%;
                width: 60%;
                height: 150%;
                background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 70%);
                border-radius: 50%;
            }
            .bank-card-bca { background: linear-gradient(135deg, #003b77 0%, #012d5c 100%); }
            .bank-card-mandiri { background: linear-gradient(135deg, #1a3a6b 0%, #e5a101 100%); }
            .bank-card-bni { background: linear-gradient(135deg, #f77f00 0%, #c85000 100%); }
            .bank-card-bri { background: linear-gradient(135deg, #00509e 0%, #003976 100%); }

            .copy-btn:active { transform: scale(0.95); }

            /* Copied toast */
            .toast-enter { animation: toast-in 0.3s ease; }
            @keyframes toast-in {
                from { opacity:0; transform: translateY(8px) scale(0.95); }
                to   { opacity:1; transform: translateY(0) scale(1); }
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-slate-100 text-slate-900 min-h-screen">

        @php
            $channel = $order->payment_channel;
            $method  = $order->payment_method;

            $isQris = $method === 'qris';
            $isVA   = $method === 'virtual_account';
            $isWallet = $method === 'e_wallet';

            // Countdown seconds from order creation
            $elapsedSeconds = now()->diffInSeconds($order->created_at, false) * -1;
            $totalSeconds   = 15 * 60; // 15 minutes
            $remainingSeconds = max(0, $totalSeconds - $elapsedSeconds);

            // VA Numbers by bank
            $vaNumbers = [
                'va_bca'     => '8888-0147-' . str_pad($order->id, 8, '0', STR_PAD_LEFT),
                'va_mandiri' => '8899-0271-' . str_pad($order->id, 8, '0', STR_PAD_LEFT),
                'va_bni'     => '9888-0062-' . str_pad($order->id, 8, '0', STR_PAD_LEFT),
                'va_bri'     => 'BRIVA 88-' . str_pad($order->id, 10, '0', STR_PAD_LEFT),
            ];
            $vaNumber = $vaNumbers[$channel] ?? '89012345678';

            // Bank labels
            $bankLabels = [
                'va_bca'     => 'BCA Virtual Account',
                'va_mandiri' => 'Mandiri Virtual Account',
                'va_bni'     => 'BNI Virtual Account',
                'va_bri'     => 'BRI Virtual Account',
            ];
            $bankLabel = $bankLabels[$channel] ?? 'Virtual Account';

            $bankCardClass = match($channel) {
                'va_bca'     => 'bank-card-bca',
                'va_mandiri' => 'bank-card-mandiri',
                'va_bni'     => 'bank-card-bni',
                'va_bri'     => 'bank-card-bri',
                default      => '',
            };

            // QRIS / Wallet labels
            $channelLabels = [
                'qris_universal'  => 'QRIS Universal',
                'wallet_dana'   => 'DANA',
                'wallet_gopay'  => 'GoPay',
                'wallet_ovo'    => 'OVO',
                'wallet_shopeepay' => 'ShopeePay',
            ];
            $channelLabel = $channelLabels[$channel] ?? ucwords(str_replace('_', ' ', $channel));

            // Fake deep-link numbers for e-wallets
            $walletNumbers = [
                'wallet_dana'      => '0812-xxxx-' . rand(1000, 9999),
                'wallet_gopay'     => '0813-xxxx-' . rand(1000, 9999),
                'wallet_ovo'       => '0818-xxxx-' . rand(1000, 9999),
                'wallet_shopeepay' => '0819-xxxx-' . rand(1000, 9999),
            ];
            $walletNumber = $walletNumbers[$channel] ?? '-';

            // Payment instructions per channel
            $instructions = [];
            if ($isVA) {
                $bankName = match($channel) {
                    'va_bca'     => 'BCA',
                    'va_mandiri' => 'Mandiri / Livin\'',
                    'va_bni'     => 'BNI Mobile Banking',
                    'va_bri'     => 'BRImo',
                    default      => 'Bank Anda',
                };
                $instructions = [
                    "Buka aplikasi <strong>{$bankName}</strong> atau ATM.",
                    "Pilih menu <strong>Transfer → Virtual Account</strong>.",
                    "Masukkan <strong>Nomor VA</strong> di atas.",
                    "Pastikan nama merchant dan nominal sesuai: <strong>Rp " . number_format($order->total_amount, 0, ',', '.') . "</strong>.",
                    "Konfirmasi dan selesaikan transaksi.",
                    "Tiket Anda akan aktif otomatis dalam beberapa detik setelah pembayaran berhasil.",
                ];
            } elseif ($isQris) {
                $instructions = [
                    "Buka aplikasi pembayaran Anda (GoPay, OVO, Dana, QRIS apapun).",
                    "Pilih menu <strong>Scan QR / Bayar</strong>.",
                    "Arahkan kamera ke kode QR di atas.",
                    "Pastikan nominal sesuai: <strong>Rp " . number_format($order->total_amount, 0, ',', '.') . "</strong>.",
                    "Konfirmasi pembayaran dengan PIN Anda.",
                    "Tiket Anda akan aktif otomatis setelah pembayaran berhasil.",
                ];
            } else {
                $instructions = [
                    "Buka aplikasi <strong>{$channelLabel}</strong> di ponsel Anda.",
                    "Pilih menu <strong>Transfer / Bayar</strong>.",
                    "Masukkan nomor <strong>{$walletNumber}</strong>.",
                    "Masukkan nominal <strong>Rp " . number_format($order->total_amount, 0, ',', '.') . "</strong>.",
                    "Konfirmasi dan selesaikan transaksi.",
                    "Tiket Anda akan aktif otomatis setelah pembayaran berhasil.",
                ];
            }
        @endphp

        {{-- Navbar --}}
        <header class="sticky top-0 z-40 bg-white/90 backdrop-blur-md border-b border-slate-200 shadow-sm">
            <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                <a href="{{ route('home') }}" class="flex items-center gap-2 text-indigo-600 font-black text-xl tracking-tight">
                    <svg class="h-7 w-7" fill="currentColor" viewBox="0 0 24 24"><path d="M20 12v6.17A2 2 0 0118 20H6a2 2 0 01-2-1.83V12h16zm0-2H4V6a2 2 0 012-2h1V3h2v1h6V3h2v1h1a2 2 0 012 2v4z"/></svg>
                    <span>TixNow</span>
                </a>
                <div class="flex items-center gap-3 text-sm">
                    <span class="hidden sm:flex items-center gap-1.5 text-slate-500">
                        <span class="inline-block w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                        Menunggu Pembayaran
                    </span>
                    <span class="font-mono text-xs bg-slate-100 text-slate-600 px-3 py-1.5 rounded-full border border-slate-200">{{ $order->invoice_number }}</span>
                </div>
            </div>
        </header>

        <main class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8 py-8 lg:py-12"
              x-data="{
                  totalSeconds: {{ $totalSeconds }},
                  remaining: {{ (int) $remainingSeconds }},
                  expired: {{ $remainingSeconds <= 0 ? 'true' : 'false' }},
                  copied: false,
                  copyText: '',
                  get minutes() { return Math.floor(this.remaining / 60); },
                  get seconds() { return Math.floor(this.remaining % 60); },
                  get pad() { return v => String(v).padStart(2,'0'); },
                  get display() { return this.pad(this.minutes) + ':' + this.pad(this.seconds); },
                  get progress() {
                      const r = 42;
                      const circ = 2 * Math.PI * r;
                      return circ - (this.remaining / this.totalSeconds) * circ;
                  },
                  get circumference() { return 2 * Math.PI * 42; },
                  get timerColor() {
                      if (this.remaining > 300) return '#f59e0b';
                      if (this.remaining > 60)  return '#ef4444';
                      return '#dc2626';
                  },
                  startTimer() {
                      if (this.expired) return;
                      const t = setInterval(() => {
                          if (this.remaining <= 0) {
                              this.expired = true;
                              clearInterval(t);
                          } else {
                              this.remaining--;
                          }
                      }, 1000);
                  },
                  copyToClipboard(text) {
                      navigator.clipboard.writeText(text).then(() => {
                          this.copyText = text;
                          this.copied = true;
                          setTimeout(() => this.copied = false, 2000);
                      });
                  }
              }"
              x-init="startTimer()">

            {{-- Expired Overlay --}}
            <div x-cloak x-show="expired"
                 class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/70 backdrop-blur-sm p-4">
                <div class="bg-white rounded-3xl p-10 text-center max-w-sm w-full shadow-2xl"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-90"
                     x-transition:enter-end="opacity-100 scale-100">
                    <div class="w-20 h-20 bg-rose-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-rose-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 mb-2">Waktu Habis</h3>
                    <p class="text-slate-500 text-sm mb-8 leading-relaxed">Batas waktu pembayaran Anda telah berakhir. Pesanan ini otomatis dibatalkan. Anda dapat melakukan pemesanan baru.</p>
                    <a href="{{ route('home') }}" class="inline-flex items-center justify-center w-full px-6 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-colors">
                        Kembali ke Beranda
                    </a>
                </div>
            </div>

            {{-- Copied Toast --}}
            <div x-cloak x-show="copied"
                 x-transition:enter="toast-enter"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-90"
                 class="fixed bottom-6 left-1/2 -translate-x-1/2 z-50 bg-slate-900 text-white text-sm font-semibold px-5 py-3 rounded-full shadow-xl flex items-center gap-2 whitespace-nowrap">
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                Disalin ke clipboard
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-[1fr_380px] gap-8 items-start">

                {{-- ===== LEFT: PAYMENT INSTRUMENT ===== --}}
                <div class="space-y-6">

                    {{-- Timer Card --}}
                    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 flex items-center gap-6">
                        {{-- SVG Ring Timer --}}
                        <div class="relative shrink-0 flex items-center justify-center" style="width:88px;height:88px;">
                            <svg width="88" height="88" viewBox="0 0 96 96">
                                <circle class="timer-ring-bg" cx="48" cy="48" r="42" fill="none" stroke-width="7"/>
                                <circle class="timer-ring-progress"
                                        cx="48" cy="48" r="42" fill="none" stroke-width="7"
                                        :stroke="timerColor"
                                        :stroke-dasharray="circumference"
                                        :stroke-dashoffset="progress"/>
                            </svg>
                            <div class="absolute inset-0 flex flex-col items-center justify-center">
                                <span class="font-mono font-black text-lg leading-none" :style="`color: ${timerColor}`" x-text="display">{{ str_pad(floor($remainingSeconds/60), 2, '0', STR_PAD_LEFT) . ':' . str_pad($remainingSeconds % 60, 2, '0', STR_PAD_LEFT) }}</span>
                            </div>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-1">Batas Waktu Pembayaran</p>
                            <p class="text-slate-900 font-bold text-base">Segera selesaikan sebelum timer habis</p>
                            <p class="text-xs text-slate-500 mt-1">Pesanan akan otomatis dibatalkan jika tidak dibayar dalam 15 menit</p>
                        </div>
                    </div>

                    {{-- ===== QRIS DISPLAY ===== --}}
                    @if($isQris)
                    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                        {{-- Header --}}
                        <div class="bg-gradient-to-r from-indigo-600 to-violet-600 p-6 text-white">
                            <div class="flex items-center justify-between mb-1">
                                <p class="text-xs font-bold uppercase tracking-widest text-indigo-200">QRIS Universal</p>
                                <span class="text-xs bg-white/20 px-2.5 py-1 rounded-full font-semibold">{{ $channelLabel }}</span>
                            </div>
                            <p class="text-2xl font-black">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                            <p class="text-indigo-200 text-xs mt-1">Scan QR dengan aplikasi apapun yang mendukung QRIS</p>
                        </div>

                        <div class="p-8 flex flex-col items-center">
                            {{-- Fake QR Code using SVG pattern --}}
                            <div class="qr-pulse rounded-3xl border-2 border-indigo-100 bg-white p-5 mb-6 shadow-md">
                                {{-- Render real QR code for the invoice number --}}
                                <img src="{{ \App\Support\QrCode::svgDataUri($order->invoice_number, 208) }}"
                                     alt="QR Code Pembayaran" class="w-52 h-52" draggable="false">
                            </div>

                            {{-- QRIS badge row --}}
                            <div class="flex items-center gap-3 flex-wrap justify-center mb-5">
                                <span class="text-xs font-bold px-3 py-1.5 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-100">GoPay</span>
                                <span class="text-xs font-bold px-3 py-1.5 rounded-full bg-blue-50 text-blue-700 border border-blue-100">OVO</span>
                                <span class="text-xs font-bold px-3 py-1.5 rounded-full bg-sky-50 text-sky-700 border border-sky-100">DANA</span>
                                <span class="text-xs font-bold px-3 py-1.5 rounded-full bg-orange-50 text-orange-700 border border-orange-100">ShopeePay</span>
                                <span class="text-xs font-bold px-3 py-1.5 rounded-full bg-slate-50 text-slate-700 border border-slate-100">m-BCA & semua Bank</span>
                            </div>

                            <p class="text-xs text-slate-400 text-center max-w-xs leading-relaxed">
                                Kode QR di atas hanya berlaku untuk pembayaran ini. Nominal sudah tertera secara otomatis.
                            </p>
                        </div>
                    </div>
                    @endif

                    {{-- ===== VIRTUAL ACCOUNT DISPLAY ===== --}}
                    @if($isVA)
                    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                        {{-- Bank Card Visual --}}
                        <div class="bank-card {{ $bankCardClass }} p-7 text-white min-h-[160px] flex flex-col justify-between">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-widest text-white/60 mb-1">Virtual Account Number</p>
                                    <p class="text-white/90 text-sm font-semibold">{{ $bankLabel }}</p>
                                </div>
                                {{-- Bank logo placeholder --}}
                                <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center font-black text-lg">
                                    {{ strtoupper(substr($channel, 3, 3)) }}
                                </div>
                            </div>
                            <div class="mt-6">
                                <p class="va-number text-2xl font-black text-white tracking-widest">{{ $vaNumber }}</p>
                            </div>
                        </div>

                        {{-- Copy Section --}}
                        <div class="p-6 border-b border-slate-100">
                            <p class="text-xs text-slate-500 mb-3 font-semibold uppercase tracking-wider">Nomor Virtual Account</p>
                            <div class="flex items-center gap-3 bg-slate-50 border border-slate-200 rounded-2xl p-4">
                                <p class="va-number flex-1 text-xl font-black text-slate-900 tracking-widest">{{ $vaNumber }}</p>
                                <button @click="copyToClipboard('{{ str_replace('-', '', $vaNumber) }}')"
                                        class="copy-btn shrink-0 flex items-center gap-1.5 px-4 py-2 bg-indigo-600 text-white text-xs font-bold rounded-xl hover:bg-indigo-700 transition-colors shadow-sm">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0013.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 01-.75.75H9a.75.75 0 01-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 01-2.25 2.25H6.75A2.25 2.25 0 014.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 011.927-.184"/></svg>
                                    Salin
                                </button>
                            </div>
                        </div>

                        {{-- Amount row --}}
                        <div class="px-6 py-4 flex items-center justify-between">
                            <p class="text-sm text-slate-500">Total Tagihan</p>
                            <div class="flex items-center gap-3">
                                <p class="font-black text-xl text-indigo-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                                <button @click="copyToClipboard('{{ $order->total_amount }}')"
                                        class="copy-btn text-xs text-slate-400 hover:text-indigo-600 transition-colors border border-slate-200 rounded-lg px-2 py-1">
                                    Salin
                                </button>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- ===== E-WALLET DISPLAY ===== --}}
                    @if($isWallet)
                    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                        <div class="p-7 bg-gradient-to-br from-violet-600 to-purple-700 text-white">
                            <p class="text-xs font-bold uppercase tracking-widest text-violet-200 mb-1">{{ $channelLabel }}</p>
                            <p class="text-3xl font-black">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                            <p class="text-violet-200 text-xs mt-2">Transfer ke nomor terdaftar berikut</p>
                        </div>
                        <div class="p-6 border-b border-slate-100">
                            <p class="text-xs text-slate-500 mb-3 font-semibold uppercase tracking-wider">Nomor Tujuan</p>
                            <div class="flex items-center gap-3 bg-slate-50 border border-slate-200 rounded-2xl p-4">
                                <p class="flex-1 text-xl font-black text-slate-900 tracking-wider">{{ $walletNumber }}</p>
                                <button @click="copyToClipboard('{{ $walletNumber }}')"
                                        class="copy-btn shrink-0 flex items-center gap-1.5 px-4 py-2 bg-violet-600 text-white text-xs font-bold rounded-xl hover:bg-violet-700 transition-colors shadow-sm">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0013.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 01-.75.75H9a.75.75 0 01-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 01-2.25 2.25H6.75A2.25 2.25 0 014.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 011.927-.184"/></svg>
                                    Salin
                                </button>
                            </div>
                        </div>
                        <div class="px-6 py-4 flex items-center justify-between">
                            <p class="text-sm text-slate-500">Nominal</p>
                            <p class="font-black text-xl text-violet-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    @endif

                    {{-- Step-by-step Instructions --}}
                    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">
                        <p class="text-sm font-bold text-slate-900 mb-5 flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs font-black shrink-0">?</span>
                            Cara Pembayaran
                        </p>
                        <ol class="space-y-4">
                            @foreach($instructions as $i => $step)
                            <li class="flex gap-4 items-start">
                                <span class="shrink-0 w-7 h-7 rounded-full bg-indigo-600 text-white flex items-center justify-center text-xs font-black">{{ $i + 1 }}</span>
                                <p class="text-sm text-slate-600 leading-relaxed pt-0.5">{!! $step !!}</p>
                            </li>
                            @endforeach
                        </ol>
                    </div>

                </div>

                {{-- ===== RIGHT: ORDER SUMMARY + ACTIONS ===== --}}
                <div class="space-y-5 lg:sticky lg:top-24">

                    {{-- Order Summary Card --}}
                    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                        {{-- Event Thumbnail Header --}}
                        @if($event)
                        <div class="relative h-28 bg-slate-200 overflow-hidden">
                            @if($event->banner_image)
                                <img src="{{ asset('storage/' . $event->banner_image) }}" alt="" class="w-full h-full object-cover">
                            @else
                                <img src="https://picsum.photos/seed/{{ $event->id }}/800/300" alt="" class="w-full h-full object-cover">
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                            <div class="absolute bottom-0 left-0 right-0 px-5 pb-4">
                                <p class="text-white font-black text-base leading-tight line-clamp-2">{{ $event->title }}</p>
                                <p class="text-white/70 text-xs mt-0.5">{{ $event->start_time->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                        @endif

                        <div class="p-5 space-y-3 border-b border-slate-100">
                            <div class="flex justify-between text-sm">
                                <span class="text-slate-500">Invoice</span>
                                <span class="font-mono font-bold text-slate-900 text-xs">{{ $order->invoice_number }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-slate-500">Kategori Tiket</span>
                                <span class="font-semibold text-slate-900">{{ $order->ticketCategory->name }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-slate-500">Jumlah</span>
                                <span class="font-semibold text-slate-900">{{ $order->quantity }} Tiket</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-slate-500">Metode</span>
                                <span class="font-semibold text-slate-900">{{ $channelLabel }}</span>
                            </div>
                        </div>

                        <div class="px-5 py-4 flex items-center justify-between bg-indigo-50">
                            <span class="font-black text-slate-900">Total</span>
                            <span class="font-black text-2xl text-indigo-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    {{-- Simulation Notice --}}
                    <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4 flex gap-3 items-start">
                        <svg class="w-5 h-5 shrink-0 text-amber-500 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
                        <div>
                            <p class="text-xs font-bold text-amber-800 mb-0.5">Mode Simulasi</p>
                            <p class="text-xs text-amber-700 leading-relaxed">Tidak ada uang sungguhan yang ditagih. Gunakan tombol di bawah untuk mensimulasikan hasil pembayaran.</p>
                        </div>
                    </div>

                    {{-- Pay Error --}}
                    @if(session('pay_error'))
                    <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 p-4 flex gap-3 items-start">
                        <svg class="w-5 h-5 shrink-0 text-rose-500 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
                        <div>
                            <p class="text-sm font-bold text-rose-800 mb-0.5">Pembayaran Gagal</p>
                            <p class="text-xs text-rose-700">{{ session('pay_error') }}</p>
                        </div>
                    </div>
                    @endif

                    {{-- Action Buttons --}}
                    <div class="space-y-3">
                        <form method="POST" action="{{ route('checkout.pay', $order->id) }}" x-data="{ loading: false }" @submit="loading = true">
                            @csrf
                            <button type="submit"
                                    :disabled="loading || expired"
                                    :class="loading || expired ? 'opacity-60 cursor-not-allowed' : 'hover:shadow-lg hover:-translate-y-0.5'"
                                    class="w-full flex items-center justify-center gap-2.5 px-5 py-4 bg-indigo-600 text-white font-bold rounded-2xl transition-all duration-200 shadow-md shadow-indigo-200 text-sm">
                                <span x-show="!loading">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </span>
                                <svg x-cloak x-show="loading" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg>
                                <span x-text="loading ? 'Memproses...' : '✓ Simulasi Pembayaran Berhasil'">✓ Simulasi Pembayaran Berhasil</span>
                            </button>
                        </form>

                        <a href="{{ route('home') }}"
                           class="w-full flex items-center justify-center gap-2 px-5 py-3.5 bg-white border border-slate-300 text-slate-600 text-sm font-semibold rounded-2xl hover:bg-slate-50 hover:border-slate-400 transition-all duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            Bayar Nanti / Batalkan
                        </a>
                    </div>

                    {{-- Security badges --}}
                    <div class="flex items-center justify-center gap-4 py-2">
                        <div class="flex items-center gap-1.5 text-xs text-slate-400">
                            <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
                            SSL Secured
                        </div>
                        <div class="flex items-center gap-1.5 text-xs text-slate-400">
                            <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                            PCI DSS Compliant
                        </div>
                    </div>

                </div>
            </div>
        </main>

    </body>
</html>
