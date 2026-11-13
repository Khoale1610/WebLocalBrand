@extends('layouts.app')

@section('title', 'LOOKBOOK & ARCHIVE — Bộ Sưu Tập OWEN STUDIO')

@push('styles')
<style>
    /* Editorial Lookbook Banner - Đồng bộ chiều cao 600px với Trang chủ & Sale */
    .lb-col-hero {
        position: relative;
        height: 600px;
        background-color: #0d0d0d;
        overflow: hidden;
        margin-bottom: 50px;
    }
    .lb-col-hero-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center 30%;
        filter: brightness(0.55) contrast(1.08);
    }
    .lb-col-hero-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(90deg, rgba(0,0,0,0.85) 0%, rgba(0,0,0,0.35) 100%);
        display: flex;
        align-items: center;
    }
    .lb-col-hero-title {
        font-family: 'Oswald', 'Plus Jakarta Sans', sans-serif;
        font-size: clamp(2.6rem, 5vw, 4.4rem);
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        color: #fff;
        line-height: 1.08;
        margin-bottom: 18px;
    }

    /* Editorial Lookbook Row Card */
    .lb-lookbook-item {
        border: 1px solid #e4e4e7;
        background-color: #fff;
        margin-bottom: 48px;
        transition: border-color 0.3s ease;
    }
    .lb-lookbook-item:hover {
        border-color: #111;
    }
    .lb-lookbook-media {
        position: relative;
        height: 100%;
        min-height: 420px;
        overflow: hidden;
        background-color: #111;
    }
    .lb-lookbook-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.65s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .lb-lookbook-item:hover .lb-lookbook-img {
        transform: scale(1.05);
    }
    .lb-lookbook-num {
        font-family: 'Oswald', sans-serif;
        font-size: 3.2rem;
        font-weight: 700;
        line-height: 1;
        color: #e4e4e7;
        letter-spacing: 0.02em;
    }
    .lb-lookbook-title {
        font-family: 'Oswald', 'Plus Jakarta Sans', sans-serif;
        font-size: 2rem;
        font-weight: 700;
        text-transform: uppercase;
        color: #111;
        letter-spacing: 0.02em;
        margin-bottom: 8px;
    }
    .lb-lookbook-sub {
        font-size: 0.85rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.16em;
        color: #d32f2f;
        margin-bottom: 16px;
        display: block;
    }
    .lb-sample-thumb {
        width: 66px;
        height: 84px;
        object-fit: cover;
        border: 1px solid #e4e4e7;
        transition: transform 0.2s ease, border-color 0.2s ease;
    }
    .lb-sample-thumb:hover {
        transform: translateY(-3px);
        border-color: #111;
    }
</style>
@endpush

@section('content')

<!-- 1. HERO EDITORIAL BANNER -->
<div class="lb-col-hero">
    <img src="https://images.unsplash.com/photo-1490481651871-ab68de25d43d?q=80&w=1800&auto=format&fit=crop" alt="OWEN Lookbook Archive" class="lb-col-hero-img">
    <div class="lb-col-hero-overlay">
        <div class="container">
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb lb-breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-white-50 text-decoration-none">HOME</a></li>
                    <li class="breadcrumb-item active text-white" aria-current="page">LOOKBOOK & COLLECTIONS</li>
                </ol>
            </nav>
            <span class="lb-kicker">SEASONAL ARCHIVE // 2026</span>
            <h1 class="lb-col-hero-title">THE CURATED<br>COLLECTIONS</h1>
            <p class="text-white-50 mb-0" style="max-width: 600px; font-size: 1.06rem;">
                Từng bộ sưu tập là một tuyên ngôn độc lập về phom dáng và chất liệu — từ nhịp sống đường phố phóng khoáng đến chuẩn mực tối giản đương đại.
            </p>
        </div>
    </div>
</div>

