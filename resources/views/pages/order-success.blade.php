@extends('layouts.app')

@section('title', 'Đặt Hàng Thành Công #' . $order->order_code . ' - WebLocalBrand')

@push('styles')
<style>
    .success-icon-box {
        width: 80px;
        height: 80px;
        background-color: #dcfce7;
        color: #16a34a;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        margin: 0 auto 1.5rem;
    }
    .order-summary-card {
        background-color: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
    }
    .vietqr-card {
        background: #ffffff;
        border: 2px dashed #dc2626;
        border-radius: 16px;
    }
    .copy-btn {
        cursor: pointer;
        padding: 0.15rem 0.5rem;
        font-size: 0.75rem;
    }
</style>
@endpush

@section('content')
<div class="container py-4 py-lg-5">
    <!-- Icon & Tiêu đề trạng thái đơn hàng -->
    <div class="text-center mb-5">
        @if($order->payment_method === 'vietqr' && $order->payment_status !== 'paid')
            <div class="success-icon-box shadow-sm" style="background-color: #fef3c7; color: #d97706;">
                <i class="fas fa-qrcode"></i>
            </div>
            <h2 class="fw-bold text-dark mb-2">Đơn Hàng Đã Tạo - Chờ Quét Mã VietQR</h2>
            <div class="mb-3">
                <span class="badge bg-warning text-dark px-3 py-2 fs-6">
                    <i class="fas fa-clock me-1"></i>Trạng thái: Chưa thanh toán (Chờ quét mã QR)
                </span>
            </div>
            <p class="text-muted fs-6 mb-1">
                Mã đơn hàng: 
                <strong class="text-danger fs-5" id="orderCodeText">{{ $order->order_code }}</strong>
                <button class="btn btn-sm btn-outline-secondary copy-btn ms-1" onclick="copyToClipboard('{{ $order->order_code }}', this)">
                    <i class="far fa-copy me-1"></i>Copy
                </button>
            </p>
            <p class="text-muted small">
                Vui lòng <strong>quét mã VietQR bên dưới</strong> để chuyển khoản thanh toán. Đơn hàng sẽ được đóng gói và giao đi ngay khi nhận được thanh toán!
            </p>
        @elseif($order->payment_status === 'paid')
            <div class="success-icon-box shadow-sm">
                <i class="fas fa-check"></i>
            </div>
            <h2 class="fw-bold text-dark mb-2">Thanh Toán Đơn Hàng Thành Công!</h2>
            <div class="mb-2">
                <span class="badge bg-success px-3 py-2 fs-6">
                    <i class="fas fa-check-circle me-1"></i>Đã thanh toán qua {{ strtoupper($order->payment_method) }}
                </span>
            </div>
            <div class="mb-2">
                <div class="alert alert-success d-inline-flex align-items-center py-2 px-3 small my-2 shadow-sm">
                    <i class="fas fa-envelope-circle-check text-success me-2 fs-6"></i>
                    <span>Email xác nhận đơn hàng đã được gửi đến: <strong class="ms-1">{{ $order->customer_email }}</strong></span>
                </div>
            </div>
            <p class="text-muted fs-6 mb-1">
                Mã đơn hàng: <strong class="text-danger fs-5" id="orderCodeText">{{ $order->order_code }}</strong>
                <button class="btn btn-sm btn-outline-secondary copy-btn ms-1" onclick="copyToClipboard('{{ $order->order_code }}', this)">
                    <i class="far fa-copy me-1"></i>Copy
                </button>
            </p>
            <p class="text-muted small">
                Cảm ơn bạn! Đơn hàng của bạn đã hoàn tất thanh toán và đang được đóng gói.
            </p>
        @else
            <div class="success-icon-box shadow-sm" style="background-color: #e0f2fe; color: #0284c7;">
                <i class="fas fa-truck-fast"></i>
            </div>
            <h2 class="fw-bold text-dark mb-2">Đặt Hàng Thành Công (COD)</h2>
            <div class="mb-2">
                <span class="badge bg-primary px-3 py-2 fs-6">
                    <i class="fas fa-money-bill-wave me-1"></i>Thanh toán tiền mặt khi nhận hàng
                </span>
            </div>
            <div class="mb-2">
                <div class="alert alert-info d-inline-flex align-items-center py-2 px-3 small my-2 shadow-sm">
                    <i class="fas fa-envelope-circle-check text-primary me-2 fs-6"></i>
                    <span>Email xác nhận đơn hàng đã được gửi đến: <strong class="ms-1">{{ $order->customer_email }}</strong></span>
                </div>
            </div>
            <p class="text-muted fs-6 mb-1">
                Mã đơn hàng: 
                <strong class="text-danger fs-5" id="orderCodeText">{{ $order->order_code }}</strong>
                <button class="btn btn-sm btn-outline-secondary copy-btn ms-1" onclick="copyToClipboard('{{ $order->order_code }}', this)">
                    <i class="far fa-copy me-1"></i>Copy
                </button>
            </p>
            <p class="text-muted small">
                Đơn hàng đã được xác nhận. Bạn sẽ thanh toán tiền mặt cho shipper khi nhận và kiểm tra hàng.
            </p>
        @endif
    </div>

    <!-- Thông báo Flash từ VNPAY / Hệ thống nếu có -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show text-center mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show text-center mb-4" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show text-center mb-4" role="alert">
            <i class="fas fa-times-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- KHỐI THANH TOÁN VIETQR (Nếu chọn chuyển khoản) -->
    @if($order->payment_method === 'vietqr' && $order->payment_status !== 'paid')
        <div class="row justify-content-center mb-5">
            <div class="col-lg-10">
                <div class="vietqr-card p-4 p-md-5 shadow-sm">
                    <div class="text-center mb-4">
                        <span class="badge bg-danger px-3 py-2 fs-6 mb-2">QUÉT MÃ VIETQR ĐỂ HOÀN TẤT THANH TOÁN</span>
                        <p class="text-muted small mb-0">Mở ứng dụng Ngân hàng (hoặc Ví điện tử) của bạn và quét mã QR bên dưới để thanh toán tức thì.</p>
                    </div>

                    <div class="row align-items-center g-4">
                        <!-- Cột hiển thị Mã QR -->
                        <div class="col-md-5 text-center border-end-md">
                            <div class="p-2 border rounded-3 d-inline-block bg-white shadow-sm">
                                <img src="{{ $qrUrl }}" alt="VietQR Payment" class="img-fluid rounded-2" style="max-width: 250px; height: auto;">
                            </div>
                            <div class="small text-muted mt-2">
                                <i class="fas fa-camera me-1"></i>Quét QR bằng bất kỳ ứng dụng ngân hàng nào
                            </div>
                        </div>

                        <!-- Cột thông tin tài khoản chuyển khoản thủ công -->
                        <div class="col-md-7 ps-md-4">
                            <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">Hoặc chuyển khoản thủ công theo thông tin:</h6>

                            <div class="mb-2 d-flex justify-content-between align-items-center py-1 border-bottom">
                                <span class="text-muted">Ngân hàng thụ hưởng:</span>
                                <span class="fw-bold text-dark">MB Bank (Ngân hàng Quân Đội)</span>
                            </div>

                            <div class="mb-2 d-flex justify-content-between align-items-center py-1 border-bottom">
                                <span class="text-muted">Số tài khoản:</span>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="fw-bold text-danger fs-5">{{ $bankConfig['account_no'] ?? '0987654321' }}</span>
                                    <button class="btn btn-outline-secondary copy-btn" onclick="copyToClipboard('{{ $bankConfig['account_no'] ?? '0987654321' }}', this)">
                                        <i class="far fa-copy"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="mb-2 d-flex justify-content-between align-items-center py-1 border-bottom">
                                <span class="text-muted">Tên chủ tài khoản:</span>
                                <span class="fw-bold text-dark">{{ $bankConfig['account_name'] ?? 'LOCAL BRAND OFFICIAL' }}</span>
                            </div>

                            <div class="mb-2 d-flex justify-content-between align-items-center py-1 border-bottom">
                                <span class="text-muted">Số tiền chuyển:</span>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="fw-bold text-success fs-5">{{ number_format($order->total_amount, 0, ',', '.') }} ₫</span>
                                    <button class="btn btn-outline-secondary copy-btn" onclick="copyToClipboard('{{ (int)$order->total_amount }}', this)">
                                        <i class="far fa-copy"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="mb-3 d-flex justify-content-between align-items-center py-1 border-bottom">
                                <span class="text-muted">Nội dung chuyển khoản:</span>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="fw-bold text-primary fs-6">{{ $order->order_code }}</span>
                                    <button class="btn btn-outline-secondary copy-btn" onclick="copyToClipboard('{{ $order->order_code }}', this)">
                                        <i class="far fa-copy"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="alert alert-info py-2 px-3 small mb-3">
                                <i class="fas fa-info-circle me-1"></i>
                                <strong>Lưu ý:</strong> Vui lòng giữ nguyên <strong>nội dung chuyển khoản là {{ $order->order_code }}</strong> để đơn hàng được duyệt tự động nhanh chóng nhất.
                            </div>

                            <div class="d-grid gap-2">
                                <form action="{{ route('checkout.vietqr.confirm', $order->order_code) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success fw-bold py-2 w-100 shadow-sm" id="btnConfirmPaid">
                                        <i class="fas fa-check-circle me-1"></i> Xác Nhận Đã Thanh Toán
                                    </button>
                                </form>
                                <a href="{{ route('checkout.vietqr', $order->order_code) }}" class="btn btn-outline-danger btn-sm text-center">
                                    <i class="fas fa-external-link-alt me-1"></i> Mở Màn Hình Quét Mã VietQR Chuyên Biệt
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="row g-4">
        <!-- Cột trái: Thông tin nhận hàng & Thanh toán -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-3 p-4 h-100">
                <h5 class="fw-bold text-dark mb-3 border-bottom pb-2">
                    <i class="fas fa-truck text-danger me-2"></i>Thông Tin Giao Nhận
                </h5>
                <div class="mb-2">
                    <span class="text-muted">Người nhận:</span>
                    <span class="fw-bold text-dark ms-1">{{ $order->customer_name }}</span>
                </div>
                <div class="mb-2">
                    <span class="text-muted">Số điện thoại:</span>
                    <span class="fw-bold text-dark ms-1">{{ $order->customer_phone }}</span>
                </div>
                <div class="mb-2">
                    <span class="text-muted">Email:</span>
                    <span class="text-dark ms-1">{{ $order->customer_email }}</span>
                </div>
                <div class="mb-2">
                    <span class="text-muted">Địa chỉ nhận hàng:</span>
                    <span class="text-dark ms-1">{{ $order->customer_address }}{{ $order->customer_district ? ', ' . $order->customer_district : '' }}{{ $order->customer_city ? ', ' . $order->customer_city : '' }}</span>
                </div>
                @if($order->customer_notes)
                    <div class="mb-3">
                        <span class="text-muted">Ghi chú:</span>
                        <span class="fst-italic text-dark ms-1">{{ $order->customer_notes }}</span>
                    </div>
                @endif

                <h5 class="fw-bold text-dark mt-4 mb-3 border-bottom pb-2">
                    <i class="fas fa-credit-card text-danger me-2"></i>Trạng Thái Thanh Toán
                </h5>
                <div class="mb-2">
                    <span class="text-muted">Hình thức:</span>
                    <span class="fw-bold ms-1">
                        @if($order->payment_method === 'cod')
                            Thanh toán khi nhận hàng (COD)
                        @elseif($order->payment_method === 'vietqr')
                            Chuyển khoản VietQR tự động
                        @elseif($order->payment_method === 'vnpay')
                            Cổng thanh toán điện tử VNPAY Sandbox
                        @else
                            {{ strtoupper($order->payment_method) }}
                        @endif
                    </span>
                </div>
                <div class="mb-2">
                    <span class="text-muted">Trạng thái thanh toán:</span>
                    <span class="ms-1">
                        @if($order->payment_status === 'paid')
                            <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Đã thanh toán</span>
                        @else
                            <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i>Chờ thanh toán</span>
                        @endif
                    </span>
                </div>
                <div class="mb-2">
                    <span class="text-muted">Trạng thái đơn hàng:</span>
                    <span class="ms-1">
                        {!! $order->order_status_badge !!}
                    </span>
                </div>
            </div>
        </div>

        <!-- Cột phải: Danh sách sản phẩm & Tổng tiền -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm order-summary-card p-4 h-100">
                <h5 class="fw-bold text-dark mb-3 border-bottom pb-2">
                    <i class="fas fa-box text-danger me-2"></i>Chi Tiết Sản Phẩm ({{ $order->items->count() }} món)
                </h5>

                <div class="items-list mb-3">
                    @foreach($order->items as $item)
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <div>
                                <h6 class="mb-0 fw-semibold text-dark">{{ $item->product_name }}</h6>
                                @if($item->variant_info)
                                    <small class="text-muted d-block">{{ $item->variant_info }}</small>
                                @endif
                                <small class="text-muted">SL: {{ $item->quantity }} &times; {{ number_format($item->price, 0, ',', '.') }} ₫</small>
                            </div>
                            <span class="fw-bold text-dark">{{ number_format($item->total_price, 0, ',', '.') }} ₫</span>
                        </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Tạm tính:</span>
                    <span class="fw-semibold">{{ number_format($order->total_amount - $order->shipping_fee + $order->discount_amount, 0, ',', '.') }} ₫</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Phí vận chuyển:</span>
                    <span class="fw-semibold {{ $order->shipping_fee == 0 ? 'text-success' : 'text-dark' }}">
                        {{ $order->shipping_fee == 0 ? 'Miễn phí' : number_format($order->shipping_fee, 0, ',', '.') . ' ₫' }}
                    </span>
                </div>
                @if($order->discount_amount > 0)
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Giảm giá voucher:</span>
                        <span class="fw-semibold text-danger">-{{ number_format($order->discount_amount, 0, ',', '.') }} ₫</span>
                    </div>
                @endif

                <hr class="my-3">

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <span class="fw-bold fs-6">Tổng cộng:</span>
                    <span class="fs-4 fw-bold text-danger">{{ number_format($order->total_amount, 0, ',', '.') }} ₫</span>
                </div>

                <div class="d-flex flex-wrap gap-2 mt-auto">
                    <a href="{{ url('/') }}" class="btn btn-dark flex-grow-1 py-2 fw-semibold">
                        <i class="fas fa-arrow-left me-1"></i> Tiếp Tục Mua Sắm
                    </a>
                    @auth
                        <a href="{{ route('account.orders') }}" class="btn btn-outline-dark py-2 fw-semibold">
                            Xem Lịch Sử Đơn
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
            const originalHtml = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-check text-success"></i> Đã chép';
            setTimeout(() => {
                btn.innerHTML = originalHtml;
            }, 2000);
        }).catch(function(err) {
            console.error('Không thể chép vào bộ nhớ đệm: ', err);
        });
    }

    function notifyTransferSent() {
        const btn = document.getElementById('btnConfirmPaid');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-check me-1"></i> Đã Gửi Thông Báo Chuyển Khoản';
        document.getElementById('transferSentAlert').classList.remove('d-none');
    }
</script>
@endpush
