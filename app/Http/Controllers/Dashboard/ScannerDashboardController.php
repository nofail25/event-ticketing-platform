<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\TicketDetail;

class ScannerDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_scanned' => TicketDetail::where('is_scanned', true)->count(),
            'total_tickets' => TicketDetail::count(),
        ];

        return view('gate.dashboard', compact('stats'));
    }
}
