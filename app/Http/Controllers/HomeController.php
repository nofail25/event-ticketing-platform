<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::with(['organizer', 'ticketCategories'])
            ->where('status', 'active');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        $events = $query->latest('start_time')->paginate(9);

        return view('welcome', compact('events'));
    }

    public function show(Event $event)
    {
        if ($event->status !== 'active') {
            abort(404);
        }

        $event->load(['organizer', 'ticketCategories']);

        return view('events.public-show', compact('event'));
    }
}
