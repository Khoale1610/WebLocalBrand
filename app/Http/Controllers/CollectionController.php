<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    /**
     * Danh sách các bộ sưu tập định hình phong cách
     */
    private function getCollectionsData()
    {
        return [
            'urban-streetwear' => [
                'slug'        => 'urban-streetwear',
                'title'       => 'Urban Streetwear 2026',
                'subtitle'    => 'Phong Cách Đường Phố Đột Phá',
                'description' => 'Bộ sưu tập lấy cảm hứng từ nhịp sống đường phố năng động: áo hoodie form rộng, áo khoác bomber phối da, quần jeans rách gối cá tính và áo thun oversize chất liệu cotton đanh mịn.',
                'banner_img'  => 'https://images.unsplash.com/photo-1509631179647-0177331693ae?w=1600&auto=format&fit=crop',
                'badge'       => 'NEW ARRIVAL',
                'category_ids'=> [3, 4, 8, 9], // T-Shirt, Áo Khoác, Jeans, Short
            ],
            'office-gentleman' => [
                'slug'        => 'office-gentleman',
                'title'       => 'Office Gentleman - Quý Ông Công Sở',
                'subtitle'    => 'Lịch Lãm, Đẳng Cấp & Tinh Tế',
                'description' => 'Sự kết hợp hoàn hảo giữa kỹ thuật may đo bespoke chuẩn mực và sợi vải công nghệ cao chống nhăn (Bamboo & Nano). Giúp quý ông luôn tự tin trong mọi cuộc họp quan trọng.',
                'banner_img'  => 'https://images.unsplash.com/photo-1507679799987-c73779587ccf?w=1600&auto=format&fit=crop',
                'badge'       => 'BEST SELLER',
                'category_ids'=> [1, 5, 6, 10, 11], // Sơ mi, Blazer/Vest, Quần Tây, Thắt lưng, Ví
            ],
            'active-summer' => [
                'slug'        => 'active-summer',
                'title'       => 'Summer Breeze - Năng Động & Thoáng Khí',
                'subtitle'    => 'Sẵn Sàng Cho Mọi Chuyến Đi',
                'description' => 'Thiết kế ưu tiên sự thông thoáng tối đa với công nghệ Cool-Touch và Quick-Dry. Áo polo thể thao, quần short khaki co giãn và phụ kiện tiện ích cho mùa hè rực rỡ.',
                'banner_img'  => 'https://images.unsplash.com/photo-1516257984-b1b4d707412e?w=1600&auto=format&fit=crop',
                'badge'       => 'TRENDING',
                'category_ids'=> [2, 3, 7, 9, 12], // Polo, T-Shirt, Khaki, Short, Phụ kiện
            ],
            'minimalist-essentials' => [
                'slug'        => 'minimalist-essentials',
                'title'       => 'Minimalist Essentials - Tối Giản Hiện Đại',
                'subtitle'    => 'Vẻ Đẹp Đến Từ Sự Đơn Giản',
                'description' => 'Tập trung vào phom dáng chuẩn, đường may tỉ mỉ và gam màu trung tính kinh điển (Trắng, Đen, Be, Xám Ghi, Xanh Navy). Những món đồ không thể thiếu trong tủ đồ nam giới.',
                'banner_img'  => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=1600&auto=format&fit=crop',
                'badge'       => 'SIGNATURE',
                'category_ids'=> [1, 2, 6, 7, 10], // Sơ mi, Polo, Quần Tây, Khaki, Thắt lưng
            ],
        ];
    }

    /**
     * Trang Tổng quan các Bộ sưu tập
     */
    public function index()
    {
        $collections = $this->getCollectionsData();

        // Gắn thêm số lượng sản phẩm và danh sách sản phẩm mẫu vào mỗi BST
        foreach ($collections as $key => &$col) {
            $col['products_count'] = Product::whereIn('category_id', $col['category_ids'])->count();
            $col['sample_products'] = Product::whereIn('category_id', $col['category_ids'])
                ->latest()
                ->take(4)
                ->get();
        }
        unset($col);

        // Lấy 8 sản phẩm nổi bật mới nhất cho thanh Spotlight
        $spotlightProducts = Product::where('is_featured', true)->latest()->take(8)->get();

        return view('pages.collections.index', compact('collections', 'spotlightProducts'));
    }

    /**
     * Chi tiết từng Bộ sưu tập
     */
    public function show($slug, Request $request)
    {
        $allCollections = $this->getCollectionsData();

        if (!isset($allCollections[$slug])) {
            abort(404, 'Không tìm thấy bộ sưu tập này.');
        }

        $collection = $allCollections[$slug];

        $query = Product::whereIn('category_id', $collection['category_ids']);

        // 1. Lọc theo danh mục con trong BST
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // 2. Lọc theo khoảng giá
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

        // 3. Sắp xếp
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'newest':
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $products = $query->paginate(12)->withQueryString();

        // Danh mục liên quan trong BST
        $categories = Category::whereIn('id', $collection['category_ids'])->get();

        return view('pages.collections.show', compact('collection', 'products', 'categories', 'allCollections'));
    }

    /**
     * Trang Khuyến mãi "Sale Up To 50%"
     */
    public function sale(Request $request)
    {
        $query = Product::with('category');

        // Ưu tiên sản phẩm nổi bật hoặc lọc theo giá
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

        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                default:
                    $query->orderBy('price', 'asc'); // Mặc định sale xếp giá ưu đãi lên trước
                    break;
            }
        } else {
            $query->orderBy('price', 'asc');
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::all();

        return view('pages.collections.sale', compact('products', 'categories'));
    }
}
