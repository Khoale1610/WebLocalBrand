@extends('layouts.app')

@section('title', 'OWEN STUDIO — Thời Trang Nam Local Brand Việt Nam')

@push('styles')
<style>
    /* Editorial & Streetwear Local Brand Aesthetic */
    .lb-hero-slide {
        position: relative;
        height: 600px;
        background-color: #0d0d0d;
        overflow: hidden;
    }
    .lb-hero-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center 25%;
        filter: brightness(0.68) contrast(1.05);
        transition: transform 6s ease;
    }
    .carousel-item.active .lb-hero-img {
        transform: scale(1.04);
    }
    .lb-hero-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(90deg, rgba(0,0,0,0.82) 0%, rgba(0,0,0,0.35) 55%, rgba(0,0,0,0.15) 100%);
        display: flex;
        align-items: center;
    }
    .lb-kicker {
        display: inline-block;
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.22em;
        color: #fff;
        border-left: 3px solid #d32f2f;
        padding-left: 12px;
        margin-bottom: 18px;
    }
    .lb-hero-title {
        font-family: 'Oswald', 'Plus Jakarta Sans', sans-serif;
        font-size: clamp(2.6rem, 5vw, 4.4rem);
        font-weight: 700;
        text-transform: uppercase;
        line-height: 1.08;
        letter-spacing: 0.02em;
        color: #fff;
        margin-bottom: 20px;
    }
    .lb-hero-desc {
        font-size: 1.1rem;
        color: rgba(255, 255, 255, 0.86);
        max-width: 540px;
        margin-bottom: 32px;
        font-weight: 400;
    }
    .btn-lb-primary {
        background-color: #ffffff;
        color: #111111;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        padding: 15px 34px;
        border-radius: 0;
        border: 2px solid #ffffff;
        font-size: 0.92rem;
        transition: all 0.25s ease;
    }
    .btn-lb-primary:hover {
        background-color: transparent;
        color: #ffffff;
    }
    .btn-lb-outline-light {
        background-color: transparent;
        color: #ffffff;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        padding: 15px 30px;
        border-radius: 0;
        border: 2px solid rgba(255,255,255,0.6);
        font-size: 0.92rem;
        transition: all 0.25s ease;
    }
    .btn-lb-outline-light:hover {
        background-color: #d32f2f;
        border-color: #d32f2f;
        color: #fff;
    }

    /* Marquee Ticker */
    .lb-marquee {
        background-color: #111111;
        color: #ffffff;
        padding: 13px 0;
        overflow: hidden;
        white-space: nowrap;
        border-bottom: 1px solid #222;
    }
    .lb-marquee-track {
        display: inline-block;
        animation: lbMarquee 28s linear infinite;
    }
    .lb-marquee-item {
        display: inline-block;
        font-size: 0.88rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.16em;
        margin: 0 28px;
    }
    .lb-marquee-dot {
        color: #d32f2f;
        margin: 0 8px;
    }
    @keyframes lbMarquee {
        0% { transform: translateX(0); }
        100% { transform: translateX(-50%); }
    }

    /* Section Headings */
    .lb-section-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 36px;
        border-bottom: 2px solid #111;
        padding-bottom: 16px;
    }
    .lb-section-subtitle {
        font-size: 0.82rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.18em;
        color: #d32f2f;
        margin-bottom: 4px;
        display: block;
    }
    .lb-section-title {
        font-family: 'Oswald', 'Plus Jakarta Sans', sans-serif;
        font-size: 2.1rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.02em;
        color: #111;
        margin: 0;
    }
    .lb-view-all {
        font-size: 0.92rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #111;
        text-decoration: none;
        padding-bottom: 2px;
        border-bottom: 2px solid transparent;
        transition: all 0.2s;
    }
    .lb-view-all:hover {
        color: #d32f2f;
        border-bottom-color: #d32f2f;
    }

    /* Editorial Category Bento Grid */
    .lb-bento-card {
        position: relative;
        overflow: hidden;
        background-color: #111;
        display: block;
        height: 440px;
        text-decoration: none;
    }
    .lb-bento-card.lb-bento-sm {
        height: 208px;
    }
    .lb-bento-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: 0.82;
        transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.4s ease;
    }
    .lb-bento-card:hover .lb-bento-img {
        transform: scale(1.07);
        opacity: 0.68;
    }
    .lb-bento-content {
        position: absolute;
        inset: 0;
        padding: 28px;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        background: linear-gradient(to top, rgba(0,0,0,0.82) 0%, rgba(0,0,0,0.1) 65%);
        color: #fff;
    }
    .lb-bento-tag {
        font-size: 0.78rem;
        font-weight: 700;
        letter-spacing: 0.16em;
        text-transform: uppercase;
        color: #f87171;
        margin-bottom: 4px;
    }
    .lb-bento-title {
        font-family: 'Oswald', 'Plus Jakarta Sans', sans-serif;
        font-size: 1.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #fff;
        margin-bottom: 6px;
    }
    .lb-bento-sm .lb-bento-title {
        font-size: 1.35rem;
    }
    .lb-bento-cta {
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.12em;
        color: #fff;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    /* Product Card Local Brand Style */
    .lb-product-card {
        border: none;
        background: transparent;
        transition: all 0.25s ease;
    }
    .lb-product-thumb {
        position: relative;
        overflow: hidden;
        background-color: #f4f4f5;
        aspect-ratio: 3 / 4;
        margin-bottom: 14px;
    }
    .lb-product-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.55s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .lb-product-card:hover .lb-product-img {
        transform: scale(1.06);
    }
    .lb-badge-group {
        position: absolute;
        top: 12px;
        left: 12px;
        display: flex;
        flex-direction: column;
        gap: 6px;
        z-index: 2;
    }
    .lb-badge {
        font-size: 0.72rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.12em;
        padding: 5px 10px;
        display: inline-block;
    }
    .lb-badge-dark {
        background-color: #111;
        color: #fff;
    }
    .lb-badge-red {
        background-color: #d32f2f;
        color: #fff;
    }
    .lb-quick-view {
        position: absolute;
        left: 12px;
        right: 12px;
        bottom: 12px;
        background-color: rgba(17, 17, 17, 0.92);
        color: #fff;
        text-align: center;
        padding: 11px 14px;
        font-size: 0.84rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        text-decoration: none;
        transform: translateY(120%);
        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), background-color 0.2s;
        z-index: 2;
    }
    .lb-product-card:hover .lb-quick-view {
        transform: translateY(0);
    }
    .lb-quick-view:hover {
        background-color: #d32f2f;
        color: #fff;
    }
    .lb-product-cat {
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: #71717a;
        margin-bottom: 4px;
    }
    .lb-product-name {
        font-size: 1.04rem;
        font-weight: 700;
        color: #111;
        text-decoration: none;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
        margin-bottom: 6px;
        transition: color 0.2s;
    }
    .lb-product-name:hover {
        color: #d32f2f;
    }
    .lb-product-price {
        font-size: 1.06rem;
        font-weight: 800;
        color: #111;
    }
    .lb-product-price-old {
        font-size: 0.9rem;
        color: #a1a1aa;
        text-decoration: line-through;
        margin-left: 8px;
        font-weight: 500;
    }

    /* Lookbook Banner */
    .lb-lookbook-banner {
        background-color: #111111;
        color: #ffffff;
        overflow: hidden;
    }
    .lb-service-bar {
        border-top: 1px solid #e4e4e7;
        border-bottom: 1px solid #e4e4e7;
        background-color: #fafafa;
    }
