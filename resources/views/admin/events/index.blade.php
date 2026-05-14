<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manage Events</h2>
                <p class="mt-1 text-sm text-gray-500">Review organizer submissions and publish approved events.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">Back to Dashboard</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm font-medium text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            <x-card>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Event Title</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Organizer Name</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Start Date</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
                                <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($events as $event)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <p class="font-semibold text-gray-900">{{ $event->title }}</p>
                                        <p class="mt-1 text-xs text-gray-500">{{ $event->location }}</p>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        {{ $event->organizer?->name ?? 'Unknown Organizer' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        <span class="font-medium">{{ $event->start_time->format('M d, Y') }}</span>
                                        <span class="block text-xs text-gray-500">{{ $event->start_time->format('H:i') }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $statusColor = match($event->status) {
                                                'pending' => 'yellow',
                                                'active' => 'green',
                                                default => 'gray',
                                            };
                                        @endphp
                                        <x-badge :color="$statusColor" class="capitalize">{{ $event->status }}</x-badge>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        @if($event->status === 'pending')
                                            <form method="POST" action="{{ route('admin.events.approve', $event) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="inline-flex items-center rounded-lg bg-green-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white shadow-sm transition hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                                    Approve
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-sm text-gray-400">No action</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-500">No events found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($events->hasPages())
                    <div class="border-t border-gray-100 bg-gray-50 px-6 py-4">
                        {{ $events->links() }}
                    </div>
                @endif
            </x-card>
        </div>
    </div>
</x-app-layout>
