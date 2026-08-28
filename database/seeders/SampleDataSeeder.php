<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Size;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Tạo Tài khoản mẫu (Admin & Khách hàng)
        User::firstOrCreate(
            ['email' => 'admin@localbrand.vn'],
            [
                'name' => 'Quản trị viên LocalBrand',
                'password' => Hash::make('12345678'),
                'phone' => '0901234567',
                'address' => 'Số 1 Đại Cồ Việt, Hai Bà Trưng, Hà Nội',
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        $customer1 = User::firstOrCreate(
            ['email' => 'khachhang@gmail.com'],
            [
                'name' => 'Nguyễn Văn An',
                'password' => Hash::make('12345678'),
                'phone' => '0987654321',
                'address' => '123 Cầu Giấy, Phường Quan Hoa, Cầu Giấy, Hà Nội',
                'role' => 'customer',
                'email_verified_at' => now(),
            ]
        );

        $customer2 = User::firstOrCreate(
            ['email' => 'maitran@gmail.com'],
            [
                'name' => 'Trần Thị Mai',
                'password' => Hash::make('12345678'),
                'phone' => '0912345678',
                'address' => '456 Lê Lợi, Phường Bến Nghé, Quận 1, TP. Hồ Chí Minh',
                'role' => 'customer',
                'email_verified_at' => now(),
            ]
        );

        // 2. Tạo Kích cỡ (Sizes)
        $sizesData = [
            ['name' => 'S', 'sort_order' => 1],
            ['name' => 'M', 'sort_order' => 2],
            ['name' => 'L', 'sort_order' => 3],
            ['name' => 'XL', 'sort_order' => 4],
            ['name' => 'XXL', 'sort_order' => 5],
        ];
        $sizes = [];
        foreach ($sizesData as $s) {
            $sizes[$s['name']] = Size::firstOrCreate(['name' => $s['name']], $s);
        }

        // 3. Tạo Màu sắc (Colors)
        $colorsData = [
            ['name' => 'Trắng', 'hex_code' => '#FFFFFF'],
            ['name' => 'Đen', 'hex_code' => '#111111'],
            ['name' => 'Xanh Navy', 'hex_code' => '#002B49'],
            ['name' => 'Xám Ghi', 'hex_code' => '#808080'],
            ['name' => 'Be Sữa', 'hex_code' => '#E8D8C8'],
        ];
        $colors = [];
        foreach ($colorsData as $c) {
            $colors[$c['name']] = Color::firstOrCreate(['name' => $c['name']], $c);
        }

        // 4. Tạo Danh mục sản phẩm (Categories)
        $categoriesData = [
            ['name' => 'Áo Sơ Mi Nam', 'slug' => 'ao-so-mi-nam'],
            ['name' => 'Áo Polo Nam', 'slug' => 'ao-polo-nam'],
            ['name' => 'Áo Thun T-Shirt', 'slug' => 'ao-thun-t-shirt'],
            ['name' => 'Quần Tây & Âu', 'slug' => 'quan-tay-au'],
            ['name' => 'Quần Khaki & Short', 'slug' => 'quan-khaki-short'],
            ['name' => 'Phụ Kiện Da', 'slug' => 'phu-kien-da'],
        ];
        $categories = [];
        foreach ($categoriesData as $cat) {
            $categories[$cat['slug']] = Category::firstOrCreate(['slug' => $cat['slug']], $cat);
        }

        // 5. Tạo Danh sách Sản phẩm kèm Biến thể
        $productsData = [
            [
                'category_slug' => 'ao-so-mi-nam',
                'name' => 'Áo Sơ Mi Nam Dài Tay Bamboo Kháng Khuẩn',
                'slug' => 'ao-so-mi-nam-dai-tay-bamboo',
                'price' => 499000,
                'is_featured' => true,
                'image' => 'https://lados.vn/wp-content/uploads/2024/11/3-den-ld8160-jpg.webp',
                'stock' => 150,
                'description' => 'Chất liệu sợi tre Bamboo tự nhiên, mềm mại, thoáng mát và kháng khuẩn tự nhiên. Thiết kế dáng regular fit lịch lãm, dễ phối cùng quần âu và blazer.',
                'sizes' => ['M', 'L', 'XL'],
                'colors' => ['Trắng', 'Đen', 'Xanh Navy'],
                'sku_prefix' => 'ASM-BAMBOO',
            ],
            [
                'category_slug' => 'ao-so-mi-nam',
                'name' => 'Áo Sơ Mi Nam Ngắn Tay Họa Tiết Kẻ Nano',
                'slug' => 'ao-so-mi-nam-ngan-tay-ke-nano',
                'price' => 450000,
                'is_featured' => true,
                'image' => 'https://buggy.yodycdn.com/images/product/306f4b94a9079b10f8cdd8121d64fc7a.webp',
                'stock' => 80,
                'description' => 'Sử dụng công nghệ sợi Nano chống nhăn vượt trội, giữ dáng áo thẳng suốt cả ngày làm việc năng động.',
                'sizes' => ['S', 'M', 'L', 'XL'],
                'colors' => ['Trắng', 'Xám Ghi'],
                'sku_prefix' => 'ASM-NANO',
            ],
            [
                'category_slug' => 'ao-polo-nam',
                'name' => 'Áo Polo Nam Bo Cổ Cổ Điển Regular Fit',
                'slug' => 'ao-polo-nam-bo-co-co-dien',
                'price' => 350000,
                'is_featured' => true,
                'image' => 'https://content.pancake.vn/web-media-262/s1080x1080/fwebp90/57/48/52/82/ca33663e431dc0f75318a612dac54a142c55e04e789481b8196ec657-w:1080-h:1080-l:505576-t:image/jpeg.jpeg',
                'stock' => 200,
                'description' => 'Chất vải cá sấu pique co giãn 4 chiều mềm mại, form áo ôm nhẹ tôn dáng thể thao khỏe khoắn.',
                'sizes' => ['M', 'L', 'XL', 'XXL'],
                'colors' => ['Trắng', 'Đen', 'Xanh Navy', 'Be Sữa'],
                'sku_prefix' => 'APL-CLASSIC',
            ],
            [
                'category_slug' => 'ao-polo-nam',
                'name' => 'Áo Polo Nam Quick-Dry Thể Thao',
                'slug' => 'ao-polo-nam-quick-dry',
                'price' => 380000,
                'is_featured' => true,
                'image' => 'https://cdn.hstatic.net/products/200000039280/6305907d-f2f3-46d8-b78c-e98a8dcb78e1_8144e7c236ae42f58498613171cf8162_b6b1c6aef6614b488e719b9b8b3ea85e.png',
                'stock' => 90,
                'description' => 'Công nghệ Quick-Dry làm mát tức thì, thấm hút mồ hôi và bay hơi cực nhanh, thích hợp cho hoạt động ngoài trời.',
                'sizes' => ['S', 'M', 'L'],
                'colors' => ['Đen', 'Xám Ghi'],
                'sku_prefix' => 'APL-QDRY',
            ],
            [
                'category_slug' => 'ao-thun-t-shirt',
                'name' => 'Áo Thun Nam Trơn Cotton Compact Premium',
                'slug' => 'ao-thun-nam-tron-cotton-compact',
                'price' => 250000,
                'is_featured' => true,
                'image' => 'https://product.hstatic.net/1000360022/product/id-000135a_-_copy_47f8bcd54fc84e3a8cbb0e3da28a39fd_1024x1024.jpg',
                'stock' => 250,
                'description' => 'Áo thun basic 100% cotton chải kỹ cao cấp, cổ tròn bo dệt không bai nhão sau khi giặt.',
                'sizes' => ['S', 'M', 'L', 'XL'],
                'colors' => ['Trắng', 'Đen', 'Xám Ghi', 'Be Sữa'],
                'sku_prefix' => 'TSH-COMPACT',
            ],
            [
                'category_slug' => 'quan-tay-au',
                'name' => 'Quần Tây Nam Dáng Slimfit Co Giãn Không Nhăn',
                'slug' => 'quan-tay-nam-slimfit-co-gian',
                'price' => 550000,
                'is_featured' => true,
                'image' => 'https://yame.vn/cdn/shop/files/qu-n-tay-non-iron-19-den-1174881694.jpg?v=1780841637&width=533',
                'stock' => 110,
                'description' => 'Thiết kế cạp quần thông minh co giãn nhẹ, đường may tỉ mỉ theo tiêu chuẩn may đo cao cấp.',
                'sizes' => ['M', 'L', 'XL'],
                'colors' => ['Đen', 'Xanh Navy', 'Xám Ghi'],
                'sku_prefix' => 'QTA-SLIM',
            ],
            [
                'category_slug' => 'quan-khaki-short',
                'name' => 'Quần Kaki Nam Túi Chéo Trẻ Trung',
                'slug' => 'quan-kaki-nam-tui-cheo',
                'price' => 480000,
                'is_featured' => false,
                'image' => 'https://lados.vn/wp-content/uploads/2024/11/3-den-ld8160-jpg.webp',
                'stock' => 70,
                'description' => 'Quần khaki dáng đứng trẻ trung, phối cùng áo sơ mi hoặc thun polo cực kỳ phong cách.',
                'sizes' => ['M', 'L', 'XL'],
                'colors' => ['Be Sữa', 'Xám Ghi', 'Đen'],
                'sku_prefix' => 'QKH-TCHEO',
            ],
            [
                'category_slug' => 'phu-kien-da',
                'name' => 'Thắt Lưng Nam Da Bò Thật Khóa Tự Động',
                'slug' => 'that-lung-nam-da-bo-that',
                'price' => 390000,
                'is_featured' => true,
                'image' => 'https://navy.vn/wp-content/uploads/2025/11/118-Nau.jpg',
                'stock' => 50,
                'description' => 'Chất liệu da bò tự nhiên 100% bền đẹp, mặt khóa kim loại phủ sơn tĩnh điện chống rỉ sét.',
                'sizes' => ['L'],
                'colors' => ['Đen'],
                'sku_prefix' => 'TL-DABO',
            ],
        ];

        foreach ($productsData as $pData) {
            $cat = $categories[$pData['category_slug']];
            $product = Product::updateOrCreate(
                ['slug' => $pData['slug']],
                [
                    'category_id' => $cat->id,
                    'name' => $pData['name'],
                    'slug' => $pData['slug'],
                    'price' => $pData['price'],
                    'is_featured' => $pData['is_featured'],
                    'image' => $pData['image'],
                    'stock' => $pData['stock'],
                    'description' => $pData['description'],
                ]
            );

            // Tạo các biến thể sản phẩm (Variants)
            foreach ($pData['sizes'] as $sizeName) {
                foreach ($pData['colors'] as $colorName) {
                    $sizeModel = $sizes[$sizeName];
                    $colorModel = $colors[$colorName];
                    $sku = $pData['sku_prefix'] . '-' . $sizeName . '-' . strtoupper(Str::slug($colorName));

                    ProductVariant::updateOrCreate(
                        [
                            'product_id' => $product->id,
                            'size_id' => $sizeModel->id,
                            'color_id' => $colorModel->id,
                        ],
                        [
                            'sku' => $sku,
                            'price' => $product->price,
                            'stock' => rand(15, 45),
                            'image' => $product->image,
                        ]
                    );
                }
            }
        }
    }
}
