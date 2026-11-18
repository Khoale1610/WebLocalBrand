@extends('layouts.app')

@section('title', 'Tìm Kiếm: ' . ($keyword ?: 'Tất cả sản phẩm') . ' — OWEN STUDIO')

@push('styles')
<style>
    /* OWEN STUDIO — Minimalist Square Search & Catalog Design System */
    .search-page {
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

    /* Search Toolbar & Header */
    .sr-header-wrap {
        padding-bottom: 18px;
        margin-bottom: 28px;
        border-bottom: 1px solid #E5E7EB;
    }
    .sr-kicker {
        display: block;
        font-size: 11.5px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.14em;
        color: #C83E3B;
        margin-bottom: 4px;
    }
    .sr-title {
        font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
        font-size: 22px;
        font-weight: 600;
        line-height: 1.375;
        color: #111111;
        margin-bottom: 4px;
    }
    @media (min-width: 768px) {
        .sr-title {
            font-size: 24px;
        }
    }
    .sr-keyword-highlight {
        color: #C83E3B;
    }
    .sr-count-text {
        font-size: 13.5px;
        color: #6B7280;
        margin-bottom: 0;
    }

    /* Filter Selects: Square 0px, 40px height, thin border #E5E7EB */
    .sr-select {
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
    .sr-select:hover,
    .sr-select:focus {
        border-color: #111111 !important;
        outline: none;
        box-shadow: none !important;
    }

    /* Empty State Box */
    .sr-empty-box {
        background-color: #FFFFFF;
        border: 1px solid #E5E7EB;
        border-radius: 0px;
        padding: 48px 24px;
        text-align: center;
    }
    .sr-empty-icon {
        width: 52px;
        height: 52px;
        border: 1px solid #E5E7EB;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 16px;
    }
    .btn-sr-primary {
        height: 44px;
        padding: 0 24px;
        background-color: #111111;
        color: #FFFFFF;
        border: 1px solid #111111;
        border-radius: 0px !important;
        font-size: 13px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    .btn-sr-primary:hover {
        background-color: #C83E3B;
        border-color: #C83E3B;
        color: #FFFFFF;
    }
    .btn-sr-outline {
        height: 44px;
        padding: 0 24px;
        background-color: #FFFFFF;
        color: #111111;
        border: 1px solid #111111;
        border-radius: 0px !important;
        font-size: 13px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    .btn-sr-outline:hover {
        background-color: #111111;
        color: #FFFFFF;
    }
</style>
@endpush

@section('content')
<div class="container py-4 py-lg-5 search-page max-w-[1200px] mx-auto">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb mb-0" style="font-size: 13px;">
            <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none text-muted">Trang chủ</a></li>
            <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Tìm kiếm</li>
        </ol>
    </nav>

    <!-- Tiêu đề & Bộ lọc sắp xếp -->
    <div class="sr-header-wrap d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3">
        <div>
            <span class="sr-kicker">SEARCH &amp; CATALOG</span>
            <h1 class="sr-title text-[22px] md:text-[24px] font-semibold leading-snug">
                @if(!empty($keyword))
                    Kết quả tìm kiếm: "<span class="sr-keyword-highlight">{{ $keyword }}</span>"
                @else
                    Tất cả sản phẩm
                @endif
            </h1>
            <p class="sr-count-text">
                Tìm thấy <strong class="text-dark fw-semibold">{{ $products->total() }}</strong> sản phẩm phù hợp
            </p>
        </div>

        <!-- Bộ lọc Danh mục & Sắp xếp -->
        <form action="{{ route('products.search') }}" method="GET" class="d-flex flex-wrap align-items-center gap-2" id="searchFilterForm">
            <input type="hidden" name="query" value="{{ $keyword }}">

            <select name="category_id" class="form-select sr-select rounded-none" style="width: auto;" onchange="this.form.submit()">
                <option value="">Tất cả danh mục</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }} ({{ $cat->products_count }})
                    </option>
                @endforeach
            </select>

            <select name="sort" class="form-select sr-select rounded-none" style="width: auto;" onchange="this.form.submit()">
                <option value="newest" {{ $sort == 'newest' ? 'selected' : '' }}>Sắp xếp: Mới nhất</option>
                <option value="price_asc" {{ $sort == 'price_asc' ? 'selected' : '' }}>Giá: Thấp đến Cao</option>
                <option value="price_desc" {{ $sort == 'price_desc' ? 'selected' : '' }}>Giá: Cao đến Thấp</option>
                <option value="name_asc" {{ $sort == 'name_asc' ? 'selected' : '' }}>Tên: A - Z</option>
            </select>
        </form>
    </div>

    @if($products->isEmpty())
        <!-- Empty State -->
        <div class="sr-empty-box rounded-none my-3">
            <div class="sr-empty-icon rounded-none">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#4B5563" stroke-width="1.5" stroke-linecap="square" stroke-linejoin="miter">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </div>
            <h5 class="fw-semibold text-dark mb-2" style="font-size: 18px;">Không tìm thấy sản phẩm phù hợp</h5>
            <p class="text-muted mb-4 mx-auto" style="font-size: 13.5px; max-width: 480px;">
                Vui lòng thử lại với từ khóa khác như "Áo sơ mi", "Polo", "Quần tây" hoặc đặt lại bộ lọc danh mục.
            </p>
            <div class="d-flex justify-content-center flex-wrap gap-2">
                <a href="{{ route('products.search') }}" class="btn-sr-outline h-[44px] text-[13px] font-semibold tracking-wider rounded-none">
                    XEM TẤT CẢ SẢN PHẨM
                </a>
                <a href="{{ url('/') }}" class="btn-sr-primary h-[44px] text-[13px] font-semibold tracking-wider rounded-none">
                    VỀ TRANG CHỦ
                </a>
            </div>
        </div>
    @else
        <!-- Lưới sản phẩm đồng bộ 100% với Card Trang chủ -->
        <div class="row g-4">
            @foreach($products as $prod)
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="lb-product-card rounded-none h-100">
                        <div class="lb-product-thumb rounded-none">
                            <div class="lb-badge-group">
                                @if($prod->is_featured)
                                    <span class="lb-badge lb-badge-dark rounded-none">BEST SELLER</span>
                                @endif
                                <span class="lb-badge lb-badge-red rounded-none" style="background-color: #C83E3B;">-20%</span>
                            </div>
                            <a href="{{ route('products.show', $prod->id) }}">
                                <img src="{{ $prod->image_url }}"
                                     class="lb-product-img rounded-none"
                                     alt="{{ $prod->name }}"
                                     onerror="this.onerror=null; this.src='https://via.placeholder.com/400x520?text=OWEN+STUDIO';">
                            </a>
                            <a href="{{ route('products.show', $prod->id) }}" class="lb-quick-view rounded-none">
                                MUA NGAY
                            </a>
                        </div>

                        <div>
                            <div class="lb-product-cat">{{ $prod->category->name ?? 'LOCAL BRAND' }}</div>
                            <a href="{{ route('products.show', $prod->id) }}" class="lb-product-name" title="{{ $prod->name }}">
                                {{ $prod->name }}
                            </a>
                            <div class="d-flex align-items-baseline">
                                <span class="lb-product-price" style="color: #C83E3B;">{{ number_format($prod->price, 0, ',', '.') }}đ</span>
                                <span class="lb-product-price-old">{{ number_format($prod->price * 1.25, 0, ',', '.') }}đ</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Phân trang vuông góc 0px -->
        <div class="d-flex justify-content-center mt-5">
            {{ $products->links('pagination::bootstrap-4') }}
        </div>
    @endif
</div>
@endsection
