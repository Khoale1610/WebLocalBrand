@extends('layouts.app')

@section('title', $product->name . ' - Thời Trang Local Brand')

@push('styles')
<style>
    .product-main-img {
        max-height: 520px;
        width: 100%;
        object-fit: cover;
        border-radius: 8px;
    }
    .variant-option-btn {
        min-width: 48px;
        height: 40px;
        font-weight: 600;
        border: 1px solid #dee2e6;
        background: #fff;
        color: #212529;
        transition: all 0.2s ease;
    }
    .variant-option-btn:hover {
        border-color: #000;
    }
    .variant-option-btn.active {
        background: #000;
        color: #fff;
        border-color: #000;
    }
    .color-option-btn {
        padding: 6px 14px;
        font-weight: 500;
        font-size: 14px;
        border: 1px solid #dee2e6;
        border-radius: 20px;
        background: #fff;
        cursor: pointer;
        transition: all 0.2s;
    }
    .color-option-btn.active {
        background: #212529;
        color: #fff;
        border-color: #212529;
    }
</style>
@endpush

@section('content')
<div class="container py-4 py-lg-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none text-muted">Trang chủ</a></li>
            @if($product->category)
                <li class="breadcrumb-item"><a href="#" class="text-decoration-none text-muted">{{ $product->category->name }}</a></li>
            @endif
            <li class="breadcrumb-item active fw-semibold text-dark text-truncate" style="max-width: 300px;" aria-current="page">{{ $product->name }}</li>
        </ol>
    </nav>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <a href="{{ route('cart.index') }}" class="fw-bold text-success text-decoration-underline ms-2">Xem giỏ hàng ngay</a>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4 g-lg-5">
        <!-- Cột Ảnh sản phẩm (col-md-6) -->
        <div class="col-md-6">
            <div class="position-relative">
                <img id="productImageDisplay" 
                     src="{{ $product->image ?? 'https://via.placeholder.com/600x800' }}" 
                     alt="{{ $product->name }}" 
                     class="product-main-img shadow-sm border">
                @if($product->is_featured)
                    <span class="position-absolute top-0 start-0 m-3 badge bg-danger fs-6 px-3 py-2">HOT DEAL</span>
                @endif
            </div>
        </div>

        <!-- Cột Thông tin chi tiết & Form Đặt hàng (col-md-6) -->
        <div class="col-md-6">
            <div class="ps-lg-3">
                @if($product->category)
                    <span class="badge bg-light text-dark border mb-2">{{ $product->category->name }}</span>
                @endif
                <h2 class="fw-bold text-dark mb-2">{{ $product->name }}</h2>

                <!-- Đánh giá sao & Mã SKU -->
                <div class="d-flex align-items-center gap-3 mb-3 text-muted small">
                    <div class="text-warning">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                        <span class="text-dark fw-bold ms-1">4.9</span>
                    </div>
                    <span>|</span>
                    <span>Đã bán: <strong>240+</strong></span>
                    <span>|</span>
                    <span>Mã SKU: <strong id="skuDisplay">{{ $product->variants->first()->sku ?? 'OWEN-DEFAULT' }}</strong></span>
                </div>

                <!-- Giá bán -->
                <div class="mb-4 p-3 bg-light rounded-3 d-flex align-items-baseline gap-3">
                    <span class="fs-2 fw-bold text-danger" id="priceDisplay">
                        {{ number_format($product->price, 0, ',', '.') }} ₫
                    </span>
                    <span class="text-muted text-decoration-line-through">
                        {{ number_format($product->price * 1.25, 0, ',', '.') }} ₫
                    </span>
                    <span class="badge bg-danger">-20%</span>
                </div>

                <!-- Form Thêm vào Giỏ hàng -->
                <form id="addToCartForm" action="{{ route('cart.add') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="variant_id" id="selectedVariantId" value="{{ $product->variants->first()->id ?? '' }}">

                    <!-- 1. Chọn Kích cỡ (Size) -->
                    @php
                        $sizes = $product->variants->pluck('size')->filter()->unique('id')->sortBy('sort_order');
                        $colors = $product->variants->pluck('color')->filter()->unique('id');
                    @endphp

                    @if($sizes->isNotEmpty())
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="fw-bold text-dark">Kích cỡ (Size): <span id="selectedSizeText" class="text-danger"></span></label>
                                <a href="#sizeGuideModal" data-bs-toggle="modal" class="small text-muted text-decoration-underline">
                                    <i class="fas fa-ruler-horizontal me-1"></i>Bảng size
                                </a>
                            </div>
                            <div class="d-flex flex-wrap gap-2" id="sizeOptionsGroup">
                                @foreach($sizes as $idx => $size)
                                    <button type="button" 
                                            class="btn variant-option-btn rounded {{ $idx === 0 ? 'active' : '' }}" 
                                            data-size-id="{{ $size->id }}"
                                            data-size-name="{{ $size->name }}"
                                            onclick="selectSize({{ $size->id }}, '{{ $size->name }}', this)">
                                        {{ $size->name }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- 2. Chọn Màu sắc (Color) -->
                    @if($colors->isNotEmpty())
                        <div class="mb-4">
                            <label class="fw-bold text-dark d-block mb-2">Màu sắc: <span id="selectedColorText" class="text-danger"></span></label>
                            <div class="d-flex flex-wrap gap-2" id="colorOptionsGroup">
                                @foreach($colors as $idx => $color)
                                    <button type="button" 
                                            class="color-option-btn {{ $idx === 0 ? 'active' : '' }}" 
                                            data-color-id="{{ $color->id }}"
                                            data-color-name="{{ $color->name }}"
                                            onclick="selectColor({{ $color->id }}, '{{ $color->name }}', this)">
                                        @if($color->hex_code)
                                            <span class="d-inline-block rounded-circle me-1 border" style="width: 12px; height: 12px; background-color: {{ $color->hex_code }};"></span>
                                        @endif
                                        {{ $color->name }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- 3. Số lượng & Trạng thái tồn kho -->
                    <div class="mb-4">
                        <label class="fw-bold text-dark d-block mb-2">Số lượng:</label>
                        <div class="d-flex align-items-center gap-3">
                            <div class="input-group" style="width: 130px;">
                                <button class="btn btn-outline-secondary" type="button" onclick="changeProductQty(-1)">-</button>
                                <input type="number" name="quantity" id="productQuantityInput" class="form-control text-center fw-bold" value="1" min="1" max="99">
                                <button class="btn btn-outline-secondary" type="button" onclick="changeProductQty(1)">+</button>
                            </div>
                            <span class="small text-muted" id="stockStatusText">
                                Tồn kho: <strong id="stockCountNumber">{{ $product->variants->first()->stock ?? $product->stock }}</strong> sản phẩm
                            </span>
                        </div>
                    </div>

                    <!-- 4. Nút Hành Động Mua Hàng -->
                    <div class="row g-2 mb-4">
                        <div class="col-6">
                            <button type="button" id="btnAddToCart" class="btn btn-outline-dark btn-lg w-100 fw-bold py-3" onclick="addToCartAjax()">
                                <i class="fas fa-shopping-bag me-2"></i>THÊM VÀO GIỎ
                            </button>
                        </div>
                        <div class="col-6">
                            <button type="submit" id="btnBuyNow" class="btn btn-danger btn-lg w-100 fw-bold py-3 shadow">
                                MUA NGAY
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Quyền lợi khách hàng -->
                <div class="bg-light p-3 rounded-3 small">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="fas fa-truck text-danger"></i>
                        <span>Miễn phí vận chuyển toàn quốc cho đơn hàng từ <strong>500.000 ₫</strong>.</span>
                    </div>
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="fas fa-sync-alt text-danger"></i>
                        <span>Đổi hàng tận nơi trong vòng <strong>30 ngày</strong> nếu không vừa size.</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-check-circle text-danger"></i>
                        <span>Cam kết 100% hàng chính hãng Local Brand Việt Nam.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Thông tin Chi tiết & Mô tả sản phẩm (Tabs) -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white border-bottom pt-3">
                    <ul class="nav nav-tabs card-header-tabs" id="productDetailTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active fw-bold text-dark" id="desc-tab" data-bs-toggle="tab" data-bs-target="#desc-pane" type="button" role="tab">
                                MÔ TẢ SẢN PHẨM
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-bold text-dark" id="size-tab" data-bs-toggle="tab" data-bs-target="#size-pane" type="button" role="tab">
                                BẢNG QUY ĐỔI KÍCH CỠ
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-bold text-dark" id="policy-tab" data-bs-toggle="tab" data-bs-target="#policy-pane" type="button" role="tab">
                                CHÍNH SÁCH ĐỔI TRẢ & BẢO HÀNH
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body p-4 tab-content">
                    <!-- Tab 1: Mô tả -->
                    <div class="tab-pane fade show active" id="desc-pane" role="tabpanel">
                        <h5 class="fw-bold mb-3">Đặc điểm nổi bật:</h5>
                        <p class="lead text-muted">{{ $product->description }}</p>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Chất liệu vải cao cấp co giãn 4 chiều mềm mịn, không xù lông.</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Đường may tỉ mỉ, chuẩn may đo thời trang cao cấp.</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Thiết kế dáng Slimfit / Regularfit thời thượng, dễ phối đồ.</li>
                        </ul>
                    </div>

                    <!-- Tab 2: Bảng Size -->
                    <div class="tab-pane fade" id="size-pane" role="tabpanel">
                        <h5 class="fw-bold mb-3">Bảng hướng dẫn chọn kích cỡ (Nam):</h5>
                        <div class="table-responsive" style="max-width: 600px;">
                            <table class="table table-bordered text-center align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Size</th>
                                        <th>Chiều cao (cm)</th>
                                        <th>Cân nặng (kg)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr><td><strong>S</strong></td><td>1m55 - 1m64</td><td>48 - 55kg</td></tr>
                                    <tr><td><strong>M</strong></td><td>1m65 - 1m72</td><td>56 - 65kg</td></tr>
                                    <tr><td><strong>L</strong></td><td>1m73 - 1m78</td><td>66 - 74kg</td></tr>
                                    <tr><td><strong>XL</strong></td><td>1m79 - 1m85</td><td>75 - 84kg</td></tr>
                                    <tr><td><strong>XXL</strong></td><td>> 1m85</td><td>85 - 92kg</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Tab 3: Chính sách -->
                    <div class="tab-pane fade" id="policy-pane" role="tabpanel">
                        <h5 class="fw-bold mb-2">Chính sách đổi trả trong 30 ngày</h5>
                        <p class="text-muted">Khách hàng được hỗ trợ đổi size, đổi mẫu tận nhà hoàn toàn miễn phí nếu sản phẩm còn nguyên tem mác, chưa qua giặt ủi.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sản phẩm liên quan -->
    @if(isset($relatedProducts) && $relatedProducts->isNotEmpty())
        <div class="mt-5">
            <h4 class="fw-bold text-uppercase mb-4">Sản Phẩm Cùng Danh Mục</h4>
            <div class="row g-4">
                @foreach($relatedProducts as $relProduct)
                    <div class="col-6 col-md-3">
                        <div class="card h-100 border-0 shadow-sm">
                            <img src="{{ $relProduct->image ?? 'https://via.placeholder.com/300' }}" class="card-img-top" alt="{{ $relProduct->name }}" style="height: 240px; object-fit: cover;">
                            <div class="card-body text-center d-flex flex-column">
                                <h6 class="card-title mb-1">
                                    <a href="{{ route('products.show', $relProduct->id) }}" class="text-dark text-decoration-none fw-semibold">
                                        {{ $relProduct->name }}
                                    </a>
                                </h6>
                                <p class="text-danger fw-bold mb-3">{{ number_format($relProduct->price, 0, ',', '.') }} ₫</p>
                                <a href="{{ route('products.show', $relProduct->id) }}" class="btn btn-outline-dark btn-sm mt-auto">Xem Chi Tiết</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

<!-- Modal Bảng Size -->
<div class="modal fade" id="sizeGuideModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Bảng Hướng Dẫn Chọn Size</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <table class="table table-bordered align-middle">
                    <thead class="table-dark">
                        <tr><th>Size</th><th>Chiều cao</th><th>Cân nặng</th></tr>
                    </thead>
                    <tbody>
                        <tr><td><strong>S</strong></td><td>1m55 - 1m64</td><td>48 - 55kg</td></tr>
                        <tr><td><strong>M</strong></td><td>1m65 - 1m72</td><td>56 - 65kg</td></tr>
                        <tr><td><strong>L</strong></td><td>1m73 - 1m78</td><td>66 - 74kg</td></tr>
                        <tr><td><strong>XL</strong></td><td>1m79 - 1m85</td><td>75 - 84kg</td></tr>
                        <tr><td><strong>XXL</strong></td><td>> 1m85</td><td>85 - 92kg</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Dữ liệu biến thể được truyền từ Laravel sang JavaScript
    const variantsData = @json($product->variants);
    let selectedSizeId = {{ $product->variants->first()->size_id ?? 'null' }};
    let selectedColorId = {{ $product->variants->first()->color_id ?? 'null' }};

    function selectSize(sizeId, sizeName, btn) {
        selectedSizeId = sizeId;
        document.querySelectorAll('#sizeOptionsGroup .variant-option-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        const sizeText = document.getElementById('selectedSizeText');
        if (sizeText) sizeText.textContent = sizeName;
        updateSelectedVariant();
    }

    function selectColor(colorId, colorName, btn) {
        selectedColorId = colorId;
        document.querySelectorAll('#colorOptionsGroup .color-option-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        const colorText = document.getElementById('selectedColorText');
        if (colorText) colorText.textContent = colorName;
        updateSelectedVariant();
    }

    function updateSelectedVariant() {
        const matched = variantsData.find(v => v.size_id == selectedSizeId && v.color_id == selectedColorId);
        const variantInput = document.getElementById('selectedVariantId');
        const priceDisplay = document.getElementById('priceDisplay');
        const skuDisplay = document.getElementById('skuDisplay');
        const stockCount = document.getElementById('stockCountNumber');
        const btnAdd = document.getElementById('btnAddToCart');
        const btnBuy = document.getElementById('btnBuyNow');

        if (matched) {
            if (variantInput) variantInput.value = matched.id;
            if (skuDisplay) skuDisplay.textContent = matched.sku || 'N/A';
            if (stockCount) stockCount.textContent = matched.stock;

            const effectivePrice = matched.price ? matched.price : {{ $product->price }};
            if (priceDisplay) {
                priceDisplay.textContent = new Intl.NumberFormat('vi-VN').format(effectivePrice) + ' ₫';
            }

            if (matched.stock <= 0) {
                btnAdd.disabled = true;
                btnBuy.disabled = true;
                btnAdd.textContent = "TẠM HẾT HÀNG";
            } else {
                btnAdd.disabled = false;
                btnBuy.disabled = false;
                btnAdd.innerHTML = '<i class="fas fa-shopping-bag me-2"></i>THÊM VÀO GIỎ';
            }
        }
    }

    function changeProductQty(delta) {
        const input = document.getElementById('productQuantityInput');
        if (!input) return;
        let val = (parseInt(input.value) || 1) + delta;
        if (val < 1) val = 1;
        if (val > 99) val = 99;
        input.value = val;
    }

    function addToCartAjax() {
        const form = document.getElementById('addToCartForm');
        const formData = new FormData(form);

        fetch("{{ route('cart.add') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Accept": "application/json"
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Cập nhật badge header
                const badge = document.getElementById('cartCountBadge');
                if (badge) badge.textContent = data.cart_count;

                alert(data.message);
            } else {
                alert(data.message || "Không thể thêm vào giỏ hàng.");
            }
        })
        .catch(err => {
            console.error(err);
            alert("Đã xảy ra lỗi khi thêm sản phẩm vào giỏ.");
        });
    }

    // Khởi tạo hiển thị ban đầu
    document.addEventListener('DOMContentLoaded', function() {
        const activeSizeBtn = document.querySelector('#sizeOptionsGroup .variant-option-btn.active');
        if (activeSizeBtn) {
            document.getElementById('selectedSizeText').textContent = activeSizeBtn.dataset.sizeName;
        }
        const activeColorBtn = document.querySelector('#colorOptionsGroup .color-option-btn.active');
        if (activeColorBtn) {
            document.getElementById('selectedColorText').textContent = activeColorBtn.dataset.colorName;
        }
        updateSelectedVariant();
    });
</script>
@endpush
