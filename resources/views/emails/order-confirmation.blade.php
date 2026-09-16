<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác nhận đơn hàng #{{ $order->order_code }}</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background-color: #f4f6f8; }
        .container { max-width: 600px; margin: 20px auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .header { background: #111827; color: #ffffff; padding: 25px; text-align: center; }
        .header h1 { margin: 0; font-size: 22px; text-transform: uppercase; letter-spacing: 1px; }
        .content { padding: 25px; }
        .order-info { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 6px; padding: 15px; margin-bottom: 20px; }
        .order-info p { margin: 6px 0; font-size: 14px; }
        .order-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .order-table th, .order-table td { padding: 10px; border-bottom: 1px solid #e5e7eb; font-size: 14px; text-align: left; }
        .order-table th { background: #f3f4f6; color: #4b5563; }
        .total-section { margin-top: 15px; text-align: right; }
        .total-row { font-size: 14px; margin: 5px 0; }
        .grand-total { font-size: 18px; font-weight: bold; color: #dc2626; margin-top: 10px; }
        .footer { background: #f3f4f6; padding: 20px; text-align: center; font-size: 12px; color: #6b7280; }
        .badge { display: inline-block; padding: 3px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; background: #e0f2fe; color: #0369a1; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>WebLocalBrand</h1>
            <p style="margin: 5px 0 0; font-size: 14px; opacity: 0.85;">Cảm ơn bạn đã mua sắm cùng chúng tôi!</p>
        </div>
        <div class="content">
            <h2 style="font-size: 18px; color: #111827;">Chào {{ $order->customer_name }},</h2>
            <p>Đơn hàng <strong>#{{ $order->order_code }}</strong> của bạn đã được tiếp nhận và đang được chuẩn bị để giao tới bạn trong thời gian sớm nhất.</p>

            <div class="order-info">
                <p><strong>Mã đơn hàng:</strong> <span style="color: #dc2626; font-weight: bold;">#{{ $order->order_code }}</span></p>
                <p><strong>Ngày đặt hàng:</strong> {{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : date('d/m/Y H:i') }}</p>
                <p><strong>Phương thức thanh toán:</strong> 
                    @if($order->payment_method === 'cod')
                        Thanh toán khi nhận hàng (COD)
                    @elseif($order->payment_method === 'vietqr')
                        Chuyển khoản VietQR tự động
                    @elseif($order->payment_method === 'vnpay')
                        Cổng thanh toán điện tử VNPAY Sandbox
                    @else
                        {{ strtoupper($order->payment_method) }}
                    @endif
                </p>
                <p><strong>Trạng thái thanh toán:</strong> 
                    <span class="badge" style="{{ $order->payment_status === 'paid' ? 'background: #dcfce7; color: #16a34a;' : '' }}">
                        @if($order->payment_status === 'paid')
                            Đã thanh toán
                        @elseif($order->payment_method === 'cod')
                            Thanh toán khi nhận hàng (COD)
                        @else
                            Chờ thanh toán
                        @endif
                    </span>
                </p>
                <p><strong>Địa chỉ giao hàng:</strong> {{ $order->customer_address }}{{ $order->customer_district ? ', ' . $order->customer_district : '' }}{{ $order->customer_city ? ', ' . $order->customer_city : '' }}</p>
                <p><strong>Số điện thoại:</strong> {{ $order->customer_phone }}</p>
                @if($order->customer_notes)
                    <p><strong>Ghi chú:</strong> <em>{{ $order->customer_notes }}</em></p>
                @endif
            </div>

            <h3 style="font-size: 16px; border-bottom: 2px solid #111827; padding-bottom: 6px;">Chi tiết sản phẩm</h3>
            <table class="order-table">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th style="text-align: center;">SL</th>
                        <th style="text-align: right;">Đơn giá</th>
                        <th style="text-align: right;">Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td>
                                <strong>{{ $item->product_name }}</strong>
                                @if($item->variant_info)
                                    <br><small style="color: #6b7280;">{{ $item->variant_info }}</small>
                                @endif
                            </td>
                            <td style="text-align: center;">{{ $item->quantity }}</td>
                            <td style="text-align: right;">{{ number_format($item->price, 0, ',', '.') }} ₫</td>
                            <td style="text-align: right; font-weight: bold;">{{ number_format($item->total_price, 0, ',', '.') }} ₫</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="total-section">
                <div class="total-row">Tạm tính: <strong>{{ number_format($order->total_amount - $order->shipping_fee + $order->discount_amount, 0, ',', '.') }} ₫</strong></div>
                <div class="total-row">Phí vận chuyển: <strong>{{ $order->shipping_fee > 0 ? number_format($order->shipping_fee, 0, ',', '.') . ' ₫' : 'Miễn phí' }}</strong></div>
                @if($order->discount_amount > 0)
                    <div class="total-row" style="color: #16a34a;">Giảm giá: <strong>-{{ number_format($order->discount_amount, 0, ',', '.') }} ₫</strong></div>
                @endif
                <div class="grand-total">Tổng thanh toán: {{ number_format($order->total_amount, 0, ',', '.') }} ₫</div>
            </div>
        </div>

        <div class="footer">
            <p>Nếu bạn có bất kỳ câu hỏi nào, vui lòng liên hệ hotline <strong>1900 8079</strong> hoặc phản hồi email này.</p>
            <p>&copy; {{ date('Y') }} WebLocalBrand. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
