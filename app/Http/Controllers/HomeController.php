<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Trang chủ
     */
    public function index()
    {
        // 1. Lấy tất cả danh mục kèm sản phẩm thuộc danh mục đó
        $categories = Category::with('products')->get();

        // 2. Lấy 8 sản phẩm nổi bật mới nhất ra Trang chủ
        $featuredProducts = Product::where('is_featured', true)
                                   ->latest()
                                   ->take(8)
                                   ->get();

        // 3. Truyền dữ liệu sang View
        return view('pages.home', compact('categories', 'featuredProducts'));
    }

    /**
     * Tìm kiếm và lọc sản phẩm toàn diện
     */
    public function search(Request $request)
    {
        $keyword = trim($request->input('query', ''));
        $categoryId = $request->input('category_id');
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');
        $sort = $request->input('sort', 'newest');

        $query = Product::with('category');

        // Tìm kiếm theo từ khóa (Tên sản phẩm, Mô tả hoặc Tên danh mục)
        if (!empty($keyword)) {
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'LIKE', "%{$keyword}%")
                  ->orWhere('description', 'LIKE', "%{$keyword}%")
                  ->orWhereHas('category', function ($catQ) use ($keyword) {
                      $catQ->where('name', 'LIKE', "%{$keyword}%");
                  });
            });
        }

        // Lọc theo danh mục
        if (!empty($categoryId)) {
            $query->where('category_id', $categoryId);
        }

        // Lọc theo khoảng giá
        if (is_numeric($minPrice)) {
            $query->where('price', '>=', (int)$minPrice);
        }
        if (is_numeric($maxPrice) && (int)$maxPrice > 0) {
            $query->where('price', '<=', (int)$maxPrice);
        }

        // Sắp xếp
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'newest':
            default:
                $query->latest();
                break;
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::withCount('products')->get();

        return view('pages.search', compact('products', 'keyword', 'categories', 'categoryId', 'minPrice', 'maxPrice', 'sort'));
    }

    /**
     * Gợi ý tìm kiếm tức thì (Live Search AJAX)
     */
    public function searchSuggest(Request $request)
    {
        $keyword = trim($request->input('query', ''));

        if (empty($keyword) || mb_strlen($keyword) < 2) {
            return response()->json([]);
        }

        $products = Product::where('name', 'LIKE', "%{$keyword}%")
                           ->select('id', 'name', 'price', 'image', 'slug')
                           ->take(5)
                           ->get()
                           ->map(function ($p) {
                               return [
                                   'id'              => $p->id,
                                   'name'            => $p->name,
                                   'price_formatted' => number_format($p->price, 0, ',', '.') . ' ₫',
                                   'image'           => $p->image ?: 'https://via.placeholder.com/60x60',
                                   'url'             => route('products.show', $p->id),
                               ];
                           });

        return response()->json($products);
    }
}
