<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Withdrawal;
use App\Services\OrganizerBalanceService;
use Illuminate\Support\Facades\Auth;

class OrganizerDashboardController extends Controller
{
    public function __construct(private readonly OrganizerBalanceService $balanceService)
    {
    }

    public function index()
    {
        $organizerId = Auth::id();

        $stats = [
            'total_events'    => Event::where('organizer_id', $organizerId)->count(),
            'active_events'   => Event::where('organizer_id', $organizerId)->where('status', 'active')->count(),
            'pending_events'  => Event::where('organizer_id', $organizerId)->where('status', 'pending')->count(),
            'draft_events'    => Event::where('organizer_id', $organizerId)->where('status', 'draft')->count(),
        ];

        $wallet = $this->balanceService->summaryFor($organizerId);

        $recentWithdrawals = Withdrawal::query()
            ->where('user_id', $organizerId)
            ->latest()
            ->take(3)
            ->get();

        $recentEvents = Event::where('organizer_id', $organizerId)
            ->latest()
            ->take(5)
            ->get();

        return view('organizer.dashboard', compact('stats', 'wallet', 'recentWithdrawals', 'recentEvents'));
    }
}
