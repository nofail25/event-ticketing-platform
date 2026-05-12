<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'ticket_category_id',
        'barcode_string',
        'is_scanned',
        'scanned_at',
    ];

    protected function casts(): array
    {
        return [
            'is_scanned' => 'boolean',
            'scanned_at' => 'datetime',
        ];
    }

    /**
     * The order this ticket detail belongs to.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * The ticket category for this ticket detail.
     */
    public function ticketCategory()
    {
        return $this->belongsTo(TicketCategory::class);
    }
}
