<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\TicketCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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
    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'ticket_category_id' => 'required|exists:ticket_categories,id',
            'quantity' => 'required|integer|min:1|max:5',
        ]);

        $ticketCategoryId = $validated['ticket_category_id'];
        $quantity = $validated['quantity'];

        // Start a database transaction
        return DB::transaction(function () use ($ticketCategoryId, $quantity) {
            // Fetch the ticket category with its event
            $ticketCategory = TicketCategory::with('event')
                ->lockForUpdate()
                ->findOrFail($ticketCategoryId);

            // Calculate available quota
            $sold = $ticketCategory->ticketDetails->count();
            $available = $ticketCategory->quota - $sold;

            // Validate quantity against available quota
            if ($quantity > $available) {
                return redirect()->back()
                    ->withErrors(['quantity' => "Only {$available} tickets available."])
                    ->withInput();
            }

            // Calculate total amount
            $totalAmount = $ticketCategory->price * $quantity;

            // Generate unique invoice number (INV-YYYYMMDD-XXXX)
            $invoiceNumber = 'INV-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4));

            // Create the order
            $order = Order::create([
                'user_id' => Auth::id(),
                'invoice_number' => $invoiceNumber,
                'total_amount' => $totalAmount,
                'payment_status' => 'paid', // Simulated as paid
            ]);

            // Create ticket details (e-tickets) for each quantity
            for ($i = 0; $i < $quantity; $i++) {
                $order->ticketDetails()->create([
                    'ticket_category_id' => $ticketCategoryId,
                    'barcode_string' => (string) Str::uuid(),
                    'is_scanned' => false,
                ]);
            }

            // Decrement the quota
            $ticketCategory->decrement('quota', $quantity);

            // Redirect with success message
            return redirect()
                ->route('customer.dashboard')
                ->with('success', "Successfully purchased {$quantity} ticket(s)! Your e-tickets are ready.");
        });
    }
}
