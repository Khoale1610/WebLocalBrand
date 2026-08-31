<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Size;
use App\Models\Color;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function show(Request $request, $slug)
    {
        // Lấy danh mục hiện tại theo slug, nếu không thấy sẽ báo lỗi 404
        $category = Category::where('slug', $slug)->firstOrFail();

        // Khởi tạo query lấy sản phẩm thuộc danh mục kèm theo quan hệ biến thể, size, color
        $query = Product::where('category_id', $category->id)
                        ->with(['variants.size', 'variants.color']);

        // 1. Lọc theo Khoảng giá
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

        // 2. Lọc theo Size (thông qua bảng biến thể product_variants)
        if ($request->filled('size')) {
            $sizeId = $request->input('size');
            $query->whereHas('variants', function($q) use ($sizeId) {
                $q->where('size_id', $sizeId);
            });
        }

        // 3. Lọc theo Màu sắc (thông qua bảng biến thể product_variants)
        if ($request->filled('color')) {
            $colorId = $request->input('color');
            $query->whereHas('variants', function($q) use ($colorId) {
                $q->where('color_id', $colorId);
            });
        }

        // 4. Sắp xếp sản phẩm
        $sort = $request->input('sort', 'latest');
        if ($sort == 'latest') {
            $query->latest();
        } elseif ($sort == 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($sort == 'price_desc') {
            $query->orderBy('price', 'desc');
        } elseif ($sort == 'best_selling') {
            $query->orderBy('sold_count', 'desc');
        }

        // Phân trang kết quả (12 sản phẩm/trang) và giữ lại các tham số lọc trên URL
        $products = $query->paginate(12)->withQueryString();

        // Lấy danh sách size và màu sắc để hiển thị các tùy chọn lọc ngoài view
        $sizes = Size::all();
        $colors = Color::all();

        return view('client.categories.show', compact('category', 'products', 'sizes', 'colors'));
    }
}
