<header class="admin-navbar">
    <div class="d-flex align-items-center">
        <!-- Sidebar Toggle Mobile/Desktop -->
        <button type="button" class="btn btn-outline-secondary rounded-none me-3 px-2 py-1" id="btn-sidebar-toggle" aria-label="Menu">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#111111" stroke-width="1.5" stroke-linecap="square" stroke-linejoin="miter">
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <line x1="3" y1="12" x2="21" y2="12"></line>
                <line x1="3" y1="18" x2="15" y2="18"></line>
            </svg>
        </button>

        <!-- Current Breadcrumb / Section Name -->
        <nav aria-label="breadcrumb" class="d-none d-md-block">
            <ol class="breadcrumb mb-0" style="font-size: 13px;">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Quản trị</a></li>
                <li class="breadcrumb-item active fw-semibold text-dark" aria-current="page">@yield('page_title', 'Bảng điều khiển')</li>
            </ol>
        </nav>
    </div>

    <!-- Right Controls -->
    <div class="d-flex align-items-center gap-2">
        <!-- Quick View Store Button -->
        <a href="{{ url('/') }}" target="_blank" class="btn btn-sm btn-outline-dark rounded-none px-3 py-1 d-none d-sm-inline-flex align-items-center gap-2" style="height: 36px; font-size: 12px; text-transform: uppercase; letter-spacing: 0.06em;">
            <span>Xem cửa hàng</span>
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="square" stroke-linejoin="miter">
                <line x1="7" y1="17" x2="17" y2="7"></line>
                <polyline points="7 7 17 7 17 17"></polyline>
            </svg>
        </a>

        <!-- Notifications dropdown -->
        <div class="dropdown">
            <button class="btn btn-outline-secondary rounded-none position-relative p-0 d-inline-flex align-items-center justify-content-center" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="width: 36px; height: 36px;">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="square" stroke-linejoin="miter">
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                    <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                </svg>
                @php
                    $pendingAlertCount = \App\Models\Order::where('order_status', 'pending')->count();
                @endphp
                @if($pendingAlertCount > 0)
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-none bg-danger" style="font-size: 10px; padding: 2px 5px;">
                        {{ $pendingAlertCount }}
                    </span>
                @endif
            </button>
            <ul class="dropdown-menu dropdown-menu-end rounded-none border p-2" style="min-width: 260px; border-color: #E5E7EB !important;">
                <li class="dropdown-header text-uppercase fw-semibold small text-muted" style="font-size: 11px; letter-spacing: 0.08em;">Thông báo đơn hàng</li>
                @if($pendingAlertCount > 0)
                    <li>
                        <a class="dropdown-item py-2 rounded-none" href="{{ Route::has('admin.orders.index') ? route('admin.orders.index') : '#' }}">
                            <div class="fw-semibold" style="font-size: 13px; color: #111111;">Có {{ $pendingAlertCount }} đơn hàng chờ xác nhận</div>
                            <small class="text-muted">Nhấn để kiểm tra và duyệt đơn</small>
                        </a>
                    </li>
                @else
                    <li class="text-center py-3 text-muted small">Không có thông báo mới</li>
                @endif
            </ul>
        </div>

        <!-- User Profile Dropdown -->
        <div class="dropdown">
            <button class="btn btn-outline-secondary rounded-none d-flex align-items-center gap-2 py-1 px-2" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="height: 36px;">
                <div class="user-avatar-circle rounded-none" style="width: 26px; height: 26px; font-size: 11.5px; background-color: #111111; color: #FFFFFF; border: none;">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                </div>
                <span class="fw-medium d-none d-md-inline" style="font-size: 13px;">{{ auth()->user()->name ?? 'Admin' }}</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end rounded-none border mt-1 py-2" style="border-color: #E5E7EB !important;">
                <li class="px-3 py-2 border-bottom">
                    <div class="fw-semibold text-dark" style="font-size: 13.5px;">{{ auth()->user()->name ?? 'Admin' }}</div>
                    <div class="text-muted" style="font-size: 12px;">{{ auth()->user()->email ?? '' }}</div>
                </li>
                <li><a class="dropdown-item py-2" style="font-size: 13px;" href="{{ route('account.index') }}">Tài khoản cá nhân</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item py-2 text-danger" style="font-size: 13px;">
                            Đăng xuất
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</header>
