<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // 1. Lấy tất cả danh mục kèm sản phẩm thuộc danh mục đó
        $categories = Category::with('products')->get();

        // 2. Lấy 8 sản phẩm nổi bật mới nhất ra Trang chủ
        $featuredProducts = Product::where('is_featured', true)
                                   ->latest()
                                   ->take(8)
                                   ->get();

        // 3. Truyền dữ liệu sang View bằng compact()
        return view('pages.home', compact('categories', 'featuredProducts'));
    }

    public function search(Request $request)
    {
        $keyword = trim($request->input('query'));

        // Tim kiếm sản phẩm theo tên sử dụng LIKE
        $products = Product::where('name', 'LIKE', "%{$keyword}%")
                           ->paginate(12);

        return view('pages.search', compact('products', 'keyword'));
    }
}
