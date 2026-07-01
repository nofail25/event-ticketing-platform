<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold text-slate-900">Edit Event</h2>
                <p class="mt-1 text-sm text-slate-500">Perbarui detail untuk "{{ $event->title }}".</p>
            </div>
            <a href="{{ route('organizer.events.index') }}" class="text-sm font-medium text-emerald-600 hover:text-emerald-800">
                &larr; Kembali ke Event
            </a>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto space-y-6">
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
            <form method="POST" action="{{ route('organizer.events.update', $event) }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Title -->
                <div>
                    <x-input-label for="title" :value="__('Judul Event')" />
                    <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $event->title)" placeholder="contoh: Konferensi Teknologi 2026" required autofocus />
                    <x-input-error :messages="$errors->get('title')" class="mt-2" />
                </div>

                <!-- Description -->
                <div>
                    <x-input-label for="description" :value="__('Deskripsi')" />
                    <textarea id="description" name="description" rows="4" class="block mt-1 w-full border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-lg shadow-sm" placeholder="Jelaskan apa yang dapat diharapkan oleh peserta...">{{ old('description', $event->description) }}</textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                </div>

                <!-- Location -->
                <div>
                    <x-input-label for="location" :value="__('Lokasi')" />
                    <x-text-input id="location" class="block mt-1 w-full" type="text" name="location" :value="old('location', $event->location)" placeholder="contoh: Jakarta Convention Center" required />
                    <x-input-error :messages="$errors->get('location')" class="mt-2" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Start Time -->
                    <div>
                        <x-input-label for="start_time" :value="__('Waktu Mulai')" />
                        <x-text-input id="start_time" class="block mt-1 w-full" type="datetime-local" name="start_time" :value="old('start_time', $event->start_time->format('Y-m-d\TH:i'))" required />
                        <x-input-error :messages="$errors->get('start_time')" class="mt-2" />
                    </div>

                    <!-- End Time -->
                    <div>
                        <x-input-label for="end_time" :value="__('Waktu Selesai')" />
                        <x-text-input id="end_time" class="block mt-1 w-full" type="datetime-local" name="end_time" :value="old('end_time', $event->end_time->format('Y-m-d\TH:i'))" required />
                        <x-input-error :messages="$errors->get('end_time')" class="mt-2" />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="rounded-lg border border-amber-200 bg-amber-50 p-4">
                        <p class="text-sm font-semibold text-amber-900">Status Saat Ini: <span class="capitalize">{{ $event->status }}</span></p>
                        <p class="mt-1 text-sm text-amber-700">Mengedit event aktif akan mengirimkannya kembali ke status menunggu ulasan admin.</p>
                    </div>

                    <!-- Banner Image -->
                    <div>
                        <x-input-label for="banner_image" :value="__('Perbarui Gambar Spanduk')" />
                        @if($event->banner_image)
                            <div class="mt-2 mb-2">
                                <p class="text-sm text-slate-500 mb-1">Spanduk Saat Ini:</p>
                                <img src="{{ Storage::url($event->banner_image) }}" alt="Spanduk Saat Ini" class="h-20 aspect-video object-cover rounded-lg">
                            </div>
                        @endif
                        <input id="banner_image" type="file" name="banner_image" class="block mt-1 w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100" accept="image/jpeg, image/png, image/webp" />
                        <x-input-error :messages="$errors->get('banner_image')" class="mt-2" />
                        <p class="mt-1 text-xs text-slate-500">Max 2MB. JPG, PNG, WEBP. Biarkan kosong untuk mempertahankan yang saat ini. <br>Rekomendasi ukuran: 1920x1080 (Rasio 16:9) agar gambar tampil penuh.</p>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="{{ route('organizer.events.index') }}" class="px-4 py-2 text-sm font-medium text-slate-700 border border-slate-300 rounded-lg hover:bg-slate-50 transition">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white text-sm font-semibold rounded-lg hover:bg-emerald-700 transition focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                        <span class="material-symbols-outlined text-base me-2" style="line-height:1;">check_circle</span>
                        Perbarui Event
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>