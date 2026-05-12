<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'invoice_number',
        'total_amount',
        'payment_status',
    ];

    protected function casts(): array
    {
        return [
            'total_amount' => 'decimal:2',
        ];
    }

    /**
     * The customer (User) who placed this order.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The individual e-tickets associated with this order.
     */
    public function ticketDetails()
    {
        return $this->hasMany(TicketDetail::class);
    }
}
