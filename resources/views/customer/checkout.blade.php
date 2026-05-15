<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Checkout - {{ $event->title }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-slate-950 text-slate-100">
        <div class="dark-page-shell flex min-h-screen flex-col">
            <div class="dark-page-content">
                <x-public-navigation :back-href="route('events.show', $event)" back-label="Back to Event" />

                <main
                    class="mx-auto w-full max-w-7xl px-4 py-8 sm:px-6 lg:px-8 lg:py-12"
                    x-data="{
                        quantity: {{ (int) old('quantity', 1) }},
                        selectedPaymentMethod: @js(old('payment_method', 'qris')),
                        selectedPaymentChannel: @js(old('payment_channel')),
                        paymentChannels: {
                            qris: [
                                { value: 'qris_bca_mobile', label: 'BCA Mobile', description: 'Scan QRIS dari m-BCA' },
                                { value: 'qris_gopay', label: 'GoPay QRIS', description: 'Scan QRIS dari GoPay' },
                                { value: 'qris_shopeepay', label: 'ShopeePay QRIS', description: 'Scan QRIS dari ShopeePay' }
                            ],
                            virtual_account: [
                                { value: 'va_bca', label: 'BCA', description: 'Virtual Account BCA' },
                                { value: 'va_mandiri', label: 'Mandiri', description: 'Virtual Account Mandiri' },
                                { value: 'va_bri', label: 'BRI', description: 'Virtual Account BRI' },
                                { value: 'va_bni', label: 'BNI', description: 'Virtual Account BNI' }
                            ],
                            e_wallet: [
                                { value: 'wallet_dana', label: 'DANA', description: 'Bayar dengan saldo DANA' },
                                { value: 'wallet_gopay', label: 'GoPay', description: 'Bayar dengan saldo GoPay' },
                                { value: 'wallet_ovo', label: 'OVO', description: 'Bayar dengan saldo OVO' },
                                { value: 'wallet_shopeepay', label: 'ShopeePay', description: 'Bayar dengan saldo ShopeePay' }
                            ]
                        },
                        platformFee: 5000,
                        pricePerTicket: {{ (float) $ticketCategory->price }},
                        init() { this.syncPaymentChannel(); },
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
                        paymentDisplayLabel() { return this.paymentLabel() + ' - ' + this.paymentChannelLabel(); },
                        selectPaymentMethod(method) {
                            this.selectedPaymentMethod = method;
                            this.selectedPaymentChannel = this.paymentChannels[method]?.[0]?.value || '';
                        },
                        syncPaymentChannel() {
                            const channels = this.paymentChannels[this.selectedPaymentMethod] || [];
                            const selectedStillAvailable = channels.some((channel) => channel.value === this.selectedPaymentChannel);
                            if (! selectedStillAvailable) this.selectedPaymentChannel = channels[0]?.value || '';
                        }
                    }"
                >
                    <div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                        <div>
                            <div class="neon-chip mb-4 w-fit">
                                <span class="h-2 w-2 rounded-full bg-cyan-300 shadow-lg shadow-cyan-300/70"></span>
                                Secure checkout
                            </div>
                            <h1 class="text-4xl font-black tracking-tight text-white sm:text-5xl">Complete your neon pass</h1>
                            <p class="mt-3 max-w-2xl text-slate-400">Pilih jumlah tiket dan metode pembayaran simulasi dengan alur yang lebih jelas, ringkas, dan responsif.</p>
                        </div>
                        <a href="{{ route('events.show', $event) }}" class="neon-button-outline w-fit">Cancel order</a>
                    </div>

                    <div class="grid grid-cols-1 gap-8 lg:grid-cols-[1.35fr_0.65fr]">
                        <section class="neon-card p-6 sm:p-8">
                            <div class="mb-8 grid gap-4 md:grid-cols-[1fr_auto] md:items-start">
                                <div>
                                    <p class="text-xs font-black uppercase tracking-[0.28em] text-fuchsia-200">Event</p>
                                    <h2 class="mt-3 text-2xl font-black text-white sm:text-3xl">{{ $event->title }}</h2>
                                    <div class="mt-4 grid gap-3 text-sm text-slate-300 sm:grid-cols-2">
                                        <p class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3">📅 {{ $event->start_time->format('M d, Y \a\t h:i A') }}</p>
                                        <p class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3">📍 {{ $event->location }}</p>
                                    </div>
                                </div>
                                <div class="rounded-3xl border border-cyan-300/20 bg-cyan-300/10 p-5 text-right">
                                    <p class="text-xs font-black uppercase tracking-widest text-cyan-200">{{ $ticketCategory->name }}</p>
                                    <p class="mt-2 text-2xl font-black text-white">Rp {{ number_format($ticketCategory->price, 0, ',', '.') }}</p>
                                    <p class="mt-1 text-xs text-slate-400">{{ $available }} available</p>
                                </div>
                            </div>

                            <form action="{{ route('checkout.store') }}" method="POST" class="space-y-8">
                                @csrf

                                <input type="hidden" name="ticket_category_id" value="{{ $ticketCategory->id }}">
                                <input type="hidden" name="payment_method" x-model="selectedPaymentMethod">
                                <input type="hidden" name="payment_channel" x-model="selectedPaymentChannel">

                                <div class="rounded-3xl border border-white/10 bg-slate-950/50 p-5">
                                    <label for="quantity" class="block text-sm font-black uppercase tracking-widest text-cyan-200">
                                        Ticket Quantity
                                    </label>
                                    <div class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-center">
                                        <select id="quantity" name="quantity" class="dark-select w-full sm:w-48" x-model.number="quantity" required>
                                            @for($i = 1; $i <= $maxQuantity; $i++)
                                                <option value="{{ $i }}">{{ $i }} @if($i > 1) Tickets @else Ticket @endif</option>
                                            @endfor
                                        </select>
                                        <p class="text-sm text-slate-400">Max {{ $maxQuantity }} per transaction</p>
                                    </div>
                                    @error('quantity')
                                        <p class="mt-2 text-sm font-bold text-rose-300">{{ $message }}</p>
                                    @enderror
                                </div>

                                <section class="space-y-4">
                                    <div>
                                        <h2 class="text-xl font-black text-white">Select Payment Method</h2>
                                        <p class="mt-1 text-sm text-slate-400">Choose a simulated payment channel to complete this order.</p>
                                    </div>

                                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                                        <button type="button" class="rounded-3xl border p-4 text-left transition hover:-translate-y-1" :class="selectedPaymentMethod === 'qris' ? 'border-cyan-300/60 bg-cyan-300/15 shadow-lg shadow-cyan-500/10' : 'border-white/10 bg-white/5 hover:border-cyan-300/30'" @click="selectPaymentMethod('qris')">
                                            <span class="text-3xl" aria-hidden="true">📱</span>
                                            <span class="mt-4 block font-black text-white">QRIS</span>
                                            <span class="mt-1 block text-xs text-slate-400">Instant scan</span>
                                        </button>

                                        <button type="button" class="rounded-3xl border p-4 text-left transition hover:-translate-y-1" :class="selectedPaymentMethod === 'virtual_account' ? 'border-fuchsia-300/60 bg-fuchsia-300/15 shadow-lg shadow-fuchsia-500/10' : 'border-white/10 bg-white/5 hover:border-fuchsia-300/30'" @click="selectPaymentMethod('virtual_account')">
                                            <span class="text-3xl" aria-hidden="true">🏦</span>
                                            <span class="mt-4 block font-black text-white">Virtual Account</span>
                                            <span class="mt-1 block text-xs text-slate-400">BCA, Mandiri, BRI</span>
                                        </button>

                                        <button type="button" class="rounded-3xl border p-4 text-left transition hover:-translate-y-1" :class="selectedPaymentMethod === 'e_wallet' ? 'border-lime-300/60 bg-lime-300/15 shadow-lg shadow-lime-500/10' : 'border-white/10 bg-white/5 hover:border-lime-300/30'" @click="selectPaymentMethod('e_wallet')">
                                            <span class="text-3xl" aria-hidden="true">💳</span>
                                            <span class="mt-4 block font-black text-white">E-Wallet</span>
                                            <span class="mt-1 block text-xs text-slate-400">GoPay, OVO, DANA</span>
                                        </button>
                                    </div>
                                    @error('payment_method')
                                        <p class="mt-2 text-sm font-bold text-rose-300">{{ $message }}</p>
                                    @enderror
                                </section>

                                <section class="space-y-4">
                                    <div>
                                        <h2 class="text-xl font-black text-white">Select Payment Option</h2>
                                        <p class="mt-1 text-sm text-slate-400" x-text="'Choose an option for ' + paymentLabel() + '.'"></p>
                                    </div>

                                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                                        <template x-for="channel in paymentChannels[selectedPaymentMethod]" :key="channel.value">
                                            <button type="button" class="rounded-3xl border p-4 text-left transition hover:-translate-y-1" :class="selectedPaymentChannel === channel.value ? 'border-cyan-300/60 bg-cyan-300/15 shadow-lg shadow-cyan-500/10' : 'border-white/10 bg-white/5 hover:border-cyan-300/30'" @click="selectedPaymentChannel = channel.value">
                                                <span class="block font-black text-white" x-text="channel.label"></span>
                                                <span class="mt-1 block text-xs text-slate-400" x-text="channel.description"></span>
                                            </button>
                                        </template>
                                    </div>
                                    @error('payment_channel')
                                        <p class="mt-2 text-sm font-bold text-rose-300">{{ $message }}</p>
                                    @enderror
                                </section>

                                <section class="rounded-3xl border border-white/10 bg-slate-950/60 p-5">
                                    <div class="space-y-3 text-sm">
                                        <div class="flex justify-between gap-4">
                                            <p class="text-slate-400">Subtotal</p>
                                            <p class="font-black text-white" x-text="formatCurrency(subtotal)"></p>
                                        </div>
                                        <div class="flex justify-between gap-4">
                                            <p class="text-slate-400">Platform Fee</p>
                                            <p class="font-black text-white" x-text="formatCurrency(platformFee)"></p>
                                        </div>
                                        <div class="flex justify-between gap-4 border-b border-white/10 pb-3">
                                            <p class="text-slate-400">Quantity</p>
                                            <p class="font-black text-white"><span x-text="quantity"></span> ticket(s)</p>
                                        </div>
                                        <div class="flex items-end justify-between gap-4 pt-1">
                                            <p class="text-lg font-black text-white">Grand Total</p>
                                            <p class="text-3xl font-black text-cyan-200" x-text="formatCurrency(grandTotal)"></p>
                                        </div>
                                    </div>
                                </section>

                                <div class="rounded-3xl border border-blue-300/20 bg-blue-300/10 p-4">
                                    <p class="text-sm text-blue-100"><strong>ℹ Payment Note:</strong> This is a simulated checkout. Your order will be created immediately and your e-tickets will be generated.</p>
                                </div>

                                <button type="submit" class="neon-button w-full py-4 text-base" x-text="'Pay with ' + paymentDisplayLabel().toUpperCase() + ' (Simulated)'"></button>
                            </form>
                        </section>

                        <aside class="space-y-6 lg:sticky lg:top-28 lg:self-start">
                            <section class="neon-card p-6">
                                <p class="text-xs font-black uppercase tracking-[0.28em] text-fuchsia-200">Payment Summary</p>
                                <h3 class="mt-2 text-2xl font-black text-white">Order pulse</h3>

                                <div class="mt-6 space-y-4 text-sm">
                                    <div class="flex justify-between gap-4">
                                        <span class="text-slate-400">Price per ticket</span>
                                        <span class="font-black text-white">Rp {{ number_format($ticketCategory->price, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between gap-4">
                                        <span class="text-slate-400">Quantity</span>
                                        <span class="font-black text-white" x-text="quantity"></span>
                                    </div>
                                    <div class="flex justify-between gap-4">
                                        <span class="text-slate-400">Subtotal</span>
                                        <span class="font-black text-white" x-text="formatCurrency(subtotal)"></span>
                                    </div>
                                    <div class="flex justify-between gap-4">
                                        <span class="text-slate-400">Platform Fee</span>
                                        <span class="font-black text-white" x-text="formatCurrency(platformFee)"></span>
                                    </div>
                                    <div class="flex justify-between gap-4">
                                        <span class="text-slate-400">Payment</span>
                                        <span class="max-w-40 text-right font-black text-white" x-text="paymentDisplayLabel()"></span>
                                    </div>
                                    <div class="border-t border-white/10 pt-4">
                                        <div class="flex items-end justify-between gap-4">
                                            <span class="text-lg font-black text-white">Grand Total</span>
                                            <span class="text-2xl font-black text-cyan-200" x-text="formatCurrency(grandTotal)"></span>
                                        </div>
                                    </div>
                                </div>
                            </section>

                            <section class="glass-panel rounded-3xl p-5 text-sm">
                                <div class="space-y-3 text-slate-300">
                                    <div class="flex items-start gap-3">
                                        <span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-lime-300 text-xs font-black text-slate-950">✓</span>
                                        <p><strong class="text-white">Instant delivery</strong> of e-tickets</p>
                                    </div>
                                    <div class="flex items-start gap-3">
                                        <span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-cyan-300 text-xs font-black text-slate-950">✓</span>
                                        <p><strong class="text-white">Unique barcode</strong> for each ticket</p>
                                    </div>
                                    <div class="flex items-start gap-3">
                                        <span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-fuchsia-300 text-xs font-black text-slate-950">✓</span>
                                        <p><strong class="text-white">Non-refundable</strong> tickets</p>
                                    </div>
                                </div>
                            </section>
                        </aside>
                    </div>
                </main>
            </div>
        </div>
    </body>
</html>