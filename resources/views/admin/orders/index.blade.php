@extends('admin.layouts.app')

@section('title', 'Quản lý Đơn hàng')
@section('page_title', 'Danh sách Đơn hàng')

@section('content')
<div class="container-fluid px-0">

    <!-- Header Actions -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold text-dark mb-1">Quản lý Đơn hàng</h3>
            <p class="text-muted small mb-0">Theo dõi, xác nhận đơn hàng, cập nhật vận chuyển và xử lý hoàn trả kho tự động khi hủy.</p>
        </div>
        <div class="d-flex gap-2">
            <span class="badge bg-white text-secondary border px-3 py-2 rounded-pill shadow-xs">
                Tổng đơn: <strong class="text-dark">{{ $statusCounts['all'] }}</strong>
            </span>
        </div>
    </div>

    <!-- Status Tabs Navigation -->
    @php
        $currentStatus = request('status', '');
    @endphp
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body p-2">
            <div class="nav nav-pills gap-1 flex-nowrap overflow-auto" id="orderStatusTabs" style="white-space: nowrap;">
                <!-- Tất cả -->
                <a href="{{ route('admin.orders.index', request()->except('status', 'page')) }}" 
                   class="nav-link py-2 px-3 rounded-pill fw-semibold {{ $currentStatus === '' ? 'active' : '' }}">
                    Tất cả <span class="badge bg-white text-dark ms-1">{{ $statusCounts['all'] }}</span>
                </a>

                <!-- Chờ xác nhận -->
                <a href="{{ route('admin.orders.index', array_merge(request()->except('page'), ['status' => 'pending'])) }}" 
                   class="nav-link py-2 px-3 rounded-pill fw-semibold {{ $currentStatus === 'pending' ? 'active bg-warning text-dark' : 'text-dark' }}">
                    <i class="fa-regular fa-clock me-1 text-warning"></i>Chờ xác nhận 
                    <span class="badge {{ $statusCounts['pending'] > 0 ? 'bg-danger text-white' : 'bg-light text-dark' }} ms-1">{{ $statusCounts['pending'] }}</span>
                </a>

                <!-- Đang đóng gói -->
                <a href="{{ route('admin.orders.index', array_merge(request()->except('page'), ['status' => 'processing'])) }}" 
                   class="nav-link py-2 px-3 rounded-pill fw-semibold {{ $currentStatus === 'processing' ? 'active bg-info text-dark' : 'text-dark' }}">
                    <i class="fa-solid fa-box-open me-1 text-info"></i>Đang đóng gói 
                    <span class="badge bg-light text-dark ms-1">{{ $statusCounts['processing'] }}</span>
                </a>

                <!-- Đang giao -->
                <a href="{{ route('admin.orders.index', array_merge(request()->except('page'), ['status' => 'shipping'])) }}" 
                   class="nav-link py-2 px-3 rounded-pill fw-semibold {{ $currentStatus === 'shipping' ? 'active bg-primary text-white' : 'text-dark' }}">
                    <i class="fa-solid fa-truck-fast me-1 text-primary"></i>Đang giao 
                    <span class="badge bg-light text-dark ms-1">{{ $statusCounts['shipping'] }}</span>
                </a>

                <!-- Hoàn thành -->
                <a href="{{ route('admin.orders.index', array_merge(request()->except('page'), ['status' => 'completed'])) }}" 
                   class="nav-link py-2 px-3 rounded-pill fw-semibold {{ $currentStatus === 'completed' ? 'active bg-success text-white' : 'text-dark' }}">
                    <i class="fa-solid fa-circle-check me-1 text-success"></i>Hoàn thành 
                    <span class="badge bg-light text-dark ms-1">{{ $statusCounts['completed'] }}</span>
                </a>

                <!-- Đã hủy -->
                <a href="{{ route('admin.orders.index', array_merge(request()->except('page'), ['status' => 'cancelled'])) }}" 
                   class="nav-link py-2 px-3 rounded-pill fw-semibold {{ $currentStatus === 'cancelled' ? 'active bg-danger text-white' : 'text-dark' }}">
                    <i class="fa-solid fa-ban me-1 text-danger"></i>Đã hủy 
                    <span class="badge bg-light text-dark ms-1">{{ $statusCounts['cancelled'] }}</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Filters Bar -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.orders.index') }}" method="GET" class="row g-2 align-items-center">
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif

                <!-- Search Input -->
                <div class="col-12 col-md-4 col-lg-3">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                        <input type="text" name="search" class="form-control bg-light border-start-0" placeholder="Mã đơn, Tên, SĐT, Email..." value="{{ request('search') }}">
                    </div>
                </div>

                <!-- Payment Method Filter -->
                <div class="col-6 col-md-3 col-lg-2">
                    <select name="payment_method" class="form-select bg-light">
                        <option value="">-- P.Thức TT --</option>
                        <option value="cod" {{ request('payment_method') == 'cod' ? 'selected' : '' }}>COD (Tiền mặt)</option>
                        <option value="vietqr" {{ request('payment_method') == 'vietqr' ? 'selected' : '' }}>VietQR Ngân hàng</option>
                        <option value="vnpay" {{ request('payment_method') == 'vnpay' ? 'selected' : '' }}>VNPAY Sandbox</option>
                    </select>
                </div>

                <!-- Payment Status Filter -->
                <div class="col-6 col-md-3 col-lg-2">
                    <select name="payment_status" class="form-select bg-light">
                        <option value="">-- T.Thái TT --</option>
                        <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>Chưa thanh toán</option>
                        <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                        <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Thất bại</option>
                        <option value="refunded" {{ request('payment_status') == 'refunded' ? 'selected' : '' }}>Đã hoàn tiền</option>
                    </select>
                </div>

                <!-- Date Range -->
                <div class="col-6 col-md-3 col-lg-2">
                    <input type="date" name="from_date" class="form-control bg-light" value="{{ request('from_date') }}" title="Từ ngày">
                </div>
                <div class="col-6 col-md-3 col-lg-2">
                    <input type="date" name="to_date" class="form-control bg-light" value="{{ request('to_date') }}" title="Đến ngày">
                </div>

                <!-- Action Button -->
                <div class="col-12 col-lg-1 d-flex gap-1">
                    <button type="submit" class="btn btn-dark w-100" title="Lọc">
                        <i class="fa-solid fa-filter"></i>
                    </button>
                    @if(request()->hasAny(['search', 'payment_method', 'payment_status', 'from_date', 'to_date']))
                        <a href="{{ route('admin.orders.index', request()->only('status')) }}" class="btn btn-outline-secondary" title="Xóa lọc">
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Orders Data Table -->
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size: 0.875rem;">
                <thead class="table-light text-muted small text-uppercase">
                    <tr>
                        <th style="min-width: 140px;">Mã đơn hàng</th>
                        <th style="min-width: 170px;">Khách hàng</th>
                        <th>Sản phẩm</th>
                        <th>Tổng tiền</th>
                        <th>Thanh toán</th>
                        <th class="text-center">Trạng thái</th>
                        <th>Ngày đặt</th>
                        <th class="text-end" style="width: 110px;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <!-- Mã đơn hàng -->
                            <td>
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="fw-bold text-primary text-decoration-none fs-6">
                                    #{{ $order->order_code }}
                                </a>
                                <div>
                                    <span class="badge bg-light text-dark border font-monospace small">
                                        {{ strtoupper($order->payment_method) }}
                                    </span>
                                </div>
                            </td>

                            <!-- Khách hàng -->
                            <td>
                                <div class="fw-semibold text-dark">{{ $order->customer_name }}</div>
                                <div class="small text-muted"><i class="fa-solid fa-phone me-1 small"></i>{{ $order->customer_phone }}</div>
                                <div class="small text-muted text-truncate" style="max-width: 200px;" title="{{ $order->customer_address }}">
                                    {{ $order->customer_address }}
                                </div>
                            </td>

                            <!-- Sản phẩm tóm tắt -->
                            <td>
                                <div class="fw-medium text-dark">
                                    {{ $order->items->sum('quantity') }} món
                                </div>
                                <small class="text-muted text-truncate d-block" style="max-width: 220px;" title="{{ $order->items->pluck('product_name')->join(', ') }}">
                                    {{ $order->items->pluck('product_name')->join(', ') }}
                                </small>
                            </td>

                            <!-- Tổng tiền -->
                            <td>
                                <div class="fw-bold text-dark fs-6">{{ number_format($order->total_amount, 0, ',', '.') }} ₫</div>
                                @if($order->shipping_fee > 0)
                                    <small class="text-muted">(Ship: {{ number_format($order->shipping_fee, 0, ',', '.') }}₫)</small>
                                @else
                                    <small class="text-success"><i class="fa-solid fa-truck-fast me-1"></i>Freeship</small>
                                @endif
                            </td>

                            <!-- Trạng thái thanh toán -->
                            <td>
                                {!! $order->payment_status_badge !!}
                            </td>

                            <!-- Trạng thái đơn hàng -->
                            <td class="text-center">
                                @php
                                    $statusMap = [
                                        'pending'    => ['label' => 'Chờ xác nhận', 'class' => 'bg-warning-subtle text-warning border-warning-subtle'],
                                        'processing' => ['label' => 'Đang đóng gói', 'class' => 'bg-info-subtle text-info border-info-subtle'],
                                        'shipping'   => ['label' => 'Đang giao', 'class' => 'bg-primary-subtle text-primary border-primary-subtle'],
                                        'completed'  => ['label' => 'Hoàn thành', 'class' => 'bg-success-subtle text-success border-success-subtle'],
                                        'cancelled'  => ['label' => 'Đã hủy', 'class' => 'bg-danger-subtle text-danger border-danger-subtle'],
                                    ];
                                    $st = $statusMap[$order->order_status] ?? ['label' => $order->order_status, 'class' => 'bg-light text-dark'];
                                @endphp
                                <span class="badge {{ $st['class'] }} border px-3 py-2 rounded-pill">
                                    {{ $st['label'] }}
                                </span>
                            </td>

                            <!-- Ngày đặt -->
                            <td>
                                <div class="text-dark">{{ $order->created_at ? $order->created_at->format('d/m/Y') : '—' }}</div>
                                <small class="text-muted">{{ $order->created_at ? $order->created_at->format('H:i') : '' }} ({{ $order->created_at ? $order->created_at->diffForHumans() : '' }})</small>
                            </td>

                            <!-- Thao tác -->
                            <td class="text-end">
                                <!-- In hóa đơn -->
                                <a href="{{ route('admin.orders.invoice', $order->id) }}" target="_blank" class="btn btn-sm btn-outline-secondary rounded-circle me-1" title="In phiếu giao hàng">
                                    <i class="fa-solid fa-print"></i>
                                </a>

                                <!-- Xem & Xử lý -->
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-primary rounded-circle" title="Xem chi tiết & Xử lý">
                                    <i class="fa-solid fa-arrow-right"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-receipt fs-1 text-secondary opacity-50 d-block mb-3"></i>
                                Không tìm thấy đơn hàng nào phù hợp với điều kiện tìm kiếm.
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
@endsection
