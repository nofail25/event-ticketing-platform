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

        if ($ticketCategory->event->status !== 'active') {
            abort(404);
        }

        if ($ticketCategory->event->end_time < now()) {
            abort(403, 'Pemesanan ditutup karena event sudah berakhir.');
        }

        // Calculate available quota (accounting for paid and unexpired pending orders)
        $available = $ticketCategory->availableQuota();

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

        // Load ticket category so we can redirect back to it on failure
        $ticketCategory = \App\Models\TicketCategory::findOrFail($ticketCategoryId);

        try {
            $order = $orderService->processOrder($ticketCategoryId, $quantity, $userId, $paymentMethod, $paymentChannel);

            // Redirect to the simulated payment gateway page
            return redirect()->route('checkout.payment', $order->id);
            
        } catch (\Exception $e) {
            // Redirect explicitly back to checkout page (not ->back()) so the URL is always correct.
            // Also flash the step so Alpine can restore to step 3 if needed.
            return redirect()
                ->route('checkout.create', $ticketCategory)
                ->withErrors(['checkout_error' => $e->getMessage()])
                ->withInput()
                ->with('step', 3);
        }
    }

    /**
     * Show the simulated payment gateway page.
     */
    public function payment(\App\Models\Order $order)
    {
        // Ensure the order belongs to the user and is still pending
        if ($order->user_id !== Auth::id() || $order->payment_status !== 'pending') {
            return redirect()->route('home')->with('error', 'Pesanan tidak valid atau sudah dibayar.');
        }

        $order->load('ticketCategory.event');
        $event = $order->ticketCategory?->event;

        if (! $event || $event->status !== 'active') {
            $order->update(['payment_status' => 'failed']);
            return redirect()->route('home')->with('error', 'Event tidak lagi tersedia. Pesanan dibatalkan.');
        }

        if ($event->end_time->isPast()) {
            $order->update(['payment_status' => 'failed']);
            return redirect()->route('home')->with('error', 'Event sudah berakhir. Pesanan dibatalkan.');
        }

        // Check if pending order has expired (older than 15 minutes)
        if ($order->created_at->copy()->addMinutes(15)->isPast()) {
            $order->update(['payment_status' => 'failed']);
            return redirect()->route('home')->with('error', 'Waktu pembayaran telah habis (melewati 15 menit). Pesanan dibatalkan.');
        }

        return view('customer.payment', [
            'order' => $order,
            'event' => $event,
        ]);
    }

    /**
     * Simulate a successful payment and complete the order.
     */
    public function pay(\App\Models\Order $order, TicketOrderService $orderService)
    {
        if ($order->user_id !== Auth::id() || $order->payment_status !== 'pending') {
            abort(403);
        }

        $order->load('ticketCategory.event');
        $event = $order->ticketCategory?->event;

        if (! $event || $event->status !== 'active') {
            $order->update(['payment_status' => 'failed']);
            return redirect()->route('home')->with('error', 'Event tidak lagi tersedia. Pesanan dibatalkan.');
        }

        if ($event->end_time->isPast()) {
            $order->update(['payment_status' => 'failed']);
            return redirect()->route('home')->with('error', 'Event sudah berakhir. Pesanan dibatalkan.');
        }

        if ($order->created_at->copy()->addMinutes(15)->isPast()) {
            $order->update(['payment_status' => 'failed']);
            return redirect()->route('home')->with('error', 'Waktu pembayaran telah habis (melewati 15 menit). Pesanan dibatalkan.');
        }

        try {
            // Mark as paid and notify user
            $orderService->completeOrder($order);
        } catch (\Exception $e) {
            // If completing the order fails for any reason, redirect back to payment page with error
            return redirect()
                ->route('checkout.payment', $order)
                ->with('pay_error', 'Terjadi kesalahan saat memproses pembayaran: ' . $e->getMessage());
        }

        $order->load('ticketCategory.event');
        $event = $order->ticketCategory?->event;
        $quantity = $order->quantity;

        if (!$event) {
            // Fallback if event relationship is missing
            return redirect()->route('customer.dashboard')
                ->with('success', "Pembayaran berhasil! {$quantity} tiket Anda sudah aktif.");
        }

        return redirect()
            ->route('events.show', $event)
            ->with('payment_success', [
                'title'          => 'Pembayaran Berhasil',
                'message'        => "{$quantity} tiket Anda sudah aktif dan siap digunakan.",
                'invoice_number' => $order->invoice_number,
                'ticket_url'     => route('customer.dashboard') . '#my-tickets',
            ]);
    }
}
