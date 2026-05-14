<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $event->title }}
            </h2>
            <div class="flex space-x-3">
                <a href="{{ route('organizer.events.edit', $event) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                    Edit Event
                </a>
                <a href="{{ route('organizer.events.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Event Details -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                @if($event->banner_image)
                    <img src="{{ Storage::url($event->banner_image) }}" alt="{{ $event->title }}" class="w-full h-64 object-cover">
                @else
                    <div class="w-full h-32 bg-gray-200 flex items-center justify-center text-gray-500">No Banner Image</div>
                @endif
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-2">
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Description</h3>
                            <p class="text-gray-700 whitespace-pre-line">{{ $event->description ?: 'No description provided.' }}</p>
                        </div>
                        <div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-2">Details</h3>
                                <div class="space-y-3">
                                    <div>
                                        <p class="text-xs text-gray-500">Status</p>
                                        <p class="font-medium">
                                            @php
                                                $statusColor = match($event->status) {
                                                    'active' => 'green',
                                                    'pending', 'draft' => 'yellow',
                                                    default => 'gray',
                                                };
                                            @endphp
                                            <x-badge :color="$statusColor" class="capitalize">{{ $event->status }}</x-badge>
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Location</p>
                                        <p class="font-medium text-sm">{{ $event->location }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Start Time</p>
                                        <p class="font-medium text-sm">{{ $event->start_time->format('M d, Y H:i') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">End Time</p>
                                        <p class="font-medium text-sm">{{ $event->end_time->format('M d, Y H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ticket Categories -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-900">Ticket Categories</h3>
                    <a href="{{ route('organizer.events.ticket-categories.create', $event) }}" class="inline-flex items-center px-3 py-1.5 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Add Category
                    </a>
                </div>
                <div class="p-6">
                    @if($event->ticketCategories->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($event->ticketCategories as $category)
                                <div class="border rounded-lg p-4 shadow-sm hover:shadow-md transition">
                                    <div class="flex justify-between items-start mb-2">
                                        <h4 class="font-bold text-lg text-gray-900">{{ $category->name }}</h4>
                                        <div class="flex space-x-2">
                                            <a href="{{ route('organizer.events.ticket-categories.edit', [$event, $category]) }}" class="text-blue-600 hover:text-blue-900 text-sm">Edit</a>
                                            <form action="{{ route('organizer.events.ticket-categories.destroy', [$event, $category]) }}" method="POST" onsubmit="return confirm('Delete this ticket category?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 text-sm">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-500">Price: <span class="font-medium text-gray-900">${{ number_format($category->price, 2) }}</span></span>
                                        <span class="text-gray-500">Quota: <span class="font-medium text-gray-900">{{ $category->quota }}</span></span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">No ticket categories added yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
