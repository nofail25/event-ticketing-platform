<?php

namespace App\Services;

use App\Models\Order;
use App\Models\TicketCategory;
use App\Models\User;
use App\Notifications\OrderPaid;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TicketOrderService
{
    /**
     * Process the ticket order and create necessary records.
     *
     * @param int $ticketCategoryId
     * @param int $quantity
     * @param int $userId
     * @param string $paymentMethod
     * @param string $paymentChannel
     * @return Order
     * @throws \Exception
     */
    public function processOrder(int $ticketCategoryId, int $quantity, int $userId, string $paymentMethod, string $paymentChannel): Order
    {
        return DB::transaction(function () use ($ticketCategoryId, $quantity, $userId, $paymentMethod, $paymentChannel) {
            // Fetch the ticket category with its event
            $ticketCategory = TicketCategory::with('event')
                ->lockForUpdate()
                ->findOrFail($ticketCategoryId);

            if (! $ticketCategory->event || $ticketCategory->event->status !== 'active') {
                throw new \Exception("Event tidak tersedia untuk pembelian tiket.");
            }

            if ($ticketCategory->event->end_time < now()) {
                throw new \Exception("Pemesanan ditutup karena event sudah berakhir.");
            }

            // Calculate active sold tickets: 
            // 1. Paid tickets
            $paidTicketsCount = $ticketCategory->ticketDetails()->count();
            
            // 2. Pending orders that haven't expired (e.g., 15 minutes)
            $pendingOrdersQuantity = Order::where('ticket_category_id', $ticketCategoryId)
                ->where('payment_status', 'pending')
                ->where('created_at', '>=', now()->subMinutes(15))
                ->sum('quantity');

            $sold = $paidTicketsCount + $pendingOrdersQuantity;
            $available = $ticketCategory->quota - $sold;

            // Validate quantity against available quota
            if ($quantity > $available) {
                throw new \Exception("Only {$available} tickets available.");
            }

            // Check user's total tickets for this event
            $existingTickets = Order::where('user_id', $userId)
                ->whereHas('ticketCategory', function ($q) use ($ticketCategory) {
                    $q->where('event_id', $ticketCategory->event_id);
                })
                ->where(function ($q) {
                    $q->where('payment_status', 'paid')
                      ->orWhere(function ($sub) {
                          $sub->where('payment_status', 'pending')
                              ->where('created_at', '>=', now()->subMinutes(15));
                      });
                })
                ->sum('quantity');

            if ($existingTickets + $quantity > 5) {
                throw new \Exception("Anda hanya dapat membeli maksimal 5 tiket per event. Anda sudah memiliki {$existingTickets} pesanan aktif.");
            }

            // Calculate total amount
            $subtotal = $ticketCategory->price * $quantity;
            $platformFeePercentage = \App\Services\OrganizerBalanceService::PLATFORM_FEE_PERCENTAGE;
            $platformFee = $subtotal * ($platformFeePercentage / 100);
            $totalAmount = $subtotal; // Customer pays the exact ticket price

            // Generate unique invoice number (INV-YYYYMMDD-XXXX)
            do {
                $invoiceNumber = 'INV-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));
            } while (Order::where('invoice_number', $invoiceNumber)->exists());

            // Create the order
            $order = Order::create([
                'user_id' => $userId,
                'ticket_category_id' => $ticketCategoryId,
                'quantity' => $quantity,
                'invoice_number' => $invoiceNumber,
                'total_amount' => $totalAmount,
                'payment_status' => 'pending', // Starts as pending
                'payment_method' => $paymentMethod,
                'payment_channel' => $paymentChannel,
                'platform_fee' => $platformFee,
            ]);

            // DO NOT create ticket details here anymore to prevent hoarding.
            // They will be generated in completeOrder() once paid.

            return $order;
        });
    }

    /**
     * Complete the order by marking it as paid and generating tickets.
     */
    public function completeOrder(Order $order): void
    {
        DB::transaction(function () use ($order) {
            // BUG-03 FIX: Lock the row first to prevent double-payment race condition
            $lockedOrder = Order::lockForUpdate()->findOrFail($order->id);

            if ($lockedOrder->payment_status !== 'pending') {
                return;
            }

            $ticketCategory = TicketCategory::with('event')
                ->lockForUpdate()
                ->findOrFail($lockedOrder->ticket_category_id);

            if (! $ticketCategory->event || $ticketCategory->event->status !== 'active') {
                throw new \Exception("Event tidak lagi tersedia untuk pembayaran.");
            }

            if ($ticketCategory->event->end_time < now()) {
                throw new \Exception("Pembayaran ditolak karena event sudah berakhir.");
            }

            $paidTicketsCount = $ticketCategory->ticketDetails()->count();
            if ($paidTicketsCount + $lockedOrder->quantity > $ticketCategory->quota) {
                throw new \Exception("Kuota tiket tidak lagi tersedia.");
            }

            $lockedOrder->update(['payment_status' => 'paid']);

            // Generate ticket details (e-tickets) for each quantity now that it's paid
            if ($lockedOrder->ticketDetails()->count() === 0) {
                for ($i = 0; $i < $lockedOrder->quantity; $i++) {
                    $lockedOrder->ticketDetails()->create([
                        'ticket_category_id' => $lockedOrder->ticket_category_id,
                        'barcode_string' => (string) Str::uuid(),
                        'is_scanned' => false,
                    ]);
                }
            }

            $user = User::find($lockedOrder->user_id);
            if ($user) {
                $user->notify(new OrderPaid($lockedOrder));
            }
        });
    }
}
