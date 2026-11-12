<header class="admin-navbar">
    <div class="d-flex align-items-center">
        <!-- Sidebar Toggle Mobile/Desktop -->
        <button type="button" class="btn btn-outline-secondary border-0 btn-sidebar-toggle me-3" id="btn-sidebar-toggle">
            <i class="fa-solid fa-bars-staggered fs-5"></i>
        </button>

        <!-- Current Breadcrumb / Section Name -->
        <nav aria-label="breadcrumb" class="d-none d-md-block">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Admin</a></li>
                <li class="breadcrumb-item active fw-semibold text-dark" aria-current="page">@yield('page_title', 'Bảng điều khiển')</li>
            </ol>
        </nav>
    </div>

    <!-- Right Controls -->
    <div class="d-flex align-items-center gap-2">
        <!-- Quick View Store Button -->
        <a href="{{ url('/') }}" target="_blank" class="btn btn-sm btn-outline-dark rounded-pill px-3 d-none d-sm-inline-flex align-items-center gap-1">
            <i class="fa-solid fa-store"></i>
            <span>Cửa hàng</span>
        </a>

        <!-- Notifications dropdown placeholder -->
        <div class="dropdown">
            <button class="btn btn-light rounded-circle position-relative p-2" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="width: 38px; height: 38px;">
                <i class="fa-regular fa-bell"></i>
                @php
                    $pendingAlertCount = \App\Models\Order::where('order_status', 'pending')->count();
                @endphp
                @if($pendingAlertCount > 0)
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 10px;">
                        {{ $pendingAlertCount }}
                    </span>
                @endif
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 p-2" style="min-width: 260px;">
                <li class="dropdown-header text-uppercase fw-bold small text-muted">Thông báo mới</li>
                @if($pendingAlertCount > 0)
                    <li>
                        <a class="dropdown-item py-2 rounded" href="{{ Route::has('admin.orders.index') ? route('admin.orders.index') : '#' }}">
                            <div class="d-flex align-items-center">
                                <div class="bg-warning-subtle text-warning p-2 rounded-circle me-2">
                                    <i class="fa-solid fa-cart-shopping"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold small">Có {{ $pendingAlertCount }} đơn hàng mới</div>
                                    <small class="text-muted">Cần xác nhận ngay</small>
                                </div>
                            </div>
                        </a>
                    </li>
                @else
                    <li class="text-center py-3 text-muted small">Không có thông báo mới nào</li>
                @endif
            </ul>
        </div>

        <!-- User Profile Dropdown -->
        <div class="dropdown">
            <button class="btn btn-light border-0 d-flex align-items-center gap-2 py-1 px-2 rounded-pill" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="user-avatar-circle small" style="width: 32px; height: 32px; font-size: 13px;">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                </div>
                <span class="fw-medium small d-none d-md-inline">{{ auth()->user()->name ?? 'Admin' }}</span>
                <i class="fa-solid fa-chevron-down text-muted" style="font-size: 10px;"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2 py-2">
                <li class="px-3 py-2 border-bottom">
                    <div class="fw-bold">{{ auth()->user()->name ?? 'Admin' }}</div>
                    <div class="small text-muted">{{ auth()->user()->email ?? '' }}</div>
                </li>
                <li><a class="dropdown-item py-2" href="{{ route('account.index') }}"><i class="fa-regular fa-user me-2 text-muted"></i>Thông tin cá nhân</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item py-2 text-danger">
                            <i class="fa-solid fa-right-from-bracket me-2"></i>Đăng xuất
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</header>
