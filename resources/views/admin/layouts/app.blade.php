<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') — OWEN STUDIO</title>

    <!-- Google Fonts: Plus Jakarta Sans & Inter (Hỗ trợ đầy đủ tiếng Việt chuẩn) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- OWEN STUDIO — Minimalist Square Admin Design System -->
    <style>
        :root {
            --sidebar-width: 256px;
            --sidebar-bg: #111111;
            --sidebar-hover: #1C1C1C;
            --sidebar-active: #C83E3B;
            --sidebar-text: #9CA3AF;
            --sidebar-text-active: #FFFFFF;
            --navbar-height: 64px;
            --bg-body: #FAFAFA;
            --card-border: #E5E7EB;
            --brand-black: #111111;
            --brand-red: #C83E3B;
        }

        body {
            font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            font-size: 14px;
            line-height: 1.55;
            background-color: var(--bg-body);
            color: #1F2937;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        /* Enforce 0px Square Corners Across Entire Admin Panel */
        .rounded-none,
        .rounded,
        .rounded-1,
        .rounded-2,
        .rounded-3,
        .rounded-4,
        .rounded-pill,
        .rounded-circle,
        .card,
        .card-header,
        .card-footer,
        .btn,
        .badge,
        .form-control,
        .form-select,
        .input-group-text,
        .modal-content,
        .modal-header,
        .modal-footer,
        .dropdown-menu,
        .dropdown-item,
        .nav-link,
        .alert,
        .pagination .page-link,
        code {
            border-radius: 0px !important;
        }

        /* Remove Soft Shadows */
        .shadow,
        .shadow-sm,
        .shadow-lg,
        .shadow-xs {
            box-shadow: none !important;
        }

        /* Layout Architecture */
        .admin-layout {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styling */
        .admin-sidebar {
            width: var(--sidebar-width);
            background-color: var(--sidebar-bg);
            color: var(--sidebar-text);
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 1040;
            display: flex;
            flex-direction: column;
            border-right: 1px solid #222222;
            transition: transform 0.25s ease-in-out;
        }

        .sidebar-brand {
            height: var(--navbar-height);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            border-bottom: 1px solid #222222;
        }

        .brand-badge {
            width: 34px;
            height: 34px;
            background-color: #FFFFFF;
            color: #111111;
            border-radius: 0px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 14px;
            letter-spacing: 0.04em;
        }

        .brand-title {
            display: block;
            font-weight: 700;
            color: #FFFFFF;
            letter-spacing: 0.08em;
            font-size: 14px;
            line-height: 1.2;
        }

        .brand-subtitle {
            display: block;
            font-size: 10px;
            letter-spacing: 0.16em;
            color: #9CA3AF;
            font-weight: 500;
            margin-top: 2px;
        }

        .sidebar-menu-wrapper {
            flex: 1;
            overflow-y: auto;
            padding: 14px 0;
        }

        .sidebar-nav {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .nav-section-title {
            padding: 14px 20px 6px;
            font-size: 10.5px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.14em;
            color: #6B7280;
        }

        .sidebar-nav .nav-link {
            display: flex;
            align-items: center;
            padding: 10px 20px;
            color: var(--sidebar-text);
            font-size: 13.5px;
            font-weight: 500;
            transition: all 0.15s ease;
            border-left: 3px solid transparent;
            text-decoration: none;
        }

        .sidebar-nav .nav-link:hover {
            color: var(--sidebar-text-active);
            background-color: var(--sidebar-hover);
        }

        .sidebar-nav .nav-link.active {
            color: var(--sidebar-text-active);
            background-color: #1C1C1C;
            border-left-color: var(--brand-red);
            font-weight: 600;
        }

        .sidebar-nav .nav-icon {
            width: 20px;
            font-size: 14px;
            margin-right: 12px;
            display: inline-flex;
            justify-content: center;
            opacity: 0.85;
        }

        .sidebar-user-footer {
            padding: 14px 20px;
            background-color: #0A0A0A;
            border-top: 1px solid #222222;
        }

        .user-avatar-circle {
            width: 34px;
            height: 34px;
            background-color: #FFFFFF;
            color: #111111;
            border: 1px solid #E5E7EB;
            font-weight: 700;
            font-size: 13px;
            border-radius: 0px !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .user-name {
            font-size: 13px;
            font-weight: 600;
            color: #FFFFFF;
        }

        .user-role {
            font-size: 10px;
            padding: 2px 6px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            background-color: #222222 !important;
            color: #D1D5DB !important;
            border: 1px solid #333333 !important;
        }

        /* Main Wrapper */
        .admin-main {
            margin-left: var(--sidebar-width);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
            min-height: 100vh;
            transition: margin-left 0.25s ease-in-out;
        }

        /* Top Navbar */
        .admin-navbar {
            height: var(--navbar-height);
            background-color: #FFFFFF;
            border-bottom: 1px solid var(--card-border);
            padding: 0 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 1030;
        }

        /* Content Body */
        .admin-content {
            padding: 24px;
            flex: 1;
        }

        /* General Card & Table UI */
        .card {
            border: 1px solid var(--card-border) !important;
            background-color: #FFFFFF;
        }

        .card-header {
            background-color: #FFFFFF !important;
            border-bottom: 1px solid var(--card-border) !important;
            padding: 14px 20px;
            font-weight: 600;
            font-size: 14.5px;
            color: #111111;
        }

        .card-footer {
            background-color: #FFFFFF !important;
            border-top: 1px solid var(--card-border) !important;
        }

        .table thead th {
            background-color: #FAFAFA !important;
            border-bottom: 1px solid #E5E7EB !important;
            font-size: 11.5px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #6B7280 !important;
            padding: 12px 16px;
        }

        .table tbody td {
            padding: 14px 16px;
            border-bottom: 1px solid #F3F4F6;
            font-size: 13.5px;
            color: #1F2937;
        }

        /* Unified Buttons & Form Controls */
        .btn {
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 0.03em;
            transition: all 0.15s ease;
        }

        .btn-primary,
        .btn-dark {
            background-color: #111111 !important;
            border-color: #111111 !important;
            color: #FFFFFF !important;
        }

        .btn-primary:hover,
        .btn-dark:hover {
            background-color: #C83E3B !important;
            border-color: #C83E3B !important;
            color: #FFFFFF !important;
        }

        .btn-outline-primary,
        .btn-outline-dark,
        .btn-outline-secondary {
            background-color: #FFFFFF !important;
            border: 1px solid #E5E7EB !important;
            color: #111111 !important;
        }

        .btn-outline-primary:hover,
        .btn-outline-dark:hover,
        .btn-outline-secondary:hover {
            background-color: #111111 !important;
            border-color: #111111 !important;
            color: #FFFFFF !important;
        }

        .btn-danger {
            background-color: #C83E3B !important;
            border-color: #C83E3B !important;
            color: #FFFFFF !important;
        }

        .btn-outline-danger {
            background-color: #FFFFFF !important;
            border: 1px solid #E5E7EB !important;
            color: #C83E3B !important;
        }

        .btn-outline-danger:hover {
            background-color: #C83E3B !important;
            border-color: #C83E3B !important;
            color: #FFFFFF !important;
        }

        .form-control,
        .form-select,
        .input-group-text {
            background-color: #FFFFFF !important;
            border: 1px solid #E5E7EB !important;
            font-size: 13.5px;
            color: #111111;
            box-shadow: none !important;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #111111 !important;
            box-shadow: none !important;
        }

        /* Nav Pills (Tabs in Orders / Attributes) */
        .nav-pills .nav-link {
            border: 1px solid #E5E7EB;
            background-color: #FFFFFF !important;
            color: #4B5563 !important;
            font-size: 13px;
            font-weight: 500;
            padding: 8px 16px;
        }

        .nav-pills .nav-link:hover {
            border-color: #111111;
            color: #111111 !important;
        }

        .nav-pills .nav-link.active {
            background-color: #111111 !important;
            border-color: #111111 !important;
            color: #FFFFFF !important;
            font-weight: 600;
        }

        /* Minimalist Badges */
        .badge {
            font-weight: 600;
            font-size: 11.5px;
            letter-spacing: 0.03em;
            padding: 4px 10px;
        }

        .bg-danger {
            background-color: #C83E3B !important;
        }

        .text-danger {
            color: #C83E3B !important;
        }

        .text-primary {
            color: #111111 !important;
        }

        /* Pagination */
        .pagination {
            gap: 4px;
        }
        .pagination .page-link {
            color: #111111 !important;
            border: 1px solid #E5E7EB !important;
            background-color: #FFFFFF !important;
            font-size: 13px;
            font-weight: 500;
            padding: 6px 12px;
        }
        .pagination .page-item.active .page-link,
        .pagination .page-link:hover {
            background-color: #111111 !important;
            border-color: #111111 !important;
            color: #FFFFFF !important;
        }

        /* Admin Footer */
        .admin-footer {
            padding: 14px 24px;
            background-color: #FFFFFF;
            border-top: 1px solid var(--card-border);
            font-size: 12.5px;
            color: #6B7280;
        }

        /* Mobile Responsive */
        .sidebar-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(17, 17, 17, 0.55);
            z-index: 1035;
        }

        @media (max-width: 991.98px) {
            .admin-sidebar {
                transform: translateX(-100%);
            }
            .admin-sidebar.show {
                transform: translateX(0);
            }
            .sidebar-backdrop.show {
                display: block;
            }
            .admin-main {
                margin-left: 0;
            }
        }
    </style>
    @stack('styles')
</head>
<body>

    <div class="admin-layout">
        <!-- Backdrop for mobile sidebar -->
        <div class="sidebar-backdrop" id="sidebar-backdrop"></div>

        <!-- 1. Left Sidebar Navigation -->
        @include('admin.layouts.sidebar')

        <!-- 2. Main Content Area -->
        <div class="admin-main">
            <!-- Top Navbar -->
            @include('admin.layouts.navbar')

            <!-- Body Content -->
            <main class="admin-content">
                <!-- Flash Session Messages -->
                @if(session('success'))
                    <div class="alert alert-dark alert-dismissible fade show d-flex align-items-center rounded-none border-dark mb-4" style="font-size: 13.5px;" role="alert">
                        <div>{{ session('success') }}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center rounded-none mb-4" style="font-size: 13.5px; border-color: #C83E3B; color: #C83E3B; background-color: #FFF5F5;" role="alert">
                        <div>{{ session('error') }}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(isset($errors) && $errors->any())
                    <div class="alert alert-danger alert-dismissible fade show rounded-none mb-4" style="font-size: 13.5px; border-color: #C83E3B; color: #C83E3B; background-color: #FFF5F5;" role="alert">
                        <div class="fw-semibold mb-1">Vui lòng kiểm tra lại thông tin:</div>
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="admin-footer d-flex justify-content-between align-items-center">
                <div>&copy; {{ date('Y') }} <strong class="text-dark">OWEN STUDIO</strong> — Hệ thống Quản trị Cửa hàng.</div>
                <div>v2.0</div>
            </footer>
        </div>
    </div>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Sidebar Mobile Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('admin-sidebar');
            const toggleBtn = document.getElementById('btn-sidebar-toggle');
            const closeBtn = document.getElementById('btn-sidebar-close');
            const backdrop = document.getElementById('sidebar-backdrop');

            function openSidebar() {
                sidebar.classList.add('show');
                backdrop.classList.add('show');
            }

            function closeSidebar() {
                sidebar.classList.remove('show');
                backdrop.classList.remove('show');
            }

            if (toggleBtn) toggleBtn.addEventListener('click', openSidebar);
            if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
            if (backdrop) backdrop.addEventListener('click', closeSidebar);
        });
    </script>

    @stack('scripts')
</body>
</html>
