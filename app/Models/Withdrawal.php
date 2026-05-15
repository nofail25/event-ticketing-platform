<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'status',
        'bank_info',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'bank_info' => 'array',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
