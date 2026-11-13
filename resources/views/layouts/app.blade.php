<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Owen - Thời Trang Nam High-End')</title>
    
    <!-- Google Fonts: Plus Jakarta Sans & Oswald (Chuẩn Local Brand Hiện Đại) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS cho Typography, Mega Menu & Local Brand Styling -->
    <style>
        /* Hệ thống Typography nâng cấp toàn trang */
        :root {
            --font-primary: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            --font-heading: 'Oswald', 'Plus Jakarta Sans', sans-serif;
            --brand-black: #111111;
            --brand-red: #d32f2f;
        }
        html {
            font-size: 16.5px; /* Tăng kích thước chữ gốc toàn trang để rõ nét và dễ đọc hơn */
        }
        body {
            font-family: var(--font-primary);
            font-size: 1rem;
            line-height: 1.65;
            color: #1a1a1a;
            background-color: #ffffff;
            -webkit-font-smoothing: antialiased;
        }
        h1, h2, h3, h4, .brand-heading {
            font-family: var(--font-primary);
            letter-spacing: -0.02em;
            font-weight: 800;
        }
        /* Nâng kích thước các chữ nhỏ để không bị lí nhí */
        .small, small {
            font-size: 0.9rem !important;
        }
        .nav-link {
            font-size: 1.02rem !important;
            letter-spacing: 0.02em;
            padding: 0.6rem 1.1rem !important;
            color: #111 !important;
            transition: color 0.2s ease;
        }
        .nav-link:hover, .nav-link.active {
            color: var(--brand-red) !important;
        }
        .btn {
            font-size: 0.96rem;
            font-weight: 600;
            letter-spacing: 0.02em;
        }
        .card-title {
            font-size: 1.05rem !important;
            line-height: 1.45;
        }

        /* CSS cho Mega Menu */
        .navbar .megamenu {
            padding: 28px;
            width: 100%;
            border-radius: 0;
            border: none;
            border-top: 2px solid #111;
            box-shadow: 0px 15px 30px rgba(0,0,0,0.12);
        }
        .navbar .has-megamenu { position: static!important; }
        .megamenu h6 { font-weight: 800; text-transform: uppercase; margin-bottom: 15px; font-size: 1rem; letter-spacing: 0.04em; }
        .megamenu ul { list-style: none; padding: 0; }
        .megamenu ul li a { color: #333; text-decoration: none; display: block; padding: 6px 0; font-size: 0.96rem; font-weight: 500; transition: transform 0.2s ease, color 0.2s ease; }
        .megamenu ul li a:hover { color: #d32f2f; transform: translateX(4px); }
        .top-bar { background-color: #0a0a0a; color: #f5f5f5; font-size: 0.88rem; letter-spacing: 0.05em; font-weight: 500; }

        /* Custom Pagination Styling chuẩn và đẹp */
        .pagination {
            display: inline-flex !important;
            flex-direction: row !important;
            align-items: center !important;
            justify-content: center !important;
            margin: 0 !important;
            padding: 0 !important;
            list-style: none !important;
            gap: 6px;
        }
        .pagination li.page-item {
            display: inline-block !important;
            margin: 0 !important;
        }
        .pagination .page-link {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            min-width: 38px;
            height: 38px;
            padding: 0 12px !important;
            font-size: 14px !important;
            font-weight: 500;
            color: #333 !important;
            background-color: #fff !important;
            border: 1px solid #dee2e6 !important;
            border-radius: 8px !important;
            text-decoration: none !important;
            transition: all 0.2s ease-in-out;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }
        .pagination .page-link:hover {
            color: #fff !important;
            background-color: #111 !important;
            border-color: #111 !important;
        }
        .pagination .page-item.active .page-link {
            color: #fff !important;
            background-color: #111 !important;
            border-color: #111 !important;
            font-weight: 700;
        }
        .pagination .page-item.disabled .page-link {
            color: #adb5bd !important;
            background-color: #f8f9fa !important;
            border-color: #dee2e6 !important;
            cursor: not-allowed;
            opacity: 0.6;
        }
        /* Ngăn SVG mặc định của Laravel pagination bị tràn kích thước */
        .pagination svg, nav svg {
            width: 16px !important;
            height: 16px !important;
            max-width: 16px !important;
            max-height: 16px !important;
            display: inline-block !important;
            vertical-align: middle !important;
        }

        /* Hệ thống Local Brand Design System dùng chung toàn trang */
        .lb-kicker {
            display: inline-block;
            font-size: 0.84rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.22em;
            color: #fff;
            border-left: 3px solid #d32f2f;
            padding-left: 12px;
            margin-bottom: 14px;
        }
        .lb-section-header {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: flex-end;
            gap: 12px;
            margin-bottom: 34px;
            border-bottom: 2px solid #111;
            padding-bottom: 16px;
        }
        .lb-section-subtitle {
            font-size: 0.82rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.18em;
            color: #d32f2f;
            margin-bottom: 4px;
            display: block;
        }
        .lb-section-title {
            font-family: var(--font-heading);
            font-size: 2.1rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.02em;
            color: #111;
            margin: 0;
        }
        .lb-view-all {
            font-size: 0.92rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #111;
            text-decoration: none;
            padding-bottom: 2px;
            border-bottom: 2px solid transparent;
            transition: all 0.2s;
        }
        .lb-view-all:hover {
            color: #d32f2f;
            border-bottom-color: #d32f2f;
        }
        .btn-lb-dark {
            background-color: #111111;
            color: #ffffff;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            padding: 13px 28px;
            border-radius: 0;
            border: 2px solid #111111;
            font-size: 0.9rem;
            text-decoration: none;
            display: inline-block;
            transition: all 0.25s ease;
        }
        .btn-lb-dark:hover {
            background-color: #d32f2f;
            border-color: #d32f2f;
            color: #ffffff;
        }
        .btn-lb-outline {
            background-color: transparent;
            color: #111111;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            padding: 12px 26px;
            border-radius: 0;
            border: 2px solid #111111;
            font-size: 0.88rem;
            text-decoration: none;
            display: inline-block;
            transition: all 0.25s ease;
        }
        .btn-lb-outline:hover {
            background-color: #111111;
            color: #ffffff;
        }

        /* Product Card chuẩn Local Brand Studio (Dùng chung) */
        .lb-product-card {
            border: none;
            background: transparent;
            transition: all 0.25s ease;
        }
        .lb-product-thumb {
            position: relative;
            overflow: hidden;
            background-color: #f4f4f5;
            aspect-ratio: 3 / 4;
            margin-bottom: 14px;
        }
        .lb-product-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.55s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .lb-product-card:hover .lb-product-img {
            transform: scale(1.06);
        }
        .lb-badge-group {
            position: absolute;
            top: 12px;
            left: 12px;
            display: flex;
            flex-direction: column;
            gap: 6px;
            z-index: 2;
        }
        .lb-badge {
            font-size: 0.72rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            padding: 5px 10px;
            display: inline-block;
        }
        .lb-badge-dark {
            background-color: #111;
            color: #fff;
        }
        .lb-badge-red {
            background-color: #d32f2f;
            color: #fff;
        }
        .lb-quick-view {
            position: absolute;
            left: 12px;
            right: 12px;
            bottom: 12px;
            background-color: rgba(17, 17, 17, 0.92);
            color: #fff;
            text-align: center;
            padding: 11px 14px;
            font-size: 0.82rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            text-decoration: none;
            transform: translateY(120%);
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), background-color 0.2s;
            z-index: 2;
        }
        .lb-product-card:hover .lb-quick-view {
            transform: translateY(0);
        }
        .lb-quick-view:hover {
            background-color: #d32f2f;
            color: #fff;
        }
        .lb-product-cat {
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #71717a;
            margin-bottom: 4px;
        }
        .lb-product-name {
            font-size: 1.04rem;
            font-weight: 700;
            color: #111;
            text-decoration: none;
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
            margin-bottom: 6px;
            transition: color 0.2s;
        }
        .lb-product-name:hover {
            color: #d32f2f;
        }
        .lb-product-price {
            font-size: 1.06rem;
            font-weight: 800;
            color: #111;
        }
        .lb-product-price-old {
            font-size: 0.9rem;
            color: #a1a1aa;
            text-decoration: line-through;
            margin-left: 8px;
            font-weight: 500;
        }
        .lb-breadcrumb {
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- 1. Top Bar & Header (Thanh trên cùng & Mega Menu) -->
    @include('layouts.includes.header')

    <!-- 2. Main Content (Nội dung thay đổi theo từng trang) -->
    <main>
        @yield('content')
    </main>

    <!-- 3. Footer -->
    @include('layouts.includes.footer')

    <!-- 4. Trợ lý AI Chatbot Floating Widget -->
    @include('layouts.includes.chatbot')

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')

</body>
</html>