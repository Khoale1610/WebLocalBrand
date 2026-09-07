@extends('layouts.app')

@section('content')
<div class="container my-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none text-muted">Trang chủ</a></li>
            <li class="breadcrumb-item active fw-bold" aria-current="page">{{ $category->name }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- BỘ LỌC TÌM KIẾM (SIDEBAR) -->
        <div class="col-lg-3 col-md-4 mb-4">
            <div class="filter-sidebar bg-light p-4 rounded shadow-sm">
                <h4 class="mb-4">Bộ Lọc Sản Phẩm</h4>
                
                <form action="{{ route('category.show', $category->id) }}" method="GET" id="filter-form">
                    <!-- Input ẩn để đồng bộ thanh sắp xếp bên ngoài vào form lọc này -->
                    <input type="hidden" name="sort" id="hidden-sort-input" value="{{ request('sort', 'newest') }}">
                    
                    <!-- 1. Lọc theo Khoảng giá -->
                    <div class="filter-group mb-4">
                        <h5 class="font-weight-bold mb-3">Mức Giá</h5>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="price" value="under_300" id="price1" {{ request('price') == 'under_300' ? 'checked' : '' }}>
                            <label class="form-check-label" for="price1">Dưới 300.000đ</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="price" value="300_500" id="price2" {{ request('price') == '300_500' ? 'checked' : '' }}>
                            <label class="form-check-label" for="price2">300.000đ - 500.000đ</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="price" value="500_1000" id="price3" {{ request('price') == '500_1000' ? 'checked' : '' }}>
                            <label class="form-check-label" for="price3">500.000đ - 1.000.000đ</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="price" value="over_1000" id="price4" {{ request('price') == 'over_1000' ? 'checked' : '' }}>
                            <label class="form-check-label" for="price4">Trên 1.000.000đ</label>
                        </div>
                    </div>

                    <!-- 2. Lọc theo Size -->
                    <div class="filter-group mb-4">
                        <h5 class="font-weight-bold mb-3">Kích Cỡ (Size)</h5>
                        @foreach($sizes as $size)
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="size[]" value="{{ $size->id }}" id="size{{ $size->id }}" 
                                {{ in_array($size->id, request('size', [])) ? 'checked' : '' }}>
                            <label class="form-check-label" for="size{{ $size->id }}">{{ $size->name }}</label>
                        </div>
                        @endforeach
                    </div>

                    <!-- 3. Lọc theo Màu Sắc -->
                    <div class="filter-group mb-4">
                        <h5 class="font-weight-bold mb-3">Màu Sắc</h5>
                        @foreach($colors as $color)
                        <div class="form-check mb-2 d-flex align-items-center">
                            <input class="form-check-input me-2" type="checkbox" name="color[]" value="{{ $color->id }}" id="color{{ $color->id }}"
                                {{ in_array($color->id, request('color', [])) ? 'checked' : '' }}>
                            <label class="form-check-label d-flex align-items-center" for="color{{ $color->id }}">
                                <span style="display:inline-block; width:16px; height:16px; background-color: {{ $color->hex_code ?? '#000' }}; border:1px solid #ccc; border-radius:50%; margin-right:8px;"></span>
                                {{ $color->name }}
                            </label>
                        </div>
                        @endforeach
                    </div>

                    <button type="submit" class="btn btn-dark w-100 mb-2">Áp Dụng Lọc</button>
                    <a href="{{ route('category.show', $category->id) }}" class="btn btn-outline-secondary w-100">Xóa Lọc</a>
                </form>
            </div>
        </div>

        <!-- HIỂN THỊ DANH SÁCH SẢN PHẨM -->
        <div class="col-lg-9 col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-4 bg-light p-3 rounded">
                <h2 class="h4 mb-0">{{ $category->name }}</h2>
                
                <!-- 4. Sắp xếp sản phẩm -->
                <div class="d-flex align-items-center">
                    <label class="me-2 text-nowrap mb-0 fw-bold">Sắp xếp:</label>
                    <select class="form-select form-select-sm" 
                            onchange="document.getElementById('hidden-sort-input').value = this.value; document.getElementById('filter-form').submit();">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá: Thấp đến Cao</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá: Cao xuống Thấp</option>
                        <option value="best_seller" {{ request('sort') == 'best_seller' ? 'selected' : '' }}>Bán chạy nhất</option>
                    </select>
                </div>
            </div>

            <!-- Lưới Sản Phẩm -->
            <div class="row">
                @forelse($products as $product)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 product-card shadow-sm border-0">
                        <a href="{{ route('products.show', $product->id) }}">
                            <img src="{{ Str::startsWith($product->image, ['http://', 'https://']) ? $product->image : asset('storage/' . $product->image) }}" 
                                 class="card-img-top" 
                                 alt="{{ $product->name }}" 
                                 style="height: 280px; object-fit: cover;"
                                 onerror="this.onerror=null; this.src='https://via.placeholder.com/300x400?text=No+Image';">
                        </a>
                        <div class="card-body text-center d-flex flex-column justify-content-between">
                            <h5 class="card-title h6">
                                <a href="{{ route('products.show', $product->id) }}" class="text-decoration-none text-dark">{{ $product->name }}</a>
                            </h5>
                            <p class="card-text text-danger fw-bold mb-0">{{ number_format($product->price, 0, ',', '.') }} VNĐ</p>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="alert alert-warning text-center p-5">
                        <i class="fas fa-search fa-3x mb-3 text-muted"></i>
                        <h5>Không tìm thấy sản phẩm nào!</h5>
                        <p class="mb-0">Vui lòng thử thay đổi điều kiện lọc (Mức giá, Size, Màu sắc) để tìm được sản phẩm ưng ý.</p>
                    </div>
                </div>
                @endforelse
            </div>

            <!-- Phân trang -->
            <div class="d-flex justify-content-center mt-4">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
