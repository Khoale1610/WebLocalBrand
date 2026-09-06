<!-- Top Bar -->
<div class="top-bar py-2 text-center">
    <span>Miễn phí vận chuyển cho đơn hàng từ 500.000đ | Hotline: 1900 8079</span>
</div>

<!-- Header Main -->
<header class="border-bottom bg-white sticky-top">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light">
            <!-- Logo -->
            <a class="navbar-brand fw-bold fs-3 text-uppercase tracking-wider" href="{{ url('/') }}">
                OWEN<span class="text-danger">.</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#main_nav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Menu Điều Hướng Chính -->
            <div class="collapse navbar-collapse" id="main_nav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active fw-semibold" href="{{ url('/') }}">Trang chủ</a>
                    </li>

                    <!-- Mega Menu Danh Mục -->
                    <li class="nav-item dropdown has-megamenu">
                        <a class="nav-link dropdown-toggle fw-semibold" href="#" data-bs-toggle="dropdown">Sản Phẩm</a>
                        <div class="dropdown-menu megamenu shadow-lg">
                            <div class="container">
                                <div class="row g-3">
                                    <!-- Cột 1: Áo Nam -->
                                    <div class="col-md-3">
                                        <h6 class="text-danger">Áo Nam</h6>
                                        <ul>
                                            <!-- Ghi chú: Đã thay dấu # bằng route gọi đến category ID -->
                                            <li><a href="{{ route('category.show', 1) }}">Áo Sơ Mi</a></li>
                                            <li><a href="{{ route('category.show', 2) }}">Áo Polo</a></li>
                                            <li><a href="{{ route('category.show', 3) }}">Áo T-Shirt (Thun)</a></li>
                                            <li><a href="{{ route('category.show', 4) }}">Áo Khoác</a></li>
                                            <li><a href="{{ route('category.show', 5) }}">Áo Vest / Blazer</a></li>
                                        </ul>
                                    </div>
                                    <!-- Cột 2: Quần Nam -->
                                    <div class="col-md-3">
                                        <h6 class="text-danger">Quần Nam</h6>
                                        <ul>
                                            <li><a href="{{ route('category.show', 6) }}">Quần Tây / Âu</a></li>
                                            <li><a href="{{ route('category.show', 7) }}">Quần Khaki</a></li>
                                            <li><a href="{{ route('category.show', 8) }}">Quần Jeans</a></li>
                                            <li><a href="{{ route('category.show', 9) }}">Quần Short</a></li>
                                        </ul>
                                    </div>
                                    <!-- Cột 3: Phụ Kiện -->
                                    <div class="col-md-3">
                                        <h6 class="text-danger">Phụ Kiện</h6>
                                        <ul>
                                            <li><a href="{{ route('category.show', 10) }}">Thắt Lưng</a></li>
                                            <li><a href="{{ route('category.show', 11) }}">Cà Vạt / Ví Da</a></li>
                                            <li><a href="{{ route('category.show', 12) }}">Tất / Sịp Nam</a></li>
                                        </ul>
                                    </div>
                                    <!-- Cột 4: Banner Khuyến Mãi -->
                                    <div class="col-md-3">
                                        <div class="bg-light p-3 text-center border">
                                            <h6 class="text-dark">Bộ Sưu Tập Mới</h6>
                                            <p class="small text-muted">Khám phá phong cách công sở hiện đại</p>
                                            <a href="#" class="btn btn-sm btn-dark">Xem Ngay</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link fw-semibold" href="#">Bộ Sưu Tập</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-semibold text-danger" href="#">Sale Up To 50%</a>
                    </li>
                </ul>

                <!-- Thanh Tìm Kiếm & Chức Năng Người Dùng -->
                <div class="d-flex align-items-center gap-3">
                    <form class="d-flex" action="{{ route('products.search') }}" method="GET">
                        <div class="input-group">
                            <input class="form-control form-control-sm" type="search" placeholder="Tìm sản phẩm..." name="query" value="{{ request('query') }}">
                            <button class="btn btn-outline-secondary btn-sm" type="submit"><i class="fas fa-search"></i></button>
                        </div>
                    </form>

                    <!-- Icon Giỏ Hàng -->
                    <a href="{{ route('cart.index') }}" class="text-dark position-relative fs-5 ms-2" title="Giỏ hàng">
                        <i class="fas fa-shopping-bag"></i>
                        <span id="cartCountBadge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger fs-6" style="font-size: 0.65rem !important;">
                            {{ array_sum(array_column(session('cart', []), 'quantity')) }}
                        </span>
                    </a>

                    <!-- Icon Tài Khoản (Dropdown) -->
                    <div class="dropdown ms-2">
                        <a href="#" class="text-dark fs-5" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false" title="Tài khoản">
                            <i class="far fa-user"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="userDropdown">
                            @guest
                                <li><a class="dropdown-item fw-bold text-dark" href="{{ route('login') }}">Đăng Nhập</a></li>
                                <li><a class="dropdown-item" href="{{ route('register') }}">Đăng Ký</a></li>
                            @else
                                <li><span class="dropdown-item-text text-muted small">Xin chào, {{ Auth::user()->name }}</span></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('account.index') }}"><i class="fas fa-user-circle me-2"></i> Quản lý tài khoản</a></li>
                                <li><a class="dropdown-item" href="{{ route('account.orders') }}"><i class="fas fa-box me-2"></i> Lịch sử đơn hàng</a></li>
                                <li><a class="dropdown-item" href="{{ route('account.wishlist.index') }}"><i class="fas fa-heart me-2 text-danger"></i> Sản phẩm yêu thích</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger"><i class="fas fa-sign-out-alt me-2"></i> Đăng xuất</button>
                                    </form>
                                </li>
                            @endguest
                        </ul>
                    </div>

                </div>
            </div>
        </nav>
    </div>
</header>
