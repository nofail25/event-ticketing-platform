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
}
