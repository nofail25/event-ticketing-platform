<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold text-slate-900">Create Event</h2>
                <p class="mt-1 text-sm text-slate-500">Set up a new event listing for your audience.</p>
            </div>
            <a href="{{ route('organizer.events.index') }}" class="text-sm font-medium text-emerald-600 hover:text-emerald-800">
                &larr; Back to Events
            </a>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto space-y-6">
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
            <form method="POST" action="{{ route('organizer.events.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Title -->
                <div>
                    <x-input-label for="title" :value="__('Event Title')" />
                    <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title')" placeholder="e.g., Tech Conference 2026" required autofocus />
                    <x-input-error :messages="$errors->get('title')" class="mt-2" />
                </div>

                <!-- Description -->
                <div>
                    <x-input-label for="description" :value="__('Description')" />
                    <textarea id="description" name="description" rows="4" class="block mt-1 w-full border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-lg shadow-sm" placeholder="Describe what attendees can expect...">{{ old('description') }}</textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                </div>

                <!-- Location -->
                <div>
                    <x-input-label for="location" :value="__('Location')" />
                    <x-text-input id="location" class="block mt-1 w-full" type="text" name="location" :value="old('location')" placeholder="e.g., Jakarta Convention Center" required />
                    <x-input-error :messages="$errors->get('location')" class="mt-2" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Start Time -->
                    <div>
                        <x-input-label for="start_time" :value="__('Start Time')" />
                        <x-text-input id="start_time" class="block mt-1 w-full" type="datetime-local" name="start_time" :value="old('start_time')" required />
                        <x-input-error :messages="$errors->get('start_time')" class="mt-2" />
                    </div>

                    <!-- End Time -->
                    <div>
                        <x-input-label for="end_time" :value="__('End Time')" />
                        <x-text-input id="end_time" class="block mt-1 w-full" type="datetime-local" name="end_time" :value="old('end_time')" required />
                        <x-input-error :messages="$errors->get('end_time')" class="mt-2" />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="rounded-lg border border-amber-200 bg-amber-50 p-4">
                        <p class="text-sm font-semibold text-amber-900">Approval Required</p>
                        <p class="mt-1 text-sm text-amber-700">New events are submitted as pending until a Super Admin approves them.</p>
                    </div>

                    <!-- Banner Image -->
                    <div>
                        <x-input-label for="banner_image" :value="__('Banner Image (Optional)')" />
                        <input id="banner_image" type="file" name="banner_image" class="block mt-1 w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100" accept="image/jpeg, image/png, image/webp" />
                        <x-input-error :messages="$errors->get('banner_image')" class="mt-2" />
                        <p class="mt-1 text-xs text-slate-500">Max 2MB. JPG, PNG, WEBP. <br>Rekomendasi ukuran: 1920x1080 (Rasio 16:9) agar gambar tampil penuh.</p>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="{{ route('organizer.events.index') }}" class="px-4 py-2 text-sm font-medium text-slate-700 border border-slate-300 rounded-lg hover:bg-slate-50 transition">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white text-sm font-semibold rounded-lg hover:bg-emerald-700 transition focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                        <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                        Create Event
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>