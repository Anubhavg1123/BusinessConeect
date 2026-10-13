<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'customer_id',
        'business_id',
        'rating',
        'review_text',
        'images',
        'likes_count',
        'status',
        'reported',
    ];

    protected $casts = [
        'images' => 'array',
        'reported' => 'boolean',
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
