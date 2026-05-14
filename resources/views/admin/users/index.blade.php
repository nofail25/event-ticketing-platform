<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manage Users</h2>
                <p class="mt-1 text-sm text-gray-500">All registered platform accounts.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">Back to Dashboard</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Flash Messages --}}
            @if ($message = Session::get('success'))
                <div class="mb-6 px-4 py-4 bg-emerald-50 border border-emerald-200 rounded-lg flex items-start gap-3">
                    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <p class="text-sm font-medium text-emerald-800">{{ $message }}</p>
                </div>
            @endif

            <x-card>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Name</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Email</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Role</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Join Date</th>
                                <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($users as $user)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 font-semibold text-gray-900">{{ $user->name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $user->email }}</td>
                                    <td class="px-6 py-4">
                                        <x-badge color="indigo">{{ $user->roles->pluck('name')->join(', ') ?: 'No Role' }}</x-badge>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $user->created_at->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex items-center px-3 py-1.5 bg-indigo-50 text-indigo-700 font-medium rounded-lg hover:bg-indigo-100 transition text-sm">
                                            <svg class="w-4 h-4 me-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m7 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            Assign Role
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-500">No users found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($users->hasPages())
                    <div class="border-t border-gray-100 bg-gray-50 px-6 py-4">
                        {{ $users->links() }}
                    </div>
                @endif
            </x-card>
        </div>
    </div>
</x-app-layout>
