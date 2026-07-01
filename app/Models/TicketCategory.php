<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'name',
        'price',
        'quota',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'quota' => 'integer',
        ];
    }

    /**
     * The event this ticket category belongs to.
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * The individual ticket details (e-tickets) for this category.
     */
    public function ticketDetails()
    {
        return $this->hasMany(TicketDetail::class);
    }

    /**
     * Get the count of tickets that are actually sold (paid) plus
     * the quantity of pending orders within the expiry window (15 mins).
     */
    public function activeSoldCount(): int
    {
        $paidCount = $this->ticketDetails()->count();
        $pendingQuantity = Order::where('ticket_category_id', $this->id)
            ->where('payment_status', 'pending')
            ->where('created_at', '>=', now()->subMinutes(15))
            ->sum('quantity');

        return $paidCount + $pendingQuantity;
    }

    /**
     * Get the number of tickets currently available to buy.
     */
    public function availableQuota(): int
    {
        return max(0, $this->quota - $this->activeSoldCount());
    }
}
