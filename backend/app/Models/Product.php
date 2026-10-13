<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'business_id',
        'category_id',
        'name',
        'description',
        'price',
        'discount',
        'stock',
        'sku',
        'images',
        'availability',
        'tags',
        'specifications',
        'status',
    ];

    protected $casts = [
        'images' => 'array',
        'tags' => 'array',
        'specifications' => 'array',
        'price' => 'decimal:2',
        'discount' => 'decimal:2',
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function finalPrice()
    {
        if ($this->discount > 0) {
            return round($this->price - ($this->price * ($this->discount / 100)), 2);
        }
        return $this->price;
    }
}
