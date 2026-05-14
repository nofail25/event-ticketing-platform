<?php

namespace App\Http\Controllers;

use App\Models\Order;

class AdminOrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'ticketDetails.ticketCategory.event'])
            ->latest()
            ->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }
}
