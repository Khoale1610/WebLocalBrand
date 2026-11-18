@extends('layouts.app')

@section('title', 'Lịch Sử Đơn Hàng — OWEN STUDIO')

@section('content')
<div class="container py-4 py-lg-5 account-page">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb mb-0" style="font-size: 13px;">
            <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none text-muted">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('account.index') }}" class="text-decoration-none text-muted">Tài khoản</a></li>
            <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Lịch sử đơn hàng</li>
        </ol>
    </nav>

    <div class="row g-4 align-items-start">
        <!-- Sidebar tài khoản -->
        <div class="col-lg-3 col-md-4">
            <div class="acc-sidebar-box">
                <div class="acc-user-header">
                    <div class="acc-avatar">
                        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                    </div>
                    <div class="overflow-hidden">
                        <div class="fw-semibold text-dark text-truncate" style="font-size: 14px;">{{ auth()->user()->name ?? 'Khách hàng' }}</div>
                        <div class="text-muted text-truncate" style="font-size: 12px;">{{ auth()->user()->email ?? '' }}</div>
                    </div>
                </div>

                <div class="acc-nav-list">
                    <a href="{{ route('account.index') }}" class="acc-nav-link">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="square" stroke-linejoin="miter">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        <span>Tổng quan tài khoản</span>
                    </a>
                    <a href="{{ route('account.orders') }}" class="acc-nav-link active">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="square" stroke-linejoin="miter">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                        </svg>
                        <span>Lịch sử đơn hàng</span>
                    </a>
                    <a href="{{ route('account.wishlist.index') }}" class="acc-nav-link">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="square" stroke-linejoin="miter">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                        </svg>
                        <span>Sản phẩm yêu thích</span>
                    </a>
                    <a href="{{ route('account.addresses') }}" class="acc-nav-link">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="square" stroke-linejoin="miter">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                        <span>Sổ địa chỉ</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Danh sách đơn hàng -->
        <div class="col-lg-9 col-md-8">
            <div class="acc-content-box">
                <div class="acc-section-header">
                    <div>
                        <span class="acc-kicker">ORDER ARCHIVE</span>
                        <h1 class="acc-title">Lịch sử mua hàng</h1>
                    </div>
                    <span class="text-muted" style="font-size: 13px;">Tổng cộng: <strong class="text-dark">{{ $orders->total() }}</strong> đơn hàng</span>
                </div>

                @forelse($orders as $order)
                    <div class="border mb-4" style="border-color: #E5E7EB !important;">
                        <div class="d-flex flex-wrap justify-content-between align-items-center py-2 px-3 gap-2 border-bottom" style="background-color: #FAFAFA; border-color: #E5E7EB !important; font-size: 13px;">
                            <div>
                                <span class="text-muted">Mã đơn:</span>
                                <a href="{{ route('checkout.success', $order->order_code) }}" class="fw-semibold text-decoration-none ms-1" style="color: #C83E3B;">
                                    #{{ $order->order_code ?? $order->id }}
                                </a>
                                <span class="text-muted mx-2">|</span>
                                <span class="text-muted">Ngày đặt: {{ $order->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge rounded-0 border" style="background-color: #111111; color: #FFFFFF; font-size: 11px;">
                                    {{ $order->order_status_label }}
                                </span>
                                <span class="badge rounded-0 border" style="background-color: #FFFFFF; color: #4B5563; border-color: #E5E7EB !important; font-size: 11px;">
                                    {{ $order->payment_status === 'paid' ? 'Đã thanh toán' : 'Chờ thanh toán' }}
                                </span>
                            </div>
                        </div>

                        <div class="p-3">
                            <div class="table-responsive">
                                <table class="table table-borderless align-middle mb-0">
                                    <tbody>
                                        @foreach($order->orderItems as $item)
                                            <tr class="border-bottom" style="border-color: #F3F4F6 !important;">
                                                <td style="width: 60px;" class="ps-0 py-2">
                                                    @if($item->product)
                                                        <img src="{{ $item->product->image_url }}"
                                                             alt="{{ $item->product_name }}"
                                                             class="border rounded-0"
                                                             style="width: 48px; height: 62px; object-fit: cover;">
                                                    @else
                                                        <div class="border d-flex align-items-center justify-content-center text-muted"
                                                             style="width: 48px; height: 62px; background-color: #FAFAFA;">
                                                            <i class="fas fa-shirt"></i>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td class="py-2">
                                                    <div class="fw-semibold text-dark" style="font-size: 13.5px;">{{ $item->product_name }}</div>
                                                    @if(!empty($item->variant_info))
                                                        <div class="text-muted" style="font-size: 12.5px;">{{ $item->variant_info }}</div>
                                                    @endif
                                                    <div class="text-muted" style="font-size: 12.5px;">SL: x{{ $item->quantity }}</div>
                                                </td>
                                                <td class="text-end fw-semibold text-dark pe-0 py-2" style="font-size: 13.5px; white-space: nowrap;">
                                                    {{ number_format($item->total_price, 0, ',', '.') }}đ
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex flex-wrap justify-content-between align-items-end mt-3 pt-2">
                                <div class="text-muted" style="font-size: 12.5px;">
                                    <div>Người nhận: <strong class="text-dark">{{ $order->customer_name }}</strong> ({{ $order->customer_phone }})</div>
                                    <div class="text-truncate" style="max-width: 440px;">Địa chỉ: {{ $order->customer_address }}</div>
                                </div>
                                <div class="text-end mt-2 mt-md-0">
                                    @if($order->discount_amount > 0)
                                        <div style="font-size: 12.5px; color: #C83E3B;">Voucher: -{{ number_format($order->discount_amount, 0, ',', '.') }}đ</div>
                                    @endif
                                    <div class="text-muted" style="font-size: 12.5px;">Phí vận chuyển: {{ $order->shipping_fee > 0 ? number_format($order->shipping_fee, 0, ',', '.') . 'đ' : 'Miễn phí' }}</div>
                                    <div class="mt-1">
                                        <span style="font-size: 13px; color: #4B5563;">Tổng thanh toán:</span>
                                        <span class="fw-semibold ms-1" style="font-size: 17px; color: #C83E3B;">{{ number_format($order->total_amount, 0, ',', '.') }}đ</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5 border" style="border-color: #E5E7EB !important;">
                        <h6 class="fw-semibold text-dark mb-2" style="font-size: 16px;">Bạn chưa có đơn hàng nào</h6>
                        <p class="text-muted mb-4" style="font-size: 13.5px;">Khám phá các thiết kế thời trang mới nhất của OWEN STUDIO ngay hôm nay.</p>
                        <a href="{{ url('/') }}" class="btn-acc-primary">
                            MUA SẮM NGAY
                        </a>
                    </div>
                @endforelse

                @if($orders->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $orders->links('pagination::bootstrap-4') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
