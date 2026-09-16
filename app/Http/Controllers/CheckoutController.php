<?php

namespace App\Http\Controllers;

use App\Mail\OrderConfirmationMail;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    /**
     * Hiển thị trang Checkout
     */
    public function index()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('warning', 'Giỏ hàng của bạn đang trống. Vui lòng chọn sản phẩm trước khi thanh toán!');
        }

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $shippingFee = ($subtotal >= CartController::FREE_SHIPPING_THRESHOLD || $subtotal == 0) ? 0 : CartController::DEFAULT_SHIPPING_FEE;
        $total = $subtotal + $shippingFee;

        // Thông tin người dùng hiện tại nếu đã đăng nhập
        $user = auth()->user();

        return view('pages.checkout', compact('cart', 'subtotal', 'shippingFee', 'total', 'user'));
    }

    /**
     * Xử lý gửi đơn hàng (Thanh toán COD / VietQR / VNPAY)
     */
    public function process(Request $request)
    {
        $request->validate([
            'customer_name'     => 'required|string|max:255',
            'customer_email'    => 'required|email|max:255',
            'customer_phone'    => ['required', 'regex:/^(0[3|5|7|8|9])[0-9]{8}$/'],
            'customer_city'     => 'required|string|max:100',
            'customer_district' => 'required|string|max:100',
            'customer_address'  => 'required|string|max:255',
            'customer_notes'    => 'nullable|string|max:1000',
            'payment_method'    => 'required|in:cod,vietqr,vnpay',
        ], [
            'customer_name.required'     => 'Vui lòng nhập họ và tên người nhận.',
            'customer_email.required'    => 'Vui lòng nhập địa chỉ email để nhận thông tin đơn hàng.',
            'customer_phone.required'    => 'Vui lòng nhập số điện thoại nhận hàng.',
            'customer_phone.regex'       => 'Số điện thoại không hợp lệ! Vui lòng nhập đúng số điện thoại di động Việt Nam gồm 10 chữ số (bắt đầu bằng 03, 05, 07, 08, 09).',
            'customer_city.required'     => 'Vui lòng chọn Tỉnh/Thành phố.',
            'customer_district.required' => 'Vui lòng chọn Quận/Huyện.',
            'customer_address.required'  => 'Vui lòng nhập địa chỉ cụ thể.',
            'payment_method.required'    => 'Vui lòng chọn một phương thức thanh toán.',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('warning', 'Giỏ hàng của bạn đang trống!');
        }

        // 1. Kiểm tra tồn kho trước khi đặt
        foreach ($cart as $item) {
            if (!empty($item['variant_id'])) {
                $variant = ProductVariant::find($item['variant_id']);
                if (!$variant || $variant->stock < $item['quantity']) {
                    return redirect()->route('cart.index')->with('error', "Sản phẩm '{$item['name']}' không đủ số lượng tồn kho!");
                }
            } else {
                $product = Product::find($item['product_id']);
                if (!$product || $product->stock < $item['quantity']) {
                    return redirect()->route('cart.index')->with('error', "Sản phẩm '{$item['name']}' không đủ số lượng tồn kho!");
                }
            }
        }

        // 2. Tính tổng tiền
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        $shippingFee = ($subtotal >= CartController::FREE_SHIPPING_THRESHOLD || $subtotal == 0) ? 0 : CartController::DEFAULT_SHIPPING_FEE;
        $totalAmount = $subtotal + $shippingFee;

        // 3. Tạo mã đơn hàng duy nhất
        $orderCode = 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(5));

        // 4. Lưu đơn hàng và trừ kho trong DB Transaction
        $order = null;
        try {
            DB::beginTransaction();

            $order = Order::create([
                'user_id'           => auth()->id(),
                'order_code'        => $orderCode,
                'customer_name'     => $request->customer_name,
                'customer_email'    => $request->customer_email,
                'customer_phone'    => $request->customer_phone,
                'customer_address'  => $request->customer_address,
                'customer_city'     => $request->customer_city,
                'customer_district' => $request->customer_district,
                'customer_notes'    => $request->customer_notes,
                'total_amount'      => $totalAmount,
                'shipping_fee'      => $shippingFee,
                'discount_amount'   => 0,
                'payment_method'    => $request->payment_method,
                'payment_status'    => Order::PAYMENT_UNPAID,
                'order_status'      => Order::STATUS_PENDING,
            ]);

            foreach ($cart as $item) {
                // Chuẩn bị chuỗi thông tin biến thể
                $variantDetails = [];
                if (!empty($item['size'])) {
                    $variantDetails[] = 'Size: ' . $item['size'];
                }
                if (!empty($item['color'])) {
                    $variantDetails[] = 'Màu: ' . $item['color'];
                }
                $variantInfo = !empty($variantDetails) ? implode(', ', $variantDetails) : null;

                OrderItem::create([
                    'order_id'           => $order->id,
                    'product_id'         => $item['product_id'],
                    'product_variant_id' => $item['variant_id'] ?? null,
                    'product_name'       => $item['name'],
                    'variant_info'       => $variantInfo,
                    'price'              => $item['price'],
                    'quantity'           => $item['quantity'],
                    'total_price'        => $item['price'] * $item['quantity'],
                ]);

                // Trừ số lượng tồn kho (Stock)
                if (!empty($item['variant_id'])) {
                    ProductVariant::where('id', $item['variant_id'])->decrement('stock', $item['quantity']);
                    Product::where('id', $item['product_id'])->decrement('stock', $item['quantity']);
                } else {
                    Product::where('id', $item['product_id'])->decrement('stock', $item['quantity']);
                }
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Lỗi khi lưu đơn hàng: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->withInput()->with('error', 'Có lỗi xảy ra trong quá trình xử lý đơn hàng. Vui lòng thử lại!');
        }

        // Xóa giỏ hàng khỏi session sau khi lưu thành công
        session()->forget('cart');

        // 5. Điều hướng theo phương thức thanh toán & Gửi email xác nhận
        if ($order->payment_method === 'vietqr') {
            // Chuyển hướng tới màn hình Quét mã VietQR & Xác nhận thanh toán
            return redirect()->route('checkout.vietqr', $order->order_code);
        }

        if ($order->payment_method === 'vnpay') {
            // Chuyển hướng tới trang Cổng mô phỏng VNPAY Sandbox chuyên nghiệp
            return redirect()->route('checkout.vnpay.sandbox', $order->order_code);
        }

        // Với COD (hoặc mặc định): Đặt hàng thành công ngay lập tức, gửi mail xác nhận
        $this->sendOrderConfirmationEmail($order);

        return redirect()->route('checkout.success', $order->order_code);
    }

    /**
     * Màn hình Cổng Thanh Toán VNPAY Sandbox (Mô phỏng & Kết nối Live)
     */
    public function vnpaySandbox($order_code)
    {
        $order = Order::where('order_code', $order_code)->firstOrFail();
        $liveVnpayUrl = $this->createVnPayPaymentUrl($order);

        return view('pages.vnpay-sandbox', compact('order', 'liveVnpayUrl'));
    }

    /**
     * Tạo URL cổng thanh toán VNPAY Sandbox chuẩn thuật toán SHA512
     */
    public function createVnPayPaymentUrl(Order $order): string
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');

        $vnp_TmnCode = config('payment.vnpay.tmn_code', '2QXUI4J4');
        $vnp_HashSecret = config('payment.vnpay.hash_secret', 'RAOCTAV2AWWJLLGPH822WGYXZCJGTGMN');
        $vnp_Url = config('payment.vnpay.url', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html');
        $vnp_Returnurl = config('payment.vnpay.return_url', route('checkout.vnpay.return'));

        $vnp_TxnRef = $order->order_code;
        // Bỏ ký tự đặc biệt theo quy định của VNPAY
        $cleanCode = preg_replace('/[^A-Za-z0-9]/', '', $order->order_code);
        $vnp_OrderInfo = 'Thanh toan don hang ' . $cleanCode;
        $vnp_OrderType = 'other';
        $vnp_Amount = (int)($order->total_amount * 100);
        $vnp_Locale = 'vn';
        $vnp_IpAddr = '127.0.0.1';
        $vnp_CreateDate = date('YmdHis');
        $vnp_ExpireDate = date('YmdHis', strtotime('+15 minutes'));

        $inputData = [
            'vnp_Version'        => '2.1.0',
            'vnp_TmnCode'        => $vnp_TmnCode,
            'vnp_Amount'         => $vnp_Amount,
            'vnp_Command'        => 'pay',
            'vnp_CreateDate'     => $vnp_CreateDate,
            'vnp_CurrCode'       => 'VND',
            'vnp_IpAddr'         => $vnp_IpAddr,
            'vnp_Locale'         => $vnp_Locale,
            'vnp_OrderInfo'      => $vnp_OrderInfo,
            'vnp_OrderType'      => $vnp_OrderType,
            'vnp_ReturnUrl'      => $vnp_Returnurl,
            'vnp_TxnRef'         => $vnp_TxnRef,
            'vnp_ExpireDate'     => $vnp_ExpireDate,
        ];

        ksort($inputData);
        $query = '';
        $i = 0;
        $hashdata = '';
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . '=' . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . '=' . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . '=' . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . '?' . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }

        return $vnp_Url;
    }

    /**
     * Xử lý kết quả trả về từ VNPAY (Return URL)
     */
    public function vnpayReturn(Request $request)
    {
        $vnp_HashSecret = config('payment.vnpay.hash_secret', 'RAOCTAV2AWWJLLGPH822WGYXZCJGTGMN');
        $inputData = [];

        foreach ($request->all() as $key => $value) {
            if (substr($key, 0, 4) == 'vnp_') {
                $inputData[$key] = $value;
            }
        }

        $vnp_SecureHash = $inputData['vnp_SecureHash'] ?? '';
        unset($inputData['vnp_SecureHash']);
        unset($inputData['vnp_SecureHashType']);

        ksort($inputData);
        $i = 0;
        $hashData = '';
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData .= '&' . urlencode($key) . '=' . urlencode($value);
            } else {
                $hashData .= urlencode($key) . '=' . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
        $orderCode = $request->input('vnp_TxnRef');
        $order = Order::where('order_code', $orderCode)->first();

        if (!$order) {
            return redirect()->route('home')->with('error', 'Không tìm thấy thông tin đơn hàng thanh toán!');
        }

        // Chấp nhận callback hợp lệ từ checksum hoặc từ mô phỏng sandbox
        $isHashValid = ($secureHash === $vnp_SecureHash) || ($request->input('is_mock') == '1');

        if ($isHashValid) {
            if ($request->input('vnp_ResponseCode') === '00') {
                // Thanh toán thành công
                $order->payment_status = Order::PAYMENT_PAID;
                $order->order_status = Order::STATUS_PROCESSING;
                $order->save();

                // Gửi email xác nhận thanh toán thành công
                $this->sendOrderConfirmationEmail($order);

                return redirect()->route('checkout.success', $order->order_code)
                                 ->with('success', 'Giao dịch qua VNPAY thành công! Đơn hàng của bạn đã được thanh toán.');
            } else {
                // Người dùng hủy hoặc giao dịch thất bại
                return redirect()->route('checkout.success', $order->order_code)
                                 ->with('warning', 'Giao dịch qua VNPAY chưa hoàn tất hoặc bị hủy (Mã phản hồi: ' . $request->input('vnp_ResponseCode') . '). Bạn có thể chuyển sang phương thức Chuyển khoản VietQR.');
            }
        } else {
            return redirect()->route('checkout.success', $order->order_code)
                             ->with('error', 'Chữ ký phản hồi từ cổng thanh toán không hợp lệ!');
        }
    }

    /**
     * Màn hình Quét mã VietQR & Thông tin thanh toán
     */
    public function vietqr($order_code)
    {
        $order = Order::with('items.product')->where('order_code', $order_code)->firstOrFail();

        // Nếu đơn hàng đã được thanh toán, chuyển hướng thẳng đến trang hoàn tất
        if ($order->payment_status === Order::PAYMENT_PAID) {
            return redirect()->route('checkout.success', $order->order_code);
        }

        $bankConfig = config('payment.vietqr');
        $bankId = $bankConfig['bank_id'] ?? 'MB';
        $accNo = $bankConfig['account_no'] ?? '0987654321';
        $accName = urlencode($bankConfig['account_name'] ?? 'LOCAL BRAND OFFICIAL');
        $amount = (int) $order->total_amount;
        $addInfo = urlencode($order->order_code);

        $qrUrl = "https://img.vietqr.io/image/{$bankId}-{$accNo}-compact2.png?amount={$amount}&addInfo={$addInfo}&accountName={$accName}";

        return view('pages.vietqr', compact('order', 'qrUrl', 'bankConfig'));
    }

    /**
     * Xử lý xác nhận đã thanh toán VietQR từ khách hàng
     */
    public function vietqrConfirm($order_code)
    {
        $order = Order::with('items.product')->where('order_code', $order_code)->firstOrFail();

        // Nếu chưa đánh dấu thanh toán, cập nhật trạng thái
        if ($order->payment_status !== Order::PAYMENT_PAID) {
            $order->payment_status = Order::PAYMENT_PAID;
            $order->order_status = Order::STATUS_PROCESSING;
            $order->save();

            // Gửi email xác nhận đặt hàng thành công đến email khách hàng đã điền
            $this->sendOrderConfirmationEmail($order);
        }

        return redirect()->route('checkout.success', $order->order_code)
                         ->with('success', 'Xác nhận thanh toán VietQR thành công! Đơn hàng #' . $order->order_code . ' đã hoàn tất.');
    }

    /**
     * Trang Cảm ơn / Order Success
     */
    public function success($order_code)
    {
        $order = Order::with('items.product')->where('order_code', $order_code)->firstOrFail();

        $qrUrl = null;
        $bankConfig = config('payment.vietqr');

        if ($order->payment_method === 'vietqr') {
            $bankId = $bankConfig['bank_id'] ?? 'MB';
            $accNo = $bankConfig['account_no'] ?? '0987654321';
            $accName = urlencode($bankConfig['account_name'] ?? 'LOCAL BRAND OFFICIAL');
            $amount = (int) $order->total_amount;
            $addInfo = urlencode($order->order_code);

            $qrUrl = "https://img.vietqr.io/image/{$bankId}-{$accNo}-compact2.png?amount={$amount}&addInfo={$addInfo}&accountName={$accName}";
        }

        return view('pages.order-success', compact('order', 'qrUrl', 'bankConfig'));
    }

    /**
     * Gửi email xác nhận đơn hàng an toàn với fallback log
     */
    protected function sendOrderConfirmationEmail(Order $order)
    {
        try {
            Mail::to($order->customer_email)->send(new OrderConfirmationMail($order));
            Log::info("Email xác nhận đơn hàng #{$order->order_code} đã được gửi thành công tới: {$order->customer_email}");
            return true;
        } catch (\Throwable $e) {
            Log::warning("Không gửi được email qua SMTP mặc định: " . $e->getMessage() . ". Tự động ghi vào log...");
            try {
                Mail::mailer('log')->to($order->customer_email)->send(new OrderConfirmationMail($order));
            } catch (\Throwable $logEx) {
                Log::error("Lỗi khi ghi log email: " . $logEx->getMessage());
            }
            return false;
        }
    }
}
