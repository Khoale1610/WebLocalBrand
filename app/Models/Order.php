<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // Trạng thái đơn hàng
    const STATUS_PENDING    = 'pending';    // Chờ xử lý / xác nhận
    const STATUS_PROCESSING = 'processing'; // Đang xử lý / đóng gói
    const STATUS_SHIPPING   = 'shipping';   // Đang giao hàng
    const STATUS_COMPLETED  = 'completed';  // Đã giao / hoàn thành
    const STATUS_CANCELLED  = 'cancelled';  // Đã hủy

    // Trạng thái thanh toán
    const PAYMENT_UNPAID    = 'unpaid';     // Chưa thanh toán
    const PAYMENT_PAID      = 'paid';       // Đã thanh toán
    const PAYMENT_FAILED    = 'failed';     // Thanh toán thất bại
    const PAYMENT_REFUNDED  = 'refunded';   // Đã hoàn tiền

    protected $fillable = [
        'user_id',
        'order_code',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_address',
        'customer_city',
        'customer_district',
        'customer_notes',
        'total_amount',
        'shipping_fee',
        'discount_amount',
        'payment_method',
        'payment_status',
        'order_status',
    ];

    /**
     * Khách hàng đặt đơn (nếu có tài khoản)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Danh sách sản phẩm trong đơn hàng
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Định dạng tiền tệ VND
     */
    public function getFormattedTotalAmountAttribute()
    {
        return number_format($this->total_amount, 0, ',', '.') . ' ₫';
    }

    /**
     * Nhãn tiếng Việt cho trạng thái đơn hàng
     */
    public function getOrderStatusLabelAttribute()
    {
        $labels = [
            self::STATUS_PENDING    => 'Chờ xác nhận',
            self::STATUS_PROCESSING => 'Đang xử lý',
            self::STATUS_SHIPPING   => 'Đang giao hàng',
            self::STATUS_COMPLETED  => 'Hoàn thành',
            self::STATUS_CANCELLED  => 'Đã hủy',
        ];

        return $labels[$this->order_status] ?? $this->order_status;
    }

    /**
     * Badge HTML Bootstrap cho trạng thái đơn hàng
     */
    public function getOrderStatusBadgeAttribute()
    {
        $badges = [
            self::STATUS_PENDING    => '<span class="badge bg-warning text-dark">Chờ xác nhận</span>',
            self::STATUS_PROCESSING => '<span class="badge bg-info text-dark">Đang xử lý</span>',
            self::STATUS_SHIPPING   => '<span class="badge bg-primary">Đang giao hàng</span>',
            self::STATUS_COMPLETED  => '<span class="badge bg-success">Hoàn thành</span>',
            self::STATUS_CANCELLED  => '<span class="badge bg-danger">Đã hủy</span>',
        ];

        return $badges[$this->order_status] ?? '<span class="badge bg-secondary">' . $this->order_status . '</span>';
    }

    /**
     * Badge HTML Bootstrap cho trạng thái thanh toán
     */
    public function getPaymentStatusBadgeAttribute()
    {
        $badges = [
            self::PAYMENT_UNPAID   => '<span class="badge bg-secondary">Chưa thanh toán</span>',
            self::PAYMENT_PAID     => '<span class="badge bg-success">Đã thanh toán</span>',
            self::PAYMENT_FAILED   => '<span class="badge bg-danger">Thất bại</span>',
            self::PAYMENT_REFUNDED => '<span class="badge bg-dark">Đã hoàn tiền</span>',
        ];

        return $badges[$this->payment_status] ?? '<span class="badge bg-secondary">' . $this->payment_status . '</span>';
    }

    /**
     * Scope lọc đơn hàng gần nhất
     */
    public function scopeLatestFirst($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}
