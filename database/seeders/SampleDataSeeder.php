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

        User::updateOrCreate(
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
            $colors[$c['name']] = Color::updateOrCreate(['name' => $c['name']], $c);
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

        // Bộ ảnh đồng nhất theo từng dòng sản phẩm (tránh lẫn lộn giữa Áo Polo, Sơ Mi, Quần, Phụ kiện)
        $categoryColorGallery = [
            // 1. Áo Sơ Mi Nam
            1 => [
                'Đen'       => 'https://lados.vn/wp-content/uploads/2024/11/3-den-ld8160-jpg.webp',
                'Trắng'     => 'https://images.unsplash.com/photo-1620012253295-c15cc3e65df4?w=800&auto=format&fit=crop',
                'Xanh Navy' => 'https://images.unsplash.com/photo-1596755094514-f87e34085b2c?w=800&auto=format&fit=crop',
                'Xám Ghi'   => 'https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?w=800&auto=format&fit=crop',
                'Xanh Nhạt' => 'https://buggy.yodycdn.com/images/product/306f4b94a9079b10f8cdd8121d64fc7a.webp',
                'Be Sữa'    => 'https://images.unsplash.com/photo-1598033129183-c4f50c736f10?w=800&auto=format&fit=crop',
                'Rêu Đậm'   => 'https://images.unsplash.com/photo-1603252109303-2751441dd157?w=800&auto=format&fit=crop',
            ],
            // 2. Áo Polo Nam (Tất cả biến thể đều là ảnh Áo Polo chuẩn phom)
            2 => [
                'Trắng'     => 'https://images.unsplash.com/photo-1586363104862-3a5e2ab60d99?w=800&auto=format&fit=crop',
                'Đen'       => 'https://cdn.hstatic.net/products/200000039280/6305907d-f2f3-46d8-b78c-e98a8dcb78e1_8144e7c236ae42f58498613171cf8162_b6b1c6aef6614b488e719b9b8b3ea85e.png',
                'Xanh Navy' => 'https://images.unsplash.com/photo-1581655353564-df123a1eb820?w=800&auto=format&fit=crop',
                'Xám Ghi'   => 'https://images.unsplash.com/photo-1576566588028-4147f3842f27?w=800&auto=format&fit=crop',
                'Be Sữa'    => 'https://images.unsplash.com/photo-1618354691373-d851c5c3a990?w=800&auto=format&fit=crop',
                'Nâu Bò'    => 'https://images.unsplash.com/photo-1586363104862-3a5e2ab60d99?w=800&auto=format&fit=crop',
            ],
            // 3. Áo T-Shirt (Thun)
            3 => [
                'Trắng'     => 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?w=800&auto=format&fit=crop',
                'Đen'       => 'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?w=800&auto=format&fit=crop',
                'Xám Ghi'   => 'https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?w=800&auto=format&fit=crop',
                'Be Sữa'    => 'https://images.unsplash.com/photo-1562157873-818bc0726f68?w=800&auto=format&fit=crop',
                'Rêu Đậm'   => 'https://images.unsplash.com/photo-1576566588028-4147f3842f27?w=800&auto=format&fit=crop',
            ],
            // 4. Áo Khoác & Hoodie
            4 => [
                'Đen'       => 'https://images.unsplash.com/photo-1551028719-00167b16eac5?w=800&auto=format&fit=crop',
                'Xám Ghi'   => 'https://images.unsplash.com/photo-1556905055-8f358a7a47b2?w=800&auto=format&fit=crop',
                'Xanh Navy' => 'https://images.unsplash.com/photo-1544441893-675973e31985?w=800&auto=format&fit=crop',
                'Rêu Đậm'   => 'https://images.unsplash.com/photo-1578587018452-892bacefd3f2?w=800&auto=format&fit=crop',
                'Be Sữa'    => 'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?w=800&auto=format&fit=crop',
            ],
            // 5. Áo Vest & Blazer
            5 => [
                'Đen'       => 'https://images.unsplash.com/photo-1507679799987-c73779587ccf?w=800&auto=format&fit=crop',
                'Xanh Navy' => 'https://images.unsplash.com/photo-1594938298603-c8148c4dae35?w=800&auto=format&fit=crop',
                'Xám Ghi'   => 'https://images.unsplash.com/photo-1593030761757-71fae45fa0e7?w=800&auto=format&fit=crop',
                'Be Sữa'    => 'https://images.unsplash.com/photo-1507679799987-c73779587ccf?w=800&auto=format&fit=crop',
                'Nâu Bò'    => 'https://images.unsplash.com/photo-1593030761757-71fae45fa0e7?w=800&auto=format&fit=crop',
            ],
            // 6. Quần Tây & Âu
            6 => [
                'Đen'       => 'https://yame.vn/cdn/shop/files/qu-n-tay-non-iron-19-den-1174881694.jpg?v=1780841637&width=533',
                'Xanh Navy' => 'https://images.unsplash.com/photo-1473966968600-fa801b869a1a?w=800&auto=format&fit=crop',
                'Xám Ghi'   => 'https://images.unsplash.com/photo-1624378439575-d8705ad7ae80?w=800&auto=format&fit=crop',
                'Be Sữa'    => 'https://images.unsplash.com/photo-1506629082955-511b1aa562c8?w=800&auto=format&fit=crop',
            ],
            // 7. Quần Khaki
            7 => [
                'Be Sữa'    => 'https://images.unsplash.com/photo-1473966968600-fa801b869a1a?w=800&auto=format&fit=crop',
                'Rêu Đậm'   => 'https://images.unsplash.com/photo-1506629082955-511b1aa562c8?w=800&auto=format&fit=crop',
                'Đen'       => 'https://images.unsplash.com/photo-1624378439575-d8705ad7ae80?w=800&auto=format&fit=crop',
                'Xám Ghi'   => 'https://images.unsplash.com/photo-1473966968600-fa801b869a1a?w=800&auto=format&fit=crop',
            ],
            // 8. Quần Jeans Nam
            8 => [
                'Xanh Navy' => 'https://images.unsplash.com/photo-1542272604-780c96856592?w=800&auto=format&fit=crop',
                'Đen'       => 'https://images.unsplash.com/photo-1541099649105-f69ad21f3246?w=800&auto=format&fit=crop',
                'Xanh Nhạt' => 'https://images.unsplash.com/photo-1582552938357-32b906df40cb?w=800&auto=format&fit=crop',
                'Xám Ghi'   => 'https://images.unsplash.com/photo-1541099649105-f69ad21f3246?w=800&auto=format&fit=crop',
            ],
            // 9. Quần Short Nam
            9 => [
                'Be Sữa'    => 'https://images.unsplash.com/photo-1591195853828-11db59a44f6b?w=800&auto=format&fit=crop',
                'Đen'       => 'https://images.unsplash.com/photo-1591195853828-11db59a44f6b?w=800&auto=format&fit=crop',
                'Xanh Navy' => 'https://images.unsplash.com/photo-1591195853828-11db59a44f6b?w=800&auto=format&fit=crop',
                'Xám Ghi'   => 'https://images.unsplash.com/photo-1591195853828-11db59a44f6b?w=800&auto=format&fit=crop',
                'Xanh Nhạt' => 'https://images.unsplash.com/photo-1591195853828-11db59a44f6b?w=800&auto=format&fit=crop',
            ],
            // 10. Thắt Lưng Da
            10 => [
                'Đen'       => 'https://navy.vn/wp-content/uploads/2025/11/118-Nau.jpg',
                'Nâu Bò'    => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=800&auto=format&fit=crop',
            ],
            // 11. Cà Vạt & Ví Da
            11 => [
                'Đen'       => 'https://images.unsplash.com/photo-1627123424574-724758594e93?w=800&auto=format&fit=crop',
                'Nâu Bò'    => 'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?w=800&auto=format&fit=crop',
                'Xanh Navy' => 'https://images.unsplash.com/photo-1598033129183-c4f50c736f10?w=800&auto=format&fit=crop',
            ],
            // 12. Tất & Phụ Kiện Nam
            12 => [
                'Trắng'     => 'https://images.unsplash.com/photo-1586350977771-b3b0abd50c82?w=800&auto=format&fit=crop',
                'Đen'       => 'https://images.unsplash.com/photo-1588850561407-ed78c282e89b?w=800&auto=format&fit=crop',
                'Xám Ghi'   => 'https://images.unsplash.com/photo-1582966772680-860e372bb558?w=800&auto=format&fit=crop',
                'Be Sữa'    => 'https://images.unsplash.com/photo-1582966772680-860e372bb558?w=800&auto=format&fit=crop',
            ],
        ];

        $standardPoloSpec = implode("\n", [
            "• Thành phần vải: 92% Polyester Microfiber + 8% Spandex co giãn 4 chiều, bề mặt mềm nhẹ.",
            "• Kiểu dệt: Dệt mắt chim (Bird's Eye Mesh) thoáng khí, thoát ẩm nhanh gấp 2 lần cotton thường.",
            "• Phom dáng: Regular Fit suông vừa vặn, che khuyết điểm tốt và tôn dáng nam giới Việt.",
            "• Chi tiết cổ & tay áo: Bo cổ dệt rib cao cấp chống bai dão, nẹp cổ 3 cúc khắc tên thương hiệu.",
        ]);

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
                'description' => implode("\n", [
                    "• Thành phần vải: 50% Bamboo tự nhiên + 47% Microfiber + 3% Spandex kháng khuẩn, hạn chế nhăn.",
                    "• Bề mặt vải: Dệt Twill mật độ cao, bề mặt mướt mịn, thoáng khí và thấm hút mồ hôi vượt trội.",
                    "• Phom dáng: Regular Fit ôm vừa vặn phần vai và ngực, dễ dàng sơ vin lịch lãm nơi công sở.",
                    "• Chi tiết hoàn thiện: Cổ áo và măng sét ép keo định hình giữ phom, không phồng rộp sau khi giặt.",
                ]),
                'sizes' => ['M', 'L', 'XL'],
                'colors' => ['Đen', 'Trắng', 'Xanh Navy'],
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
                'description' => implode("\n", [
                    "• Thành phần vải: 92% Polyester Microfiber Nano + 8% Spandex chống nhăn tự nhiên.",
                    "• Kiểu dệt: Dệt sợi mảnh thoáng khí, bề mặt kẻ sọc nhã nhặn tạo hiệu ứng thon gọn.",
                    "• Phom dáng: Regular Fit thoải mái vận động suốt ngày dài mà vẫn giữ nét chỉn chu.",
                    "• Chi tiết hoàn thiện: Cổ áo đứng phom chống bai dão, đường may cuốn sườn 5 mũi/cm sắc nét.",
                ]),
                'sizes' => ['S', 'M', 'L', 'XL'],
                'colors' => ['Xanh Nhạt', 'Trắng', 'Xám Ghi'],
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
                'description' => implode("\n", [
                    "• Thành phần vải: 70% Linen tự nhiên + 30% Cotton đã qua xử lý làm mềm sợi vải.",
                    "• Kiểu dệt: Dệt thoi thoáng khí, tốc độ bay hơi ẩm nhanh, phù hợp thời tiết nhiệt đới.",
                    "• Phom dáng: Regular Fit suông nhẹ, thiết kế cổ trụ (Mandarin Collar) tối giản hiện đại.",
                    "• Chi tiết hoàn thiện: Cúc áo vân gỗ tự nhiên, vạt bầu dễ mặc thả ngoài hoặc sơ vin.",
                ]),
                'sizes' => ['M', 'L', 'XL'],
                'colors' => ['Xanh Navy', 'Trắng', 'Be Sữa'],
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
                'description' => $standardPoloSpec,
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
                'description' => $standardPoloSpec,
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
                'description' => $standardPoloSpec,
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
                'description' => implode("\n", [
                    "• Thành phần vải: 95% Cotton Compact chải kỹ định lượng 250GSM + 5% Spandex co giãn 4 chiều.",
                    "• Bề mặt vải: Xử lý đốt lông bề mặt (Singeing) giúp vải đanh mịn, không xù lông sau khi giặt máy.",
                    "• Phom dáng: Regular Fit chuẩn mực, độ dài ngang hông dễ phối cùng quần jeans hoặc quần âu.",
                    "• Bo cổ: Bo rib bản 2.2cm dệt kép chống bai dão, giữ cổ áo luôn ôm sát gọn gàng.",
                ]),
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
                'description' => implode("\n", [
                    "• Thành phần vải: 100% Cotton 2 chiều Heavyweight định lượng 280GSM đứng phom.",
                    "• Công nghệ in: In lụa plastisol cao cấp kết hợp ép nhiệt chống bong tróc, nứt gãy hình in.",
                    "• Phom dáng: Boxy Fit trễ vai nhẹ, mang lại vẻ ngoài khỏe khoắn và hiện đại.",
                    "• Bo cổ: Bo cổ dệt gân dày dặn 2.5cm chống bai dão, đường may móc xích vai đôi.",
                ]),
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
                'description' => implode("\n", [
                    "• Thành phần vải: 92% Modal sợi sồi tự nhiên + 8% Spandex siêu mềm nhẹ và mát lạnh.",
                    "• Kiểu dệt: Single Jersey mịn phẳng, khả năng thấm hút và kháng tĩnh điện tự nhiên.",
                    "• Phom dáng: Regular Fit ôm gọn gàng, cổ tim (V-neck) vừa phải tinh tế.",
                    "• Chi tiết hoàn thiện: Viền cổ dệt liền mạch chống bai dão, không cộm ngứa nhờ nhãn in nhiệt.",
                ]),
                'sizes' => ['S', 'M', 'L', 'XL'],
                'colors' => ['Xám Ghi', 'Trắng', 'Đen'],
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
                'description' => implode("\n", [
                    "• Thành phần vải: Thân áo dạ ép Melange 650GSM kết hợp tay da Microfiber chống bong nổ.",
                    "• Lớp lót trong: Lót dù chần bông kim cương mỏng nhẹ, giữ nhiệt tốt và tích hợp túi ngực ẩn.",
                    "• Phom dáng: Regular Bomber Fit vừa vặn, tôn cầu vai nam tính.",
                    "• Bo cổ & gấu áo: Bo dệt sọc gân đàn hồi cao chống bai dão, nút bấm đồng sơn tĩnh điện.",
                ]),
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
                'description' => implode("\n", [
                    "• Thành phần vải: 85% Cotton + 15% Polyester nỉ chân cua (French Terry) định lượng 380GSM.",
                    "• Bề mặt vải: Mặt ngoài đanh mịn chống xù, mặt trong dệt vòng sợi thoáng khí không gây bí bách.",
                    "• Phom dáng: Relaxed Fit thoải mái, mũ áo 2 lớp đứng phom không bị xẹp khi mặc.",
                    "• Chi tiết hoàn thiện: Bo tay và bo gấu dệt rib co giãn chống bai dão, túi Kangaroo gia cố bọ chỉ.",
                ]),
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
                'description' => implode("\n", [
                    "• Thành phần vải: 100% Polyester Taslan phủ màng chống thấm nước nhẹ (Water-Repellent) và cản gió.",
                    "• Lớp lót trong: Lưới Tricot mắt nhỏ thoáng khí, không dính da khi vận động ngoài trời.",
                    "• Phom dáng: Regular Fit gọn gàng, cổ cao chắn gió kèm mũ có thể tháo rời linh hoạt.",
                    "• Chi tiết hoàn thiện: Khóa kéo YKK trượt mượt, túi hai bên có khóa kéo bảo vệ vật dụng cá nhân.",
                ]),
                'sizes' => ['S', 'M', 'L', 'XL'],
                'colors' => ['Xanh Navy', 'Đen', 'Xám Ghi'],
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
                'description' => implode("\n", [
                    "• Thành phần vải: 70% Polyester + 28% Viscose + 2% Spandex dày dặn, giữ phom thẳng phẳng.",
                    "• Cấu trúc may: Đệm vai mỏng tự nhiên (Soft Shoulder), lót lụa Habutai thoáng nhẹ bên trong.",
                    "• Phom dáng: Semi-Over Fit hiện đại, dễ dàng layer cùng áo thun cổ tròn hoặc áo sơ mi.",
                    "• Chi tiết hoàn thiện: Ve áo Notch Lapel sắc nét, 2 khuy cài trước và xẻ tà sau tiện lợi.",
                ]),
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
                'description' => implode("\n", [
                    "• Thành phần vải: Vải Tuytsi len pha (Wool Blend) cao cấp, chống nhăn và chống bám bụi.",
                    "• Kỹ thuật dựng: Ép mùng ngực Half-Canvas giúp ngực áo căng phom chuẩn may đo Bespoke.",
                    "• Phom dáng: Tailored Regular Fit ôm vừa vặn theo đường nét cơ thể nam giới.",
                    "• Chi tiết hoàn thiện: 4 khuy tay áo đính chồng (Kissing Buttons), 2 túi cơi trong ngực.",
                ]),
                'sizes' => ['M', 'L', 'XL'],
                'colors' => ['Xanh Navy', 'Đen'],
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
                'description' => implode("\n", [
                    "• Thành phần vải: 68% Polyester + 30% Rayon + 2% Spandex dệt họa tiết Glen Check cổ điển.",
                    "• Bề mặt vải: Dệt sợi nhuộm trước (Yarn-Dyed) giúp đường kẻ sắc nét và bền màu tuyệt đối.",
                    "• Phom dáng: Regular Fit thanh lịch, canh khớp đường kẻ caro tỉ mỉ tại vai và túi áo.",
                    "• Chi tiết hoàn thiện: Lót trong toàn phần mềm mượt, khuy sừng nhân tạo vân tự nhiên.",
                ]),
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
                'description' => implode("\n", [
                    "• Thành phần vải: 76% Polyester + 20% Rayon + 4% Spandex co giãn nhẹ, chống nhăn Non-Iron.",
                    "• Bề mặt vải: Dệt chéo mịn phẳng, giữ ly quần sắc nét sau nhiều lần giặt.",
                    "• Phom dáng: Slim Fit thuôn gọn từ đùi xuống ống (rộng ống 17.5cm), tôn chiều cao.",
                    "• Chi tiết cạp quần: Cạp quần tích hợp thun ẩn co giãn 2 bên hông giúp thoải mái khi ngồi làm việc.",
                ]),
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
                'description' => implode("\n", [
                    "• Thành phần vải: Vải Vitex cao cấp định lượng 310GSM có độ rũ tự nhiên và đứng ống.",
                    "• Thiết kế: Cạp cao 4.5cm kèm đai Sidetab điều chỉnh vòng eo không cần dùng thắt lưng.",
                    "• Phom dáng: Regular Straight xếp 1 ly trước (Single Pleat) thoải mái phần hông và đùi.",
                    "• Chi tiết hoàn thiện: Gấu quần lật lai (Turn-up Cuff) 3.5cm chuẩn phong cách Sartorial.",
                ]),
                'sizes' => ['M', 'L', 'XL'],
                'colors' => ['Xám Ghi', 'Be Sữa', 'Đen'],
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
                'description' => implode("\n", [
                    "• Thành phần vải: 92% Polyester Microfiber + 8% Spandex thoáng nhẹ, bề mặt lì sang trọng.",
                    "• Phom dáng: Relaxed Straight ống suông rộng vừa phải (rộng ống 21cm), che khuyết điểm chân.",
                    "• Chi tiết cạp: Móc gài kim loại ẩn bên trong cạp giúp mặt trước phẳng phiu tối giản.",
                    "• Đường may: Viền túi chéo gia cố bọ chỉ chắc chắn, lót túi bằng vải cotton mềm.",
                ]),
                'sizes' => ['M', 'L', 'XL'],
                'colors' => ['Xanh Navy', 'Đen', 'Xám Ghi'],
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
                'description' => implode("\n", [
                    "• Thành phần vải: 97% Cotton Twill + 3% Spandex dệt vân chéo dày dặn, co giãn thoải mái.",
                    "• Xử lý hoàn tất: Công nghệ Enzyme Wash làm mềm vải và chống co rút sau khi giặt.",
                    "• Phom dáng: Regular Fit ống đứng gọn gàng, phù hợp cả đi làm lẫn dạo phố.",
                    "• Chi tiết hoàn thiện: Khóa kéo đồng YKK bền bỉ, túi hậu cơi chỉ đôi sắc sảo.",
                ]),
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
                'description' => implode("\n", [
                    "• Thành phần vải: 98% Cotton Chino định lượng 320GSM + 2% Elastane đứng phom.",
                    "• Độ bền màu: Công nghệ nhuộm hoàn nguyên giữ màu sắc tươi mới, không phai màu.",
                    "• Phom dáng: Regular Fit truyền thống, phần đùi và đầu gối rộng rãi dễ cử động.",
                    "• Chi tiết hoàn thiện: Đường may sườn kép (Flat-Felled Seam) chịu lực cao.",
                ]),
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
                'description' => implode("\n", [
                    "• Thành phần vải: 100% Cotton Ripstop dệt ô vuông chống rách xước, thoáng mát.",
                    "• Thiết kế: 6 túi đa năng gồm 2 túi chéo, 2 túi hộp đùi nắp gài ẩn và 2 túi sau.",
                    "• Phom dáng: Relaxed Fit khỏe khoắn, tích hợp dây rút gấu quần tùy chỉnh độ ôm cổ chân.",
                    "• Chi tiết hoàn thiện: Chi tiết chiết ly đầu gối tạo độ phồng 3D thuận tiện khi di chuyển.",
                ]),
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
                'description' => implode("\n", [
                    "• Thành phần vải: 98% Cotton Denim 12.5oz + 2% Spandex co giãn đàn hồi tốt.",
                    "• Kỹ thuật Wash: Bio-Stone Wash tạo hiệu ứng chuyển màu tự nhiên và làm mềm sợi vải.",
                    "• Phom dáng: Slim Fit ôm vừa vặn, tôn dáng chân thẳng mà không gây bó sát khó chịu.",
                    "• Phụ liệu: Đinh tán đồng nguyên khối chống gỉ, khóa kéo YKK tự khóa an toàn.",
                ]),
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
                'description' => implode("\n", [
                    "• Thành phần vải: 100% Cotton Denim 13.5oz dày dặn, giữ phom ống đứng chuẩn mực.",
                    "• Màu sắc: Nhuộm Indigo sâu màu, hạn chế tối đa ra màu khi giặt.",
                    "• Phom dáng: Classic Straight ống suông thẳng từ đùi xuống mắt cá chân.",
                    "• Chi tiết hoàn thiện: Chỉ may lõi поли cường lực, tag da bò dập nhiệt phía sau lưng quần.",
                ]),
                'sizes' => ['M', 'L', 'XL', 'XXL'],
                'colors' => ['Đen', 'Xanh Navy', 'Xám Ghi'],
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
                'description' => implode("\n", [
                    "• Thành phần vải: 98% Cotton Denim + 2% Elastane co giãn thoải mái khi gập gối.",
                    "• Chi tiết thiết kế: Xử lý xước lazer nhẹ ở phần gối có đáp vải lót bên trong chống toạc rộng.",
                    "• Phom dáng: Tapered Fit gọn dần về phía cổ chân, cực kỳ hợp khi đi cùng giày Sneaker.",
                    "• Chi tiết hoàn thiện: Đường chỉ diễu hai kim sắc nét, toàn bộ khuy nút mạ crôm mờ.",
                ]),
                'sizes' => ['M', 'L', 'XL'],
                'colors' => ['Xanh Nhạt', 'Đen'],
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
                'description' => implode("\n", [
                    "• Thành phần vải: 97% Cotton Chino + 3% Spandex mềm mịn, thấm hút mồ hôi tốt.",
                    "• Phom dáng: Regular Fit dài ngang trên gối 3cm, mang lại diện mạo gọn gàng năng động.",
                    "• Thiết kế cạp: Cạp quần phối thun co giãn ẩn phía sau kèm đỉa quần đeo thắt lưng.",
                    "• Chi tiết hoàn thiện: Túi sâu rộng đựng vừa điện thoại lớn và ví tiền an toàn.",
                ]),
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
                'image' => 'https://images.unsplash.com/photo-1591195853828-11db59a44f6b?w=800&auto=format&fit=crop',
                'stock' => 110,
                'description' => implode("\n", [
                    "• Thành phần vải: 92% Polyester Microfiber + 8% Spandex siêu nhẹ, khô nhanh trong 15 phút.",
                    "• Công nghệ: Đục lỗ thoáng khí Lazer-Cut hai bên hông giúp tản nhiệt tức thì khi vận động.",
                    "• Phom dáng: Active Regular Fit có xẻ V nhỏ ở gấu quần tránh căng tức khi sải bước.",
                    "• Tiện ích: 2 túi bên có khóa kéo ép nhiệt chống rơi đồ khi chạy bộ, đạp xe.",
                ]),
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
                'image' => 'https://images.unsplash.com/photo-1591195853828-11db59a44f6b?w=800&auto=format&fit=crop',
                'stock' => 75,
                'description' => implode("\n", [
                    "• Thành phần vải: 98% Cotton Denim 11.5oz + 2% Spandex đã xử lý giặt mềm.",
                    "• Phom dáng: Regular Fit đứng ống, gấu quần may chặn chỉ tránh bung sợi quá mức.",
                    "• Màu sắc: Wash xanh cổ điển dễ phối cùng áo Polo hoặc áo T-Shirt trơn.",
                    "• Phụ liệu: Khuy đồng đúc nguyên khối, túi đồng hồ (Coin Pocket) chuẩn 5 túi cổ điển.",
                ]),
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
                'description' => implode("\n", [
                    "• Chất liệu dây: Da bò thật nguyên tấm (Full-Grain Leather) xử lý bề mặt chống bong tróc.",
                    "• Kích thước: Bản rộng 3.4cm x Dài 120cm tiêu chuẩn, dễ dàng cắt ngắn theo vòng eo.",
                    "• Đầu khóa: Hợp kim kẽm mạ PVD chống trầy xước, cơ chế khóa trượt tự động 32 nấc.",
                    "• Bảo quản: Kèm hộp cứng sang trọng, bảo hành bề mặt da và đầu khóa 12 tháng.",
                ]),
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
                'description' => implode("\n", [
                    "• Chất liệu dây: Da bò sáp nguyên miếng dày 3.8mm, càng sử dụng càng lên nước bóng đẹp.",
                    "• Kích thước: Bản rộng 3.8cm chuyên dụng phối cùng quần Jeans và quần Khaki.",
                    "• Đầu khóa: Khóa kim đồng đúc nguyên khối chống rỉ sét, chịu lực kéo cao.",
                    "• Hoàn thiện: Xử lý sơn cạnh thủ công 3 lớp mịn màng, đục sẵn 5 lỗ tiêu chuẩn.",
                ]),
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
                'description' => implode("\n", [
                    "• Chất liệu dây: Da bò lớp 1 dập vân Saffiano halus chống xước và chống thấm nước.",
                    "• Kích thước: Bản rộng 3.2cm thanh lịch, vừa vặn hoàn hảo với mọi đỉa quần tây công sở.",
                    "• Đầu khóa: Thiết kế mặt chữ nhật vát cạnh tối giản, tông màu xám khói lịch lãm.",
                    "• Đường may: Khâu viền chỉ sáp đồng màu tinh tế, không lộ mối nối.",
                ]),
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
                'description' => implode("\n", [
                    "• Chất liệu: Da bò Epsom nhập khẩu giữ phom vuông vức, chống trầy xước và chống thấm.",
                    "• Kích thước: 11cm x 8.5cm x 1.2cm siêu mỏng, bỏ túi quần trước không gây cộm.",
                    "• Công năng: 6 khe cắm thẻ ATM/CCCD, 1 ngăn tiền thẳng và 1 lớp màng chắn RFID bảo mật thẻ.",
                    "• Hoàn thiện: Đường chỉ khâu tay tỉ mỉ, lót vải Polyester chống ẩm mốc.",
                ]),
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
                'description' => implode("\n", [
                    "• Chất liệu: Da bò hạt Mil mềm mại, độ đàn hồi tự nhiên cao và không bong nổ.",
                    "• Kích thước: 21cm x 11.5cm x 2.5cm đựng vừa điện thoại 6.8 inch, hộ chiếu và sổ tay nhỏ.",
                    "• Cấu trúc ngăn: 2 ngăn lớn, 1 ngăn khóa kéo giữa và 12 khe cắm thẻ ngân hàng.",
                    "• Phụ kiện: Đi kèm dây đeo cổ tay có thể tháo rời, khóa kéo kim loại mạ titan.",
                ]),
                'sizes' => ['FreeSize'],
                'colors' => ['Nâu Bò', 'Đen'],
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
                'description' => implode("\n", [
                    "• Chất liệu cà vạt: Lụa Jacquard dệt nổi mật độ 1200 mũi tạo độ bóng mờ sang trọng.",
                    "• Kích thước bản: Bản mũi tên 7cm tiêu chuẩn hiện đại, dễ thắt nút Windsor cân đối.",
                    "• Bộ sản phẩm gồm: 01 Cà vạt lụa + 01 Khăn cài túi ngực (Pocket Square) + 01 Kẹp cà vạt mạ bạc.",
                    "• Đóng gói: Hộp vuông đen lì cao cấp, thích hợp làm quà tặng doanh nhân.",
                ]),
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
                'description' => implode("\n", [
                    "• Thành phần sợi: 80% Cotton chải kỹ + 15% Polyamide + 5% Elastane co giãn ôm bàn chân.",
                    "• Công nghệ dệt: Dệt lưới thoáng khí ở mu bàn chân, kết hợp phân tử Ag+ khử mùi hiệu quả.",
                    "• Thiết kế: Cổ ngắn dưới mắt cá chân, có đệm gót chống tuột và giảm ma sát khi đi giày.",
                    "• Quy cách: Hộp 5 đôi phối màu trung tính dễ dàng sử dụng hằng ngày.",
                ]),
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
                'description' => implode("\n", [
                    "• Thành phần sợi: 82% Organic Cotton + 15% Polyester + 3% Spandex êm ái.",
                    "• Kiểu dệt: Dệt gân dọc (Ribbed Knit) ôm cổ chân chắc chắn, không để lại vết hằn trên da.",
                    "• Độ dài: Cổ cao 18cm chuẩn mực khi diện cùng quần Tây công sở hoặc giày Loafer/Sneaker.",
                    "• Độ bền: Gia cố sợi kép tại mũi và gót chân chống thủng rách.",
                ]),
                'sizes' => ['FreeSize'],
                'colors' => ['Xám Ghi', 'Trắng', 'Đen', 'Be Sữa'],
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
                'description' => implode("\n", [
                    "• Chất liệu vải: 100% Cotton Twill định lượng 290GSM dày dặn, thấm hút mồ hôi trán tốt.",
                    "• Phom nón: 6 múi (6-Panel Baseball Cap) ép keo định hình phần trán giữ phom cứng cáp.",
                    "• Chi tiết: Thêu nổi 3D sắc nét phía trước, 6 lỗ thông hơi thêu viền chỉ thoáng mát.",
                    "• Khóa điều chỉnh: Khóa gài kim loại chống gỉ phía sau, phù hợp vòng đầu từ 54cm - 60cm.",
                ]),
                'sizes' => ['FreeSize'],
                'colors' => ['Đen', 'Trắng', 'Be Sữa'],
                'sku_prefix' => 'NON-CAP',
            ],
        ];

        // 6. Lưu vào Cơ Sở Dữ Liệu và Tạo các Biến thể
        foreach ($productsData as $pData) {
            // Ảnh đại diện: dùng ảnh riêng của màu đầu tiên nếu đã có trong public/images/products
            $mainLocal = 'images/products/' . $pData['slug'] . '-' . Str::slug($pData['colors'][0] ?? '') . '.jpg';
            if (file_exists(public_path($mainLocal))) {
                $pData['image'] = $mainLocal;
            }

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

            $catId = $pData['category_id'];
            $firstColorName = $pData['colors'][0] ?? null;

            // Tạo các biến thể sản phẩm (Variants) với ảnh đồng bộ theo đúng danh mục sản phẩm
            foreach ($pData['sizes'] as $sizeName) {
                foreach ($pData['colors'] as $colorName) {
                    $sizeModel = $sizes[$sizeName] ?? null;
                    $colorModel = $colors[$colorName] ?? null;

                    if (!$sizeModel || !$colorModel) continue;

                    $sku = $pData['sku_prefix'] . '-' . $sizeName . '-' . strtoupper(Str::slug($colorName));
                    // Ưu tiên ảnh riêng theo sản phẩm + màu: public/images/products/{slug}-{mau}.jpg
                    $localImage = 'images/products/' . $pData['slug'] . '-' . Str::slug($colorName) . '.jpg';
                    $varImage = file_exists(public_path($localImage))
                        ? $localImage
                        : ($categoryColorGallery[$catId][$colorName] ?? $pData['image']);

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

        // 7. Nếu có bản lưu đồng bộ từ Admin (admin_products_sync.json), tự động áp dụng để giữ nguyên 100% ảnh & dữ liệu đã chỉnh sửa khi clone từ Git
        $snapshotPath = database_path('seeders/admin_products_sync.json');
        if (file_exists($snapshotPath)) {
            $syncedProducts = json_decode(file_get_contents($snapshotPath), true);
            if (is_array($syncedProducts)) {
                foreach ($syncedProducts as $sp) {
                    if (empty($sp['slug'])) continue;

                    $product = Product::updateOrCreate(
                        ['slug' => $sp['slug']],
                        [
                            'category_id' => $sp['category_id'],
                            'name'        => $sp['name'],
                            'slug'        => $sp['slug'],
                            'price'       => $sp['price'],
                            'is_featured' => $sp['is_featured'] ?? false,
                            'image'       => $sp['image'],
                            'stock'       => $sp['stock'] ?? 0,
                            'description' => $sp['description'] ?? '',
                        ]
                    );

                    if (!empty($sp['variants']) && is_array($sp['variants'])) {
                        foreach ($sp['variants'] as $sv) {
                            $sizeModel = !empty($sv['size_name']) ? Size::firstOrCreate(['name' => $sv['size_name']]) : null;
                            $colorModel = !empty($sv['color_name']) ? Color::firstOrCreate(['name' => $sv['color_name']]) : null;
                            if (!$sizeModel || !$colorModel) continue;

                            ProductVariant::updateOrCreate(
                                [
                                    'product_id' => $product->id,
                                    'size_id'    => $sizeModel->id,
                                    'color_id'   => $colorModel->id,
                                ],
                                [
                                    'sku'   => $sv['sku'],
                                    'price' => $sv['price'] ?? $product->price,
                                    'stock' => $sv['stock'] ?? 20,
                                    'image' => $sv['image'] ?? $product->image,
                                ]
                            );
                        }
                        $product->update(['stock' => $product->variants()->sum('stock')]);
                    }
                }
            }
        }
    }
}
