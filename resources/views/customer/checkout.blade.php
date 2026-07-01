<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Checkout - {{ $event->title }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            [x-cloak] { display: none !important; }
        </style>
    </head>
    <body class="font-sans antialiased bg-slate-50 text-slate-900">
        <div class="page-shell flex min-h-screen flex-col">
            <div class="page-content">
                <x-public-navigation />

                <main
                    class="mx-auto w-full max-w-4xl px-4 py-8 sm:px-6 lg:px-8 lg:py-12"
                    x-data="{
                        step: {{ old('quantity') || $errors->any() ? 3 : 1 }},
                        quantity: {{ (int) old('quantity', 1) }},
                        selectedPaymentMethod: @js(old('payment_method', 'qris')),
                        selectedPaymentChannel: @js(old('payment_channel', 'qris_universal')),
                        paymentChannels: {
                            qris: [
                                { value: 'qris_universal', label: 'QRIS Universal', description: 'Scan QR dengan aplikasi apapun', icon: 'qr_code_scanner' }
                            ],
                            virtual_account: [
                                { value: 'va_bca', label: 'BCA Virtual Account', description: 'Pay via BCA ATM/Mobile', icon: 'account_balance' },
                                { value: 'va_mandiri', label: 'Mandiri Virtual Account', description: 'Pay via Livin by Mandiri', icon: 'account_balance' },
                                { value: 'va_bni', label: 'BNI Virtual Account', description: 'Pay via BNI Mobile', icon: 'account_balance' },
                                { value: 'va_bri', label: 'BRI Virtual Account', description: 'Pay via BRImo', icon: 'account_balance' }
                            ],
                            e_wallet: [
                                { value: 'wallet_gopay', label: 'GoPay', description: 'Bayar instan via aplikasi', icon: 'account_balance_wallet' },
                                { value: 'wallet_ovo', label: 'OVO', description: 'Bayar instan via aplikasi', icon: 'account_balance_wallet' },
                                { value: 'wallet_dana', label: 'DANA', description: 'Bayar instan via aplikasi', icon: 'account_balance_wallet' },
                                { value: 'wallet_shopeepay', label: 'ShopeePay', description: 'Bayar instan via aplikasi', icon: 'account_balance_wallet' }
                            ]
                        },
                        platformFee: 0,
                        pricePerTicket: {{ (float) $ticketCategory->price }},
                        get subtotal() { return this.pricePerTicket * (Number(this.quantity) || 0); },
                        get grandTotal() { return this.subtotal + this.platformFee; },
                        formatCurrency(value) {
                            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(value || 0);
                        },
                        paymentLabel() {
                            const labels = { qris: 'QRIS', virtual_account: 'Virtual Account', e_wallet: 'E-Wallet' };
                            return labels[this.selectedPaymentMethod] || this.selectedPaymentMethod;
                        },
                        paymentChannelLabel() {
                            const selectedChannel = this.paymentChannels[this.selectedPaymentMethod]?.find((channel) => channel.value === this.selectedPaymentChannel);
                            return selectedChannel?.label || 'Select option';
                        },
                        nextStep() { if(this.step < 3) this.step++; },
                        prevStep() { if(this.step > 1) this.step--; }
                    }"
                >
                    <div class="mb-8 text-center">
                        @if($errors->any())
                            <div class="mb-6 mx-auto max-w-lg rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-800 text-left flex gap-3 items-start shadow-sm">
                                <span class="material-symbols-outlined shrink-0 text-rose-600">error</span>
                                <div>
                                    <strong class="block mb-0.5 text-rose-900">Gagal memproses pesanan:</strong>
                                    <ul class="list-disc list-inside">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif
                        <div class="inline-flex items-center justify-center space-x-4">
                            <!-- Progress Bar -->
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm transition-colors" :class="step >= 1 ? 'bg-indigo-600 text-white shadow-md' : 'bg-slate-200 text-slate-500'">1</div>
                                <div class="w-16 h-1 transition-colors" :class="step >= 2 ? 'bg-indigo-600' : 'bg-slate-200'"></div>
                                <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm transition-colors" :class="step >= 2 ? 'bg-indigo-600 text-white shadow-md' : 'bg-slate-200 text-slate-500'">2</div>
                                <div class="w-16 h-1 transition-colors" :class="step >= 3 ? 'bg-indigo-600' : 'bg-slate-200'"></div>
                                <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm transition-colors" :class="step >= 3 ? 'bg-indigo-600 text-white shadow-md' : 'bg-slate-200 text-slate-500'">3</div>
                            </div>
                        </div>
                    </div>

                    <div class="clean-card bg-white border border-slate-200 shadow-xl overflow-hidden relative">
                        <form action="{{ route('checkout.store') }}" method="POST" x-data="{ isSubmitting: false }" @submit="if(isSubmitting) $event.preventDefault(); isSubmitting = true;">
                            @csrf
                            <input type="hidden" name="ticket_category_id" value="{{ $ticketCategory->id }}">
                            <input type="hidden" name="payment_method" :value="selectedPaymentMethod">
                            <input type="hidden" name="payment_channel" :value="selectedPaymentChannel">

                            <!-- Step 1: Ticket Details -->
                            <div x-show="step === 1" x-transition.opacity.duration.300ms class="p-8 sm:p-12">
                                <h2 class="text-3xl font-black text-slate-900 text-center mb-2">Detail Tiket</h2>
                                <p class="text-slate-500 text-center mb-10">Tinjau pilihan Anda dan tentukan jumlah tiket</p>
                                
                                <div class="rounded-3xl border border-indigo-100 bg-indigo-50/50 p-6 mb-8 relative overflow-hidden">
                                    <div class="absolute -right-10 -top-10 opacity-10">
                                        <span class="material-symbols-outlined text-indigo-600" style="line-height:1;">local_activity</span>
                                    </div>
                                    <div class="relative z-10 flex flex-col sm:flex-row gap-6 items-start sm:items-center">
                                        <div class="h-24 aspect-video rounded-2xl bg-slate-200 overflow-hidden shrink-0 shadow-sm border border-white">
                                            @if($event->banner_image)
                                                <img src="{{ asset('storage/' . $event->banner_image) }}" class="w-full h-full object-cover">
                                            @else
                                                <img src="https://picsum.photos/seed/{{ $event->id }}/800/600" class="w-full h-full object-cover">
                                            @endif
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-[10px] font-black uppercase tracking-widest text-indigo-600">{{ $ticketCategory->name }}</p>
                                            <h3 class="text-xl font-black text-slate-900 mt-1">{{ $event->title }}</h3>
                                            <p class="text-sm text-slate-600 mt-1 font-medium">{{ $event->start_time->format('D, d M Y • H:i') }}</p>
                                        </div>
                                        <div class="text-left sm:text-right w-full sm:w-auto border-t sm:border-t-0 border-slate-200 pt-4 sm:pt-0">
                                            <p class="text-xs font-bold uppercase tracking-widest text-slate-400">Harga per tiket</p>
                                            <p class="text-2xl font-black text-indigo-600">Rp {{ number_format($ticketCategory->price, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between border-b border-slate-100 pb-6 mb-6">
                                    <div>
                                        <p class="font-bold text-slate-900">Pilih Jumlah</p>
                                        <p class="text-sm text-slate-500">Maksimal {{ $maxQuantity }} tiket per transaksi</p>
                                    </div>
                                    <div class="flex items-center gap-4 bg-slate-50 border border-slate-200 rounded-full p-1">
                                        <button type="button" @click="if(quantity > 1) quantity--" class="w-10 h-10 rounded-full bg-white shadow-sm flex items-center justify-center text-slate-600 hover:text-indigo-600 transition-transform active:scale-[0.90] focus:ring-2 focus:ring-indigo-500">-</button>
                                        <input type="number" name="quantity" x-model.number="quantity" class="w-12 text-center bg-transparent border-none font-black text-xl text-slate-900 focus:ring-0 p-0" readonly>
                                        <button type="button" @click="if(quantity < {{ $maxQuantity }}) quantity++" class="w-10 h-10 rounded-full bg-white shadow-sm flex items-center justify-center text-slate-600 hover:text-indigo-600 transition-transform active:scale-[0.90] focus:ring-2 focus:ring-indigo-500">+</button>
                                    </div>
                                </div>

                                <div class="flex justify-between items-center mt-10">
                                    <a href="{{ route('events.show', $event) }}" class="text-sm font-bold text-slate-500 hover:text-slate-900 transition-colors flex items-center gap-1"><span>&larr;</span> Kembali ke Event</a>
                                    <button type="button" @click="nextStep()" class="primary-button group">
                                        Lanjut ke Pembayaran 
                                        <span class="inline-block transform group-hover:translate-x-1 transition-transform ml-1">→</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Step 2: Payment Method -->
                            <div x-cloak x-show="step === 2" x-transition.opacity.duration.300ms class="p-8 sm:p-12">
                                <h2 class="text-3xl font-black text-slate-900 text-center mb-2">Metode Pembayaran</h2>
                                <p class="text-slate-500 text-center mb-10">Pilih cara pembayaran Anda (Simulasi)</p>

                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
                                    <button type="button" 
                                            @click="selectedPaymentMethod = 'qris'; selectedPaymentChannel = paymentChannels['qris'][0].value"
                                            class="p-5 rounded-3xl border-2 transition-all duration-150 ease-out-ui flex flex-col items-center text-center group active:scale-[0.98]"
                                            :class="selectedPaymentMethod === 'qris' ? 'border-indigo-500 bg-indigo-50' : 'border-slate-100 hover:border-slate-200'">
                                        <span class="material-symbols-outlined text-4xl mb-3 transform group-hover:scale-110 transition-transform" style="line-height:1;">qr_code_scanner</span>
                                        <span class="font-black" :class="selectedPaymentMethod === 'qris' ? 'text-indigo-700' : 'text-slate-900'">QRIS</span>
                                        <span class="text-xs mt-1" :class="selectedPaymentMethod === 'qris' ? 'text-indigo-500' : 'text-slate-500'">Scan instan</span>
                                    </button>

                                    <button type="button" 
                                            @click="selectedPaymentMethod = 'virtual_account'; selectedPaymentChannel = paymentChannels['virtual_account'][0].value"
                                            class="p-5 rounded-3xl border-2 transition-all duration-150 ease-out-ui flex flex-col items-center text-center group active:scale-[0.98]"
                                            :class="selectedPaymentMethod === 'virtual_account' ? 'border-indigo-500 bg-indigo-50' : 'border-slate-100 hover:border-slate-200'">
                                        <span class="material-symbols-outlined text-4xl mb-3 transform group-hover:scale-110 transition-transform" style="line-height:1;">account_balance</span>
                                        <span class="font-black" :class="selectedPaymentMethod === 'virtual_account' ? 'text-indigo-700' : 'text-slate-900'">Virtual Account</span>
                                        <span class="text-xs mt-1" :class="selectedPaymentMethod === 'virtual_account' ? 'text-indigo-500' : 'text-slate-500'">Transfer Bank</span>
                                    </button>

                                    <button type="button" 
                                            @click="selectedPaymentMethod = 'e_wallet'; selectedPaymentChannel = paymentChannels['e_wallet'][0].value"
                                            class="p-5 rounded-3xl border-2 transition-all duration-150 ease-out-ui flex flex-col items-center text-center group active:scale-[0.98]"
                                            :class="selectedPaymentMethod === 'e_wallet' ? 'border-indigo-500 bg-indigo-50' : 'border-slate-100 hover:border-slate-200'">
                                        <span class="material-symbols-outlined text-4xl mb-3 transform group-hover:scale-110 transition-transform" style="line-height:1;">account_balance_wallet</span>
                                        <span class="font-black" :class="selectedPaymentMethod === 'e_wallet' ? 'text-indigo-700' : 'text-slate-900'">E-Wallet</span>
                                        <span class="text-xs mt-1" :class="selectedPaymentMethod === 'e_wallet' ? 'text-indigo-500' : 'text-slate-500'">Aplikasi Dompet</span>
                                    </button>
                                </div>

                                <div class="space-y-3">
                                    <p class="text-sm font-bold text-slate-900">Pilih Penyedia</p>
                                    <template x-for="channel in paymentChannels[selectedPaymentMethod]" :key="channel.value">
                                        <label class="flex items-center gap-4 p-4 rounded-2xl border-2 cursor-pointer transition-all duration-150 ease-out-ui active:scale-[0.98]"
                                               :class="selectedPaymentChannel === channel.value ? 'border-indigo-500 bg-white shadow-md' : 'border-slate-100 hover:border-slate-200 bg-slate-50'">
                                            <div class="relative flex items-center justify-center">
                                                <input type="radio" name="temp_channel" :value="channel.value" x-model="selectedPaymentChannel" class="peer sr-only">
                                                <div class="w-6 h-6 rounded-full border-2 border-slate-300 peer-checked:border-indigo-500 peer-checked:bg-indigo-500 transition-all flex items-center justify-center">
                                                    <div class="w-2.5 h-2.5 rounded-full bg-white scale-0 peer-checked:scale-100 transition-transform"></div>
                                                </div>
                                            </div>
                                            <span class="material-symbols-outlined text-3xl text-slate-400 peer-checked:text-indigo-600 transition-colors" x-text="channel.icon" style="line-height:1;"></span>
                                            <div class="flex-1">
                                                <p class="font-bold text-slate-900" x-text="channel.label"></p>
                                                <p class="text-xs text-slate-500" x-text="channel.description"></p>
                                            </div>
                                        </label>
                                    </template>
                                </div>

                                <div class="flex justify-between items-center mt-10">
                                    <button type="button" @click="prevStep()" class="text-sm font-bold text-slate-500 hover:text-slate-900 transition-colors flex items-center gap-1">
                                        <span>←</span> Kembali
                                    </button>
                                    <button type="button" @click="nextStep()" class="primary-button group">
                                        Tinjau Pesanan 
                                        <span class="inline-block transform group-hover:translate-x-1 transition-transform ml-1">→</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Step 3: Review & Confirm -->
                            <div x-cloak x-show="step === 3" x-transition.opacity.duration.300ms class="p-8 sm:p-12">
                                <h2 class="text-3xl font-black text-slate-900 text-center mb-2">Tinjau & Konfirmasi</h2>
                                <p class="text-slate-500 text-center mb-10">Mohon tinjau detail pesanan Anda sebelum membayar</p>

                                <div class="bg-slate-50 rounded-3xl p-6 mb-8 border border-slate-200">
                                    <div class="flex items-center justify-between pb-4 border-b border-dashed border-slate-300">
                                        <div>
                                            <p class="font-bold text-slate-900">{{ $event->title }}</p>
                                            <p class="text-xs text-slate-500">{{ $ticketCategory->name }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-black text-indigo-600"><span x-text="quantity"></span>x</p>
                                        </div>
                                    </div>
                                    
                                    <div class="py-4 space-y-3">
                                        <div class="flex justify-between text-sm">
                                            <span class="text-slate-500">Subtotal</span>
                                            <span class="font-bold text-slate-900" x-text="formatCurrency(subtotal)"></span>
                                        </div>
                                        <div class="flex justify-between text-sm">
                                            <span class="text-slate-500">Biaya Layanan</span>
                                            <span class="font-bold text-slate-900">Gratis</span>
                                        </div>
                                        <div class="flex justify-between text-sm">
                                            <span class="text-slate-500">Metode Pembayaran</span>
                                            <span class="font-bold text-indigo-600" x-text="paymentChannelLabel()"></span>
                                        </div>
                                    </div>

                                    <div class="pt-4 border-t border-dashed border-slate-300 flex items-end justify-between">
                                        <span class="text-lg font-black text-slate-900">Total</span>
                                        <span class="text-3xl font-black text-indigo-600" x-text="formatCurrency(grandTotal)"></span>
                                    </div>
                                </div>

                                <div class="rounded-2xl border border-blue-200 bg-blue-50 p-4 mb-8 flex gap-3 items-start">
                                    <span class="material-symbols-outlined text-xl text-blue-500 shrink-0 mt-0.5" style="line-height:1;">info</span>
                                    <p class="text-xs text-blue-700 leading-relaxed">
                                        <strong>Mode Simulasi:</strong> Tidak ada uang sungguhan yang ditagih. Ini akan membuat pesanan berhasil dan menghasilkan tiket digital Anda secara instan.
                                    </p>
                                </div>

                                <div class="flex flex-col-reverse sm:flex-row justify-between items-center mt-10 gap-4 sm:gap-0">
                                    <button type="button" @click="prevStep()" class="text-sm font-bold text-slate-500 hover:text-slate-900 transition-colors flex items-center gap-1 w-full sm:w-auto justify-center">
                                        <span>←</span> Ubah Pembayaran
                                    </button>
                                    <button type="submit" class="primary-button w-full sm:w-auto relative overflow-hidden group" :class="{ 'opacity-75 cursor-not-allowed': isSubmitting }">
                                        <span class="relative z-10 flex items-center gap-2">
                                            <svg x-cloak x-show="isSubmitting" class="w-4 h-4 text-white animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                            <span x-text="isSubmitting ? 'Memproses...' : 'Bayar ' + formatCurrency(grandTotal)"></span>
                                        </span>
                                        <div class="absolute inset-0 bg-white/20 transform -translate-x-full group-hover:translate-x-full transition-transform duration-700 ease-out"></div>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </main>
            </div>
        </div>
    </body>
</html>