@extends('layouts.app')

@section('title', 'Giỏ Hàng - Thời Trang Local Brand')

@push('styles')
<style>
    .cart-item-img {
        width: 80px;
        height: 100px;
        object-fit: cover;
        border-radius: 6px;
    }
    .qty-input-group {
        max-width: 130px;
    }
    .qty-btn {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }
    .qty-input {
        text-align: center;
        font-weight: 600;
    }
    .summary-card {
        border-radius: 10px;
        background-color: #fdfdfd;
    }
    .freeship-box {
        background-color: #f0fdf4;
        border: 1px dashed #22c55e;
        border-radius: 8px;
    }
</style>
@endpush

@section('content')
<div class="container py-4 py-lg-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none text-muted">Trang chủ</a></li>
            <li class="breadcrumb-item active fw-semibold text-dark" aria-current="page">Giỏ hàng của bạn</li>
        </ol>
    </nav>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center shadow-sm" role="alert">
            <i class="fas fa-check-circle fs-5 me-2"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center shadow-sm" role="alert">
            <i class="fas fa-exclamation-circle fs-5 me-2"></i>
            <div>{{ session('error') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Alert thông báo động bằng Javascript -->
    <div id="cartJsAlert" class="d-none alert alert-dismissible fade show shadow-sm" role="alert">
        <span id="cartJsAlertMessage"></span>
        <button type="button" class="btn-close" onclick="document.getElementById('cartJsAlert').classList.add('d-none')"></button>
    </div>

    @if(empty($cart))
        <!-- Giao diện khi giỏ hàng TRỐNG -->
        <div class="text-center py-5 my-4 bg-light rounded-4 shadow-sm border">
            <div class="mb-3">
                <i class="fas fa-shopping-bag text-muted display-1"></i>
            </div>
            <h4 class="fw-bold text-dark mb-2">Giỏ hàng của bạn đang trống!</h4>
            <p class="text-muted mb-4">Hãy khám phá ngay những mẫu áo sơ mi, polo và quần tây thời thượng mới nhất.</p>
            <a href="{{ url('/') }}" class="btn btn-dark btn-lg px-4 shadow-sm">
                <i class="fas fa-arrow-left me-2"></i>Tiếp Tục Mua Sắm
            </a>
        </div>
    @else
        <!-- Giao diện khi CÓ SẢN PHẨM trong giỏ -->
        <div class="row g-4" id="cartContentSection">
            <!-- Cột trái: Bảng danh sách sản phẩm (col-lg-8) -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">Sản phẩm trong giỏ (<span id="cartHeaderTotalQty">{{ array_sum(array_column($cart, 'quantity')) }}</span>)</h5>
                        <form action="{{ route('cart.clear') }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa toàn bộ giỏ hàng?');">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-danger border-0">
                                <i class="far fa-trash-alt me-1"></i>Xóa tất cả
                            </button>
                        </form>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="table-light text-muted small text-uppercase">
                                    <tr>
                                        <th style="min-width: 250px;" class="ps-3">Sản phẩm</th>
                                        <th class="text-center">Đơn giá</th>
                                        <th class="text-center" style="min-width: 140px;">Số lượng</th>
                                        <th class="text-end">Thành tiền</th>
                                        <th class="text-center pe-3" style="width: 50px;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cart as $key => $item)
                                        <tr id="cartRow_{{ $key }}">
                                            <!-- Cột Sản phẩm & Biến thể -->
                                            <td class="ps-3 py-3">
                                                <div class="d-flex align-items-center gap-3">
                                                    <img src="{{ $item['image'] ?? 'https://via.placeholder.com/100' }}" alt="{{ $item['name'] }}" class="cart-item-img border shadow-sm">
                                                    <div>
                                                        <h6 class="mb-1 fw-bold">
                                                            <a href="{{ url('/') }}" class="text-dark text-decoration-none hover-danger">
                                                                {{ $item['name'] }}
                                                            </a>
                                                        </h6>
                                                        <div class="d-flex flex-wrap gap-1 mt-1">
                                                            @if(!empty($item['size']))
                                                                <span class="badge bg-light text-dark border">Size: {{ $item['size'] }}</span>
                                                            @endif
                                                            @if(!empty($item['color']))
                                                                <span class="badge bg-secondary">{{ $item['color'] }}</span>
                                                            @endif
                                                        </div>
                                                        @if(!empty($item['sku']))
                                                            <small class="text-muted d-block mt-1">SKU: {{ $item['sku'] }}</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- Cột Đơn giá -->
                                            <td class="text-center fw-semibold text-muted">
                                                {{ number_format($item['price'], 0, ',', '.') }} ₫
                                            </td>

                                            <!-- Cột Tăng giảm số lượng -->
                                            <td class="text-center">
                                                <div class="input-group qty-input-group mx-auto">
                                                    <button class="btn btn-outline-secondary qty-btn" type="button" onclick="changeQty('{{ $key }}', -1)">-</button>
                                                    <input type="number" 
                                                           id="qtyInput_{{ $key }}" 
                                                           class="form-control qty-input" 
                                                           value="{{ $item['quantity'] }}" 
                                                           min="1" 
                                                           max="{{ $item['stock'] > 0 ? $item['stock'] : 99 }}"
                                                           onchange="onQtyInputChange('{{ $key }}')">
                                                    <button class="btn btn-outline-secondary qty-btn" type="button" onclick="changeQty('{{ $key }}', 1)">+</button>
                                                </div>
                                                <small class="text-muted d-block mt-1">Kho: {{ $item['stock'] }}</small>
                                            </td>

                                            <!-- Cột Thành tiền -->
                                            <td class="text-end fw-bold text-danger fs-6" id="itemTotalPrice_{{ $key }}">
                                                {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }} ₫
                                            </td>

                                            <!-- Cột Nút Xóa -->
                                            <td class="text-center pe-3">
                                                <button type="button" class="btn btn-link text-danger p-0 fs-5" title="Xóa món này" onclick="removeItem('{{ $key }}')">
                                                    <i class="far fa-trash-alt"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card-footer bg-white py-3 border-top d-flex justify-content-between align-items-center">
                        <a href="{{ url('/') }}" class="btn btn-outline-dark">
                            <i class="fas fa-arrow-left me-2"></i>Tiếp tục mua hàng
                        </a>
                    </div>
                </div>
            </div>

            <!-- Cột phải: Tóm tắt đơn hàng & Thanh toán (col-lg-4) -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm summary-card p-3 p-lg-4 sticky-top" style="top: 100px;">
                    <h5 class="fw-bold mb-3">Tóm tắt đơn hàng</h5>

                    <!-- Hộp tiến trình Freeship -->
                    <div class="freeship-box p-3 mb-3">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="fas fa-truck-fast text-success fs-5"></i>
                            <div class="small" id="freeshipTextMessage">
                                @if($amountNeededForFreeShipping > 0)
                                    Mua thêm <strong class="text-danger" id="freeshipAmountNeededText">{{ number_format($amountNeededForFreeShipping, 0, ',', '.') }} ₫</strong> để được <strong>Miễn phí vận chuyển</strong>!
                                @else
                                    <strong class="text-success">🎉 Bạn đã đủ điều kiện Miễn phí vận chuyển!</strong>
                                @endif
                            </div>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div id="freeshipProgressBar" 
                                 class="progress-bar bg-success progress-bar-striped progress-bar-animated" 
                                 role="progressbar" 
                                 style="width: {{ $freeShippingPercent }}%;" 
                                 aria-valuenow="{{ $freeShippingPercent }}" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100"></div>
                        </div>
                    </div>

                    <!-- Bảng tính tiền chi tiết -->
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Tạm tính:</span>
                        <span class="fw-semibold" id="cartSubtotalText">{{ number_format($subtotal, 0, ',', '.') }} ₫</span>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Phí vận chuyển:</span>
                        <span class="fw-semibold text-success" id="cartShippingFeeText">
                            {{ $shippingFee == 0 ? 'Miễn phí' : number_format($shippingFee, 0, ',', '.') . ' ₫' }}
                        </span>
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Giảm giá voucher:</span>
                        <span class="fw-semibold text-danger">0 ₫</span>
                    </div>

                    <hr class="my-3">

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span class="fw-bold fs-6">Tổng cộng:</span>
                        <div class="text-end">
                            <span class="fs-4 fw-bold text-danger" id="cartTotalAmountText">{{ number_format($total, 0, ',', '.') }} ₫</span>
                            <small class="d-block text-muted">(Đã bao gồm thuế VAT)</small>
                        </div>
                    </div>

                    <!-- Form nhập mã giảm giá -->
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Mã giảm giá (Coupon)" id="couponCodeInput">
                        <button class="btn btn-outline-dark" type="button" onclick="alert('Tính năng áp dụng mã giảm giá sẽ được kích hoạt tại bước thanh toán!')">Áp dụng</button>
                    </div>

                    <!-- Nút Tiến hành thanh toán -->
                    <a href="#" class="btn btn-danger btn-lg w-100 fw-bold py-3 shadow mb-3" onclick="alert('Chức năng Đặt hàng (Checkout) sẽ được kích hoạt ở bước tiếp theo!'); return false;">
                        TIẾN HÀNH ĐẶT HÀNG <i class="fas fa-arrow-right ms-2"></i>
                    </a>

                    <!-- Cam kết dịch vụ -->
                    <div class="border-top pt-3 text-muted small">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="fas fa-shield-alt text-primary"></i>
                            <span>Bảo hành đổi trả miễn phí trong vòng 30 ngày.</span>
                        </div>
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="fas fa-shipping-fast text-primary"></i>
                            <span>Giao hàng hỏa tốc từ 2 - 4 ngày toàn quốc.</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-headset text-primary"></i>
                            <span>Hotline hỗ trợ khách hàng: 1900 8079 (8:00 - 22:00)</span>
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
    // Token CSRF Laravel cho request AJAX
    const csrfToken = "{{ csrf_token() }}";

    // Hiển thị thông báo động
    function showCartAlert(message, type = 'success') {
        const alertBox = document.getElementById('cartJsAlert');
        const alertMsg = document.getElementById('cartJsAlertMessage');
        alertBox.className = `alert alert-${type} alert-dismissible fade show shadow-sm`;
        alertMsg.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>${message}`;
        alertBox.classList.remove('d-none');
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // Tăng / Giảm số lượng qua nút +/-
    function changeQty(key, delta) {
        const input = document.getElementById(`qtyInput_${key}`);
        if (!input) return;

        let currentVal = parseInt(input.value) || 1;
        let maxVal = parseInt(input.getAttribute('max')) || 99;
        let minVal = parseInt(input.getAttribute('min')) || 1;

        let newVal = currentVal + delta;
        if (newVal < minVal) newVal = minVal;
        if (newVal > maxVal) {
            showCartAlert(`Rất tiếc, bạn chỉ có thể mua tối đa ${maxVal} sản phẩm này.`, 'warning');
            return;
        }

        input.value = newVal;
        updateCartItem(key, newVal);
    }

    // Xử lý khi người dùng nhập số trực tiếp vào ô input
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

    // Gửi AJAX cập nhật số lượng
    function updateCartItem(key, quantity) {
        fetch("{{ route('cart.update') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken,
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
                // Cập nhật giá trị hiển thị trên bảng
                const itemTotalElem = document.getElementById(`itemTotalPrice_${key}`);
                if (itemTotalElem) itemTotalElem.textContent = data.item_total;

                document.getElementById('cartSubtotalText').textContent = data.subtotal;
                document.getElementById('cartShippingFeeText').textContent = data.shipping_fee;
                document.getElementById('cartTotalAmountText').textContent = data.total;

                // Cập nhật Badge trên Header và Header giỏ hàng
                const badge = document.getElementById('cartCountBadge');
                if (badge) badge.textContent = data.cart_count;
                const headerQty = document.getElementById('cartHeaderTotalQty');
                if (headerQty) headerQty.textContent = data.cart_count;

                // Cập nhật thanh Freeship
                updateFreeshipBar(data.is_freeship, data.amount_needed, data.freeship_percent);

                showCartAlert(data.message, 'success');
            } else {
                showCartAlert(data.message, 'danger');
            }
        })
        .catch(err => {
            console.error(err);
            showCartAlert("Đã xảy ra lỗi khi cập nhật giỏ hàng. Vui lòng thử lại!", "danger");
        });
    }

    // Gửi AJAX xóa 1 món hàng
    function removeItem(key) {
        if (!confirm("Bạn có chắc muốn xóa sản phẩm này khỏi giỏ hàng?")) {
            return;
        }

        const url = "{{ url('/cart/remove') }}/" + encodeURIComponent(key);

        fetch(url, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
                "Accept": "application/json"
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Xóa hàng tương ứng khỏi bảng
                const row = document.getElementById(`cartRow_${key}`);
                if (row) row.remove();

                // Cập nhật Badge Header
                const badge = document.getElementById('cartCountBadge');
                if (badge) badge.textContent = data.cart_count;

                if (data.cart_is_empty) {
                    // Nếu giỏ hàng trống, tải lại trang để hiện giao diện Empty State
                    location.reload();
                    return;
                }

                const headerQty = document.getElementById('cartHeaderTotalQty');
                if (headerQty) headerQty.textContent = data.cart_count;

                document.getElementById('cartSubtotalText').textContent = data.subtotal;
                document.getElementById('cartShippingFeeText').textContent = data.shipping_fee;
                document.getElementById('cartTotalAmountText').textContent = data.total;

                updateFreeshipBar(data.is_freeship, data.amount_needed, data.freeship_percent);
                showCartAlert(data.message, 'success');
            } else {
                showCartAlert(data.message, 'danger');
            }
        })
        .catch(err => {
            console.error(err);
            showCartAlert("Không thể xóa sản phẩm. Vui lòng thử lại!", "danger");
        });
    }

    // Cập nhật thanh tiến trình Freeship
    function updateFreeshipBar(isFreeship, amountNeeded, percent) {
        const textElem = document.getElementById('freeshipTextMessage');
        const barElem = document.getElementById('freeshipProgressBar');

        if (barElem) {
            barElem.style.width = `${percent}%`;
            barElem.setAttribute('aria-valuenow', percent);
        }

        if (textElem) {
            if (isFreeship) {
                textElem.innerHTML = `<strong class="text-success">🎉 Bạn đã đủ điều kiện Miễn phí vận chuyển!</strong>`;
            } else {
                const formattedNeeded = new Intl.NumberFormat('vi-VN').format(amountNeeded) + ' ₫';
                textElem.innerHTML = `Mua thêm <strong class="text-danger">${formattedNeeded}</strong> để được <strong>Miễn phí vận chuyển</strong>!`;
            }
        }
    }
</script>
@endpush
