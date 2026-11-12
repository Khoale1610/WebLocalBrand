<aside id="admin-sidebar" class="admin-sidebar">
    <!-- Brand Logo / Name -->
    <div class="sidebar-brand">
        <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center text-decoration-none">
            <span class="brand-badge me-2"><i class="fa-solid fa-vest-patches"></i></span>
            <div class="brand-text">
                <span class="brand-title">OWEN STUDIO</span>
                <span class="brand-subtitle">ADMIN PANEL</span>
            </div>
        </a>
        <button type="button" class="btn-sidebar-close d-lg-none text-white border-0 bg-transparent" id="btn-sidebar-close">
            <i class="fa-solid fa-xmark fs-5"></i>
        </button>
    </div>

    <!-- Navigation Menu -->
    <div class="sidebar-menu-wrapper">
        <ul class="sidebar-nav">
            <!-- 1. TỔNG QUAN -->
            <li class="nav-section-title">TỔNG QUAN</li>
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-pie nav-icon"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- 2. QUẢN LÝ BÁN HÀNG -->
            <li class="nav-section-title">QUẢN LÝ BÁN HÀNG</li>
            <li class="nav-item">
                <a href="{{ Route::has('admin.orders.index') ? route('admin.orders.index') : '#' }}" 
                   class="nav-link {{ request()->is('admin/orders*') ? 'active' : '' }}">
                    <i class="fa-solid fa-receipt nav-icon"></i>
                    <span>Đơn hàng</span>
                    @php
                        $pendingOrders = \App\Models\Order::whereIn('order_status', ['pending', 'processing'])->count();
                    @endphp
                    @if($pendingOrders > 0)
                        <span class="badge bg-danger rounded-pill ms-auto">{{ $pendingOrders }}</span>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ Route::has('admin.vouchers.index') ? route('admin.vouchers.index') : '#' }}" 
                   class="nav-link {{ request()->is('admin/vouchers*') ? 'active' : '' }}">
                    <i class="fa-solid fa-ticket-simple nav-icon"></i>
                    <span>Mã giảm giá (Voucher)</span>
                </a>
            </li>

            <!-- 3. QUẢN LÝ KHO & SẢN PHẨM -->
            <li class="nav-section-title">SẢN PHẨM & KHO</li>
            <li class="nav-item">
                <a href="{{ Route::has('admin.categories.index') ? route('admin.categories.index') : '#' }}" 
                   class="nav-link {{ request()->is('admin/categories*') ? 'active' : '' }}">
                    <i class="fa-solid fa-layer-group nav-icon"></i>
                    <span>Danh mục</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ Route::has('admin.products.index') ? route('admin.products.index') : '#' }}" 
                   class="nav-link {{ request()->is('admin/products*') ? 'active' : '' }}">
                    <i class="fa-solid fa-shirt nav-icon"></i>
                    <span>Sản phẩm & Biến thể</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ Route::has('admin.attributes.index') ? route('admin.attributes.index') : '#' }}" 
                   class="nav-link {{ request()->is('admin/attributes*') ? 'active' : '' }}">
                    <i class="fa-solid fa-tags nav-icon"></i>
                    <span>Thuộc tính (Size / Màu)</span>
                </a>
            </li>

            <!-- 4. NGƯỜI DÙNG & HỆ THỐNG -->
            <li class="nav-section-title">KHÁCH HÀNG & HỆ THỐNG</li>
            <li class="nav-item">
                <a href="{{ Route::has('admin.users.index') ? route('admin.users.index') : '#' }}" 
                   class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}">
                    <i class="fa-solid fa-users nav-icon"></i>
                    <span>Khách hàng</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('/') }}" target="_blank" class="nav-link">
                    <i class="fa-solid fa-arrow-up-right-from-square nav-icon text-info"></i>
                    <span>Xem website</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Admin Footer Profile Mini -->
    <div class="sidebar-user-footer">
        <div class="d-flex align-items-center">
            <div class="user-avatar-circle">
                {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
            </div>
            <div class="user-info ms-2 overflow-hidden">
                <div class="user-name text-truncate">{{ auth()->user()->name ?? 'Quản trị viên' }}</div>
                <div class="user-role badge bg-primary-subtle text-primary border border-primary-subtle">Admin</div>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="ms-auto">
                @csrf
                <button type="submit" class="btn btn-sm btn-link text-danger p-0 text-decoration-none" title="Đăng xuất">
                    <i class="fa-solid fa-right-from-bracket fs-6"></i>
                </button>
            </form>
        </div>
    </div>
</aside>
