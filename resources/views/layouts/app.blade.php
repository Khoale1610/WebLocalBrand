<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Owen - Thời Trang Nam High-End')</title>
    
    <!-- Google Fonts: Plus Jakarta Sans & Inter (Hỗ trợ đầy đủ tiếng Việt chuẩn) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS cho Typography, Mega Menu & Local Brand Styling -->
    <style>
        /* Hệ thống Typography nâng cấp toàn trang */
        :root {
            --font-primary: 'Plus Jakarta Sans', 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            --font-heading: 'Plus Jakarta Sans', 'Inter', sans-serif;
            --brand-black: #111111;
            --brand-red: #C83E3B;
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
            border: 1px solid #E5E7EB !important;
            border-radius: 0px !important;
            text-decoration: none !important;
            transition: all 0.2s ease-in-out;
            box-shadow: none;
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
            color: #C83E3B;
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
            background-color: #C83E3B;
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

        /* OWEN STUDIO — Minimalist Square Account Pages Design System */
        .account-page {
            font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
        }
        .acc-sidebar-box {
            background-color: #FFFFFF;
            border: 1px solid #E5E7EB;
            border-radius: 0px !important;
        }
        .acc-user-header {
            padding: 20px;
            border-bottom: 1px solid #E5E7EB;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .acc-avatar {
            width: 42px;
            height: 42px;
            background-color: #111111;
            color: #FFFFFF;
            font-weight: 700;
            font-size: 15px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 0px !important;
            flex-shrink: 0;
        }
        .acc-nav-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .acc-nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 13px 20px;
            font-size: 13.5px;
            font-weight: 500;
            color: #4B5563;
            text-decoration: none;
            border-bottom: 1px solid #F3F4F6;
            border-left: 3px solid transparent;
            transition: all 0.15s ease;
        }
        .acc-nav-link:last-child {
            border-bottom: none;
        }
        .acc-nav-link:hover {
            background-color: #FAFAFA;
            color: #111111;
        }
        .acc-nav-link.active {
            background-color: #111111;
            color: #FFFFFF;
            border-left-color: #C83E3B;
            font-weight: 600;
        }
        .acc-content-box {
            background-color: #FFFFFF;
            border: 1px solid #E5E7EB;
            border-radius: 0px !important;
            padding: 28px;
        }
        .acc-section-header {
            padding-bottom: 16px;
            margin-bottom: 24px;
            border-bottom: 1px solid #E5E7EB;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }
        .acc-kicker {
            display: block;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.14em;
            color: #C83E3B;
            margin-bottom: 4px;
        }
        .acc-title {
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
            font-size: 20px;
            font-weight: 600;
            color: #111111;
            margin: 0;
        }
        .acc-info-row {
            display: flex;
            flex-direction: column;
            padding: 14px 0;
            border-bottom: 1px solid #F3F4F6;
            font-size: 14px;
        }
        @media (min-width: 768px) {
            .acc-info-row {
                flex-direction: row;
                align-items: center;
            }
        }
        .acc-info-row:last-child {
            border-bottom: none;
        }
        .acc-info-label {
            width: 180px;
            flex-shrink: 0;
            color: #6B7280;
            font-weight: 500;
            font-size: 13.5px;
        }
        .acc-info-value {
            color: #111111;
            font-weight: 500;
            font-size: 14px;
        }
        .acc-input {
            height: 42px;
            border: 1px solid #E5E7EB !important;
            border-radius: 0px !important;
            font-size: 14px;
            color: #111111;
            box-shadow: none !important;
            padding: 0 14px;
        }
        .acc-input:focus {
            border-color: #111111 !important;
            box-shadow: none !important;
        }
        .btn-acc-primary {
            height: 44px;
            background-color: #111111;
            color: #FFFFFF;
            border: 1px solid #111111;
            border-radius: 0px !important;
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 24px;
            text-decoration: none;
            transition: all 0.2s ease;
        }
        .btn-acc-primary:hover {
            background-color: #C83E3B;
            border-color: #C83E3B;
            color: #FFFFFF;
        }
        .btn-acc-outline {
            height: 38px;
            background-color: #FFFFFF;
            color: #111111;
            border: 1px solid #E5E7EB;
            border-radius: 0px !important;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 16px;
            text-decoration: none;
            transition: all 0.15s ease;
        }
        .btn-acc-outline:hover {
            border-color: #C83E3B;
            color: #C83E3B;
            background-color: #FFF5F5;
        }

        /* =========================================================
           RESPONSIVE MOBILE & TABLET SYSTEM
           ========================================================= */
        html, body {
            overflow-x: hidden;
        }
        @media (max-width: 991.98px) {
            .navbar .megamenu {
                padding: 16px;
                max-height: 60vh;
                overflow-y: auto;
                box-shadow: none;
                border-top: 1px solid #e5e7eb;
                border-bottom: 1px solid #e5e7eb;
            }
            .nav-link {
                padding: 0.55rem 0.25rem !important;
            }
        }
        @media (max-width: 767.98px) {
            html {
                font-size: 15.5px;
            }
            .top-bar {
                font-size: 0.78rem;
                padding-left: 8px;
                padding-right: 8px;
            }
            .sticky-top:not(header) {
                position: static !important;
            }
            /* Hero Banners gọn gàng trên mobile */
            .lb-hero-slide,
            .lb-col-hero,
            .lb-show-hero,
            .lb-sale-hero {
                height: auto !important;
                min-height: 390px !important;
                padding-top: 48px !important;
                padding-bottom: 48px !important;
            }
            .lb-hero-overlay,
            .lb-col-hero-overlay,
            .lb-show-hero-overlay {
                position: relative !important;
                background: linear-gradient(180deg, rgba(0,0,0,0.65) 0%, rgba(0,0,0,0.82) 100%) !important;
                padding: 36px 0 !important;
            }
            .lb-hero-img,
            .lb-col-hero-img,
            .lb-show-hero-bg {
                position: absolute !important;
                inset: 0 !important;
            }
            .lb-hero-title,
            .lb-col-hero-title,
            .lb-show-title,
            .lb-sale-title {
                font-size: 2rem !important;
                line-height: 1.15 !important;
            }
            .lb-hero-desc {
                font-size: 0.96rem !important;
                margin-bottom: 20px !important;
            }
            .btn-lb-primary,
            .btn-lb-outline-light {
                padding: 11px 20px !important;
                font-size: 0.82rem !important;
            }
            /* Bento & Lookbook */
            .lb-bento-card {
                height: 250px !important;
            }
            .lb-bento-card.lb-bento-sm {
                height: 195px !important;
            }
            .lb-bento-content {
                padding: 18px !important;
            }
            .lb-lookbook-media {
                min-height: 250px !important;
            }
            .lb-section-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
                margin-bottom: 24px;
            }
            .lb-section-title {
                font-size: 1.5rem !important;
            }
            /* Product Cards */
            .lb-product-name {
                font-size: 0.92rem !important;
            }
            .lb-product-price {
                font-size: 0.96rem !important;
            }
            .lb-product-price-old {
                font-size: 0.8rem !important;
            }
            .lb-quick-view {
                transform: translateY(0) !important;
                font-size: 0.72rem !important;
                padding: 7px 8px !important;
                left: 6px !important;
                right: 6px !important;
                bottom: 6px !important;
            }
            /* Pagination wrap trên màn hình nhỏ */
            .pagination {
                flex-wrap: wrap !important;
                row-gap: 6px;
            }
            .pagination .page-link {
                min-width: 34px;
                height: 34px;
                padding: 0 10px !important;
                font-size: 13px !important;
            }
            /* Tabs chi tiết sản phẩm cuộn ngang không bị vỡ */
            .pd-nav-tabs {
                flex-wrap: nowrap !important;
                overflow-x: auto !important;
                -webkit-overflow-scrolling: touch;
            }
            .pd-nav-tabs .nav-link {
                white-space: nowrap !important;
                font-size: 12.5px !important;
                padding: 12px 14px !important;
            }
            /* Gallery chi tiết sản phẩm xếp dọc gọn trên mobile */
            .pd-gallery-container {
                flex-direction: column !important;
            }
            .pd-thumbs-column {
                flex-direction: row !important;
                flex-wrap: wrap !important;
                width: 100% !important;
            }
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