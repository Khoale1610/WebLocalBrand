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
        // 1. Lấy tất cả danh mục kèm số lượng sản phẩm
        $categories = Category::withCount('products')->get();

        // 2. Lấy 8 sản phẩm nổi bật (Best Sellers / Featured)
        $featuredProducts = Product::with('category')
                                   ->where('is_featured', true)
                                   ->latest()
                                   ->take(8)
                                   ->get();

        // 3. Lấy 8 sản phẩm mới về (New Arrivals)
        $newArrivals = Product::with('category')
                              ->latest('id')
                              ->take(8)
                              ->get();

        // 4. Truyền dữ liệu sang View
        return view('pages.home', compact('categories', 'featuredProducts', 'newArrivals'));
    }

    /**
     * Bỏ dấu tiếng Việt để so khớp khi người dùng tìm không dấu
     */
    private function removeVietnameseAccents(string $str): string
    {
        $accents = [
            'a' => 'áàảãạăắằẳẵặâấầẩẫậ',
            'd' => 'đ',
            'e' => 'éèẻẽẹêếềểễệ',
            'i' => 'íìỉĩị',
            'o' => 'óòỏõọôốồổỗộơớờởỡợ',
            'u' => 'úùủũụưứừửữự',
            'y' => 'ýỳỷỹỵ',
        ];
        $str = mb_strtolower($str, 'UTF-8');
        foreach ($accents as $nonAccent => $accentGroup) {
            $str = preg_replace('/[' . $accentGroup . ']/u', $nonAccent, $str);
        }
        return $str;
    }

    /**
     * Kiểm tra chuỗi $haystack có khớp từ khóa $keyword theo ranh giới từ & dấu tiếng Việt
     */
    private function matchesSearchKeyword(string $haystack, string $keyword): bool
    {
        $terms = preg_split('/\s+/u', trim($keyword), -1, PREG_SPLIT_NO_EMPTY);
        if (empty($terms)) {
            return true;
        }

        $haystackLower = mb_strtolower($haystack, 'UTF-8');
        $haystackNoAccent = $this->removeVietnameseAccents($haystackLower);

        foreach ($terms as $term) {
            $termLower = mb_strtolower($term, 'UTF-8');
            $hasAccent = (bool) preg_match('/[àáạảãâầấậẩẫăằắặẳẵèéẹẻẽêềếệểễìíịỉĩòóọỏõôồốộổỗơờớợởỡùúụủũưừứựửữỳýỵỷỹđ]/u', $termLower);

            if ($hasAccent) {
                // Nếu từ khóa có dấu: khớp chính xác dấu và ranh giới từ (tránh "áo" khớp "cao", "thao")
                $pattern = '/(?<!\p{L})' . preg_quote($termLower, '/') . '(?!\p{L})/u';
                if (!preg_match($pattern, $haystackLower)) {
                    return false;
                }
            } else {
                // Nếu từ khóa không dấu: so khớp trên bản không dấu theo ranh giới từ
                // Cho phép khớp tiền tố nếu từ dài >= 3 ký tự (vd: "hood" -> "hoodie")
                $suffix = mb_strlen($termLower, 'UTF-8') >= 3 ? '' : '(?!\p{L})';
                $pattern = '/(?<!\p{L})' . preg_quote($termLower, '/') . $suffix . '/u';
                if (!preg_match($pattern, $haystackNoAccent)) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Lấy danh sách ID sản phẩm khớp từ khóa chính xác (trên tên sản phẩm + tên danh mục)
     */
    private function getMatchedProductIds(string $keyword): array
    {
        return Product::with('category:id,name')
            ->select('id', 'name', 'category_id')
            ->get()
            ->filter(function ($product) use ($keyword) {
                $searchable = $product->name . ' ' . ($product->category->name ?? '');
                return $this->matchesSearchKeyword($searchable, $keyword);
            })
            ->pluck('id')
            ->all();
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
        $matchedIds = null;

        // Tìm kiếm theo từ khóa chính xác (có dấu & ranh giới từ tiếng Việt)
        if (!empty($keyword)) {
            $matchedIds = $this->getMatchedProductIds($keyword);
            if (empty($matchedIds)) {
                $query->whereRaw('1 = 0');
            } else {
                $query->whereIn('id', $matchedIds);
            }
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

        // Sắp xếp (Ưu tiên khớp tên nguyên cụm lên đầu khi tìm kiếm mặc định)
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
                if (!empty($keyword)) {
                    $query->orderByRaw('CASE WHEN products.name LIKE ? THEN 0 ELSE 1 END', ["%{$keyword}%"]);
                }
                $query->latest();
                break;
        }

        $products = $query->paginate(12)->withQueryString();

        // Đếm số sản phẩm thực tế khớp điều kiện tìm kiếm hiện tại trong từng danh mục
        $categories = Category::withCount(['products' => function ($q) use ($matchedIds, $minPrice, $maxPrice) {
            if ($matchedIds !== null) {
                if (empty($matchedIds)) {
                    $q->whereRaw('1 = 0');
                } else {
                    $q->whereIn('products.id', $matchedIds);
                }
            }
            if (is_numeric($minPrice)) {
                $q->where('products.price', '>=', (int)$minPrice);
            }
            if (is_numeric($maxPrice) && (int)$maxPrice > 0) {
                $q->where('products.price', '<=', (int)$maxPrice);
            }
        }])->get();

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

        $matchedIds = $this->getMatchedProductIds($keyword);
        if (empty($matchedIds)) {
            return response()->json([]);
        }

        $products = Product::with('category')
                          ->whereIn('id', $matchedIds)
                          ->orderByRaw('CASE WHEN products.name LIKE ? THEN 0 ELSE 1 END', ["%{$keyword}%"])
                          ->select('id', 'name', 'price', 'image', 'slug', 'category_id')
                          ->take(6)
                          ->get()
                          ->map(function ($p) {
                              return [
                                  'id'              => $p->id,
                                  'name'            => $p->name,
                                  'price_formatted' => number_format($p->price, 0, ',', '.') . 'đ',
                                  'image'           => $p->image_url,
                                  'url'             => route('products.show', $p->id),
                              ];
                          });

        return response()->json($products);
    }
}
