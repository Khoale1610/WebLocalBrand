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

    /**
     * Ghi chú: Liên kết 1-Nhiều với bảng wishlists
     * (Một sản phẩm có thể nằm trong danh sách yêu thích của nhiều khách hàng khác nhau)
     */
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    /**
     * Ghi chú: Lấy trực tiếp danh sách các Khách hàng (User) đã thêm sản phẩm này vào yêu thích
     * thông qua bảng trung gian 'wishlists'
     */
    public function favoritedByUsers()
    {
        return $this->belongsToMany(User::class, 'wishlists', 'product_id', 'user_id')->withTimestamps();
    }
}
