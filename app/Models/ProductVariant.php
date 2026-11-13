<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'size_id',
        'color_id',
        'sku',
        'price',
        'stock',
        'image',
    ];

    protected $appends = [
        'effective_price',
        'image_url',
    ];

    /**
     * Sản phẩm gốc
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Kích thước (Size)
     */
    public function size()
    {
        return $this->belongsTo(Size::class);
    }

    /**
     * Màu sắc (Color)
     */
    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    /**
     * Chi tiết các đơn hàng chứa biến thể này
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'product_variant_id');
    }

    /**
     * Lấy giá thực tế (Nếu biến thể không có giá riêng thì lấy giá sản phẩm gốc)
     */
    public function getEffectivePriceAttribute()
    {
        return $this->price ?? $this->product->price ?? 0;
    }

    /**
     * Định dạng giá hiển thị VND
     */
    public function getFormattedPriceAttribute()
    {
        return number_format($this->effective_price, 0, ',', '.') . ' ₫';
    }

    /**
     * Đường dẫn ảnh hợp lệ (nếu biến thể có ảnh riêng thì dùng, không thì lấy ảnh sản phẩm cha)
     */
    public function getImageUrlAttribute()
    {
        $img = $this->image ?? $this->product->image ?? null;
        if (!$img) {
            return 'https://via.placeholder.com/400x500?text=No+Image';
        }
        if (str_starts_with($img, 'http://') || str_starts_with($img, 'https://')) {
            return $img;
        }
        return asset($img);
    }
}
