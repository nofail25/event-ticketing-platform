<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="flex-shrink-0 w-9 h-9 rounded-lg bg-orange-600 flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-slate-900">Ticket Scanner</h2>
                <p class="text-xs text-slate-500 font-medium">Gate entry validation</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto space-y-6">
        <!-- Welcome Header -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
            <h1 class="text-2xl font-bold text-slate-900">Hello, {{ Auth::user()->name }}</h1>
            <p class="text-slate-500 mt-1">Scan or enter a ticket barcode to validate entry</p>
        </div>

        <!-- Live Camera Scanner -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
            <h3 class="text-lg font-semibold text-slate-900 mb-4 text-center">Live Camera Scanner</h3>
            <div id="reader" class="w-full max-w-md mx-auto rounded-lg overflow-hidden border-2 border-slate-200"></div>
        </div>

        <div class="flex items-center">
            <div class="flex-grow border-t border-slate-200"></div>
            <span class="mx-4 text-sm font-medium text-slate-500">OR enter manually</span>
            <div class="flex-grow border-t border-slate-200"></div>
        </div>

        <!-- Scanner Form -->
        <form id="scanner_form" action="{{ route('gate.scan') }}" method="POST" class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 space-y-6">
            @csrf

            <div class="space-y-2">
                <label for="barcode_input" class="block text-sm font-semibold text-slate-700">
                    Barcode / Ticket ID
                </label>
                <input
                    type="text"
                    id="barcode_input"
                    name="barcode_string"
                    placeholder="Paste barcode or enter ticket ID..."
                    autofocus
                    class="w-full px-4 py-3 text-lg border-2 border-slate-300 rounded-lg focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition-all placeholder-slate-400"
                    autocomplete="off"
                >
                @error('barcode_string')
                    <p class="text-sm text-red-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-3">
                <button type="submit" class="flex-1 bg-orange-600 hover:bg-orange-700 text-white font-bold py-3 px-6 rounded-lg transition-all shadow-sm text-lg">
                    Verify Ticket
                </button>
                <button type="reset" class="px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-lg transition-all text-lg">
                    Clear
                </button>
            </div>
        </form>

        <!-- Result Display - VALID -->
        @if(session('scan_success'))
            <div class="animate-pulse">
                <div class="bg-emerald-50 border-2 border-emerald-500 rounded-xl p-6 shadow-sm">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-14 h-14 rounded-full bg-emerald-500 flex items-center justify-center shadow-sm">
                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h2 class="text-2xl font-bold text-emerald-700 mb-2">Valid Ticket</h2>
                            @if(session('scan_details'))
                                <div class="space-y-1 text-emerald-700">
                                    <p class="font-semibold">Event: {{ session('scan_details')['event_name'] ?? 'N/A' }}</p>
                                    <p class="font-semibold">Ticket: {{ session('scan_details')['category_name'] ?? 'N/A' }}</p>
                                    <p class="font-semibold">Guest: {{ session('scan_details')['customer_name'] ?? 'N/A' }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Result Display - ALREADY SCANNED -->
        @if(session('scan_warning'))
            <div class="animate-pulse">
                <div class="bg-amber-50 border-2 border-amber-500 rounded-xl p-6 shadow-sm">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-14 h-14 rounded-full bg-amber-500 flex items-center justify-center shadow-sm">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h2 class="text-2xl font-bold text-amber-700 mb-2">Already Scanned</h2>
                            <p class="text-amber-700">This ticket has already been used for entry.</p>
                            @if(session('scan_message'))
                                <p class="text-amber-600 mt-2 font-mono">{{ session('scan_message') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Result Display - INVALID -->
        @if($errors->has('scan_result') || ($errors->any() && !session('scan_success') && !session('scan_warning')))
            <div class="animate-pulse">
                <div class="bg-red-50 border-2 border-red-500 rounded-xl p-6 shadow-sm">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-14 h-14 rounded-full bg-red-500 flex items-center justify-center shadow-sm">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h2 class="text-2xl font-bold text-red-700 mb-2">Invalid Ticket</h2>
                            <p class="text-red-700">Ticket not found or invalid format.</p>
                            @if($errors->first('scan_result'))
                                <p class="text-red-600 mt-2">{{ $errors->first('scan_result') }}</p>
                            @endif
                            @if(session('scan_message'))
                                <p class="text-red-600 mt-2">{{ session('scan_message') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- User Info Footer -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 text-center">
            <p class="text-slate-600 text-sm">
                Logged in as: <span class="font-semibold text-slate-900">{{ Auth::user()->email }}</span>
            </p>
            <span class="inline-flex items-center gap-1.5 mt-1 px-3 py-1 rounded-full text-xs font-semibold bg-orange-50 text-orange-700 border border-orange-200">
                <span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span>
                Gate Scanner
            </span>
        </div>
    </div>

    <!-- HTML5 QR Code Library -->
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

                try {
                    html5QrcodeScanner.pause();
                } catch (e) {
                    html5QrcodeScanner.clear();
                }

                document.getElementById('barcode_input').value = decodedText;
                document.getElementById('scanner_form').submit();
            }

            html5QrcodeScanner.render(onScanSuccess);
        });
    </script>
</x-app-layout>