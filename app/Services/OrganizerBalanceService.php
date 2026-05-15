<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Withdrawal;

class OrganizerBalanceService
{
    public const PLATFORM_FEE_PERCENTAGE = 5;

    public function summaryFor(int $organizerId): array
    {
        $grossRevenue = $this->grossRevenueFor($organizerId);
        $platformFee = round($grossRevenue * (self::PLATFORM_FEE_PERCENTAGE / 100), 2);
        $netRevenue = max(round($grossRevenue - $platformFee, 2), 0);
        $reservedWithdrawals = $this->reservedWithdrawalsFor($organizerId);
        $currentBalance = max(round($netRevenue - $reservedWithdrawals, 2), 0);

        return [
            'gross_revenue' => $grossRevenue,
            'platform_fee_percentage' => self::PLATFORM_FEE_PERCENTAGE,
            'platform_fee_amount' => $platformFee,
            'net_revenue' => $netRevenue,
            'reserved_withdrawals' => $reservedWithdrawals,
            'current_balance' => $currentBalance,
        ];
    }

    public function withdrawableBalanceFor(int $organizerId): float
    {
        return $this->summaryFor($organizerId)['current_balance'];
    }

    private function grossRevenueFor(int $organizerId): float
    {
        return (float) Order::query()
            ->join('ticket_details', 'orders.id', '=', 'ticket_details.order_id')
            ->join('ticket_categories', 'ticket_details.ticket_category_id', '=', 'ticket_categories.id')
            ->join('events', 'ticket_categories.event_id', '=', 'events.id')
            ->where('events.organizer_id', $organizerId)
            ->where('orders.payment_status', 'paid')
            ->sum('ticket_categories.price');
    }

    private function reservedWithdrawalsFor(int $organizerId): float
    {
        return (float) Withdrawal::query()
            ->where('user_id', $organizerId)
            ->whereIn('status', ['pending', 'completed'])
            ->sum('amount');
    }
}
