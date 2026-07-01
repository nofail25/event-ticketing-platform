<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\TicketDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        $events = $query->paginate(8)->withQueryString();

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

        return view('events.public-show', [
            'event' => $event,
        ]);
    }

    /**
     * Fetch search suggestions via AJAX.
     */
    public function searchSuggestions(Request $request)
    {
        $search = $request->get('q', '');
        
        $query = Event::where('status', 'active');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        $events = $query->select('id', 'title', 'location', 'start_time')
            ->take(5)
            ->get();

        // Format data to include the generated URL
        $formattedEvents = $events->map(function ($event) {
            return [
                'id' => $event->id,
                'title' => $event->title,
                'location' => $event->location,
                'start_time' => $event->start_time->format('d M Y'),
                'url' => route('events.show', $event->id)
            ];
        });

        return response()->json($formattedEvents);
    }
}
