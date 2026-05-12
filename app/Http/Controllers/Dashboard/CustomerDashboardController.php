<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class CustomerDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_orders'  => Order::where('user_id', Auth::id())->count(),
            'paid_orders'   => Order::where('user_id', Auth::id())->where('payment_status', 'paid')->count(),
            'pending_orders'=> Order::where('user_id', Auth::id())->where('payment_status', 'pending')->count(),
        ];

        $recentOrders = Order::where('user_id', Auth::id())
            ->with('ticketDetails.ticketCategory.event')
            ->latest()
            ->take(5)
            ->get();

        return view('customer.dashboard', compact('stats', 'recentOrders'));
    }
}
