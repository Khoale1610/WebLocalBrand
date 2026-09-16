@extends('layouts.app')

@section('title', 'Thanh Toán Đơn Hàng - WebLocalBrand')

@push('styles')
<style>
    .checkout-step-title {
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 1.25rem;
        color: #111827;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .payment-option-card {
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 0.75rem;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
    }
    .payment-option-card:hover {
        border-color: #9ca3af;
        background-color: #fafafa;
    }
    .payment-option-card.active {
        border-color: #dc2626;
        background-color: #fff5f5;
    }
    .payment-option-card input[type="radio"] {
        cursor: pointer;
    }
    .checkout-summary-card {
        background-color: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
    }
    .checkout-item-img {
        width: 64px;
        height: 64px;
        object-fit: cover;
        border-radius: 6px;
    }
</style>
@endpush

@section('content')
<div class="container py-4 py-lg-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none text-muted">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('cart.index') }}" class="text-decoration-none text-muted">Giỏ hàng</a></li>
            <li class="breadcrumb-item active fw-semibold text-dark" aria-current="page">Thanh toán</li>
        </ol>
    </nav>

    <!-- Thông báo lỗi Flash -->
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <h6 class="fw-bold mb-2"><i class="fas fa-exclamation-triangle me-2"></i>Vui lòng kiểm tra lại thông tin:</h6>
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
        <div class="row g-4">
            <!-- Cột trái: Thông tin nhận hàng & Thanh toán (col-lg-7) -->
            <div class="col-lg-7">
                <!-- 1. Thông tin giao hàng -->
                <div class="card border-0 shadow-sm rounded-3 p-4 mb-4">
                    <div class="checkout-step-title">
                        <i class="fas fa-map-marker-alt text-danger"></i>
                        <span>1. Thông Tin Nhận Hàng</span>
                    </div>

                    <div class="row g-3">
                        <div class="col-12">
                            <label for="customer_name" class="form-label fw-semibold">Họ và tên người nhận <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('customer_name') is-invalid @enderror" 
                                   id="customer_name" name="customer_name" 
                                   value="{{ old('customer_name', $user->name ?? '') }}" 
                                   placeholder="Ví dụ: Nguyễn Văn An" required>
                            @error('customer_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="customer_phone" class="form-label fw-semibold">Số điện thoại nhận hàng <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control @error('customer_phone') is-invalid @enderror" 
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
                            <small class="text-muted" style="font-size: 0.75rem;">Định dạng: 10 chữ số bắt đầu bằng 03, 05, 07, 08, 09</small>
                        </div>

                        <div class="col-md-6">
                            <label for="customer_email" class="form-label fw-semibold">Địa chỉ Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('customer_email') is-invalid @enderror" 
                                   id="customer_email" name="customer_email" 
                                   value="{{ old('customer_email', $user->email ?? '') }}" 
                                   placeholder="Để nhận thông báo đơn hàng" required>
                            @error('customer_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="customer_city" class="form-label fw-semibold">Tỉnh / Thành phố <span class="text-danger">*</span></label>
                            <select class="form-select @error('customer_city') is-invalid @enderror" id="customer_city" name="customer_city" required>
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
                            <label for="customer_district" class="form-label fw-semibold">Quận / Huyện <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('customer_district') is-invalid @enderror" 
                                   id="customer_district" name="customer_district" 
                                   value="{{ old('customer_district', 'Cầu Giấy') }}" 
                                   placeholder="Ví dụ: Quận Cầu Giấy" required>
                            @error('customer_district')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="customer_address" class="form-label fw-semibold">Địa chỉ chi tiết (Số nhà, đường, ngõ) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('customer_address') is-invalid @enderror" 
                                   id="customer_address" name="customer_address" 
                                   value="{{ old('customer_address', $user->address ?? '') }}" 
                                   placeholder="Ví dụ: Số 123 đường Cầu Giấy, Phường Quan Hoa" required>
                            @error('customer_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="customer_notes" class="form-label fw-semibold">Ghi chú giao hàng (Tùy chọn)</label>
                            <textarea class="form-control" id="customer_notes" name="customer_notes" rows="2" 
                                      placeholder="Ví dụ: Giao vào giờ hành chính, gọi trước khi tới...">{{ old('customer_notes') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- 2. Phương thức thanh toán -->
                <div class="card border-0 shadow-sm rounded-3 p-4">
                    <div class="checkout-step-title">
                        <i class="fas fa-wallet text-danger"></i>
                        <span>2. Phương Thức Thanh Toán</span>
                    </div>

                    <!-- Lựa chọn COD -->
                    <label class="payment-option-card d-flex align-items-center gap-3 active" id="labelPaymentCod">
                        <input type="radio" name="payment_method" value="cod" id="payCod" class="form-check-input mt-0" checked onchange="updatePaymentSelection()">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="fw-bold text-dark"><i class="fas fa-truck me-2 text-success"></i>Thanh toán khi nhận hàng (COD)</span>
                                <span class="badge bg-success-subtle text-success">Phổ biến</span>
                            </div>
                            <small class="text-muted d-block mt-1">Bạn chỉ thanh toán bằng tiền mặt khi shipper giao hàng tận nơi và kiểm tra sản phẩm.</small>
                        </div>
                    </label>

                    <!-- Lựa chọn VietQR -->
                    <label class="payment-option-card d-flex align-items-center gap-3" id="labelPaymentVietqr">
                        <input type="radio" name="payment_method" value="vietqr" id="payVietqr" class="form-check-input mt-0" onchange="updatePaymentSelection()">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="fw-bold text-dark"><i class="fas fa-qrcode me-2 text-primary"></i>Quét mã VietQR tự động</span>
                                <span class="badge bg-primary-subtle text-primary">Nhanh chóng & Miễn phí</span>
                            </div>
                            <small class="text-muted d-block mt-1">Mã QR tự động điền sẵn số tài khoản, số tiền và nội dung chuyển khoản mã đơn.</small>
                        </div>
                    </label>

                    <!-- Lựa chọn VNPAY -->
                    <label class="payment-option-card d-flex align-items-center gap-3" id="labelPaymentVnpay">
                        <input type="radio" name="payment_method" value="vnpay" id="payVnpay" class="form-check-input mt-0" onchange="updatePaymentSelection()">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="fw-bold text-dark"><i class="fas fa-credit-card me-2 text-danger"></i>Cổng thanh toán VNPAY Sandbox</span>
                                <span class="badge bg-danger-subtle text-danger">ATM / QR / Visa / Master</span>
                            </div>
                            <small class="text-muted d-block mt-1">Thanh toán tức thì qua cổng kiểm thử VNPAY Sandbox với tài khoản/thẻ demo.</small>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Cột phải: Tóm tắt đơn hàng (col-lg-5) -->
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm checkout-summary-card p-4 sticky-top" style="top: 100px;">
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                        <h5 class="fw-bold mb-0">Tóm tắt đơn hàng</h5>
                        <span class="badge bg-dark">{{ array_sum(array_column($cart, 'quantity')) }} sản phẩm</span>
                    </div>

                    <!-- Danh sách các sản phẩm -->
                    <div class="checkout-items-list mb-3" style="max-height: 280px; overflow-y: auto;">
                        @foreach($cart as $item)
                            <div class="d-flex align-items-center gap-3 py-2 border-bottom">
                                <img src="{{ $item['image'] ?? 'https://via.placeholder.com/100' }}" alt="{{ $item['name'] }}" class="checkout-item-img border shadow-sm">
                                <div class="flex-grow-1">
                                    <h6 class="mb-0 fw-semibold text-truncate" style="max-width: 200px;" title="{{ $item['name'] }}">{{ $item['name'] }}</h6>
                                    <div class="small text-muted">
                                        @if(!empty($item['size'])) Size: {{ $item['size'] }} @endif
                                        @if(!empty($item['color'])) - {{ $item['color'] }} @endif
                                    </div>
                                    <div class="small fw-semibold text-dark">SL: {{ $item['quantity'] }} &times; {{ number_format($item['price'], 0, ',', '.') }} ₫</div>
                                </div>
                                <div class="text-end fw-bold text-dark">
                                    {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }} ₫
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Tính tiền -->
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Tạm tính:</span>
                        <span class="fw-semibold">{{ number_format($subtotal, 0, ',', '.') }} ₫</span>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Phí vận chuyển:</span>
                        <span class="fw-semibold {{ $shippingFee == 0 ? 'text-success' : 'text-dark' }}">
                            {{ $shippingFee == 0 ? 'Miễn phí (Freeship)' : number_format($shippingFee, 0, ',', '.') . ' ₫' }}
                        </span>
                    </div>

                    <hr class="my-3">

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span class="fw-bold fs-6">Tổng thanh toán:</span>
                        <span class="fs-4 fw-bold text-danger">{{ number_format($total, 0, ',', '.') }} ₫</span>
                    </div>

                    <button type="submit" class="btn btn-danger btn-lg w-100 fw-bold py-3 shadow mb-3" id="btnSubmitOrder">
                        XÁC NHẬN ĐẶT HÀNG <i class="fas fa-arrow-right ms-2"></i>
                    </button>

                    <div class="text-center">
                        <a href="{{ route('cart.index') }}" class="text-decoration-none text-muted small">
                            <i class="fas fa-arrow-left me-1"></i> Quay lại giỏ hàng để sửa
                        </a>
                    </div>

                    <!-- Bảo chứng -->
                    <div class="border-top pt-3 mt-3 text-muted small text-center">
                        <div class="d-flex justify-content-center gap-3 mb-2">
                            <span><i class="fas fa-shield-alt text-success me-1"></i>Bảo mật SSL</span>
                            <span><i class="fas fa-sync text-primary me-1"></i>Đổi trả 30 ngày</span>
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
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Đang xử lý đơn hàng...';
    });
</script>
@endpush
