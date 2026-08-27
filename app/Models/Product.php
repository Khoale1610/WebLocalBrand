<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['category_id', 'name', 'slug', 'price', 'description', 'image', 'is_featured'];

    // Một sản phẩm có nhiều biến thể
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }
}
