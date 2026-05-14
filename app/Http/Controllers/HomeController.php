<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\TicketDetail;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display a listing of public active events (catalog).
     */
    public function index(Request $request)
    {
        $query = Event::where('status', 'active')
            ->with(['organizer', 'ticketCategories']);

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        $events = $query->paginate(9)->withQueryString();

        return view('welcome', ['events' => $events]);
    }

    /**
     * Display the specified event details.
     */
    public function show(Event $event)
    {
        // Only show active events to the public
        if ($event->status !== 'active') {
            abort(404);
        }

        // Eager load relationships
        $event->load(['organizer', 'ticketCategories']);

        $userEventTickets = collect();

        if (auth()->check()) {
            $userEventTickets = TicketDetail::query()
                ->with(['order', 'ticketCategory'])
                ->whereHas('order', function ($query) {
                    $query->where('user_id', auth()->id())
                          ->where('payment_status', 'paid');
                })
                ->whereHas('ticketCategory', function ($query) use ($event) {
                    $query->where('event_id', $event->id);
                })
                ->latest()
                ->get();
        }

        return view('events.public-show', [
            'event' => $event,
            'userEventTickets' => $userEventTickets,
        ]);
    }
}