<div class="container pb-5">
    <!-- 2. DANH SÁCH BỘ SƯU TẬP DẠNG EDITORIAL -->
    <div class="lb-section-header">
        <div>
            <span class="lb-section-subtitle">ALL RELEASES</span>
            <h2 class="lb-section-title">04 BỘ SƯU TẬP CHỦ ĐẠO</h2>
        </div>
        <span class="text-muted fw-semibold small text-uppercase" style="letter-spacing: 0.1em;">SELECT YOUR AESTHETIC</span>
    </div>

    @foreach($collections as $slug => $col)
        <div class="lb-lookbook-item">
            <div class="row g-0 align-items-stretch {{ $loop->even ? 'flex-lg-row-reverse' : '' }}">
                <!-- Ảnh Banner BST -->
                <div class="col-lg-6">
                    <a href="{{ route('collections.show', $col['slug']) }}" class="d-block lb-lookbook-media">
                        <img src="{{ $col['banner_img'] }}" alt="{{ $col['title'] }}" class="lb-lookbook-img">
                        <div class="lb-badge-group">
                            <span class="lb-badge lb-badge-dark">{{ $col['badge'] }}</span>
                            <span class="lb-badge lb-badge-red">{{ $col['products_count'] }} ITEMS</span>
                        </div>
                    </a>
                </div>

                <!-- Nội dung Editorial -->
                <div class="col-lg-6 d-flex flex-column justify-content-between p-4 p-md-5">
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="lb-lookbook-sub mb-0">{{ $col['subtitle'] }}</span>
                            <span class="lb-lookbook-num">0{{ $loop->iteration }}</span>
                        </div>
                        <h3 class="lb-lookbook-title">{{ $col['title'] }}</h3>
                        <p class="text-secondary mb-4" style="font-size: 1.02rem; line-height: 1.7;">
                            {{ $col['description'] }}
                        </p>
                    </div>

                    <div>
                        <!-- Sản phẩm tiêu biểu trong BST -->
                        @if(isset($col['sample_products']) && $col['sample_products']->isNotEmpty())
                            <div class="mb-4 pt-3 border-top">
                                <span class="d-block text-uppercase fw-bold text-muted mb-2" style="font-size: 0.78rem; letter-spacing: 0.14em;">
                                    KEY PIECES TRONG BỘ SƯU TẬP:
                                </span>
                                <div class="d-flex align-items-center gap-3 flex-wrap">
                                    @foreach($col['sample_products'] as $sample)
                                        <a href="{{ route('products.show', $sample->id) }}" title="{{ $sample->name }}">
                                            <img src="{{ Str::startsWith($sample->image, ['http://', 'https://']) ? $sample->image : asset('storage/' . $sample->image) }}"
                                                 alt="{{ $sample->name }}"
                                                 class="lb-sample-thumb"
                                                 onerror="this.onerror=null; this.src='https://via.placeholder.com/100x130?text=OWEN';">
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <a href="{{ route('collections.show', $col['slug']) }}" class="btn-lb-dark">
                            KHÁM PHÁ DROP NÀY ({{ $col['products_count'] }}) <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <!-- 3. SPOTLIGHT: SẢN PHẨM ĐƯỢC SĂN ĐÓN NHẤT -->
    @if(isset($spotlightProducts) && $spotlightProducts->isNotEmpty())
        <div class="pt-4 mt-5">
            <div class="lb-section-header">
                <div>
                    <span class="lb-section-subtitle">CURATED ARCHIVE</span>
                    <h2 class="lb-section-title">THIẾT KẾ Tâm ĐIỂM TRONG TUẦN</h2>
                </div>
                <a href="{{ route('collections.sale') }}" class="lb-view-all text-danger">
                    SĂN PRIVATE SALE UP TO 50% <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>

            <div class="row g-4">
                @foreach($spotlightProducts as $product)
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="lb-product-card h-100">
                            <div class="lb-product-thumb">
                                <div class="lb-badge-group">
                                    @if($product->is_featured)
                                        <span class="lb-badge lb-badge-dark">FEATURED</span>
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
                                    XEM CHI TIẾT &rarr;
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
        </div>
    @endif
</div>
@endsection
