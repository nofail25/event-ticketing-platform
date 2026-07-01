<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ticket_category_id',
        'quantity',
        'invoice_number',
        'total_amount',
        'payment_status',
        'payment_method',
        'payment_channel',
        'platform_fee',
    ];

    protected function casts(): array
    {
        return [
            'total_amount' => 'decimal:2',
            'platform_fee' => 'decimal:2',
            'quantity' => 'integer',
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
     * The ticket category for this order.
     */
    public function ticketCategory()
    {
        return $this->belongsTo(TicketCategory::class);
    }

    /**
     * The individual e-tickets associated with this order.
     */
    public function ticketDetails()
    {
        return $this->hasMany(TicketDetail::class);
    }

    public function getPaymentMethodLabelAttribute(): string
    {
        return match ($this->payment_method) {
            'qris' => 'QRIS',
            'virtual_account' => 'Virtual Account',
            'e_wallet' => 'E-Wallet',
            default => 'Payment',
        };
    }

    public function getPaymentChannelLabelAttribute(): ?string
    {
        return match ($this->payment_channel) {
            'qris_universal' => 'QRIS Universal',
            'va_bca' => 'BCA Virtual Account',
            'va_mandiri' => 'Mandiri Virtual Account',
            'va_bri' => 'BRI Virtual Account',
            'va_bni' => 'BNI Virtual Account',
            'wallet_dana' => 'DANA',
            'wallet_gopay' => 'GoPay',
            'wallet_ovo' => 'OVO',
            'wallet_shopeepay' => 'ShopeePay',
            default => null,
        };
    }

    public function getPaymentDisplayLabelAttribute(): string
    {
        if (! $this->payment_channel_label) {
            return $this->payment_method_label;
        }

        return "{$this->payment_method_label} - {$this->payment_channel_label}";
    }
}
