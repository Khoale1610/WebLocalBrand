<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Voucher;
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
                'reply'   => 'Dạ bạn có thể đặt câu hỏi để Owen hỗ trợ tư vấn sản phẩm theo tầm giá, chọn size, mã giảm giá hoặc kiểm tra đơn hàng nhé!',
            ]);
        }

        $reply = $this->generateBotReply($message);

        return response()->json([
            'success' => true,
            'reply'   => $reply,
        ]);
    }

    /**
     * Động cơ xử lý ngôn ngữ tự nhiên & Tri thức cửa hàng OWEN STUDIO
     */
    protected function generateBotReply(string $rawMessage): string
    {
        $msg = mb_strtolower(trim($rawMessage), 'UTF-8');

        // 1. Tra cứu mã đơn hàng trực tiếp (ưu tiên cao nhất nếu có mã ORD-...)
        if (preg_match('/(ord-[a-z0-9\-]+)/ui', $rawMessage, $matches)) {
            return $this->handleOrderLookup(strtoupper($matches[1]));
        }

        // 2. Chào hỏi thuần túy (chỉ khi câu ngắn chỉ là lời chào)
        if (preg_match('/^(chào|hi|hello|alo|hey|shop ơi|admin ơi|chào shop|xin chào|owen ơi|chào owen)[\s!?.]*$/u', $msg)) {
            return "Dạ Owen xin chào bạn! Mình là trợ lý ảo của <strong>OWEN STUDIO</strong>.<br>" .
                   "Bạn có thể hỏi mình tự nhiên như:<br>" .
                   "• <em>\"Tư vấn áo rẻ dưới 300k\"</em> hoặc <em>\"Tìm quần jeans đen\"</em><br>" .
                   "• <em>\"Mình cao 1m72 nặng 65kg mặc size gì?\"</em><br>" .
                   "• <em>\"Có mã giảm giá nào không?\"</em> hoặc <em>\"Kiểm tra đơn ORD-...\"</em>";
        }

        // 3. Tư vấn chọn Size (hỗ trợ cả chiều cao 1m72 / 172cm và cân nặng 65kg)
        if (preg_match('/(chiều cao|cân nặng|nặng|kg|\bm[0-9]{2}\b|[0-9]{3}\s*cm|chọn size|bảng size|mặc size|size gì|kích cỡ|mặc vừa)/u', $msg)) {
            return $this->handleSizeAdvice($msg);
        }

        // 4. Hỏi về Voucher / Mã giảm giá / Chương trình Sale
        if (preg_match('/(voucher|mã giảm giá|mã giảm|khuyến mãi|ưu đãi|giảm giá|mã freeship|coupon|sale)/u', $msg)
            && !preg_match('/(áo|quần|sơ mi|polo|thun|khoác|hoodie|vest|blazer|jeans|kaki|short|ví|tất|thắt lưng|nón)/u', $msg)) {
            return $this->handleVoucherInquiry();
        }

        // 5. Chính sách Đổi trả & Bảo hành
        if (preg_match('/(đổi trả|bảo hành|trả hàng|đổi size|bị lỗi|đổi hàng|hoàn tiền)/u', $msg)) {
            return "Dạ về <strong>Chính sách đổi trả</strong>, Owen hỗ trợ rất chu đáo:<br>" .
                   "• Hỗ trợ <strong>đổi hàng trong vòng 30 ngày</strong> kể từ ngày nhận hàng.<br>" .
                   "• <strong>Miễn phí đổi size/màu</strong> nếu bạn mặc chưa vừa hoặc miễn phí 100% ship 2 chiều nếu có lỗi sản xuất.<br>" .
                   "• <em>Điều kiện:</em> Sản phẩm còn nguyên tem mác, chưa qua giặt ủi hay sử dụng.<br>" .
                   "Bạn cần đổi đơn nào có thể gọi hotline <strong>1900 8079</strong> để được xử lý ngay ạ!";
        }

        // 6. Chính sách Giao hàng & Vận chuyển (Freeship)
        if (preg_match('/(phí ship|vận chuyển|giao hàng|bao lâu nhận|freeship|miễn phí ship|ship cod|tiền ship)/u', $msg)) {
            return "Dạ về <strong>Chính sách giao hàng</strong> của Owen:<br>" .
                   "• <strong>MIỄN PHÍ VẬN CHUYỂN (Freeship)</strong> toàn quốc cho đơn hàng từ <strong>500.000₫</strong> trở lên.<br>" .
                   "• Đơn dưới 500.000₫ áp dụng phí ship đồng giá chỉ <strong>30.000₫</strong>.<br>" .
                   "• Thời gian giao: Nội thành 1–2 ngày, các tỉnh thành khác 2–4 ngày làm việc ạ.";
        }

        // 7. Phương thức Thanh toán
        if (preg_match('/(thanh toán|chuyển khoản|vietqr|vnpay|ngân hàng|trả tiền|cod|quét mã)/u', $msg)) {
            return "Dạ Owen hỗ trợ 3 phương thức thanh toán an toàn & tiện lợi:<br>" .
                   "1. <strong>COD:</strong> Thanh toán tiền mặt khi nhận hàng.<br>" .
                   "2. <strong>VietQR tự động:</strong> Quét mã QR ngân hàng kèm sẵn số tiền & mã đơn.<br>" .
                   "3. <strong>VNPAY:</strong> Thanh toán qua thẻ ATM nội địa, Visa/MasterCard hoặc VNPAY-QR.";
        }

        // 8. Địa chỉ Cửa hàng / Showroom / Hotline
        if (preg_match('/(địa chỉ|ở đâu|cửa hàng|showroom|chi nhánh|hotline|liên hệ|số điện thoại|giờ mở cửa)/u', $msg)) {
            return "Dạ bạn có thể ghé thăm trực tiếp showroom của <strong>OWEN STUDIO</strong>:<br>" .
                   "• <strong>Showroom Hà Nội:</strong> Tầng 17, Tòa nhà Viwaseen, 48 Tố Hữu, Nam Từ Liêm, Hà Nội.<br>" .
                   "• <strong>Giờ mở cửa:</strong> 8:30 – 22:00 hàng ngày (kể cả Thứ 7, CN).<br>" .
                   "• <strong>Hotline 24/7:</strong> <strong>1900 8079</strong>.<br>" .
                   "Bạn cũng có thể xem bản đồ chỉ đường ngay ở phần chân trang (Footer) ạ!";
        }

        // 9. Tư vấn & Tìm kiếm sản phẩm thông minh theo Loại + Khoảng giá + Màu sắc + Xu hướng
        $productReply = $this->handleSmartProductSearch($msg);
        if ($productReply !== null) {
            return $productReply;
        }

        // 10. Danh mục sản phẩm tại cửa hàng
        if (preg_match('/(danh mục|có những gì|kinh doanh gì|bán những gì|bán gì)/u', $msg)) {
            $categories = Category::withCount('products')->get();
            $catList = $categories->map(fn($c) => "{$c->name} ({$c->products_count})")->implode(', ');
            return "Dạ OWEN STUDIO hiện có đầy đủ các dòng thời trang nam: <strong>{$catList}</strong>.<br>" .
                   "Bạn đang muốn tìm sản phẩm nào hoặc tầm giá bao nhiêu để Owen lọc mẫu phù hợp nhất cho bạn ạ?";
        }

        // 11. Trả lời mặc định lịch sự kèm gợi ý
        return "Dạ mình là <strong>Owen - Trợ lý ảo thời trang</strong>. Mình chưa hiểu rõ ý bạn lắm, bạn có thể thử hỏi cụ thể hơn như:<br>" .
               "• <em>\"Tìm áo thun dưới 300k\"</em> hoặc <em>\"Áo khoác màu đen\"</em><br>" .
               "• <em>\"Cao 1m75 nặng 70kg mặc size gì?\"</em><br>" .
               "• <em>\"Có mã giảm giá nào không?\"</em><br>" .
               "• <em>\"Kiểm tra đơn hàng ORD-...\"</em>";
    }

    /**
     * Tra cứu đơn hàng theo mã
     */
    private function handleOrderLookup(string $orderCode): string
    {
        $order = Order::with('items')->where('order_code', $orderCode)->first();

        if (!$order) {
            return "Dạ Owen đã kiểm tra mã đơn <strong>#{$orderCode}</strong> nhưng chưa thấy trên hệ thống. Bạn vui lòng kiểm tra lại mã đơn trong mục <em>Lịch sử đơn hàng</em> hoặc gọi hotline <strong>1900 8079</strong> nhé!";
        }

        $statusBadge = $order->order_status_label;
        $paymentStatus = $order->payment_status === 'paid' ? 'Đã thanh toán' : 'Chưa thanh toán';
        $totalFormatted = number_format($order->total_amount, 0, ',', '.') . '₫';

        return "Dạ thông tin đơn hàng <strong>#{$order->order_code}</strong> của bạn:<br>" .
               "• <strong>Người nhận:</strong> {$order->customer_name} ({$order->customer_phone})<br>" .
               "• <strong>Trạng thái:</strong> <span class='text-primary fw-bold'>{$statusBadge}</span><br>" .
               "• <strong>Thanh toán:</strong> {$paymentStatus} ({$order->payment_method})<br>" .
               "• <strong>Tổng tiền:</strong> {$totalFormatted} ({$order->items->count()} sản phẩm)<br>" .
               "Đơn hàng đang được xử lý nhanh nhất có thể ạ!";
    }

    /**
     * Tư vấn chọn Size thông minh theo cả Chiều cao & Cân nặng
     */
    private function handleSizeAdvice(string $msg): string
    {
        // Bắt cân nặng (vd: 65kg, 65 kg)
        preg_match('/(\d{2,3})\s*kg/u', $msg, $weightMatch);
        $weight = isset($weightMatch[1]) ? (int) $weightMatch[1] : 0;

        // Bắt chiều cao (vd: 1m72, 1m7, 1.72m, 172cm)
        $height = 0;
        if (preg_match('/1[\.,]?\s*m\s*(\d{1,2})/u', $msg, $hMatch) || preg_match('/1\.(\d{2})\s*m?/u', $msg, $hMatch)) {
            $cmPart = $hMatch[1];
            if (strlen($cmPart) === 1) {
                $cmPart .= '0';
            }
            $height = 100 + (int) $cmPart;
        } elseif (preg_match('/(\d{3})\s*cm/u', $msg, $hMatch)) {
            $height = (int) $hMatch[1];
        }

        if ($weight > 0 || $height > 0) {
            $sizeByWeight = null;
            if ($weight > 0) {
                if ($weight <= 55) $sizeByWeight = 1; // S
                elseif ($weight <= 65) $sizeByWeight = 2; // M
                elseif ($weight <= 73) $sizeByWeight = 3; // L
                elseif ($weight <= 82) $sizeByWeight = 4; // XL
                else $sizeByWeight = 5; // XXL
            }

            $sizeByHeight = null;
            if ($height > 0) {
                if ($height <= 166) $sizeByHeight = 1; // S
                elseif ($height <= 172) $sizeByHeight = 2; // M
                elseif ($height <= 178) $sizeByHeight = 3; // L
                elseif ($height <= 184) $sizeByHeight = 4; // XL
                else $sizeByHeight = 5; // XXL
            }

            $map = [1 => 'S', 2 => 'M', 3 => 'L', 4 => 'XL', 5 => 'XXL'];
            $finalIdx = max($sizeByWeight ?? 1, $sizeByHeight ?? 1);
            $suggested = $map[$finalIdx];

            $infoParts = [];
            if ($height > 0) $infoParts[] = "cao <strong>{$height}cm</strong>";
            if ($weight > 0) $infoParts[] = "nặng <strong>{$weight}kg</strong>";
            $infoStr = implode(' và ', $infoParts);

            return "Dạ với thông số {$infoStr}, Owen khuyên bạn chọn <strong>Size {$suggested}</strong> để mặc vừa vặn, tôn dáng nhất ạ!<br>" .
                   "• Nếu bạn thích mặc <em>form rộng thoải mái / Oversize</em> hoặc có bụng nhẹ, có thể nhích lên <strong>1 size</strong> nhé.<br>" .
                   "• Owen hỗ trợ đổi size miễn phí trong 30 ngày nếu mặc chưa vừa ạ!";
        }

        return "Dạ đây là <strong>Bảng size áo chuẩn của OWEN STUDIO</strong>:<br>" .
               "• <strong>Size S:</strong> 1m60 – 1m66 | 52 – 58kg<br>" .
               "• <strong>Size M:</strong> 1m67 – 1m72 | 59 – 66kg<br>" .
               "• <strong>Size L:</strong> 1m73 – 1m78 | 67 – 74kg<br>" .
               "• <strong>Size XL:</strong> 1m79 – 1m84 | 75 – 82kg<br>" .
               "• <strong>Size XXL:</strong> 1m85 – 1m90 | 83 – 90kg<br>" .
               "Bạn nhắn cho Owen chiều cao và cân nặng (ví dụ: <em>\"1m72 65kg\"</em>) để mình chọn size chính xác nhất cho bạn nha!";
    }

    /**
     * Truy vấn các mã Voucher đang hoạt động từ Database
     */
    private function handleVoucherInquiry(): string
    {
        $vouchers = Voucher::where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', now());
            })
            ->take(4)
            ->get();

        if ($vouchers->isEmpty()) {
            return "Dạ hiện tại chương trình <strong>Archive Sale Up To 50%</strong> đang mở bán trực tiếp với rất nhiều mẫu giá ưu đãi. Bạn có thể xem ngay tại mục <a href='" . route('collections.sale') . "' class='fw-bold text-danger'>Sale Up To 50%</a> ạ!";
        }

        $html = "Dạ OWEN STUDIO đang có các <strong>Mã Giảm Giá (Voucher)</strong> áp dụng ngay tại Giỏ Hàng:<br><ul class='ps-3 mb-2'>";
        foreach ($vouchers as $v) {
            $html .= "<li class='mb-1'><strong><code>{$v->code}</code></strong>: {$v->name}</li>";
        }
        $html .= "</ul>Ngoài ra bạn có thể săn thêm các mẫu giảm giá sâu tại <a href='" . route('collections.sale') . "' class='fw-bold text-danger'>Archive Sale Up To 50%</a> nhé!";

        return $html;
    }

    /**
     * Tìm kiếm & Tư vấn sản phẩm thông minh theo Loại + Giá + Màu sắc + Độ phù hợp
     */
    private function handleSmartProductSearch(string $msg): ?string
    {
        $priceInfo = $this->parsePriceConditions($msg);
        $categoryFilter = $this->parseCategoryIntent($msg);
        $colorFilter = $this->parseColorIntent($msg);

        $hasShoppingIntent = preg_match(
            '/(tư vấn|tìm|gợi ý|mua|xem|có mẫu|mẫu nào|sản phẩm|giá|bao nhiêu|rẻ|đắt|cao cấp|bán chạy|hot|mới|đẹp|dưới|trên|tầm|khoảng)/u',
            $msg
        );

        if (!$hasShoppingIntent && !$categoryFilter['matched'] && !$priceInfo['has_price'] && !$colorFilter) {
            return null;
        }

        $query = Product::with('category')->where('stock', '>', 0);

        // 1. Lọc theo danh mục/loại sản phẩm nhận diện được
        if (!empty($categoryFilter['category_ids'])) {
            $query->whereIn('category_id', $categoryFilter['category_ids']);
        } elseif (!empty($categoryFilter['keywords'])) {
            $keywords = $categoryFilter['keywords'];
            $query->where(function ($q) use ($keywords) {
                foreach ($keywords as $kw) {
                    $q->orWhere('name', 'LIKE', "%{$kw}%")
                      ->orWhereHas('category', fn($cq) => $cq->where('name', 'LIKE', "%{$kw}%"));
                }
            });
        }

        // 2. Lọc theo màu sắc nếu khách nhắc tới màu
        if ($colorFilter) {
            $query->where(function ($q) use ($colorFilter) {
                $q->where('name', 'LIKE', "%{$colorFilter}%")
                  ->orWhereHas('variants.color', fn($cq) => $cq->where('name', 'LIKE', "%{$colorFilter}%"));
            });
        }

        // 3. Lọc theo khoảng giá khách yêu cầu (vd: "dưới 300k", "300k đến 500k", "trên 1 triệu")
        if ($priceInfo['min'] !== null) {
            $query->where('price', '>=', $priceInfo['min']);
        }
        if ($priceInfo['max'] !== null) {
            $query->where('price', '<=', $priceInfo['max']);
        }

        // 4. Sắp xếp theo ý định ("rẻ" -> giá tăng dần, "cao cấp/đắt" -> giá giảm dần)
        switch ($priceInfo['sort']) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            default:
                $query->orderByDesc('is_featured')->latest('id');
                break;
        }

        $totalMatched = (clone $query)->count();
        $products = $query->take(4)->get();

        // Trường hợp không có sản phẩm đúng tầm giá quá thấp/khắt khe -> gợi ý các mẫu gần nhất cùng loại
        if ($products->isEmpty()) {
            $fallbackQuery = Product::with('category')->where('stock', '>', 0);
            if (!empty($categoryFilter['category_ids'])) {
                $fallbackQuery->whereIn('category_id', $categoryFilter['category_ids']);
            }
            $nearest = $fallbackQuery->orderBy('price', 'asc')->take(3)->get();

            if ($nearest->isEmpty()) {
                return "Dạ hiện tại Owen chưa tìm thấy sản phẩm khớp chính xác với yêu cầu này. Bạn thử đổi tầm giá hoặc xem toàn bộ bộ sưu tập tại <a href='" . route('products.search') . "' class='fw-bold text-danger'>Cửa hàng</a> nhé!";
            }

            $label = $categoryFilter['label'] ?: 'sản phẩm';
            $rangeText = $this->formatPriceRangeText($priceInfo);
            $html = "Dạ hiện tại Owen chưa có mẫu <strong>{$label}</strong> nào {$rangeText}.<br>" .
                    "Tuy nhiên, đây là những mẫu <strong>{$label} giá tốt nhất</strong> gần tầm giá bạn tìm ạ:<br><ul class='ps-3 mb-2'>";
            foreach ($nearest as $p) {
                $priceFormatted = number_format($p->price, 0, ',', '.') . '₫';
                $url = route('products.show', $p->id);
                $html .= "<li class='mb-1'><a href='{$url}' class='fw-bold text-danger text-decoration-none'>{$p->name}</a> – <strong>{$priceFormatted}</strong></li>";
            }
            $html .= "</ul>Bạn nhấp vào tên sản phẩm để xem ảnh và chọn size/màu nhé!";
            return $html;
        }

        // Có sản phẩm khớp chính xác
        $label = $categoryFilter['label'] ?: 'sản phẩm';
        $criteriaDesc = [];
        if ($colorFilter) $criteriaDesc[] = "màu <strong>{$colorFilter}</strong>";
        if ($priceInfo['min'] !== null || $priceInfo['max'] !== null) {
            $criteriaDesc[] = $this->formatPriceRangeText($priceInfo);
        } elseif ($priceInfo['sort'] === 'price_asc') {
            $criteriaDesc[] = "<strong>giá tốt nhất</strong>";
        }
        $criteriaText = !empty($criteriaDesc) ? ' (' . implode(', ', $criteriaDesc) . ')' : '';

        $html = "Dạ Owen tìm thấy <strong>{$totalMatched}</strong> mẫu <strong>{$label}</strong> phù hợp{$criteriaText}. Gợi ý nổi bật cho bạn:<br><ul class='ps-3 mb-2'>";
        foreach ($products as $p) {
            $priceFormatted = number_format($p->price, 0, ',', '.') . '₫';
            $url = route('products.show', $p->id);
            $html .= "<li class='mb-1'><a href='{$url}' class='fw-bold text-danger text-decoration-none'>{$p->name}</a> – <strong>{$priceFormatted}</strong></li>";
        }
        $html .= "</ul>";

        if ($totalMatched > 4) {
            $searchParams = array_filter([
                'query'     => $categoryFilter['search_query'] ?? '',
                'min_price' => $priceInfo['min'],
                'max_price' => $priceInfo['max'],
                'sort'      => $priceInfo['sort'] ?: 'newest',
            ]);
            $moreUrl = route('products.search', $searchParams);
            $html .= "<a href='{$moreUrl}' class='fw-bold text-dark text-decoration-underline small'>Xem tất cả {$totalMatched} sản phẩm phù hợp &rarr;</a>";
        } else {
            $html .= "Bạn nhấp vào tên sản phẩm để xem chi tiết, đổi màu và chọn size nha!";
        }

        return $html;
    }

    /**
     * Phân tích khoảng giá và xu hướng sắp xếp từ câu hỏi tiếng Việt
     * Hỗ trợ: "dưới 300k", "trên 500k", "từ 300k đến 500k", "tầm 400k", "1 triệu", "rẻ"...
     */
    private function parsePriceConditions(string $msg): array
    {
        $min = null;
        $max = null;
        $sort = '';

        $toVnd = function (string $numStr, ?string $unit): int {
            $clean = str_replace([',', '.'], '', $numStr);
            $val = (float) $clean;
            $unit = mb_strtolower((string) $unit, 'UTF-8');

            if (in_array($unit, ['tr', 'triệu', 'm'], true)) {
                return (int) ($val * 1000000);
            }
            if (in_array($unit, ['k', 'ngàn', 'nghìn'], true)) {
                return (int) ($val * 1000);
            }
            // Nếu số nhỏ hơn 2000 mà không ghi đơn vị (vd: "dưới 300") -> hiểu là nghìn đồng
            if ($val > 0 && $val < 2000) {
                return (int) ($val * 1000);
            }
            return (int) $val;
        };

        // 1. Khoảng từ X đến Y (vd: "từ 300k đến 500k", "300k - 500k")
        if (preg_match('/(?:từ\s*)?(\d+(?:[.,]\d+)?)\s*(k|nghìn|ngàn|triệu|tr|đ|vnd)?\s*(?:đến|tới|\-)\s*(\d+(?:[.,]\d+)?)\s*(k|nghìn|ngàn|triệu|tr|đ|vnd)?/u', $msg, $m)) {
            $unit1 = $m[2] ?: ($m[4] ?? '');
            $min = $toVnd($m[1], $unit1);
            $max = $toVnd($m[3], $m[4] ?? $unit1);
        }
        // 2. Dưới / tối đa / không quá / <= X
        elseif (preg_match('/(?:dưới|nhỏ hơn|ít hơn|tối đa|không quá|<=|<)\s*(\d+(?:[.,]\d+)?)\s*(k|nghìn|ngàn|triệu|tr|đ|vnd)?/u', $msg, $m)) {
            $max = $toVnd($m[1], $m[2] ?? '');
            $sort = 'price_asc';
        }
        // 3. Trên / từ / hơn / >= X
        elseif (preg_match('/(?:trên|lớn hơn|hơn|từ|tối thiểu|>=|>)\s*(\d+(?:[.,]\d+)?)\s*(k|nghìn|ngàn|triệu|tr|đ|vnd)?/u', $msg, $m)) {
            $min = $toVnd($m[1], $m[2] ?? '');
            $sort = 'price_asc';
        }
        // 4. Tầm / khoảng X (±20%)
        elseif (preg_match('/(?:tầm|khoảng|cỡ|giá)\s*(\d+(?:[.,]\d+)?)\s*(k|nghìn|ngàn|triệu|tr|đ|vnd)/u', $msg, $m)) {
            $target = $toVnd($m[1], $m[2]);
            $min = (int) ($target * 0.75);
            $max = (int) ($target * 1.25);
        }

        // Xu hướng sắp xếp từ từ khóa tính chất giá
        if (preg_match('/(rẻ|giá tốt|bình dân|tiết kiệm|thấp nhất|sinh viên)/u', $msg)) {
            $sort = 'price_asc';
        } elseif (preg_match('/(đắt|cao cấp|sang|xịn|cao nhất)/u', $msg)) {
            $sort = 'price_desc';
        }

        return [
            'min'       => $min,
            'max'       => $max,
            'sort'      => $sort,
            'has_price' => ($min !== null || $max !== null),
        ];
    }

    /**
     * Nhận diện loại sản phẩm / danh mục chính xác
     */
    private function parseCategoryIntent(string $msg): array
    {
        $rules = [
            ['pattern' => '/(sơ mi|shirt)/u',              'ids' => [1],             'label' => 'Áo Sơ Mi',         'q' => 'áo sơ mi'],
            ['pattern' => '/\bpolo\b/u',                   'ids' => [2],             'label' => 'Áo Polo',          'q' => 'polo'],
            ['pattern' => '/(áo thun|t-shirt|áo phông|\bthun\b)/u', 'ids' => [3],   'label' => 'Áo Thun',          'q' => 'áo thun'],
            ['pattern' => '/(áo khoác|hoodie|bomber|khoác)/u',      'ids' => [4],   'label' => 'Áo Khoác & Hoodie','q' => 'áo khoác'],
            ['pattern' => '/(vest|blazer)/u',              'ids' => [5],             'label' => 'Áo Vest & Blazer', 'q' => 'blazer'],
            ['pattern' => '/(quần tây|quần âu)/u',         'ids' => [6],             'label' => 'Quần Tây / Âu',    'q' => 'quần tây'],
            ['pattern' => '/(kaki|khaki)/u',               'ids' => [7],             'label' => 'Quần Khaki',       'q' => 'kaki'],
            ['pattern' => '/(jeans?|quần bò|denim)/u',     'ids' => [8],             'label' => 'Quần Jeans',       'q' => 'jeans'],
            ['pattern' => '/(short|quần đùi)/u',           'ids' => [9],             'label' => 'Quần Short',       'q' => 'short'],
            ['pattern' => '/(thắt lưng|dây nịt)/u',        'ids' => [10],            'label' => 'Thắt Lưng',        'q' => 'thắt lưng'],
            ['pattern' => '/(cà vạt|ví da|\bví\b|clutch)/u','ids' => [11],           'label' => 'Cà Vạt & Ví Da',   'q' => 'ví'],
            ['pattern' => '/(tất|\bvớ\b|nón|mũ|phụ kiện)/u','ids' => [12],          'label' => 'Tất & Phụ Kiện',   'q' => ''],
            // Nhóm tổng quát (đặt sau các nhóm chi tiết)
            ['pattern' => '/(?<!\p{L})áo(?!\p{L})/u',     'ids' => [1, 2, 3, 4, 5], 'label' => 'Áo',               'q' => 'áo'],
            ['pattern' => '/(?<!\p{L})quần(?!\p{L})/u',   'ids' => [6, 7, 8, 9],    'label' => 'Quần',             'q' => 'quần'],
        ];

        foreach ($rules as $r) {
            if (preg_match($r['pattern'], $msg)) {
                return [
                    'matched'      => true,
                    'category_ids' => $r['ids'],
                    'keywords'     => [],
                    'label'        => $r['label'],
                    'search_query' => $r['q'],
                ];
            }
        }

        return [
            'matched'      => false,
            'category_ids' => [],
            'keywords'     => [],
            'label'        => '',
            'search_query' => '',
        ];
    }

    /**
     * Nhận diện màu sắc trong câu hỏi
     */
    private function parseColorIntent(string $msg): ?string
    {
        $colors = ['đen', 'trắng', 'xanh navy', 'xanh', 'xám', 'be', 'nâu', 'rêu', 'đỏ'];
        foreach ($colors as $c) {
            if (preg_match('/(?<!\p{L})' . preg_quote($c, '/') . '(?!\p{L})/u', $msg)) {
                return $c;
            }
        }
        return null;
    }

    private function formatPriceRangeText(array $priceInfo): string
    {
        if ($priceInfo['min'] !== null && $priceInfo['max'] !== null) {
            return 'từ <strong>' . number_format($priceInfo['min'], 0, ',', '.') . '₫</strong> đến <strong>' . number_format($priceInfo['max'], 0, ',', '.') . '₫</strong>';
        }
        if ($priceInfo['max'] !== null) {
            return 'dưới <strong>' . number_format($priceInfo['max'], 0, ',', '.') . '₫</strong>';
        }
        if ($priceInfo['min'] !== null) {
            return 'từ <strong>' . number_format($priceInfo['min'], 0, ',', '.') . '₫</strong> trở lên';
        }
        return '';
    }
}
