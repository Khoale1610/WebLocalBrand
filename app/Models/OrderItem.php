<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'product_variant_id',
        'product_name',
        'variant_info',
        'price',
        'quantity',
        'total_price',
    ];

    /**
     * Đơn hàng chứa item này
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Sản phẩm gốc
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Biến thể cụ thể (Size, Color) nếu có
     */
    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    /**
     * Định dạng đơn giá VND
     */
    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 0, ',', '.') . ' ₫';
    }

    /**
     * Định dạng thành tiền VND
     */
    public function getFormattedTotalPriceAttribute()
    {
        return number_format($this->total_price, 0, ',', '.') . ' ₫';
    }
}
