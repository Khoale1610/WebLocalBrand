@extends('layouts.app')

@section('title', 'Cổng Thanh Toán VNPAY Sandbox — OWEN STUDIO')

@push('styles')
<style>
    /* OWEN STUDIO — Minimalist Square VNPAY Sandbox Design System */
    .vnpay-page {
        font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        max-width: 1200px;
        margin-left: auto;
        margin-right: auto;
    }
    .vnpay-wrapper {
        max-width: 780px;
        margin: 0 auto;
    }
    .rounded-none {
        border-radius: 0px !important;
    }

    /* Step Progress Bar */
    .owen-step-bar {
        display: flex;
        align-items: stretch;
        border: 1px solid #E5E7EB;
        background-color: #FFFFFF;
        margin-bottom: 28px;
    }
    .owen-step-item {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding: 14px 12px;
        font-size: 13px;
        font-weight: 600;
        color: #9CA3AF;
        border-right: 1px solid #E5E7EB;
        background-color: #FAFAFA;
        text-align: center;
    }
    .owen-step-item:last-child {
        border-right: none;
    }
    .owen-step-num {
        width: 24px;
        height: 24px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 11.5px;
        font-weight: 700;
        border: 1px solid #D1D5DB;
        color: #6B7280;
        background-color: #FFFFFF;
        flex-shrink: 0;
    }
    .owen-step-item.completed {
        background-color: #FFFFFF;
        color: #111111;
    }
    .owen-step-item.completed .owen-step-num {
        background-color: #111111;
        border-color: #111111;
        color: #FFFFFF;
    }
    .owen-step-item.active {
        background-color: #111111;
        color: #FFFFFF;
    }
    .owen-step-item.active .owen-step-num {
        background-color: #C83E3B;
        border-color: #C83E3B;
        color: #FFFFFF;
    }

    /* Sandbox Notice */
    .vp-notice {
        background-color: #FAFAFA;
        border: 1px solid #E5E7EB;
        border-left: 3px solid #111111;
        padding: 16px 20px;
        margin-bottom: 24px;
    }

    /* Main VNPAY Card */
    .vnpay-box {
        background-color: #FFFFFF;
        border: 1px solid #E5E7EB;
        border-radius: 0px;
    }
    .vnpay-header {
        background-color: #111111;
        color: #FFFFFF;
        padding: 24px 28px;
        border-bottom: 3px solid #C83E3B;
    }
    .vp-badge-sandbox {
        display: inline-block;
        padding: 4px 10px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        background-color: #C83E3B;
        color: #FFFFFF;
    }
    .vp-badge-env {
        display: inline-block;
        padding: 4px 10px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        background-color: #27272A;
        color: #E5E7EB;
        border: 1px solid #3F3F46;
    }

    /* Summary & Demo Card Info */
    .vp-summary-box {
        background-color: #FAFAFA;
        border: 1px solid #E5E7EB;
        padding: 20px;
        margin-bottom: 20px;
    }
    .vp-test-card {
        background-color: #FFFFFF;
        border: 1px solid #E5E7EB;
        border-left: 3px solid #C83E3B;
        padding: 18px 20px;
        margin-bottom: 24px;
    }
    .vp-code {
        background-color: #F4F4F5;
        color: #111111;
        padding: 2px 6px;
        font-weight: 600;
        font-size: 13px;
        border: 1px solid #E5E7EB;
    }

    /* Buttons */
    .btn-vp-success {
        width: 100%;
        height: 50px;
        background-color: #111111;
        color: #FFFFFF;
        border: 1px solid #111111;
        border-radius: 0px !important;
        font-size: 13.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        transition: all 0.2s ease;
    }
    .btn-vp-success:hover {
        background-color: #C83E3B;
        border-color: #C83E3B;
        color: #FFFFFF;
    }
    .btn-vp-cancel {
        width: 100%;
        height: 44px;
        background-color: #FFFFFF;
        color: #C83E3B;
        border: 1px solid #C83E3B;
        border-radius: 0px !important;
        font-size: 13px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        transition: all 0.2s ease;
    }
    .btn-vp-cancel:hover {
        background-color: #FFF5F5;
        color: #C83E3B;
    }
    .btn-vp-live {
        display: inline-block;
        padding: 9px 18px;
        border: 1px solid #E5E7EB;
        background-color: #FFFFFF;
        color: #111111;
        font-size: 12.5px;
        font-weight: 600;
        text-decoration: none;
        transition: border-color 0.15s ease;
    }
    .btn-vp-live:hover {
        border-color: #111111;
        color: #111111;
    }

    @media (max-width: 767.98px) {
        .owen-step-bar {
            flex-wrap: wrap;
        }
        .owen-step-item {
            flex: 1 1 50%;
            border-bottom: 1px solid #E5E7EB;
            padding: 10px 8px;
            font-size: 12px;
        }
        .vnpay-header {
            padding: 20px;
        }
    }
