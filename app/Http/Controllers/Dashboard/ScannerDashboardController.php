<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\TicketDetail;

class ScannerDashboardController extends Controller
{
    public function index()
    {
        $organizerId = auth()->user()->organizer_id;

        $stats = [
            'total_scanned' => TicketDetail::whereHas('ticketCategory.event', function ($q) use ($organizerId) {
                $q->where('organizer_id', $organizerId);
            })->where('is_scanned', true)->count(),
            'total_tickets' => TicketDetail::whereHas('ticketCategory.event', function ($q) use ($organizerId) {
                $q->where('organizer_id', $organizerId);
            })->count(),
        ];

        return view('gate.dashboard', compact('stats'));
    }
}
