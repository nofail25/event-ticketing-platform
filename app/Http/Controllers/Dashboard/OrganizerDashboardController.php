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

        $eventStats = Event::where('organizer_id', $organizerId)
            ->selectRaw('COUNT(*) as total, SUM(status = "active") as active, SUM(status = "pending") as pending, SUM(status = "draft") as draft')
            ->first();

        $stats = [
            'total_events'    => $eventStats->total ?? 0,
            'active_events'   => $eventStats->active ?? 0,
            'pending_events'  => $eventStats->pending ?? 0,
            'draft_events'    => $eventStats->draft ?? 0,
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

        $profile = Auth::user()->organizerProfile;

        return view('organizer.dashboard', compact('stats', 'wallet', 'recentWithdrawals', 'recentEvents', 'profile'));
    }
}
