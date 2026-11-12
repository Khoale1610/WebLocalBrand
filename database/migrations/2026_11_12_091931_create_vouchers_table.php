<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique(); // Mã voucher (VD: OWEN50, SUMMER20)
            $table->string('name'); // Tên chương trình (VD: Giảm 50K cho đơn từ 400K)
            $table->enum('type', ['percent', 'fixed'])->default('fixed'); // % hoặc số tiền cố định
            $table->decimal('value', 12, 0); // Giá trị giảm (VD: 20 cho 20% hoặc 50000 cho 50.000đ)
            $table->decimal('max_discount', 12, 0)->nullable(); // Mức giảm tối đa nếu là giảm theo %
            $table->decimal('min_order_amount', 12, 0)->default(0); // Giá trị đơn hàng tối thiểu
            $table->integer('usage_limit')->nullable(); // Giới hạn tổng số lượt sử dụng
            $table->integer('used_count')->default(0); // Số lượt đã dùng
            $table->dateTime('start_date')->nullable(); // Ngày bắt đầu hiệu lực
            $table->dateTime('end_date')->nullable(); // Ngày hết hạn
            $table->boolean('is_active')->default(true); // Trạng thái bật / tắt
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
