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
        <nav class="bg-white shadow-sm sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <div class="flex items-center gap-4">
                        <a href="{{ route('events.show', $event) }}" class="text-gray-600 hover:text-gray-900">
                            ← Back to Event
                        </a>
                    </div>
                    <div class="text-lg font-bold text-blue-600">Checkout</div>
                    <div></div>
                </div>
            </div>
        </nav>

        <!-- Checkout Container -->
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
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
                                📅 {{ $event->start_time->format('M d, Y \a\t h:i A') }}
                            </p>
                            <p class="text-gray-600">
                                📍 {{ $event->location }}
                            </p>
                        </div>

                        <!-- Ticket Category Info -->
                        <div class="mb-6 pb-6 border-b">
                            <p class="text-sm text-gray-600 mb-2">Ticket Category</p>
                            <p class="text-xl font-semibold text-gray-900">{{ $ticketCategory->name }}</p>
                            <div class="flex items-center gap-4 mt-3">
                                <div>
                                    <p class="text-sm text-gray-600">Price per Ticket</p>
                                    <p class="text-2xl font-bold text-blue-600">${{ number_format($ticketCategory->price, 2) }}</p>
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

                            <div>
                                <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">
                                    How many tickets would you like?
                                </label>
                                <div class="flex items-center gap-3">
                                    <select
                                        id="quantity"
                                        name="quantity"
                                        class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        onchange="updateTotal()"
                                        required
                                    >
                                        <option value="">Select quantity...</option>
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

                            <!-- Price Breakdown -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="flex justify-between items-center mb-2">
                                    <p class="text-gray-600">Subtotal</p>
                                    <p class="font-semibold text-gray-900">
                                        <span id="subtotal-price">${{ number_format($ticketCategory->price, 2) }}</span>
                                    </p>
                                </div>
                                <div class="flex justify-between items-center mb-3 pb-3 border-b">
                                    <p class="text-gray-600">Quantity</p>
                                    <p class="font-semibold text-gray-900">
                                        <span id="quantity-display">1</span> ticket(s)
                                    </p>
                                </div>
                                <div class="flex justify-between items-center">
                                    <p class="text-lg font-semibold text-gray-900">Total Price</p>
                                    <p class="text-3xl font-bold text-blue-600">
                                        $<span id="total-price">{{ number_format($ticketCategory->price, 2) }}</span>
                                    </p>
                                </div>
                            </div>

                            <!-- Important Notice -->
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <p class="text-sm text-blue-800">
                                    <strong>ℹ Payment Note:</strong> This is a simulated checkout. Your order will be created immediately and your e-tickets will be generated.
                                </p>
                            </div>

                            <!-- Submit Button -->
                            <button
                                type="submit"
                                class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-blue-700 transition text-lg"
                            >
                                Confirm & Pay (Simulated)
                            </button>

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
                                <span class="font-semibold text-gray-900">${{ number_format($ticketCategory->price, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Quantity:</span>
                                <span class="font-semibold text-gray-900">
                                    <span id="summary-qty">1</span>
                                </span>
                            </div>
                            <div class="border-t pt-3 flex justify-between">
                                <span class="text-lg font-bold text-gray-900">Total:</span>
                                <span class="text-2xl font-bold text-blue-600">
                                    $<span id="summary-total">{{ number_format($ticketCategory->price, 2) }}</span>
                                </span>
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

        <script>
            const pricePerTicket = {{ $ticketCategory->price }};

            function updateTotal() {
                const quantity = parseInt(document.getElementById('quantity').value) || 0;
                
                if (quantity > 0) {
                    const total = (pricePerTicket * quantity).toFixed(2);
                    
                    document.getElementById('quantity-display').textContent = quantity;
                    document.getElementById('subtotal-price').textContent = '$' + (pricePerTicket * quantity).toFixed(2);
                    document.getElementById('total-price').textContent = total;
                    
                    document.getElementById('summary-qty').textContent = quantity;
                    document.getElementById('summary-total').textContent = total;
                }
            }
        </script>
    </body>
</html>
