<?php

namespace Tests\Feature;

use App\Mail\OrderConfirmationMail;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class VietQrCheckoutTest extends TestCase
{
    public function test_vietqr_checkout_flow()
    {
        Mail::fake();

        $product = Product::first();
        $this->assertNotNull($product);

        // Giả lập giỏ hàng trong session
        $cart = [
            'item_1' => [
                'product_id' => $product->id,
                'name'       => $product->name,
                'price'      => $product->price,
                'quantity'   => 1,
                'image'      => $product->image_url ?? '',
                'size'       => 'L',
                'color'      => 'Đen',
            ],
        ];

        // 1. Gửi form đặt hàng với phương thức VietQR
        $response = $this->withSession(['cart' => $cart])->post(route('checkout.process'), [
            'customer_name'     => 'Nguyễn Văn Test',
            'customer_email'    => 'test_customer@gmail.com',
            'customer_phone'    => '0987654321',
            'customer_city'     => 'Hà Nội',
            'customer_district' => 'Cầu Giấy',
            'customer_address'  => '123 Đường Cầu Giấy',
            'payment_method'    => 'vietqr',
        ]);

        // Đơn hàng mới nhất trong DB
        $order = Order::where('customer_email', 'test_customer@gmail.com')->orderBy('id', 'desc')->first();
        $this->assertNotNull($order);
        $this->assertEquals('vietqr', $order->payment_method);
        $this->assertEquals(Order::PAYMENT_UNPAID, $order->payment_status);

        // Phải chuyển hướng đến màn hình quét mã VietQR
        $response->assertRedirect(route('checkout.vietqr', $order->order_code));

        // 2. Truy cập màn hình quét mã VietQR
        $vietqrPage = $this->get(route('checkout.vietqr', $order->order_code));
        $vietqrPage->assertStatus(200);
        $vietqrPage->assertSee($order->order_code);
        $vietqrPage->assertSee('MB Bank');
        $vietqrPage->assertSee('XÁC NHẬN ĐÃ THANH TOÁN');

        // 3. Bấm nút Xác nhận đã thanh toán
        $confirmResponse = $this->post(route('checkout.vietqr.confirm', $order->order_code));

        // Phải chuyển hướng về trang success
        $confirmResponse->assertRedirect(route('checkout.success', $order->order_code));

        // Đơn hàng phải chuyển sang trạng thái đã thanh toán
        $order->refresh();
        $this->assertEquals(Order::PAYMENT_PAID, $order->payment_status);
        $this->assertEquals(Order::STATUS_PROCESSING, $order->order_status);

        // Mail xác nhận phải được gửi đến đúng email khách hàng
        Mail::assertSent(OrderConfirmationMail::class, function ($mail) use ($order) {
            return $mail->hasTo('test_customer@gmail.com') &&
                   $mail->order->order_code === $order->order_code;
        });

        // 4. Nếu truy cập lại màn hình VietQR khi đã thanh toán, phải tự động chuyển sang trang success
        $vietqrPageAfterPaid = $this->get(route('checkout.vietqr', $order->order_code));
        $vietqrPageAfterPaid->assertRedirect(route('checkout.success', $order->order_code));

        // 5. Trang success hiển thị đúng trạng thái Đã thanh toán và email đã gửi
        $successPage = $this->get(route('checkout.success', $order->order_code));
        $successPage->assertStatus(200);
        $successPage->assertSee('Thanh Toán Đơn Hàng Thành Công!');
        $successPage->assertSee('test_customer@gmail.com');
    }

    public function test_cod_checkout_sends_email_immediately()
    {
        Mail::fake();

        $product = Product::first();
        $this->assertNotNull($product);

        $cart = [
            'item_1' => [
                'product_id' => $product->id,
                'name'       => $product->name,
                'price'      => $product->price,
                'quantity'   => 1,
                'image'      => $product->image_url ?? '',
                'size'       => 'M',
                'color'      => 'Trắng',
            ],
        ];

        // Gửi form đặt hàng với phương thức COD
        $response = $this->withSession(['cart' => $cart])->post(route('checkout.process'), [
            'customer_name'     => 'Trần Thị COD',
            'customer_email'    => 'cod_customer@gmail.com',
            'customer_phone'    => '0912345678',
            'customer_city'     => 'TP. Hồ Chí Minh',
            'customer_district' => 'Quận 1',
            'customer_address'  => '456 Lê Lợi',
            'payment_method'    => 'cod',
        ]);

        $order = Order::where('customer_email', 'cod_customer@gmail.com')->orderBy('id', 'desc')->first();
        $this->assertNotNull($order);
        $this->assertEquals('cod', $order->payment_method);

        // COD chuyển hướng thẳng đến trang success
        $response->assertRedirect(route('checkout.success', $order->order_code));

        // Mail xác nhận phải được gửi ngay
        Mail::assertSent(OrderConfirmationMail::class, function ($mail) {
            return $mail->hasTo('cod_customer@gmail.com');
        });
    }

    public function test_order_confirmation_mail_renders_properly()
    {
        $order = Order::with('items.product')->orderBy('id', 'desc')->first();
        $this->assertNotNull($order);

        $mailable = new OrderConfirmationMail($order);
        $rendered = $mailable->render();

        $this->assertStringContainsString($order->order_code, $rendered);
        $this->assertStringContainsString($order->customer_name, $rendered);
        $this->assertStringContainsString('WebLocalBrand', $rendered);
    }
}
