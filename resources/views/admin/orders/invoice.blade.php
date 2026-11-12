<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa đơn #{{ $order->order_code }} - Owen Local Brand</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #f1f5f9;
            color: #1e293b;
            padding: 30px 0;
        }

        .invoice-card {
            background: #fff;
            max-width: 850px;
            margin: 0 auto;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .brand-title {
            font-size: 24px;
            font-weight: 800;
            letter-spacing: 1px;
            color: #0f172a;
        }

        .table th {
            background-color: #f8fafc;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }

        .table td {
            font-size: 14px;
            vertical-align: middle;
        }

        @media print {
            body {
                background: #fff;
                padding: 0;
            }
            .invoice-card {
                box-shadow: none;
                padding: 0;
                max-width: 100%;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <!-- Print Control Bar (Hidden when printing) -->
    <div class="container text-center mb-4 no-print">
        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-outline-secondary rounded-pill px-4 me-2">
            <i class="fa-solid fa-arrow-left me-1"></i>Quay lại đơn hàng
        </a>
        <button onclick="window.print()" class="btn btn-primary rounded-pill px-4 fw-bold shadow">
            <i class="fa-solid fa-print me-2"></i>In phiếu xuất kho / giao hàng
        </button>
    </div>

    <!-- Invoice Body -->
    <div class="invoice-card">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-start border-bottom pb-4 mb-4">
            <div>
                <div class="brand-title">OWEN LOCAL BRAND</div>
                <div class="text-muted small mt-1">Hệ thống thời trang nam phong cách hiện đại</div>
                <div class="small text-muted mt-1">
                    <div><i class="fa-solid fa-location-dot me-1"></i>Số 1 Đại Cồ Việt, Hai Bà Trưng, Hà Nội</div>
                    <div><i class="fa-solid fa-phone me-1"></i>Hotline CSKH: 1900 8079 | Website: owen.vn</div>
                </div>
            </div>
            <div class="text-end">
                <h4 class="fw-bold text-dark mb-1">PHIẾU GIAO HÀNG</h4>
                <div class="fw-bold text-primary fs-5 font-monospace">#{{ $order->order_code }}</div>
                <div class="small text-muted">Ngày: {{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : date('d/m/Y') }}</div>
            </div>
        </div>

        <!-- Recipient & Shipping Information -->
        <div class="row mb-4">
            <div class="col-6">
                <div class="text-uppercase small fw-bold text-muted mb-2">Thông tin người nhận hàng:</div>
                <div class="fw-bold text-dark fs-6">{{ $order->customer_name }}</div>
                <div class="text-dark"><i class="fa-solid fa-phone me-1 text-muted"></i>{{ $order->customer_phone }}</div>
                <div class="text-dark"><i class="fa-regular fa-envelope me-1 text-muted"></i>{{ $order->customer_email }}</div>
            </div>
            <div class="col-6">
                <div class="text-uppercase small fw-bold text-muted mb-2">Địa chỉ giao hàng:</div>
                <div class="fw-semibold text-dark">{{ $order->customer_address }}</div>
                <div class="text-muted small">{{ $order->customer_district ? $order->customer_district . ', ' : '' }}{{ $order->customer_city }}</div>
                <div class="mt-2 small">
                    <strong>Phương thức TT:</strong> 
                    <span class="badge bg-light text-dark border">{{ strtoupper($order->payment_method) }}</span>
                    @if($order->payment_status === 'paid')
                        <span class="badge bg-success ms-1">ĐÃ THANH TOÁN</span>
                    @else
                        <span class="badge bg-danger ms-1">THU TIỀN TẬN NƠI (COD)</span>
                    @endif
                </div>
            </div>
        </div>

        @if($order->customer_notes)
            <div class="p-2 mb-4 bg-light border rounded small">
                <strong>Ghi chú giao hàng:</strong> <em>"{{ $order->customer_notes }}"</em>
            </div>
        @endif

        <!-- Goods Table -->
        <div class="table-responsive mb-4">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th style="width: 40px;" class="text-center">STT</th>
                        <th>Tên sản phẩm</th>
                        <th>Phân loại (Size / Màu)</th>
                        <th class="text-center" style="width: 80px;">SL</th>
                        <th class="text-end" style="width: 130px;">Đơn giá</th>
                        <th class="text-end" style="width: 140px;">Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $idx => $item)
                        <tr>
                            <td class="text-center text-muted">{{ $idx + 1 }}</td>
                            <td>
                                <div class="fw-bold text-dark">{{ $item->product_name }}</div>
                                @if($item->variant && $item->variant->sku)
                                    <div class="small text-muted font-monospace">SKU: {{ $item->variant->sku }}</div>
                                @endif
                            </td>
                            <td>
                                {{ $item->variant_info ?? '—' }}
                            </td>
                            <td class="text-center fw-bold">{{ $item->quantity }}</td>
                            <td class="text-end">{{ number_format($item->price, 0, ',', '.') }} ₫</td>
                            <td class="text-end fw-semibold">{{ number_format($item->total_price, 0, ',', '.') }} ₫</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Calculations -->
        <div class="row justify-content-end mb-4">
            <div class="col-6">
                <div class="d-flex justify-content-between py-1 text-muted small">
                    <span>Tạm tính tiền hàng:</span>
                    <span>{{ number_format($order->items->sum('total_price'), 0, ',', '.') }} ₫</span>
                </div>
                <div class="d-flex justify-content-between py-1 text-muted small">
                    <span>Phí vận chuyển:</span>
                    <span>{{ $order->shipping_fee > 0 ? number_format($order->shipping_fee, 0, ',', '.') . ' ₫' : 'Miễn phí' }}</span>
                </div>
                @if($order->discount_amount > 0)
                    <div class="d-flex justify-content-between py-1 text-danger small">
                        <span>Giảm giá:</span>
                        <span>-{{ number_format($order->discount_amount, 0, ',', '.') }} ₫</span>
                    </div>
                @endif
                <hr class="my-2">
                <div class="d-flex justify-content-between py-1">
                    <span class="fw-bold fs-6">TỔNG TIỀN PHẢI THU:</span>
                    <span class="fw-bold fs-5 text-danger">{{ number_format($order->total_amount, 0, ',', '.') }} ₫</span>
                </div>
            </div>
        </div>

        <!-- Signatures -->
        <div class="row text-center mt-5 pt-3">
            <div class="col-4">
                <div class="fw-bold small text-uppercase">Người lập phiếu</div>
                <div class="text-muted small fst-italic mt-1">(Ký, ghi rõ họ tên)</div>
            </div>
            <div class="col-4">
                <div class="fw-bold small text-uppercase">Nhân viên giao hàng</div>
                <div class="text-muted small fst-italic mt-1">(Ký, ghi rõ họ tên)</div>
            </div>
            <div class="col-4">
                <div class="fw-bold small text-uppercase">Người nhận hàng</div>
                <div class="text-muted small fst-italic mt-1">(Ký xác nhận đã nhận đủ hàng)</div>
            </div>
        </div>

        <div class="text-center text-muted small mt-5 pt-4 border-top">
            Cảm ơn quý khách đã tin tưởng và ủng hộ sản phẩm thời trang <strong>Owen Local Brand</strong>!
        </div>
    </div>

</body>
</html>
