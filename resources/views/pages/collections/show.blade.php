@extends('layouts.app')

@section('title', $collection['title'] . ' — Bộ Sưu Tập OWEN STUDIO')

@push('styles')
<style>
    /* Collection Show Hero - Đồng bộ chiều cao 600px */
    .lb-show-hero {
        position: relative;
        height: 600px;
        background-color: #111;
        overflow: hidden;
        margin-bottom: 50px;
    }
    .lb-show-hero-bg {
        width: 100%;
        height: 100%;
        object-fit: cover;
        filter: brightness(0.55) contrast(1.06);
    }
    .lb-show-hero-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(90deg, rgba(0,0,0,0.85) 0%, rgba(0,0,0,0.35) 100%);
        display: flex;
        align-items: center;
    }
    .lb-show-title {
        font-family: 'Oswald', 'Plus Jakarta Sans', sans-serif;
        font-size: clamp(2.6rem, 5vw, 4.4rem);
        font-weight: 700;
        text-transform: uppercase;
        color: #fff;
        letter-spacing: 0.03em;
        line-height: 1.08;
        margin-bottom: 18px;
    }

    /* Studio Filter Sidebar */
    .lb-filter-panel {
        border: 1px solid #e4e4e7;
        background-color: #fafafa;
        padding: 26px;
    }
    .lb-filter-heading {
        font-family: 'Oswald', 'Plus Jakarta Sans', sans-serif;
        font-size: 1.15rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        border-bottom: 2px solid #111;
        padding-bottom: 12px;
        margin-bottom: 22px;
        color: #111;
    }
    .lb-filter-group-title {
        font-size: 0.82rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.14em;
        color: #111;
        margin-bottom: 12px;
        display: block;
    }
    .lb-filter-panel .form-check-label {
        font-size: 0.96rem;
        font-weight: 500;
        color: #27272a;
        cursor: pointer;
    }
    .lb-filter-panel .form-check-input:checked {
        background-color: #111;
        border-color: #111;
    }
    .lb-toolbar {
        border-bottom: 2px solid #111;
        padding-bottom: 14px;
        margin-bottom: 30px;
    }
    .lb-sort-select {
        border: 1px solid #111;
        border-radius: 0;
        font-size: 0.9rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 8px 34px 8px 14px;
        background-color: #fff;
        color: #111;
    }
    .lb-other-col-link {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #e4e4e7;
        color: #111;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.94rem;
        transition: color 0.2s, padding-left 0.2s;
    }
    .lb-other-col-link:hover {
        color: #d32f2f;
        padding-left: 6px;
    }
</style>
@endpush

@section('content')

<!-- 1. COLLECTION HERO BANNER -->
<div class="lb-show-hero">
    <img src="{{ $collection['banner_img'] }}" alt="{{ $collection['title'] }}" class="lb-show-hero-bg">
    <div class="lb-show-hero-overlay">
        <div class="container">
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb lb-breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-white-50 text-decoration-none">HOME</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('collections.index') }}" class="text-white-50 text-decoration-none">COLLECTIONS</a></li>
                    <li class="breadcrumb-item active text-white" aria-current="page">{{ $collection['title'] }}</li>
                </ol>
            </nav>
            <span class="lb-kicker">{{ $collection['badge'] }} // DROP 2026</span>
            <h1 class="lb-show-title">{{ $collection['title'] }}</h1>
            <p class="mb-0 text-white-50" style="max-width: 720px; font-size: 1.04rem;">
                {{ $collection['description'] }}
            </p>
        </div>
    </div>
</div>

