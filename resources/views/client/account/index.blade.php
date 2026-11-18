@extends('layouts.app')

@section('title', 'Tài Khoản Của Tôi — OWEN STUDIO')

@section('content')
<div class="container py-4 py-lg-5 account-page">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb mb-0" style="font-size: 13px;">
            <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none text-muted">Trang chủ</a></li>
            <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">Tài khoản của tôi</li>
        </ol>
    </nav>

    <div class="row g-4 align-items-start">
        <!-- Sidebar tài khoản (Vuông góc 0px, không dùng màu xanh dương Bootstrap) -->
        <div class="col-lg-3 col-md-4">
            <div class="acc-sidebar-box">
                <div class="acc-user-header">
                    <div class="acc-avatar">
                        {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                    </div>
                    <div class="overflow-hidden">
                        <div class="fw-semibold text-dark text-truncate" style="font-size: 14px;">{{ $user->name }}</div>
                        <div class="text-muted text-truncate" style="font-size: 12px;">{{ $user->email }}</div>
                    </div>
                </div>

                <div class="acc-nav-list">
                    <a href="{{ route('account.index') }}" class="acc-nav-link active">
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
                    <a href="{{ route('account.wishlist.index') }}" class="acc-nav-link">
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

        <!-- Nội dung chính -->
        <div class="col-lg-9 col-md-8">
            <div class="acc-content-box">
                <div class="acc-section-header">
                    <div>
                        <span class="acc-kicker">MEMBER PROFILE</span>
                        <h1 class="acc-title">Thông tin tài khoản</h1>
                    </div>
                    @if($user->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="btn-acc-outline" style="border-color: #111111;">
                            Vào trang Quản trị &rarr;
                        </a>
                    @endif
                </div>

                @if(session('success'))
                    <div class="alert alert-dark rounded-0 border-dark mb-4" style="font-size: 13.5px;">
                        {{ session('success') }}
                    </div>
                @endif

                <div>
                    <div class="acc-info-row">
                        <div class="acc-info-label">Họ và tên</div>
                        <div class="acc-info-value">{{ $user->name }}</div>
                    </div>
                    <div class="acc-info-row">
                        <div class="acc-info-label">Địa chỉ Email</div>
                        <div class="acc-info-value">{{ $user->email }}</div>
                    </div>
                    <div class="acc-info-row">
                        <div class="acc-info-label">Số điện thoại</div>
                        <div class="acc-info-value">{{ $user->phone ?? 'Chưa cập nhật' }}</div>
                    </div>
                    <div class="acc-info-row">
                        <div class="acc-info-label">Địa chỉ mặc định</div>
                        <div class="acc-info-value">{{ $user->address ?? 'Chưa cập nhật' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
