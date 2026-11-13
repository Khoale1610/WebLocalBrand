@extends('layouts.app')

@section('title', 'PRIVATE ARCHIVE SALE UP TO 50% — OWEN STUDIO')

@push('styles')
<style>
    /* Archive Sale Hero - Đồng bộ chiều cao 600px với Trang chủ & Bộ sưu tập */
    .lb-sale-hero {
        position: relative;
        height: 600px;
        background-color: #0d0d0d;
        color: #fff;
        display: flex;
        align-items: center;
        overflow: hidden;
        border-bottom: 4px solid #d32f2f;
    }
    .lb-sale-hero-bg {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: 0.28;
        filter: grayscale(35%) contrast(1.15);
    }
    .lb-sale-hero-content {
        position: relative;
        z-index: 2;
        width: 100%;
    }
    .lb-sale-title {
        font-family: 'Oswald', 'Plus Jakarta Sans', sans-serif;
        font-size: clamp(2.6rem, 5vw, 4.4rem);
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        line-height: 1.08;
        color: #fff;
        margin-bottom: 18px;
    }
    .lb-sale-highlight {
        color: #ef4444;
    }

    /* Voucher Perks Bar */
    .lb-voucher-box {
        border: 1px dashed #111;
        background-color: #fafafa;
        padding: 18px 22px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        transition: all 0.2s ease;
    }
    .lb-voucher-box:hover {
        background-color: #111;
        color: #fff;
        border-style: solid;
    }
    .lb-voucher-code {
        font-family: 'Oswald', sans-serif;
        font-size: 1.15rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        background-color: #d32f2f;
        color: #fff;
        padding: 4px 12px;
    }

    /* Filter Pills & Toolbar */
    .lb-sale-toolbar {
        border-top: 2px solid #111;
        border-bottom: 2px solid #111;
        padding: 16px 0;
        margin-bottom: 36px;
    }
    .lb-price-pill {
        display: inline-block;
        padding: 8px 16px;
        font-size: 0.84rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        text-decoration: none;
        color: #111;
        border: 1px solid #d4d4d8;
        background-color: #fff;
        transition: all 0.2s;
    }
    .lb-price-pill:hover,
    .lb-price-pill.active {
        background-color: #111;
        color: #fff;
        border-color: #111;
    }
    .lb-sort-select {
        border: 1px solid #111;
        border-radius: 0;
        font-size: 0.88rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        padding: 8px 34px 8px 14px;
        color: #111;
    }
</style>
@endpush

@section('content')