</style>
@endpush

@section('content')

<!-- 1. HERO EDITORIAL CAROUSEL -->
<div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000">
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
    </div>
    <div class="carousel-inner">
        <!-- Slide 1: Streetwear & Essential Drop -->
        <div class="carousel-item active lb-hero-slide">
            <img src="https://images.unsplash.com/photo-1523398002811-999ca8dec234?q=80&w=1600&auto=format&fit=crop" class="lb-hero-img" alt="Local Brand Streetwear Drop">
            <div class="lb-hero-overlay">
                <div class="container">
                    <div class="col-lg-8">
                        <span class="lb-kicker">DROP 01 // AUTUMN WINTER 2026</span>
                        <h1 class="lb-hero-title">URBAN ARCHIVE<br>STREETWEAR COLLECTION</h1>
                        <p class="lb-hero-desc">
                            Định hình cá tính với chất liệu Cotton 2 chiều định lượng 280GSM, form Boxy & Oversize chuẩn mực dành riêng cho giới trẻ Việt.
                        </p>
                        <div class="d-flex flex-wrap gap-3">
                            <a href="{{ route('collections.index') }}" class="btn btn-lb-primary">
                                XEM LOOKBOOK <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                            <a href="#newArrivalsSection" class="btn btn-lb-outline-light">
                                NEW ARRIVALS
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Slide 2: Minimalist Menswear -->
        <div class="carousel-item lb-hero-slide">
            <img src="https://images.unsplash.com/photo-1490481651871-ab68de25d43d?q=80&w=1600&auto=format&fit=crop" class="lb-hero-img" alt="Minimalist Menswear">
            <div class="lb-hero-overlay">
                <div class="container">
                    <div class="col-lg-8">
                        <span class="lb-kicker">LIMITED RELEASE // ESSENTIAL LINE</span>
                        <h2 class="lb-hero-title">MINIMALIST STUDIO<br>& TAILORED DENIM</h2>
                        <p class="lb-hero-desc">
                            Sự giao thoa giữa đường nét tối giản hiện đại và tính ứng dụng cao. Giảm ngay tới 50% cho các thiết kế Archive cuối mùa.
                        </p>
                        <div class="d-flex flex-wrap gap-3">
                            <a href="{{ route('collections.sale') }}" class="btn btn-lb-primary">
                                SĂN SALE UP TO 50% <i class="fas fa-bolt ms-2"></i>
                            </a>
                            <a href="#featuredSection" class="btn btn-lb-outline-light">
                                BEST SELLERS
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 2. BRAND MARQUEE TICKER -->
<div class="lb-marquee">
    <div class="lb-marquee-track">
        <span class="lb-marquee-item">HEAVYWEIGHT COTTON 280GSM <span class="lb-marquee-dot">✦</span></span>
        <span class="lb-marquee-item">DESIGNED PROUDLY IN VIETNAM <span class="lb-marquee-dot">✦</span></span>
        <span class="lb-marquee-item">FREESHIP ĐƠN TỪ 500.000Đ <span class="lb-marquee-dot">✦</span></span>
        <span class="lb-marquee-item">ĐỔI TRẢ MIỄN PHÍ TRONG 30 NGÀY <span class="lb-marquee-dot">✦</span></span>
        <span class="lb-marquee-item">OWEN STUDIO ARCHIVE 2026 <span class="lb-marquee-dot">✦</span></span>
        <span class="lb-marquee-item">HEAVYWEIGHT COTTON 280GSM <span class="lb-marquee-dot">✦</span></span>
        <span class="lb-marquee-item">DESIGNED PROUDLY IN VIETNAM <span class="lb-marquee-dot">✦</span></span>
        <span class="lb-marquee-item">FREESHIP ĐƠN TỪ 500.000Đ <span class="lb-marquee-dot">✦</span></span>
        <span class="lb-marquee-item">ĐỔI TRẢ MIỄN PHÍ TRONG 30 NGÀY <span class="lb-marquee-dot">✦</span></span>
        <span class="lb-marquee-item">OWEN STUDIO ARCHIVE 2026 <span class="lb-marquee-dot">✦</span></span>
    </div>
