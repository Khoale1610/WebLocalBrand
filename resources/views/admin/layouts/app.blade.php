<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') | Owen Local Brand</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Admin Master Styling -->
    <style>
        :root {
            --sidebar-width: 260px;
            --sidebar-bg: #0f172a; /* Slate 900 */
            --sidebar-hover: #1e293b; /* Slate 800 */
            --sidebar-active: #3b82f6; /* Blue 500 */
            --sidebar-text: #94a3b8;
            --sidebar-text-active: #ffffff;
            --navbar-height: 68px;
            --bg-body: #f8fafc;
            --card-border: #e2e8f0;
            --primary-brand: #0f172a;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-body);
            color: #334155;
            overflow-x: hidden;
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
            transition: transform 0.3s ease-in-out;
        }

        .sidebar-brand {
            height: var(--navbar-height);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .brand-badge {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, #3b82f6, #6366f1);
            color: white;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
        }

        .brand-title {
            display: block;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: 0.5px;
            font-size: 0.95rem;
            line-height: 1.2;
        }

        .brand-subtitle {
            display: block;
            font-size: 0.65rem;
            letter-spacing: 1.5px;
            color: #64748b;
            font-weight: 600;
        }

        .sidebar-menu-wrapper {
            flex: 1;
            overflow-y: auto;
            padding: 1rem 0;
        }

        .sidebar-nav {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .nav-section-title {
            padding: 0.8rem 1.5rem 0.4rem;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: #64748b;
        }

        .sidebar-nav .nav-link {
            display: flex;
            align-items: center;
            padding: 0.7rem 1.5rem;
            color: var(--sidebar-text);
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }

        .sidebar-nav .nav-link:hover {
            color: var(--sidebar-text-active);
            background-color: var(--sidebar-hover);
        }

        .sidebar-nav .nav-link.active {
            color: var(--sidebar-text-active);
            background-color: rgba(59, 130, 246, 0.15);
            border-left-color: var(--sidebar-active);
            font-weight: 600;
        }

        .sidebar-nav .nav-icon {
            width: 22px;
            font-size: 1rem;
            margin-right: 0.75rem;
            display: inline-flex;
            justify-content: center;
        }

        .sidebar-user-footer {
            padding: 1rem 1.25rem;
            background-color: rgba(0, 0, 0, 0.2);
            border-top: 1px solid rgba(255, 255, 255, 0.08);
        }

        .user-avatar-circle {
            width: 38px;
            height: 38px;
            background: #3b82f6;
            color: white;
            font-weight: 700;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .user-name {
            font-size: 0.85rem;
            font-weight: 600;
            color: #ffffff;
        }

        .user-role {
            font-size: 0.65rem;
            padding: 0.15rem 0.5rem;
        }

        /* Main Wrapper */
        .admin-main {
            margin-left: var(--sidebar-width);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
            min-height: 100vh;
            transition: margin-left 0.3s ease-in-out;
        }

        /* Top Navbar */
        .admin-navbar {
            height: var(--navbar-height);
            background-color: #ffffff;
            border-bottom: 1px solid var(--card-border);
            padding: 0 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 1030;
        }

        /* Content Body */
        .admin-content {
            padding: 1.75rem 1.5rem;
            flex: 1;
        }

        /* General Card UI */
        .card {
            border: 1px solid var(--card-border);
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
            background: #ffffff;
        }

        .card-header {
            background-color: #ffffff;
            border-bottom: 1px solid var(--card-border);
            padding: 1rem 1.25rem;
            font-weight: 600;
        }

        /* Admin Footer */
        .admin-footer {
            padding: 1rem 1.5rem;
            background-color: #ffffff;
            border-top: 1px solid var(--card-border);
            font-size: 0.8rem;
            color: #64748b;
        }

        /* Mobile Responsive */
        .sidebar-backdrop {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(15, 23, 42, 0.6);
            z-index: 1035;
            backdrop-filter: blur(2px);
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
                    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
                        <i class="fa-solid fa-circle-check fs-5 me-2"></i>
                        <div>{{ session('success') }}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
                        <i class="fa-solid fa-circle-exclamation fs-5 me-2"></i>
                        <div>{{ session('error') }}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(isset($errors) && $errors->any())
                    <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
                        <div class="d-flex align-items-center mb-1">
                            <i class="fa-solid fa-triangle-exclamation me-2"></i>
                            <strong>Có lỗi xảy ra, vui lòng kiểm tra lại:</strong>
                        </div>
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
                <div>&copy; {{ date('Y') }} <strong>Owen Local Brand</strong>. Hệ thống Quản trị Bán hàng.</div>
                <div>Phiên bản 1.0</div>
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
