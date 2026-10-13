<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    protected $fillable = [
        'customer_id',
        'business_id',
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'preferred_contact_time',
        'status', // pending, replied, closed
        'reply_message',
        'replied_at',
    ];

    protected $casts = [
        'replied_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}
