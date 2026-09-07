<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Size;
use App\Models\Color;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Hiển thị trang chi tiết sản phẩm
     */
    public function show($id)
    {
        $product = Product::with(['category', 'variants.size', 'variants.color'])->findOrFail($id);

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        return view('pages.product-detail', compact('product', 'relatedProducts'));
    }

    /**
     * Xử lý Trang Danh mục Sản phẩm có Bộ lọc đa năng
     */
    public function category(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        // Khởi tạo query lấy sản phẩm thuộc danh mục đang xem
        $query = Product::where('category_id', $category->id);

        // ==========================================
        // 1. Lọc theo Khoảng giá
        // ==========================================
        if ($request->filled('price')) {
            switch ($request->price) {
                case 'under_300':
                    $query->where('price', '<', 300000);
                    break;
                case '300_500':
                    $query->whereBetween('price', [300000, 500000]);
                    break;
                case '500_1000':
                    $query->whereBetween('price', [500000, 1000000]);
                    break;
                case 'over_1000':
                    $query->where('price', '>', 1000000);
                    break;
            }
        }

        // ==========================================
        // 2. Lọc theo Size (Qua bảng trung gian variants)
        // ==========================================
        if ($request->filled('size')) {
            $query->whereHas('variants', function ($q) use ($request) {
                $q->whereIn('size_id', $request->size);
            });
        }

        // ==========================================
        // 3. Lọc theo Màu sắc (Qua bảng trung gian variants)
        // ==========================================
        if ($request->filled('color')) {
            $query->whereHas('variants', function ($q) use ($request) {
                $q->whereIn('color_id', $request->color);
            });
        }

        // ==========================================
        // 4. Sắp xếp sản phẩm
        // ==========================================
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'best_seller':
                    // Tạm thời sắp xếp theo ID giảm dần nếu chưa có bảng thống kê lượt bán
                    $query->orderBy('id', 'desc');
                    break;
                case 'newest':
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        } else {
            // Mặc định luôn xếp sản phẩm mới nhất lên đầu
            $query->orderBy('created_at', 'desc');
        }

        // Ghi chú: withQueryString() tự động giữ lại toàn bộ các biến trên URL (price, size, color) khi khách chuyển trang 2, trang 3
        $products = $query->paginate(12)->withQueryString();

        // Lấy dữ liệu cho Sidebar bộ lọc
        $sizes = Size::all();
        $colors = Color::all();

        return view('client.products.index', compact('category', 'products', 'sizes', 'colors'));
    }
}
