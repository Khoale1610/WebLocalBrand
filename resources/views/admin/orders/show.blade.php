@extends('admin.layouts.app')

@section('title', 'Đơn hàng #' . $order->order_code)
@section('page_title', 'Chi tiết Đơn hàng')

@section('content')
<div class="container-fluid px-0">

    <!-- Top Action Navigation -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-light rounded-circle text-muted" title="Quay lại danh sách">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <h3 class="fw-bold text-dark mb-0">Đơn hàng #{{ $order->order_code }}</h3>
            </div>
            <p class="text-muted small mb-0 ps-4 ms-2">
                Ngày đặt: <strong>{{ $order->created_at ? $order->created_at->format('d/m/Y H:i:s') : '—' }}</strong> 
                ({{ $order->created_at ? $order->created_at->diffForHumans() : '' }})
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.orders.invoice', $order->id) }}" target="_blank" class="btn btn-dark rounded-pill px-4 shadow-sm fw-semibold">
                <i class="fa-solid fa-print me-2"></i>In phiếu xuất kho / giao hàng
            </a>
        </div>
    </div>

    <!-- Alert nếu đơn hàng đã bị hủy -->
    @if($order->order_status === \App\Models\Order::STATUS_CANCELLED)
        <div class="alert alert-danger d-flex align-items-center mb-4 border-0 shadow-sm" role="alert">
            <i class="fa-solid fa-triangle-exclamation fs-3 me-3"></i>
            <div>
                <h6 class="fw-bold mb-1">Đơn hàng này đã bị HỦY</h6>
                <p class="mb-0 small">Toàn bộ số lượng tồn kho của các sản phẩm và biến thể trong đơn này đã được tự động hoàn trả lại vào kho hàng.</p>
            </div>
        </div>
    @endif

    <div class="row g-4">
        <!-- LEFT COLUMN: Chi tiết Sản phẩm & Hóa đơn (8 Cột) -->
        <div class="col-12 col-lg-8">
            
            <!-- Card 1: Danh sách sản phẩm -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <div class="fw-bold text-dark">
                        <i class="fa-solid fa-bag-shopping text-primary me-2"></i>Danh sách sản phẩm ({{ $order->items->sum('quantity') }} sản phẩm)
                    </div>
                    <span class="badge bg-light text-dark border">{{ $order->items->count() }} mặt hàng</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="font-size: 0.875rem;">
                        <thead class="table-light text-muted small text-uppercase">
                            <tr>
                                <th style="width: 70px;">Ảnh</th>
                                <th>Sản phẩm & Biến thể</th>
                                <th class="text-end">Đơn giá</th>
                                <th class="text-center" style="width: 80px;">Số lượng</th>
                                <th class="text-end" style="width: 140px;">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        @php
                                            $img = $item->variant->image_url ?? $item->product->image_url ?? 'https://via.placeholder.com/60';
                                        @endphp
                                        <img src="{{ $img }}" 
                                             alt="{{ $item->product_name }}" 
                                             class="rounded border object-fit-cover shadow-xs" 
                                             style="width: 54px; height: 64px;">
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark fs-6">{{ $item->product_name }}</div>
                                        @if($item->variant_info)
                                            <div class="small text-muted mb-1">
                                                <span class="badge bg-light text-dark border">
                                                    {{ $item->variant_info }}
                                                </span>
                                            </div>
                                        @endif
                                        @if($item->variant && $item->variant->sku)
                                            <div class="small text-muted font-monospace">
                                                SKU: {{ $item->variant->sku }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="text-end fw-semibold text-dark">
                                        {{ number_format($item->price, 0, ',', '.') }} ₫
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark border px-3 py-2 fs-6 fw-bold">
                                            x{{ $item->quantity }}
                                        </span>
                                    </td>
                                    <td class="text-end fw-bold text-dark fs-6">
                                        {{ number_format($item->total_price, 0, ',', '.') }} ₫
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Bảng tính tiền & Chiết khấu -->
                <div class="card-footer bg-white p-4 border-top">
                    <div class="row justify-content-end">
                        <div class="col-12 col-md-6 col-lg-5">
                            <div class="d-flex justify-content-between py-1 text-muted small">
                                <span>Tạm tính tiền hàng:</span>
                                <span class="fw-semibold text-dark">{{ number_format($order->items->sum('total_price'), 0, ',', '.') }} ₫</span>
                            </div>
                            <div class="d-flex justify-content-between py-1 text-muted small">
                                <span>Phí vận chuyển:</span>
                                @if($order->shipping_fee > 0)
                                    <span class="fw-semibold text-dark">{{ number_format($order->shipping_fee, 0, ',', '.') }} ₫</span>
                                @else
                                    <span class="text-success fw-bold"><i class="fa-solid fa-truck-fast me-1"></i>Miễn phí</span>
                                @endif
                            </div>
                            @if($order->discount_amount > 0)
                                <div class="d-flex justify-content-between py-1 text-danger small">
                                    <span>Voucher giảm giá:</span>
                                    <span class="fw-bold">-{{ number_format($order->discount_amount, 0, ',', '.') }} ₫</span>
                                </div>
                            @endif
                            <hr class="my-2">
                            <div class="d-flex justify-content-between py-2 align-items-center">
                                <span class="fw-bold text-dark">Tổng tiền thanh toán:</span>
                                <span class="fw-bold text-danger fs-5">{{ number_format($order->total_amount, 0, ',', '.') }} ₫</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 2: Ghi chú của khách hàng khi đặt -->
            @if($order->customer_notes)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3 fw-bold text-dark">
                        <i class="fa-regular fa-comment-dots text-primary me-2"></i>Ghi chú của khách hàng
                    </div>
                    <div class="card-body p-3 bg-light">
                        <p class="mb-0 text-dark fst-italic">"{{ $order->customer_notes }}"</p>
                    </div>
                </div>
            @endif

        </div>

        <!-- RIGHT COLUMN: Cập nhật Trạng thái & Thông tin giao nhận (4 Cột) -->
        <div class="col-12 col-lg-4">

            <!-- Card 1: Cập nhật Trạng thái Đơn hàng & Xử lý Kho -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 fw-bold text-dark">
                    <i class="fa-solid fa-list-check text-primary me-2"></i>Xử lý & Cập nhật đơn hàng
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.orders.status.update', $order->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Trạng thái đơn hàng -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Trạng thái đơn hàng <span class="text-danger">*</span></label>
                            <select name="order_status" class="form-select form-select-lg">
                                <option value="pending" {{ $order->order_status === 'pending' ? 'selected' : '' }}>⏳ Chờ xác nhận</option>
                                <option value="processing" {{ $order->order_status === 'processing' ? 'selected' : '' }}>📦 Đang đóng gói / xử lý</option>
                                <option value="shipping" {{ $order->order_status === 'shipping' ? 'selected' : '' }}>🚚 Đang giao hàng</option>
                                <option value="completed" {{ $order->order_status === 'completed' ? 'selected' : '' }}>✅ Đã giao / Hoàn thành</option>
                                <option value="cancelled" {{ $order->order_status === 'cancelled' ? 'selected' : '' }}>❌ Đã hủy đơn hàng</option>
                            </select>
                        </div>

                        <!-- Trạng thái thanh toán -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Trạng thái thanh toán <span class="text-danger">*</span></label>
                            <select name="payment_status" class="form-select">
                                <option value="unpaid" {{ $order->payment_status === 'unpaid' ? 'selected' : '' }}>⚪ Chưa thanh toán</option>
                                <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>🟢 Đã thanh toán</option>
                                <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>🔴 Thanh toán thất bại</option>
                                <option value="refunded" {{ $order->payment_status === 'refunded' ? 'selected' : '' }}>🔄 Đã hoàn tiền</option>
                            </select>
                        </div>

                        <!-- Hộp cảnh báo cơ chế hoàn trả kho tự động -->
                        <div class="p-3 bg-light rounded-3 border mb-4">
                            <div class="d-flex gap-2">
                                <i class="fa-solid fa-circle-info text-info mt-1"></i>
                                <div class="small text-muted">
                                    <strong>Cơ chế tự động:</strong> Khi bạn chuyển trạng thái sang <strong>"Đã hủy"</strong>, hệ thống sẽ tự động cộng hoàn trả lại số lượng từng biến thể vào kho hàng.
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 fw-semibold shadow-sm">
                            <i class="fa-solid fa-floppy-disk me-2"></i>Lưu cập nhật
                        </button>
                    </form>
                </div>
            </div>

            <!-- Card 2: Thông tin Người nhận hàng -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 fw-bold text-dark">
                    <i class="fa-regular fa-user text-primary me-2"></i>Thông tin Khách hàng
                </div>
                <div class="card-body p-3">
                    <div class="mb-3 pb-3 border-bottom">
                        <div class="text-muted small">Người nhận hàng:</div>
                        <div class="fw-bold text-dark fs-6">{{ $order->customer_name }}</div>
                        @if($order->user)
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill small mt-1">Tài khoản thành viên</span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary rounded-pill small mt-1">Khách mua vãng lai</span>
                        @endif
                    </div>

                    <div class="mb-3 pb-3 border-bottom">
                        <div class="text-muted small">Số điện thoại:</div>
                        <div class="fw-semibold text-dark">
                            <a href="tel:{{ $order->customer_phone }}" class="text-decoration-none text-dark">
                                <i class="fa-solid fa-phone me-1 text-primary"></i>{{ $order->customer_phone }}
                            </a>
                        </div>
                    </div>

                    <div class="mb-3 pb-3 border-bottom">
                        <div class="text-muted small">Email:</div>
                        <div class="text-dark">{{ $order->customer_email }}</div>
                    </div>

                    <div class="mb-3 pb-3 border-bottom">
                        <div class="text-muted small">Địa chỉ nhận hàng:</div>
                        <div class="fw-semibold text-dark mb-1">{{ $order->customer_address }}</div>
                        <div class="small text-muted">
                            {{ $order->customer_district ? $order->customer_district . ', ' : '' }}{{ $order->customer_city }}
                        </div>
                    </div>

                    <div>
                        <div class="text-muted small">Phương thức thanh toán:</div>
                        <div class="fw-bold text-dark">
                            @if($order->payment_method === 'cod')
                                💵 Thanh toán khi nhận hàng (COD)
                            @elseif($order->payment_method === 'vietqr')
                                🏦 Chuyển khoản VietQR Ngân hàng
                            @elseif($order->payment_method === 'vnpay')
                                💳 Cổng thanh toán VNPAY
                            @else
                                {{ strtoupper($order->payment_method) }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection
