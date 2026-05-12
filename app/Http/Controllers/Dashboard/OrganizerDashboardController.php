<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;

class OrganizerDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_events'    => Event::where('organizer_id', Auth::id())->count(),
            'active_events'   => Event::where('organizer_id', Auth::id())->where('status', 'active')->count(),
            'pending_events'  => Event::where('organizer_id', Auth::id())->where('status', 'pending')->count(),
            'draft_events'    => Event::where('organizer_id', Auth::id())->where('status', 'draft')->count(),
        ];

        $recentEvents = Event::where('organizer_id', Auth::id())
            ->latest()
            ->take(5)
            ->get();

        return view('organizer.dashboard', compact('stats', 'recentEvents'));
    }
}
