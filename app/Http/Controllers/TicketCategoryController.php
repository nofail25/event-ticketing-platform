<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\TicketCategory;
use Illuminate\Http\Request;

class TicketCategoryController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(Event $event)
    {
        if ($event->organizer_id !== auth()->id()) {
            abort(403);
        }
        return view('organizer.ticket-categories.create', compact('event'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Event $event)
    {
        if ($event->organizer_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'quota' => 'required|integer|min:1',
        ]);

        $event->ticketCategories()->create($validated);

        return redirect()->route('organizer.events.show', $event)->with('success', 'Ticket category created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event, TicketCategory $ticketCategory)
    {
        if ($event->organizer_id !== auth()->id() || $ticketCategory->event_id !== $event->id) {
            abort(403);
        }

        return view('organizer.ticket-categories.edit', compact('event', 'ticketCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event, TicketCategory $ticketCategory)
    {
        if ($event->organizer_id !== auth()->id() || $ticketCategory->event_id !== $event->id) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'quota' => 'required|integer|min:1',
        ]);

        $ticketCategory->update($validated);

        return redirect()->route('organizer.events.show', $event)->with('success', 'Ticket category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event, TicketCategory $ticketCategory)
    {
        if ($event->organizer_id !== auth()->id() || $ticketCategory->event_id !== $event->id) {
            abort(403);
        }

        $ticketCategory->delete();

        return redirect()->route('organizer.events.show', $event)->with('success', 'Ticket category deleted successfully.');
    }
}
