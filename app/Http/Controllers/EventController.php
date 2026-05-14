<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = Event::where('organizer_id', auth()->id())->latest()->paginate(10);
        return view('organizer.events.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('organizer.events.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEventRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('banner_image')) {
            $validated['banner_image'] = $request->file('banner_image')->store('banners', 'public');
        }

        $validated['organizer_id'] = auth()->id();
        $validated['status'] = 'pending';

        Event::create($validated);

        return redirect()->route('organizer.events.index')->with('success', 'Event created and submitted for admin approval.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        if ($event->organizer_id !== auth()->id()) {
            abort(403);
        }

        $event->load('ticketCategories');

        return view('organizer.events.show', compact('event'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        if ($event->organizer_id !== auth()->id()) {
            abort(403);
        }

        return view('organizer.events.edit', compact('event'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEventRequest $request, Event $event)
    {
        if ($event->organizer_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validated();
        unset($validated['status']);

        if ($request->hasFile('banner_image')) {
            if ($event->banner_image) {
                Storage::disk('public')->delete($event->banner_image);
            }
            $validated['banner_image'] = $request->file('banner_image')->store('banners', 'public');
        }

        if ($event->status === 'active') {
            $validated['status'] = 'pending';
        }

        $event->update($validated);

        return redirect()->route('organizer.events.index')->with('success', 'Event updated successfully. Active events are sent back for admin approval after edits.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        if ($event->organizer_id !== auth()->id()) {
            abort(403);
        }

        if ($event->banner_image) {
            Storage::disk('public')->delete($event->banner_image);
        }

        $event->delete();

        return redirect()->route('organizer.events.index')->with('success', 'Event deleted successfully.');
    }
}
