<?php
// Gán ảnh cục bộ public/images/products/{slug}-{mau}.jpg cho sản phẩm & biến thể (chỉ cập nhật cột image).
// Chạy: php database/apply_local_images.php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Product;
use Illuminate\Support\Str;

$v = 0; $p = 0;
foreach (Product::with('variants.color')->get() as $product) {
    $first = null;
    foreach ($product->variants as $variant) {
        if (!$variant->color) continue;
        $path = 'images/products/' . $product->slug . '-' . Str::slug($variant->color->name) . '.jpg';
        if (!file_exists(public_path($path))) continue;
        $first = $first ?? $path;
        if ($variant->image !== $path) { $variant->update(['image' => $path]); $v++; }
    }
    // Ảnh đại diện = ảnh màu của biến thể đầu tiên (theo seeder: màu đầu tiên trong danh sách)
    if ($first) {
        $firstVariant = $product->variants->sortBy('id')->first();
        $mainPath = 'images/products/' . $product->slug . '-' . Str::slug($firstVariant->color->name ?? '') . '.jpg';
        $main = file_exists(public_path($mainPath)) ? $mainPath : $first;
        if ($product->image !== $main) { $product->update(['image' => $main]); $p++; }
    }
}
echo "Cap nhat: $p san pham, $v bien the\n";