</div>

<!-- 3. SHOP BY CATEGORY (BENTO LOOKBOOK GRID) -->
<section class="container my-5 pt-3">
    <div class="lb-section-header">
        <div>
            <span class="lb-section-subtitle">CURATED CATEGORIES</span>
            <h2 class="lb-section-title">DANH MỤC CHỦ ĐẠO</h2>
        </div>
        <a href="{{ route('collections.index') }}" class="lb-view-all">
            TẤT CẢ BỘ SƯU TẬP <i class="fas fa-arrow-right ms-1"></i>
        </a>
    </div>

    <div class="row g-4">
        <!-- Cột lớn 1: Áo Khoác & Outerwear -->
        <div class="col-lg-5">
            <a href="{{ route('category.show', 4) }}" class="lb-bento-card">
                <img src="https://images.unsplash.com/photo-1551028719-00167b16eac5?q=80&w=900&auto=format&fit=crop" class="lb-bento-img" alt="Outerwear & Jackets">
                <div class="lb-bento-content">
                    <span class="lb-bento-tag">SIGNATURE OUTERWEAR</span>
                    <h3 class="lb-bento-title">JACKETS & HOODIES</h3>
                    <span class="lb-bento-cta">KHÁM PHÁ NGAY <i class="fas fa-arrow-right"></i></span>
                </div>
            </a>
        </div>

        <!-- Cột giữa: Áo Thun / Polo & Sơ Mi -->
        <div class="col-lg-4 d-flex flex-column gap-4">
            <a href="{{ route('category.show', 3) }}" class="lb-bento-card lb-bento-sm">
                <img src="https://images.unsplash.com/photo-1521572267360-ee0c2909d518?q=80&w=800&auto=format&fit=crop" class="lb-bento-img" alt="Graphic Tees & Polo">
                <div class="lb-bento-content">
                    <span class="lb-bento-tag">BOXY FIT 280GSM</span>
                    <h3 class="lb-bento-title">T-SHIRTS & POLO</h3>
                    <span class="lb-bento-cta">XEM DANH MỤC <i class="fas fa-arrow-right"></i></span>
                </div>
            </a>
            <a href="{{ route('category.show', 1) }}" class="lb-bento-card lb-bento-sm">
                <img src="https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?q=80&w=800&auto=format&fit=crop" class="lb-bento-img" alt="Minimalist Shirts">
                <div class="lb-bento-content">
                    <span class="lb-bento-tag">SMART CASUAL</span>
                    <h3 class="lb-bento-title">SƠ MI & BLAZER</h3>
                    <span class="lb-bento-cta">XEM DANH MỤC <i class="fas fa-arrow-right"></i></span>
                </div>
            </a>
        </div>

        <!-- Cột phải: Quần Jeans / Cargo & Phụ kiện -->
        <div class="col-lg-3 d-flex flex-column gap-4">
            <a href="{{ route('category.show', 8) }}" class="lb-bento-card lb-bento-sm">
                <img src="https://images.unsplash.com/photo-1542272604-787c3835535d?q=80&w=800&auto=format&fit=crop" class="lb-bento-img" alt="Denim & Trousers">
                <div class="lb-bento-content">
                    <span class="lb-bento-tag">WIDE LEG & SLIM</span>
                    <h3 class="lb-bento-title">DENIM & PANTS</h3>
                    <span class="lb-bento-cta">MUA NGAY <i class="fas fa-arrow-right"></i></span>
                </div>
            </a>
            <a href="{{ route('category.show', 10) }}" class="lb-bento-card lb-bento-sm">
                <img src="https://images.unsplash.com/photo-1553062407-98eeb64c6a62?q=80&w=800&auto=format&fit=crop" class="lb-bento-img" alt="Leather & Accessories">
                <div class="lb-bento-content">
                    <span class="lb-bento-tag">EVERYDAY CARRY</span>
                    <h3 class="lb-bento-title">PHỤ KIỆN & VÍ DA</h3>
                    <span class="lb-bento-cta">MUA NGAY <i class="fas fa-arrow-right"></i></span>
                </div>
            </a>
        </div>
    </div>
