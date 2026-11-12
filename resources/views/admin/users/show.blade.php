@extends('admin.layouts.app')

@section('title', 'Hồ sơ khách hàng: ' . $user->name)
@section('page_title', 'Hồ sơ Khách hàng')

@section('content')
<div class="container-fluid px-0">

    <!-- Top Navigation Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-light rounded-circle text-muted" title="Quay lại danh sách">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <h3 class="fw-bold text-dark mb-0">{{ $user->name }}</h3>
                <span class="badge {{ $rank['color'] }} rounded-pill px-3 py-1 ms-2">
                    <i class="fa-solid {{ $rank['icon'] }} me-1"></i>{{ $rank['name'] }}
                </span>
            </div>
            <p class="text-muted small mb-0 ps-4 ms-2">Khách hàng ID: #{{ $user->id }} | Thành viên từ: {{ $user->created_at ? $user->created_at->format('d/m/Y') : '—' }}</p>
        </div>
    </div>

    <div class="row g-4">
        <!-- LEFT COLUMN: Thông tin cá nhân & Thống kê chi tiêu (4 Cột) -->
        <div class="col-12 col-lg-4">
            
            <!-- Card 1: Thông tin cá nhân -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body text-center p-4">
                    <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center fw-bold shadow mb-3" style="width: 72px; height: 72px; font-size: 28px;">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <h5 class="fw-bold text-dark mb-1">{{ $user->name }}</h5>
                    <div class="text-muted small mb-3">{{ $user->email }}</div>

                    <div class="text-start border-top pt-3">
                        <div class="mb-2">
                            <span class="text-muted small d-block">Số điện thoại:</span>
                            <span class="fw-semibold text-dark">
                                @if($user->phone)
                                    <a href="tel:{{ $user->phone }}" class="text-decoration-none text-dark">
                                        <i class="fa-solid fa-phone me-1 text-primary"></i>{{ $user->phone }}
                                    </a>
                                @else
                                    <span class="text-muted small">Chưa cung cấp</span>
                                @endif
                            </span>
                        </div>
                        <div class="mb-2">
                            <span class="text-muted small d-block">Địa chỉ giao hàng:</span>
                            <span class="text-dark small">{{ $user->address ?: 'Chưa cập nhật địa chỉ' }}</span>
                        </div>
                        <div>
                            <span class="text-muted small d-block">Vai trò hệ thống:</span>
                            <span class="badge {{ $user->role === 'admin' ? 'bg-danger' : 'bg-secondary' }} rounded-pill px-3 py-1 mt-1">
                                {{ strtoupper($user->role) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 2: Thống kê mua hàng (CLV) -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 fw-bold text-dark">
                    <i class="fa-solid fa-chart-line text-primary me-2"></i>Chỉ số giá trị khách hàng
                </div>
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded-3 mb-2">
                        <span class="text-muted small">Tổng chi tiêu:</span>
                        <span class="fw-bold fs-5 text-danger">{{ number_format($totalSpent, 0, ',', '.') }} ₫</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded-3">
                        <span class="text-muted small">Số đơn hoàn thành:</span>
                        <span class="fw-bold fs-5 text-dark">{{ $orders->total() }} đơn</span>
                    </div>
                </div>
            </div>

            <!-- Card 3: Phân quyền vai trò -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 fw-bold text-dark">
                    <i class="fa-solid fa-user-shield text-primary me-2"></i>Phân quyền tài khoản
                </div>
                <div class="card-body p-3">
                    <form action="{{ route('admin.users.role.update', $user->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn thay đổi vai trò tài khoản này?');">
                        @csrf
                        @method('PATCH')
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Chọn vai trò:</label>
                            <select name="role" class="form-select">
                                <option value="customer" {{ $user->role === 'customer' ? 'selected' : '' }}>Khách hàng thường (Customer)</option>
                                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Quản trị viên (Admin)</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-dark btn-sm rounded-pill w-100 py-2 fw-semibold">
                            Cập nhật vai trò
                        </button>
                    </form>
                </div>
            </div>

        </div>

        <!-- RIGHT COLUMN: Lịch sử đơn hàng (8 Cột) -->
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <div class="fw-bold text-dark">
                        <i class="fa-solid fa-receipt text-primary me-2"></i>Lịch sử đặt hàng ({{ $orders->total() }} đơn)
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="font-size: 0.875rem;">
                        <thead class="table-light text-muted small text-uppercase">
                            <tr>
                                <th>Mã đơn</th>
                                <th>Ngày đặt</th>
                                <th>Số món</th>
                                <th>Tổng tiền</th>
                                <th>Thanh toán</th>
                                <th class="text-center">Trạng thái</th>
                                <th class="text-end" style="width: 70px;">Chi tiết</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $order->id) }}" class="fw-bold text-primary text-decoration-none">
                                            #{{ $order->order_code }}
                                        </a>
                                    </td>
                                    <td class="text-muted small">
                                        {{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : '—' }}
                                    </td>
                                    <td>
                                        {{ $order->items->sum('quantity') }} sản phẩm
                                    </td>
                                    <td class="fw-bold text-dark">
                                        {{ number_format($order->total_amount, 0, ',', '.') }} ₫
                                    </td>
                                    <td>
                                        {!! $order->payment_status_badge !!}
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $statusMap = [
                                                'pending'    => ['label' => 'Chờ xử lý', 'class' => 'bg-warning-subtle text-warning border-warning-subtle'],
                                                'processing' => ['label' => 'Đang đóng gói', 'class' => 'bg-info-subtle text-info border-info-subtle'],
                                                'shipping'   => ['label' => 'Đang giao', 'class' => 'bg-primary-subtle text-primary border-primary-subtle'],
                                                'completed'  => ['label' => 'Hoàn thành', 'class' => 'bg-success-subtle text-success border-success-subtle'],
                                                'cancelled'  => ['label' => 'Đã hủy', 'class' => 'bg-danger-subtle text-danger border-danger-subtle'],
                                            ];
                                            $st = $statusMap[$order->order_status] ?? ['label' => $order->order_status, 'class' => 'bg-light text-dark'];
                                        @endphp
                                        <span class="badge {{ $st['class'] }} border px-2 py-1 rounded-pill">
                                            {{ $st['label'] }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary rounded-circle" title="Xem chi tiết đơn">
                                            <i class="fa-solid fa-arrow-right"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        Khách hàng này chưa có đơn hàng nào trong hệ thống.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($orders->hasPages())
                    <div class="card-footer bg-white py-3">
                        <div class="d-flex justify-content-center">
                            {{ $orders->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

</div>
@endsection
