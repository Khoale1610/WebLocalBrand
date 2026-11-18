@extends('layouts.app')

@section('title', 'Sản Phẩm Yêu Thích — OWEN STUDIO')

@section('content')
<div class="container py-4 py-lg-5 account-page">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb mb-0" style="font-size: 13px;">
            <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none text-muted">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('account.index') }}" class="text-decoration-none text-muted">Tài khoản</a></li>
            <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Sản phẩm yêu thích</li>
        </ol>
    </nav>

    <div class="row g-4 align-items-start">
        <!-- Sidebar tài khoản -->
        <div class="col-lg-3 col-md-4">
            <div class="acc-sidebar-box">
                <div class="acc-user-header">
                    <div class="acc-avatar">
                        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                    </div>
                    <div class="overflow-hidden">
                        <div class="fw-semibold text-dark text-truncate" style="font-size: 14px;">{{ auth()->user()->name ?? 'Khách hàng' }}</div>
                        <div class="text-muted text-truncate" style="font-size: 12px;">{{ auth()->user()->email ?? '' }}</div>
                    </div>
                </div>

                <div class="acc-nav-list">
                    <a href="{{ route('account.index') }}" class="acc-nav-link">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="square" stroke-linejoin="miter">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        <span>Tổng quan tài khoản</span>
                    </a>
                    <a href="{{ route('account.orders') }}" class="acc-nav-link">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="square" stroke-linejoin="miter">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                        </svg>
                        <span>Lịch sử đơn hàng</span>
                    </a>
                    <a href="{{ route('account.wishlist.index') }}" class="acc-nav-link active">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="square" stroke-linejoin="miter">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                        </svg>
                        <span>Sản phẩm yêu thích</span>
                    </a>
                    <a href="{{ route('account.addresses') }}" class="acc-nav-link">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="square" stroke-linejoin="miter">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                        <span>Sổ địa chỉ</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Nội dung danh sách yêu thích -->
        <div class="col-lg-9 col-md-8">
            <div class="acc-content-box">
                <div class="acc-section-header">
                    <div>
                        <span class="acc-kicker">SAVED ITEMS</span>
                        <h1 class="acc-title">Sản phẩm yêu thích</h1>
                    </div>
                    <span class="text-muted" style="font-size: 13px;">Đã lưu: <strong class="text-dark">{{ $wishlists->total() }}</strong> sản phẩm</span>
                </div>

                @if(session('success'))
                    <div class="alert alert-dark rounded-0 border-dark mb-4" style="font-size: 13.5px;">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('info'))
                    <div class="alert alert-secondary rounded-0 mb-4" style="font-size: 13.5px;">
                        {{ session('info') }}
                    </div>
                @endif

                <div class="row g-4">
                    @forelse($wishlists as $item)
                        @if($item->product)
                            <div class="col-6 col-md-4">
                                <div class="lb-product-card h-100 d-flex flex-column">
                                    <div class="lb-product-thumb">
                                        <div class="lb-badge-group">
                                            @if($item->product->is_featured)
                                                <span class="lb-badge lb-badge-dark">BEST SELLER</span>
                                            @endif
                                            <span class="lb-badge lb-badge-red" style="background-color: #C83E3B;">-20%</span>
                                        </div>
                                        <a href="{{ route('products.show', $item->product->id) }}">
                                            <img src="{{ $item->product->image_url }}"
                                                 class="lb-product-img"
                                                 alt="{{ $item->product->name }}"
                                                 onerror="this.onerror=null; this.src='https://via.placeholder.com/400x520?text=OWEN+STUDIO';">
                                        </a>
                                        <a href="{{ route('products.show', $item->product->id) }}" class="lb-quick-view">
                                            MUA NGAY
                                        </a>
                                    </div>

                                    <div class="flex-grow-1 d-flex flex-column">
                                        <div class="lb-product-cat">{{ $item->product->category->name ?? 'LOCAL BRAND' }}</div>
                                        <a href="{{ route('products.show', $item->product->id) }}" class="lb-product-name" title="{{ $item->product->name }}">
                                            {{ $item->product->name }}
                                        </a>
                                        <div class="d-flex align-items-baseline mb-3">
                                            <span class="lb-product-price" style="color: #C83E3B;">{{ number_format($item->product->price, 0, ',', '.') }}đ</span>
                                            <span class="lb-product-price-old">{{ number_format($item->product->price * 1.25, 0, ',', '.') }}đ</span>
                                        </div>

                                        <form action="{{ route('account.wishlist.remove', $item->product->id) }}" method="POST" class="mt-auto">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-acc-outline w-100">
                                                Bỏ yêu thích
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @empty
                        <div class="col-12">
                            <div class="text-center py-5 border" style="border-color: #E5E7EB !important;">
                                <h6 class="fw-semibold text-dark mb-2" style="font-size: 16px;">Danh sách yêu thích đang trống</h6>
                                <p class="text-muted mb-4" style="font-size: 13.5px;">Hãy bấm vào biểu tượng trái tim ở trang sản phẩm để lưu lại các thiết kế bạn yêu thích.</p>
                                <a href="{{ url('/') }}" class="btn-acc-primary">
                                    KHÁM PHÁ SẢN PHẨM
                                </a>
                            </div>
                        </div>
                    @endforelse
                </div>

                @if($wishlists->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $wishlists->links('pagination::bootstrap-4') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
