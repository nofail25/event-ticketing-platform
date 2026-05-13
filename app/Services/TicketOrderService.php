<?php

namespace App\Services;

use App\Models\Order;
use App\Models\TicketCategory;
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
     * @return Order
     * @throws \Exception
     */
    public function processOrder(int $ticketCategoryId, int $quantity, int $userId): Order
    {
        return DB::transaction(function () use ($ticketCategoryId, $quantity, $userId) {
            // Fetch the ticket category with its event
            $ticketCategory = TicketCategory::with('event')
                ->lockForUpdate()
                ->findOrFail($ticketCategoryId);

            // Calculate available quota
            $sold = $ticketCategory->ticketDetails()->count();
            $available = $ticketCategory->quota - $sold;

            // Validate quantity against available quota
            if ($quantity > $available) {
                throw new \Exception("Only {$available} tickets available.");
            }

            // Calculate total amount
            $totalAmount = $ticketCategory->price * $quantity;

            // Generate unique invoice number (INV-YYYYMMDD-XXXX)
            $invoiceNumber = 'INV-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4));

            // Create the order
            $order = Order::create([
                'user_id' => $userId,
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

            return $order;
        });
    }
}
