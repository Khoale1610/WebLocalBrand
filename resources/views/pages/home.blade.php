@extends('layouts.app')

@section('title', 'Trang Chủ - Thời Trang Nam Local Brand')

@section('content')
<!-- Banner Slider Section -->
<div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <div class="bg-dark text-white text-center py-5" style="min-height: 420px; background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1490481651871-ab68de25d43d?q=80&w=1200') center/cover;">
                <div class="container d-flex h-100 align-items-center justify-content-center flex-column py-5">
                    <h1 class="display-4 fw-bold">LOCAL BRAND FASHION</h1>
                    <p class="lead" style="max-width: 600px;">Khám phá bộ sưu tập áo sơ mi kháng khuẩn, polo thể thao và quần âu cao cấp định hình phong cách hiện đại.</p>
                    <a href="#featuredSection" class="btn btn-danger btn-lg px-4 mt-2 fw-bold shadow">
                        KHÁM PHÁ NGAY <i class="fas fa-arrow-down ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Lợi ích mua sắm (USPs) -->
<div class="bg-light py-4 border-bottom">
    <div class="container">
        <div class="row g-3 text-center">
            <div class="col-md-3 col-6">
                <div class="d-flex align-items-center justify-content-center gap-3">
                    <i class="fas fa-truck text-danger fs-3"></i>
                    <div class="text-start">
                        <h6 class="mb-0 fw-bold">Miễn Phí Vận Chuyển</h6>
                        <small class="text-muted">Cho đơn hàng từ 500k</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="d-flex align-items-center justify-content-center gap-3">
                    <i class="fas fa-sync-alt text-danger fs-3"></i>
                    <div class="text-start">
                        <h6 class="mb-0 fw-bold">30 Ngày Đổi Trả</h6>
                        <small class="text-muted">Đổi trả tận nơi tiện lợi</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="d-flex align-items-center justify-content-center gap-3">
                    <i class="fas fa-shield-alt text-danger fs-3"></i>
                    <div class="text-start">
                        <h6 class="mb-0 fw-bold">100% Chính Hãng</h6>
                        <small class="text-muted">Sản phẩm chất lượng cao</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="d-flex align-items-center justify-content-center gap-3">
                    <i class="fas fa-headset text-danger fs-3"></i>
                    <div class="text-start">
                        <h6 class="mb-0 fw-bold">Hỗ Trợ 24/7</h6>
                        <small class="text-muted">Hotline 1900 8079</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Featured Products Section -->
<div class="container my-5" id="featuredSection">
    <div class="text-center mb-5">
        <h3 class="text-uppercase fw-bold mb-2">Sản Phẩm Nổi Bật</h3>
        <p class="text-muted">Những thiết kế được yêu thích nhất trong tuần</p>
        <div class="bg-danger mx-auto" style="height: 3px; width: 60px;"></div>
    </div>
    
    <div class="row g-4">
        @forelse($featuredProducts as $prod)
            <div class="col-6 col-md-3">
                <div class="card h-100 border-0 shadow-sm product-card">
                    <div class="position-relative overflow-hidden">
                        <a href="{{ route('products.show', $prod->id) }}">
                            <img src="{{ $prod->image ?? 'https://via.placeholder.com/300x400' }}" 
                                 class="card-img-top" 
                                 alt="{{ $prod->name }}" 
                                 style="height: 280px; object-fit: cover;">
                        </a>
                        <span class="position-absolute top-0 start-0 m-2 badge bg-danger">HOT</span>
                    </div>

                    <div class="card-body text-center d-flex flex-column p-3">
                        @if($prod->category)
                            <span class="badge bg-light text-dark border mb-2 align-self-center">{{ $prod->category->name }}</span>
                        @endif
                        <h6 class="card-title mb-2">
                            <a href="{{ route('products.show', $prod->id) }}" class="text-dark text-decoration-none fw-semibold">
                                {{ $prod->name }}
                            </a>
                        </h6>
                        <p class="text-danger fw-bold fs-5 mb-3">{{ number_format($prod->price, 0, ',', '.') }} ₫</p>
                        
                        <div class="mt-auto d-grid gap-2">
                            <a href="{{ route('products.show', $prod->id) }}" class="btn btn-outline-dark btn-sm">
                                <i class="fas fa-eye me-1"></i>Xem Chi Tiết
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-4">
                <p class="text-muted">Chưa có sản phẩm nổi bật nào.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection