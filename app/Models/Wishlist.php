<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;

    // Ghi chú: Khai báo các cột được phép thêm dữ liệu
    protected $fillable = [
        'user_id',
        'product_id'
    ];

    /**
     * Quan hệ: Một mục yêu thích thuộc về một người dùng cụ thể
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Quan hệ: Một mục yêu thích tương ứng với một sản phẩm cụ thể
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}