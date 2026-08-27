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
                                            <li><a href="#">Áo Sơ Mi</a></li>
                                            <li><a href="#">Áo Polo</a></li>
                                            <li><a href="#">Áo T-Shirt (Thun)</a></li>
                                            <li><a href="#">Áo Khoác</a></li>
                                            <li><a href="#">Áo Vest / Blazer</a></li>
                                        </ul>
                                    </div>
                                    <!-- Cột 2: Quần Nam -->
                                    <div class="col-md-3">
                                        <h6 class="text-danger">Quần Nam</h6>
                                        <ul>
                                            <li><a href="#">Quần Tây / Âu</a></li>
                                            <li><a href="#">Quần Khaki</a></li>
                                            <li><a href="#">Quần Jeans</a></li>
                                            <li><a href="#">Quần Short</a></li>
                                        </ul>
                                    </div>
                                    <!-- Cột 3: Phụ Kiện -->
                                    <div class="col-md-3">
                                        <h6 class="text-danger">Phụ Kiện</h6>
                                        <ul>
                                            <li><a href="#">Thắt Lưng</a></li>
                                            <li><a href="#">Cà Vạt / Ví Da</a></li>
                                            <li><a href="#">Tất / Sịp Nam</a></li>
                                        </ul>
                                    </div>
                                    <!-- Cột 4: Banner Khuyến Mãi -->
                                    <div class="col-md-3">
                                        <div class="bg-light p-3 text-center border">
                                            <h6 class="text-dark">Bộ Sưu Tập Mới</h6>
                                            <p class="small text-muted">Khám phá phong cách công sở hiện đại 2026</p>
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
                    <form class="d-flex" action="#" method="GET">
                        <div class="input-group">
                            <input class="form-control form-control-sm" type="search" placeholder="Tìm sản phẩm..." name="keyword">
                            <button class="btn btn-outline-secondary btn-sm" type="submit"><i class="fas fa-search"></i></button>
                        </div>
                    </form>

                    <!-- Icon Giỏ Hàng & Tài Khoản -->
                    <a href="{{ route('cart.index') }}" class="text-dark position-relative fs-5 ms-2">
                        <i class="fas fa-shopping-bag"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger fs-6" style="font-size: 0.65rem !important;">
                            2
                        </span>
                    </a>

                    <a href="#" class="text-dark fs-5 ms-2" title="Tài khoản">
                        <i class="far fa-user"></i>
                    </a>
                </div>
            </div>
        </nav>
    </div>
</header>