</style>
@endpush

@section('content')
<div class="container py-4 py-lg-5 vnpay-page">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb mb-0" style="font-size: 13px;">
            <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none text-muted">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('cart.index') }}" class="text-decoration-none text-muted">Giỏ hàng</a></li>
            <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Cổng thanh toán VNPAY</li>
        </ol>
    </nav>

    <!-- Tiến trình 4 bước -->
    <div class="owen-step-bar">
        <div class="owen-step-item completed">
            <span class="owen-step-num"><i class="fas fa-check"></i></span>
            <span>Giỏ hàng</span>
        </div>
        <div class="owen-step-item completed">
            <span class="owen-step-num"><i class="fas fa-check"></i></span>
            <span>Đặt hàng</span>
        </div>
        <div class="owen-step-item active">
            <span class="owen-step-num">3</span>
            <span>Cổng VNPAY</span>
        </div>
        <div class="owen-step-item">
            <span class="owen-step-num">4</span>
            <span>Hoàn tất</span>
        </div>
    </div>

    <div class="vnpay-wrapper">
        <!-- Giải thích Sandbox -->
        <div class="vp-notice">
            <div class="d-flex align-items-start gap-3">
                <i class="fas fa-shield-alt fs-5 text-dark mt-1"></i>
                <div>
                    <div class="fw-bold text-dark mb-1" style="font-size: 14px;">Môi trường kiểm thử VNPAY Sandbox</div>
                    <p class="text-muted mb-0" style="font-size: 13px; line-height: 1.55;">
                        Đây là cổng mô phỏng giao dịch trực tuyến phục vụ kiểm thử đồ án (<strong>không trừ tiền thật</strong>), cho phép kiểm tra đầy đủ luồng callback xác nhận thành công (mã <code>00</code>) hoặc hủy giao dịch (mã <code>24</code>).
                    </p>
                </div>
            </div>
        </div>

        <!-- Khối Cổng thanh toán VNPAY -->
        <div class="vnpay-box">
            <div class="vnpay-header">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <span class="fs-4 fw-bold" style="letter-spacing: 0.06em;">
                            <span style="color: #C83E3B;">VN</span>PAY
                        </span>
                        <span class="vp-badge-sandbox">Sandbox Mode</span>
                    </div>
                    <span class="vp-badge-env"><i class="fas fa-lock me-1"></i>Test Environment</span>
                </div>
                <h1 class="h4 fw-bold text-white mb-1">Cổng Thanh Toán Điện Tử VNPAY</h1>
                <p class="mb-0 text-white-50" style="font-size: 13.5px;">Hóa đơn thanh toán cho đơn hàng <strong class="text-white">#{{ $order->order_code }}</strong></p>
            </div>

            <div class="p-4 p-md-5">
                <!-- Thông tin đơn hàng -->
                <div class="vp-summary-box">
                    <div class="d-flex justify-content-between mb-2" style="font-size: 13.5px;">
                        <span class="text-muted">Đơn vị thụ hưởng</span>
                        <span class="fw-bold text-dark">OWEN STUDIO</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2" style="font-size: 13.5px;">
                        <span class="text-muted">Mã tham chiếu đơn hàng</span>
                        <span class="fw-bold text-dark">{{ $order->order_code }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center pt-3 mt-2 border-top" style="border-color: #E5E7EB !important;">
                        <span class="fw-bold text-dark" style="font-size: 14px;">Số tiền cần thanh toán</span>
                        <span class="fs-4 fw-bold" style="color: #C83E3B;">{{ number_format($order->total_amount, 0, ',', '.') }}đ</span>
                    </div>
                </div>

                <!-- Thông tin thẻ test NCB -->
                <div class="vp-test-card">
                    <div class="fw-bold text-dark mb-3" style="font-size: 13.5px; text-transform: uppercase; letter-spacing: 0.06em;">
                        <i class="fas fa-credit-card me-2" style="color: #C83E3B;"></i>Thông tin Thẻ Test NCB (VNPAY Demo)
                    </div>
                    <div class="row g-2" style="font-size: 13px;">
                        <div class="col-sm-6">
                            <span class="text-muted">Ngân hàng:</span>
                            <strong class="text-dark ms-1">NCB (Quốc Dân)</strong>
                        </div>
                        <div class="col-sm-6">
                            <span class="text-muted">Số thẻ:</span>
                            <span class="vp-code ms-1">9704198526191432198</span>
                        </div>
                        <div class="col-sm-6">
                            <span class="text-muted">Chủ thẻ:</span>
                            <span class="vp-code ms-1">NGUYEN VAN A</span>
                        </div>
                        <div class="col-sm-6">
                            <span class="text-muted">Ngày phát hành:</span>
                            <span class="vp-code ms-1">07/15</span>
                            <span class="text-muted ms-2">OTP:</span>
                            <span class="vp-code ms-1">123456</span>
                        </div>
                    </div>
                </div>

                <!-- 2 Nút mô phỏng giao dịch -->
                <div class="d-grid gap-3 mb-4">
                    <form action="{{ route('checkout.vnpay.return') }}" method="GET">
                        <input type="hidden" name="vnp_TxnRef" value="{{ $order->order_code }}">
                        <input type="hidden" name="vnp_Amount" value="{{ (int)$order->total_amount * 100 }}">
                        <input type="hidden" name="vnp_ResponseCode" value="00">
                        <input type="hidden" name="vnp_TransactionNo" value="{{ time() }}">
                        <input type="hidden" name="is_mock" value="1">
                        <button type="submit" class="btn-vp-success">
                            <i class="fas fa-check me-2"></i>Thanh toán thành công (Demo Thẻ NCB)
                        </button>
                    </form>

                    <form action="{{ route('checkout.vnpay.return') }}" method="GET">
                        <input type="hidden" name="vnp_TxnRef" value="{{ $order->order_code }}">
                        <input type="hidden" name="vnp_Amount" value="{{ (int)$order->total_amount * 100 }}">
                        <input type="hidden" name="vnp_ResponseCode" value="24">
                        <input type="hidden" name="is_mock" value="1">
                        <button type="submit" class="btn-vp-cancel">
                            <i class="fas fa-times me-2"></i>Hủy thanh toán (Mô phỏng hủy giao dịch)
                        </button>
                    </form>
                </div>

                <!-- Kết nối Sandbox VNPAY trực tiếp -->
                <div class="text-center border-top pt-3" style="border-color: #E5E7EB !important;">
                    <p class="text-muted mb-2" style="font-size: 12.5px;">
                        Hoặc chuyển hướng tới máy chủ VNPAY Sandbox chính thức (yêu cầu cấu hình Terminal Merchant):
                    </p>
                    <a href="{{ $liveVnpayUrl }}" class="btn-vp-live">
                        <i class="fas fa-external-link-alt me-1"></i> Mở cổng Sandbox VNPAY (sandbox.vnpayment.vn)
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
