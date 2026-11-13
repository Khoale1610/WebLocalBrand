@extends('layouts.app')

@section('title', $product->name . ' - Thời Trang Local Brand')

@push('styles')
<style>
    .product-main-img-wrap {
        position: relative;
        overflow: hidden;
        border-radius: 12px;
        background-color: #f8f9fa;
    }
    .product-main-img {
        max-height: 520px;
        width: 100%;
        object-fit: cover;
        border-radius: 12px;
        transition: opacity 0.25s ease-in-out, transform 0.3s ease;
    }
    .product-thumb-img {
        width: 72px;
        height: 88px;
        object-fit: cover;
        border-radius: 8px;
        cursor: pointer;
        border: 2px solid #e5e7eb;
        transition: all 0.2s ease;
    }
    .product-thumb-img:hover {
        border-color: #111;
        transform: translateY(-2px);
    }
    .product-thumb-img.active {
        border-color: #dc3545;
        box-shadow: 0 0 0 2px rgba(220, 53, 69, 0.25);
    }
    .variant-option-btn {
        min-width: 52px;
        height: 42px;
        font-weight: 600;
        font-size: 14px;
        border: 1.5px solid #e5e7eb;
        background: #fff;
        color: #1f2937;
        border-radius: 8px;
        transition: all 0.2s ease;
    }
    .variant-option-btn:hover {
        border-color: #111;
    }
    .variant-option-btn.active {
        background: #111;
        color: #fff;
        border-color: #111;
    }
    .variant-option-btn.disabled {
        opacity: 0.4;
        text-decoration: line-through;
        cursor: not-allowed;
    }
    .color-option-btn {
        padding: 7px 16px;
        font-weight: 600;
        font-size: 14px;
        border: 1.5px solid #e5e7eb;
        border-radius: 30px;
        background: #fff;
        color: #374151;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
    }
    .color-option-btn:hover {
        border-color: #111;
    }
    .color-option-btn.active {
        background: #111;
        color: #fff;
        border-color: #111;
    }
    .color-dot {
        width: 14px;
        height: 14px;
        border-radius: 50%;
        display: inline-block;
        border: 1px solid rgba(0,0,0,0.15);
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
                <li class="breadcrumb-item"><a href="{{ route('category.show', $product->category->id) }}" class="text-decoration-none text-muted">{{ $product->category->name }}</a></li>
            @endif
            <li class="breadcrumb-item active fw-semibold text-dark text-truncate" style="max-width: 320px;" aria-current="page">{{ $product->name }}</li>
        </ol>
    </nav>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <a href="{{ route('cart.index') }}" class="fw-bold text-success text-decoration-underline ms-2">Xem giỏ hàng</a>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-info-circle me-2"></i>{{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4 g-lg-5">
        <!-- Cột Ảnh sản phẩm & Gallery Thumbnails (col-md-6) -->
        <div class="col-md-6">
            <div class="product-main-img-wrap shadow-sm border mb-3">
                <img id="productImageDisplay" 
                     src="{{ $product->image ?? 'https://via.placeholder.com/600x800' }}" 
                     alt="{{ $product->name }}" 
                     class="product-main-img">
                @if($product->is_featured)
                    <span class="position-absolute top-0 start-0 m-3 badge bg-danger fs-6 px-3 py-2 shadow-sm">HOT DEAL</span>
                @endif
            </div>

            <!-- Dải Thumbnail ảnh các màu sắc -->
            @php
                // Lấy danh sách ảnh độc nhất từ sản phẩm và các biến thể màu
                $galleryImages = collect();
                if ($product->image) {
                    $galleryImages->push([
                        'url' => $product->image,
                        'color_id' => null,
                        'color_name' => 'Mặc định'
                    ]);
                }
                foreach ($product->variants as $v) {
                    if ($v->image && !$galleryImages->contains('url', $v->image)) {
                        $galleryImages->push([
                            'url' => $v->image,
                            'color_id' => $v->color_id,
                            'color_name' => $v->color ? $v->color->name : ''
                        ]);
                    }
                }
            @endphp

            @if($galleryImages->count() > 1)
                <div class="d-flex gap-2 overflow-auto py-2" id="galleryThumbnailsStrip">
                    @foreach($galleryImages as $thumb)
                        <img src="{{ $thumb['url'] }}" 
                             alt="{{ $thumb['color_name'] }}" 
                             class="product-thumb-img {{ $loop->first ? 'active' : '' }}" 
                             data-img-url="{{ $thumb['url'] }}"
                             data-color-id="{{ $thumb['color_id'] ?? '' }}"
                             title="{{ $thumb['color_name'] }}"
                             onclick="onThumbnailClick('{{ $thumb['url'] }}', '{{ $thumb['color_id'] }}', this)">
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Cột Thông tin chi tiết & Form Đặt hàng (col-md-6) -->
        <div class="col-md-6">
            <div class="ps-lg-3">
                @if($product->category)
                    <a href="{{ route('category.show', $product->category->id) }}" class="badge bg-light text-dark border text-decoration-none mb-2 px-3 py-2">
                        <i class="fas fa-tag me-1 text-danger"></i>{{ $product->category->name }}
                    </a>
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
                    <span>Mã SKU: <strong id="skuDisplay" class="text-dark">{{ $product->variants->first()->sku ?? $product->slug }}</strong></span>
                </div>

                <!-- Giá bán -->
                <div class="mb-4 p-3 bg-light rounded-3 d-flex align-items-baseline gap-3">
                    <span class="fs-2 fw-bold text-danger" id="priceDisplay">
                        {{ number_format($product->price, 0, ',', '.') }} ₫
                    </span>
                    <span class="text-muted text-decoration-line-through fs-5" id="originalPriceDisplay">
                        {{ number_format($product->price * 1.25, 0, ',', '.') }} ₫
                    </span>
                    <span class="badge bg-danger fs-6">-20%</span>
                </div>

                <!-- Form Thêm vào Giỏ hàng -->
                <form id="addToCartForm" action="{{ route('cart.add') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="variant_id" id="selectedVariantId" value="{{ $product->variants->first()->id ?? '' }}">

                    @php
                        $sizes = $product->variants->pluck('size')->filter()->unique('id')->sortBy('sort_order')->values();
                        $colors = $product->variants->pluck('color')->filter()->unique('id')->values();
                        $firstVariant = $product->variants->first();
                    @endphp

                    <!-- 1. Chọn Màu sắc (Color) - Đổi ảnh trực tiếp -->
                    @if($colors->isNotEmpty())
                        <div class="mb-4">
                            <label class="fw-bold text-dark d-block mb-2">
                                Màu sắc: <span id="selectedColorText" class="text-danger fw-bold">{{ $firstVariant && $firstVariant->color ? $firstVariant->color->name : '' }}</span>
                            </label>
                            <div class="d-flex flex-wrap gap-2" id="colorOptionsGroup">
                                @foreach($colors as $color)
                                    <button type="button" 
                                            class="color-option-btn {{ ($firstVariant && $firstVariant->color_id == $color->id) ? 'active' : '' }}" 
                                            data-color-id="{{ $color->id }}"
                                            data-color-name="{{ $color->name }}"
                                            onclick="selectColor({{ $color->id }}, '{{ $color->name }}', this)">
                                        @if($color->hex_code)
                                            <span class="color-dot" style="background-color: {{ $color->hex_code }};"></span>
                                        @endif
                                        {{ $color->name }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- 2. Chọn Kích cỡ (Size) -->
                    @if($sizes->isNotEmpty())
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="fw-bold text-dark">
                                    Kích cỡ (Size): <span id="selectedSizeText" class="text-danger fw-bold">{{ $firstVariant && $firstVariant->size ? $firstVariant->size->name : '' }}</span>
                                </label>
                                <a href="#sizeGuideModal" data-bs-toggle="modal" class="small text-muted text-decoration-underline">
                                    <i class="fas fa-ruler-horizontal me-1"></i>Bảng size
                                </a>
                            </div>
                            <div class="d-flex flex-wrap gap-2" id="sizeOptionsGroup">
                                @foreach($sizes as $size)
                                    <button type="button" 
                                            class="btn variant-option-btn {{ ($firstVariant && $firstVariant->size_id == $size->id) ? 'active' : '' }}" 
                                            data-size-id="{{ $size->id }}"
                                            data-size-name="{{ $size->name }}"
                                            onclick="selectSize({{ $size->id }}, '{{ $size->name }}', this)">
                                        {{ $size->name }}
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
                            <span class="small" id="stockStatusWrapper">
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2" id="stockBadge">
                                    <i class="fas fa-boxes-stacked me-1"></i>Tồn kho: <strong id="stockCountNumber">{{ $firstVariant->stock ?? $product->stock }}</strong> sản phẩm
                                </span>
                            </span>
                        </div>
                    </div>

                    <!-- 4. Nút Hành Động Mua Hàng -->
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <button type="button" id="btnAddToCart" class="btn btn-outline-dark btn-lg w-100 fw-bold py-3" onclick="addToCartAjax()">
                                <i class="fas fa-shopping-bag me-2"></i>THÊM VÀO GIỎ
                            </button>
                        </div>
                        <div class="col-6">
                            <button type="submit" name="action" value="buy_now" id="btnBuyNow" class="btn btn-danger btn-lg w-100 fw-bold py-3 shadow">
                                <i class="fas fa-bolt me-1"></i>MUA NGAY
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Nút Thêm vào sản phẩm yêu thích (Wishlist) -->
                <form action="{{ route('account.wishlist.add') }}" method="POST" class="mb-4">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <button type="submit" class="btn btn-outline-secondary w-100 py-2 border-dashed">
                        <i class="far fa-heart me-2 text-danger"></i> Thêm vào sản phẩm yêu thích
                    </button>
                </form>

                <!-- Quyền lợi khách hàng -->
                <div class="bg-light p-3 rounded-3 small border">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="fas fa-truck text-danger fs-6"></i>
                        <span>Miễn phí vận chuyển toàn quốc cho đơn hàng từ <strong>500.000 ₫</strong>.</span>
                    </div>
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="fas fa-sync-alt text-danger fs-6"></i>
                        <span>Đổi hàng tận nơi trong vòng <strong>30 ngày</strong> nếu không vừa size.</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-shield-alt text-danger fs-6"></i>
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
    // Dữ liệu biến thể đầy đủ từ Backend
    const variantsData = @json($product->variants);
    const defaultProductImage = "{{ $product->image ?? 'https://via.placeholder.com/600x800' }}";
    const baseProductPrice = {{ (int) $product->price }};

    let selectedSizeId = {{ $firstVariant->size_id ?? 'null' }};
    let selectedColorId = {{ $firstVariant->color_id ?? 'null' }};

    // Hàm chuyển ảnh lớn với hiệu ứng mượt
    function switchMainImage(imgUrl) {
        if (!imgUrl) return;
        const mainImg = document.getElementById('productImageDisplay');
        if (!mainImg || mainImg.src === imgUrl) return;

        mainImg.style.opacity = '0.4';
        setTimeout(() => {
            mainImg.src = imgUrl;
            mainImg.style.opacity = '1';
        }, 120);

        // Highlight thumbnail tương ứng
        document.querySelectorAll('#galleryThumbnailsStrip .product-thumb-img').forEach(t => {
            if (t.dataset.imgUrl === imgUrl) {
                t.classList.add('active');
            } else {
                t.classList.remove('active');
            }
        });
    }

    // Khi click trực tiếp vào Thumbnail
    function onThumbnailClick(imgUrl, colorId, thumbEl) {
        switchMainImage(imgUrl);
        if (colorId) {
            const colorBtn = document.querySelector(`#colorOptionsGroup [data-color-id="${colorId}"]`);
            if (colorBtn) {
                const colorName = colorBtn.dataset.colorName;
                selectColor(parseInt(colorId), colorName, colorBtn);
            }
        }
    }

    // Chọn Kích thước (Size)
    function selectSize(sizeId, sizeName, btn) {
        selectedSizeId = sizeId;
        document.querySelectorAll('#sizeOptionsGroup .variant-option-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        const sizeText = document.getElementById('selectedSizeText');
        if (sizeText) sizeText.textContent = sizeName;

        // Kiểm tra xem tổ hợp với màu hiện tại có tồn tại không
        let matched = variantsData.find(v => v.size_id == selectedSizeId && v.color_id == selectedColorId);
        if (!matched && selectedColorId !== null) {
            // Tự động tìm màu đầu tiên có sẵn của size này
            const firstColorForThisSize = variantsData.find(v => v.size_id == selectedSizeId && v.stock > 0) || variantsData.find(v => v.size_id == selectedSizeId);
            if (firstColorForThisSize && firstColorForThisSize.color_id) {
                const colorBtn = document.querySelector(`#colorOptionsGroup [data-color-id="${firstColorForThisSize.color_id}"]`);
                if (colorBtn) {
                    selectColor(firstColorForThisSize.color_id, colorBtn.dataset.colorName, colorBtn);
                    return;
                }
            }
        }

        updateSelectedVariant();
    }

    // Chọn Màu sắc (Color)
    function selectColor(colorId, colorName, btn) {
        selectedColorId = colorId;
        document.querySelectorAll('#colorOptionsGroup .color-option-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        const colorText = document.getElementById('selectedColorText');
        if (colorText) colorText.textContent = colorName;

        // 1. Tìm biến thể cho màu này
        let matched = variantsData.find(v => v.color_id == selectedColorId && v.size_id == selectedSizeId);
        if (!matched && selectedSizeId !== null) {
            // Tự động tìm size đầu tiên có sẵn của màu này
            const firstSizeForThisColor = variantsData.find(v => v.color_id == selectedColorId && v.stock > 0) || variantsData.find(v => v.color_id == selectedColorId);
            if (firstSizeForThisColor && firstSizeForThisColor.size_id) {
                selectedSizeId = firstSizeForThisColor.size_id;
                document.querySelectorAll('#sizeOptionsGroup .variant-option-btn').forEach(b => {
                    if (b.dataset.sizeId == selectedSizeId) {
                        b.classList.add('active');
                        const st = document.getElementById('selectedSizeText');
                        if (st) st.textContent = b.dataset.sizeName;
                    } else {
                        b.classList.remove('active');
                    }
                });
                matched = firstSizeForThisColor;
            }
        }

        // 2. ĐỔI ẢNH CHÍNH THEO MÀU SẮC BIẾN THỂ
        const targetImg = (matched && matched.image_url) ? matched.image_url : ((matched && matched.image) ? matched.image : null);
        if (targetImg) {
            switchMainImage(targetImg);
        }

        updateSelectedVariant();
    }

    // Cập nhật trạng thái variant (Giá, SKU, Tồn kho, Nút bấm)
    function updateSelectedVariant() {
        let matched = null;
        if (selectedSizeId !== null && selectedColorId !== null) {
            matched = variantsData.find(v => v.size_id == selectedSizeId && v.color_id == selectedColorId);
        } else if (selectedSizeId !== null) {
            matched = variantsData.find(v => v.size_id == selectedSizeId);
        } else if (selectedColorId !== null) {
            matched = variantsData.find(v => v.color_id == selectedColorId);
        } else {
            matched = variantsData[0] || null;
        }

        const variantInput = document.getElementById('selectedVariantId');
        const priceDisplay = document.getElementById('priceDisplay');
        const origPriceDisplay = document.getElementById('originalPriceDisplay');
        const skuDisplay = document.getElementById('skuDisplay');
        const stockStatusWrapper = document.getElementById('stockStatusWrapper');
        const btnAdd = document.getElementById('btnAddToCart');
        const btnBuy = document.getElementById('btnBuyNow');

        if (matched) {
            if (variantInput) variantInput.value = matched.id;
            if (skuDisplay) skuDisplay.textContent = matched.sku || 'OWEN-VARIANT-' + matched.id;

            // Đổi giá
            const price = matched.effective_price || matched.price || baseProductPrice;
            if (priceDisplay) {
                priceDisplay.textContent = new Intl.NumberFormat('vi-VN').format(price) + ' ₫';
            }
            if (origPriceDisplay) {
                origPriceDisplay.textContent = new Intl.NumberFormat('vi-VN').format(Math.round(price * 1.25)) + ' ₫';
            }

            // Đổi ảnh nếu có ảnh riêng
            if (matched.image_url || matched.image) {
                switchMainImage(matched.image_url || matched.image);
            }

            // Kiểm tra tồn kho
            if (matched.stock > 0) {
                if (stockStatusWrapper) {
                    stockStatusWrapper.innerHTML = `
                        <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2">
                            <i class="fas fa-boxes-stacked me-1"></i>Tồn kho: <strong>${matched.stock}</strong> sản phẩm
                        </span>
                    `;
                }
                btnAdd.disabled = false;
                btnBuy.disabled = false;
                btnAdd.innerHTML = '<i class="fas fa-shopping-bag me-2"></i>THÊM VÀO GIỎ';
                btnBuy.innerHTML = '<i class="fas fa-bolt me-1"></i>MUA NGAY';
            } else {
                if (stockStatusWrapper) {
                    stockStatusWrapper.innerHTML = `
                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2">
                            <i class="fas fa-ban me-1"></i>Tạm hết hàng
                        </span>
                    `;
                }
                btnAdd.disabled = true;
                btnBuy.disabled = true;
                btnAdd.innerHTML = 'TẠM HẾT HÀNG';
                btnBuy.innerHTML = 'TẠM HẾT HÀNG';
            }
        } else {
            // Không tìm thấy biến thể phù hợp
            if (variantInput) variantInput.value = '';
            if (stockStatusWrapper) {
                stockStatusWrapper.innerHTML = `
                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-3 py-2">
                        <i class="fas fa-exclamation-triangle me-1"></i>Tùy chọn này hiện chưa có
                    </span>
                `;
            }
            btnAdd.disabled = true;
            btnBuy.disabled = true;
            btnAdd.innerHTML = 'KHÔNG KHẢ DỤNG';
            btnBuy.innerHTML = 'KHÔNG KHẢ DỤNG';
        }
    }

    // Tăng giảm số lượng
    function changeProductQty(delta) {
        const input = document.getElementById('productQuantityInput');
        if (!input) return;
        let val = (parseInt(input.value) || 1) + delta;
        if (val < 1) val = 1;
        if (val > 99) val = 99;
        input.value = val;
    }

    // Thêm vào giỏ bằng AJAX
    function addToCartAjax() {
        const form = document.getElementById('addToCartForm');
        const formData = new FormData(form);
        const btnAdd = document.getElementById('btnAddToCart');
        const originalText = btnAdd.innerHTML;

        btnAdd.disabled = true;
        btnAdd.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang thêm...';

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

                btnAdd.classList.remove('btn-outline-dark');
                btnAdd.classList.add('btn-success');
                btnAdd.innerHTML = '<i class="fas fa-check me-2"></i>ĐÃ THÊM!';

                setTimeout(() => {
                    btnAdd.classList.remove('btn-success');
                    btnAdd.classList.add('btn-outline-dark');
                    btnAdd.disabled = false;
                    btnAdd.innerHTML = originalText;
                }, 1600);
            } else {
                alert(data.message || "Không thể thêm vào giỏ hàng.");
                btnAdd.disabled = false;
                btnAdd.innerHTML = originalText;
            }
        })
        .catch(err => {
            console.error(err);
            alert("Đã xảy ra lỗi khi thêm sản phẩm vào giỏ hàng.");
            btnAdd.disabled = false;
            btnAdd.innerHTML = originalText;
        });
    }

    // Khởi tạo ban đầu khi tải xong trang
    document.addEventListener('DOMContentLoaded', function() {
        updateSelectedVariant();
    });
</script>
@endpush
