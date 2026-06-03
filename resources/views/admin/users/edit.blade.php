<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-slate-900">Assign Role</h2>
                <p class="mt-1 text-sm text-slate-500">Change the role for {{ $user->name }}.</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto space-y-6">
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
            <!-- User Info -->
            <div class="mb-6 pb-6 border-b border-slate-100">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 font-bold text-lg">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">{{ $user->name }}</h3>
                        <p class="text-sm text-slate-600">{{ $user->email }}</p>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <x-input-label for="role" :value="__('Select Role')" />
                    <select
                        id="role"
                        name="role"
                        class="block mt-1 w-full border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm"
                        required
                        onchange="toggleOrganizerSelect()"
                    >
                        <option value="">-- Choose a role --</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}" @selected(($userRole?->id === $role->id))>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('role')" class="mt-2" />
                    <p class="mt-1 text-sm text-slate-500">Select the role to assign to this user.</p>
                </div>

                <div id="organizer-select-container" class="hidden">
                    <x-input-label for="organizer_id" :value="__('Assign to Event Organizer (Optional)')" />
                    <select
                        id="organizer_id"
                        name="organizer_id"
                        class="block mt-1 w-full border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm"
                    >
                        <option value="">-- Global Scanner (Can scan any ticket) --</option>
                        @foreach($organizers as $organizer)
                            <option value="{{ $organizer->id }}" @selected(old('organizer_id', $user->organizer_id) == $organizer->id)>
                                {{ $organizer->name }} ({{ $organizer->email }})
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('organizer_id')" class="mt-2" />
                    <p class="mt-1 text-sm text-slate-500">If assigned, this scanner can only scan tickets for events created by this organizer.</p>
                </div>

                <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4">
                    <div class="flex gap-3">
                        <svg class="w-5 h-5 text-indigo-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zm-11-1a1 1 0 11-2 0 1 1 0 012 0zM8 7a1 1 0 000 2h6a1 1 0 100-2H8z" clip-rule="evenodd"/></svg>
                        <div>
                            <p class="text-sm font-semibold text-indigo-900">Current Role</p>
                            <p class="text-sm text-indigo-800 mt-1">
                                @if($userRole)
                                    <strong>{{ $userRole->name }}</strong>
                                @else
                                    <strong>No Role Assigned</strong>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700 transition focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Update Role
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="px-4 py-2 text-sm font-medium text-slate-700 border border-slate-300 rounded-lg hover:bg-slate-50 transition">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleOrganizerSelect() {
            const roleSelect = document.getElementById('role');
            const organizerContainer = document.getElementById('organizer-select-container');
            
            if (roleSelect.value === 'Gate Scanner') {
                organizerContainer.classList.remove('hidden');
            } else {
                organizerContainer.classList.add('hidden');
            }
        }

        // Run on page load
        document.addEventListener('DOMContentLoaded', toggleOrganizerSelect);
    </script>
</x-app-layout>