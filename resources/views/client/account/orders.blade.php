@extends('layouts.app')

@section('title', 'Lịch Sử Đơn Hàng - WebLocalBrand')

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="list-group shadow-sm">
                <a href="{{ route('account.index') }}" class="list-group-item list-group-item-action">
                    <i class="fas fa-user-circle me-2"></i>Tổng quan tài khoản
                </a>
                <a href="{{ route('account.orders') }}" class="list-group-item list-group-item-action active">
                    <i class="fas fa-box-open me-2"></i>Lịch sử đơn hàng
                </a>
                <a href="{{ route('account.wishlist.index') }}" class="list-group-item list-group-item-action">
                    <i class="fas fa-heart me-2"></i>Sản phẩm yêu thích
                </a>
                <a href="{{ route('account.addresses') }}" class="list-group-item list-group-item-action">
                    <i class="fas fa-map-marker-alt me-2"></i>Sổ địa chỉ
                </a>
            </div>
        </div>

        <div class="col-md-9">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fw-bold mb-0"><i class="fas fa-receipt me-2 text-danger"></i>Lịch Sử Mua Hàng</h4>
                        <span class="badge bg-light text-dark border">{{ $orders->total() }} đơn hàng</span>
                    </div>

                    @forelse($orders as $order)
                        <div class="card border rounded-3 mb-4 shadow-sm">
                            <div class="card-header bg-light d-flex flex-wrap justify-content-between align-items-center py-2 px-3 gap-2">
                                <div>
                                    <span class="text-muted small">Mã đơn:</span>
                                    <strong class="text-dark">{{ $order->order_code ?? '#' . $order->id }}</strong>
                                    <span class="text-muted small ms-2">| Ngày đặt: {{ $order->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    {!! $order->order_status_badge !!}
                                    {!! $order->payment_status_badge !!}
                                </div>
                            </div>
                            <div class="card-body p-3">
                                <div class="table-responsive">
                                    <table class="table table-borderless align-middle mb-0">
                                        <tbody>
                                            @foreach($order->orderItems as $item)
                                                <tr class="border-bottom">
                                                    <td style="width: 60px;">
                                                        @php
                                                            $img = $item->product ? ($item->product->image ?? null) : null;
                                                        @endphp
                                                        @if($img)
                                                            <img src="{{ Str::startsWith($img, 'http') ? $img : asset('storage/' . $img) }}" 
                                                                 alt="{{ $item->product_name }}" 
                                                                 class="rounded" 
                                                                 style="width: 50px; height: 60px; object-fit: cover;">
                                                        @else
                                                            <div class="bg-light rounded d-flex align-items-center justify-content-center text-muted" 
                                                                 style="width: 50px; height: 60px;">
                                                                <i class="fas fa-shirt"></i>
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="fw-semibold text-dark">{{ $item->product_name }}</div>
                                                        @if(!empty($item->variant_info))
                                                            <small class="text-muted d-block">{{ $item->variant_info }}</small>
                                                        @endif
                                                        <small class="text-muted">SL: x{{ $item->quantity }}</small>
                                                    </td>
                                                    <td class="text-end fw-semibold text-danger">
                                                        {{ number_format($item->total_price, 0, ',', '.') }} ₫
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="d-flex flex-wrap justify-content-between align-items-center mt-3 pt-2">
                                    <div class="small text-muted mb-2 mb-md-0">
                                        <span>Người nhận: <strong>{{ $order->customer_name }}</strong> ({{ $order->customer_phone }})</span><br>
                                        <span class="text-truncate d-inline-block" style="max-width: 450px;">{{ $order->customer_address }}</span>
                                    </div>
                                    <div class="text-end">
                                        @if($order->discount_amount > 0)
                                            <div class="small text-success">Giảm giá voucher: -{{ number_format($order->discount_amount, 0, ',', '.') }} ₫</div>
                                        @endif
                                        <div class="small text-muted">Phí ship: {{ $order->shipping_fee > 0 ? number_format($order->shipping_fee, 0, ',', '.') . ' ₫' : 'Miễn phí' }}</div>
                                        <div class="fs-5 fw-bold text-danger">
                                            Tổng thanh toán: {{ number_format($order->total_amount, 0, ',', '.') }} ₫
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="fas fa-box-open fa-3x text-muted mb-3 d-block"></i>
                            <h5 class="fw-semibold text-dark">Bạn chưa có đơn hàng nào</h5>
                            <p class="text-muted small">Khám phá các sản phẩm thời trang mới nhất của Local Brand ngay hôm nay!</p>
                            <a href="{{ url('/') }}" class="btn btn-dark btn-sm mt-2">
                                <i class="fas fa-shopping-bag me-1"></i> Mua sắm ngay
                            </a>
                        </div>
                    @endforelse

                    <div class="mt-3">
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
