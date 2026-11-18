@extends('layouts.app')

@section('title', $product->name . ' — OWEN STUDIO')

@push('styles')
<style>
    /* OWEN STUDIO — Refined Minimalist Menswear Typography & Square Design System */
    .product-detail-page {
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

    /* Two-column 52% / 48% Desktop Balance */
    @media (min-width: 768px) {
        .pd-col-gallery {
            flex: 0 0 auto;
            width: 52%;
        }
        .pd-col-info {
            flex: 0 0 auto;
            width: 48%;
        }
    }

    /* Gallery & Vertical Thumbnails (64px aspect 3/4 on the right) + Fixed 410px 3:4 Main Image */
    .pd-gallery-container {
        display: flex;
        flex-direction: column;
        gap: 12px;
        align-items: center;
        justify-content: center;
    }
    @media (min-width: 768px) {
        .pd-gallery-container {
            flex-direction: row;
            align-items: flex-start;
            justify-content: center;
            gap: 12px;
        }
    }
    .pd-thumbs-column {
        display: flex;
        flex-direction: row;
        gap: 8px;
        flex-shrink: 0;
        overflow-x: auto;
        max-width: 100%;
    }
    @media (min-width: 768px) {
        .pd-thumbs-column {
            flex-direction: column;
            overflow-y: auto;
            overflow-x: hidden;
            max-height: 546px;
        }
    }
    .product-main-img-wrap {
        position: relative;
        width: 100%;
        max-width: 410px;
        aspect-ratio: 3 / 4;
        overflow: hidden;
        border-radius: 0px;
        background-color: #F7F7F7;
        border: 1px solid #E5E7EB;
        flex-shrink: 0;
    }
    .product-main-img {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        border-radius: 0px;
        transition: opacity 0.2s ease-in-out;
    }
    .product-thumb-img {
        width: 64px;
        aspect-ratio: 3 / 4;
        height: 85px;
        object-fit: cover;
        object-position: center;
        overflow: hidden;
        border-radius: 0px;
        cursor: pointer;
        border: 1px solid #E5E7EB;
        background-color: #F7F7F7;
        flex-shrink: 0;
        transition: all 0.2s ease;
    }
    .product-thumb-img:hover {
        border-color: #111111;
    }
    .product-thumb-img.active {
        border: 2px solid #111111;
    }

    /* Typography & Header Info */
    .pd-category-label {
        display: inline-block;
        font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
        font-size: 12px;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: #71717A;
        text-decoration: none;
        margin-bottom: 6px;
        transition: color 0.2s ease;
    }
    .pd-category-label:hover {
        color: #111111;
    }
    .pd-product-title {
        font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
        font-size: 22px;
        font-weight: 600;
        line-height: 1.375;
        letter-spacing: -0.01em;
        color: #111111;
        margin-bottom: 10px;
    }
    @media (min-width: 768px) {
        .pd-product-title {
            font-size: 24px;
        }
    }
    .pd-meta-row {
        font-size: 13px;
        color: #6B7280;
        padding-bottom: 14px;
        border-bottom: 1px solid #E5E7EB;
        margin-bottom: 16px;
    }

    /* Pricing Block */
    .pd-price-block {
        background-color: #FFFFFF;
        padding-bottom: 18px;
        margin-bottom: 20px;
        border-bottom: 1px solid #E5E7EB;
    }
    .pd-price-current {
        font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
        font-size: 20px;
        font-weight: 600;
        color: #C83E3B;
        line-height: 1.2;
        text-decoration: none;
    }
    .pd-currency {
        font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
        font-size: 18px;
        font-weight: 600;
        text-decoration: none !important;
        border-bottom: none !important;
        margin-left: 1px;
    }
    .pd-price-original {
        font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
        font-size: 14px;
        color: #9CA3AF;
        text-decoration: line-through;
        font-weight: 400;
    }
    .pd-discount-tag {
        background-color: #C83E3B;
        color: #FFFFFF;
        font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        padding: 3px 8px;
        border-radius: 0px;
        display: inline-block;
    }

    /* Variant Labels: 14px, font-medium (500), capitalize first letter only, #1F2937 */
    .pd-option-label {
        font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
        font-size: 14px;
        font-weight: 500;
        text-transform: none;
        letter-spacing: 0;
        color: #1F2937;
    }
    .pd-option-value {
        font-weight: 600;
        color: #111111;
    }

    /* Color Swatch: 32x32px Square (border-radius: 0px) */
    .color-option-btn {
        width: 32px;
        height: 32px;
        padding: 0;
        border-radius: 0px !important;
        border: 1px solid #E5E7EB;
        cursor: pointer;
        position: relative;
        transition: all 0.15s ease;
        background-clip: padding-box;
    }
    .color-option-btn:hover {
        border-color: #111111;
    }
    .color-option-btn.active,
    .color-option-btn.ring-1 {
        box-shadow: 0 0 0 2px #FFFFFF, 0 0 0 3px #111111;
        border-color: #111111;
    }

    /* Size Option: Square 0px, 1px solid #E5E7EB, active 2px solid #111111 + bold text (white bg) */
    .variant-option-btn {
        min-width: 46px;
        height: 40px;
        padding: 0 14px;
        font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
        font-weight: 500;
        font-size: 13.5px;
        border: 1px solid #E5E7EB;
        background-color: #FFFFFF;
        color: #111111;
        border-radius: 0px !important;
        transition: all 0.15s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .variant-option-btn:hover {
        border-color: #111111;
        background-color: #FFFFFF;
        color: #111111;
    }
    .variant-option-btn.active {
        background-color: #FFFFFF !important;
        color: #111111 !important;
        border: 2px solid #111111 !important;
        font-weight: 700 !important;
    }
    .variant-option-btn.disabled {
        opacity: 0.4;
        text-decoration: line-through;
        cursor: not-allowed;
    }

    /* Quantity Input Group: Square 0px */
    .pd-qty-group {
        display: inline-flex;
        align-items: stretch;
        border: 1px solid #E5E7EB;
        background-color: #FFFFFF;
        height: 40px;
        border-radius: 0px;
    }
    .pd-qty-btn {
        width: 38px;
        border: none;
        background-color: #FFFFFF;
        color: #111111;
        font-size: 15px;
        font-weight: 500;
        border-radius: 0px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background-color 0.15s ease;
    }
    .pd-qty-btn:hover {
        background-color: #F4F4F5;
    }
    .pd-qty-input {
        width: 48px;
        border: none;
        border-left: 1px solid #E5E7EB;
        border-right: 1px solid #E5E7EB;
        text-align: center;
        font-weight: 600;
        font-size: 14px;
        color: #111111;
        background-color: #FFFFFF;
        border-radius: 0px;
        outline: none;
        -moz-appearance: textfield;
    }
    .pd-qty-input::-webkit-outer-spin-button,
    .pd-qty-input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* CTA Buttons: height 44px, font-size 13px, font-semibold (600), tracking-wider */
    .btn-pd-cart {
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
        transition: all 0.2s ease;
    }
    .btn-pd-cart:hover:not(:disabled) {
        background-color: #111111;
        color: #FFFFFF;
    }
    .btn-pd-wishlist {
        width: 44px;
        height: 44px;
        flex-shrink: 0;
        background-color: #FFFFFF;
        color: #111111;
        border: 1px solid #E5E7EB;
        border-radius: 0px !important;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        transition: all 0.2s ease;
    }
    .btn-pd-wishlist:hover {
        border-color: #111111;
        background-color: #FAFAFA;
    }
    .btn-pd-buy {
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
        transition: all 0.2s ease;
    }
    .btn-pd-buy:hover:not(:disabled) {
        background-color: #C83E3B;
        border-color: #C83E3B;
        color: #FFFFFF;
    }
    .btn-pd-cart:disabled,
    .btn-pd-buy:disabled {
        opacity: 0.45;
        cursor: not-allowed;
    }

    /* Minimalist Policy Frame: 13.5px, leading-relaxed, text-gray-600 (#4B5563) */
    .pd-policy-box {
        background-color: #FFFFFF;
        border: 1px solid #E5E7EB;
        border-radius: 0px;
        padding: 16px 18px;
    }
    .pd-policy-item {
        display: flex;
        align-items: center;
        gap: 12px;
        font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
        font-size: 13.5px;
        line-height: 1.625;
        color: #4B5563;
    }
    .pd-policy-item + .pd-policy-item {
        margin-top: 10px;
        padding-top: 10px;
        border-top: 1px solid #F3F4F6;
    }

    /* Product Detail Tabs & Description Content (13.5px, leading-relaxed, #4B5563) */
    .pd-tabs-wrap {
        border: 1px solid #E5E7EB;
        border-radius: 0px;
        background-color: #FFFFFF;
    }
    .pd-nav-tabs {
        border-bottom: 1px solid #E5E7EB;
        padding: 0 20px;
        gap: 24px;
    }
    .pd-nav-tabs .nav-link {
        border: none;
        border-bottom: 2px solid transparent;
        border-radius: 0px;
        padding: 14px 4px !important;
        font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
        font-size: 13px !important;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #6B7280 !important;
        background: transparent;
    }
    .pd-nav-tabs .nav-link:hover {
        color: #111111 !important;
    }
    .pd-nav-tabs .nav-link.active {
        color: #111111 !important;
        border-bottom-color: #111111;
    }
    .pd-spec-list {
        list-style: none;
        padding-left: 0;
        margin-bottom: 0;
    }
    .pd-spec-list li {
        font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
        color: #4B5563;
        font-size: 13.5px;
        line-height: 1.625;
        margin-bottom: 8px;
    }
</style>
@endpush

@section('content')
<div class="container py-4 py-lg-5 product-detail-page max-w-[1200px] mx-auto">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb mb-0" style="font-size: 13px;">
            <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none text-muted">Trang chủ</a></li>
            @if($product->category)
                <li class="breadcrumb-item"><a href="{{ route('category.show', $product->category->id) }}" class="text-decoration-none text-muted">{{ $product->category->name }}</a></li>
            @endif
            <li class="breadcrumb-item active text-dark text-truncate" style="max-width: 320px;" aria-current="page">{{ $product->name }}</li>
        </ol>
    </nav>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="alert alert-dark alert-dismissible fade show rounded-none border-dark mb-4" style="font-size: 13.5px;" role="alert">
            {{ session('success') }}
            <a href="{{ route('cart.index') }}" class="fw-semibold text-dark text-decoration-underline ms-2">Xem giỏ hàng</a>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('info'))
        <div class="alert alert-secondary alert-dismissible fade show rounded-none mb-4" style="font-size: 13.5px;" role="alert">
            {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @php
        $sizes = $product->variants->pluck('size')->filter()->unique('id')->sortBy('sort_order')->values();
        $colors = $product->variants->pluck('color')->filter()->unique('id')->values();
        $firstVariant = $product->variants->firstWhere('image', $product->image) ?? $product->variants->first();

        // Lấy danh sách ảnh độc nhất từ sản phẩm và các biến thể màu
        $galleryImages = collect();
        if ($product->image) {
            $galleryImages->push([
                'url' => $product->image_url,
                'color_id' => $firstVariant ? $firstVariant->color_id : null,
                'color_name' => $firstVariant && $firstVariant->color ? $firstVariant->color->name : 'Mặc định'
            ]);
        }
        foreach ($product->variants as $v) {
            $vImgUrl = $v->image ? $v->image_url : null;
            if ($vImgUrl && !$galleryImages->contains('url', $vImgUrl)) {
                $galleryImages->push([
                    'url' => $vImgUrl,
                    'color_id' => $v->color_id,
                    'color_name' => $v->color ? $v->color->name : ''
                ]);
            }
        }
    @endphp

    <div class="row g-4 g-lg-5 grid-cols-12 align-items-start">
        <!-- Cột Ảnh sản phẩm & Vertical Gallery Thumbnails bên phải (52% / col-md-6) -->
        <div class="col-md-6 col-span-6 pd-col-gallery">
            <div class="pd-gallery-container flex items-start justify-center gap-3 flex-row">
                <div class="product-main-img-wrap w-full max-w-[410px] aspect-[3/4] overflow-hidden rounded-none bg-[#F7F7F7]">
                    <div class="lb-badge-group">
                        @if($product->is_featured)
                            <span class="lb-badge lb-badge-dark rounded-none">BEST SELLER</span>
                        @endif
                        <span class="lb-badge lb-badge-red rounded-none" style="background-color: #C83E3B;">-20%</span>
                    </div>
                    <img id="productImageDisplay"
                         src="{{ $product->image_url }}"
                         alt="{{ $product->name }}"
                         class="product-main-img w-full h-full object-cover object-center transition-opacity duration-200 rounded-none"
                         onerror="this.onerror=null; this.src='https://via.placeholder.com/600x800?text=OWEN+STUDIO';">
                </div>

                @if($galleryImages->count() > 1)
                    <div class="pd-thumbs-column gap-2" id="galleryThumbnailsStrip">
                        @foreach($galleryImages as $thumb)
                            <img src="{{ $thumb['url'] }}"
                                 alt="{{ $thumb['color_name'] }}"
                                 class="product-thumb-img w-[64px] aspect-[3/4] overflow-hidden object-cover rounded-none {{ $loop->first ? 'active' : '' }}"
                                 data-img-url="{{ $thumb['url'] }}"
                                 data-color-id="{{ $thumb['color_id'] ?? '' }}"
                                 title="{{ $thumb['color_name'] }}"
                                 onclick="onThumbnailClick('{{ $thumb['url'] }}', '{{ $thumb['color_id'] }}', this)">
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Cột Thông tin chi tiết & Form Đặt hàng (48% / col-md-6) -->
        <div class="col-md-6 col-span-6 pd-col-info">
            <div class="ps-lg-3">
                @if($product->category)
                    <a href="{{ route('category.show', $product->category->id) }}" class="pd-category-label tracking-wider">
                        {{ $product->category->name }}
                    </a>
                @endif

                <h1 class="pd-product-title text-[22px] md:text-[24px] font-semibold leading-snug">{{ $product->name }}</h1>

                <!-- Mã SKU & Lượt bán -->
                <div class="d-flex align-items-center gap-3 pd-meta-row">
                    <span>Mã SKU: <strong id="skuDisplay" class="text-dark fw-medium">{{ $firstVariant->sku ?? $product->slug }}</strong></span>
                    <span>|</span>
                    <span>Đã bán: <strong class="text-dark fw-medium">240+</strong></span>
                </div>

                <!-- Giá bán khuyến mãi (20px, #C83E3B, chữ đ thường 18px không gạch chân) & Giá gốc (14px) -->
                <div class="pd-price-block d-flex align-items-baseline gap-3">
                    <span class="pd-price-current text-[20px] font-semibold text-[#C83E3B]" id="priceDisplay">{{ number_format($product->price, 0, ',', '.') }}<span class="pd-currency text-[18px]">đ</span></span>
                    <span class="pd-price-original text-[14px] text-gray-400 line-through" id="originalPriceDisplay">{{ number_format($product->price * 1.25, 0, ',', '.') }}đ</span>
                    <span class="pd-discount-tag rounded-none ms-1">-20%</span>
                </div>

                <!-- Form Thêm vào Giỏ hàng -->
                <form id="addToCartForm" action="{{ route('cart.add') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="variant_id" id="selectedVariantId" value="{{ $firstVariant->id ?? '' }}">

                    <!-- 1. Chọn Màu sắc (Nhãn 14px font-medium viết hoa chữ cái đầu, ô vuông 32x32px) -->
                    @if($colors->isNotEmpty())
                        <div class="mb-4">
                            <label class="pd-option-label text-[14px] font-medium text-gray-800 d-block mb-2">
                                Màu sắc: <span id="selectedColorText" class="pd-option-value ms-1">{{ $firstVariant && $firstVariant->color ? $firstVariant->color->name : '' }}</span>
                            </label>
                            <div class="d-flex flex-wrap gap-3 pt-1" id="colorOptionsGroup">
                                @foreach($colors as $color)
                                    @php
                                        $isActiveColor = $firstVariant && $firstVariant->color_id == $color->id;
                                    @endphp
                                    <button type="button"
                                            class="color-option-btn rounded-none {{ $isActiveColor ? 'active ring-1 ring-offset-2 ring-[#111111]' : '' }}"
                                            style="background-color: {{ $color->hex_code ?? '#111111' }};"
                                            data-color-id="{{ $color->id }}"
                                            data-color-name="{{ $color->name }}"
                                            title="{{ $color->name }}"
                                            aria-label="Màu {{ $color->name }}"
                                            onclick="selectColor({{ $color->id }}, '{{ $color->name }}', this)">
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- 2. Chọn Kích cỡ (Nhãn 14px font-medium viết hoa chữ cái đầu, ô vuông 0px) -->
                    @if($sizes->isNotEmpty())
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="pd-option-label text-[14px] font-medium text-gray-800 mb-0">
                                    Kích cỡ: <span id="selectedSizeText" class="pd-option-value ms-1">{{ $firstVariant && $firstVariant->size ? $firstVariant->size->name : '' }}</span>
                                </label>
                                <a href="#sizeGuideModal" data-bs-toggle="modal" class="text-muted text-decoration-underline" style="font-size: 13px;">
                                    Bảng quy đổi kích cỡ
                                </a>
                            </div>
                            <div class="d-flex flex-wrap gap-2" id="sizeOptionsGroup">
                                @foreach($sizes as $size)
                                    <button type="button"
                                            class="variant-option-btn rounded-none {{ ($firstVariant && $firstVariant->size_id == $size->id) ? 'active' : '' }}"
                                            data-size-id="{{ $size->id }}"
                                            data-size-name="{{ $size->name }}"
                                            onclick="selectSize({{ $size->id }}, '{{ $size->name }}', this)">
                                        {{ $size->name }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- 3. Số lượng & Dòng text tồn kho ngang hàng -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2" style="max-width: 260px;">
                            <label class="pd-option-label text-[14px] font-medium text-gray-800 mb-0" for="productQuantityInput">Số lượng:</label>
                            <span id="stockStatusWrapper" style="font-size: 13px; color: #6B7280; font-weight: 400;">
                                Còn <span id="stockCountNumber">{{ $firstVariant->stock ?? $product->stock }}</span> sản phẩm
                            </span>
                        </div>
                        <div class="pd-qty-group rounded-none">
                            <button class="pd-qty-btn rounded-none" type="button" onclick="changeProductQty(-1)" aria-label="Giảm số lượng">&minus;</button>
                            <input type="number" name="quantity" id="productQuantityInput" class="pd-qty-input rounded-none" value="1" min="1" max="99">
                            <button class="pd-qty-btn rounded-none" type="button" onclick="changeProductQty(1)" aria-label="Tăng số lượng">&#43;</button>
                        </div>
                    </div>

                    <!-- 4. Cụm Nút Hành Động (CTA): Chiều cao 44px, cỡ chữ 13px font-semibold tracking-wider -->
                    <div class="d-flex gap-2 mb-2">
                        <button type="button" id="btnAddToCart" class="btn-pd-cart h-[44px] text-[13px] font-semibold tracking-wider rounded-none flex-grow-1" onclick="addToCartAjax()">
                            THÊM VÀO GIỎ
                        </button>
                        <button type="submit" form="wishlistForm" class="btn-pd-wishlist h-[44px] rounded-none" title="Thêm vào sản phẩm yêu thích" aria-label="Thêm vào yêu thích">
                            <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="#4B5563" stroke-width="1.5" stroke-linecap="square" stroke-linejoin="miter">
                                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="mb-4">
                        <button type="submit" name="action" value="buy_now" id="btnBuyNow" class="btn-pd-buy h-[44px] text-[13px] font-semibold tracking-wider rounded-none w-100">
                            MUA NGAY
                        </button>
                    </div>
                </form>

                <!-- Form ẩn xử lý thêm vào Wishlist -->
                <form id="wishlistForm" action="{{ route('account.wishlist.add') }}" method="POST" class="d-none">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                </form>

                <!-- 5. Khung chính sách với icon nét mảnh đơn sắc (stroke-width: 1.5, #4B5563, chữ 13.5px) -->
                <div class="pd-policy-box rounded-none text-[13.5px] leading-relaxed text-gray-600">
                    <div class="pd-policy-item">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#4B5563" stroke-width="1.5" stroke-linecap="square" stroke-linejoin="miter" class="flex-shrink-0">
                            <rect x="1" y="3" width="15" height="13"></rect>
                            <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                            <circle cx="5.5" cy="18.5" r="2.5"></circle>
                            <circle cx="18.5" cy="18.5" r="2.5"></circle>
                        </svg>
                        <span>Miễn phí vận chuyển toàn quốc cho đơn hàng từ <strong class="text-dark fw-semibold">500.000đ</strong>.</span>
                    </div>
                    <div class="pd-policy-item">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#4B5563" stroke-width="1.5" stroke-linecap="square" stroke-linejoin="miter" class="flex-shrink-0">
                            <polyline points="23 4 23 10 17 10"></polyline>
                            <polyline points="1 20 1 14 7 14"></polyline>
                            <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path>
                        </svg>
                        <span>Hỗ trợ đổi size/màu tận nơi trong vòng <strong class="text-dark fw-semibold">30 ngày</strong>.</span>
                    </div>
                    <div class="pd-policy-item">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#4B5563" stroke-width="1.5" stroke-linecap="square" stroke-linejoin="miter" class="flex-shrink-0">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                            <polyline points="9 12 11 14 15 10"></polyline>
                        </svg>
                        <span>Cam kết chuẩn chất lượng phom dáng & chất liệu OWEN STUDIO.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Thông tin Chi tiết & Thông số kỹ thuật sản phẩm (Tabs vuông góc, nội dung 13.5px) -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="pd-tabs-wrap rounded-none">
                <ul class="nav nav-tabs pd-nav-tabs" id="productDetailTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active rounded-none" id="desc-tab" data-bs-toggle="tab" data-bs-target="#desc-pane" type="button" role="tab">
                            THÔNG SỐ & MÔ TẢ SẢN PHẨM
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-none" id="size-tab" data-bs-toggle="tab" data-bs-target="#size-pane" type="button" role="tab">
                            BẢNG QUY ĐỔI KÍCH CỠ
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-none" id="policy-tab" data-bs-toggle="tab" data-bs-target="#policy-pane" type="button" role="tab">
                            CHÍNH SÁCH ĐỔI TRẢ & BẢO HÀNH
                        </button>
                    </li>
                </ul>
                <div class="p-4 tab-content text-[13.5px] leading-relaxed text-gray-600">
                    <!-- Tab 1: Mô tả kỹ thuật (Gạch đầu dòng •) -->
                    <div class="tab-pane fade show active" id="desc-pane" role="tabpanel">
                        <h6 class="fw-semibold mb-3" style="font-size: 14px; color: #111111;">Thông số kỹ thuật & Đặc tính sản phẩm</h6>
                        @php
                            $descLines = array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', (string) $product->description)));
                        @endphp
                        <ul class="pd-spec-list text-[13.5px] leading-relaxed text-gray-600">
                            @if(count($descLines) > 1)
                                @foreach($descLines as $line)
                                    <li>{{ Str::startsWith($line, '•') ? $line : '• ' . ltrim($line, '-*• ') }}</li>
                                @endforeach
                            @else
                                @if(!empty($product->description))
                                    <li>{{ Str::startsWith(trim($product->description), '•') ? trim($product->description) : '• ' . trim($product->description) }}</li>
                                @endif
                                <li>• Thành phần vải: 92% Polyester Microfiber + 8% Spandex co giãn 4 chiều, bề mặt đanh mịn và giữ phom ổn định.</li>
                                <li>• Công nghệ dệt: Kiểu dệt mắt chim (Bird's Eye Pique) thoáng khí, hỗ trợ thoát ẩm nhanh và hạn chế nhăn nhàu.</li>
                                <li>• Phom dáng: Regular Fit chuẩn phom nam giới Việt, dễ dàng sơ vin hoặc mặc thả ngoài lịch sự.</li>
                                <li>• Chi tiết hoàn thiện: Bo cổ và bo tay dệt rib mật độ cao chống bai dão, đường may khóa sườn chắc chắn.</li>
                            @endif
                        </ul>
                    </div>

                    <!-- Tab 2: Bảng Size -->
                    <div class="tab-pane fade" id="size-pane" role="tabpanel">
                        <h6 class="fw-semibold mb-3" style="font-size: 14px; color: #111111;">Bảng thông số kích cỡ tiêu chuẩn (Nam)</h6>
                        <div class="table-responsive" style="max-width: 640px;">
                            <table class="table table-bordered text-center align-middle mb-0" style="font-size: 13.5px;">
                                <thead style="background-color: #111111; color: #FFFFFF;">
                                    <tr>
                                        <th>Size</th>
                                        <th>Chiều cao (cm)</th>
                                        <th>Cân nặng (kg)</th>
                                        <th>Rộng vai (cm)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr><td><strong>S</strong></td><td>160 - 166</td><td>52 - 58</td><td>42</td></tr>
                                    <tr><td><strong>M</strong></td><td>167 - 172</td><td>59 - 66</td><td>44</td></tr>
                                    <tr><td><strong>L</strong></td><td>173 - 178</td><td>67 - 74</td><td>46</td></tr>
                                    <tr><td><strong>XL</strong></td><td>179 - 184</td><td>75 - 82</td><td>48</td></tr>
                                    <tr><td><strong>XXL</strong></td><td>185 - 190</td><td>83 - 90</td><td>50</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Tab 3: Chính sách -->
                    <div class="tab-pane fade" id="policy-pane" role="tabpanel">
                        <h6 class="fw-semibold mb-3" style="font-size: 14px; color: #111111;">Quy định đổi trả trong 30 ngày</h6>
                        <ul class="pd-spec-list text-[13.5px] leading-relaxed text-gray-600">
                            <li>• Áp dụng đổi size hoặc đổi mẫu trong vòng 30 ngày kể từ ngày nhận hàng.</li>
                            <li>• Sản phẩm đổi cần giữ nguyên tem mác, hóa đơn mua hàng và chưa qua giặt ủi hoặc sử dụng.</li>
                            <li>• Miễn phí 100% chi phí vận chuyển hai chiều nếu sản phẩm phát sinh lỗi từ nhà sản xuất.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sản phẩm liên quan (Đồng bộ 100% Card Trang chủ) -->
    @if(isset($relatedProducts) && $relatedProducts->isNotEmpty())
        <section class="mt-5 pt-4">
            <div class="lb-section-header">
                <div>
                    <span class="lb-section-subtitle" style="color: #C83E3B;">RECOMMENDED FOR YOU</span>
                    <h2 class="lb-section-title" style="font-family: 'Plus Jakarta Sans', 'Inter', sans-serif; font-size: 20px; font-weight: 700;">SẢN PHẨM CÙNG DANH MỤC</h2>
                </div>
                @if($product->category)
                    <a href="{{ route('category.show', $product->category->id) }}" class="lb-view-all" style="font-size: 13px;">
                        XEM TẤT CẢ <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                @endif
            </div>

            <div class="row g-4">
                @foreach($relatedProducts as $relProduct)
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="lb-product-card rounded-none h-100">
                            <div class="lb-product-thumb rounded-none">
                                <div class="lb-badge-group">
                                    <span class="lb-badge lb-badge-dark rounded-none">BEST SELLER</span>
                                    <span class="lb-badge lb-badge-red rounded-none" style="background-color: #C83E3B;">-20%</span>
                                </div>
                                <a href="{{ route('products.show', $relProduct->id) }}">
                                    <img src="{{ $relProduct->image_url }}"
                                         class="lb-product-img rounded-none"
                                         alt="{{ $relProduct->name }}"
                                         onerror="this.onerror=null; this.src='https://via.placeholder.com/400x520?text=OWEN+STUDIO';">
                                </a>
                                <a href="{{ route('products.show', $relProduct->id) }}" class="lb-quick-view rounded-none">
                                    MUA NGAY
                                </a>
                            </div>

                            <div>
                                <div class="lb-product-cat">{{ $relProduct->category->name ?? 'LOCAL BRAND' }}</div>
                                <a href="{{ route('products.show', $relProduct->id) }}" class="lb-product-name" title="{{ $relProduct->name }}">
                                    {{ $relProduct->name }}
                                </a>
                                <div class="d-flex align-items-baseline">
                                    <span class="lb-product-price" style="color: #C83E3B;">{{ number_format($relProduct->price, 0, ',', '.') }}đ</span>
                                    <span class="lb-product-price-old">{{ number_format($relProduct->price * 1.25, 0, ',', '.') }}đ</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif
</div>

<!-- Modal Bảng Size (Vuông góc 0px) -->
<div class="modal fade" id="sizeGuideModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-none border-dark">
            <div class="modal-header rounded-none border-bottom">
                <h5 class="modal-title fw-semibold" style="font-family: 'Plus Jakarta Sans', 'Inter', sans-serif; font-size: 16px;">Bảng hướng dẫn chọn kích cỡ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-4">
                <table class="table table-bordered align-middle mb-0" style="font-size: 13.5px;">
                    <thead style="background-color: #111111; color: #FFFFFF;">
                        <tr><th>Size</th><th>Chiều cao (cm)</th><th>Cân nặng (kg)</th></tr>
                    </thead>
                    <tbody>
                        <tr><td><strong>S</strong></td><td>160 - 166</td><td>52 - 58kg</td></tr>
                        <tr><td><strong>M</strong></td><td>167 - 172</td><td>59 - 66kg</td></tr>
                        <tr><td><strong>L</strong></td><td>173 - 178</td><td>67 - 74kg</td></tr>
                        <tr><td><strong>XL</strong></td><td>179 - 184</td><td>75 - 82kg</td></tr>
                        <tr><td><strong>XXL</strong></td><td>185 - 190</td><td>83 - 90kg</td></tr>
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
    const defaultProductImage = "{{ $product->image_url }}";
    const baseProductPrice = {{ (int) $product->price }};

    let selectedSizeId = {{ $firstVariant->size_id ?? 'null' }};
    let selectedColorId = {{ $firstVariant->color_id ?? 'null' }};

    // Hàm chuyển ảnh lớn với hiệu ứng mượt
    function switchMainImage(imgUrl) {
        if (!imgUrl) return;
        const mainImg = document.getElementById('productImageDisplay');
        if (!mainImg) return;

        if (mainImg.getAttribute('src') !== imgUrl) {
            mainImg.style.opacity = '0.4';
            setTimeout(() => {
                mainImg.src = imgUrl;
                mainImg.style.opacity = '1';
            }, 120);
        }

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

        let matched = variantsData.find(v => v.size_id == selectedSizeId && v.color_id == selectedColorId);
        if (!matched && selectedColorId !== null) {
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
        document.querySelectorAll('#colorOptionsGroup .color-option-btn').forEach(b => {
            b.classList.remove('active', 'ring-1', 'ring-offset-2', 'ring-[#111111]');
        });
        btn.classList.add('active', 'ring-1', 'ring-offset-2', 'ring-[#111111]');

        const colorText = document.getElementById('selectedColorText');
        if (colorText) colorText.textContent = colorName;

        // 1. Tìm biến thể cho màu này
        let matched = variantsData.find(v => v.color_id == selectedColorId && v.size_id == selectedSizeId);
        if (!matched && selectedSizeId !== null) {
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

        // 2. Đổi ảnh chính theo màu sắc biến thể
        const colorVariantWithImg = matched || variantsData.find(v => v.color_id == selectedColorId && (v.image_url || v.image));
        const targetImg = colorVariantWithImg ? (colorVariantWithImg.image_url || colorVariantWithImg.image) : null;
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
            if (skuDisplay) skuDisplay.textContent = matched.sku || 'OWEN-' + matched.id;

            // Đổi giá (Hiển thị chữ 'đ' thường cỡ 18px, không gạch chân)
            const price = matched.effective_price || matched.price || baseProductPrice;
            if (priceDisplay) {
                priceDisplay.innerHTML = new Intl.NumberFormat('vi-VN').format(price) + '<span class="pd-currency text-[18px]">đ</span>';
            }
            if (origPriceDisplay) {
                origPriceDisplay.textContent = new Intl.NumberFormat('vi-VN').format(Math.round(price * 1.25)) + 'đ';
            }

            // Đổi ảnh nếu biến thể có ảnh riêng
            if (matched.image_url || matched.image) {
                switchMainImage(matched.image_url || matched.image);
            }

            // Kiểm tra tồn kho (Text mảnh màu xám trung tính, không dùng badge xanh)
            if (matched.stock > 0) {
                if (stockStatusWrapper) {
                    stockStatusWrapper.innerHTML = `Còn <span id="stockCountNumber">${matched.stock}</span> sản phẩm`;
                }
                btnAdd.disabled = false;
                btnBuy.disabled = false;
                btnAdd.textContent = 'THÊM VÀO GIỎ';
                btnBuy.textContent = 'MUA NGAY';
            } else {
                if (stockStatusWrapper) {
                    stockStatusWrapper.innerHTML = `<span style="color: #C83E3B;">Tạm hết hàng</span>`;
                }
                btnAdd.disabled = true;
                btnBuy.disabled = true;
                btnAdd.textContent = 'TẠM HẾT HÀNG';
                btnBuy.textContent = 'TẠM HẾT HÀNG';
            }
        } else {
            if (variantInput) variantInput.value = '';
            if (stockStatusWrapper) {
                stockStatusWrapper.innerHTML = `Tùy chọn chưa có sẵn`;
            }
            btnAdd.disabled = true;
            btnBuy.disabled = true;
            btnAdd.textContent = 'KHÔNG KHẢ DỤNG';
            btnBuy.textContent = 'KHÔNG KHẢ DỤNG';
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
        const originalText = 'THÊM VÀO GIỎ';

        btnAdd.disabled = true;
        btnAdd.textContent = 'ĐANG THÊM...';

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
                const badge = document.getElementById('cartCountBadge');
                if (badge) badge.textContent = data.cart_count;

                btnAdd.style.backgroundColor = '#111111';
                btnAdd.style.color = '#FFFFFF';
                btnAdd.textContent = 'ĐÃ THÊM VÀO GIỎ';

                setTimeout(() => {
                    btnAdd.style.backgroundColor = '';
                    btnAdd.style.color = '';
                    btnAdd.disabled = false;
                    btnAdd.textContent = originalText;
                }, 1500);
            } else {
                alert(data.message || "Không thể thêm vào giỏ hàng.");
                btnAdd.disabled = false;
                btnAdd.textContent = originalText;
            }
        })
        .catch(err => {
            console.error(err);
            alert("Đã xảy ra lỗi khi thêm sản phẩm vào giỏ hàng.");
            btnAdd.disabled = false;
            btnAdd.textContent = originalText;
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        updateSelectedVariant();
    });
</script>
@endpush
