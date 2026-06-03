<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold text-slate-900">My Events</h2>
                <p class="mt-1 text-sm text-slate-500">Manage your event listings and ticket categories.</p>
            </div>
            <a href="{{ route('organizer.events.create') }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white text-xs font-semibold uppercase tracking-wider rounded-lg hover:bg-emerald-700 transition focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                Create Event
            </a>
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
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Banner</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Title</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Date</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Status</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($events as $event)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($event->banner_image)
                                        <img src="{{ Storage::url($event->banner_image) }}" alt="Banner" class="h-12 aspect-video object-cover rounded-lg shadow-sm">
                                    @else
                                        <div class="h-12 aspect-video bg-slate-100 rounded-lg flex items-center justify-center text-[10px] text-slate-400 border border-slate-200 text-center leading-tight">No Img</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-semibold text-slate-900">{{ $event->title }}</div>
                                    <div class="text-xs text-slate-500 mt-1 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        {{ $event->location }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-slate-900 font-medium">{{ $event->start_time->format('M d, Y') }}</div>
                                    <div class="text-xs text-slate-500 mt-1">{{ $event->start_time->format('H:i') }} - {{ $event->end_time->format('H:i') }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusColor = match($event->status) {
                                            'active' => 'green',
                                            'pending', 'draft' => 'yellow',
                                            default => 'gray',
                                        };
                                    @endphp
                                    <x-badge :color="$statusColor" class="capitalize">{{ $event->status }}</x-badge>
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-medium space-x-2">
                                    <a href="{{ route('organizer.events.show', $event) }}" class="inline-flex items-center px-3 py-1.5 bg-emerald-50 text-emerald-700 font-medium rounded-lg hover:bg-emerald-100 transition text-sm">Manage</a>
                                    <a href="{{ route('organizer.events.edit', $event) }}" class="inline-flex items-center px-3 py-1.5 bg-indigo-50 text-indigo-700 font-medium rounded-lg hover:bg-indigo-100 transition text-sm">Edit</a>
                                    <form action="{{ route('organizer.events.destroy', $event) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this event?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-50 text-red-700 font-medium rounded-lg hover:bg-red-100 transition text-sm">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-20 h-20 bg-emerald-50 rounded-full flex items-center justify-center mb-4">
                                            <svg class="w-10 h-10 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-bold text-slate-900 mb-1">No events found</h3>
                                        <p class="text-slate-500 text-sm mb-6 max-w-sm">You haven't created any events yet. Get started by setting up your very first event!</p>
                                        <a href="{{ route('organizer.events.create') }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white text-xs font-semibold uppercase tracking-wider rounded-lg hover:bg-emerald-700 transition">
                                            Create Your First Event
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($events->hasPages())
                <div class="px-6 py-4 border-t border-slate-200 bg-slate-50">
                    {{ $events->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>