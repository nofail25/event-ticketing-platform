<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="flex items-center gap-3">
                <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-gradient-to-br from-orange-400 to-orange-600 shadow-md flex items-center justify-center">
                    <span class="material-symbols-outlined text-xl text-white" style="line-height:1;">qr_code_scanner</span>
                </div>
                <div>
                    <h2 class="text-xl font-black text-slate-900 tracking-tight">Scanner</h2>
                    <p class="text-xs text-slate-500 font-semibold">Validasi Tiket Masuk</p>
                </div>
            </div>
            <!-- Condense welcome message to top right for desktop -->
            <div class="hidden sm:block text-right">
                <p class="text-xs font-semibold text-slate-500">Penjaga Pintu</p>
                <p class="text-sm font-bold text-slate-900">{{ Auth::user()->name }}</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-md mx-auto sm:max-w-xl space-y-4 sm:space-y-6 pb-20">
        
        <!-- Live Camera Scanner - Pindah ke paling atas agar langsung siap scan -->
        <div class="clean-card bg-white rounded-3xl p-3 sm:p-5 border-2 border-orange-100 shadow-orange-100/50">
            <div class="flex items-center justify-between mb-3 px-1">
                <h3 class="text-sm font-bold text-slate-700 uppercase tracking-wider">Kamera Langsung</h3>
                <span class="flex h-2.5 w-2.5 relative">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-500"></span>
                </span>
            </div>
            <div id="reader" class="w-full bg-slate-900 rounded-2xl overflow-hidden aspect-square sm:aspect-video flex items-center justify-center relative shadow-inner">
                <!-- Placeholder if camera not loaded yet -->
            </div>
        </div>

        <!-- Result Display - VALID -->
        @if(session('scan_success'))
            <div class="animate-stagger-enter delay-1">
                <div class="bg-emerald-50 border-2 border-emerald-500 rounded-3xl p-5 shadow-lg shadow-emerald-200/50">
                    <div class="flex flex-col items-center text-center">
                        <div class="w-16 h-16 rounded-full bg-emerald-500 flex items-center justify-center shadow-md mb-3">
                            <span class="material-symbols-outlined text-4xl text-white" style="line-height:1;">check_circle</span>
                        </div>
                        <h2 class="text-2xl font-black text-emerald-700 mb-1">AKSES DIBERIKAN</h2>
                        <p class="text-sm font-bold text-emerald-600 mb-4 bg-emerald-100 px-3 py-1 rounded-full border border-emerald-200">Tiket Valid</p>
                        
                        @if(session('scan_details'))
                            <div class="w-full bg-white rounded-2xl p-4 text-left border border-emerald-100 shadow-sm space-y-2">
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Nama Tamu</p>
                                    <p class="font-bold text-slate-900">{{ session('scan_details')['customer_name'] ?? 'N/A' }}</p>
                                </div>
                                <div class="grid grid-cols-2 gap-3 pt-2 border-t border-slate-100">
                                    <div>
                                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Event</p>
                                        <p class="text-sm font-bold text-slate-800 line-clamp-1">{{ session('scan_details')['event_name'] ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Tipe Tiket</p>
                                        <p class="text-sm font-bold text-slate-800 line-clamp-1">{{ session('scan_details')['category_name'] ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <!-- Result Display - ALREADY SCANNED -->
        @if(session('scan_warning'))
            <div class="animate-stagger-enter delay-1">
                <div class="bg-amber-50 border-2 border-amber-500 rounded-3xl p-5 shadow-lg shadow-amber-200/50">
                    <div class="flex flex-col items-center text-center">
                        <div class="w-16 h-16 rounded-full bg-amber-500 flex items-center justify-center shadow-md mb-3">
                            <span class="material-symbols-outlined text-4xl text-white" style="line-height:1;">warning</span>
                        </div>
                        <h2 class="text-2xl font-black text-amber-700 mb-1">SUDAH DIPINDAI</h2>
                        <p class="text-sm font-bold text-amber-600 mb-3 bg-amber-100 px-3 py-1 rounded-full border border-amber-200">Tiket sudah digunakan</p>
                        @if(session('scan_message'))
                            <p class="text-xs font-mono bg-white px-3 py-2 rounded-xl border border-amber-200 text-amber-700 w-full">{{ session('scan_message') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <!-- Result Display - INVALID -->
        @if($errors->has('scan_result') || ($errors->any() && !session('scan_success') && !session('scan_warning')))
            <div class="animate-stagger-enter delay-1">
                <div class="bg-red-50 border-2 border-red-500 rounded-3xl p-5 shadow-lg shadow-red-200/50">
                    <div class="flex flex-col items-center text-center">
                        <div class="w-16 h-16 rounded-full bg-red-600 flex items-center justify-center shadow-md mb-3">
                            <span class="material-symbols-outlined text-4xl text-white" style="line-height:1;">cancel</span>
                        </div>
                        <h2 class="text-2xl font-black text-red-700 mb-1">TIKET TIDAK VALID</h2>
                        <p class="text-sm font-bold text-red-600 mb-3 bg-red-100 px-3 py-1 rounded-full border border-red-200">Tiket tidak ditemukan atau format tidak valid</p>
                        @if($errors->first('scan_result'))
                            <p class="text-xs font-mono bg-white px-3 py-2 rounded-xl border border-red-200 text-red-700 w-full">{{ $errors->first('scan_result') }}</p>
                        @endif
                        @if(session('scan_message'))
                            <p class="text-xs font-mono bg-white px-3 py-2 rounded-xl border border-red-200 text-red-700 w-full">{{ session('scan_message') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <div class="flex items-center justify-center gap-4 py-2">
            <div class="h-px bg-slate-200 flex-1"></div>
            <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Entri Manual</span>
            <div class="h-px bg-slate-200 flex-1"></div>
        </div>

        <!-- Scanner Form -->
        <form id="scanner_form" action="{{ route('gate.scan') }}" method="POST" class="clean-card bg-white rounded-3xl p-5 border border-slate-200">
            @csrf
            <div class="space-y-4">
                <div>
                    <label for="barcode_input" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2 ml-1">
                        Kode Tiket
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                            <span class="material-symbols-outlined text-slate-400" style="line-height:1;">pin</span>
                        </span>
                        <input
                            type="text"
                            id="barcode_input"
                            name="barcode_string"
                            placeholder="Masukkan kode 12 karakter..."
                            class="w-full pl-11 pr-4 py-4 text-base font-mono font-bold border-2 border-slate-200 rounded-2xl focus:outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-500/20 transition-all placeholder:font-sans placeholder:font-medium placeholder:text-slate-400 uppercase"
                            autocomplete="off"
                        >
                    </div>
                    @error('barcode_string')
                        <p class="mt-2 text-xs font-bold text-red-500 flex items-center gap-1 ml-1">
                            <span class="material-symbols-outlined text-sm">error</span>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <button type="button" onclick="document.getElementById('barcode_input').value=''" class="flex items-center justify-center py-4 bg-slate-100 hover:bg-slate-200 active:bg-slate-300 text-slate-700 font-bold rounded-2xl transition-colors text-sm">
                        Bersihkan
                    </button>
                    <button type="submit" class="flex items-center justify-center gap-2 py-4 bg-orange-600 hover:bg-orange-700 active:scale-[0.98] text-white font-bold rounded-2xl transition-all shadow-sm shadow-orange-500/30 text-sm">
                        <span class="material-symbols-outlined text-base" style="line-height:1;">fact_check</span>
                        Verifikasi
                    </button>
                </div>
            </div>
        </form>

    </div>

    <!-- HTML5 QR Code Library -->
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const readerElement = document.getElementById('reader');
            
            // Responsive QR Box size calculation for mobile viewing
            const qrboxFunction = function(viewfinderWidth, viewfinderHeight) {
                let minEdgePercentage = 0.75; // 75% of the smallest edge
                let minEdgeSize = Math.min(viewfinderWidth, viewfinderHeight);
                let qrboxSize = Math.floor(minEdgeSize * minEdgePercentage);
                return {
                    width: qrboxSize,
                    height: qrboxSize
                };
            };

            const html5QrCode = new Html5Qrcode("reader");
            const config = { 
                fps: 10, 
                qrbox: qrboxFunction,
                aspectRatio: 1.0 // Force square aspect ratio on mobile for better fit
            };
            
            let isScanned = false;
            
            function onScanSuccess(decodedText, decodedResult) {
                if (isScanned) return;
                isScanned = true;
                
                // Visual feedback that scan was captured
                document.getElementById('reader').style.opacity = '0.5';
                
                // Stop camera before submitting
                html5QrCode.stop().then(() => {
                    submitForm(decodedText);
                }).catch(err => {
                    submitForm(decodedText);
                });
            }

            function submitForm(text) {
                document.getElementById('barcode_input').value = text;
                document.getElementById('scanner_form').submit();
            }

            // Start camera
            function startCamera() {
                readerElement.innerHTML = '<div class="absolute inset-0 flex flex-col items-center justify-center p-6 text-center text-white/50"><div class="animate-spin inline-block w-10 h-10 border-4 border-current border-t-transparent text-orange-500 rounded-full mb-3" role="status"></div><p class="text-sm font-medium">Memulai kamera...</p></div>';
                
                Html5Qrcode.getCameras().then(devices => {
                    if (devices && devices.length) {
                        // Prioritaskan kamera belakang (environment)
                        html5QrCode.start({ facingMode: "environment" }, config, onScanSuccess)
                        .catch(err => {
                            console.warn("Gagal menggunakan kamera belakang, mencoba kamera lain...", err);
                            // Fallback jika kamera belakang tidak ada
                            html5QrCode.start(devices[0].id, config, onScanSuccess)
                            .catch(e => {
                                showError("Kamera gagal dimulai. Periksa izin.");
                            });
                        });
                    } else {
                        showError("Tidak ada kamera terdeteksi di perangkat ini.");
                    }
                }).catch(err => {
                    console.error("Error getting cameras", err);
                    showError("Akses kamera ditolak atau koneksi tidak aman.<br><br>Pastikan Anda menggunakan HTTPS dan mengizinkan akses kamera.");
                });
            }

            function showError(message) {
                readerElement.innerHTML = `
                    <div class="absolute inset-0 flex flex-col items-center justify-center p-6 text-center bg-slate-900 text-white">
                        <div class="flex items-center justify-center w-12 h-12 rounded-full bg-red-500/20 mb-3">
                            <span class="material-symbols-outlined text-2xl text-red-500" style="line-height:1;">warning</span>
                        </div>
                        <p class="text-xs text-red-400 font-semibold mb-4 leading-relaxed">${message}</p>
                        <button onclick="window.location.reload()" class="px-4 py-2 bg-white/10 hover:bg-white/20 active:scale-[0.98] rounded-xl text-xs font-bold text-white transition-all border border-white/10">
                            Coba Lagi Kamera
                        </button>
                    </div>
                `;
            }

            // Start immediately
            startCamera();

            // Auto uppercase on input
            const barcodeInput = document.getElementById('barcode_input');
            if (barcodeInput) {
                barcodeInput.addEventListener('input', function() {
                    this.value = this.value.toUpperCase();
                });
            }
        });
    </script>
</x-app-layout>