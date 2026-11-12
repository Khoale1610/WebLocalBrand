<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Hiển thị bảng điều khiển trung tâm (Admin Dashboard)
     */
    public function index()
    {
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();

        // 1. Thống kê số liệu thẻ KPI
        // Tổng doanh thu tháng này (chỉ tính đơn hoàn thành hoặc đã thanh toán)
        $monthlyRevenue = Order::where('created_at', '>=', $startOfMonth)
            ->where(function ($query) {
                $query->where('order_status', Order::STATUS_COMPLETED)
                      ->orWhere('payment_status', Order::PAYMENT_PAID);
            })
            ->sum('total_amount');

        // Doanh thu hôm nay
        $todayRevenue = Order::whereDate('created_at', Carbon::today())
            ->where(function ($query) {
                $query->where('order_status', Order::STATUS_COMPLETED)
                      ->orWhere('payment_status', Order::PAYMENT_PAID);
            })
            ->sum('total_amount');

        // Số đơn hàng mới cần xử lý (pending / processing)
        $pendingOrdersCount = Order::whereIn('order_status', [Order::STATUS_PENDING, Order::STATUS_PROCESSING])->count();
        $totalOrdersCount = Order::count();

        // Tổng số khách hàng
        $totalCustomers = User::where('role', 'customer')->count();

        // Tổng số sản phẩm và số biến thể
        $totalProducts = Product::count();

        // Cảnh báo tồn kho thấp (biến thể có stock <= 5 hoặc sản phẩm có stock <= 5)
        $lowStockVariants = ProductVariant::with(['product', 'size', 'color'])
            ->where('stock', '<=', 5)
            ->orderBy('stock', 'asc')
            ->limit(5)
            ->get();

        // 2. Danh sách 8 đơn hàng mới nhất
        $recentOrders = Order::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        // 3. Đơn hàng theo trạng thái (dùng vẽ biểu đồ tròn / donut)
        $statusCounts = [
            'pending'    => Order::where('order_status', Order::STATUS_PENDING)->count(),
            'processing' => Order::where('order_status', Order::STATUS_PROCESSING)->count(),
            'shipping'   => Order::where('order_status', Order::STATUS_SHIPPING)->count(),
            'completed'  => Order::where('order_status', Order::STATUS_COMPLETED)->count(),
            'cancelled'  => Order::where('order_status', Order::STATUS_CANCELLED)->count(),
        ];

        // 4. Doanh thu 7 ngày gần nhất (dùng vẽ biểu đồ đường Line Chart)
        $revenueLast7Days = [];
        $labelsLast7Days = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dayLabel = $date->format('d/m');
            $dayRevenue = Order::whereDate('created_at', $date)
                ->where(function ($query) {
                    $query->where('order_status', Order::STATUS_COMPLETED)
                          ->orWhere('payment_status', Order::PAYMENT_PAID);
                })
                ->sum('total_amount');

            $labelsLast7Days[] = $dayLabel;
            $revenueLast7Days[] = (float) $dayRevenue;
        }

        // 5. Top 5 sản phẩm bán chạy nhất (dựa trên số lượng trong các đơn không bị hủy)
        $topProducts = OrderItem::select(
                'product_id',
                'product_name',
                DB::raw('SUM(quantity) as total_sold'),
                DB::raw('SUM(total_price) as total_revenue')
            )
            ->whereHas('order', function ($query) {
                $query->where('order_status', '!=', Order::STATUS_CANCELLED);
            })
            ->groupBy('product_id', 'product_name')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->with('product')
            ->get();

        return view('admin.dashboard', compact(
            'monthlyRevenue',
            'todayRevenue',
            'pendingOrdersCount',
            'totalOrdersCount',
            'totalCustomers',
            'totalProducts',
            'lowStockVariants',
            'recentOrders',
            'statusCounts',
            'labelsLast7Days',
            'revenueLast7Days',
            'topProducts'
        ));
    }
}
