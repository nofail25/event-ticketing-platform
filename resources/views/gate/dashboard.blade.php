<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="flex-shrink-0 w-9 h-9 rounded-lg bg-gradient-to-br from-orange-500 to-red-600 flex items-center justify-center shadow">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                </svg>
            </div>
            <div>
                <h2 class="font-bold text-xl text-gray-800 leading-tight">Ticket Scanner</h2>
                <p class="text-xs text-gray-500 font-medium">Gate entry validation</p>
            </div>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gray-50 py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl mx-auto">
            
            {{-- Welcome Header --}}
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Hello, {{ Auth::user()->name }}</h1>
                <p class="text-gray-600">Scan or enter a ticket barcode to validate entry</p>
            </div>

            {{-- Live Camera Scanner Section --}}
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 text-center">Live Camera Scanner</h3>
                <div id="reader" class="w-full max-w-md mx-auto rounded-lg overflow-hidden border-2 border-gray-300"></div>
            </div>

            <div class="flex items-center mb-8">
                <div class="flex-grow border-t border-gray-300"></div>
                <span class="mx-4 text-gray-500 font-medium">OR enter manually</span>
                <div class="flex-grow border-t border-gray-300"></div>
            </div>

            {{-- Scanner Form Container --}}
            <form id="scanner_form" action="{{ route('gate.scan') }}" method="POST" class="bg-white rounded-2xl shadow-lg p-8 space-y-6">
                @csrf

                {{-- Input Section --}}
                <div class="space-y-3">
                    <label for="barcode_input" class="block text-lg font-semibold text-gray-800">
                        Barcode / Ticket ID
                    </label>
                    <input
                        type="text"
                        id="barcode_input"
                        name="barcode_string"
                        placeholder="Paste barcode or enter ticket ID..."
                        autofocus
                        class="w-full px-6 py-4 text-xl border-2 border-gray-300 rounded-xl focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition-all placeholder-gray-400"
                        autocomplete="off"
                    >
                    @error('barcode_string')
                        <p class="text-sm text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Submit Button --}}
                <button
                    type="submit"
                    class="w-full bg-gradient-to-r from-orange-500 to-red-600 hover:from-orange-600 hover:to-red-700 text-white font-bold py-4 px-6 rounded-xl transition-all shadow-md hover:shadow-lg text-xl"
                >
                    ✓ Verify Ticket
                </button>

                {{-- Clear Button --}}
                <button
                    type="reset"
                    class="w-full bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-3 px-6 rounded-xl transition-all text-lg"
                >
                    Clear
                </button>
            </form>

            {{-- Result Display - VALID (Green) --}}
            @if(session('scan_success'))
                <div class="mt-8 animate-pulse">
                    <div class="bg-gradient-to-r from-emerald-50 to-green-50 border-4 border-emerald-500 rounded-2xl p-8 shadow-xl">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-16 h-16 rounded-full bg-emerald-500 flex items-center justify-center shadow-lg">
                                <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h2 class="text-3xl font-bold text-emerald-700 mb-2">✓ VALID TICKET</h2>
                                @if(session('scan_details'))
                                    <div class="space-y-2 text-emerald-700">
                                        <p class="text-lg font-semibold">Event: {{ session('scan_details')['event_name'] ?? 'N/A' }}</p>
                                        <p class="text-lg font-semibold">Ticket Type: {{ session('scan_details')['category_name'] ?? 'N/A' }}</p>
                                        <p class="text-lg font-semibold">Guest: {{ session('scan_details')['customer_name'] ?? 'N/A' }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Result Display - ALREADY SCANNED (Yellow/Orange) --}}
            @if(session('scan_warning'))
                <div class="mt-8 animate-pulse">
                    <div class="bg-gradient-to-r from-yellow-50 to-orange-50 border-4 border-yellow-500 rounded-2xl p-8 shadow-xl">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-16 h-16 rounded-full bg-yellow-500 flex items-center justify-center shadow-lg">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h2 class="text-3xl font-bold text-yellow-700 mb-2">⚠ ALREADY SCANNED</h2>
                                <p class="text-lg text-yellow-700">This ticket has already been used for entry.</p>
                                @if(session('scan_message'))
                                    <p class="text-base text-yellow-600 mt-2 font-mono">{{ session('scan_message') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Result Display - INVALID (Red) --}}
            @if($errors->has('scan_result') || ($errors->any() && !session('scan_success') && !session('scan_warning')))
                <div class="mt-8 animate-pulse">
                    <div class="bg-gradient-to-r from-red-50 to-rose-50 border-4 border-red-600 rounded-2xl p-8 shadow-xl">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-16 h-16 rounded-full bg-red-600 flex items-center justify-center shadow-lg">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h2 class="text-3xl font-bold text-red-700 mb-2">✗ INVALID TICKET</h2>
                                <p class="text-lg text-red-700">Ticket not found or invalid format.</p>
                                @if($errors->first('scan_result'))
                                    <p class="text-base text-red-600 mt-2">{{ $errors->first('scan_result') }}</p>
                                @endif
                                @if(session('scan_message'))
                                    <p class="text-base text-red-600 mt-2">{{ session('scan_message') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- User Info Footer --}}
            <div class="mt-12 text-center">
                <p class="text-gray-600 text-sm">
                    Logged in as: <span class="font-semibold text-gray-800">{{ Auth::user()->email }}</span>
                </p>
                <p class="text-gray-500 text-xs mt-1">Gate Scanner Role</p>
            </div>

        </div>
    </div>

    {{-- HTML5 QR Code Library --}}
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let html5QrcodeScanner = new Html5QrcodeScanner(
                "reader",
                { fps: 10, qrbox: {width: 250, height: 250} },
                false
            );

            let isScanned = false;

            function onScanSuccess(decodedText, decodedResult) {
                if (isScanned) return;
                isScanned = true;

                // Stop/pause scanner to prevent multiple rapid scans
                try {
                    html5QrcodeScanner.pause();
                } catch (e) {
                    html5QrcodeScanner.clear();
                }

                // Inject the decoded text into the barcode input
                document.getElementById('barcode_input').value = decodedText;

                // Automatically submit the form
                document.getElementById('scanner_form').submit();
            }

            html5QrcodeScanner.render(onScanSuccess);
        });
    </script>
</x-app-layout>
