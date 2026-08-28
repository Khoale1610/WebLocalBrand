<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'hex_code'];

    /**
     * Một màu sắc xuất hiện trong nhiều biến thể sản phẩm
     */
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }
}
