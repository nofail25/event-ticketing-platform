<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create New Role</h2>
                <p class="mt-1 text-sm text-gray-500">Add a custom role to the system.</p>
            </div>
            <a href="{{ route('admin.roles.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">Back to Roles</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <x-card>
                <form method="POST" action="{{ route('admin.roles.store') }}" class="space-y-6">
                    @csrf

                    <div>
                        <x-input-label for="name" :value="__('Role Name')" />
                        <x-text-input
                            id="name"
                            class="block mt-1 w-full"
                            type="text"
                            name="name"
                            :value="old('name')"
                            placeholder="e.g., Moderator"
                            autofocus
                            required
                        />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        <p class="mt-1 text-sm text-gray-500">Enter a unique name for this custom role.</p>
                    </div>

                    <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                        <x-primary-button>
                            <svg class="w-4 h-4 me-2 inline-block" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                            Create Role
                        </x-primary-button>
                        <a href="{{ route('admin.roles.index') }}" class="px-4 py-2 text-gray-700 font-medium border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                            Cancel
                        </a>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
</x-app-layout>
