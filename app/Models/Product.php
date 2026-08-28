<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'price',
        'description',
        'image',
        'stock',
        'is_featured',
    ];

    /**
     * Danh mục của sản phẩm
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Một sản phẩm có nhiều biến thể (Size + Màu)
     */
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    /**
     * Chi tiết các đơn hàng chứa sản phẩm này
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Định dạng giá hiển thị VND
     */
    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 0, ',', '.') . ' ₫';
    }
}
