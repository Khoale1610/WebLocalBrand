<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'sort_order'];

    /**
     * Một kích thước xuất hiện trong nhiều biến thể sản phẩm
     */
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }
}
