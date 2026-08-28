<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_variant_id')->nullable()->constrained()->onDelete('set null');
            $table->string('product_name'); // Lưu tên sản phẩm tại thời điểm mua
            $table->string('variant_info')->nullable(); // Lưu thông tin biến thể (Vd: Size L - Màu Đen - SKU: OWEN-01-L-BLK)
            $table->decimal('price', 12, 0); // Đơn giá tại thời điểm mua
            $table->integer('quantity')->default(1); // Số lượng mua
            $table->decimal('total_price', 12, 0); // Thành tiền (price * quantity)
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
        Schema::dropIfExists('order_items');
    }
}
