<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold text-slate-900">Edit Ticket Category</h2>
                <p class="mt-1 text-sm text-slate-500">Update "{{ $ticketCategory->name }}" for "{{ $event->title }}".</p>
            </div>
            <a href="{{ route('organizer.events.show', $event) }}" class="text-sm font-medium text-emerald-600 hover:text-emerald-800">
                &larr; Back to Event
            </a>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto space-y-6">
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
            <form method="POST" action="{{ route('organizer.events.ticket-categories.update', [$event, $ticketCategory]) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <x-input-label for="name" :value="__('Category Name')" />
                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $ticketCategory->name)" placeholder="e.g. VIP, General Admission" required autofocus />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    <p class="mt-1 text-sm text-slate-500">Enter a descriptive name for this ticket category.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="price" :value="__('Price ($)')" />
                        <x-text-input id="price" class="block mt-1 w-full" type="number" step="0.01" min="0" name="price" :value="old('price', $ticketCategory->price)" required />
                        <x-input-error :messages="$errors->get('price')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="quota" :value="__('Quota (Total Tickets)')" />
                        <x-text-input id="quota" class="block mt-1 w-full" type="number" min="1" name="quota" :value="old('quota', $ticketCategory->quota)" required />
                        <x-input-error :messages="$errors->get('quota')" class="mt-2" />
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="{{ route('organizer.events.show', $event) }}" class="px-4 py-2 text-sm font-medium text-slate-700 border border-slate-300 rounded-lg hover:bg-slate-50 transition">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white text-sm font-semibold rounded-lg hover:bg-emerald-700 transition focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                        <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Update Ticket Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>