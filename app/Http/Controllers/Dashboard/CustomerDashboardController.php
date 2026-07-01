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

        $orderStats = Order::where('user_id', $user->id)
            ->selectRaw('COUNT(*) as total, SUM(payment_status = "paid") as paid, SUM(payment_status = "pending") as pending')
            ->first();

        $stats = [
            'total_orders'  => $orderStats->total ?? 0,
            'paid_orders'   => $orderStats->paid ?? 0,
            'pending_orders'=> $orderStats->pending ?? 0,
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

        return view('customer.dashboard', compact('stats', 'recentOrders'));
    }
}
