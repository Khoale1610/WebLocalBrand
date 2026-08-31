<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Size;
use App\Models\Color;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Hiển thị trang danh mục sản phẩm kèm theo bộ lọc và sắp xếp
     */
    public function show(Request $request, $slug)
    {
        // 1. Tìm danh mục theo slug, nếu không thấy sẽ trả về lỗi 404
        $category = Category::where('slug', $slug)->firstOrFail();

        // 2. Khởi tạo truy vấn lấy sản phẩm thuộc danh mục và nạp trước các quan hệ (Eager Loading)
        $query = Product::where('category_id', $category->id)
                        ->with(['variants.size', 'variants.color']);

        // 3. Xử lý Lọc theo Khoảng Giá
        if ($request->filled('price')) {
            $priceRange = $request->input('price');
            if ($priceRange == 'under_300k') {
                $query->where('price', '<', 300000);
            } elseif ($priceRange == '300k_500k') {
                $query->whereBetween('price', [300000, 500000]);
            } elseif ($priceRange == '500k_1tr') {
                $query->whereBetween('price', [500000, 1000000]);
            } elseif ($priceRange == 'over_1tr') {
                $query->where('price', '>', 1000000);
            }
        }

        // 4. Xử lý Lọc theo Kích thước (Size) thông qua bảng biến thể
        if ($request->filled('size')) {
            $sizeId = $request->input('size');
            $query->whereHas('variants', function ($q) use ($sizeId) {
                $q->where('size_id', $sizeId);
            });
        }

        // 5. Xử lý Lọc theo Màu sắc (Color) thông qua bảng biến thể
        if ($request->filled('color')) {
            $colorId = $request->input('color');
            $query->whereHas('variants', function ($q) use ($colorId) {
                $q->where('color_id', $colorId);
            });
        }

        // 6. Xử lý Sắp xếp sản phẩm
        $sort = $request->input('sort', 'latest');
        if ($sort == 'latest') {
            $query->latest();
        } elseif ($sort == 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($sort == 'price_desc') {
            $query->orderBy('price', 'desc');
        } elseif ($sort == 'best_selling') {
            // Giả sử có cột bán chạy sold_count (nếu không có có thể bỏ qua hoặc chỉnh theo DB của bạn)
            $query->orderBy('sold_count', 'desc');
        }

        // 7. Phân trang 12 sản phẩm mỗi trang và duy trì các tham số lọc trên URL
        $products = $query->paginate(12)->withQueryString();

        // 8. Lấy toàn bộ danh sách Size và Màu sắc để truyền ra View làm bộ lọc
        $sizes = Size::all();
        $colors = Color::all();

        // 9. Trả về đúng view client/products/index.blade.php
        return view('client.products.index', compact('category', 'products', 'sizes', 'colors'));
    }
}
