<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Danh sách đơn hàng & Bộ lọc
     */
    public function index(Request $request)
    {
        $query = Order::with(['items.product', 'items.variant', 'user']);

        // 1. Lọc theo trạng thái đơn hàng (Tab)
        if ($status = $request->input('status')) {
            if (in_array($status, [
                Order::STATUS_PENDING,
                Order::STATUS_PROCESSING,
                Order::STATUS_SHIPPING,
                Order::STATUS_COMPLETED,
                Order::STATUS_CANCELLED
            ])) {
                $query->where('order_status', $status);
            }
        }

        // 2. Tìm kiếm theo mã đơn, tên, sđt, email
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('order_code', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%");
            });
        }

        // 3. Lọc theo phương thức thanh toán
        if ($paymentMethod = $request->input('payment_method')) {
            $query->where('payment_method', $paymentMethod);
        }

        // 4. Lọc theo trạng thái thanh toán
        if ($paymentStatus = $request->input('payment_status')) {
            $query->where('payment_status', $paymentStatus);
        }

        // 5. Lọc theo khoảng ngày đặt
        if ($fromDate = $request->input('from_date')) {
            $query->whereDate('created_at', '>=', $fromDate);
        }
        if ($toDate = $request->input('to_date')) {
            $query->whereDate('created_at', '<=', $toDate);
        }

        $orders = $query->latestFirst()->paginate(12)->withQueryString();

        // Đếm số lượng đơn theo từng trạng thái để hiển thị Badge trên Tabs
        $statusCounts = [
            'all'        => Order::count(),
            'pending'    => Order::where('order_status', Order::STATUS_PENDING)->count(),
            'processing' => Order::where('order_status', Order::STATUS_PROCESSING)->count(),
            'shipping'   => Order::where('order_status', Order::STATUS_SHIPPING)->count(),
            'completed'  => Order::where('order_status', Order::STATUS_COMPLETED)->count(),
            'cancelled'  => Order::where('order_status', Order::STATUS_CANCELLED)->count(),
        ];

        return view('admin.orders.index', compact('orders', 'statusCounts'));
    }

    /**
     * Chi tiết đơn hàng
     */
    public function show(Order $order)
    {
        $order->load(['items.product', 'items.variant.size', 'items.variant.color', 'user']);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Cập nhật trạng thái đơn hàng & Xử lý Hoàn trả kho khi Huỷ
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'order_status'   => 'required|in:pending,processing,shipping,completed,cancelled',
            'payment_status' => 'required|in:unpaid,paid,failed,refunded',
            'customer_notes' => 'nullable|string',
        ]);

        $oldStatus = $order->order_status;
        $newStatus = $request->order_status;
        $oldPaymentStatus = $order->payment_status;
        $newPaymentStatus = $request->payment_status;

        DB::beginTransaction();
        try {
            // ============================================================
            // XỬ LÝ HOÀN TRẢ KHO & TRỪ KHO THEO TRẠNG THÁI
            // ============================================================

            // Trường hợp 1: Chuyển từ trạng thái hoạt động SANG ĐÃ HỦY (Cancelled)
            // => TỰ ĐỘNG HOÀN TRẢ LẠI SỐ LƯỢNG HÀNG VÀO KHO
            if ($oldStatus !== Order::STATUS_CANCELLED && $newStatus === Order::STATUS_CANCELLED) {
                foreach ($order->items as $item) {
                    if ($item->product_variant_id) {
                        ProductVariant::where('id', $item->product_variant_id)->increment('stock', $item->quantity);
                    }
                    if ($item->product_id) {
                        Product::where('id', $item->product_id)->increment('stock', $item->quantity);
                    }
                }

                // Nếu đơn hàng đã thanh toán trước (online) mà bị hủy -> có thể tự động đổi sang Đã hoàn tiền nếu muốn
                if ($order->payment_status === Order::PAYMENT_PAID && $newPaymentStatus === Order::PAYMENT_PAID) {
                    $newPaymentStatus = Order::PAYMENT_REFUNDED;
                }
            }

            // Trường hợp 2: Khôi phục đơn hàng từ ĐÃ HỦY (Cancelled) QUAY LẠI Hoạt động
            // => PHẢI TRỪ LẠI KHO ĐỂ TRÁNH DƯ THỪA TỒN KHO ẢO
            elseif ($oldStatus === Order::STATUS_CANCELLED && $newStatus !== Order::STATUS_CANCELLED) {
                foreach ($order->items as $item) {
                    if ($item->product_variant_id) {
                        ProductVariant::where('id', $item->product_variant_id)->decrement('stock', $item->quantity);
                    }
                    if ($item->product_id) {
                        Product::where('id', $item->product_id)->decrement('stock', $item->quantity);
                    }
                }
            }

            // Cập nhật thông tin đơn hàng
            $order->order_status   = $newStatus;
            $order->payment_status = $newPaymentStatus;
            if ($request->has('customer_notes')) {
                $order->customer_notes = $request->customer_notes;
            }
            $order->save();

            DB::commit();

            $statusText = $order->order_status_label;
            $msg = "Đã cập nhật trạng thái đơn hàng #{$order->order_code} thành: '{$statusText}'!";
            if ($oldStatus !== Order::STATUS_CANCELLED && $newStatus === Order::STATUS_CANCELLED) {
                $msg .= " (Toàn bộ số lượng sản phẩm đã được tự động hoàn trả lại kho hàng).";
            } elseif ($oldStatus === Order::STATUS_CANCELLED && $newStatus !== Order::STATUS_CANCELLED) {
                $msg .= " (Hệ thống đã trừ lại số lượng tương ứng trong kho hàng).";
            }

            return redirect()->back()->with('success', $msg);

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Lỗi cập nhật trạng thái đơn hàng: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Mẫu in Hóa đơn / Phiếu xuất kho giao hàng (Invoice / Packing Slip)
     */
    public function invoice(Order $order)
    {
        $order->load(['items.product', 'items.variant.size', 'items.variant.color']);

        return view('admin.orders.invoice', compact('order'));
    }
}
