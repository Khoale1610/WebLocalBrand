@extends('layouts.app')

@section('title', 'Đặt Hàng Thành Công #' . $order->order_code . ' — OWEN STUDIO')

@push('styles')
<style>
    /* OWEN STUDIO — Minimalist Square Order Success Design System */
    .order-success-page {
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
    .leading-snug {
        line-height: 1.375 !important;
    }
    .leading-relaxed {
        line-height: 1.625 !important;
    }

    /* Header Status Banner */
    .os-header-box {
        background-color: #FFFFFF;
        border: 1px solid #E5E7EB;
        border-radius: 0px;
        padding: 32px 24px;
        margin-bottom: 28px;
        text-align: center;
    }
    .os-icon-box {
        width: 52px;
        height: 52px;
        background-color: #FFFFFF;
        border: 1.5px solid #111111;
        border-radius: 0px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 16px;
    }
    .os-title {
        font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
        font-size: 22px;
        font-weight: 600;
        line-height: 1.375;
        color: #111111;
        margin-bottom: 8px;
    }
    @media (min-width: 768px) {
        .os-title {
            font-size: 24px;
        }
    }
    .os-status-tag {
        display: inline-block;
        font-size: 11.5px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        padding: 4px 12px;
        border-radius: 0px;
        border: 1px solid #111111;
        background-color: #111111;
        color: #FFFFFF;
        margin-bottom: 14px;
    }
    .os-status-tag-outline {
        display: inline-block;
        font-size: 11.5px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        padding: 4px 12px;
        border-radius: 0px;
        border: 1px solid #E5E7EB;
        background-color: #FAFAFA;
        color: #4B5563;
    }
    .os-order-code-wrap {
        font-size: 14px;
        color: #4B5563;
        margin-bottom: 8px;
    }
    .os-order-code {
        font-weight: 600;
        color: #C83E3B;
        letter-spacing: 0.04em;
    }
    .copy-btn {
        cursor: pointer;
        padding: 2px 8px;
        font-size: 11.5px;
        font-weight: 500;
        color: #4B5563;
        background-color: #FFFFFF;
        border: 1px solid #E5E7EB;
        border-radius: 0px !important;
        transition: all 0.15s ease;
    }
    .copy-btn:hover {
        border-color: #111111;
        color: #111111;
        background-color: #FAFAFA;
    }
    .os-desc {
        font-size: 13.5px;
        line-height: 1.625;
        color: #6B7280;
        max-width: 580px;
        margin: 0 auto;
    }

    /* Section Cards */
    .os-card {
        background-color: #FFFFFF;
        border: 1px solid #E5E7EB;
        border-radius: 0px;
        padding: 24px;
    }
    .os-section-title {
        font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
        font-size: 15px;
        font-weight: 600;
        color: #111111;
        padding-bottom: 12px;
        margin-bottom: 16px;
        border-bottom: 1px solid #E5E7EB;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .os-info-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
        padding: 8px 0;
        border-bottom: 1px solid #F3F4F6;
        font-size: 13.5px;
        line-height: 1.6;
    }
    .os-info-row:last-child {
        border-bottom: none;
    }
    .os-info-label {
        color: #6B7280;
        flex-shrink: 0;
    }
    .os-info-value {
        color: #111111;
        font-weight: 500;
        text-align: right;
    }

    /* VietQR Payment Box */
    .vietqr-card {
        background-color: #FFFFFF;
        border: 1.5px solid #111111;
        border-radius: 0px;
        padding: 28px;
        margin-bottom: 28px;
    }
    .os-qr-frame {
        display: inline-block;
        padding: 12px;
        background-color: #FFFFFF;
        border: 1px solid #E5E7EB;
        border-radius: 0px;
    }
    .os-note-box {
        background-color: #FAFAFA;
        border: 1px solid #E5E7EB;
        border-radius: 0px;
        padding: 12px 14px;
        font-size: 13px;
        line-height: 1.55;
        color: #4B5563;
    }

    /* Pricing & Currency */
    .os-total-price {
        font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
        font-size: 20px;
        font-weight: 600;
        color: #C83E3B;
        line-height: 1.2;
    }
    .os-currency {
        font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
        font-size: 18px;
        font-weight: 600;
        text-decoration: none !important;
        margin-left: 1px;
    }

    /* CTA Buttons: 44px height, 13px font-semibold tracking-wider, square 0px */
    .btn-os-primary {
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
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0 24px;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    .btn-os-primary:hover {
        background-color: #C83E3B;
        border-color: #C83E3B;
        color: #FFFFFF;
    }
    .btn-os-outline {
        height: 44px;
        background-color: #FFFFFF;
        color: #111111;
        border: 1px solid #111111;
        border-radius: 0px !important;
        font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
        font-size: 13px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0 24px;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    .btn-os-outline:hover {
        background-color: #111111;
        color: #FFFFFF;
    }
</style>
@endpush

@section('content')
<div class="container py-4 py-lg-5 order-success-page max-w-[1200px] mx-auto">
    <!-- Thông báo Flash từ VNPAY / Hệ thống nếu có -->
    @if(session('success'))
        <div class="alert alert-dark alert-dismissible fade show rounded-none border-dark text-center mb-4" style="font-size: 13.5px;" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('warning'))
        <div class="alert alert-secondary alert-dismissible fade show rounded-none text-center mb-4" style="font-size: 13.5px;" role="alert">
            {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-none text-center mb-4" style="font-size: 13.5px; border-color: #C83E3B; color: #C83E3B; background-color: #FFF5F5;" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Khối Header Trạng thái Đơn hàng -->
    <div class="os-header-box rounded-none">
        @if($order->payment_method === 'vietqr' && $order->payment_status !== 'paid')
            <div class="os-icon-box rounded-none">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#111111" stroke-width="1.5" stroke-linecap="square" stroke-linejoin="miter">
                    <rect x="3" y="3" width="7" height="7"></rect>
                    <rect x="14" y="3" width="7" height="7"></rect>
                    <rect x="14" y="14" width="7" height="7"></rect>
                    <rect x="3" y="14" width="7" height="7"></rect>
                </svg>
            </div>
            <h1 class="os-title text-[22px] md:text-[24px] font-semibold leading-snug">Đơn hàng đã tạo — Chờ thanh toán VietQR</h1>
            <div>
                <span class="os-status-tag rounded-none" style="background-color: #C83E3B; border-color: #C83E3B;">Chờ quét mã QR thanh toán</span>
            </div>
            <div class="os-order-code-wrap">
                Mã đơn hàng:
                <span class="os-order-code ms-1" id="orderCodeText">{{ $order->order_code }}</span>
                <button type="button" class="copy-btn rounded-none ms-2" onclick="copyToClipboard('{{ $order->order_code }}', this)">
                    Sao chép
                </button>
            </div>
            <p class="os-desc text-[13.5px] leading-relaxed text-gray-600">
                Vui lòng quét mã VietQR bên dưới để hoàn tất chuyển khoản. Đơn hàng sẽ được xác nhận và đóng gói ngay sau khi hệ thống ghi nhận thanh toán.
            </p>
        @elseif($order->payment_status === 'paid')
            <div class="os-icon-box rounded-none">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#111111" stroke-width="1.5" stroke-linecap="square" stroke-linejoin="miter">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
            </div>
            <h1 class="os-title text-[22px] md:text-[24px] font-semibold leading-snug">Thanh toán đơn hàng thành công</h1>
            <div>
                <span class="os-status-tag rounded-none">Đã thanh toán qua {{ strtoupper($order->payment_method) }}</span>
            </div>
            <div class="os-order-code-wrap">
                Mã đơn hàng:
                <span class="os-order-code ms-1" id="orderCodeText">{{ $order->order_code }}</span>
                <button type="button" class="copy-btn rounded-none ms-2" onclick="copyToClipboard('{{ $order->order_code }}', this)">
                    Sao chép
                </button>
            </div>
            <p class="os-desc text-[13.5px] leading-relaxed text-gray-600">
                Cảm ơn bạn đã mua sắm tại OWEN STUDIO. Email xác nhận đơn hàng đã được gửi tới <strong class="text-dark fw-medium">{{ $order->customer_email }}</strong>.
            </p>
        @else
            <div class="os-icon-box rounded-none">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#111111" stroke-width="1.5" stroke-linecap="square" stroke-linejoin="miter">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
            </div>
            <h1 class="os-title text-[22px] md:text-[24px] font-semibold leading-snug">Đặt hàng thành công</h1>
            <div>
                <span class="os-status-tag rounded-none">Thanh toán khi nhận hàng (COD)</span>
            </div>
            <div class="os-order-code-wrap">
                Mã đơn hàng:
                <span class="os-order-code ms-1" id="orderCodeText">{{ $order->order_code }}</span>
                <button type="button" class="copy-btn rounded-none ms-2" onclick="copyToClipboard('{{ $order->order_code }}', this)">
                    Sao chép
                </button>
            </div>
            <p class="os-desc text-[13.5px] leading-relaxed text-gray-600">
                Đơn hàng đã được ghi nhận và gửi thông báo tới <strong class="text-dark fw-medium">{{ $order->customer_email }}</strong>. Quý khách vui lòng thanh toán tiền mặt cho nhân viên giao hàng khi nhận và kiểm tra sản phẩm.
            </p>
        @endif
    </div>

    <!-- KHỐI THANH TOÁN VIETQR (Nếu chọn chuyển khoản và chưa thanh toán) -->
    @if($order->payment_method === 'vietqr' && $order->payment_status !== 'paid')
        <div class="vietqr-card rounded-none">
            <div class="os-section-title">
                <span>Hướng dẫn chuyển khoản qua mã VietQR</span>
                <span class="os-status-tag-outline rounded-none">Tự động điền thông tin</span>
            </div>

            <div class="row align-items-center g-4">
                <!-- Cột hiển thị Mã QR -->
                <div class="col-md-5 text-center">
                    <div class="os-qr-frame rounded-none">
                        <img src="{{ $qrUrl }}" alt="VietQR Payment" class="img-fluid rounded-none" style="max-width: 230px; height: auto;">
                    </div>
                    <div class="mt-2" style="font-size: 12.5px; color: #6B7280;">
                        Mở ứng dụng Ngân hàng bất kỳ và quét mã QR
                    </div>
                </div>

                <!-- Cột thông tin tài khoản chuyển khoản thủ công -->
                <div class="col-md-7">
                    <div class="mb-3" style="font-size: 14px; font-weight: 600; color: #111111;">
                        Thông tin chuyển khoản thủ công
                    </div>

                    <div class="os-info-row">
                        <span class="os-info-label">Ngân hàng thụ hưởng</span>
                        <span class="os-info-value">MB Bank (Ngân hàng Quân Đội)</span>
                    </div>

                    <div class="os-info-row">
                        <span class="os-info-label">Số tài khoản</span>
                        <div class="d-flex align-items-center gap-2">
                            <span class="fw-semibold text-dark">{{ $bankConfig['account_no'] ?? '0987654321' }}</span>
                            <button type="button" class="copy-btn rounded-none" onclick="copyToClipboard('{{ $bankConfig['account_no'] ?? '0987654321' }}', this)">
                                Sao chép
                            </button>
                        </div>
                    </div>

                    <div class="os-info-row">
                        <span class="os-info-label">Chủ tài khoản</span>
                        <span class="os-info-value">{{ $bankConfig['account_name'] ?? 'LOCAL BRAND OFFICIAL' }}</span>
                    </div>

                    <div class="os-info-row">
                        <span class="os-info-label">Số tiền chuyển</span>
                        <div class="d-flex align-items-center gap-2">
                            <span class="fw-semibold" style="color: #C83E3B; font-size: 15px;">{{ number_format($order->total_amount, 0, ',', '.') }}đ</span>
                            <button type="button" class="copy-btn rounded-none" onclick="copyToClipboard('{{ (int)$order->total_amount }}', this)">
                                Sao chép
                            </button>
                        </div>
                    </div>

                    <div class="os-info-row mb-3">
                        <span class="os-info-label">Nội dung chuyển khoản</span>
                        <div class="d-flex align-items-center gap-2">
                            <span class="fw-semibold text-dark">{{ $order->order_code }}</span>
                            <button type="button" class="copy-btn rounded-none" onclick="copyToClipboard('{{ $order->order_code }}', this)">
                                Sao chép
                            </button>
                        </div>
                    </div>

                    <div class="os-note-box rounded-none mb-3">
                        Lưu ý: Vui lòng giữ nguyên nội dung chuyển khoản <strong class="text-dark">{{ $order->order_code }}</strong> để hệ thống đối soát tự động.
                    </div>

                    <div class="d-flex flex-column flex-sm-row gap-2">
                        <form action="{{ route('checkout.vietqr.confirm', $order->order_code) }}" method="POST" class="flex-grow-1">
                            @csrf
                            <button type="submit" class="btn-os-primary h-[44px] text-[13px] font-semibold tracking-wider rounded-none w-100" id="btnConfirmPaid">
                                XÁC NHẬN ĐÃ THANH TOÁN
                            </button>
                        </form>
                        <a href="{{ route('checkout.vietqr', $order->order_code) }}" class="btn-os-outline h-[44px] text-[13px] font-semibold tracking-wider rounded-none">
                            MÀN HÌNH QUÉT QR
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="row g-4">
        <!-- Cột trái: Thông tin giao nhận & Trạng thái thanh toán -->
        <div class="col-lg-6">
            <div class="os-card rounded-none h-100">
                <div class="os-section-title">
                    <span>Thông tin giao nhận</span>
                </div>

                <div class="os-info-row">
                    <span class="os-info-label">Người nhận</span>
                    <span class="os-info-value">{{ $order->customer_name }}</span>
                </div>
                <div class="os-info-row">
                    <span class="os-info-label">Số điện thoại</span>
                    <span class="os-info-value">{{ $order->customer_phone }}</span>
                </div>
                <div class="os-info-row">
                    <span class="os-info-label">Email</span>
                    <span class="os-info-value">{{ $order->customer_email }}</span>
                </div>
                <div class="os-info-row">
                    <span class="os-info-label">Địa chỉ nhận hàng</span>
                    <span class="os-info-value">{{ $order->customer_address }}{{ $order->customer_district ? ', ' . $order->customer_district : '' }}{{ $order->customer_city ? ', ' . $order->customer_city : '' }}</span>
                </div>
                @if($order->customer_notes)
                    <div class="os-info-row">
                        <span class="os-info-label">Ghi chú</span>
                        <span class="os-info-value">{{ $order->customer_notes }}</span>
                    </div>
                @endif

                <div class="os-section-title mt-4">
                    <span>Trạng thái thanh toán & đơn hàng</span>
                </div>

                <div class="os-info-row">
                    <span class="os-info-label">Phương thức thanh toán</span>
                    <span class="os-info-value">
                        @if($order->payment_method === 'cod')
                            Thanh toán khi nhận hàng (COD)
                        @elseif($order->payment_method === 'vietqr')
                            Chuyển khoản qua mã VietQR
                        @elseif($order->payment_method === 'vnpay')
                            Cổng thanh toán VNPAY Sandbox
                        @else
                            {{ strtoupper($order->payment_method) }}
                        @endif
                    </span>
                </div>
                <div class="os-info-row">
                    <span class="os-info-label">Trạng thái thanh toán</span>
                    <span class="os-info-value">
                        @if($order->payment_status === 'paid')
                            <span class="os-status-tag rounded-none mb-0">Đã thanh toán</span>
                        @else
                            <span class="os-status-tag-outline rounded-none">Chờ thanh toán</span>
                        @endif
                    </span>
                </div>
                <div class="os-info-row">
                    <span class="os-info-label">Trạng thái đơn hàng</span>
                    <span class="os-info-value">
                        <span class="os-status-tag-outline rounded-none">{{ $order->order_status_label }}</span>
                    </span>
                </div>
            </div>
        </div>

        <!-- Cột phải: Danh sách sản phẩm & Tổng tiền -->
        <div class="col-lg-6">
            <div class="os-card rounded-none h-100 d-flex flex-column">
                <div class="os-section-title">
                    <span>Chi tiết sản phẩm</span>
                    <span style="font-size: 13px; font-weight: 400; color: #6B7280;">{{ $order->items->count() }} sản phẩm</span>
                </div>

                <div class="items-list mb-3">
                    @foreach($order->items as $item)
                        <div class="d-flex justify-content-between align-items-start py-2 border-bottom" style="border-color: #F3F4F6 !important;">
                            <div class="pe-3">
                                <div style="font-size: 13.5px; font-weight: 600; color: #111111;">{{ $item->product_name }}</div>
                                @if($item->variant_info)
                                    <div style="font-size: 12.5px; color: #6B7280;">{{ $item->variant_info }}</div>
                                @endif
                                <div style="font-size: 12.5px; color: #6B7280;">SL: {{ $item->quantity }} &times; {{ number_format($item->price, 0, ',', '.') }}đ</div>
                            </div>
                            <span style="font-size: 13.5px; font-weight: 600; color: #111111; white-space: nowrap;">
                                {{ number_format($item->total_price, 0, ',', '.') }}đ
                            </span>
                        </div>
                    @endforeach
                </div>

                <div class="pt-1">
                    <div class="d-flex justify-content-between mb-2" style="font-size: 13.5px; color: #4B5563;">
                        <span>Tạm tính</span>
                        <span class="fw-medium text-dark">{{ number_format($order->total_amount - $order->shipping_fee + $order->discount_amount, 0, ',', '.') }}đ</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2" style="font-size: 13.5px; color: #4B5563;">
                        <span>Phí vận chuyển</span>
                        <span class="fw-medium text-dark">
                            {{ $order->shipping_fee == 0 ? 'Miễn phí' : number_format($order->shipping_fee, 0, ',', '.') . 'đ' }}
                        </span>
                    </div>
                    @if($order->discount_amount > 0)
                        <div class="d-flex justify-content-between mb-2" style="font-size: 13.5px; color: #C83E3B;">
                            <span>Giảm giá voucher</span>
                            <span class="fw-semibold">-{{ number_format($order->discount_amount, 0, ',', '.') }}đ</span>
                        </div>
                    @endif
                </div>

                <div class="d-flex justify-content-between align-items-baseline pt-3 mt-2 mb-4" style="border-top: 1px solid #E5E7EB;">
                    <span style="font-size: 14px; font-weight: 600; color: #111111;">Tổng cộng</span>
                    <span class="os-total-price text-[20px] font-semibold text-[#C83E3B]">
                        {{ number_format($order->total_amount, 0, ',', '.') }}<span class="os-currency text-[18px]">đ</span>
                    </span>
                </div>

                <div class="d-flex flex-column flex-sm-row gap-2 mt-auto">
                    <a href="{{ url('/') }}" class="btn-os-primary h-[44px] text-[13px] font-semibold tracking-wider rounded-none flex-grow-1">
                        TIẾP TỤC MUA SẮM
                    </a>
                    @auth
                        <a href="{{ route('account.orders') }}" class="btn-os-outline h-[44px] text-[13px] font-semibold tracking-wider rounded-none">
                            XEM LỊCH SỬ ĐƠN HÀNG
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function copyToClipboard(text, btn) {
        navigator.clipboard.writeText(text).then(function() {
            const originalText = btn.textContent;
            btn.textContent = 'Đã chép';
            btn.style.borderColor = '#111111';
            btn.style.color = '#111111';
            setTimeout(() => {
                btn.textContent = originalText;
                btn.style.borderColor = '';
                btn.style.color = '';
            }, 1800);
        }).catch(function(err) {
            console.error('Không thể chép vào bộ nhớ đệm: ', err);
        });
    }
</script>
@endpush
