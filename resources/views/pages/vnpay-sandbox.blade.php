@extends('layouts.app')

@section('title', 'Cổng Thanh Toán VNPAY Sandbox - WebLocalBrand')

@push('styles')
<style>
    .vnpay-container {
        max-width: 680px;
        margin: 0 auto;
    }
    .vnpay-header-box {
        background: linear-gradient(135deg, #005baa 0%, #003a70 100%);
        color: white;
        border-radius: 12px 12px 0 0;
        padding: 24px;
    }
    .vnpay-card {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    }
    .demo-badge {
        background: #fef08a;
        color: #854d0e;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.75rem;
    }
</style>
@endpush

@section('content')
<div class="container py-4 py-lg-5">
    <div class="vnpay-container">
        <!-- Thông báo giải thích Sandbox -->
        <div class="alert alert-info border-0 shadow-sm rounded-3 mb-4">
            <div class="d-flex align-items-start gap-3">
                <i class="fas fa-info-circle fs-4 text-primary mt-1"></i>
                <div>
                    <h6 class="fw-bold mb-1">VNPAY Sandbox là gì?</h6>
                    <p class="small text-muted mb-0">
                        <strong>VNPAY Sandbox</strong> là môi trường kiểm thử (mô phỏng) do VNPAY cung cấp cho lập trình viên và sinh viên thực hiện đồ án. Môi trường này <strong>không trừ tiền thật</strong>, cho phép bạn giả lập đầy đủ quy trình xác nhận thanh toán thành công hoặc hủy thanh toán để chấm điểm đồ án.
                    </p>
                </div>
            </div>
        </div>

        <div class="card vnpay-card border-0">
            <!-- Header VNPAY -->
            <div class="vnpay-header-box">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <span class="fs-4 fw-black tracking-wider"><span style="color: #ed1c24;">VN</span>PAY</span>
                        <span class="demo-badge text-uppercase">Sandbox Demo Mode</span>
                    </div>
                    <span class="badge bg-light text-dark"><i class="fas fa-shield-alt text-success me-1"></i>Môi Trường Test</span>
                </div>
                <h4 class="fw-bold mb-1">Cổng Thanh Toán Điện Tử VNPAY</h4>
                <p class="small text-white-50 mb-0">Hóa đơn thanh toán cho đơn hàng #{{ $order->order_code }}</p>
            </div>

            <!-- Body Chi tiết đơn & Lựa chọn mô phỏng -->
            <div class="card-body p-4 p-md-5">
                <!-- Thông tin đơn hàng -->
                <div class="bg-light p-3 rounded-3 mb-4 border">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Đơn vị thụ hưởng:</span>
                        <span class="fw-bold text-dark">WebLocalBrand Fashion</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Mã đơn hàng:</span>
                        <span class="fw-bold text-danger">{{ $order->order_code }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                        <span class="fw-bold text-dark">Số tiền cần thanh toán:</span>
                        <span class="fs-4 fw-bold text-primary">{{ number_format($order->total_amount, 0, ',', '.') }} ₫</span>
                    </div>
                </div>

                <!-- Thẻ demo của VNPAY Sandbox -->
                <div class="card border-primary-subtle bg-primary-subtle p-3 rounded-3 mb-4">
                    <h6 class="fw-bold text-primary mb-2"><i class="fas fa-credit-card me-2"></i>Thông tin Thẻ Test VNPAY Sandbox (NCB Demo):</h6>
                    <div class="row g-2 small text-dark">
                        <div class="col-sm-6"><strong>Ngân hàng:</strong> NCB (Quốc Dân)</div>
                        <div class="col-sm-6"><strong>Số thẻ:</strong> <code>9704198526191432198</code></div>
                        <div class="col-sm-6"><strong>Tên chủ thẻ:</strong> <code>NGUYEN VAN A</code></div>
                        <div class="col-sm-6"><strong>Ngày phát hành:</strong> <code>07/15</code></div>
                        <div class="col-sm-6"><strong>Mã OTP:</strong> <code>123456</code></div>
                    </div>
                </div>

                <!-- 2 Nút kích hoạt kiểm thử -->
                <div class="d-grid gap-3 mb-4">
                    <!-- Nút 1: Mô phỏng thanh toán Thành Công (Trả mã 00) -->
                    <form action="{{ route('checkout.vnpay.return') }}" method="GET">
                        <input type="hidden" name="vnp_TxnRef" value="{{ $order->order_code }}">
                        <input type="hidden" name="vnp_Amount" value="{{ (int)$order->total_amount * 100 }}">
                        <input type="hidden" name="vnp_ResponseCode" value="00">
                        <input type="hidden" name="vnp_TransactionNo" value="{{ time() }}">
                        <input type="hidden" name="is_mock" value="1">
                        <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold py-3 shadow-sm">
                            <i class="fas fa-check-circle me-2"></i>Thanh Toán Thành Công (Demo Thẻ NCB)
                        </button>
                    </form>

                    <!-- Nút 2: Mô phỏng Người dùng Hủy thanh toán (Trả mã 24) -->
                    <form action="{{ route('checkout.vnpay.return') }}" method="GET">
                        <input type="hidden" name="vnp_TxnRef" value="{{ $order->order_code }}">
                        <input type="hidden" name="vnp_Amount" value="{{ (int)$order->total_amount * 100 }}">
                        <input type="hidden" name="vnp_ResponseCode" value="24">
                        <input type="hidden" name="is_mock" value="1">
                        <button type="submit" class="btn btn-outline-danger w-100 fw-semibold py-2">
                            <i class="fas fa-times-circle me-2"></i>Hủy Thanh Toán (Mô phỏng Hủy giao dịch)
                        </button>
                    </form>
                </div>

                <!-- Tuỳ chọn kết nối server VNPAY Sandbox trực tiếp -->
                <div class="text-center border-top pt-3">
                    <p class="small text-muted mb-2">Hoặc kết nối trực tiếp đến máy chủ VNPAY Sandbox (Yêu cầu tài khoản Merchant đã đăng ký):</p>
                    <a href="{{ $liveVnpayUrl }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-external-link-alt me-1"></i> Mở cổng live Sandbox VNPAY (sandbox.vnpayment.vn)
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
