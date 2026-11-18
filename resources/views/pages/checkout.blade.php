@extends('layouts.app')

@section('title', 'Thanh Toán Đơn Hàng — OWEN STUDIO')

@push('styles')
<style>
    /* OWEN STUDIO — Minimalist Square Checkout Design System */
    .checkout-page {
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

    /* Section Box & Headings */
    .co-box {
        background-color: #FFFFFF;
        border: 1px solid #E5E7EB;
        border-radius: 0px;
        padding: 24px;
    }
    .checkout-step-title {
        font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
        font-size: 16px;
        font-weight: 600;
        color: #111111;
        letter-spacing: 0.02em;
        padding-bottom: 14px;
        margin-bottom: 20px;
        border-bottom: 1px solid #E5E7EB;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    /* Form Controls: Square 0px, 14px labels, clean focus */
    .co-label {
        font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
        font-size: 14px;
        font-weight: 500;
        color: #1F2937;
        margin-bottom: 6px;
        display: block;
    }
    .co-required {
        color: #C83E3B;
    }
    .co-input,
    .co-select,
    .co-textarea {
        font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
        font-size: 14px;
        color: #111111;
        background-color: #FFFFFF;
        border: 1px solid #E5E7EB;
        border-radius: 0px !important;
        padding: 10px 14px;
        transition: border-color 0.15s ease;
        box-shadow: none !important;
    }
    .co-input,
    .co-select {
        height: 42px;
    }
    .co-input::placeholder,
    .co-textarea::placeholder {
        color: #9CA3AF;
        font-size: 13.5px;
    }
    .co-input:focus,
    .co-select:focus,
    .co-textarea:focus {
        border-color: #111111 !important;
        outline: none;
        box-shadow: none !important;
    }
    .co-hint {
        font-size: 12.5px;
        color: #6B7280;
        margin-top: 4px;
        display: block;
    }

    /* Payment Method Option Cards: Square 0px, 1px solid #E5E7EB, active 2px solid #111111 */
    .payment-option-card {
        border: 1px solid #E5E7EB;
        border-radius: 0px !important;
        background-color: #FFFFFF;
        padding: 16px;
        margin-bottom: 12px;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .payment-option-card:last-child {
        margin-bottom: 0;
    }
    .payment-option-card:hover {
        border-color: #111111;
    }
    .payment-option-card.active {
        border: 2px solid #111111;
        background-color: #FFFFFF;
        padding: 15px;
    }
    .payment-option-card input[type="radio"] {
        cursor: pointer;
        width: 16px;
        height: 16px;
        accent-color: #111111;
        border-radius: 0px;
        box-shadow: none !important;
    }
    .payment-option-card input[type="radio"]:checked {
        background-color: #111111;
        border-color: #111111;
    }
    .co-payment-title {
        font-size: 14px;
        font-weight: 600;
        color: #111111;
    }
    .co-payment-tag {
        font-size: 11px;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #4B5563;
        border: 1px solid #E5E7EB;
        background-color: #FAFAFA;
        padding: 2px 8px;
        border-radius: 0px;
    }
    .co-payment-desc {
        font-size: 13px;
        line-height: 1.5;
        color: #6B7280;
        margin-top: 4px;
    }

    /* Order Summary Column */
    .checkout-summary-card {
        background-color: #FFFFFF;
        border: 1px solid #E5E7EB;
        border-radius: 0px !important;
        padding: 24px;
    }
    .checkout-item-img {
        width: 56px;
        height: 72px;
        object-fit: cover;
        object-position: top;
        border-radius: 0px !important;
        border: 1px solid #E5E7EB;
        background-color: #F4F4F5;
        flex-shrink: 0;
    }
    .co-item-name {
        font-size: 13.5px;
        font-weight: 600;
        color: #111111;
        line-height: 1.35;
        margin-bottom: 2px;
    }
    .co-item-meta {
        font-size: 12.5px;
        color: #6B7280;
    }
    .co-summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 13.5px;
        color: #4B5563;
        margin-bottom: 10px;
    }
    .co-total-price {
        font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
        font-size: 20px;
        font-weight: 600;
        color: #C83E3B;
        line-height: 1.2;
    }
    .co-currency {
        font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
        font-size: 18px;
        font-weight: 600;
        text-decoration: none !important;
        margin-left: 1px;
    }

    /* Primary Submit Button: 44px height, 13px font-semibold tracking-wider, #111111, square 0px */
    .btn-co-submit {
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
        transition: all 0.2s ease;
    }
    .btn-co-submit:hover:not(:disabled) {
        background-color: #C83E3B;
        border-color: #C83E3B;
        color: #FFFFFF;
    }
    .btn-co-submit:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    .co-back-link {
        font-size: 13px;
        color: #6B7280;
        text-decoration: none;
        transition: color 0.15s ease;
    }
    .co-back-link:hover {
        color: #111111;
        text-decoration: underline;
    }
</style>
@endpush

@section('content')
<div class="container py-4 py-lg-5 checkout-page max-w-[1200px] mx-auto">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb mb-0" style="font-size: 13px;">
            <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none text-muted">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('cart.index') }}" class="text-decoration-none text-muted">Giỏ hàng</a></li>
            <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Thanh toán</li>
        </ol>
    </nav>

    <!-- Thông báo lỗi Flash -->
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-none mb-4" style="font-size: 13.5px; border-color: #C83E3B; color: #C83E3B; background-color: #FFF5F5;" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show rounded-none mb-4" style="font-size: 13.5px; border-color: #C83E3B; color: #C83E3B; background-color: #FFF5F5;" role="alert">
            <h6 class="fw-semibold mb-2" style="font-size: 14px;">Vui lòng kiểm tra lại thông tin:</h6>
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('checkout.process') }}" method="POST" id="checkoutForm">
        @csrf
        <div class="row g-4 align-items-start">
            <!-- Cột trái: Thông tin nhận hàng & Phương thức thanh toán (col-lg-7) -->
            <div class="col-lg-7">
                <!-- 1. Thông tin giao hàng -->
                <div class="co-box rounded-none mb-4">
                    <div class="checkout-step-title">
                        <span>1. Thông tin nhận hàng</span>
                    </div>

                    <div class="row g-3">
                        <div class="col-12">
                            <label for="customer_name" class="co-label text-[14px] font-medium text-gray-800">Họ và tên người nhận <span class="co-required">*</span></label>
                            <input type="text" class="form-control co-input rounded-none @error('customer_name') is-invalid @enderror"
                                   id="customer_name" name="customer_name"
                                   value="{{ old('customer_name', $user->name ?? '') }}"
                                   placeholder="Ví dụ: Nguyễn Văn An" required>
                            @error('customer_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="customer_phone" class="co-label text-[14px] font-medium text-gray-800">Số điện thoại nhận hàng <span class="co-required">*</span></label>
                            <input type="tel" class="form-control co-input rounded-none @error('customer_phone') is-invalid @enderror"
                                   id="customer_phone" name="customer_phone"
                                   value="{{ old('customer_phone', $user->phone ?? '') }}"
                                   placeholder="Ví dụ: 0987654321 (10 chữ số)"
                                   pattern="0[3|5|7|8|9][0-9]{8}"
                                   maxlength="10"
                                   minlength="10"
                                   title="Số điện thoại di động Việt Nam gồm 10 chữ số (bắt đầu bằng 03, 05, 07, 08, 09)"
                                   required>
                            @error('customer_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <span class="co-hint">Định dạng: 10 chữ số bắt đầu bằng 03, 05, 07, 08, 09</span>
                        </div>

                        <div class="col-md-6">
                            <label for="customer_email" class="co-label text-[14px] font-medium text-gray-800">Địa chỉ Email <span class="co-required">*</span></label>
                            <input type="email" class="form-control co-input rounded-none @error('customer_email') is-invalid @enderror"
                                   id="customer_email" name="customer_email"
                                   value="{{ old('customer_email', $user->email ?? '') }}"
                                   placeholder="Để nhận thông báo đơn hàng" required>
                            @error('customer_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="customer_city" class="co-label text-[14px] font-medium text-gray-800">Tỉnh / Thành phố <span class="co-required">*</span></label>
                            <select class="form-select co-select rounded-none @error('customer_city') is-invalid @enderror" id="customer_city" name="customer_city" required>
                                <option value="">-- Chọn Tỉnh/Thành phố --</option>
                                @php
                                    $cities = ['Hà Nội', 'TP. Hồ Chí Minh', 'Đà Nẵng', 'Hải Phòng', 'Cần Thơ', 'Bình Dương', 'Đồng Nai', 'Bắc Ninh', 'Quảng Ninh', 'Thừa Thiên Huế', 'Khánh Hòa', 'Lâm Đồng'];
                                    $selectedCity = old('customer_city', 'Hà Nội');
                                @endphp
                                @foreach($cities as $city)
                                    <option value="{{ $city }}" {{ $selectedCity == $city ? 'selected' : '' }}>{{ $city }}</option>
                                @endforeach
                            </select>
                            @error('customer_city')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="customer_district" class="co-label text-[14px] font-medium text-gray-800">Quận / Huyện <span class="co-required">*</span></label>
                            <input type="text" class="form-control co-input rounded-none @error('customer_district') is-invalid @enderror"
                                   id="customer_district" name="customer_district"
                                   value="{{ old('customer_district', 'Cầu Giấy') }}"
                                   placeholder="Ví dụ: Quận Cầu Giấy" required>
                            @error('customer_district')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="customer_address" class="co-label text-[14px] font-medium text-gray-800">Địa chỉ chi tiết (Số nhà, đường, phường/xã) <span class="co-required">*</span></label>
                            <input type="text" class="form-control co-input rounded-none @error('customer_address') is-invalid @enderror"
                                   id="customer_address" name="customer_address"
                                   value="{{ old('customer_address', $user->address ?? '') }}"
                                   placeholder="Ví dụ: Số 123 đường Cầu Giấy, Phường Quan Hoa" required>
                            @error('customer_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="customer_notes" class="co-label text-[14px] font-medium text-gray-800">Ghi chú giao hàng (Tùy chọn)</label>
                            <textarea class="form-control co-textarea rounded-none" id="customer_notes" name="customer_notes" rows="2"
                                      placeholder="Ví dụ: Giao vào giờ hành chính, gọi trước khi tới...">{{ old('customer_notes') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- 2. Phương thức thanh toán -->
                <div class="co-box rounded-none">
                    <div class="checkout-step-title">
                        <span>2. Phương thức thanh toán</span>
                    </div>

                    <!-- Lựa chọn COD -->
                    <label class="payment-option-card rounded-none d-flex align-items-start gap-3 active" id="labelPaymentCod">
                        <input type="radio" name="payment_method" value="cod" id="payCod" class="form-check-input mt-1" checked onchange="updatePaymentSelection()">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center justify-content-between gap-2">
                                <span class="co-payment-title d-flex align-items-center gap-2">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#4B5563" stroke-width="1.5" stroke-linecap="square" stroke-linejoin="miter">
                                        <rect x="1" y="3" width="15" height="13"></rect>
                                        <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                                        <circle cx="5.5" cy="18.5" r="2.5"></circle>
                                        <circle cx="18.5" cy="18.5" r="2.5"></circle>
                                    </svg>
                                    Thanh toán khi nhận hàng (COD)
                                </span>
                                <span class="co-payment-tag rounded-none">Phổ biến</span>
                            </div>
                            <div class="co-payment-desc">Thanh toán bằng tiền mặt trực tiếp cho nhân viên giao hàng sau khi kiểm tra sản phẩm.</div>
                        </div>
                    </label>

                    <!-- Lựa chọn VietQR -->
                    <label class="payment-option-card rounded-none d-flex align-items-start gap-3" id="labelPaymentVietqr">
                        <input type="radio" name="payment_method" value="vietqr" id="payVietqr" class="form-check-input mt-1" onchange="updatePaymentSelection()">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center justify-content-between gap-2">
                                <span class="co-payment-title d-flex align-items-center gap-2">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#4B5563" stroke-width="1.5" stroke-linecap="square" stroke-linejoin="miter">
                                        <rect x="3" y="3" width="7" height="7"></rect>
                                        <rect x="14" y="3" width="7" height="7"></rect>
                                        <rect x="14" y="14" width="7" height="7"></rect>
                                        <rect x="3" y="14" width="7" height="7"></rect>
                                    </svg>
                                    Chuyển khoản qua mã VietQR tự động
                                </span>
                                <span class="co-payment-tag rounded-none">Miễn phí</span>
                            </div>
                            <div class="co-payment-desc">Mã QR tự động điền sẵn số tài khoản, số tiền và nội dung chuyển khoản theo mã đơn hàng.</div>
                        </div>
                    </label>

                    <!-- Lựa chọn VNPAY -->
                    <label class="payment-option-card rounded-none d-flex align-items-start gap-3" id="labelPaymentVnpay">
                        <input type="radio" name="payment_method" value="vnpay" id="payVnpay" class="form-check-input mt-1" onchange="updatePaymentSelection()">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center justify-content-between gap-2">
                                <span class="co-payment-title d-flex align-items-center gap-2">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#4B5563" stroke-width="1.5" stroke-linecap="square" stroke-linejoin="miter">
                                        <rect x="1" y="4" width="22" height="16"></rect>
                                        <line x1="1" y1="10" x2="23" y2="10"></line>
                                    </svg>
                                    Cổng thanh toán VNPAY Sandbox
                                </span>
                                <span class="co-payment-tag rounded-none">ATM / Visa / Master</span>
                            </div>
                            <div class="co-payment-desc">Thanh toán trực tuyến an toàn qua cổng VNPAY bằng thẻ nội địa hoặc thẻ quốc tế.</div>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Cột phải: Tóm tắt đơn hàng (col-lg-5) -->
            <div class="col-lg-5">
                <div class="checkout-summary-card rounded-none sticky-top" style="top: 96px;">
                    <div class="checkout-step-title">
                        <span>Tóm tắt đơn hàng</span>
                        <span style="font-size: 13px; font-weight: 400; color: #6B7280;">{{ array_sum(array_column($cart, 'quantity')) }} sản phẩm</span>
                    </div>

                    <!-- Danh sách các sản phẩm -->
                    <div class="checkout-items-list mb-3" style="max-height: 300px; overflow-y: auto;">
                        @foreach($cart as $item)
                            <div class="d-flex align-items-start gap-3 py-3 border-bottom" style="border-color: #F3F4F6 !important;">
                                <img src="{{ $item['image'] ?? 'https://via.placeholder.com/100x130' }}"
                                     alt="{{ $item['name'] }}"
                                     class="checkout-item-img rounded-none">
                                <div class="flex-grow-1 min-w-0">
                                    <div class="co-item-name text-truncate" title="{{ $item['name'] }}">{{ $item['name'] }}</div>
                                    <div class="co-item-meta mb-1">
                                        @if(!empty($item['size'])) Size: {{ $item['size'] }} @endif
                                        @if(!empty($item['color'])) {{ !empty($item['size']) ? ' / ' : '' }}Màu: {{ $item['color'] }} @endif
                                    </div>
                                    <div class="co-item-meta">
                                        SL: {{ $item['quantity'] }} &times; {{ number_format($item['price'], 0, ',', '.') }}đ
                                    </div>
                                </div>
                                <div class="text-end fw-semibold text-dark" style="font-size: 13.5px; white-space: nowrap;">
                                    {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}đ
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Chi tiết tính tiền -->
                    <div class="pt-2">
                        <div class="co-summary-row">
                            <span>Tạm tính</span>
                            <span class="fw-medium text-dark">{{ number_format($subtotal, 0, ',', '.') }}đ</span>
                        </div>

                        <div class="co-summary-row">
                            <span>Phí vận chuyển</span>
                            <span class="fw-medium text-dark">
                                {{ $shippingFee == 0 ? 'Miễn phí' : number_format($shippingFee, 0, ',', '.') . 'đ' }}
                            </span>
                        </div>

                        @if(!empty($discountAmount) && $discountAmount > 0)
                            <div class="co-summary-row" style="color: #C83E3B;">
                                <span>Mã giảm giá ({{ $voucherSession['code'] ?? 'Voucher' }})</span>
                                <span class="fw-semibold">-{{ number_format($discountAmount, 0, ',', '.') }}đ</span>
                            </div>
                        @endif
                    </div>

                    <div class="d-flex justify-content-between align-items-baseline pt-3 mt-2 mb-4" style="border-top: 1px solid #E5E7EB;">
                        <span style="font-size: 14px; font-weight: 600; color: #111111;">Tổng thanh toán</span>
                        <span class="co-total-price text-[20px] font-semibold text-[#C83E3B]">
                            {{ number_format($total, 0, ',', '.') }}<span class="co-currency text-[18px]">đ</span>
                        </span>
                    </div>

                    <button type="submit" class="btn-co-submit h-[44px] text-[13px] font-semibold tracking-wider rounded-none mb-3" id="btnSubmitOrder">
                        XÁC NHẬN ĐẶT HÀNG
                    </button>

                    <div class="text-center mb-3">
                        <a href="{{ route('cart.index') }}" class="co-back-link">
                            &larr; Quay lại giỏ hàng
                        </a>
                    </div>

                    <!-- Bảo chứng tối giản (SVG nét mảnh 1.5, màu #4B5563) -->
                    <div class="pt-3 text-center" style="border-top: 1px solid #E5E7EB; font-size: 12.5px; color: #4B5563;">
                        <div class="d-flex justify-content-center align-items-center gap-4">
                            <span class="d-inline-flex align-items-center gap-1">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#4B5563" stroke-width="1.5" stroke-linecap="square" stroke-linejoin="miter">
                                    <rect x="3" y="11" width="18" height="11"></rect>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                </svg>
                                Bảo mật thanh toán SSL
                            </span>
                            <span class="d-inline-flex align-items-center gap-1">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#4B5563" stroke-width="1.5" stroke-linecap="square" stroke-linejoin="miter">
                                    <polyline points="23 4 23 10 17 10"></polyline>
                                    <polyline points="1 20 1 14 7 14"></polyline>
                                    <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path>
                                </svg>
                                Đổi trả trong 30 ngày
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    function updatePaymentSelection() {
        const radios = document.querySelectorAll('input[name="payment_method"]');
        radios.forEach(radio => {
            const card = radio.closest('.payment-option-card');
            if (radio.checked) {
                card.classList.add('active');
            } else {
                card.classList.remove('active');
            }
        });
    }

    document.getElementById('checkoutForm').addEventListener('submit', function(e) {
        const phoneInput = document.getElementById('customer_phone');
        const phoneVal = phoneInput.value.trim();
        const phoneRegex = /^(0[3|5|7|8|9])[0-9]{8}$/;

        if (!phoneRegex.test(phoneVal)) {
            e.preventDefault();
            alert('Số điện thoại không hợp lệ! Vui lòng nhập số điện thoại di động Việt Nam gồm 10 chữ số (bắt đầu bằng 03, 05, 07, 08, 09).');
            phoneInput.focus();
            phoneInput.classList.add('is-invalid');
            return false;
        }

        const btn = document.getElementById('btnSubmitOrder');
        btn.disabled = true;
        btn.textContent = 'ĐANG XỬ LÝ ĐƠN HÀNG...';
    });
</script>
@endpush
