<?php

namespace App\Http\Controllers;

use App\Models\Event;

class AdminEventController extends Controller
{
    public function index()
    {
        $events = Event::with('organizer')
            ->orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END")
            ->latest()
            ->paginate(15);

        return view('admin.events.index', compact('events'));
    }

    public function approve(Event $event)
    {
        $event->update(['status' => 'active']);

        return back()->with('success', "\"{$event->title}\" has been approved and published.");
    }
}