</section>

<!-- 4. BEST SELLERS / FEATURED DROP -->
<section class="container my-5 pt-4" id="featuredSection">
    <div class="lb-section-header">
        <div>
            <span class="lb-section-subtitle">MOST WANTED ITEMS</span>
            <h2 class="lb-section-title">BEST SELLERS IN WEEK</h2>
        </div>
        <a href="{{ route('products.search', ['sort' => 'newest']) }}" class="lb-view-all">
            XEM TẤT CẢ SẢN PHẨM <i class="fas fa-arrow-right ms-1"></i>
        </a>
    </div>

    <div class="row g-4">
        @forelse($featuredProducts as $prod)
            <div class="col-6 col-md-4 col-lg-3">
                <div class="lb-product-card h-100">
                    <div class="lb-product-thumb">
                        <div class="lb-badge-group">
                            <span class="lb-badge lb-badge-dark">BEST SELLER</span>
                            <span class="lb-badge lb-badge-red">-20%</span>
                        </div>
                        <a href="{{ route('products.show', $prod->id) }}">
                            <img src="{{ Str::startsWith($prod->image, ['http://', 'https://']) ? $prod->image : asset('storage/' . $prod->image) }}"
                                 class="lb-product-img"
                                 alt="{{ $prod->name }}"
                                 onerror="this.onerror=null; this.src='https://via.placeholder.com/400x520?text=OWEN+STUDIO';">
                        </a>
                        <a href="{{ route('products.show', $prod->id) }}" class="lb-quick-view">
                            TÙY CHỌN BIẾN THỂ &rarr;
                        </a>
                    </div>

                    <div>
                        <div class="lb-product-cat">{{ $prod->category->name ?? 'LOCAL BRAND' }}</div>
                        <a href="{{ route('products.show', $prod->id) }}" class="lb-product-name" title="{{ $prod->name }}">
                            {{ $prod->name }}
                        </a>
                        <div class="d-flex align-items-center">
                            <span class="lb-product-price">{{ number_format($prod->price, 0, ',', '.') }}₫</span>
                            <span class="lb-product-price-old">{{ number_format($prod->price * 1.25, 0, ',', '.') }}₫</span>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <p class="text-muted">Chưa có sản phẩm nổi bật nào.</p>
            </div>
        @endforelse
    </div>
