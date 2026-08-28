<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // Hỗ trợ khách vãng lai (null) và khách có tài khoản
            $table->string('order_code')->unique(); // Mã đơn hàng (Vd: ORD-20260828-001)
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->string('customer_address');
            $table->string('customer_city')->nullable();
            $table->string('customer_district')->nullable();
            $table->text('customer_notes')->nullable();
            $table->decimal('total_amount', 12, 0); // Tổng tiền thanh toán (VND)
            $table->decimal('shipping_fee', 12, 0)->default(0); // Phí vận chuyển
            $table->decimal('discount_amount', 12, 0)->default(0); // Tiền giảm giá voucher
            $table->string('payment_method')->default('cod'); // cod, vnpay, momo, bank_transfer
            $table->string('payment_status')->default('unpaid'); // unpaid, paid, failed, refunded
            $table->string('order_status')->default('pending'); // pending, processing, shipping, completed, cancelled
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
