@extends('layouts.app')

@section('title', 'Thanh Toán VietQR — Đơn hàng #' . $order->order_code . ' — OWEN STUDIO')

@push('styles')
<style>
    /* OWEN STUDIO — Minimalist Square VietQR Design System */
    .vietqr-page {
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

    /* Step Progress Bar: Square 0px, high-contrast monochrome */
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

    /* Main Card */
    .vietqr-box {
        background-color: #FFFFFF;
        border: 1px solid #E5E7EB;
        border-radius: 0px;
    }
    .vietqr-header {
        background-color: #111111;
        color: #FFFFFF;
        padding: 22px 28px;
        border-bottom: 3px solid #C83E3B;
    }
    .vq-kicker {
        display: inline-block;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.14em;
        color: #C83E3B;
        margin-bottom: 4px;
    }
    .vq-status-badge {
        display: inline-block;
        padding: 4px 10px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        background-color: #27272A;
        color: #FFFFFF;
        border: 1px solid #3F3F46;
    }

    /* QR Frame */
    .qr-frame {
        background-color: #FFFFFF;
        border: 1px solid #E5E7EB;
        padding: 16px;
        display: inline-block;
    }
    .qr-img {
        max-width: 260px;
        width: 100%;
        height: auto;
        display: block;
    }

    /* Transfer Info Table */
    .transfer-info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        padding: 13px 0;
        border-bottom: 1px dashed #E5E7EB;
    }
    .transfer-info-row:last-child {
        border-bottom: none;
    }
    .transfer-highlight-box {
        background-color: #FFF5F5;
        border: 1px solid #FECACA;
        border-left: 3px solid #C83E3B;
        padding: 14px 16px;
        margin-top: 8px;
    }

    /* Buttons */
    .btn-vq-copy {
        border: 1px solid #111111;
        background-color: #FFFFFF;
        color: #111111;
        border-radius: 0px !important;
        padding: 4px 10px;
        font-size: 12px;
        font-weight: 600;
        transition: all 0.15s ease;
    }
    .btn-vq-copy:hover {
        background-color: #111111;
        color: #FFFFFF;
    }
    .btn-vq-confirm {
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
    .btn-vq-confirm:hover {
        background-color: #C83E3B;
        border-color: #C83E3B;
        color: #FFFFFF;
    }
    .btn-vq-outline {
        border: 1px solid #E5E7EB;
        background-color: #FFFFFF;
        color: #111111;
        border-radius: 0px !important;
        font-size: 12.5px;
        font-weight: 600;
        padding: 8px 16px;
        transition: border-color 0.15s ease;
    }
    .btn-vq-outline:hover {
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
        .vietqr-header {
            padding: 18px 20px;
        }
    }
</style>
@endpush

@section('content')
<div class="container py-4 py-lg-5 vietqr-page">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb mb-0" style="font-size: 13px;">
            <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none text-muted">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('cart.index') }}" class="text-decoration-none text-muted">Giỏ hàng</a></li>
            <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Thanh toán VietQR</li>
        </ol>
    </nav>

    <!-- Tiến trình 4 bước (Vuông góc 0px) -->
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
            <span>Quét mã VietQR</span>
        </div>
        <div class="owen-step-item">
            <span class="owen-step-num">4</span>
            <span>Hoàn tất</span>
        </div>
    </div>

    <!-- Thông báo Flash -->
    @if(session('error'))
        <div class="alert alert-danger rounded-none mb-4" style="font-size: 13.5px; border-color: #C83E3B; color: #C83E3B; background-color: #FFF5F5;" role="alert">
            <i class="fas fa-times-circle me-2"></i>{{ session('error') }}
        </div>
    @endif
    @if(session('warning'))
        <div class="alert alert-warning rounded-none mb-4" style="font-size: 13.5px;" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('warning') }}
        </div>
    @endif

    <!-- Khối chính VietQR -->
    <div class="vietqr-box mb-4">
        <div class="vietqr-header d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <span class="vq-kicker">VIETQR PAYMENT GATEWAY</span>
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <h1 class="h4 fw-bold text-white mb-0">Quét Mã QR Hoặc Chuyển Khoản</h1>
                    <span class="vq-status-badge"><i class="fas fa-clock me-1 text-danger"></i>Chờ xác nhận</span>
                </div>
            </div>
            <div class="text-md-end">
                <span class="d-block text-white-50" style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.08em;">Mã đơn hàng</span>
                <strong class="fs-5 text-white tracking-wider">#{{ $order->order_code }}</strong>
            </div>
        </div>

        <div class="p-4 p-md-5">
            <div class="row g-4 g-lg-5 align-items-center">
                <!-- Cột hiển thị Mã QR -->
                <div class="col-md-5 text-center border-end-md">
                    <div class="qr-frame mb-3">
                        <img src="{{ $qrUrl }}" alt="VietQR Payment Code" class="qr-img" id="vietQrImage">
                    </div>
                    <p class="text-muted mb-3" style="font-size: 13px;">
                        <i class="fas fa-qrcode me-1 text-dark"></i> Mở App Ngân hàng hoặc Ví điện tử bất kỳ để quét mã
                    </p>
                    <a href="{{ $qrUrl }}" target="_blank" download="vietqr_{{ $order->order_code }}.png" class="btn btn-vq-outline">
                        <i class="fas fa-download me-1"></i> Tải ảnh mã QR
                    </a>
                </div>

                <!-- Cột Thông tin chuyển khoản & Nút xác nhận -->
                <div class="col-md-7">
                    <div class="p-3 mb-3 border" style="background-color: #FAFAFA; border-color: #E5E7EB !important;">
                        <div class="fw-semibold text-dark mb-1" style="font-size: 14px;">
                            <i class="fas fa-university me-2" style="color: #C83E3B;"></i>Thông tin chuyển khoản thủ công
                        </div>
                        <p class="text-muted mb-0" style="font-size: 13px;">
                            Mã QR đã tự động điền sẵn số tiền và nội dung. Nếu không quét được, vui lòng chuyển theo thông tin dưới đây:
                        </p>
                    </div>

                    <div class="mb-3">
                        <div class="transfer-info-row">
                            <span class="text-muted" style="font-size: 13.5px;">Ngân hàng thụ hưởng</span>
                            <span class="fw-semibold text-dark" style="font-size: 14px;">MB Bank (Ngân hàng Quân Đội)</span>
                        </div>

                        <div class="transfer-info-row">
                            <span class="text-muted" style="font-size: 13.5px;">Số tài khoản</span>
                            <div class="d-flex align-items-center gap-2">
                                <span class="fw-bold text-dark fs-5" id="bankAccNo">{{ $bankConfig['account_no'] ?? '0987654321' }}</span>
                                <button type="button" class="btn-vq-copy" onclick="copyText('{{ $bankConfig['account_no'] ?? '0987654321' }}', this)">
                                    <i class="far fa-copy me-1"></i>Chép
                                </button>
                            </div>
                        </div>

                        <div class="transfer-info-row">
                            <span class="text-muted" style="font-size: 13.5px;">Chủ tài khoản</span>
                            <span class="fw-semibold text-dark text-uppercase" style="font-size: 14px;">{{ $bankConfig['account_name'] ?? 'OWEN STUDIO OFFICIAL' }}</span>
                        </div>

                        <div class="transfer-info-row">
                            <span class="text-muted" style="font-size: 13.5px;">Số tiền thanh toán</span>
                            <div class="d-flex align-items-center gap-2">
                                <span class="fw-bold fs-5" style="color: #C83E3B;">{{ number_format($order->total_amount, 0, ',', '.') }}đ</span>
                                <button type="button" class="btn-vq-copy" onclick="copyText('{{ (int)$order->total_amount }}', this)">
                                    <i class="far fa-copy me-1"></i>Chép
                                </button>
                            </div>
                        </div>

                        <div class="transfer-highlight-box d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <div>
                                <span class="fw-semibold text-dark d-block" style="font-size: 13.5px;">Nội dung chuyển khoản (Bắt buộc)</span>
                                <span class="text-muted" style="font-size: 12px;">Hệ thống dùng mã này để đối soát đơn hàng tự động</span>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="fw-bold fs-6" style="color: #C83E3B;">{{ $order->order_code }}</span>
                                <button type="button" class="btn-vq-copy" style="border-color: #C83E3B; color: #C83E3B;" onclick="copyText('{{ $order->order_code }}', this)">
                                    <i class="far fa-copy me-1"></i>Chép
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="p-3 mb-4 border" style="background-color: #FAFAFA; border-color: #E5E7EB !important; font-size: 13px; color: #4B5563;">
                        <i class="fas fa-info-circle me-1 text-dark"></i>
                        Sau khi chuyển khoản thành công trên ứng dụng ngân hàng, nhấn <strong>"Xác nhận đã thanh toán"</strong> để hoàn tất đơn hàng và nhận email xác nhận.
                    </div>

                    <form action="{{ route('checkout.vietqr.confirm', $order->order_code) }}" method="POST" id="formConfirmPaid">
                        @csrf
                        <button type="submit" class="btn-vq-confirm" id="btnConfirmPaid">
                            <i class="fas fa-check me-2"></i>Xác nhận đã thanh toán
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Khối Chi tiết đơn hàng -->
    <div class="vietqr-box p-4">
        <div class="d-flex justify-content-between align-items-center pb-3 mb-3 border-bottom" style="border-color: #E5E7EB !important;">
            <h2 class="h6 fw-bold text-uppercase mb-0 tracking-wider">
                Chi tiết đơn hàng #{{ $order->order_code }}
            </h2>
            <span class="text-muted" style="font-size: 13px;">{{ $order->items->count() }} sản phẩm</span>
        </div>

        <div class="row g-4">
            <!-- Danh sách sản phẩm -->
            <div class="col-md-7">
                <div class="mb-3">
                    @foreach($order->items as $item)
                        <div class="d-flex justify-content-between align-items-start py-2 border-bottom" style="border-color: #F3F4F6 !important;">
                            <div class="pe-3">
                                <div class="fw-semibold text-dark" style="font-size: 14px;">{{ $item->product_name }}</div>
                                @if($item->variant_info)
                                    <div class="text-muted" style="font-size: 12.5px;">{{ $item->variant_info }}</div>
                                @endif
                                <div class="text-muted" style="font-size: 12.5px;">SL: {{ $item->quantity }} &times; {{ number_format($item->price, 0, ',', '.') }}đ</div>
                            </div>
                            <span class="fw-semibold text-dark text-nowrap" style="font-size: 14px;">{{ number_format($item->total_price, 0, ',', '.') }}đ</span>
                        </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-between mb-1" style="font-size: 13.5px;">
                    <span class="text-muted">Tạm tính</span>
                    <span class="fw-medium">{{ number_format($order->total_amount - $order->shipping_fee + $order->discount_amount, 0, ',', '.') }}đ</span>
                </div>
                <div class="d-flex justify-content-between mb-1" style="font-size: 13.5px;">
                    <span class="text-muted">Phí vận chuyển</span>
                    <span class="fw-medium {{ $order->shipping_fee == 0 ? 'text-success' : 'text-dark' }}">
                        {{ $order->shipping_fee == 0 ? 'Miễn phí' : number_format($order->shipping_fee, 0, ',', '.') . 'đ' }}
                    </span>
                </div>
                @if($order->discount_amount > 0)
                    <div class="d-flex justify-content-between mb-1" style="font-size: 13.5px;">
                        <span class="text-muted">Giảm giá voucher</span>
                        <span class="fw-medium" style="color: #C83E3B;">-{{ number_format($order->discount_amount, 0, ',', '.') }}đ</span>
                    </div>
                @endif
                <div class="d-flex justify-content-between align-items-center pt-2 mt-2 border-top" style="border-color: #111111 !important;">
                    <span class="fw-bold text-dark">Tổng thanh toán</span>
                    <span class="fs-5 fw-bold" style="color: #C83E3B;">{{ number_format($order->total_amount, 0, ',', '.') }}đ</span>
                </div>
            </div>

            <!-- Thông tin nhận hàng -->
            <div class="col-md-5 border-start-md ps-md-4">
                <h6 class="fw-bold text-uppercase text-muted mb-3" style="font-size: 12px; letter-spacing: 0.08em;">Thông tin nhận hàng</h6>
                <div class="mb-2" style="font-size: 13.5px;">
                    <span class="text-muted">Người nhận:</span>
                    <strong class="text-dark ms-1">{{ $order->customer_name }}</strong>
                </div>
                <div class="mb-2" style="font-size: 13.5px;">
                    <span class="text-muted">Số điện thoại:</span>
                    <strong class="text-dark ms-1">{{ $order->customer_phone }}</strong>
                </div>
                <div class="mb-2" style="font-size: 13.5px;">
                    <span class="text-muted">Email:</span>
                    <span class="text-dark ms-1">{{ $order->customer_email }}</span>
                </div>
                <div class="mb-2" style="font-size: 13.5px;">
                    <span class="text-muted">Địa chỉ:</span>
                    <span class="text-dark ms-1">{{ $order->customer_address }}{{ $order->customer_district ? ', ' . $order->customer_district : '' }}{{ $order->customer_city ? ', ' . $order->customer_city : '' }}</span>
                </div>
                @if($order->customer_notes)
                    <div class="mb-0" style="font-size: 13.5px;">
                        <span class="text-muted">Ghi chú:</span>
                        <span class="fst-italic text-dark ms-1">{{ $order->customer_notes }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Liên kết hỗ trợ -->
    <div class="text-center mt-4" style="font-size: 13px;">
        <a href="{{ url('/') }}" class="text-decoration-none text-muted me-3">
            <i class="fas fa-arrow-left me-1"></i> Quay về trang chủ
        </a>
        <span class="text-muted">|</span>
        <span class="text-muted ms-3">
            Hỗ trợ đơn hàng 24/7: <strong style="color: #C83E3B;">1900 8079</strong>
        </span>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function copyText(text, btn) {
        navigator.clipboard.writeText(text).then(function() {
            const originalHtml = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-check me-1"></i>Đã chép';
            btn.style.backgroundColor = '#111111';
            btn.style.color = '#FFFFFF';
            setTimeout(() => {
                btn.innerHTML = originalHtml;
                btn.style.backgroundColor = '';
                btn.style.color = '';
            }, 2000);
        }).catch(function(err) {
            console.error('Không thể chép vào bộ nhớ đệm: ', err);
        });
    }

    document.getElementById('formConfirmPaid').addEventListener('submit', function() {
        const btn = document.getElementById('btnConfirmPaid');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Đang xác nhận thanh toán...';
    });
</script>
@endpush