</section>

<!-- 5. EDITORIAL CAMPAIGN BANNER -->
<section class="container my-5 py-2">
    <div class="lb-lookbook-banner">
        <div class="row g-0 align-items-center">
            <div class="col-lg-6 p-4 p-md-5">
                <span class="lb-kicker">CAMPAIGN STORY // 2026</span>
                <h2 class="lb-hero-title" style="font-size: 2.5rem;">THE CRAFTSMANSHIP OF MODERN LOCAL BRAND</h2>
                <p class="text-white-50 mb-4" style="font-size: 1.05rem; line-height: 1.7;">
                    Mỗi sản phẩm của OWEN STUDIO đều trải qua quy trình xử lý co rút vải và kiểm định đường kim mũi chỉ nghiêm ngặt trước khi lên kệ. Không chạy theo số lượng, chúng tôi tập trung vào độ bền và phom dáng chuẩn xác nhất cho người mặc.
                </p>
                <div class="row g-3 mb-4 pt-2 border-top border-secondary">
                    <div class="col-4">
                        <div class="fw-bold fs-3 text-white">280+</div>
                        <div class="text-white-50 small">Biến thể Size & Màu</div>
                    </div>
                    <div class="col-4">
                        <div class="fw-bold fs-3 text-white">100%</div>
                        <div class="text-white-50 small">Cotton & Denim Tuyển Chọn</div>
                    </div>
                    <div class="col-4">
                        <div class="fw-bold fs-3 text-white">30 Ngày</div>
                        <div class="text-white-50 small">Đổi Size Tận Nhà</div>
                    </div>
                </div>
                <a href="{{ route('collections.index') }}" class="btn btn-lb-primary">
                    KHÁM PHÁ CÁC BỘ SƯU TẬP
                </a>
            </div>
            <div class="col-lg-6">
                <img src="https://images.unsplash.com/photo-1516826957135-700dedea698c?q=80&w=1000&auto=format&fit=crop"
                     alt="Local Brand Lookbook"
                     class="w-100"
                     style="height: 480px; object-fit: cover;">
            </div>
        </div>
    </div>
