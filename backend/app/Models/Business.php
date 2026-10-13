<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'name',
        'owner_name',
        'description',
        'gst_number',
        'website',
        'email',
        'phone',
        'whatsapp',
        'facebook',
        'instagram',
        'linkedin',
        'location',
        'google_maps_embed',
        'opening_time',
        'closing_time',
        'state',
        'city',
        'address',
        'pin_code',
        'status', // pending, approved, rejected
        'views_count',
        'logo',
        'cover_image',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category()
    {
        return $this->belongsTo(BusinessCategory::class, 'category_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function inquiries()
    {
        return $this->hasMany(Inquiry::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function gallery()
    {
        return $this->hasMany(Gallery::class);
    }

    public function averageRating()
    {
        return round($this->reviews()->avg('rating') ?? 0, 1);
    }
}
