<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProcessCheckoutRequest;
use App\Models\TicketCategory;
use App\Services\TicketOrderService;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    /**
     * Show the checkout form for a specific ticket category.
     */
    public function create(TicketCategory $ticketCategory)
    {
        // Eager load event and organizer
        $ticketCategory->load('event.organizer');

        // Calculate available quota (quota - already sold tickets)
        $sold = $ticketCategory->ticketDetails->count();
        $available = $ticketCategory->quota - $sold;

        // Abort if no tickets available
        if ($available <= 0) {
            abort(404, 'This ticket category is sold out.');
        }

        // Get the maximum quantity user can buy (5 or available, whichever is less)
        $maxQuantity = min(5, $available);

        return view('customer.checkout', [
            'ticketCategory' => $ticketCategory,
            'event' => $ticketCategory->event,
            'available' => $available,
            'maxQuantity' => $maxQuantity,
        ]);
    }

    /**
     * Process the checkout and create the order.
     */
    public function store(ProcessCheckoutRequest $request, TicketOrderService $orderService)
    {
        $validated = $request->validated();
        $ticketCategoryId = $validated['ticket_category_id'];
        $quantity = $validated['quantity'];
        $paymentMethod = $validated['payment_method'];
        $paymentChannel = $validated['payment_channel'];
        $userId = Auth::id();

        try {
            $orderService->processOrder($ticketCategoryId, $quantity, $userId, $paymentMethod, $paymentChannel);

            return redirect()
                ->route('customer.dashboard')
                ->with('success', "Successfully purchased {$quantity} ticket(s)! Your e-tickets are ready.");
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['quantity' => $e->getMessage()])
                ->withInput();
        }
    }
}
