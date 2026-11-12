@extends('admin.layouts.app')

@section('title', 'Dashboard - Tổng quan hệ thống')
@section('page_title', 'Dashboard')

@section('content')
<div class="container-fluid px-0">

    <!-- Page Header Title -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold text-dark mb-1">Tổng quan hoạt động</h3>
            <p class="text-muted small mb-0">Theo dõi doanh số, đơn hàng và tình trạng tồn kho cửa hàng theo thời gian thực.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-white text-secondary border px-3 py-2 rounded-pill shadow-xs">
                <i class="fa-regular fa-clock me-1 text-primary"></i> Hôm nay: {{ now()->format('d/m/Y') }}
            </span>
        </div>
    </div>

    <!-- 1. KPI Metric Cards Row -->
    <div class="row g-3 mb-4">
        <!-- Doanh thu tháng -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100 p-3 border-0 shadow-sm" style="border-left: 4px solid #3b82f6 !important;">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-semibold text-uppercase">Doanh thu tháng {{ now()->format('m/Y') }}</span>
                    <div class="p-2 rounded bg-primary-subtle text-primary">
                        <i class="fa-solid fa-coins fs-5"></i>
                    </div>
                </div>
                <h4 class="fw-bold mb-1 text-dark">{{ number_format($monthlyRevenue, 0, ',', '.') }} <span class="fs-6 fw-normal text-muted">đ</span></h4>
                <div class="small text-muted">Hôm nay: <span class="text-success fw-bold">{{ number_format($todayRevenue, 0, ',', '.') }} đ</span></div>
            </div>
        </div>

        <!-- Đơn hàng cần xử lý -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100 p-3 border-0 shadow-sm" style="border-left: 4px solid #f59e0b !important;">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-semibold text-uppercase">Đơn cần xử lý</span>
                    <div class="p-2 rounded bg-warning-subtle text-warning">
                        <i class="fa-solid fa-box-open fs-5"></i>
                    </div>
                </div>
                <h4 class="fw-bold mb-1 text-dark">{{ $pendingOrdersCount }} <span class="fs-6 fw-normal text-muted">đơn</span></h4>
                <div class="small text-muted">Tổng tích lũy: <span class="fw-semibold text-dark">{{ $totalOrdersCount }} đơn</span></div>
            </div>
        </div>

        <!-- Tổng sản phẩm -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100 p-3 border-0 shadow-sm" style="border-left: 4px solid #10b981 !important;">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-semibold text-uppercase">Tổng Sản Phẩm</span>
                    <div class="p-2 rounded bg-success-subtle text-success">
                        <i class="fa-solid fa-shirt fs-5"></i>
                    </div>
                </div>
                <h4 class="fw-bold mb-1 text-dark">{{ $totalProducts }} <span class="fs-6 fw-normal text-muted">mặt hàng</span></h4>
                <div class="small text-muted">Đang bày bán trên shop</div>
            </div>
        </div>

        <!-- Tổng khách hàng -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card h-100 p-3 border-0 shadow-sm" style="border-left: 4px solid #8b5cf6 !important;">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-semibold text-uppercase">Khách hàng</span>
                    <div class="p-2 rounded bg-info-subtle text-info">
                        <i class="fa-solid fa-users fs-5"></i>
                    </div>
                </div>
                <h4 class="fw-bold mb-1 text-dark">{{ $totalCustomers }} <span class="fs-6 fw-normal text-muted">tài khoản</span></h4>
                <div class="small text-muted">Đã tạo hồ sơ mua hàng</div>
            </div>
        </div>
    </div>

    <!-- 2. Charts Row: Revenue Line Chart & Order Status Doughnut -->
    <div class="row g-3 mb-4">
        <!-- Biểu đồ doanh thu 7 ngày -->
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <div class="fw-bold text-dark d-flex align-items-center gap-2">
                        <i class="fa-solid fa-chart-line text-primary"></i>
                        <span>Xu hướng Doanh thu (7 ngày gần nhất)</span>
                    </div>
                    <span class="badge bg-light text-muted border">Đơn vị: VNĐ</span>
                </div>
                <div class="card-body p-3">
                    <div style="position: relative; height: 280px;">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Biểu đồ trạng thái đơn hàng -->
        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 fw-bold text-dark d-flex align-items-center gap-2">
                    <i class="fa-solid fa-chart-pie text-warning"></i>
                    <span>Cơ cấu Trạng thái Đơn hàng</span>
                </div>
                <div class="card-body p-3 d-flex flex-column align-items-center justify-content-center">
                    <div style="position: relative; height: 210px; width: 100%;">
                        <canvas id="statusChart"></canvas>
                    </div>
                    <div class="mt-3 small text-muted text-center">
                        Tổng hợp toàn bộ {{ $totalOrdersCount }} đơn hàng
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Two Columns: Recent Orders & Top Selling Products -->
    <div class="row g-3 mb-4">
        <!-- Đơn hàng gần nhất -->
        <div class="col-12 col-xl-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <div class="fw-bold text-dark d-flex align-items-center gap-2">
                        <i class="fa-solid fa-list-check text-primary"></i>
                        <span>Đơn hàng mới nhận</span>
                    </div>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                        Xem tất cả <i class="fa-solid fa-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="font-size: 0.875rem;">
                        <thead class="table-light text-muted small text-uppercase">
                            <tr>
                                <th>Mã đơn</th>
                                <th>Khách hàng</th>
                                <th>Tổng tiền</th>
                                <th>Trạng thái</th>
                                <th>Thời gian</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                                <tr>
                                    <td class="fw-bold text-primary">
                                        <a href="{{ route('admin.orders.show', $order->id) }}" class="text-decoration-none">
                                            #{{ $order->order_code }}
                                        </a>
                                    </td>
                                    <td>
                                        <div class="fw-medium text-dark">{{ $order->customer_name }}</div>
                                        <div class="small text-muted">{{ $order->customer_phone }}</div>
                                    </td>
                                    <td class="fw-bold text-dark">
                                        {{ number_format($order->total_amount, 0, ',', '.') }} đ
                                    </td>
                                    <td>
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
                                        <span class="badge {{ $st['class'] }} border">
                                            {{ $st['label'] }}
                                        </span>
                                    </td>
                                    <td class="text-muted small">
                                        {{ $order->created_at ? $order->created_at->diffForHumans() : '—' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">Chưa có đơn hàng nào trong hệ thống.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Top Sản Phẩm Bán Chạy Nhất -->
        <div class="col-12 col-xl-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <div class="fw-bold text-dark d-flex align-items-center gap-2">
                        <i class="fa-solid fa-fire text-danger"></i>
                        <span>Top Sản Phẩm Bán Chạy</span>
                    </div>
                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle">Best-sellers</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="font-size: 0.875rem;">
                        <thead class="table-light text-muted small text-uppercase">
                            <tr>
                                <th>Sản phẩm</th>
                                <th class="text-center">Đã bán</th>
                                <th class="text-end">Doanh số</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topProducts as $top)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            @if($top->product)
                                                <img src="{{ $top->product->image_url }}" alt="" class="rounded border object-fit-cover shadow-xs" style="width: 36px; height: 44px;">
                                                <div class="overflow-hidden">
                                                    <a href="{{ route('admin.products.edit', $top->product_id) }}" class="fw-semibold text-dark text-decoration-none text-truncate d-block" style="max-width: 170px;">
                                                        {{ $top->product_name }}
                                                    </a>
                                                    <small class="text-muted">{{ $top->product->category->name ?? 'Thời trang' }}</small>
                                                </div>
                                            @else
                                                <span class="fw-semibold text-dark">{{ $top->product_name }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1 fw-bold">
                                            {{ $top->total_sold }} cái
                                        </span>
                                    </td>
                                    <td class="text-end fw-bold text-dark">
                                        {{ number_format($top->total_revenue, 0, ',', '.') }} đ
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted small">Chưa có dữ liệu sản phẩm bán ra.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- 4. Low Stock Warning Row -->
    <div class="row g-3">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <div class="fw-bold text-dark d-flex align-items-center gap-2">
                        <i class="fa-solid fa-triangle-exclamation text-danger"></i>
                        <span>Cảnh báo tồn kho thấp (Cần nhập thêm hàng)</span>
                    </div>
                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle">Ngưỡng cảnh báo: ≤ 5 chiếc</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="font-size: 0.875rem;">
                            <thead class="table-light text-muted small text-uppercase">
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Phân loại biến thể</th>
                                    <th>Mã SKU</th>
                                    <th>Số lượng tồn hiện tại</th>
                                    <th class="text-end">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($lowStockVariants as $variant)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                @if($variant->product)
                                                    <img src="{{ $variant->image_url }}" alt="" class="rounded border object-fit-cover shadow-xs" style="width: 36px; height: 44px;">
                                                    <span class="fw-semibold text-dark">{{ $variant->product->name }}</span>
                                                @else
                                                    <span class="text-muted">Sản phẩm #{{ $variant->product_id }}</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            Size: <span class="badge bg-light text-dark border">{{ $variant->size->name ?? 'N/A' }}</span>
                                            | Màu: <span class="badge bg-light text-dark border">{{ $variant->color->name ?? 'N/A' }}</span>
                                        </td>
                                        <td>
                                            <code class="text-dark bg-light px-2 py-1 rounded small">{{ $variant->sku }}</code>
                                        </td>
                                        <td>
                                            <span class="badge bg-danger rounded-pill px-3 py-2">
                                                Còn {{ $variant->stock }} chiếc
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            @if($variant->product_id)
                                                <a href="{{ route('admin.products.edit', $variant->product_id) }}#variants-section" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                                    <i class="fa-solid fa-boxes-packing me-1"></i>Cập nhật kho
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-4 text-center text-muted small">
                                            <i class="fa-solid fa-circle-check text-success fs-4 d-block mb-2"></i>
                                            Kho hàng hoạt động ổn định, không có biến thể nào sắp cạn kiệt.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Line Chart: Doanh thu 7 ngày gần nhất
    const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctxRevenue, {
        type: 'line',
        data: {
            labels: {!! json_encode($labelsLast7Days) !!},
            datasets: [{
                label: 'Doanh thu (VNĐ)',
                data: {!! json_encode($revenueLast7Days) !!},
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderWidth: 2.5,
                fill: true,
                tension: 0.35,
                pointBackgroundColor: '#3b82f6',
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return ' ' + context.parsed.y.toLocaleString('vi-VN') + ' đ';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return (value / 1000).toLocaleString('vi-VN') + 'k';
                        }
                    },
                    grid: { color: '#f1f5f9' }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });

    // 2. Doughnut Chart: Cơ cấu Trạng thái đơn hàng
    const ctxStatus = document.getElementById('statusChart').getContext('2d');
    new Chart(ctxStatus, {
        type: 'doughnut',
        data: {
            labels: ['Chờ duyệt', 'Đang xử lý', 'Đang giao', 'Hoàn thành', 'Đã hủy'],
            datasets: [{
                data: [
                    {{ $statusCounts['pending'] }},
                    {{ $statusCounts['processing'] }},
                    {{ $statusCounts['shipping'] }},
                    {{ $statusCounts['completed'] }},
                    {{ $statusCounts['cancelled'] }}
                ],
                backgroundColor: [
                    '#f59e0b', // warning
                    '#06b6d4', // cyan
                    '#3b82f6', // blue
                    '#10b981', // green
                    '#ef4444'  // red
                ],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { boxWidth: 12, font: { size: 11 } }
                }
            },
            cutout: '65%'
        }
    });
});
</script>
@endpush
