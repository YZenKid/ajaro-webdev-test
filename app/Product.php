<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'desc',
        'category_id',
        'price_purchase',
        'price_sale',
        'stock'
    ];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
