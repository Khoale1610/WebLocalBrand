@extends('layouts.app')

@section('title', 'Thanh Toán VietQR - Đơn hàng #' . $order->order_code . ' - WebLocalBrand')

@push('styles')
<style>
    .vietqr-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }
    .vietqr-header {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        color: #ffffff;
        padding: 1.5rem 2rem;
    }
    .qr-frame {
        background: #ffffff;
        border: 2px solid #e2e8f0;
        border-radius: 16px;
        padding: 1rem;
        display: inline-block;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06);
    }
    .qr-img {
        max-width: 270px;
        width: 100%;
        height: auto;
        border-radius: 8px;
    }
    .copy-btn {
        cursor: pointer;
        padding: 0.25rem 0.6rem;
        font-size: 0.8rem;
        border-radius: 6px;
        transition: all 0.2s ease;
    }
    .copy-btn:hover {
        background-color: #f3f4f6;
    }
    .transfer-info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px dashed #e2e8f0;
    }
    .transfer-info-row:last-child {
        border-bottom: none;
    }
    .order-step-bar {
        display: flex;
        justify-content: space-between;
        margin-bottom: 2rem;
        position: relative;
    }
    .step-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        flex: 1;
        text-align: center;
        font-size: 0.85rem;
        font-weight: 600;
        color: #94a3b8;
    }
    .step-item.active {
        color: #dc2626;
    }
    .step-item.completed {
        color: #16a34a;
    }
    .step-circle {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
        background: #f1f5f9;
        color: #64748b;
        font-weight: 700;
        border: 2px solid #e2e8f0;
    }
    .step-item.completed .step-circle {
        background: #dcfce7;
        color: #16a34a;
        border-color: #86efac;
    }
    .step-item.active .step-circle {
        background: #fee2e2;
        color: #dc2626;
        border-color: #fca5a5;
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
            <li class="breadcrumb-item active fw-semibold text-dark" aria-current="page">Thanh toán VietQR</li>
        </ol>
    </nav>

    <!-- Tiến trình các bước -->
    <div class="order-step-bar mx-auto" style="max-width: 650px;">
        <div class="step-item completed">
            <div class="step-circle"><i class="fas fa-check"></i></div>
            <span>1. Giỏ hàng</span>
        </div>
        <div class="step-item completed">
            <div class="step-circle"><i class="fas fa-check"></i></div>
            <span>2. Đặt hàng</span>
        </div>
        <div class="step-item active">
            <div class="step-circle"><i class="fas fa-qrcode"></i></div>
            <span>3. Quét mã VietQR</span>
        </div>
        <div class="step-item">
            <div class="step-circle"><i class="fas fa-box-open"></i></div>
            <span>4. Hoàn tất</span>
        </div>
    </div>

    <!-- Thông báo lỗi hoặc cảnh báo Flash -->
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show text-center mb-4" role="alert">
            <i class="fas fa-times-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show text-center mb-4" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-9">
            <!-- Khối thẻ VietQR -->
            <div class="vietqr-card mb-4">
                <!-- Header -->
                <div class="vietqr-header d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="badge bg-danger px-3 py-1 text-uppercase fw-bold">Thanh Toán VietQR</span>
                            <span class="badge bg-warning text-dark px-2 py-1"><i class="fas fa-clock me-1"></i>Chờ xác nhận</span>
                        </div>
                        <h4 class="fw-bold mb-0 text-white">Quét Mã QR Hoặc Chuyển Khoản</h4>
                    </div>
                    <div class="text-md-end">
                        <small class="text-white-50 d-block">Mã đơn hàng</small>
                        <strong class="fs-5 text-warning tracking-wide">#{{ $order->order_code }}</strong>
                    </div>
                </div>

                <!-- Body -->
                <div class="p-4 p-md-5">
                    <div class="row g-4 align-items-center">
                        <!-- Cột hiển thị Mã QR -->
                        <div class="col-md-5 text-center">
                            <div class="qr-frame mb-3">
                                <img src="{{ $qrUrl }}" alt="VietQR Payment Code" class="qr-img" id="vietQrImage">
                            </div>
                            <p class="small text-muted mb-2">
                                <i class="fas fa-camera text-primary me-1"></i> Quét bằng bất kỳ App Ngân hàng hoặc Ví điện tử nào
                            </p>
                            <a href="{{ $qrUrl }}" target="_blank" download="vietqr_{{ $order->order_code }}.png" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-download me-1"></i> Tải ảnh mã QR
                            </a>
                        </div>

                        <!-- Cột Thông tin tài khoản & Nút xác nhận thanh toán -->
                        <div class="col-md-7 ps-md-4">
                            <div class="bg-light p-3 rounded-3 mb-3 border">
                                <h6 class="fw-bold text-dark mb-2">
                                    <i class="fas fa-university text-danger me-2"></i>Thông Tin Chuyển Khoản Thủ Công
                                </h6>
                                <p class="text-muted small mb-0">Mã QR đã điền sẵn số tiền và nội dung. Nếu không thể quét QR, bạn có thể chuyển khoản theo thông tin sau:</p>
                            </div>

                            <div class="transfer-info-list mb-3">
                                <div class="transfer-info-row">
                                    <span class="text-muted small">Ngân hàng thụ hưởng:</span>
                                    <span class="fw-bold text-dark">MB Bank (Ngân hàng Quân Đội)</span>
                                </div>

                                <div class="transfer-info-row">
                                    <span class="text-muted small">Số tài khoản:</span>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="fw-bold text-danger fs-5" id="bankAccNo">{{ $bankConfig['account_no'] ?? '0987654321' }}</span>
                                        <button type="button" class="btn btn-outline-secondary copy-btn" onclick="copyText('{{ $bankConfig['account_no'] ?? '0987654321' }}', this)">
                                            <i class="far fa-copy me-1"></i>Chép
                                        </button>
                                    </div>
                                </div>

                                <div class="transfer-info-row">
                                    <span class="text-muted small">Tên người nhận:</span>
                                    <span class="fw-bold text-dark">{{ $bankConfig['account_name'] ?? 'LOCAL BRAND OFFICIAL' }}</span>
                                </div>

                                <div class="transfer-info-row">
                                    <span class="text-muted small">Số tiền cần chuyển:</span>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="fw-bold text-success fs-5">{{ number_format($order->total_amount, 0, ',', '.') }} ₫</span>
                                        <button type="button" class="btn btn-outline-secondary copy-btn" onclick="copyText('{{ (int)$order->total_amount }}', this)">
                                            <i class="far fa-copy me-1"></i>Chép
                                        </button>
                                    </div>
                                </div>

                                <div class="transfer-info-row bg-warning-subtle px-2 py-2 rounded-2">
                                    <div>
                                        <span class="text-dark fw-semibold small d-block">Nội dung chuyển khoản:</span>
                                        <span class="text-muted" style="font-size: 0.75rem;">(Bắt buộc để hệ thống nhận diện)</span>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="fw-bold text-danger fs-6">{{ $order->order_code }}</span>
                                        <button type="button" class="btn btn-danger btn-sm copy-btn text-white" onclick="copyText('{{ $order->order_code }}', this)">
                                            <i class="far fa-copy me-1"></i>Chép
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Alert cảnh báo -->
                            <div class="alert alert-info py-2 px-3 small mb-4">
                                <i class="fas fa-info-circle me-1"></i> Sau khi chuyển khoản thành công từ app ngân hàng của bạn, hãy bấm nút <strong>"Xác nhận đã thanh toán"</strong> bên dưới để hoàn tất đơn hàng và nhận email xác nhận.
                            </div>

                            <!-- Nút Xác nhận đã thanh toán -->
                            <form action="{{ route('checkout.vietqr.confirm', $order->order_code) }}" method="POST" id="formConfirmPaid">
                                @csrf
                                <button type="submit" class="btn btn-success btn-lg w-100 fw-bold py-3 shadow" id="btnConfirmPaid">
                                    <i class="fas fa-check-circle me-2"></i>XÁC NHẬN ĐÃ THANH TOÁN
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Khối Tóm tắt đơn hàng & Địa chỉ nhận hàng -->
            <div class="card border-0 shadow-sm rounded-3 p-4">
                <h5 class="fw-bold text-dark mb-3 border-bottom pb-2">
                    <i class="fas fa-shopping-bag text-danger me-2"></i>Chi Tiết Đơn Hàng #{{ $order->order_code }}
                </h5>

                <div class="row g-4">
                    <!-- Danh sách món hàng -->
                    <div class="col-md-7 border-end-md">
                        <h6 class="fw-semibold text-muted small text-uppercase mb-2">Sản phẩm đặt mua</h6>
                        <div class="items-list">
                            @foreach($order->items as $item)
                                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                    <div>
                                        <div class="fw-semibold text-dark">{{ $item->product_name }}</div>
                                        @if($item->variant_info)
                                            <small class="text-muted d-block">{{ $item->variant_info }}</small>
                                        @endif
                                        <small class="text-muted">SL: {{ $item->quantity }} &times; {{ number_format($item->price, 0, ',', '.') }} ₫</small>
                                    </div>
                                    <span class="fw-bold text-dark">{{ number_format($item->total_price, 0, ',', '.') }} ₫</span>
                                </div>
                            @endforeach
                        </div>

                        <div class="d-flex justify-content-between mt-3 mb-1">
                            <span class="text-muted small">Tạm tính:</span>
                            <span class="fw-semibold">{{ number_format($order->total_amount - $order->shipping_fee + $order->discount_amount, 0, ',', '.') }} ₫</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted small">Phí vận chuyển:</span>
                            <span class="fw-semibold {{ $order->shipping_fee == 0 ? 'text-success' : 'text-dark' }}">
                                {{ $order->shipping_fee == 0 ? 'Miễn phí' : number_format($order->shipping_fee, 0, ',', '.') . ' ₫' }}
                            </span>
                        </div>
                        @if($order->discount_amount > 0)
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted small">Giảm giá:</span>
                                <span class="fw-semibold text-danger">-{{ number_format($order->discount_amount, 0, ',', '.') }} ₫</span>
                            </div>
                        @endif
                        <hr class="my-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold">Tổng thanh toán:</span>
                            <span class="fs-5 fw-bold text-danger">{{ number_format($order->total_amount, 0, ',', '.') }} ₫</span>
                        </div>
                    </div>

                    <!-- Thông tin người nhận -->
                    <div class="col-md-5">
                        <h6 class="fw-semibold text-muted small text-uppercase mb-2">Thông tin nhận hàng</h6>
                        <div class="mb-2">
                            <span class="text-muted small">Người nhận:</span>
                            <span class="fw-bold text-dark ms-1">{{ $order->customer_name }}</span>
                        </div>
                        <div class="mb-2">
                            <span class="text-muted small">Số điện thoại:</span>
                            <span class="fw-bold text-dark ms-1">{{ $order->customer_phone }}</span>
                        </div>
                        <div class="mb-2">
                            <span class="text-muted small">Email nhận thông báo:</span>
                            <span class="text-primary ms-1">{{ $order->customer_email }}</span>
                        </div>
                        <div class="mb-2">
                            <span class="text-muted small">Địa chỉ:</span>
                            <span class="text-dark ms-1">{{ $order->customer_address }}{{ $order->customer_district ? ', ' . $order->customer_district : '' }}{{ $order->customer_city ? ', ' . $order->customer_city : '' }}</span>
                        </div>
                        @if($order->customer_notes)
                            <div class="mb-2">
                                <span class="text-muted small">Ghi chú:</span>
                                <span class="fst-italic text-dark ms-1">{{ $order->customer_notes }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Nút quay lại hoặc hỗ trợ -->
            <div class="text-center mt-4">
                <a href="{{ url('/') }}" class="text-decoration-none text-muted small me-3">
                    <i class="fas fa-home me-1"></i> Quay về trang chủ
                </a>
                <span class="text-muted small">|</span>
                <span class="text-muted small ms-3">
                    Cần hỗ trợ? Gọi hotline: <strong class="text-danger">1900 8079</strong>
                </span>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function copyText(text, btn) {
        navigator.clipboard.writeText(text).then(function() {
            const originalHtml = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-check text-success"></i> Đã chép';
            btn.classList.add('btn-success');
            btn.classList.remove('btn-outline-secondary');
            setTimeout(() => {
                btn.innerHTML = originalHtml;
                btn.classList.remove('btn-success');
                btn.classList.add('btn-outline-secondary');
            }, 2000);
        }).catch(function(err) {
            console.error('Không thể chép vào bộ nhớ đệm: ', err);
        });
    }

    document.getElementById('formConfirmPaid').addEventListener('submit', function(e) {
        const btn = document.getElementById('btnConfirmPaid');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Đang xác nhận thanh toán & gửi email...';
    });
</script>
@endpush
