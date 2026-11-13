<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Category;
use App\Models\Size;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductVariant;

class SampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Tạo Tài khoản Admin & Khách hàng mẫu
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Quản trị viên LocalBrand',
                'password' => Hash::make('12345678'),
                'phone' => '0901234567',
                'address' => 'Tòa nhà Landmark 81, 720A Điện Biên Phủ, Phường 22, Bình Thạnh, TP. Hồ Chí Minh',
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
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
            ['name' => 'FreeSize', 'sort_order' => 6],
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
            ['name' => 'Nâu Bò', 'hex_code' => '#8B4513'],
            ['name' => 'Rêu Đậm', 'hex_code' => '#2E473B'],
            ['name' => 'Xanh Nhạt', 'hex_code' => '#87CEEB'],
        ];
        $colors = [];
        foreach ($colorsData as $c) {
            $colors[$c['name']] = Color::firstOrCreate(['name' => $c['name']], $c);
        }

        // 4. Tạo Danh mục sản phẩm (12 Danh mục chuẩn khớp Menu)
        $categoriesData = [
            1  => ['name' => 'Áo Sơ Mi Nam', 'slug' => 'ao-so-mi-nam'],
            2  => ['name' => 'Áo Polo Nam', 'slug' => 'ao-polo-nam'],
            3  => ['name' => 'Áo T-Shirt (Thun)', 'slug' => 'ao-thun-t-shirt'],
            4  => ['name' => 'Áo Khoác & Hoodie', 'slug' => 'ao-khoac-nam'],
            5  => ['name' => 'Áo Vest & Blazer', 'slug' => 'ao-vest-blazer'],
            6  => ['name' => 'Quần Tây & Âu', 'slug' => 'quan-tay-au'],
            7  => ['name' => 'Quần Khaki', 'slug' => 'quan-khaki'],
            8  => ['name' => 'Quần Jeans Nam', 'slug' => 'quan-jeans-nam'],
            9  => ['name' => 'Quần Short Nam', 'slug' => 'quan-short-nam'],
            10 => ['name' => 'Thắt Lưng Da', 'slug' => 'that-lung-da'],
            11 => ['name' => 'Cà Vạt & Ví Da', 'slug' => 'vi-da-ca-vat'],
            12 => ['name' => 'Tất & Phụ Kiện Nam', 'slug' => 'tat-do-lot-nam'],
        ];

        $categories = [];
        foreach ($categoriesData as $id => $cat) {
            $categories[$id] = Category::updateOrCreate(['id' => $id], $cat);
        }

        // Bảng ảnh biến thể theo màu sắc
        $colorImagesMap = [
            'Trắng'     => 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?w=800&auto=format&fit=crop',
            'Đen'       => 'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?w=800&auto=format&fit=crop',
            'Xanh Navy' => 'https://images.unsplash.com/photo-1596755094514-f87e34085b2c?w=800&auto=format&fit=crop',
            'Xám Ghi'   => 'https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?w=800&auto=format&fit=crop',
            'Be Sữa'    => 'https://images.unsplash.com/photo-1618354691373-d851c5c3a990?w=800&auto=format&fit=crop',
            'Nâu Bò'    => 'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?w=800&auto=format&fit=crop',
            'Rêu Đậm'   => 'https://images.unsplash.com/photo-1578587018452-892bacefd3f2?w=800&auto=format&fit=crop',
            'Xanh Nhạt' => 'https://images.unsplash.com/photo-1542272604-780c96856592?w=800&auto=format&fit=crop',
        ];

        // 5. Danh sách 36 Sản phẩm Thời Trang Local Brand
        $productsData = [
            // CATEGORY 1: Áo Sơ Mi Nam
            [
                'category_id' => 1,
                'name' => 'Áo Sơ Mi Nam Dài Tay Bamboo Kháng Khuẩn',
                'slug' => 'ao-so-mi-nam-dai-tay-bamboo',
                'price' => 499000,
                'is_featured' => true,
                'image' => 'https://lados.vn/wp-content/uploads/2024/11/3-den-ld8160-jpg.webp',
                'stock' => 150,
                'description' => 'Chất liệu sợi tre tự nhiên mềm mại, kháng khuẩn tự nhiên, thoáng mát ngày hè. Thiết kế regular fit tôn dáng lịch lãm cho quý ông công sở.',
                'sizes' => ['M', 'L', 'XL'],
                'colors' => ['Trắng', 'Đen', 'Xanh Navy'],
                'sku_prefix' => 'ASM-BAMBOO',
            ],
            [
                'category_id' => 1,
                'name' => 'Áo Sơ Mi Nam Ngắn Tay Họa Tiết Kẻ Nano',
                'slug' => 'ao-so-mi-nam-ngan-tay-ke-nano',
                'price' => 450000,
                'is_featured' => true,
                'image' => 'https://buggy.yodycdn.com/images/product/306f4b94a9079b10f8cdd8121d64fc7a.webp',
                'stock' => 80,
                'description' => 'Công nghệ xử lý Nano chống nhăn vượt trội, giữ dáng áo thẳng thớm suốt ngày dài hoạt động. Họa tiết kẻ sọc thanh lịch.',
                'sizes' => ['S', 'M', 'L', 'XL'],
                'colors' => ['Trắng', 'Xám Ghi', 'Xanh Nhạt'],
                'sku_prefix' => 'ASM-NANO',
            ],
            [
                'category_id' => 1,
                'name' => 'Áo Sơ Mi Cổ Tàu Vải Linen Tự Nhiên',
                'slug' => 'ao-so-mi-co-tau-vai-linen',
                'price' => 420000,
                'is_featured' => false,
                'image' => 'https://images.unsplash.com/photo-1596755094514-f87e34085b2c?w=800&auto=format&fit=crop',
                'stock' => 60,
                'description' => 'Chất vải đũi linen tự nhiên thấm hút mồ hôi tối đa, phong cách phóng khoáng rất thích hợp cho những chuyến du lịch, dạo phố.',
                'sizes' => ['M', 'L', 'XL'],
                'colors' => ['Trắng', 'Be Sữa', 'Rêu Đậm'],
                'sku_prefix' => 'ASM-LINEN',
            ],

            // CATEGORY 2: Áo Polo Nam
            [
                'category_id' => 2,
                'name' => 'Áo Polo Nam Bo Cổ Cổ Điển Regular Fit',
                'slug' => 'ao-polo-nam-bo-co-co-dien',
                'price' => 350000,
                'is_featured' => true,
                'image' => 'https://images.unsplash.com/photo-1586363104862-3a5e2ab60d99?w=800&auto=format&fit=crop',
                'stock' => 200,
                'description' => 'Vải cá sấu cotton pique co giãn 4 chiều mềm mại, phom ôm nhẹ tôn vóc dáng khỏe khoắn, lịch sự.',
                'sizes' => ['M', 'L', 'XL', 'XXL'],
                'colors' => ['Trắng', 'Đen', 'Xanh Navy', 'Be Sữa'],
                'sku_prefix' => 'APL-CLASSIC',
            ],
            [
                'category_id' => 2,
                'name' => 'Áo Polo Nam Quick-Dry Thể Thao',
                'slug' => 'ao-polo-nam-quick-dry',
                'price' => 380000,
                'is_featured' => true,
                'image' => 'https://cdn.hstatic.net/products/200000039280/6305907d-f2f3-46d8-b78c-e98a8dcb78e1_8144e7c236ae42f58498613171cf8162_b6b1c6aef6614b488e719b9b8b3ea85e.png',
                'stock' => 90,
                'description' => 'Công nghệ thoát ẩm tức thì giúp cơ thể luôn khô ráo dễ chịu trong các hoạt động thể thao ngoài trời hoặc chơi golf.',
                'sizes' => ['S', 'M', 'L'],
                'colors' => ['Đen', 'Xám Ghi', 'Xanh Navy'],
                'sku_prefix' => 'APL-QDRY',
            ],
            [
                'category_id' => 2,
                'name' => 'Áo Polo Dệt Kim Phối Màu Retro Vintage',
                'slug' => 'ao-polo-det-kim-retro-vintage',
                'price' => 450000,
                'is_featured' => false,
                'image' => 'https://images.unsplash.com/photo-1618354691373-d851c5c3a990?w=800&auto=format&fit=crop',
                'stock' => 70,
                'description' => 'Chất len dệt kim mỏng nhẹ mang âm hưởng cổ điển châu Âu, phối màu sọc tinh tế tạo điểm nhấn nổi bật.',
                'sizes' => ['M', 'L', 'XL'],
                'colors' => ['Be Sữa', 'Nâu Bò', 'Đen'],
                'sku_prefix' => 'APL-RETRO',
            ],

            // CATEGORY 3: Áo Thun T-Shirt
            [
                'category_id' => 3,
                'name' => 'Áo Thun Nam Trơn Cotton Compact Premium',
                'slug' => 'ao-thun-nam-tron-cotton-compact',
                'price' => 250000,
                'is_featured' => true,
                'image' => 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?w=800&auto=format&fit=crop',
                'stock' => 250,
                'description' => 'Sợi cotton compact cao cấp hạn chế tối đa xù lông, bề mặt đanh mịn màng, cổ bo dệt co giãn tốt.',
                'sizes' => ['S', 'M', 'L', 'XL'],
                'colors' => ['Trắng', 'Đen', 'Xám Ghi', 'Be Sữa'],
                'sku_prefix' => 'TSH-COMPACT',
            ],
            [
                'category_id' => 3,
                'name' => 'Áo Thun Form Rộng Graphic Local Brand',
                'slug' => 'ao-thun-form-rong-graphic',
                'price' => 290000,
                'is_featured' => true,
                'image' => 'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?w=800&auto=format&fit=crop',
                'stock' => 120,
                'description' => 'Form oversize cá tính mang phong cách đường phố đặc trưng của giới trẻ, hình in công nghệ lụa sắc nét.',
                'sizes' => ['M', 'L', 'XL'],
                'colors' => ['Đen', 'Trắng', 'Rêu Đậm'],
                'sku_prefix' => 'TSH-GRAPHIC',
            ],
            [
                'category_id' => 3,
                'name' => 'Áo Thun Nam Cổ Tim Co Giãn Modal',
                'slug' => 'ao-thun-nam-co-tim-modal',
                'price' => 260000,
                'is_featured' => false,
                'image' => 'https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?w=800&auto=format&fit=crop',
                'stock' => 85,
                'description' => 'Chất liệu Modal sợi gỗ sồi êm dịu, mềm như lụa, thích hợp mặc lót trong sơ mi hoặc mặc nhà năng động.',
                'sizes' => ['S', 'M', 'L', 'XL'],
                'colors' => ['Trắng', 'Đen', 'Xám Ghi'],
                'sku_prefix' => 'TSH-MODAL',
            ],

            // CATEGORY 4: Áo Khoác & Hoodie
            [
                'category_id' => 4,
                'name' => 'Áo Khoác Bomber Varsity Phối Tay Da',
                'slug' => 'ao-khoac-bomber-varsity-tay-da',
                'price' => 650000,
                'is_featured' => true,
                'image' => 'https://images.unsplash.com/photo-1551028719-00167b16eac5?w=800&auto=format&fit=crop',
                'stock' => 60,
                'description' => 'Áo khoác bomber thời thượng, thân dạ lì cao cấp kết hợp tay da mềm, thêu logo nổi tỉ mỉ trước ngực.',
                'sizes' => ['M', 'L', 'XL'],
                'colors' => ['Đen', 'Xanh Navy', 'Rêu Đậm'],
                'sku_prefix' => 'AK-VARSITY',
            ],
            [
                'category_id' => 4,
                'name' => 'Áo Hoodie Nỉ Bông Form Rộng Streetwear',
                'slug' => 'ao-hoodie-ni-bong-streetwear',
                'price' => 490000,
                'is_featured' => true,
                'image' => 'https://images.unsplash.com/photo-1556905055-8f358a7a47b2?w=800&auto=format&fit=crop',
                'stock' => 95,
                'description' => 'Nỉ chân cua cào bông ấm áp, mũ trùm 2 lớp đứng phom dày dặn, túi kangaroo tiện dụng phía trước.',
                'sizes' => ['M', 'L', 'XL'],
                'colors' => ['Xám Ghi', 'Đen', 'Be Sữa'],
                'sku_prefix' => 'AK-HOODIE',
            ],
            [
                'category_id' => 4,
                'name' => 'Áo Khoác Gió 2 Lớp Chống Thấm Nước',
                'slug' => 'ao-khoac-gio-2-lop-chong-nuoc',
                'price' => 420000,
                'is_featured' => false,
                'image' => 'https://images.unsplash.com/photo-1544441893-675973e31985?w=800&auto=format&fit=crop',
                'stock' => 110,
                'description' => 'Lớp ngoài chống bám nước trượt mưa, lớp trong lót lưới thoáng khí, kèm túi khóa kéo an toàn.',
                'sizes' => ['S', 'M', 'L', 'XL'],
                'colors' => ['Đen', 'Xanh Navy', 'Xám Ghi'],
                'sku_prefix' => 'AK-GIO',
            ],

            // CATEGORY 5: Áo Vest & Blazer
            [
                'category_id' => 5,
                'name' => 'Áo Blazer Nam Dáng Rộng Phong Cách Hàn Quốc',
                'slug' => 'ao-blazer-nam-dang-rong-han-quoc',
                'price' => 790000,
                'is_featured' => true,
                'image' => 'https://images.unsplash.com/photo-1507679799987-c73779587ccf?w=800&auto=format&fit=crop',
                'stock' => 45,
                'description' => 'Thiết kế vai đệm nhẹ tạo phom đứng đắn, xẻ tà sau thoải mái khi ngồi, dễ dàng phối cùng áo thun hoặc sơ mi.',
                'sizes' => ['M', 'L', 'XL'],
                'colors' => ['Đen', 'Xám Ghi', 'Be Sữa'],
                'sku_prefix' => 'BLZ-KOREA',
            ],
            [
                'category_id' => 5,
                'name' => 'Áo Vest Nam Công Sở 2 Khuy Cao Cấp',
                'slug' => 'ao-vest-nam-cong-so-2-khuy',
                'price' => 890000,
                'is_featured' => false,
                'image' => 'https://images.unsplash.com/photo-1594938298603-c8148c4dae35?w=800&auto=format&fit=crop',
                'stock' => 40,
                'description' => 'Chuẩn may đo Bespoke sắc sảo từng đường kim mũi chỉ, ve áo chữ K lịch lãm, lót lụa mềm mượt sang trọng.',
                'sizes' => ['M', 'L', 'XL'],
                'colors' => ['Đen', 'Xanh Navy'],
                'sku_prefix' => 'VST-SUIT',
            ],
            [
                'category_id' => 5,
                'name' => 'Áo Blazer Kẻ Caro Retro Lịch Lãm',
                'slug' => 'ao-blazer-ke-caro-retro',
                'price' => 820000,
                'is_featured' => false,
                'image' => 'https://images.unsplash.com/photo-1593030761757-71fae45fa0e7?w=800&auto=format&fit=crop',
                'stock' => 35,
                'description' => 'Họa tiết kẻ sọc ô nhã nhặn phong cách học viện Anh quốc, chất liệu len pha bền màu không bám bụi.',
                'sizes' => ['M', 'L', 'XL'],
                'colors' => ['Xám Ghi', 'Nâu Bò'],
                'sku_prefix' => 'BLZ-CARO',
            ],

            // CATEGORY 6: Quần Tây & Âu
            [
                'category_id' => 6,
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
                'category_id' => 6,
                'name' => 'Quần Tây Xếp Ly Cạp Cao Phong Cách Cổ Điển',
                'slug' => 'quan-tay-xep-ly-cap-cao',
                'price' => 580000,
                'is_featured' => false,
                'image' => 'https://images.unsplash.com/photo-1624378439575-d8705ad7ae80?w=800&auto=format&fit=crop',
                'stock' => 75,
                'description' => 'Chi tiết xếp 2 ly trước tinh tế giúp che khuyết điểm đùi, cạp tăng đơ chỉnh kích thước bụng tiện lợi.',
                'sizes' => ['M', 'L', 'XL'],
                'colors' => ['Be Sữa', 'Đen', 'Xám Ghi'],
                'sku_prefix' => 'QTA-PLEATS',
            ],
            [
                'category_id' => 6,
                'name' => 'Quần Âu Dáng Suông Ống Rộng Tối Giản',
                'slug' => 'quan-au-dang-suong-ong-rong',
                'price' => 520000,
                'is_featured' => false,
                'image' => 'https://images.unsplash.com/photo-1473966968600-fa801b869a1a?w=800&auto=format&fit=crop',
                'stock' => 80,
                'description' => 'Phom dáng suông thời thượng phong cách Scandinavian, vải có độ rũ mềm tự nhiên, thoải mái vận động.',
                'sizes' => ['M', 'L', 'XL'],
                'colors' => ['Đen', 'Xám Ghi', 'Xanh Navy'],
                'sku_prefix' => 'QTA-WIDE',
            ],

            // CATEGORY 7: Quần Khaki
            [
                'category_id' => 7,
                'name' => 'Quần Kaki Nam Túi Chéo Trẻ Trung',
                'slug' => 'quan-kaki-nam-tui-cheo',
                'price' => 480000,
                'is_featured' => true,
                'image' => 'https://images.unsplash.com/photo-1473966968600-fa801b869a1a?w=800&auto=format&fit=crop',
                'stock' => 70,
                'description' => 'Quần khaki dáng đứng trẻ trung, phối cùng áo sơ mi hoặc thun polo cực kỳ phong cách và năng động.',
                'sizes' => ['M', 'L', 'XL'],
                'colors' => ['Be Sữa', 'Xám Ghi', 'Đen'],
                'sku_prefix' => 'QKH-TCHEO',
            ],
            [
                'category_id' => 7,
                'name' => 'Quần Kaki Nam Dáng Regular Dày Dặn',
                'slug' => 'quan-kaki-nam-regular-day-dan',
                'price' => 490000,
                'is_featured' => false,
                'image' => 'https://images.unsplash.com/photo-1506629082955-511b1aa562c8?w=800&auto=format&fit=crop',
                'stock' => 90,
                'description' => 'Chất kaki cotton chéo sợi dày dặn, giữ màu cực tốt sau nhiều lần giặt, đường may đinh tán gia cố chắc chắn.',
                'sizes' => ['M', 'L', 'XL', 'XXL'],
                'colors' => ['Rêu Đậm', 'Be Sữa', 'Đen'],
                'sku_prefix' => 'QKH-REG',
            ],
            [
                'category_id' => 7,
                'name' => 'Quần Kaki Túi Hộp Cargo Pants Năng Động',
                'slug' => 'quan-kaki-tui-hop-cargo-pants',
                'price' => 520000,
                'is_featured' => true,
                'image' => 'https://images.unsplash.com/photo-1624378439575-d8705ad7ae80?w=800&auto=format&fit=crop',
                'stock' => 65,
                'description' => 'Thiết kế túi hộp bên đùi hầm hố chuẩn phong cách Streetwear, gấu quần có dây rút tùy chỉnh ống túm.',
                'sizes' => ['M', 'L', 'XL'],
                'colors' => ['Đen', 'Rêu Đậm', 'Be Sữa'],
                'sku_prefix' => 'QKH-CARGO',
            ],

            // CATEGORY 8: Quần Jeans Nam
            [
                'category_id' => 8,
                'name' => 'Quần Jeans Nam Slimfit Co Giãn Wash Nhẹ',
                'slug' => 'quan-jeans-nam-slimfit-co-gian',
                'price' => 590000,
                'is_featured' => true,
                'image' => 'https://images.unsplash.com/photo-1542272604-780c96856592?w=800&auto=format&fit=crop',
                'stock' => 120,
                'description' => 'Vải denim dệt kèm spandex co giãn tốt, kỹ thuật wash hiện đại tạo hiệu ứng bạc màu tự nhiên, tôn dáng chân dài.',
                'sizes' => ['S', 'M', 'L', 'XL'],
                'colors' => ['Xanh Navy', 'Đen', 'Xanh Nhạt'],
                'sku_prefix' => 'JNS-SLIM',
            ],
            [
                'category_id' => 8,
                'name' => 'Quần Jeans Nam Ống Đứng Suông Basic',
                'slug' => 'quan-jeans-nam-ong-dung-basic',
                'price' => 550000,
                'is_featured' => false,
                'image' => 'https://images.unsplash.com/photo-1541099649105-f69ad21f3246?w=800&auto=format&fit=crop',
                'stock' => 95,
                'description' => 'Form suông cổ điển không kén người mặc, khóa kéo đồng mạ cao cấp chống rỉ sét, độ bền lên đến nhiều năm.',
                'sizes' => ['M', 'L', 'XL', 'XXL'],
                'colors' => ['Xanh Navy', 'Đen', 'Xám Ghi'],
                'sku_prefix' => 'JNS-BASIC',
            ],
            [
                'category_id' => 8,
                'name' => 'Quần Jeans Nam Rách Gối Cá Tính Streetwear',
                'slug' => 'quan-jeans-nam-rach-goi',
                'price' => 620000,
                'is_featured' => true,
                'image' => 'https://images.unsplash.com/photo-1582552938357-32b906df40cb?w=800&auto=format&fit=crop',
                'stock' => 55,
                'description' => 'Điểm nhấn rách xước gối thủ công tạo phong cách bụi bặm, phá cách cho những buổi dạo phố cuối tuần.',
                'sizes' => ['M', 'L', 'XL'],
                'colors' => ['Đen', 'Xanh Nhạt'],
                'sku_prefix' => 'JNS-DISTRESS',
            ],

            // CATEGORY 9: Quần Short Nam
            [
                'category_id' => 9,
                'name' => 'Quần Short Kaki Nam Co Giãn Thoáng Mát',
                'slug' => 'quan-short-kaki-nam-co-gian',
                'price' => 320000,
                'is_featured' => true,
                'image' => 'https://images.unsplash.com/photo-1591195853828-11db59a44f6b?w=800&auto=format&fit=crop',
                'stock' => 140,
                'description' => 'Độ dài ngang đầu gối tiêu chuẩn, lưng cạp có chun ẩn phía sau giúp thoải mái tối đa khi ngồi hay vận động.',
                'sizes' => ['M', 'L', 'XL'],
                'colors' => ['Be Sữa', 'Xanh Navy', 'Đen'],
                'sku_prefix' => 'SHT-KAKI',
            ],
            [
                'category_id' => 9,
                'name' => 'Quần Short Gió Thể Thao Nhanh Khô',
                'slug' => 'quan-short-gio-the-thao',
                'price' => 280000,
                'is_featured' => false,
                'image' => 'https://images.unsplash.com/photo-1562157873-818bc0726f68?w=800&auto=format&fit=crop',
                'stock' => 110,
                'description' => 'Trọng lượng siêu nhẹ, tích hợp túi kéo khóa sau để đựng chìa khóa hay điện thoại khi chạy bộ, tập gym.',
                'sizes' => ['S', 'M', 'L', 'XL'],
                'colors' => ['Đen', 'Xám Ghi', 'Xanh Navy'],
                'sku_prefix' => 'SHT-SPORT',
            ],
            [
                'category_id' => 9,
                'name' => 'Quần Short Jean Nam Rách Lai Vintage',
                'slug' => 'quan-short-jean-nam-vintage',
                'price' => 350000,
                'is_featured' => false,
                'image' => 'https://images.unsplash.com/photo-1542272604-780c96856592?w=800&auto=format&fit=crop',
                'stock' => 75,
                'description' => 'Gấu quần cắt lai sờn phong cách retro, chất jean mềm đã qua xử lý giặt làm mềm không gây ráp da.',
                'sizes' => ['M', 'L', 'XL'],
                'colors' => ['Xanh Nhạt', 'Đen'],
                'sku_prefix' => 'SHT-JEAN',
            ],

            // CATEGORY 10: Thắt Lưng Da
            [
                'category_id' => 10,
                'name' => 'Thắt Lưng Nam Da Bò Thật Khóa Tự Động',
                'slug' => 'that-lung-nam-da-bo-that',
                'price' => 390000,
                'is_featured' => true,
                'image' => 'https://navy.vn/wp-content/uploads/2025/11/118-Nau.jpg',
                'stock' => 50,
                'description' => 'Chất liệu da bò tự nhiên 100% bền đẹp, mặt khóa kim loại phủ sơn tĩnh điện chống rỉ sét, khóa răng cưa tiện dụng.',
                'sizes' => ['FreeSize'],
                'colors' => ['Đen', 'Nâu Bò'],
                'sku_prefix' => 'TL-DABO',
            ],
            [
                'category_id' => 10,
                'name' => 'Thắt Lưng Nam Da Sáp Khóa Kim Vintage',
                'slug' => 'that-lung-nam-da-sap-khoa-kim',
                'price' => 420000,
                'is_featured' => false,
                'image' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=800&auto=format&fit=crop',
                'stock' => 40,
                'description' => 'Da sáp nguyên tấm dày 3.8mm, càng dùng càng bóng đẹp theo thời gian, phù hợp hoàn hảo với quần jeans và kaki.',
                'sizes' => ['FreeSize'],
                'colors' => ['Nâu Bò', 'Đen'],
                'sku_prefix' => 'TL-VINTAGE',
            ],
            [
                'category_id' => 10,
                'name' => 'Thắt Lưng Bản Nhỏ Phối Quần Tây Công Sở',
                'slug' => 'that-lung-ban-nho-quan-tay',
                'price' => 360000,
                'is_featured' => false,
                'image' => 'https://navy.vn/wp-content/uploads/2025/11/118-Nau.jpg',
                'stock' => 45,
                'description' => 'Bản rộng 3.2cm chuẩn mực khi diện cùng quần tây âu, mặt khóa xoay 2 mặt tiện lợi đổi phong cách.',
                'sizes' => ['FreeSize'],
                'colors' => ['Đen'],
                'sku_prefix' => 'TL-OFFICE',
            ],

            // CATEGORY 11: Cà Vạt & Ví Da
            [
                'category_id' => 11,
                'name' => 'Ví Nam Da Bò Dáng Đứng Mini Cardholder',
                'slug' => 'vi-nam-da-bo-mini-cardholder',
                'price' => 290000,
                'is_featured' => true,
                'image' => 'https://images.unsplash.com/photo-1627123424574-724758594e93?w=800&auto=format&fit=crop',
                'stock' => 85,
                'description' => 'Thiết kế siêu mỏng nhẹ vừa vặn túi quần trước, tích hợp ngăn chứa thẻ thông minh và kẹp tiền kim loại.',
                'sizes' => ['FreeSize'],
                'colors' => ['Đen', 'Nâu Bò'],
                'sku_prefix' => 'VI-CARD',
            ],
            [
                'category_id' => 11,
                'name' => 'Ví Dài Cầm Tay Nam Clutch Da Cao Cấp',
                'slug' => 'vi-dai-cam-tay-nam-clutch',
                'price' => 590000,
                'is_featured' => false,
                'image' => 'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?w=800&auto=format&fit=crop',
                'stock' => 30,
                'description' => 'Clutch cầm tay lịch lãm đựng vừa điện thoại, sổ tay và nhiều ngăn thẻ, khóa kéo êm ái chống kẹt.',
                'sizes' => ['FreeSize'],
                'colors' => ['Đen', 'Nâu Bò'],
                'sku_prefix' => 'VI-CLUTCH',
            ],
            [
                'category_id' => 11,
                'name' => 'Bộ Combo Cà Vạt Lụa & Kẹp Mạ Bạc',
                'slug' => 'bo-combo-ca-vat-lua-kep-ma-bac',
                'price' => 320000,
                'is_featured' => false,
                'image' => 'https://images.unsplash.com/photo-1598033129183-c4f50c736f10?w=800&auto=format&fit=crop',
                'stock' => 60,
                'description' => 'Hộp quà tặng sang trọng gồm cà vạt lụa dệt hoa văn chìm tinh xảo, khăn cài áo vest và kẹp mạ bạc đính kèm.',
                'sizes' => ['FreeSize'],
                'colors' => ['Xanh Navy', 'Đen'],
                'sku_prefix' => 'CV-GIFTSET',
            ],

            // CATEGORY 12: Tất & Phụ Kiện Nam
            [
                'category_id' => 12,
                'name' => 'Set 5 Đôi Tất Cổ Ngắn Cotton Kháng Khuẩn',
                'slug' => 'set-5-doi-tat-co-ngan-cotton',
                'price' => 150000,
                'is_featured' => true,
                'image' => 'https://images.unsplash.com/photo-1586350977771-b3b0abd50c82?w=800&auto=format&fit=crop',
                'stock' => 200,
                'description' => 'Sợi cotton chải kỹ thấm hút vượt trội, công nghệ nano bạc khử mùi chân hiệu quả, viền gót silicon chống tụt.',
                'sizes' => ['FreeSize'],
                'colors' => ['Trắng', 'Đen', 'Xám Ghi'],
                'sku_prefix' => 'TAT-SHORT',
            ],
            [
                'category_id' => 12,
                'name' => 'Set 5 Đôi Tất Cổ Cao Dệt Gân Vintage',
                'slug' => 'set-5-doi-tat-co-cao-vintage',
                'price' => 180000,
                'is_featured' => false,
                'image' => 'https://images.unsplash.com/photo-1582966772680-860e372bb558?w=800&auto=format&fit=crop',
                'stock' => 150,
                'description' => 'Tất cao cổ dệt sọc gân phong cách Hàn Quốc, phối cùng sneaker hoặc giày tây công sở cực kỳ cuốn hút.',
                'sizes' => ['FreeSize'],
                'colors' => ['Trắng', 'Đen', 'Be Sữa'],
                'sku_prefix' => 'TAT-HIGH',
            ],
            [
                'category_id' => 12,
                'name' => 'Nón Lưỡi Trai Kaki Thêu Chữ Local Brand',
                'slug' => 'non-luoi-trai-kaki-theu-chu',
                'price' => 220000,
                'is_featured' => true,
                'image' => 'https://images.unsplash.com/photo-1588850561407-ed78c282e89b?w=800&auto=format&fit=crop',
                'stock' => 90,
                'description' => 'Form nón cứng cáp ôm đầu, chất vải kaki 100% cotton dày dặn, khóa gài kim loại điều chỉnh vòng đầu dễ dàng.',
                'sizes' => ['FreeSize'],
                'colors' => ['Đen', 'Trắng', 'Be Sữa'],
                'sku_prefix' => 'NON-CAP',
            ],
        ];

        // 6. Lưu vào Cơ Sở Dữ Liệu và Tạo các Biến thể
        foreach ($productsData as $pData) {
            $product = Product::updateOrCreate(
                ['slug' => $pData['slug']],
                [
                    'category_id' => $pData['category_id'],
                    'name'        => $pData['name'],
                    'slug'        => $pData['slug'],
                    'price'       => $pData['price'],
                    'is_featured' => $pData['is_featured'],
                    'image'       => $pData['image'],
                    'stock'       => $pData['stock'],
                    'description' => $pData['description'],
                ]
            );

            // Tạo các biến thể sản phẩm (Variants)
            foreach ($pData['sizes'] as $sizeName) {
                foreach ($pData['colors'] as $colorName) {
                    $sizeModel = $sizes[$sizeName] ?? null;
                    $colorModel = $colors[$colorName] ?? null;

                    if (!$sizeModel || !$colorModel) continue;

                    $sku = $pData['sku_prefix'] . '-' . $sizeName . '-' . strtoupper(Str::slug($colorName));
                    $varImage = $colorImagesMap[$colorName] ?? $product->image;

                    ProductVariant::updateOrCreate(
                        [
                            'product_id' => $product->id,
                            'size_id'    => $sizeModel->id,
                            'color_id'   => $colorModel->id,
                        ],
                        [
                            'sku'   => $sku,
                            'price' => $product->price,
                            'stock' => rand(20, 50),
                            'image' => $varImage,
                        ]
                    );
                }
            }

            // Đồng bộ lại tổng kho sản phẩm từ biến thể
            $product->update(['stock' => $product->variants()->sum('stock')]);
        }
    }
}