<!-- 1. ARCHIVE SALE HERO -->
<div class="lb-sale-hero">
    <img src="https://images.unsplash.com/photo-1523398002811-999ca8dec234?q=80&w=1800&auto=format&fit=crop" alt="Archive Sale Banner" class="lb-sale-hero-bg">
    <div class="container lb-sale-hero-content">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb lb-breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-white-50 text-decoration-none">HOME</a></li>
                <li class="breadcrumb-item"><a href="{{ route('collections.index') }}" class="text-white-50 text-decoration-none">COLLECTIONS</a></li>
                <li class="breadcrumb-item active text-danger" aria-current="page">ARCHIVE SALE</li>
            </ol>
        </nav>

        <div class="row align-items-end">
            <div class="col-lg-8">
                <span class="lb-kicker">END OF SEASON CLEARANCE // LIMITED STOCK</span>
                <h1 class="lb-sale-title">
                    ARCHIVE SALE<br>
                    <span class="lb-sale-highlight">UP TO 50% OFF</span> SELECTED ITEMS
                </h1>
                <p class="text-white-50 mb-0" style="max-width: 620px; font-size: 1.06rem;">
                    Cơ hội cuối cùng để sở hữu các thiết kế Signature & Streetwear giới hạn từ OWEN STUDIO với mức ưu đãi tốt nhất trong năm. Số lượng biến thể size có hạn.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                <div class="d-inline-block border border-secondary p-3 text-start bg-black bg-opacity-50">
                    <div class="text-uppercase fw-bold text-danger small" style="letter-spacing: 0.14em;">ĐANG MỞ BÁN TRỰC TUYẾN</div>
                    <div class="fs-3 fw-bold text-white" style="font-family: 'Oswald', sans-serif;">{{ $products->total() }} THIẾT KẾ ƯU ĐÃI</div>
                    <div class="small text-white-50">Áp dụng đồng thời cùng Voucher Giỏ Hàng</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <!-- 2. VOUCHER HIGHLIGHT STRIP -->
    <div class="row g-3 mb-5">
        <div class="col-md-4">
            <div class="lb-voucher-box">
                <div>
                    <div class="fw-bold text-uppercase" style="font-size: 0.9rem; letter-spacing: 0.06em;">GIẢM THÊM 50.000Đ</div>
                    <div class="small opacity-75">Đơn hàng từ 499.000đ</div>
                </div>
                <span class="lb-voucher-code">OWEN50</span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="lb-voucher-box">
                <div>
                    <div class="fw-bold text-uppercase" style="font-size: 0.9rem; letter-spacing: 0.06em;">ƯU ĐÃI VIP 15%</div>
                    <div class="small opacity-75">Đơn hàng từ 999.000đ</div>
                </div>
                <span class="lb-voucher-code">LOCAL15</span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="lb-voucher-box">
                <div>
                    <div class="fw-bold text-uppercase" style="font-size: 0.9rem; letter-spacing: 0.06em;">FREESHIP TOÀN QUỐC</div>
                    <div class="small opacity-75">Tự động áp dụng từ 500K</div>
                </div>
                <span class="lb-voucher-code">FREESHIP</span>
            </div>
        </div>
    </div>

    <!-- 3. THANH FILTER MỨC GIÁ & SẮP XẾP -->
    <div class="lb-sale-toolbar d-flex flex-wrap justify-content-between align-items-center gap-3">
        <!-- Quick Price Filter Pills -->
        <div class="d-flex flex-wrap align-items-center gap-2">
            <span class="fw-bold text-uppercase me-2 small" style="letter-spacing: 0.1em;">MỨC GIÁ:</span>
            <a href="{{ route('collections.sale', ['sort' => request('sort')]) }}"
               class="lb-price-pill {{ !request('price') ? 'active' : '' }}">
                TẤT CẢ ({{ $products->total() }})
            </a>
            <a href="{{ route('collections.sale', ['price' => 'under_300', 'sort' => request('sort')]) }}"
               class="lb-price-pill {{ request('price') == 'under_300' ? 'active' : '' }}">
                &lt; 300K
            </a>
            <a href="{{ route('collections.sale', ['price' => '300_500', 'sort' => request('sort')]) }}"
               class="lb-price-pill {{ request('price') == '300_500' ? 'active' : '' }}">
                300K – 500K
            </a>
            <a href="{{ route('collections.sale', ['price' => '500_1000', 'sort' => request('sort')]) }}"
               class="lb-price-pill {{ request('price') == '500_1000' ? 'active' : '' }}">
                500K – 1 TRIỆU
            </a>
            <a href="{{ route('collections.sale', ['price' => 'over_1000', 'sort' => request('sort')]) }}"
               class="lb-price-pill {{ request('price') == 'over_1000' ? 'active' : '' }}">
                &gt; 1 TRIỆU
            </a>
        </div>

        <!-- Sort Dropdown -->
        <form method="GET" action="{{ route('collections.sale') }}" class="d-flex align-items-center gap-2">
            @if(request('price'))
                <input type="hidden" name="price" value="{{ request('price') }}">
            @endif
            <label class="small fw-bold text-uppercase text-muted text-nowrap mb-0" style="letter-spacing: 0.08em;">SẮP XẾP:</label>
            <select name="sort" class="form-select lb-sort-select" style="width: 210px;" onchange="this.form.submit()">
                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>GIÁ: THẤP ĐẾN CAO</option>
                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>GIÁ: CAO ĐẾN THẤP</option>
                <option value="newest" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>MỚI CẬP NHẬT</option>
            </select>
        </form>
    </div>

    <!-- 4. DANH SÁCH SẢN PHẨM SALE -->
    @if($products->count() > 0)
        <div class="row g-4 mb-5">
            @foreach($products as $product)
                @php
                    $discountPercent = $product->is_featured ? 35 : 25;
                    $originalPrice = round($product->price / (1 - $discountPercent / 100), -3);
                @endphp
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="lb-product-card h-100">
                        <div class="lb-product-thumb">
                            <div class="lb-badge-group">
                                <span class="lb-badge lb-badge-red">SALE -{{ $discountPercent }}%</span>
                                @if($product->is_featured)
                                    <span class="lb-badge lb-badge-dark">LIMITED</span>
                                @endif
                            </div>
                            <a href="{{ route('products.show', $product->id) }}">
                                <img src="{{ Str::startsWith($product->image, ['http://', 'https://']) ? $product->image : asset('storage/' . $product->image) }}"
                                     class="lb-product-img"
                                     alt="{{ $product->name }}"
                                     onerror="this.onerror=null; this.src='https://via.placeholder.com/400x520?text=OWEN+STUDIO';">
                            </a>
                            <a href="{{ route('products.show', $product->id) }}" class="lb-quick-view">
                                SĂN DEAL NGAY &rarr;
                            </a>
                        </div>

                        <div>
                            <div class="lb-product-cat">{{ $product->category->name ?? 'LOCAL BRAND' }}</div>
                            <a href="{{ route('products.show', $product->id) }}" class="lb-product-name" title="{{ $product->name }}">
                                {{ $product->name }}
                            </a>
                            <div class="d-flex align-items-center">
                                <span class="lb-product-price text-danger">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                                <span class="lb-product-price-old">{{ number_format($originalPrice, 0, ',', '.') }}₫</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Phân trang -->
        <div class="d-flex justify-content-center pt-4 border-top">
            {{ $products->links() }}
        </div>
    @else
        <div class="text-center py-5 border bg-light">
            <i class="fas fa-tag fa-3x text-muted mb-3 d-block"></i>
            <h5 class="fw-bold text-uppercase">Không có thiết kế nào trong mức giá này</h5>
            <p class="text-muted">Hãy chọn mức giá khác để xem toàn bộ danh sách ưu đãi Archive Sale.</p>
            <a href="{{ route('collections.sale') }}" class="btn-lb-dark mt-2">
                XEM TẤT CẢ SẢN PHẨM SALE
            </a>
        </div>
    @endif
</div>
@endsection
