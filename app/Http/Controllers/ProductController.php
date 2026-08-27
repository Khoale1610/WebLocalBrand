<?php

namespace App\Http\Controllers;
// Trong ProductController.php

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
class ProductController extends Controller
{
    public function show($id)
    {
        // Lấy sản phẩm kèm danh sách biến thể, size và màu
        $product = Product::with(['variants.size', 'variants.color'])->findOrFail($id);

        return view('pages.product-detail', compact('product'));
    }
}