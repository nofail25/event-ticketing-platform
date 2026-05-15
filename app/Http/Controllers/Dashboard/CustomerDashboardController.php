<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class CustomerDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $stats = [
            'total_orders'  => Order::where('user_id', $user->id)->count(),
            'paid_orders'   => Order::where('user_id', $user->id)->where('payment_status', 'paid')->count(),
            'pending_orders'=> Order::where('user_id', $user->id)->where('payment_status', 'pending')->count(),
        ];

        // Fetch all orders with their related e-tickets
        $recentOrders = Order::where('user_id', $user->id)
            ->with([
                'ticketDetails' => function ($query) {
                    $query->with('ticketCategory.event');
                }
            ])
            ->latest()
            ->get();

        // Fetch recent notifications
        $notifications = $user->notifications()->latest()->get();

        return view('customer.dashboard', compact('stats', 'recentOrders', 'notifications'));
    }
}
