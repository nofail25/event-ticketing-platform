<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Checkout - {{ $event->title }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <!-- Navigation -->
        <nav class="bg-gradient-to-r from-indigo-700 via-indigo-700 to-purple-700 shadow-lg shadow-indigo-900/10 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <div class="flex items-center gap-4">
                        <a href="{{ route('home') }}" class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg bg-white/15 ring-1 ring-white/25 flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                                </svg>
                            </div>
                            <span class="text-sm md:text-base font-bold text-white">EventTicketing</span>
                        </a>
                        <a href="{{ route('events.show', $event) }}" class="hidden sm:inline-flex text-sm font-medium text-indigo-100 hover:text-white transition">
                            Back to Event
                        </a>
                    </div>
                    <div class="flex items-center gap-4">
                        <a href="{{ route('customer.dashboard') }}" class="hidden sm:inline-flex items-center border-b-2 border-transparent px-1 pt-1 text-sm font-medium text-indigo-100 hover:border-white/60 hover:text-white transition">
                            My Tickets
                        </a>
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center gap-2 px-3 py-2 border border-white/20 text-sm leading-4 font-medium rounded-lg text-white bg-white/10 hover:bg-white/15 focus:outline-none transition ease-in-out duration-150">
                                    <div class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center text-white font-bold text-xs">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                    <span class="hidden sm:inline">{{ Auth::user()->name }}</span>
                                    <svg class="fill-current h-4 w-4 text-indigo-100" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <div class="px-4 py-2 border-b border-gray-100">
                                    <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                                </div>
                                <x-dropdown-link :href="route('customer.dashboard')">
                                    My Tickets
                                </x-dropdown-link>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                        Log Out
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Checkout Container -->
        <div
            class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12"
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
                init() {
                    this.syncPaymentChannel();
                },
                get subtotal() {
                    return this.pricePerTicket * (Number(this.quantity) || 0);
                },
                get grandTotal() {
                    return this.subtotal + this.platformFee;
                },
                formatCurrency(value) {
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        maximumFractionDigits: 0
                    }).format(value || 0);
                },
                paymentLabel() {
                    const labels = {
                        qris: 'QRIS',
                        virtual_account: 'Virtual Account',
                        e_wallet: 'E-Wallet'
                    };

                    return labels[this.selectedPaymentMethod] || this.selectedPaymentMethod;
                },
                paymentChannelLabel() {
                    const selectedChannel = this.paymentChannels[this.selectedPaymentMethod]
                        ?.find((channel) => channel.value === this.selectedPaymentChannel);

                    return selectedChannel?.label || 'Select option';
                },
                paymentDisplayLabel() {
                    return this.paymentLabel() + ' - ' + this.paymentChannelLabel();
                },
                selectPaymentMethod(method) {
                    this.selectedPaymentMethod = method;
                    this.selectedPaymentChannel = this.paymentChannels[method]?.[0]?.value || '';
                },
                syncPaymentChannel() {
                    const channels = this.paymentChannels[this.selectedPaymentMethod] || [];
                    const selectedStillAvailable = channels.some((channel) => channel.value === this.selectedPaymentChannel);

                    if (! selectedStillAvailable) {
                        this.selectedPaymentChannel = channels[0]?.value || '';
                    }
                }
            }"
        >
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Order Summary -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h1 class="text-3xl font-bold text-gray-900 mb-6">Order Summary</h1>

                        <!-- Event Info -->
                        <div class="mb-6 pb-6 border-b">
                            <p class="text-sm text-gray-600 mb-2">Event</p>
                            <h2 class="text-2xl font-bold text-gray-900">{{ $event->title }}</h2>
                            <p class="text-gray-600 mt-2">
                                &#x1F4C5; {{ $event->start_time->format('M d, Y \a\t h:i A') }}
                            </p>
                            <p class="text-gray-600">
                                &#x1F4CD; {{ $event->location }}
                            </p>
                        </div>

                        <!-- Ticket Category Info -->
                        <div class="mb-6 pb-6 border-b">
                            <p class="text-sm text-gray-600 mb-2">Ticket Category</p>
                            <p class="text-xl font-semibold text-gray-900">{{ $ticketCategory->name }}</p>
                            <div class="flex items-center gap-4 mt-3">
                                <div>
                                    <p class="text-sm text-gray-600">Price per Ticket</p>
                                    <p class="text-2xl font-bold text-blue-600">Rp {{ number_format($ticketCategory->price, 0, ',', '.') }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Tickets Available</p>
                                    <p class="text-2xl font-bold text-green-600">{{ $available }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Quantity Selection -->
                        <form action="{{ route('checkout.store') }}" method="POST" class="space-y-6">
                            @csrf

                            <input type="hidden" name="ticket_category_id" value="{{ $ticketCategory->id }}">
                            <input type="hidden" name="payment_method" x-model="selectedPaymentMethod">
                            <input type="hidden" name="payment_channel" x-model="selectedPaymentChannel">

                            <div>
                                <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">
                                    How many tickets would you like?
                                </label>
                                <div class="flex items-center gap-3">
                                    <select
                                        id="quantity"
                                        name="quantity"
                                        class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        x-model.number="quantity"
                                        required
                                    >
                                        @for($i = 1; $i <= $maxQuantity; $i++)
                                            <option value="{{ $i }}">{{ $i }} @if($i > 1) Tickets @else Ticket @endif</option>
                                        @endfor
                                    </select>
                                    <p class="text-sm text-gray-600">
                                        (Max {{ $maxQuantity }} per transaction)
                                    </p>
                                </div>
                                @error('quantity')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Payment Method Selection -->
                            <div class="space-y-3">
                                <div>
                                    <h2 class="text-lg font-bold text-gray-900">Select Payment Method</h2>
                                    <p class="text-sm text-gray-600 mt-1">Choose a simulated payment channel to complete this order.</p>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                    <button
                                        type="button"
                                        class="text-left rounded-lg border p-4 transition shadow-sm hover:border-blue-300 hover:bg-blue-50/60"
                                        :class="selectedPaymentMethod === 'qris' ? 'border-blue-600 bg-blue-50 ring-2 ring-blue-100' : 'border-gray-200 bg-white'"
                                        @click="selectPaymentMethod('qris')"
                                    >
                                        <span class="text-2xl" aria-hidden="true">&#x1F4F1;</span>
                                        <span class="block font-bold text-gray-900 mt-3">QRIS</span>
                                        <span class="block text-xs text-gray-500 mt-1">Instant</span>
                                    </button>

                                    <button
                                        type="button"
                                        class="text-left rounded-lg border p-4 transition shadow-sm hover:border-blue-300 hover:bg-blue-50/60"
                                        :class="selectedPaymentMethod === 'virtual_account' ? 'border-blue-600 bg-blue-50 ring-2 ring-blue-100' : 'border-gray-200 bg-white'"
                                        @click="selectPaymentMethod('virtual_account')"
                                    >
                                        <span class="text-2xl" aria-hidden="true">&#x1F3E6;</span>
                                        <span class="block font-bold text-gray-900 mt-3">Virtual Account</span>
                                        <span class="block text-xs text-gray-500 mt-1">BCA, Mandiri, BRI</span>
                                    </button>

                                    <button
                                        type="button"
                                        class="text-left rounded-lg border p-4 transition shadow-sm hover:border-blue-300 hover:bg-blue-50/60"
                                        :class="selectedPaymentMethod === 'e_wallet' ? 'border-blue-600 bg-blue-50 ring-2 ring-blue-100' : 'border-gray-200 bg-white'"
                                        @click="selectPaymentMethod('e_wallet')"
                                    >
                                        <span class="text-2xl" aria-hidden="true">&#x1F4B3;</span>
                                        <span class="block font-bold text-gray-900 mt-3">E-Wallet</span>
                                        <span class="block text-xs text-gray-500 mt-1">GoPay, OVO, DANA</span>
                                    </button>
                                </div>
                                @error('payment_method')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Payment Channel Selection -->
                            <div class="space-y-3">
                                <div>
                                    <h2 class="text-lg font-bold text-gray-900">Select Payment Option</h2>
                                    <p class="text-sm text-gray-600 mt-1" x-text="'Choose an option for ' + paymentLabel() + '.'"></p>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <template x-for="channel in paymentChannels[selectedPaymentMethod]" :key="channel.value">
                                        <button
                                            type="button"
                                            class="text-left rounded-lg border p-4 transition shadow-sm hover:border-blue-300 hover:bg-blue-50/60"
                                            :class="selectedPaymentChannel === channel.value ? 'border-blue-600 bg-blue-50 ring-2 ring-blue-100' : 'border-gray-200 bg-white'"
                                            @click="selectedPaymentChannel = channel.value"
                                        >
                                            <span class="block font-bold text-gray-900" x-text="channel.label"></span>
                                            <span class="block text-xs text-gray-500 mt-1" x-text="channel.description"></span>
                                        </button>
                                    </template>
                                </div>
                                @error('payment_channel')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Price Breakdown -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="flex justify-between items-center mb-2">
                                    <p class="text-gray-600">Subtotal</p>
                                    <p class="font-semibold text-gray-900" x-text="formatCurrency(subtotal)"></p>
                                </div>
                                <div class="flex justify-between items-center mb-2">
                                    <p class="text-gray-600">Platform Fee</p>
                                    <p class="font-semibold text-gray-900" x-text="formatCurrency(platformFee)"></p>
                                </div>
                                <div class="flex justify-between items-center mb-3 pb-3 border-b">
                                    <p class="text-gray-600">Quantity</p>
                                    <p class="font-semibold text-gray-900"><span x-text="quantity"></span> ticket(s)</p>
                                </div>
                                <div class="flex justify-between items-center">
                                    <p class="text-lg font-semibold text-gray-900">Grand Total</p>
                                    <p class="text-3xl font-bold text-blue-600" x-text="formatCurrency(grandTotal)"></p>
                                </div>
                            </div>

                            <!-- Important Notice -->
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <p class="text-sm text-blue-800">
                                    <strong>&#x2139; Payment Note:</strong> This is a simulated checkout. Your order will be created immediately and your e-tickets will be generated.
                                </p>
                            </div>

                            <!-- Submit Button -->
                            <button
                                type="submit"
                                class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-blue-700 transition text-lg"
                                x-text="'Pay with ' + paymentDisplayLabel().toUpperCase() + ' (Simulated)'"
                            ></button>

                            <a
                                href="{{ route('events.show', $event) }}"
                                class="block w-full text-center text-gray-600 hover:text-gray-900 py-2 border border-gray-300 rounded-lg font-medium transition"
                            >
                                Cancel
                            </a>
                        </form>
                    </div>
                </div>

                <!-- Order Summary Sidebar -->
                <div>
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg border border-blue-100 p-6 sticky top-20">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Payment Summary</h3>

                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Price per ticket:</span>
                                <span class="font-semibold text-gray-900">Rp {{ number_format($ticketCategory->price, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Quantity:</span>
                                <span class="font-semibold text-gray-900">
                                    <span x-text="quantity"></span>
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Subtotal:</span>
                                <span class="font-semibold text-gray-900" x-text="formatCurrency(subtotal)"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Platform Fee:</span>
                                <span class="font-semibold text-gray-900" x-text="formatCurrency(platformFee)"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Payment:</span>
                                <span class="font-semibold text-gray-900 text-right" x-text="paymentDisplayLabel()"></span>
                            </div>
                            <div class="border-t pt-3 flex justify-between">
                                <span class="text-lg font-bold text-gray-900">Grand Total:</span>
                                <span class="text-2xl font-bold text-blue-600" x-text="formatCurrency(grandTotal)"></span>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg p-4 space-y-2 text-sm">
                            <div class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <p class="text-gray-700"><strong>Instant delivery</strong> of e-tickets</p>
                            </div>
                            <div class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <p class="text-gray-700"><strong>Unique barcode</strong> for each ticket</p>
                            </div>
                            <div class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <p class="text-gray-700"><strong>Non-refundable</strong> tickets</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </body>
</html>
