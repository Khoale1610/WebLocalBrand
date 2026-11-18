@extends('layouts.app')

@section('title', $category->name . ' — OWEN STUDIO')

@push('styles')
<style>
    /* OWEN STUDIO — Minimalist Square Category Page Design System */
    .category-page {
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

    /* Filter Sidebar */
    .cat-filter-box {
        background-color: #FFFFFF;
        border: 1px solid #E5E7EB;
        border-radius: 0px;
        padding: 22px;
    }
    .cat-filter-header {
        font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
        font-size: 15px;
        font-weight: 600;
        color: #111111;
        padding-bottom: 14px;
        margin-bottom: 18px;
        border-bottom: 1px solid #E5E7EB;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .cat-filter-group {
        padding-bottom: 18px;
        margin-bottom: 18px;
        border-bottom: 1px solid #F3F4F6;
    }
    .cat-filter-group:last-of-type {
        border-bottom: none;
        padding-bottom: 6px;
    }
    .cat-filter-title {
        font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
        font-size: 13.5px;
        font-weight: 600;
        color: #111111;
        margin-bottom: 12px;
        display: block;
    }
    .cat-check-item {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 8px;
    }
    .cat-check-item:last-child {
        margin-bottom: 0;
    }
    .cat-check-input {
        width: 16px;
        height: 16px;
        margin: 0;
        border: 1px solid #D1D5DB;
        border-radius: 0px !important;
        cursor: pointer;
        box-shadow: none !important;
        accent-color: #111111;
    }
    .cat-check-input:checked {
        background-color: #111111 !important;
        border-color: #111111 !important;
    }
    .cat-check-label {
        font-size: 13.5px;
        color: #4B5563;
        cursor: pointer;
        user-select: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .cat-color-swatch {
        width: 16px;
        height: 16px;
        display: inline-block;
        border: 1px solid #E5E7EB;
        border-radius: 0px;
        flex-shrink: 0;
    }

    /* Filter Buttons */
    .btn-cat-apply {
        height: 42px;
        width: 100%;
        background-color: #111111;
        color: #FFFFFF;
        border: 1px solid #111111;
        border-radius: 0px !important;
        font-size: 12.5px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        transition: all 0.2s ease;
    }
    .btn-cat-apply:hover {
        background-color: #C83E3B;
        border-color: #C83E3B;
        color: #FFFFFF;
    }
    .btn-cat-reset {
        height: 40px;
        width: 100%;
        background-color: #FFFFFF;
        color: #4B5563;
        border: 1px solid #E5E7EB;
        border-radius: 0px !important;
        font-size: 12.5px;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: all 0.15s ease;
    }
    .btn-cat-reset:hover {
        border-color: #111111;
        color: #111111;
    }

    /* Category Header & Sort Bar */
    .cat-toolbar {
        padding-bottom: 16px;
        margin-bottom: 24px;
        border-bottom: 1px solid #E5E7EB;
    }
    .cat-kicker {
        display: block;
        font-size: 11.5px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.14em;
        color: #C83E3B;
        margin-bottom: 4px;
    }
    .cat-title {
        font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
        font-size: 22px;
        font-weight: 600;
        line-height: 1.375;
        color: #111111;
        margin-bottom: 2px;
    }
    @media (min-width: 768px) {
        .cat-title {
            font-size: 24px;
        }
    }
    .cat-count {
        font-size: 13px;
        color: #6B7280;
    }
    .cat-sort-select {
        height: 40px;
        font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
        font-size: 13.5px;
        font-weight: 500;
        color: #111111;
        background-color: #FFFFFF;
        border: 1px solid #E5E7EB;
        border-radius: 0px !important;
        padding: 0 34px 0 14px;
        box-shadow: none !important;
        cursor: pointer;
        transition: border-color 0.15s ease;
    }
    .cat-sort-select:hover,
    .cat-sort-select:focus {
        border-color: #111111 !important;
        outline: none;
    }

    /* Empty State */
    .cat-empty-box {
        background-color: #FFFFFF;
        border: 1px solid #E5E7EB;
        border-radius: 0px;
        padding: 48px 24px;
        text-align: center;
    }
</style>
@endpush

@section('content')
<div class="container py-4 py-lg-5 category-page max-w-[1200px] mx-auto">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb mb-0" style="font-size: 13px;">
            <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none text-muted">Trang chủ</a></li>
            <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">{{ $category->name }}</li>
        </ol>
    </nav>

    <div class="row g-4 align-items-start">
        <!-- BỘ LỌC TÌM KIẾM (SIDEBAR - col-lg-3) -->
        <div class="col-lg-3 col-md-4">
            <div class="cat-filter-box rounded-none sticky-top" style="top: 96px;">
                <div class="cat-filter-header">
                    <span>Bộ lọc sản phẩm</span>
                    @if(request()->hasAny(['price', 'size', 'color']))
                        <a href="{{ route('category.show', $category->id) }}" class="text-decoration-underline" style="font-size: 12px; color: #C83E3B; font-weight: 500;">Đặt lại</a>
                    @endif
                </div>

                <form action="{{ route('category.show', $category->id) }}" method="GET" id="filter-form">
                    <!-- Input ẩn đồng bộ thanh sắp xếp -->
                    <input type="hidden" name="sort" id="hidden-sort-input" value="{{ request('sort', 'newest') }}">

                    <!-- 1. Lọc theo Khoảng giá -->
                    <div class="cat-filter-group">
                        <span class="cat-filter-title">Mức giá</span>
                        <div class="cat-check-item">
                            <input class="form-check-input cat-check-input rounded-none" type="radio" name="price" value="" id="priceAll" {{ !request('price') ? 'checked' : '' }}>
                            <label class="cat-check-label" for="priceAll">Tất cả mức giá</label>
                        </div>
                        <div class="cat-check-item">
                            <input class="form-check-input cat-check-input rounded-none" type="radio" name="price" value="under_300" id="price1" {{ request('price') == 'under_300' ? 'checked' : '' }}>
                            <label class="cat-check-label" for="price1">Dưới 300.000đ</label>
                        </div>
                        <div class="cat-check-item">
                            <input class="form-check-input cat-check-input rounded-none" type="radio" name="price" value="300_500" id="price2" {{ request('price') == '300_500' ? 'checked' : '' }}>
                            <label class="cat-check-label" for="price2">300.000đ – 500.000đ</label>
                        </div>
                        <div class="cat-check-item">
                            <input class="form-check-input cat-check-input rounded-none" type="radio" name="price" value="500_1000" id="price3" {{ request('price') == '500_1000' ? 'checked' : '' }}>
                            <label class="cat-check-label" for="price3">500.000đ – 1.000.000đ</label>
                        </div>
                        <div class="cat-check-item">
                            <input class="form-check-input cat-check-input rounded-none" type="radio" name="price" value="over_1000" id="price4" {{ request('price') == 'over_1000' ? 'checked' : '' }}>
                            <label class="cat-check-label" for="price4">Trên 1.000.000đ</label>
                        </div>
                    </div>

                    <!-- 2. Lọc theo Size -->
                    <div class="cat-filter-group">
                        <span class="cat-filter-title">Kích cỡ</span>
                        @foreach($sizes as $size)
                            <div class="cat-check-item">
                                <input class="form-check-input cat-check-input rounded-none" type="checkbox" name="size[]" value="{{ $size->id }}" id="size{{ $size->id }}"
                                    {{ in_array($size->id, request('size', [])) ? 'checked' : '' }}>
                                <label class="cat-check-label" for="size{{ $size->id }}">{{ $size->name }}</label>
                            </div>
                        @endforeach
                    </div>

                    <!-- 3. Lọc theo Màu sắc (Ô vuông sắc cạnh 0px) -->
                    <div class="cat-filter-group">
                        <span class="cat-filter-title">Màu sắc</span>
                        @foreach($colors as $color)
                            <div class="cat-check-item">
                                <input class="form-check-input cat-check-input rounded-none" type="checkbox" name="color[]" value="{{ $color->id }}" id="color{{ $color->id }}"
                                    {{ in_array($color->id, request('color', [])) ? 'checked' : '' }}>
                                <label class="cat-check-label" for="color{{ $color->id }}">
                                    <span class="cat-color-swatch rounded-none" style="background-color: {{ $color->hex_code ?? '#111111' }};"></span>
                                    {{ $color->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>

                    <button type="submit" class="btn-cat-apply rounded-none mb-2">Áp dụng lọc</button>
                    <a href="{{ route('category.show', $category->id) }}" class="btn-cat-reset rounded-none">Xóa bộ lọc</a>
                </form>
            </div>
        </div>

        <!-- HIỂN THỊ DANH SÁCH SẢN PHẨM (col-lg-9) -->
        <div class="col-lg-9 col-md-8">
            <div class="cat-toolbar d-flex flex-column flex-sm-row justify-content-between align-items-sm-end gap-3">
                <div>
                    <span class="cat-kicker">CATEGORY COLLECTION</span>
                    <h1 class="cat-title text-[22px] md:text-[24px] font-semibold leading-snug">{{ $category->name }}</h1>
                    <div class="cat-count">Hiển thị <strong class="text-dark fw-semibold">{{ $products->total() }}</strong> sản phẩm</div>
                </div>

                <!-- 4. Sắp xếp sản phẩm -->
                <div class="d-flex align-items-center gap-2">
                    <label class="text-nowrap mb-0" style="font-size: 13px; color: #6B7280;">Sắp xếp:</label>
                    <select class="form-select cat-sort-select rounded-none" style="width: auto;"
                            onchange="document.getElementById('hidden-sort-input').value = this.value; document.getElementById('filter-form').submit();">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá: Thấp đến Cao</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá: Cao xuống Thấp</option>
                        <option value="best_seller" {{ request('sort') == 'best_seller' ? 'selected' : '' }}>Bán chạy nhất</option>
                    </select>
                </div>
            </div>

            <!-- Lưới Sản Phẩm (Đồng bộ 100% Card Trang chủ) -->
            @if($products->isNotEmpty())
                <div class="row g-4">
                    @foreach($products as $product)
                        <div class="col-6 col-lg-4">
                            <div class="lb-product-card rounded-none h-100">
                                <div class="lb-product-thumb rounded-none">
                                    <div class="lb-badge-group">
                                        @if($product->is_featured)
                                            <span class="lb-badge lb-badge-dark rounded-none">BEST SELLER</span>
                                        @endif
                                        <span class="lb-badge lb-badge-red rounded-none" style="background-color: #C83E3B;">-20%</span>
                                    </div>
                                    <a href="{{ route('products.show', $product->id) }}">
                                        <img src="{{ $product->image_url }}"
                                             class="lb-product-img rounded-none"
                                             alt="{{ $product->name }}"
                                             onerror="this.onerror=null; this.src='https://via.placeholder.com/400x520?text=OWEN+STUDIO';">
                                    </a>
                                    <a href="{{ route('products.show', $product->id) }}" class="lb-quick-view rounded-none">
                                        MUA NGAY
                                    </a>
                                </div>

                                <div>
                                    <div class="lb-product-cat">{{ $category->name }}</div>
                                    <a href="{{ route('products.show', $product->id) }}" class="lb-product-name" title="{{ $product->name }}">
                                        {{ $product->name }}
                                    </a>
                                    <div class="d-flex align-items-baseline">
                                        <span class="lb-product-price" style="color: #C83E3B;">{{ number_format($product->price, 0, ',', '.') }}đ</span>
                                        <span class="lb-product-price-old">{{ number_format($product->price * 1.25, 0, ',', '.') }}đ</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Phân trang -->
                <div class="d-flex justify-content-center mt-5">
                    {{ $products->links('pagination::bootstrap-4') }}
                </div>
            @else
                <div class="cat-empty-box rounded-none">
                    <div class="mb-3 d-inline-flex align-items-center justify-content-center" style="width: 52px; height: 52px; border: 1px solid #E5E7EB;">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#4B5563" stroke-width="1.5" stroke-linecap="square" stroke-linejoin="miter">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </div>
                    <h5 class="fw-semibold text-dark mb-2" style="font-size: 18px;">Không tìm thấy sản phẩm phù hợp</h5>
                    <p class="text-muted mb-4 mx-auto" style="font-size: 13.5px; max-width: 460px;">
                        Vui lòng thử thay đổi điều kiện lọc (Mức giá, Kích cỡ, Màu sắc) để tìm được sản phẩm ưng ý.
                    </p>
                    <a href="{{ route('category.show', $category->id) }}" class="btn-cat-apply rounded-none d-inline-flex align-items-center justify-content-center text-decoration-none" style="max-width: 200px;">
                        XÓA BỘ LỌC
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
