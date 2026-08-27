<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            
            // Khóa ngoại liên kết tới bảng Sản phẩm, Size và Color
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('size_id')->constrained()->onDelete('cascade');
            $table->foreignId('color_id')->constrained()->onDelete('cascade');
            
            $table->string('sku')->unique(); // Mã quản lý kho riêng (Vd: OWEN-ASM01-M-NAVY)
            $table->decimal('price', 12, 0)->nullable(); // Giá riêng nếu biến thể này khác giá gốc
            $table->integer('stock')->default(0); // Số lượng tồn kho của riêng biến thể này
            $table->string('image')->nullable(); // Ảnh góc chụp riêng theo màu
            
            $table->timestamps();

            // Đảm bảo không trùng lặp cặp Product + Size + Color
            $table->unique(['product_id', 'size_id', 'color_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};