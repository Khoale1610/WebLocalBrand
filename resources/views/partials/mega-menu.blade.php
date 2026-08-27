<ul class="navbar-nav me-auto mb-2 mb-lg-0 fw-semibold">
    <li class="nav-item">
        <a class="nav-link py-3 text-uppercase" href="{{ route('home') }}">Trang chủ</a>
    </li>

    <!-- Hiển thị danh mục động từ MySQL -->
    @foreach($categories as $category)
        <li class="nav-item">
            <a class="nav-link py-3 text-uppercase" href="#">
                {{ $category->name }}
            </a>
        </li>
    @endforeach
</ul>