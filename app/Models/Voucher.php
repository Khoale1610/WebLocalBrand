<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'type',
        'value',
        'max_discount',
        'min_order_amount',
        'usage_limit',
        'used_count',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date'   => 'datetime',
        'is_active'  => 'boolean',
    ];

    /**
     * Kiểm tra voucher có hợp lệ với số tiền đơn hàng hiện tại không
     * Trả về mảng ['valid' => bool, 'message' => string]
     */
    public function checkValidity($orderSubtotal)
    {
        $now = Carbon::now();

        if (!$this->is_active) {
            return ['valid' => false, 'message' => 'Mã giảm giá này hiện đang tạm ngưng áp dụng.'];
        }

        if ($this->start_date && $now->lt($this->start_date)) {
            return ['valid' => false, 'message' => 'Mã giảm giá chưa đến thời gian áp dụng (bắt đầu từ: ' . $this->start_date->format('d/m/Y') . ').'];
        }

        if ($this->end_date && $now->gt($this->end_date)) {
            return ['valid' => false, 'message' => 'Mã giảm giá đã hết hạn sử dụng vào ngày ' . $this->end_date->format('d/m/Y') . '.'];
        }

        if ($this->usage_limit !== null && $this->used_count >= $this->usage_limit) {
            return ['valid' => false, 'message' => 'Mã giảm giá đã hết lượt sử dụng.'];
        }

        if ($orderSubtotal < $this->min_order_amount) {
            return [
                'valid' => false, 
                'message' => 'Đơn hàng tối thiểu để áp dụng mã này là ' . number_format($this->min_order_amount, 0, ',', '.') . ' ₫.'
            ];
        }

        return ['valid' => true, 'message' => 'Áp dụng mã giảm giá thành công!'];
    }

    /**
     * Tính toán số tiền được giảm dựa trên tạm tính tiền hàng
     */
    public function calculateDiscount($orderSubtotal)
    {
        if ($this->type === 'percent') {
            $discount = ($orderSubtotal * $this->value) / 100;
            if ($this->max_discount && $discount > $this->max_discount) {
                $discount = $this->max_discount;
            }
            return (float) $discount;
        }

        // Loại giảm cố định theo VNĐ
        return (float) min($this->value, $orderSubtotal);
    }

    /**
     * Định dạng nhãn giảm giá (VD: 20% hoặc 50.000 ₫)
     */
    public function getFormattedValueAttribute()
    {
        if ($this->type === 'percent') {
            $str = $this->value . '%';
            if ($this->max_discount) {
                $str .= ' (Tối đa ' . number_format($this->max_discount, 0, ',', '.') . ' ₫)';
            }
            return $str;
        }

        return number_format($this->value, 0, ',', '.') . ' ₫';
    }

    /**
     * Trạng thái chi tiết (Active, Expired, Exhausted, Scheduled, Inactive)
     */
    public function getStatusInfoAttribute()
    {
        $now = Carbon::now();

        if (!$this->is_active) {
            return ['label' => 'Đã tắt', 'class' => 'bg-secondary'];
        }

        if ($this->end_date && $now->gt($this->end_date)) {
            return ['label' => 'Đã hết hạn', 'class' => 'bg-danger'];
        }

        if ($this->start_date && $now->lt($this->start_date)) {
            return ['label' => 'Chưa bắt đầu', 'class' => 'bg-warning text-dark'];
        }

        if ($this->usage_limit !== null && $this->used_count >= $this->usage_limit) {
            return ['label' => 'Hết lượt dùng', 'class' => 'bg-dark'];
        }

        return ['label' => 'Đang hoạt động', 'class' => 'bg-success'];
    }
}
