<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
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

        // 1. Tính tổng tạm tính
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        // 2. Tính phí vận chuyển theo chính sách
        $shippingFee = ($subtotal >= self::FREE_SHIPPING_THRESHOLD || $subtotal == 0) ? 0 : self::DEFAULT_SHIPPING_FEE;
        $total = $subtotal + $shippingFee;

        // 3. Tiến trình Freeship
        $freeShippingThreshold = self::FREE_SHIPPING_THRESHOLD;
        $amountNeededForFreeShipping = max(0, $freeShippingThreshold - $subtotal);
        $freeShippingPercent = $freeShippingThreshold > 0 ? min(100, round(($subtotal / $freeShippingThreshold) * 100)) : 100;

        return view('pages.cart', compact(
            'cart',
            'subtotal',
            'shippingFee',
            'total',
            'freeShippingThreshold',
            'amountNeededForFreeShipping',
            'freeShippingPercent'
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

        $product = Product::findOrFail($request->product_id);
        $variant = null;

        if ($request->filled('variant_id')) {
            $variant = ProductVariant::with(['size', 'color'])->findOrFail($request->variant_id);
        } else {
            // Nếu không chọn biến thể cụ thể, lấy biến thể đầu tiên của sản phẩm nếu có
            $variant = $product->variants()->with(['size', 'color'])->first();
        }

        // Khóa định danh duy nhất trong giỏ hàng
        $cartKey = $variant ? 'var_' . $variant->id : 'prod_' . $product->id;
        $unitPrice = $variant ? $variant->effective_price : $product->price;
        $maxStock = $variant ? $variant->stock : $product->stock;
        $quantityToAdd = (int) $request->input('quantity', 1);

        $cart = session()->get('cart', []);
        $currentQtyInCart = isset($cart[$cartKey]) ? $cart[$cartKey]['quantity'] : 0;
        $newQuantity = $currentQtyInCart + $quantityToAdd;

        // Kiểm tra tồn kho
        if ($maxStock > 0 && $newQuantity > $maxStock) {
            $errorMsg = "Rất tiếc, số lượng trong kho chỉ còn {$maxStock} sản phẩm.";
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMsg,
                ], 422);
            }
            return redirect()->back()->with('error', $errorMsg);
        }

        // Tạo / Cập nhật phần tử trong giỏ
        $cart[$cartKey] = [
            'product_id' => $product->id,
            'variant_id' => $variant ? $variant->id : null,
            'name'       => $product->name,
            'slug'       => $product->slug,
            'image'      => ($variant && $variant->image) ? $variant->image : $product->image,
            'price'      => $unitPrice,
            'size'       => ($variant && $variant->size) ? $variant->size->name : null,
            'color'      => ($variant && $variant->color) ? $variant->color->name : null,
            'sku'        => $variant ? $variant->sku : null,
            'quantity'   => $newQuantity,
            'stock'      => $maxStock,
        ];

        session()->put('cart', $cart);

        $totalItemsCount = array_sum(array_column($cart, 'quantity'));
        $successMsg = 'Đã thêm sản phẩm "' . $product->name . '" vào giỏ hàng!';

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success'    => true,
                'message'    => $successMsg,
                'cart_count' => $totalItemsCount,
                'cart_item'  => $cart[$cartKey],
            ]);
        }

        return redirect()->back()->with('success', $successMsg);
    }

    /**
     * Cập nhật số lượng của một sản phẩm trong giỏ hàng
     */
    public function update(Request $request)
    {
        $request->validate([
            'key'      => 'required|string',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = session()->get('cart', []);
        $key = $request->key;
        $quantity = (int) $request->quantity;

        if (!isset($cart[$key])) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Sản phẩm không tồn tại trong giỏ hàng.'], 404);
            }
            return redirect()->route('cart.index')->with('error', 'Sản phẩm không tồn tại trong giỏ hàng.');
        }

        // Kiểm tra tồn kho
        $stock = $cart[$key]['stock'];
        if ($stock > 0 && $quantity > $stock) {
            $errorMsg = "Chỉ còn tối đa {$stock} sản phẩm trong kho.";
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $errorMsg], 422);
            }
            return redirect()->route('cart.index')->with('error', $errorMsg);
        }

        $cart[$key]['quantity'] = $quantity;
        session()->put('cart', $cart);

        // Tính lại tổng tiền
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        $shippingFee = ($subtotal >= self::FREE_SHIPPING_THRESHOLD || $subtotal == 0) ? 0 : self::DEFAULT_SHIPPING_FEE;
        $total = $subtotal + $shippingFee;
        $itemTotal = $cart[$key]['price'] * $quantity;
        $totalItemsCount = array_sum(array_column($cart, 'quantity'));

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success'          => true,
                'message'          => 'Đã cập nhật giỏ hàng thành công!',
                'cart_count'       => $totalItemsCount,
                'item_total'       => number_format($itemTotal, 0, ',', '.') . ' ₫',
                'subtotal'         => number_format($subtotal, 0, ',', '.') . ' ₫',
                'shipping_fee'     => $shippingFee == 0 ? 'Miễn phí' : number_format($shippingFee, 0, ',', '.') . ' ₫',
                'total'            => number_format($total, 0, ',', '.') . ' ₫',
                'is_freeship'      => $shippingFee === 0,
                'amount_needed'    => max(0, self::FREE_SHIPPING_THRESHOLD - $subtotal),
                'freeship_percent' => min(100, round(($subtotal / self::FREE_SHIPPING_THRESHOLD) * 100)),
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Đã cập nhật số lượng thành công!');
    }

    /**
     * Xóa 1 sản phẩm khỏi giỏ hàng
     */
    public function remove(Request $request, $key)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$key])) {
            $name = $cart[$key]['name'];
            unset($cart[$key]);
            session()->put('cart', $cart);

            $msg = 'Đã xóa sản phẩm "' . $name . '" khỏi giỏ hàng.';
        } else {
            $msg = 'Sản phẩm không có trong giỏ hàng.';
        }

        if ($request->ajax() || $request->wantsJson()) {
            $subtotal = 0;
            foreach ($cart as $item) {
                $subtotal += $item['price'] * $item['quantity'];
            }
            $shippingFee = ($subtotal >= self::FREE_SHIPPING_THRESHOLD || $subtotal == 0) ? 0 : self::DEFAULT_SHIPPING_FEE;
            $total = $subtotal + $shippingFee;

            return response()->json([
                'success'          => true,
                'message'          => $msg,
                'cart_count'       => array_sum(array_column($cart, 'quantity')),
                'cart_is_empty'    => empty($cart),
                'subtotal'         => number_format($subtotal, 0, ',', '.') . ' ₫',
                'shipping_fee'     => $shippingFee == 0 ? 'Miễn phí' : number_format($shippingFee, 0, ',', '.') . ' ₫',
                'total'            => number_format($total, 0, ',', '.') . ' ₫',
                'is_freeship'      => $shippingFee === 0,
                'amount_needed'    => max(0, self::FREE_SHIPPING_THRESHOLD - $subtotal),
                'freeship_percent' => min(100, round(($subtotal / self::FREE_SHIPPING_THRESHOLD) * 100)),
            ]);
        }

        return redirect()->route('cart.index')->with('success', $msg);
    }

    /**
     * Xóa toàn bộ giỏ hàng
     */
    public function clear()
    {
        session()->forget('cart');
        return redirect()->route('cart.index')->with('success', 'Đã xóa toàn bộ giỏ hàng!');
    }
}
