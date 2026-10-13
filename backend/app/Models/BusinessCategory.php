<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessCategory extends Model
{
    protected $fillable = ['name', 'slug', 'icon', 'description', 'status'];

    public function businesses()
    {
        return $this->hasMany(Business::class, 'category_id');
    }
}
