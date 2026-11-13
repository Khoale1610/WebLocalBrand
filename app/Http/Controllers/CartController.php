<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Voucher;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Ngưỡng miễn phí vận chuyển (VND)
     */
    const FREE_SHIPPING_THRESHOLD = 500000;
    const DEFAULT_SHIPPING_FEE = 30000;

    /**
     * Hiển thị trang giỏ hàng
     */
    public function index()
    {
        $cart = session()->get('cart', []);

        // 1. Tính tổng tạm tính và đồng bộ tồn kho thực tế
        $subtotal = 0;
        foreach ($cart as $key => &$item) {
            $subtotal += $item['price'] * $item['quantity'];

            // Đảm bảo luôn có key 'stock' và giá trị tồn kho mới nhất
            if (!empty($item['variant_id'])) {
                $var = ProductVariant::find($item['variant_id']);
                $item['stock'] = $var ? $var->stock : 0;
            } else {
                $prod = Product::find($item['product_id']);
                $item['stock'] = $prod ? $prod->stock : 0;
            }
        }
        unset($item);
        session()->put('cart', $cart);

        // 2. Tính phí vận chuyển theo chính sách
        $shippingFee = ($subtotal >= self::FREE_SHIPPING_THRESHOLD || $subtotal == 0) ? 0 : self::DEFAULT_SHIPPING_FEE;

        // 3. Tiến trình Freeship
        $freeShippingThreshold = self::FREE_SHIPPING_THRESHOLD;
        $amountNeededForFreeShipping = max(0, $freeShippingThreshold - $subtotal);
        $freeShippingPercent = $freeShippingThreshold > 0 ? min(100, round(($subtotal / $freeShippingThreshold) * 100)) : 100;

        // 4. Voucher giảm giá
        $voucherSession = session()->get('voucher', null);
        $discountAmount = 0;
        if ($voucherSession) {
            $voucherModel = Voucher::find($voucherSession['id']);
            if ($voucherModel) {
                $check = $voucherModel->checkValidity($subtotal);
                if ($check['valid']) {
                    $discountAmount = $voucherModel->calculateDiscount($subtotal);
                    $voucherSession['discount'] = $discountAmount;
                    session()->put('voucher', $voucherSession);
                } else {
                    session()->forget('voucher');
                    $voucherSession = null;
                }
            } else {
                session()->forget('voucher');
                $voucherSession = null;
            }
        }

        $total = max(0, $subtotal + $shippingFee - $discountAmount);

        return view('pages.cart', compact(
            'cart',
            'subtotal',
            'shippingFee',
            'total',
            'freeShippingThreshold',
            'amountNeededForFreeShipping',
            'freeShippingPercent',
            'voucherSession',
            'discountAmount'
        ));
    }

    /**
     * Thêm sản phẩm hoặc biến thể vào giỏ hàng
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'nullable|exists:product_variants,id',
            'quantity'   => 'nullable|integer|min:1',
        ]);

        $productId = $request->product_id;
        $variantId = $request->variant_id;
        $quantity  = $request->input('quantity', 1);

        $product = Product::findOrFail($productId);
        $variant = null;

        if ($variantId) {
            $variant = ProductVariant::with(['size', 'color'])->findOrFail($variantId);
        }

        $price = $variant ? $variant->effective_price : $product->price;
        $stock = $variant ? $variant->stock : $product->stock;
        $cartKey = $variant ? "{$productId}_{$variantId}" : "{$productId}_single";

        $cart = session()->get('cart', []);

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $quantity;
            $cart[$cartKey]['stock'] = $stock;
        } else {
            $cart[$cartKey] = [
                'product_id' => $product->id,
                'variant_id' => $variant ? $variant->id : null,
                'name'       => $product->name,
                'price'      => $price,
                'quantity'   => $quantity,
                'stock'      => $stock,
                'image'      => $variant && $variant->image ? $variant->image_url : $product->image_url,
                'size'       => $variant && $variant->size ? $variant->size->name : null,
                'color'      => $variant && $variant->color ? $variant->color->name : null,
                'sku'        => $variant ? $variant->sku : $product->slug,
            ];
        }

        session()->put('cart', $cart);

        if ($request->input('action') === 'buy_now') {
            return redirect()->route('checkout.index');
        }

        if ($request->ajax() || $request->wantsJson()) {
            $totalQty = array_sum(array_column($cart, 'quantity'));
            return response()->json([
                'success'    => true,
                'message'    => "Đã thêm '{$product->name}' vào giỏ hàng thành công!",
                'cart_count' => $totalQty,
            ]);
        }

        return redirect()->back()->with('success', "Đã thêm '{$product->name}' vào giỏ hàng!");
    }

    /**
     * Cập nhật số lượng sản phẩm trong giỏ
     */
    public function update(Request $request)
    {
        $request->validate([
            'key'      => 'required',
            'quantity' => 'required|integer|min:1',
        ]);

        $key = $request->key;
        $qty = $request->quantity;
        $cart = session()->get('cart', []);

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] = $qty;
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Đã cập nhật giỏ hàng!');
    }

    /**
     * Xóa 1 sản phẩm khỏi giỏ hàng
     */
    public function remove(Request $request, $key)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$key])) {
            unset($cart[$key]);
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng!');
    }

    /**
     * Xóa toàn bộ giỏ hàng
     */
    public function clear()
    {
        session()->forget('cart');
        session()->forget('voucher');
        return redirect()->route('cart.index')->with('success', 'Đã xóa toàn bộ giỏ hàng!');
    }

    /**
     * Áp dụng mã Voucher giảm giá
     */
    public function applyVoucher(Request $request)
    {
        $code = strtoupper(trim($request->input('code')));
        if (!$code) {
            return redirect()->back()->with('error', 'Vui lòng nhập mã giảm giá.');
        }

        $voucher = Voucher::where('code', $code)->first();
        if (!$voucher) {
            return redirect()->back()->with('error', "Mã giảm giá '{$code}' không tồn tại hoặc không hợp lệ.");
        }

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->back()->with('error', 'Giỏ hàng đang trống, không thể áp dụng voucher.');
        }

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $check = $voucher->checkValidity($subtotal);
        if (!$check['valid']) {
            return redirect()->back()->with('error', $check['message']);
        }

        $discount = $voucher->calculateDiscount($subtotal);

        session()->put('voucher', [
            'id'       => $voucher->id,
            'code'     => $voucher->code,
            'name'     => $voucher->name,
            'discount' => $discount,
        ]);

        return redirect()->back()->with('success', "Áp dụng mã {$voucher->code} thành công! Tiết kiệm " . number_format($discount, 0, ',', '.') . " ₫.");
    }

    /**
     * Gỡ bỏ mã Voucher
     */
    public function removeVoucher()
    {
        session()->forget('voucher');
        return redirect()->back()->with('success', 'Đã gỡ bỏ mã giảm giá khỏi đơn hàng.');
    }
}