</section>

<!-- 6. NEW ARRIVALS (SẢN PHẨM MỚI LÊN KỆ) -->
@if(isset($newArrivals) && $newArrivals->isNotEmpty())
<section class="container my-5 pt-3" id="newArrivalsSection">
    <div class="lb-section-header">
        <div>
            <span class="lb-section-subtitle">JUST DROPPED</span>
            <h2 class="lb-section-title">NEW ARRIVALS — HÀNG MỚI VỀ</h2>
        </div>
        <a href="{{ route('collections.sale') }}" class="lb-view-all text-danger">
            ƯU ĐÃI FLASH SALE <i class="fas fa-fire ms-1"></i>
        </a>
    </div>

    <div class="row g-4">
        @foreach($newArrivals as $prod)
            <div class="col-6 col-md-4 col-lg-3">
                <div class="lb-product-card h-100">
                    <div class="lb-product-thumb">
                        <div class="lb-badge-group">
                            <span class="lb-badge lb-badge-dark">NEW DROP</span>
                        </div>
                        <a href="{{ route('products.show', $prod->id) }}">
                            <img src="{{ Str::startsWith($prod->image, ['http://', 'https://']) ? $prod->image : asset('storage/' . $prod->image) }}"
                                 class="lb-product-img"
                                 alt="{{ $prod->name }}"
                                 onerror="this.onerror=null; this.src='https://via.placeholder.com/400x520?text=OWEN+STUDIO';">
                        </a>
                        <a href="{{ route('products.show', $prod->id) }}" class="lb-quick-view">
                            XEM CHI TIẾT &rarr;
                        </a>
                    </div>

                    <div>
                        <div class="lb-product-cat">{{ $prod->category->name ?? 'LOCAL BRAND' }}</div>
                        <a href="{{ route('products.show', $prod->id) }}" class="lb-product-name" title="{{ $prod->name }}">
                            {{ $prod->name }}
                        </a>
                        <div class="d-flex align-items-center">
                            <span class="lb-product-price">{{ number_format($prod->price, 0, ',', '.') }}₫</span>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</section>
@endif

<!-- 7. CHÍNH SÁCH & ĐẶC QUYỀN THÀNH VIÊN -->
<section class="lb-service-bar py-5 mt-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-3 col-6">
                <div class="d-flex align-items-start gap-3">
                    <div class="bg-dark text-white d-flex align-items-center justify-content-center flex-shrink-0" style="width: 46px; height: 46px;">
                        <i class="fas fa-shipping-fast"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-uppercase mb-1" style="font-size: 0.95rem;">Giao Hàng Toàn Quốc</h6>
                        <p class="text-muted mb-0 small">Miễn phí ship cho mọi đơn hàng từ 500.000đ</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="d-flex align-items-start gap-3">
                    <div class="bg-dark text-white d-flex align-items-center justify-content-center flex-shrink-0" style="width: 46px; height: 46px;">
                        <i class="fas fa-exchange-alt"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-uppercase mb-1" style="font-size: 0.95rem;">Đổi Size Trong 30 Ngày</h6>
                        <p class="text-muted mb-0 small">Hỗ trợ đổi size/màu tận nơi nhanh chóng</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="d-flex align-items-start gap-3">
                    <div class="bg-dark text-white d-flex align-items-center justify-content-center flex-shrink-0" style="width: 46px; height: 46px;">
                        <i class="fas fa-check-double"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-uppercase mb-1" style="font-size: 0.95rem;">Cam Kết Chất Lượng</h6>
                        <p class="text-muted mb-0 small">Hình ảnh & chất vải đúng mô tả 100%</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="d-flex align-items-start gap-3">
                    <div class="bg-dark text-white d-flex align-items-center justify-content-center flex-shrink-0" style="width: 46px; height: 46px;">
                        <i class="fas fa-headset"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-uppercase mb-1" style="font-size: 0.95rem;">Support 08:30 - 22:00</h6>
                        <p class="text-muted mb-0 small">Hotline CSKH: 1900 8079</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
