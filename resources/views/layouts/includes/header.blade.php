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
                                            <a href="{{ route('collections.index') }}" class="btn btn-sm btn-dark">Xem Ngay</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link fw-semibold {{ request()->routeIs('collections.index') || request()->routeIs('collections.show') ? 'active text-danger' : '' }}" href="{{ route('collections.index') }}">Bộ Sưu Tập</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-semibold {{ request()->routeIs('collections.sale') ? 'active fw-bold' : '' }}" style="color: #C83E3B !important;" href="{{ route('collections.sale') }}">Sale Up To 50% <span class="badge rounded-0 px-1 small" style="background-color: #C83E3B; font-size: 10px;">HOT</span></a>
                    </li>
                </ul>

                <!-- Thanh Tìm Kiếm & Chức Năng Người Dùng -->
                <div class="d-flex align-items-center gap-3">
                    <div class="position-relative" id="headerSearchWrapper">
                        <form class="d-flex" action="{{ route('products.search') }}" method="GET" id="headerSearchForm" autocomplete="off">
                            <div class="input-group">
                                <input class="form-control form-control-sm rounded-0 border-secondary-subtle shadow-none" type="search" placeholder="Tìm sản phẩm..." name="query" id="headerSearchInput" value="{{ request('query') }}" style="height: 36px; font-size: 13px;">
                                <button class="btn btn-outline-dark btn-sm rounded-0 px-3" type="submit" style="height: 36px;"><i class="fas fa-search"></i></button>
                            </div>
                        </form>
                        <!-- Khung dropdown hiển thị kết quả gợi ý tức thì -->
                        <div id="liveSearchResults" class="dropdown-menu p-0 border rounded-0 d-none position-absolute start-0 w-100" style="min-width: 280px; z-index: 1050; max-height: 350px; overflow-y: auto; border-color: #E5E7EB !important; box-shadow: 0 10px 25px rgba(0,0,0,0.08);">
                        </div>
                    </div>


                    <!-- Icon Giỏ Hàng -->
                    <a href="{{ route('cart.index') }}" class="text-dark position-relative fs-5 ms-2" title="Giỏ hàng">
                        <i class="fas fa-shopping-bag"></i>
                        <span id="cartCountBadge" class="position-absolute top-0 start-100 translate-middle badge rounded-0" style="background-color: #C83E3B; font-size: 10px !important; padding: 3px 5px;">
                            {{ array_sum(array_column(session('cart', []), 'quantity')) }}
                        </span>
                    </a>

                    <!-- Icon Tài Khoản (Dropdown Vuông góc 0px) -->
                    <div class="dropdown ms-2">
                        <a href="#" class="text-dark fs-5" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false" title="Tài khoản">
                            <i class="far fa-user"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end rounded-0 border py-2 mt-2" aria-labelledby="userDropdown" style="min-width: 230px; border-color: #E5E7EB !important; box-shadow: 0 10px 25px rgba(0,0,0,0.08); font-size: 13.5px;">
                            @guest
                                <li><a class="dropdown-item fw-semibold text-dark py-2" href="{{ route('login') }}">Đăng nhập</a></li>
                                <li><a class="dropdown-item py-2 text-muted" href="{{ route('register') }}">Đăng ký tài khoản</a></li>
                            @else
                                <li class="px-3 py-2 border-bottom" style="border-color: #F3F4F6 !important;">
                                    <div class="text-muted" style="font-size: 11.5px;">Xin chào,</div>
                                    <div class="fw-semibold text-dark text-truncate">{{ Auth::user()->name }}</div>
                                </li>
                                @if(Auth::user()->isAdmin())
                                    <li>
                                        <a class="dropdown-item fw-semibold py-2 d-flex align-items-center gap-2" style="color: #C83E3B;" href="{{ route('admin.dashboard') }}">
                                            <span>Trang Quản trị (Admin)</span>
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider my-1"></li>
                                @endif
                                <li><a class="dropdown-item py-2 text-dark" href="{{ route('account.index') }}">Quản lý tài khoản</a></li>
                                <li><a class="dropdown-item py-2 text-dark" href="{{ route('account.orders') }}">Lịch sử đơn hàng</a></li>
                                <li><a class="dropdown-item py-2 text-dark" href="{{ route('account.wishlist.index') }}">Sản phẩm yêu thích</a></li>
                                <li><a class="dropdown-item py-2 text-dark" href="{{ route('account.addresses') }}">Sổ địa chỉ</a></li>
                                <li><hr class="dropdown-divider my-1"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item py-2" style="color: #C83E3B; font-weight: 500;">Đăng xuất</button>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('headerSearchInput');
    const resultsBox = document.getElementById('liveSearchResults');
    const searchWrapper = document.getElementById('headerSearchWrapper');
    let debounceTimer;

    if (!searchInput || !resultsBox) return;

    searchInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        const query = this.value.trim();

        if (query.length < 2) {
            resultsBox.classList.add('d-none');
            resultsBox.innerHTML = '';
            return;
        }

        debounceTimer = setTimeout(() => {
            fetch(`{{ route('products.search.suggest') }}?query=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(products => {
                    if (!products || products.length === 0) {
                        resultsBox.innerHTML = `
                            <div class="p-3 text-center text-muted small">
                                Không tìm thấy sản phẩm phù hợp.
                            </div>
                        `;
                    } else {
                        let html = '<div class="list-group list-group-flush">';
                        products.forEach(p => {
                            html += `
                                <a href="${p.url}" class="list-group-item list-group-item-action d-flex align-items-center gap-2 py-2 px-3">
                                    <img src="${p.image}" alt="${p.name}" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;" class="border">
                                    <div class="flex-grow-1 text-truncate">
                                        <div class="small fw-semibold text-dark text-truncate">${p.name}</div>
                                        <div class="small text-danger fw-bold">${p.price_formatted}</div>
                                    </div>
                                </a>
                            `;
                        });
                        html += `
                            <a href="{{ route('products.search') }}?query=${encodeURIComponent(query)}" class="list-group-item list-group-item-action text-center small text-primary fw-bold py-2 bg-light">
                                Xem tất cả kết quả cho "${query}" &rarr;
                            </a>
                        </div>`;
                        resultsBox.innerHTML = html;
                    }
                    resultsBox.classList.remove('d-none');
                })
                .catch(err => {
                    console.error('Live search error:', err);
                });
        }, 250);
    });

    // Đóng dropdown khi click ra ngoài
    document.addEventListener('click', function(e) {
        if (!searchWrapper.contains(e.target)) {
            resultsBox.classList.add('d-none');
        }
    });
});
</script>

