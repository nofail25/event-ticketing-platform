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
        'payment_method',
        'payment_channel',
        'platform_fee',
    ];

    protected function casts(): array
    {
        return [
            'total_amount' => 'decimal:2',
            'platform_fee' => 'decimal:2',
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
            'qris_bca_mobile' => 'BCA Mobile',
            'qris_gopay' => 'GoPay QRIS',
            'qris_shopeepay' => 'ShopeePay QRIS',
            'va_bca' => 'BCA',
            'va_mandiri' => 'Mandiri',
            'va_bri' => 'BRI',
            'va_bni' => 'BNI',
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
