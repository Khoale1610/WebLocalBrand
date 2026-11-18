@extends('admin.layouts.app')

@section('title', 'Bảng điều khiển — OWEN STUDIO Admin')
@section('page_title', 'Bảng điều khiển')

@section('content')
<div class="container-fluid px-0">

    <!-- Page Header Title -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-4 pb-3 border-bottom gap-3" style="border-color: #E5E7EB !important;">
        <div>
            <span class="d-block text-uppercase fw-semibold mb-1" style="font-size: 11.5px; letter-spacing: 0.14em; color: #C83E3B;">REAL-TIME ANALYTICS</span>
            <h1 class="fw-semibold text-dark mb-1" style="font-size: 22px; line-height: 1.35;">Tổng quan hoạt động</h1>
            <p class="text-muted mb-0" style="font-size: 13.5px;">Theo dõi doanh số, đơn hàng và tình trạng tồn kho cửa hàng theo thời gian thực.</p>
        </div>
        <div>
            <span class="badge bg-white text-dark border px-3 py-2 rounded-none" style="border-color: #E5E7EB !important; font-weight: 500; font-size: 12.5px;">
                Hôm nay: <strong>{{ now()->format('d/m/Y') }}</strong>
            </span>
        </div>
    </div>

    <!-- 1. KPI Metric Cards Row (Vuông góc 0px, tối giản sắc cạnh) -->
    <div class="row g-3 mb-4">
        <!-- Doanh thu tháng -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card rounded-none h-100 p-3" style="border-left: 3px solid #C83E3B !important;">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted text-uppercase fw-semibold" style="font-size: 11.5px; letter-spacing: 0.06em;">Doanh thu tháng {{ now()->format('m/Y') }}</span>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#4B5563" stroke-width="1.5" stroke-linecap="square" stroke-linejoin="miter">
                        <line x1="12" y1="1" x2="12" y2="23"></line>
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                    </svg>
                </div>
                <div class="fw-semibold mb-1" style="font-size: 21px; color: #C83E3B;">
                    {{ number_format($monthlyRevenue, 0, ',', '.') }}<span style="font-size: 18px;">đ</span>
                </div>
                <div class="text-muted" style="font-size: 12.5px;">
                    Hôm nay: <strong class="text-dark">{{ number_format($todayRevenue, 0, ',', '.') }}đ</strong>
                </div>
            </div>
        </div>

        <!-- Đơn hàng cần xử lý -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card rounded-none h-100 p-3" style="border-left: 3px solid #111111 !important;">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted text-uppercase fw-semibold" style="font-size: 11.5px; letter-spacing: 0.06em;">Đơn cần xử lý</span>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#4B5563" stroke-width="1.5" stroke-linecap="square" stroke-linejoin="miter">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                        <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                        <line x1="12" y1="22.08" x2="12" y2="12"></line>
                    </svg>
                </div>
                <div class="fw-semibold mb-1 text-dark" style="font-size: 21px;">
                    {{ $pendingOrdersCount }} <span class="fw-normal text-muted" style="font-size: 14px;">đơn</span>
                </div>
                <div class="text-muted" style="font-size: 12.5px;">
                    Tổng tích lũy: <strong class="text-dark">{{ $totalOrdersCount }} đơn</strong>
                </div>
            </div>
        </div>

        <!-- Tổng sản phẩm -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card rounded-none h-100 p-3" style="border-left: 3px solid #111111 !important;">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted text-uppercase fw-semibold" style="font-size: 11.5px; letter-spacing: 0.06em;">Tổng sản phẩm</span>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#4B5563" stroke-width="1.5" stroke-linecap="square" stroke-linejoin="miter">
                        <path d="M20.38 3.46L16 2a4 4 0 0 1-8 0L3.62 3.46a2 2 0 0 0-1.34 2.23l.58 3.47a1 1 0 0 0 .99.84H6v10c0 1.1.9 2 2 2h8a2 2 0 0 0 2-2V10h2.15a1 1 0 0 0 .99-.84l.58-3.47a2 2 0 0 0-1.34-2.23z"></path>
                    </svg>
                </div>
                <div class="fw-semibold mb-1 text-dark" style="font-size: 21px;">
                    {{ $totalProducts }} <span class="fw-normal text-muted" style="font-size: 14px;">mặt hàng</span>
                </div>
                <div class="text-muted" style="font-size: 12.5px;">Đang mở bán trên hệ thống</div>
            </div>
        </div>

        <!-- Tổng khách hàng -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card rounded-none h-100 p-3" style="border-left: 3px solid #111111 !important;">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted text-uppercase fw-semibold" style="font-size: 11.5px; letter-spacing: 0.06em;">Khách hàng</span>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#4B5563" stroke-width="1.5" stroke-linecap="square" stroke-linejoin="miter">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                </div>
                <div class="fw-semibold mb-1 text-dark" style="font-size: 21px;">
                    {{ $totalCustomers }} <span class="fw-normal text-muted" style="font-size: 14px;">tài khoản</span>
                </div>
                <div class="text-muted" style="font-size: 12.5px;">Hồ sơ thành viên đã đăng ký</div>
            </div>
        </div>
    </div>

    <!-- 2. Charts Row: Revenue Line Chart & Order Status Doughnut -->
    <div class="row g-3 mb-4">
        <!-- Biểu đồ doanh thu 7 ngày -->
        <div class="col-12 col-lg-8">
            <div class="card rounded-none h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Xu hướng doanh thu (7 ngày gần nhất)</span>
                    <span class="badge bg-light text-muted border rounded-none">Đơn vị: đ</span>
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
            <div class="card rounded-none h-100">
                <div class="card-header">
                    <span>Cơ cấu trạng thái đơn hàng</span>
                </div>
                <div class="card-body p-3 d-flex flex-column align-items-center justify-content-center">
                    <div style="position: relative; height: 210px; width: 100%;">
                        <canvas id="statusChart"></canvas>
                    </div>
                    <div class="mt-3 text-muted text-center" style="font-size: 12.5px;">
                        Tổng hợp toàn bộ <strong>{{ $totalOrdersCount }}</strong> đơn hàng
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Two Columns: Recent Orders & Top Selling Products -->
    <div class="row g-3 mb-4">
        <!-- Đơn hàng gần nhất -->
        <div class="col-12 col-xl-7">
            <div class="card rounded-none h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Đơn hàng mới nhận</span>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-dark rounded-none px-3">
                        Xem tất cả
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
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
                                    <td class="fw-semibold">
                                        <a href="{{ route('admin.orders.show', $order->id) }}" class="text-decoration-none" style="color: #C83E3B;">
                                            #{{ $order->order_code }}
                                        </a>
                                    </td>
                                    <td>
                                        <div class="fw-medium text-dark">{{ $order->customer_name }}</div>
                                        <div class="text-muted" style="font-size: 12px;">{{ $order->customer_phone }}</div>
                                    </td>
                                    <td class="fw-semibold text-dark">
                                        {{ number_format($order->total_amount, 0, ',', '.') }}đ
                                    </td>
                                    <td>
                                        @php
                                            $statusMap = [
                                                'pending'    => ['label' => 'Chờ xử lý', 'style' => 'background:#FAFAFA; color:#111111; border:1px solid #E5E7EB;'],
                                                'processing' => ['label' => 'Đang đóng gói', 'style' => 'background:#FAFAFA; color:#111111; border:1px solid #111111;'],
                                                'shipping'   => ['label' => 'Đang giao', 'style' => 'background:#111111; color:#FFFFFF; border:1px solid #111111;'],
                                                'completed'  => ['label' => 'Hoàn thành', 'style' => 'background:#111111; color:#FFFFFF; border:1px solid #111111;'],
                                                'cancelled'  => ['label' => 'Đã hủy', 'style' => 'background:#FFF5F5; color:#C83E3B; border:1px solid #C83E3B;'],
                                            ];
                                            $st = $statusMap[$order->order_status] ?? ['label' => $order->order_status, 'style' => 'background:#FAFAFA; color:#111111; border:1px solid #E5E7EB;'];
                                        @endphp
                                        <span class="badge rounded-none" style="{{ $st['style'] }}">
                                            {{ $st['label'] }}
                                        </span>
                                    </td>
                                    <td class="text-muted" style="font-size: 12.5px;">
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
            <div class="card rounded-none h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Top sản phẩm bán chạy</span>
                    <span class="badge rounded-none" style="background-color: #111111; color: #FFFFFF;">BEST SELLERS</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
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
                                                <img src="{{ $top->product->image_url }}" alt="" class="border object-fit-cover rounded-none" style="width: 36px; height: 48px;">
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
                                        <span class="badge bg-light text-dark border rounded-none px-2 py-1">
                                            {{ $top->total_sold }} sp
                                        </span>
                                    </td>
                                    <td class="text-end fw-semibold" style="color: #C83E3B;">
                                        {{ number_format($top->total_revenue, 0, ',', '.') }}đ
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted">Chưa có dữ liệu sản phẩm bán ra.</td>
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
            <div class="card rounded-none">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Cảnh báo tồn kho thấp (Cần nhập thêm hàng)</span>
                    <span class="badge rounded-none" style="background-color: #C83E3B; color: #FFFFFF;">Ngưỡng: &le; 5 sản phẩm</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Phân loại biến thể</th>
                                    <th>Mã SKU</th>
                                    <th>Số lượng tồn</th>
                                    <th class="text-end">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($lowStockVariants as $variant)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                @if($variant->product)
                                                    <img src="{{ $variant->image_url }}" alt="" class="border object-fit-cover rounded-none" style="width: 36px; height: 48px;">
                                                    <span class="fw-semibold text-dark">{{ $variant->product->name }}</span>
                                                @else
                                                    <span class="text-muted">Sản phẩm #{{ $variant->product_id }}</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            Size: <span class="badge bg-light text-dark border rounded-none">{{ $variant->size->name ?? 'N/A' }}</span>
                                            Màu: <span class="badge bg-light text-dark border rounded-none">{{ $variant->color->name ?? 'N/A' }}</span>
                                        </td>
                                        <td>
                                            <code class="text-dark bg-light px-2 py-1 border rounded-none">{{ $variant->sku }}</code>
                                        </td>
                                        <td>
                                            <span class="badge rounded-none" style="background-color: #C83E3B; color: #FFFFFF;">
                                                Còn {{ $variant->stock }} sp
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            @if($variant->product_id)
                                                <a href="{{ route('admin.products.edit', $variant->product_id) }}#variants-section" class="btn btn-sm btn-outline-dark rounded-none px-3">
                                                    Cập nhật kho
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-4 text-center text-muted">
                                            Kho hàng hoạt động ổn định, không có biến thể nào dưới ngưỡng tồn kho tối thiểu.
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
    // 1. Line Chart: Doanh thu 7 ngày gần nhất (Tông Đen #111111 & Đỏ gạch #C83E3B)
    const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctxRevenue, {
        type: 'line',
        data: {
            labels: {!! json_encode($labelsLast7Days) !!},
            datasets: [{
                label: 'Doanh thu (đ)',
                data: {!! json_encode($revenueLast7Days) !!},
                borderColor: '#111111',
                backgroundColor: 'rgba(17, 17, 17, 0.05)',
                borderWidth: 2,
                fill: true,
                tension: 0.25,
                pointBackgroundColor: '#C83E3B',
                pointBorderColor: '#FFFFFF',
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
                            return ' ' + context.parsed.y.toLocaleString('vi-VN') + 'đ';
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
                    grid: { color: '#F3F4F6' }
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
                    '#9CA3AF',
                    '#6B7280',
                    '#374151',
                    '#111111',
                    '#C83E3B'
                ],
                borderWidth: 2,
                borderColor: '#FFFFFF'
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
            cutout: '66%'
        }
    });
});
</script>
@endpush
