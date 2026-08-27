<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Owen - Thời Trang Nam High-End')</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS cho Mega Menu & Styling -->
    <style>
        /* CSS cho Mega Menu */
        .navbar .megamenu {
            padding: 20px;
            width: 100%;
            border-radius: 0;
            border: none;
            box-shadow: 0px 8px 16px rgba(0,0,0,0.1);
        }
        .navbar .has-megamenu { position: static!important; }
        .megamenu h6 { font-weight: bold; text-transform: uppercase; margin-bottom: 15px; }
        .megamenu ul { list-style: none; padding: 0; }
        .megamenu ul li a { color: #333; text-decoration: none; display: block; padding: 4px 0; font-size: 14px; }
        .megamenu ul li a:hover { color: #d32f2f; }
        .top-bar { background-color: #111; color: #fff; font-size: 13px; }
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

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>