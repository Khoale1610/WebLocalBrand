<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Product;
use App\Models\ProductVariant;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $customer1 = User::where('email', 'khachhang@gmail.com')->first();
        $customer2 = User::where('email', 'maitran@gmail.com')->first();

        $products = Product::with(['variants.size', 'variants.color'])->get();

        if ($products->isEmpty()) {
            return;
        }

        // Danh sách các đơn hàng mẫu phong phú
        $sampleOrders = [
            [
                'user_id'          => $customer1 ? $customer1->id : null,
                'order_code'       => 'ORD-20260828-001',
                'customer_name'    => 'Nguyễn Văn An',
                'customer_email'   => 'khachhang@gmail.com',
                'customer_phone'   => '0987654321',
                'customer_address' => '123 Cầu Giấy, Phường Quan Hoa, Cầu Giấy, Hà Nội',
                'customer_city'    => 'Hà Nội',
                'customer_district'=> 'Cầu Giấy',
                'customer_notes'   => 'Giao hàng giờ hành chính giúp mình nhé.',
                'shipping_fee'     => 0,
                'discount_amount'  => 50000,
                'payment_method'   => 'cod',
                'payment_status'   => Order::PAYMENT_PAID,
                'order_status'     => Order::STATUS_COMPLETED,
                'items'            => [
                    ['product_index' => 0, 'quantity' => 2], // Áo Sơ Mi Bamboo
                    ['product_index' => 5, 'quantity' => 1], // Quần Tây Slimfit
                ],
                'created_at'       => now()->subDays(5),
            ],
            [
                'user_id'          => $customer2 ? $customer2->id : null,
                'order_code'       => 'ORD-20260828-002',
                'customer_name'    => 'Trần Thị Mai',
                'customer_email'   => 'maitran@gmail.com',
                'customer_phone'   => '0912345678',
                'customer_address' => '456 Lê Lợi, Phường Bến Nghé, Quận 1, TP. Hồ Chí Minh',
                'customer_city'    => 'TP. Hồ Chí Minh',
                'customer_district'=> 'Quận 1',
                'customer_notes'   => 'Đóng gói cẩn thận làm quà tặng sinh nhật.',
                'shipping_fee'     => 30000,
                'discount_amount'  => 0,
                'payment_method'   => 'vnpay',
                'payment_status'   => Order::PAYMENT_PAID,
                'order_status'     => Order::STATUS_SHIPPING,
                'items'            => [
                    ['product_index' => 2, 'quantity' => 1], // Áo Polo Bo Cổ
                    ['product_index' => 7, 'quantity' => 1], // Thắt Lưng Da
                ],
                'created_at'       => now()->subDays(2),
            ],
            [
                'user_id'          => null, // Khách vãng lai (Guest checkout)
                'order_code'       => 'ORD-20260828-003',
                'customer_name'    => 'Lê Hoàng Long',
                'customer_email'   => 'longle@gmail.com',
                'customer_phone'   => '0933112233',
                'customer_address' => '789 Nguyễn Trãi, Phường 2, Quận 5, TP. Hồ Chí Minh',
                'customer_city'    => 'TP. Hồ Chí Minh',
                'customer_district'=> 'Quận 5',
                'customer_notes'   => 'Gọi trước khi giao 15 phút.',
                'shipping_fee'     => 30000,
                'discount_amount'  => 0,
                'payment_method'   => 'cod',
                'payment_status'   => Order::PAYMENT_UNPAID,
                'order_status'     => Order::STATUS_PROCESSING,
                'items'            => [
                    ['product_index' => 4, 'quantity' => 3], // Áo Thun Trơn Cotton
                ],
                'created_at'       => now()->subDay(),
            ],
            [
                'user_id'          => null, // Khách vãng lai (Guest checkout)
                'order_code'       => 'ORD-20260828-004',
                'customer_name'    => 'Phạm Minh Đức',
                'customer_email'   => 'ducpham@gmail.com',
                'customer_phone'   => '0977889900',
                'customer_address' => '12 Trần Phú, Phường Máy Tơ, Ngô Quyền, Hải Phòng',
                'customer_city'    => 'Hải Phòng',
                'customer_district'=> 'Ngô Quyền',
                'customer_notes'   => '',
                'shipping_fee'     => 30000,
                'discount_amount'  => 0,
                'payment_method'   => 'momo',
                'payment_status'   => Order::PAYMENT_PAID,
                'order_status'     => Order::STATUS_PENDING,
                'items'            => [
                    ['product_index' => 3, 'quantity' => 1], // Áo Polo Thể Thao Quick-Dry
                    ['product_index' => 6, 'quantity' => 1], // Quần Khaki
                ],
                'created_at'       => now()->subHours(4),
            ],
            [
                'user_id'          => $customer1 ? $customer1->id : null,
                'order_code'       => 'ORD-20260828-005',
                'customer_name'    => 'Nguyễn Văn An',
                'customer_email'   => 'khachhang@gmail.com',
                'customer_phone'   => '0987654321',
                'customer_address' => '123 Cầu Giấy, Cầu Giấy, Hà Nội',
                'customer_city'    => 'Hà Nội',
                'customer_district'=> 'Cầu Giấy',
                'customer_notes'   => 'Đặt nhầm size xin hủy đơn.',
                'shipping_fee'     => 0,
                'discount_amount'  => 0,
                'payment_method'   => 'cod',
                'payment_status'   => Order::PAYMENT_UNPAID,
                'order_status'     => Order::STATUS_CANCELLED,
                'items'            => [
                    ['product_index' => 1, 'quantity' => 1], // Áo Sơ Mi Kẻ Nano
                ],
                'created_at'       => now()->subDays(7),
            ],
        ];

        foreach ($sampleOrders as $orderData) {
            $itemsData = $orderData['items'];
            unset($orderData['items']);

            // Tính toán tổng tiền sản phẩm
            $calculatedSubtotal = 0;
            $orderItemsToInsert = [];

            foreach ($itemsData as $itemInfo) {
                $product = $products[$itemInfo['quantity'] ? ($itemInfo['product_index'] % $products->count()) : 0];
                $variant = $product->variants->first(); // Lấy 1 biến thể ngẫu nhiên có sẵn

                $unitPrice = $variant ? $variant->effective_price : $product->price;
                $quantity = $itemInfo['quantity'];
                $totalItemPrice = $unitPrice * $quantity;
                $calculatedSubtotal += $totalItemPrice;

                $variantInfoText = 'Mặc định';
                if ($variant && $variant->size && $variant->color) {
                    $variantInfoText = "Size: {$variant->size->name}, Màu: {$variant->color->name} (SKU: {$variant->sku})";
                }

                $orderItemsToInsert[] = [
                    'product_id'         => $product->id,
                    'product_variant_id' => $variant ? $variant->id : null,
                    'product_name'       => $product->name,
                    'variant_info'       => $variantInfoText,
                    'price'              => $unitPrice,
                    'quantity'           => $quantity,
                    'total_price'        => $totalItemPrice,
                    'created_at'         => $orderData['created_at'],
                    'updated_at'         => $orderData['created_at'],
                ];
            }

            $orderData['total_amount'] = max(0, $calculatedSubtotal + $orderData['shipping_fee'] - $orderData['discount_amount']);
            $orderData['updated_at'] = $orderData['created_at'];

            $order = Order::create($orderData);

            foreach ($orderItemsToInsert as $item) {
                $item['order_id'] = $order->id;
                OrderItem::create($item);
            }
        }
    }
}
