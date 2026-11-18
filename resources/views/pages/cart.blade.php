@extends('layouts.app')

@section('title', 'Giỏ Hàng — OWEN STUDIO')

@push('styles')
<style>
    /* OWEN STUDIO — Minimalist Square Cart Design System */
    .cart-page {
        font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        max-width: 1200px;
        margin-left: auto;
        margin-right: auto;
    }
    .rounded-none {
        border-radius: 0px !important;
    }
    .tracking-wider {
        letter-spacing: 0.08em !important;
    }

    /* Cart Containers */
    .cart-box {
        background-color: #FFFFFF;
        border: 1px solid #E5E7EB;
        border-radius: 0px;
    }
    .cart-header {
        padding: 18px 24px;
        border-bottom: 1px solid #E5E7EB;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .cart-header-title {
        font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
        font-size: 16px;
        font-weight: 600;
        color: #111111;
        margin: 0;
    }
    .btn-clear-cart {
        background: transparent;
        border: none;
        padding: 4px 0;
        font-size: 13px;
        font-weight: 500;
        color: #6B7280;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: color 0.15s ease;
    }
    .btn-clear-cart:hover {
        color: #C83E3B;
    }

    /* Table Styling */
    .cart-table {
        margin-bottom: 0;
    }
    .cart-table thead th {
        background-color: #FAFAFA;
        border-bottom: 1px solid #E5E7EB;
        font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #6B7280;
        padding: 12px 16px;
        white-space: nowrap;
    }
    .cart-table tbody td {
        padding: 18px 16px;
        border-bottom: 1px solid #F3F4F6;
        vertical-align: middle;
    }
    .cart-item-img {
        width: 68px;
        height: 85px;
        object-fit: cover;
        object-position: top;
        border-radius: 0px !important;
        border: 1px solid #E5E7EB;
        background-color: #F4F4F5;
        flex-shrink: 0;
    }
    .cart-item-name {
        font-size: 14px;
        font-weight: 600;
        color: #111111;
        text-decoration: none;
        line-height: 1.4;
        display: inline-block;
        margin-bottom: 4px;
        transition: color 0.15s ease;
    }
    .cart-item-name:hover {
        color: #C83E3B;
    }
    .cart-variant-tag {
        display: inline-block;
        font-size: 12px;
        font-weight: 500;
        color: #4B5563;
        background-color: #FAFAFA;
        border: 1px solid #E5E7EB;
        padding: 2px 8px;
        border-radius: 0px;
    }
    .cart-sku-text {
        font-size: 12px;
        color: #9CA3AF;
        margin-top: 4px;
    }
    .cart-unit-price {
        font-size: 14px;
        font-weight: 500;
        color: #374151;
        white-space: nowrap;
    }
    .cart-subtotal-price {
        font-size: 14.5px;
        font-weight: 600;
        color: #C83E3B;
        white-space: nowrap;
    }

    /* Square Quantity Selector */
    .cart-qty-group {
        display: inline-flex;
        align-items: stretch;
        border: 1px solid #E5E7EB;
        background-color: #FFFFFF;
        height: 36px;
        border-radius: 0px;
    }
    .cart-qty-btn {
        width: 32px;
        border: none;
        background-color: #FFFFFF;
        color: #111111;
        font-size: 14px;
        font-weight: 500;
        border-radius: 0px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background-color 0.15s ease;
    }
    .cart-qty-btn:hover {
        background-color: #F4F4F5;
    }
    .cart-qty-input {
        width: 42px;
        border: none;
        border-left: 1px solid #E5E7EB;
        border-right: 1px solid #E5E7EB;
        text-align: center;
        font-weight: 600;
        font-size: 13.5px;
        color: #111111;
        background-color: #FFFFFF;
        border-radius: 0px;
        outline: none;
        -moz-appearance: textfield;
    }
    .cart-qty-input::-webkit-outer-spin-button,
    .cart-qty-input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    .cart-stock-note {
        font-size: 12px;
        color: #6B7280;
        margin-top: 4px;
    }

    /* Remove Item Button */
    .btn-remove-item {
        width: 32px;
        height: 32px;
        background: transparent;
        border: 1px solid transparent;
        border-radius: 0px !important;
        color: #6B7280;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        box-shadow: none !important;
        transition: all 0.15s ease;
    }
    .btn-remove-item:hover {
        color: #C83E3B;
        border-color: #E5E7EB;
        background-color: #FAFAFA;
    }

    /* Summary Card & Freeship Bar */
    .summary-card {
        background-color: #FFFFFF;
        border: 1px solid #E5E7EB;
        border-radius: 0px !important;
        padding: 24px;
    }
    .freeship-box {
        background-color: #FAFAFA;
        border: 1px solid #E5E7EB;
        border-radius: 0px !important;
        padding: 14px 16px;
    }
    .freeship-track {
        height: 4px;
        background-color: #E5E7EB;
        border-radius: 0px;
        overflow: hidden;
        margin-top: 10px;
    }
    .freeship-fill {
        height: 100%;
        background-color: #111111;
        transition: width 0.3s ease;
    }

    /* Total Price & Currency */
    .cart-total-price {
        font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
        font-size: 20px;
        font-weight: 600;
        color: #C83E3B;
        line-height: 1.2;
    }
    .cart-currency {
        font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
        font-size: 18px;
        font-weight: 600;
        text-decoration: none !important;
        margin-left: 1px;
    }

    /* Voucher Form */
    .voucher-input {
        height: 40px;
        font-size: 13px;
        border: 1px solid #E5E7EB;
        border-right: none;
        border-radius: 0px !important;
        padding: 0 12px;
        color: #111111;
        box-shadow: none !important;
    }
    .voucher-input:focus {
        border-color: #111111;
        outline: none;
    }
    .btn-voucher-apply {
        height: 40px;
        padding: 0 18px;
        background-color: #111111;
        color: #FFFFFF;
        border: 1px solid #111111;
        border-radius: 0px !important;
        font-size: 12.5px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        white-space: nowrap;
        transition: background-color 0.15s ease;
    }
    .btn-voucher-apply:hover {
        background-color: #333333;
    }

    /* Primary & Secondary CTAs */
    .btn-cart-checkout {
        height: 44px;
        background-color: #111111;
        color: #FFFFFF;
        border: 1px solid #111111;
        border-radius: 0px !important;
        font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
        font-size: 13px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        width: 100%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    .btn-cart-checkout:hover {
        background-color: #C83E3B;
        border-color: #C83E3B;
        color: #FFFFFF;
    }
    .btn-cart-continue {
        height: 40px;
        padding: 0 20px;
        background-color: #FFFFFF;
        color: #111111;
        border: 1px solid #111111;
        border-radius: 0px !important;
        font-size: 12.5px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    .btn-cart-continue:hover {
        background-color: #111111;
        color: #FFFFFF;
    }
</style>
@endpush

@section('content')
<div class="container py-4 py-lg-5 cart-page max-w-[1200px] mx-auto">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb mb-0" style="font-size: 13px;">
            <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none text-muted">Trang chủ</a></li>
            <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Giỏ hàng</li>
        </ol>
    </nav>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="alert alert-dark alert-dismissible fade show rounded-none border-dark mb-4" style="font-size: 13.5px;" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-none mb-4" style="font-size: 13.5px; border-color: #C83E3B; color: #C83E3B; background-color: #FFF5F5;" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Alert thông báo động bằng Javascript -->
    <div id="cartJsAlert" class="d-none alert alert-dismissible fade show rounded-none mb-4" style="font-size: 13.5px;" role="alert">
        <span id="cartJsAlertMessage"></span>
        <button type="button" class="btn-close" onclick="document.getElementById('cartJsAlert').classList.add('d-none')"></button>
    </div>

    @if(empty($cart))
        <!-- Giao diện khi giỏ hàng TRỐNG -->
        <div class="cart-box rounded-none text-center py-5 px-4 my-3">
            <div class="mb-3 d-inline-flex align-items-center justify-content-center" style="width: 56px; height: 56px; border: 1px solid #E5E7EB;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#4B5563" stroke-width="1.5" stroke-linecap="square" stroke-linejoin="miter">
                    <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <path d="M16 10a4 4 0 0 1-8 0"></path>
                </svg>
            </div>
            <h4 class="fw-semibold text-dark mb-2" style="font-size: 20px;">Giỏ hàng của bạn đang trống</h4>
            <p class="text-muted mb-4" style="font-size: 13.5px;">Hãy khám phá các thiết kế áo sơ mi, polo và quần âu mới nhất từ OWEN STUDIO.</p>
            <a href="{{ url('/') }}" class="btn-cart-checkout h-[44px] text-[13px] font-semibold tracking-wider rounded-none" style="max-width: 240px;">
                TIẾP TỤC MUA SẮM
            </a>
        </div>
    @else
        <!-- Giao diện khi CÓ SẢN PHẨM trong giỏ -->
        <div class="row g-4 align-items-start" id="cartContentSection">
            <!-- Cột trái: Bảng danh sách sản phẩm (col-lg-8) -->
            <div class="col-lg-8">
                <div class="cart-box rounded-none">
                    <div class="cart-header">
                        <h1 class="cart-header-title">Sản phẩm trong giỏ (<span id="cartHeaderTotalQty">{{ array_sum(array_column($cart, 'quantity')) }}</span>)</h1>
                        <form action="{{ route('cart.clear') }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa toàn bộ giỏ hàng?');">
                            @csrf
                            <button type="submit" class="btn-clear-cart">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="square" stroke-linejoin="miter">
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                </svg>
                                Xóa tất cả
                            </button>
                        </form>
                    </div>

                    <div class="table-responsive">
                        <table class="table cart-table">
                            <thead>
                                <tr>
                                    <th style="min-width: 260px;" class="ps-4">Sản phẩm</th>
                                    <th class="text-center">Đơn giá</th>
                                    <th class="text-center" style="min-width: 130px;">Số lượng</th>
                                    <th class="text-end">Thành tiền</th>
                                    <th class="text-center pe-4" style="width: 52px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cart as $key => $item)
                                    <tr id="cartRow_{{ $key }}">
                                        <!-- Cột Sản phẩm & Biến thể -->
                                        <td class="ps-4">
                                            <div class="d-flex align-items-start gap-3">
                                                <a href="{{ !empty($item['product_id']) ? route('products.show', $item['product_id']) : url('/') }}">
                                                    <img src="{{ $item['image'] ?? 'https://via.placeholder.com/100x130' }}"
                                                         alt="{{ $item['name'] }}"
                                                         class="cart-item-img rounded-none">
                                                </a>
                                                <div>
                                                    <a href="{{ !empty($item['product_id']) ? route('products.show', $item['product_id']) : url('/') }}" class="cart-item-name">
                                                        {{ $item['name'] }}
                                                    </a>
                                                    <div class="d-flex flex-wrap gap-1 mt-1">
                                                        @if(!empty($item['size']))
                                                            <span class="cart-variant-tag rounded-none">Size: {{ $item['size'] }}</span>
                                                        @endif
                                                        @if(!empty($item['color']))
                                                            <span class="cart-variant-tag rounded-none">Màu: {{ $item['color'] }}</span>
                                                        @endif
                                                    </div>
                                                    @if(!empty($item['sku']))
                                                        <div class="cart-sku-text">SKU: {{ $item['sku'] }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Cột Đơn giá (Không xuống dòng chữ đ) -->
                                        <td class="text-center cart-unit-price">
                                            {{ number_format($item['price'], 0, ',', '.') }}đ
                                        </td>

                                        <!-- Cột Tăng giảm số lượng -->
                                        <td class="text-center">
                                            <div class="cart-qty-group rounded-none mx-auto">
                                                <button class="cart-qty-btn rounded-none" type="button" onclick="changeQty('{{ $key }}', -1)" aria-label="Giảm">&minus;</button>
                                                <input type="number"
                                                       id="qtyInput_{{ $key }}"
                                                       class="cart-qty-input rounded-none"
                                                       value="{{ $item['quantity'] }}"
                                                       min="1"
                                                       max="{{ (isset($item['stock']) && $item['stock'] > 0) ? $item['stock'] : 99 }}"
                                                       onchange="onQtyInputChange('{{ $key }}')">
                                                <button class="cart-qty-btn rounded-none" type="button" onclick="changeQty('{{ $key }}', 1)" aria-label="Tăng">&#43;</button>
                                            </div>
                                            <div class="cart-stock-note">Còn {{ isset($item['stock']) ? $item['stock'] : 'sẵn' }} sp</div>
                                        </td>

                                        <!-- Cột Thành tiền -->
                                        <td class="text-end cart-subtotal-price" id="itemTotalPrice_{{ $key }}">
                                            {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}đ
                                        </td>

                                        <!-- Cột Nút Xóa -->
                                        <td class="text-center pe-4">
                                            <button type="button" class="btn-remove-item rounded-none" title="Xóa sản phẩm" onclick="removeItem('{{ $key }}')">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="square" stroke-linejoin="miter">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="p-3 px-4 border-top d-flex justify-content-between align-items-center" style="border-color: #E5E7EB !important;">
                        <a href="{{ url('/') }}" class="btn-cart-continue rounded-none">
                            &larr; TIẾP TỤC MUA HÀNG
                        </a>
                    </div>
                </div>
            </div>

            <!-- Cột phải: Tóm tắt đơn hàng & Thanh toán (col-lg-4) -->
            <div class="col-lg-4">
                <div class="summary-card rounded-none sticky-top" style="top: 96px;">
                    <h2 class="cart-header-title pb-3 mb-3 border-bottom" style="border-color: #E5E7EB !important;">Tóm tắt đơn hàng</h2>

                    <!-- Hộp tiến trình Freeship tối giản -->
                    <div class="freeship-box rounded-none mb-3">
                        <div class="d-flex align-items-center gap-2">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#4B5563" stroke-width="1.5" stroke-linecap="square" stroke-linejoin="miter" class="flex-shrink-0">
                                <rect x="1" y="3" width="15" height="13"></rect>
                                <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                                <circle cx="5.5" cy="18.5" r="2.5"></circle>
                                <circle cx="18.5" cy="18.5" r="2.5"></circle>
                            </svg>
                            <div style="font-size: 13px; color: #4B5563; line-height: 1.45;" id="freeshipTextMessage">
                                @if($amountNeededForFreeShipping > 0)
                                    Mua thêm <strong style="color: #C83E3B;" id="freeshipAmountNeededText">{{ number_format($amountNeededForFreeShipping, 0, ',', '.') }}đ</strong> để được <strong class="text-dark">Miễn phí vận chuyển</strong>
                                @else
                                    <strong class="text-dark">Đơn hàng đã đủ điều kiện Miễn phí vận chuyển</strong>
                                @endif
                            </div>
                        </div>
                        <div class="freeship-track">
                            <div id="freeshipProgressBar"
                                 class="freeship-fill"
                                 style="width: {{ $freeShippingPercent }}%;"
                                 aria-valuenow="{{ $freeShippingPercent }}"
                                 aria-valuemin="0"
                                 aria-valuemax="100"></div>
                        </div>
                    </div>

                    <!-- Bảng tính tiền chi tiết -->
                    <div class="d-flex justify-content-between mb-2" style="font-size: 13.5px; color: #4B5563;">
                        <span>Tạm tính</span>
                        <span class="fw-medium text-dark" id="cartSubtotalText">{{ number_format($subtotal, 0, ',', '.') }}đ</span>
                    </div>

                    <div class="d-flex justify-content-between mb-2" style="font-size: 13.5px; color: #4B5563;">
                        <span>Phí vận chuyển</span>
                        <span class="fw-medium text-dark" id="cartShippingFeeText">
                            {{ $shippingFee == 0 ? 'Miễn phí' : number_format($shippingFee, 0, ',', '.') . 'đ' }}
                        </span>
                    </div>

                    <div class="d-flex justify-content-between mb-3" style="font-size: 13.5px; color: #4B5563;">
                        <span>Giảm giá voucher</span>
                        <span class="fw-medium" style="color: #C83E3B;">-{{ number_format($discountAmount ?? 0, 0, ',', '.') }}đ</span>
                    </div>

                    <div class="d-flex justify-content-between align-items-baseline pt-3 mb-4" style="border-top: 1px solid #E5E7EB;">
                        <span style="font-size: 14px; font-weight: 600; color: #111111;">Tổng cộng</span>
                        <div class="text-end">
                            <span class="cart-total-price text-[20px] font-semibold text-[#C83E3B]" id="cartTotalAmountText">
                                {{ number_format($total, 0, ',', '.') }}<span class="cart-currency text-[18px]">đ</span>
                            </span>
                            <span class="d-block" style="font-size: 12px; color: #9CA3AF;">(Đã bao gồm VAT)</span>
                        </div>
                    </div>

                    <!-- Form nhập mã giảm giá -->
                    @if(!empty($voucherSession))
                        <div class="d-flex justify-content-between align-items-center p-2 px-3 mb-3 rounded-none" style="background-color: #FAFAFA; border: 1px solid #111111; font-size: 13px;">
                            <div>
                                Mã <strong>{{ $voucherSession['code'] }}</strong>: <span style="color: #C83E3B; font-weight: 600;">-{{ number_format($discountAmount ?? 0, 0, ',', '.') }}đ</span>
                            </div>
                            <form action="{{ route('cart.voucher.remove') }}" method="POST" class="d-inline mb-0">
                                @csrf
                                <button type="submit" class="btn btn-sm p-0 text-decoration-underline" style="font-size: 12px; color: #6B7280;">Gỡ</button>
                            </form>
                        </div>
                    @else
                        <form action="{{ route('cart.voucher.apply') }}" method="POST" class="mb-3">
                            @csrf
                            <div class="d-flex">
                                <input type="text" name="code" class="form-control voucher-input rounded-none text-uppercase" placeholder="Mã giảm giá (VD: OWENNEW)" required>
                                <button class="btn-voucher-apply rounded-none" type="submit">Áp dụng</button>
                            </div>
                        </form>
                    @endif

                    <!-- Nút Tiến hành đặt hàng nằm gọn trong khung Summary -->
                    <a href="{{ route('checkout.index') }}" class="btn-cart-checkout h-[44px] text-[13px] font-semibold tracking-wider rounded-none mb-3">
                        TIẾN HÀNH ĐẶT HÀNG
                    </a>

                    <!-- Cam kết dịch vụ (SVG nét mảnh 1.5, màu #4B5563) -->
                    <div class="pt-3" style="border-top: 1px solid #E5E7EB; font-size: 13px; color: #4B5563;">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#4B5563" stroke-width="1.5" stroke-linecap="square" stroke-linejoin="miter" class="flex-shrink-0">
                                <polyline points="23 4 23 10 17 10"></polyline>
                                <polyline points="1 20 1 14 7 14"></polyline>
                                <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path>
                            </svg>
                            <span>Hỗ trợ đổi trả miễn phí trong vòng 30 ngày.</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#4B5563" stroke-width="1.5" stroke-linecap="square" stroke-linejoin="miter" class="flex-shrink-0">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                                <polyline points="9 12 11 14 15 10"></polyline>
                            </svg>
                            <span>Giao hàng toàn quốc — Kiểm tra trước khi nhận.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    const csrfToken = "{{ csrf_token() }}";

    function showCartAlert(message, type = 'success') {
        const alertBox = document.getElementById('cartJsAlert');
        const alertMsg = document.getElementById('cartJsAlertMessage');
        alertBox.className = `alert alert-${type === 'success' ? 'dark' : 'danger'} alert-dismissible fade show rounded-none mb-4`;
        alertMsg.textContent = message;
        alertBox.classList.remove('d-none');
    }

    function changeQty(key, delta) {
        const input = document.getElementById(`qtyInput_${key}`);
        if (!input) return;

        let currentVal = parseInt(input.value) || 1;
        let maxVal = parseInt(input.getAttribute('max')) || 99;
        let minVal = parseInt(input.getAttribute('min')) || 1;

        let newVal = currentVal + delta;
        if (newVal < minVal) newVal = minVal;
        if (newVal > maxVal) {
            showCartAlert(`Bạn chỉ có thể mua tối đa ${maxVal} sản phẩm này.`, 'warning');
            return;
        }

        input.value = newVal;
        updateCartItem(key, newVal);
    }

    function onQtyInputChange(key) {
        const input = document.getElementById(`qtyInput_${key}`);
        if (!input) return;

        let val = parseInt(input.value) || 1;
        let maxVal = parseInt(input.getAttribute('max')) || 99;
        let minVal = parseInt(input.getAttribute('min')) || 1;

        if (val < minVal) val = minVal;
        if (val > maxVal) val = maxVal;
        input.value = val;

        updateCartItem(key, val);
    }

    function updateCartItem(key, quantity) {
        fetch("{{ route('cart.update') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken,
                "X-Requested-With": "XMLHttpRequest",
                "Accept": "application/json"
            },
            body: JSON.stringify({
                key: key,
                quantity: quantity
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const itemTotalElem = document.getElementById(`itemTotalPrice_${key}`);
                if (itemTotalElem) itemTotalElem.textContent = data.item_total;

                document.getElementById('cartSubtotalText').textContent = data.subtotal;
                document.getElementById('cartShippingFeeText').textContent = data.shipping_fee;
                document.getElementById('cartTotalAmountText').innerHTML = data.total;

                const badge = document.getElementById('cartCountBadge');
                if (badge) badge.textContent = data.cart_count;
                const headerQty = document.getElementById('cartHeaderTotalQty');
                if (headerQty) headerQty.textContent = data.cart_count;

                updateFreeshipBar(data.is_freeship, data.amount_needed, data.freeship_percent);
            } else {
                showCartAlert(data.message || "Không thể cập nhật giỏ hàng.", 'danger');
            }
        })
        .catch(err => {
            console.error(err);
            showCartAlert("Đã xảy ra lỗi khi cập nhật giỏ hàng. Vui lòng thử lại!", "danger");
        });
    }

    function removeItem(key) {
        if (!confirm("Bạn có chắc muốn xóa sản phẩm này khỏi giỏ hàng?")) {
            return;
        }

        const url = "{{ url('/cart/remove') }}/" + encodeURIComponent(key);

        fetch(url, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken,
                "X-Requested-With": "XMLHttpRequest",
                "Accept": "application/json"
            },
            body: JSON.stringify({ _method: "DELETE" })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const row = document.getElementById(`cartRow_${key}`);
                if (row) row.remove();

                const badge = document.getElementById('cartCountBadge');
                if (badge) badge.textContent = data.cart_count;

                if (data.cart_is_empty) {
                    location.reload();
                    return;
                }

                const headerQty = document.getElementById('cartHeaderTotalQty');
                if (headerQty) headerQty.textContent = data.cart_count;

                document.getElementById('cartSubtotalText').textContent = data.subtotal;
                document.getElementById('cartShippingFeeText').textContent = data.shipping_fee;
                document.getElementById('cartTotalAmountText').innerHTML = data.total;

                updateFreeshipBar(data.is_freeship, data.amount_needed, data.freeship_percent);
                showCartAlert(data.message, 'success');
            } else {
                showCartAlert(data.message || "Không thể xóa sản phẩm.", 'danger');
            }
        })
        .catch(err => {
            console.error(err);
            showCartAlert("Không thể xóa sản phẩm. Vui lòng thử lại!", "danger");
        });
    }

    function updateFreeshipBar(isFreeship, amountNeeded, percent) {
        const textElem = document.getElementById('freeshipTextMessage');
        const barElem = document.getElementById('freeshipProgressBar');

        if (barElem) {
            barElem.style.width = `${percent}%`;
            barElem.setAttribute('aria-valuenow', percent);
        }

        if (textElem) {
            if (isFreeship) {
                textElem.innerHTML = `<strong class="text-dark">Đơn hàng đã đủ điều kiện Miễn phí vận chuyển</strong>`;
            } else {
                const formattedNeeded = new Intl.NumberFormat('vi-VN').format(amountNeeded) + 'đ';
                textElem.innerHTML = `Mua thêm <strong style="color: #C83E3B;">${formattedNeeded}</strong> để được <strong class="text-dark">Miễn phí vận chuyển</strong>`;
            }
        }
    }
</script>
@endpush
