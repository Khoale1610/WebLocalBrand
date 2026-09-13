<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ChatbotController extends Controller
{
    /**
     * Tiếp nhận và phản hồi tin nhắn chatbot
     */
    public function reply(Request $request)
    {
        $message = trim($request->input('message', ''));

        if (empty($message)) {
            return response()->json([
                'success' => false,
                'reply'   => 'Dạ bạn có thể đặt câu hỏi để em hỗ trợ tư vấn sản phẩm, chọn size hoặc đơn hàng tại Local Brand nhé!',
            ]);
        }

        $reply = $this->generateBotReply($message);

        return response()->json([
            'success' => true,
            'reply'   => $reply,
        ]);
    }

    /**
     * Động cơ xử lý ngôn ngữ & Tri thức cửa hàng Local Brand
     */
    protected function generateBotReply(string $rawMessage): string
    {
        $msg = Str::lower($rawMessage);

        // 1. Chào hỏi thân thiện
        if (preg_match('/^(chào|hi|hello|alo|hey|shop ơi|admin ơi|chào shop|xin chào|owen)/u', $msg)) {
            return "Dạ Owen xin chào bạn! Mình là trợ lý ảo của thương hiệu Owen. Mình có thể hỗ trợ bạn tìm mẫu áo sơ mi, polo, quần tây, tư vấn size chuẩn, kiểm tra đơn hàng hoặc giải đáp chính sách đổi trả/freeship. Bạn đang quan tâm đến sản phẩm nào ạ?";
        }

        // 2. Tra cứu mã đơn hàng trực tiếp trong tin nhắn (ví dụ: ORD-20260914-XXXXX)
        if (preg_match('/(ord-[a-z0-9\-]+)/ui', $rawMessage, $matches)) {
            $orderCode = strtoupper($matches[1]);
            $order = Order::with('items')->where('order_code', $orderCode)->first();

            if ($order) {
                $statusBadge = $order->order_status_label;
                $paymentStatus = $order->payment_status === 'paid' ? 'Đã thanh toán' : 'Chưa thanh toán';
                $totalFormatted = number_format($order->total_amount, 0, ',', '.') . ' ₫';
                
                return "Dạ thông tin đơn hàng <strong>#{$order->order_code}</strong> của bạn như sau:<br>" .
                       "• <strong>Người nhận:</strong> {$order->customer_name} ({$order->customer_phone})<br>" .
                       "• <strong>Trạng thái đơn:</strong> <span class='text-primary fw-bold'>{$statusBadge}</span><br>" .
                       "• <strong>Thanh toán:</strong> {$paymentStatus} ({$order->payment_method})<br>" .
                       "• <strong>Tổng tiền:</strong> {$totalFormatted}<br>" .
                       "• <strong>Số món:</strong> {$order->items->count()} sản phẩm.<br>" .
                       "Đơn hàng của bạn đang được xử lý nhanh nhất có thể ạ!";
            } else {
                return "Dạ em đã kiểm tra mã đơn hàng <strong>#{$orderCode}</strong> nhưng chưa tìm thấy trên hệ thống. Bạn vui lòng kiểm tra lại mã đơn trong email hoặc gọi hotline <strong>1900 8079</strong> để nhân viên hỗ trợ ngay nhé!";
            }
        }

        // 3. Tư vấn chọn Size (Nhận diện chiều cao, cân nặng)
        if (preg_match('/(cao|nặng|kg|m[0-9]{2}|size|kích cỡ|chọn size|bảng size|mặc vừa)/u', $msg)) {
            // Kiểm tra nếu có cân nặng cụ thể (vd: 65kg, 70 kg)
            preg_match('/([0-9]{2,3})\s*kg/u', $msg, $weightMatch);
            $weight = isset($weightMatch[1]) ? (int)$weightMatch[1] : 0;

            if ($weight > 0) {
                $suggestedSize = 'M';
                if ($weight < 56) {
                    $suggestedSize = 'S (Dành cho 48 - 55kg)';
                } elseif ($weight <= 65) {
                    $suggestedSize = 'M (Dành cho 56 - 65kg)';
                } elseif ($weight <= 73) {
                    $suggestedSize = 'L (Dành cho 66 - 73kg)';
                } elseif ($weight <= 82) {
                    $suggestedSize = 'XL (Dành cho 74 - 82kg)';
                } else {
                    $suggestedSize = 'XXL (Dành cho trên 82kg)';
                }

                return "Dạ với cân nặng khoảng <strong>{$weight}kg</strong>, Owen khuyên bạn nên chọn <strong>Size {$suggestedSize}</strong> để mặc vừa vặn và thoải mái nhất ạ! Nếu bạn thích mặc form rộng (Oversize) che khuyết điểm, bạn có thể tăng thêm 1 size nhé.";
            }

            return "Dạ đây là <strong>Bảng size chuẩn của Owen</strong> để bạn tham khảo ạ:<br>" .
                   "• <strong>Size S:</strong> Chiều cao &lt; 1m65, cân nặng 48 - 55kg<br>" .
                   "• <strong>Size M:</strong> Chiều cao 1m65 - 1m72, cân nặng 56 - 65kg<br>" .
                   "• <strong>Size L:</strong> Chiều cao 1m70 - 1m77, cân nặng 66 - 73kg<br>" .
                   "• <strong>Size XL:</strong> Chiều cao 1m75 - 1m82, cân nặng 74 - 82kg<br>" .
                   "• <strong>Size XXL:</strong> Chiều cao &gt; 1m80, cân nặng 83 - 90kg<br>" .
                   "Bạn cho Owen xin chiều cao và cân nặng để mình tư vấn size chuẩn xác nhất cho bạn nha!";
        }

        // 4. Chính sách Đổi trả & Bảo hành
        if (preg_match('/(đổi trả|bảo hành|trả hàng|đổi size|lỗi|đổi hàng)/u', $msg)) {
            return "Dạ về <strong>Chính sách đổi trả</strong>, Owen hỗ trợ rất chu đáo:<br>" .
                   "• Hỗ trợ <strong>đổi hàng trong vòng 30 ngày</strong> kể từ ngày nhận hàng.<br>" .
                   "• <strong>Miễn phí đổi size</strong> nếu bạn mặc không vừa hoặc sản phẩm có lỗi từ nhà sản xuất.<br>" .
                   "• <em>Điều kiện:</em> Sản phẩm còn nguyên tem mác, chưa qua giặt ủi hay sử dụng.<br>" .
                   "Nếu cần hỗ trợ đổi trả đơn hàng, bạn chỉ cần liên hệ qua hotline <strong>1900 8079</strong> là được hỗ trợ ngay ạ!";
        }

        // 5. Chính sách Giao hàng & Vận chuyển (Freeship)
        if (preg_match('/(ship|vận chuyển|phí ship|giao hàng|bao lâu|freeship|miễn phí ship|ship cod)/u', $msg)) {
            return "Dạ về <strong>Chính sách giao hàng</strong> của Owen:<br>" .
                   "• <strong>MIỄN PHÍ VẬN CHUYỂN (Freeship)</strong> toàn quốc cho mọi đơn hàng từ <strong>500.000 ₫</strong> trở lên.<br>" .
                   "• Đơn hàng dưới 500.000 ₫ áp dụng mức phí ship đồng giá ưu đãi chỉ <strong>30.000 ₫</strong>.<br>" .
                   "• Thời gian nhận hàng: Nội thành 1 - 2 ngày, các tỉnh thành khác khoảng 2 - 4 ngày làm việc ạ.";
        }

        // 6. Phương thức Thanh toán (COD, VietQR, VNPAY)
        if (preg_match('/(thanh toán|chuyển khoản|qr|vietqr|vnpay|ngân hàng|trả tiền|cod|thẻ)/u', $msg)) {
            return "Dạ Owen hỗ trợ 3 phương thức thanh toán cực kỳ tiện lợi và an toàn:<br>" .
                   "1. <strong>COD:</strong> Thanh toán tiền mặt khi shipper giao hàng tận tay.<br>" .
                   "2. <strong>Quét mã VietQR tự động:</strong> Tự động hiển thị mã QR có sẵn số tiền và mã đơn hàng, quét qua app ngân hàng là xong.<br>" .
                   "3. <strong>Cổng VNPAY Sandbox:</strong> Thanh toán online qua thẻ ATM nội địa, Visa, MasterCard hoặc VNPAY-QR nhanh chóng.";
        }

        // 7. Địa chỉ Cửa hàng / Showroom / Hotline
        if (preg_match('/(địa chỉ|ở đâu|cửa hàng|showroom|chi nhánh|hotline|liên hệ|số điện thoại)/u', $msg)) {
            return "Dạ bạn có thể ghé thăm và trải nghiệm mua sắm trực tiếp tại hệ thống cửa hàng Owen:<br>" .
                   "• <strong>Showroom Hà Nội:</strong> Số 1 Đại Cồ Việt, P. Bách Khoa, Q. Hai Bà Trưng, Hà Nội.<br>" .
                   "• <strong>Showroom TP.HCM:</strong> 456 Lê Lợi, P. Bến Nghé, Quận 1, TP. Hồ Chí Minh.<br>" .
                   "• <strong>Giờ mở cửa:</strong> 8:30 - 22:00 hàng ngày (kể cả Thứ 7, CN và ngày lễ).<br>" .
                   "• <strong>Hotline hỗ trợ:</strong> <strong>1900 8079</strong> (Hỗ trợ 24/7).";
        }

        // 8. Tra cứu sản phẩm theo từ khóa (Áo sơ mi, Polo, Áo thun, Quần, Giá cả, Sản phẩm mới...)
        $isShoppingQuery = preg_match('/(áo|sơ mi|polo|thun|quần|tây|jeans|kaki|sản phẩm|bán gì|giá|bao nhiêu|mua|mẫu mới|best seller|nổi bật)/u', $msg);
        
        if ($isShoppingQuery) {
            // Trích xuất từ khóa tìm kiếm
            $searchKey = '';
            if (str_contains($msg, 'sơ mi')) $searchKey = 'sơ mi';
            elseif (str_contains($msg, 'polo')) $searchKey = 'polo';
            elseif (str_contains($msg, 'thun') || str_contains($msg, 't-shirt')) $searchKey = 'thun';
            elseif (str_contains($msg, 'quần')) $searchKey = 'quần';
            else $searchKey = '';

            $query = Product::query();
            if ($searchKey) {
                $query->where('name', 'LIKE', "%{$searchKey}%");
            } else {
                $query->where('is_featured', true);
            }

            $products = $query->take(3)->get();

            if ($products->isNotEmpty()) {
                $html = "Dạ Owen gợi ý cho bạn một số mẫu thời trang nổi bật tại cửa hàng:<br><ul class='ps-3 mb-2'>";
                foreach ($products as $p) {
                    $priceFormatted = number_format($p->price, 0, ',', '.') . ' ₫';
                    $url = route('products.show', $p->id);
                    $html .= "<li class='mb-1'><a href='{$url}' class='fw-bold text-danger text-decoration-none' target='_blank'>{$p->name}</a> - Giá: <strong>{$priceFormatted}</strong></li>";
                }
                $html .= "</ul>Bạn có thể nhấp vào tên sản phẩm để xem chi tiết ảnh và chọn size nha!";
                return $html;
            }
        }

        // 9. Danh mục sản phẩm tại cửa hàng
        if (preg_match('/(danh mục|loại áo|loại quần|có những gì|kinh doanh gì)/u', $msg)) {
            $categories = Category::take(6)->get();
            $catList = $categories->pluck('name')->implode(', ');
            return "Dạ cửa hàng Owen hiện có các dòng sản phẩm thời trang cao cấp bao gồm: <strong>{$catList}</strong>. Bạn đang muốn tìm dòng sản phẩm nào để Owen tư vấn chi tiết hơn ạ?";
        }

        // 10. TỪ CHỐI KHÉO LÉO CÂU HỎI KHÔNG LIÊN QUAN
        // Nếu câu hỏi không chứa bất kỳ từ khóa nào về thời trang, quần áo, đơn hàng, cửa hàng
        return "Dạ xin lỗi bạn, mình là <strong>Owen - Trợ lý ảo thông minh</strong> chuyên hỗ trợ tư vấn <strong>sản phẩm thời trang, chọn size, đơn hàng và chính sách mua sắm tại cửa hàng Owen</strong> ạ.<br><br>" .
               "Owen chưa thể giải đáp các câu hỏi ngoài phạm vi hoạt động của cửa hàng. Nếu bạn cần tư vấn chọn đồ, kiểm tra đơn hàng hay chính sách freeship, bạn cứ nhắn cho Owen nhé! 😊";
    }
}
