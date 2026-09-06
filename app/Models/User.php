<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Danh sách đơn hàng mà người dùng này đã đặt
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Kiểm tra người dùng có phải là Quản trị viên (Admin) hay không
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Ghi chú: Liên kết 1-Nhiều với bảng wishlists 
     * (Một người dùng có thể có nhiều mục wishlist)
     */
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    /**
     * Ghi chú: Lấy trực tiếp danh sách các Sản phẩm (Product) mà người dùng đã yêu thích 
     * thông qua bảng trung gian 'wishlists'
     */
    public function favoriteProducts()
    {
        return $this->belongsToMany(Product::class, 'wishlists', 'user_id', 'product_id')->withTimestamps();
    }
}
