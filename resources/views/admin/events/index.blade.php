<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-slate-900">Manage Events</h2>
                <p class="mt-1 text-sm text-slate-500">Review organizer submissions and publish approved events.</p>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        @if(session('success'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead>
                        <tr class="bg-slate-50">
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Event Title</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Organizer</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Start Date</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Status</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($events as $event)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4">
                                    <p class="font-semibold text-slate-900">{{ $event->title }}</p>
                                    <p class="mt-0.5 text-xs text-slate-500">{{ $event->location }}</p>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-700">
                                    {{ $event->organizer?->name ?? 'Unknown Organizer' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-700">
                                    <span class="font-medium">{{ $event->start_time->format('M d, Y') }}</span>
                                    <span class="block text-xs text-slate-400">{{ $event->start_time->format('H:i') }}</span>
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
                                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white text-xs font-semibold uppercase tracking-wider rounded-lg hover:bg-emerald-700 transition focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                                                Approve
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-sm text-slate-400">No action</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-sm text-slate-500">No events found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($events->hasPages())
                <div class="border-t border-slate-200 bg-slate-50 px-6 py-4">
                    {{ $events->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>