<!-- 2. MAIN CONTENT: FILTER + PRODUCTS GRID -->
<div class="container pb-5">
    <div class="row g-4">
        <!-- Sidebar Filter (col-lg-3) -->
        <div class="col-lg-3 col-md-4">
            <div class="lb-filter-panel sticky-top" style="top: 95px;">
                <div class="lb-filter-heading">
                    <i class="fas fa-sliders-h me-2"></i>BỘ LỌC STUDIO
                </div>

                <form action="{{ route('collections.show', $collection['slug']) }}" method="GET" id="collectionFilterForm">
                    <!-- Sắp xếp ẩn -->
                    <input type="hidden" name="sort" value="{{ request('sort', 'newest') }}" id="filterSortInput">

                    <!-- Lọc theo danh mục trong BST -->
                    @if(isset($categories) && $categories->isNotEmpty())
                        <div class="mb-4 pb-3 border-bottom">
                            <span class="lb-filter-group-title">DANH MỤC TRONG DROP</span>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="category_id" value="" id="catAll" {{ !request('category_id') ? 'checked' : '' }} onchange="this.form.submit()">
                                <label class="form-check-label" for="catAll">Tất cả thiết kế</label>
                            </div>
                            @foreach($categories as $cat)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="category_id" value="{{ $cat->id }}" id="cat_{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'checked' : '' }} onchange="this.form.submit()">
                                    <label class="form-check-label" for="cat_{{ $cat->id }}">{{ $cat->name }}</label>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Lọc theo khoảng giá -->
                    <div class="mb-4">
                        <span class="lb-filter-group-title">KHOẢNG GIÁ</span>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="price" value="" id="priceAll" {{ !request('price') ? 'checked' : '' }} onchange="this.form.submit()">
                            <label class="form-check-label" for="priceAll">Mọi mức giá</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="price" value="under_300" id="price1" {{ request('price') == 'under_300' ? 'checked' : '' }} onchange="this.form.submit()">
                            <label class="form-check-label" for="price1">Dưới 300.000₫</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="price" value="300_500" id="price2" {{ request('price') == '300_500' ? 'checked' : '' }} onchange="this.form.submit()">
                            <label class="form-check-label" for="price2">300.000₫ – 500.000₫</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="price" value="500_1000" id="price3" {{ request('price') == '500_1000' ? 'checked' : '' }} onchange="this.form.submit()">
                            <label class="form-check-label" for="price3">500.000₫ – 1.000.000₫</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="price" value="over_1000" id="price4" {{ request('price') == 'over_1000' ? 'checked' : '' }} onchange="this.form.submit()">
                            <label class="form-check-label" for="price4">Trên 1.000.000₫</label>
                        </div>
                    </div>

                    @if(request()->hasAny(['category_id', 'price']))
                        <a href="{{ route('collections.show', $collection['slug']) }}" class="btn-lb-outline w-100 text-center">
                            <i class="fas fa-undo me-1"></i>ĐẶT LẠI BỘ LỌC
                        </a>
                    @endif
                </form>

                <!-- BST Khác -->
                <div class="mt-4 pt-3 border-top">
                    <span class="lb-filter-group-title">CÁC BỘ SƯU TẬP KHÁC</span>
                    <div>
                        @foreach($allCollections as $otherSlug => $otherCol)
                            @if($otherSlug !== $collection['slug'])
                                <a href="{{ route('collections.show', $otherSlug) }}" class="lb-other-col-link">
                                    <span>{{ $otherCol['title'] }}</span>
                                    <i class="fas fa-arrow-right small"></i>
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Products List (col-lg-9) -->
        <div class="col-lg-9 col-md-8">
            <!-- Thanh Top Bar: Đếm & Sắp xếp -->
            <div class="lb-toolbar d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div class="fw-bold text-uppercase" style="letter-spacing: 0.08em; font-size: 0.92rem;">
                    HIỂN THỊ <span class="text-danger">{{ $products->total() }}</span> THIẾT KẾ TRONG BỘ SƯU TẬP
                </div>
                <div class="d-flex align-items-center gap-2">
                    <label class="small fw-bold text-uppercase text-muted text-nowrap mb-0" style="letter-spacing: 0.08em;">SẮP XẾP:</label>
                    <select class="form-select lb-sort-select" style="width: 210px;" onchange="applySort(this.value)">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>MỚI NHẤT</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>GIÁ: THẤP ĐẾN CAO</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>GIÁ: CAO ĐẾN THẤP</option>
                    </select>
                </div>
            </div>

            <!-- Grid Sản phẩm -->
            @if($products->count() > 0)
                <div class="row g-4 mb-5">
                    @foreach($products as $product)
                        <div class="col-6 col-lg-4">
                            <div class="lb-product-card h-100">
                                <div class="lb-product-thumb">
                                    <div class="lb-badge-group">
                                        @if($product->is_featured)
                                            <span class="lb-badge lb-badge-dark">HOT DROP</span>
                                        @endif
                                        <span class="lb-badge lb-badge-red">-20%</span>
                                    </div>
                                    <a href="{{ route('products.show', $product->id) }}">
                                        <img src="{{ Str::startsWith($product->image, ['http://', 'https://']) ? $product->image : asset('storage/' . $product->image) }}"
                                             class="lb-product-img"
                                             alt="{{ $product->name }}"
                                             onerror="this.onerror=null; this.src='https://via.placeholder.com/400x520?text=OWEN+STUDIO';">
                                    </a>
                                    <a href="{{ route('products.show', $product->id) }}" class="lb-quick-view">
                                        TÙY CHỌN BIẾN THỂ &rarr;
                                    </a>
                                </div>

                                <div>
                                    <div class="lb-product-cat">{{ $product->category->name ?? 'LOCAL BRAND' }}</div>
                                    <a href="{{ route('products.show', $product->id) }}" class="lb-product-name" title="{{ $product->name }}">
                                        {{ $product->name }}
                                    </a>
                                    <div class="d-flex align-items-center">
                                        <span class="lb-product-price">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                                        <span class="lb-product-price-old">{{ number_format($product->price * 1.25, 0, ',', '.') }}₫</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Phân trang -->
                <div class="d-flex justify-content-center pt-3 border-top">
                    {{ $products->links() }}
                </div>
            @else
                <div class="text-center py-5 border bg-light">
                    <i class="fas fa-box-open fa-3x text-muted mb-3 d-block"></i>
                    <h5 class="fw-bold text-uppercase">Chưa có thiết kế phù hợp với bộ lọc</h5>
                    <p class="text-muted">Vui lòng chọn khoảng giá hoặc danh mục khác để tiếp tục khám phá.</p>
                    <a href="{{ route('collections.show', $collection['slug']) }}" class="btn-lb-dark mt-2">
                        XÓA BỘ LỌC
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    function applySort(sortVal) {
        const sortInput = document.getElementById('filterSortInput');
        const form = document.getElementById('collectionFilterForm');
        if (sortInput && form) {
            sortInput.value = sortVal;
            form.submit();
        }
    }
</script>
@endpush
@endsection
