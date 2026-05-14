<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Assign Role</h2>
                <p class="mt-1 text-sm text-gray-500">Change the role for {{ $user->name }}.</p>
            </div>
            <a href="{{ route('admin.users.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">Back to Users</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <x-card>
                <div class="mb-6 pb-6 border-b border-gray-100">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-lg">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ $user->name }}</h3>
                            <p class="text-sm text-gray-600">{{ $user->email }}</p>
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
                            class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm"
                            required
                        >
                            <option value="">-- Choose a role --</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" @selected(($userRole?->id === $role->id))>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('role')" class="mt-2" />
                        <p class="mt-1 text-sm text-gray-500">Select the role to assign to this user.</p>
                    </div>

                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex gap-3">
                            <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zm-11-1a1 1 0 11-2 0 1 1 0 012 0zM8 7a1 1 0 000 2h6a1 1 0 100-2H8z" clip-rule="evenodd"/></svg>
                            <div>
                                <p class="text-sm font-semibold text-blue-900">Current Role</p>
                                <p class="text-sm text-blue-800 mt-1">
                                    @if($userRole)
                                        <strong>{{ $userRole->name }}</strong>
                                    @else
                                        <strong>No Role Assigned</strong>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                        <x-primary-button>
                            <svg class="w-4 h-4 me-2 inline-block" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Update Role
                        </x-primary-button>
                        <a href="{{ route('admin.users.index') }}" class="px-4 py-2 text-gray-700 font-medium border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                            Cancel
                        </a>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
</x-app-layout>
