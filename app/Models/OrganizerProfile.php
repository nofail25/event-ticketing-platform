<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizerProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_name',
        'company_description',
        'pic_name',
        'phone_number',
        'address',
        'website_url',
        'legal_document_path',
        'bank_name',
        'account_number',
        'account_holder',
        'verification_status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
