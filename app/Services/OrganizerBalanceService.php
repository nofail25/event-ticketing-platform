<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Withdrawal;

class OrganizerBalanceService
{
    public const PLATFORM_FEE_PERCENTAGE = 5;

    public function summaryFor(int $organizerId): array
    {
        // Calculate total gross and fee directly from orders table
        $totalGross = (float) Order::query()
            ->join('ticket_categories', 'orders.ticket_category_id', '=', 'ticket_categories.id')
            ->join('events', 'ticket_categories.event_id', '=', 'events.id')
            ->where('events.organizer_id', $organizerId)
            ->where('orders.payment_status', 'paid')
            ->sum('orders.total_amount');

        $totalFee = (float) Order::query()
            ->join('ticket_categories', 'orders.ticket_category_id', '=', 'ticket_categories.id')
            ->join('events', 'ticket_categories.event_id', '=', 'events.id')
            ->where('events.organizer_id', $organizerId)
            ->where('orders.payment_status', 'paid')
            ->sum('orders.platform_fee');
        
        $withdrawableGross = (float) Order::query()
            ->join('ticket_categories', 'orders.ticket_category_id', '=', 'ticket_categories.id')
            ->join('events', 'ticket_categories.event_id', '=', 'events.id')
            ->where('events.organizer_id', $organizerId)
            ->where('orders.payment_status', 'paid')
            ->where('events.end_time', '<=', now())
            ->sum('orders.total_amount');

        $withdrawableFee = (float) Order::query()
            ->join('ticket_categories', 'orders.ticket_category_id', '=', 'ticket_categories.id')
            ->join('events', 'ticket_categories.event_id', '=', 'events.id')
            ->where('events.organizer_id', $organizerId)
            ->where('orders.payment_status', 'paid')
            ->where('events.end_time', '<=', now())
            ->sum('orders.platform_fee');
            
        $withdrawableNet = max(round($withdrawableGross - $withdrawableFee, 2), 0);

        $reservedWithdrawals = $this->reservedWithdrawalsFor($organizerId);
        
        $currentBalance = max(round($withdrawableNet - $reservedWithdrawals, 2), 0);

        return [
            'gross_revenue' => $totalGross,
            'withdrawable_gross' => $withdrawableGross,
            'platform_fee_percentage' => self::PLATFORM_FEE_PERCENTAGE,
            'platform_fee_amount' => $totalFee,
            'withdrawable_fee_amount' => $withdrawableFee,
            'reserved_withdrawals' => $reservedWithdrawals,
            'current_balance' => $currentBalance,
        ];
    }

    public function withdrawableBalanceFor(int $organizerId): float
    {
        return $this->summaryFor($organizerId)['current_balance'];
    }

    private function reservedWithdrawalsFor(int $organizerId): float
    {
        return (float) Withdrawal::query()
            ->where('user_id', $organizerId)
            ->whereIn('status', ['pending', 'completed']) // 'pending' and 'completed' withdrawals MUST be subtracted from the lifetime gross.
            ->sum('amount');
    }
}
