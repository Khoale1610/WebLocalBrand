<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Hiển thị trang chi tiết sản phẩm kèm danh sách biến thể
     */
    public function show($id)
    {
        // Lấy sản phẩm kèm danh mục và danh sách biến thể (Size + Màu)
        $product = Product::with(['category', 'variants.size', 'variants.color'])->findOrFail($id);

        // Lấy 4 sản phẩm liên quan cùng danh mục
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        return view('pages.product-detail', compact('product', 'relatedProducts'));
    }
}