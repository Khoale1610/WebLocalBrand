<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Danh sách khách hàng & Thành viên
     */
    public function index(Request $request)
    {
        $query = User::withCount('orders')
            ->withSum(['orders' => function ($q) {
                $q->where('order_status', Order::STATUS_COMPLETED)
                  ->orWhere('payment_status', Order::PAYMENT_PAID);
            }], 'total_amount');

        // 1. Tìm kiếm theo tên, email, sđt
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // 2. Lọc theo vai trò (Customer / Admin)
        if ($role = $request->input('role')) {
            $query->where('role', $role);
        }

        // 3. Sắp xếp (Chi tiêu cao nhất / Mới nhất / Nhiều đơn nhất)
        $sort = $request->input('sort', 'latest');
        if ($sort === 'spending_desc') {
            $query->orderByDesc('orders_sum_total_amount');
        } elseif ($sort === 'orders_desc') {
            $query->orderByDesc('orders_count');
        } else {
            $query->orderBy('id', 'desc');
        }

        $users = $query->paginate(10)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Hồ sơ chi tiết khách hàng & Lịch sử mua hàng
     */
    public function show(User $user)
    {
        $orders = $user->orders()->with(['items'])->latestFirst()->paginate(10);

        // Tính tổng chi tiêu
        $totalSpent = $user->orders()
            ->where(function ($q) {
                $q->where('order_status', Order::STATUS_COMPLETED)
                  ->orWhere('payment_status', Order::PAYMENT_PAID);
            })
            ->sum('total_amount');

        // Tính phân hạng thành viên
        $rank = [
            'name'  => 'Thành viên Mới (Đồng)',
            'color' => 'bg-secondary',
            'icon'  => 'fa-award',
        ];
        if ($totalSpent >= 7000000) {
            $rank = ['name' => 'VIP Kim Cương', 'color' => 'bg-primary', 'icon' => 'fa-gem'];
        } elseif ($totalSpent >= 3000000) {
            $rank = ['name' => 'Thành viên Vàng', 'color' => 'bg-warning text-dark', 'icon' => 'fa-crown'];
        } elseif ($totalSpent >= 1000000) {
            $rank = ['name' => 'Thành viên Bạc', 'color' => 'bg-info text-dark', 'icon' => 'fa-medal'];
        }

        return view('admin.users.show', compact('user', 'orders', 'totalSpent', 'rank'));
    }

    /**
     * Đổi vai trò (Customer <-> Admin)
     */
    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:customer,admin',
        ]);

        // Tránh tự hạ quyền của chính mình
        if (auth()->id() === $user->id && $request->role !== 'admin') {
            return redirect()->back()->with('error', 'Bạn không thể tự hạ quyền Admin của chính tài khoản đang đăng nhập!');
        }

        $user->update(['role' => $request->role]);

        return redirect()->back()->with('success', "Đã cập nhật vai trò cho '{$user->name}' thành: " . strtoupper($request->role));
    }
}
