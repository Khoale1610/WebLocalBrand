@extends('layouts.app') {{-- Lưu ý: Đổi 'layouts.app' thành tên file layout chính của bạn nếu khác --}}

@section('content')
<div class="container mt-4">
    <div class="row">
        <!-- Cột trái: Bộ Lọc Sản Phẩm -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h4 class="mb-4 fw-bold">Bộ Lọc</h4>
                    <form action="{{ route('category.show', $category->slug) }}" method="GET" id="filter-form">
                        
                        {{-- 1. Lọc theo Khoảng Giá --}}
                        <div class="mb-4">
                            <h6 class="fw-bold">Khoảng Giá</h6>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="price" value="under_300k" id="price1" {{ request('price') == 'under_300k' ? 'checked' : '' }} onchange="this.form.submit()">
                                <label class="form-check-label" for="price1">Dưới 300,000₫</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="price" value="300k_500k" id="price2" {{ request('price') == '300k_500k' ? 'checked' : '' }} onchange="this.form.submit()">
                                <label class="form-check-label" for="price2">300k - 500k</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="price" value="500k_1tr" id="price3" {{ request('price') == '500k_1tr' ? 'checked' : '' }} onchange="this.form.submit()">
                                <label class="form-check-label" for="price3">500k - 1 Triệu</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="price" value="over_1tr" id="price4" {{ request('price') == 'over_1tr' ? 'checked' : '' }} onchange="this.form.submit()">
                                <label class="form-check-label" for="price4">Trên 1 Triệu</label>
                            </div>
                        </div>

                        {{-- 2. Lọc theo Size --}}
                        <div class="mb-4">
                            <h6 class="fw-bold">Kích Thước (Size)</h6>
                            <select name="size" class="form-select shadow-none" onchange="this.form.submit()">
                                <option value="">Tất cả kích thước</option>
                                @foreach($sizes as $size)
                                    <option value="{{ $size->id }}" {{ request('size') == $size->id ? 'selected' : '' }}>
                                        {{ $size->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- 3. Lọc theo Màu Sắc --}}
                        <div class="mb-4">
                            <h6 class="fw-bold">Màu Sắc</h6>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($colors as $color)
                                    <div class="form-check p-0">
                                        <input class="btn-check" type="radio" name="color" value="{{ $color->id }}" id="color_{{ $color->id }}" {{ request('color') == $color->id ? 'checked' : '' }} onchange="this.form.submit()">
                                        <label class="btn btn-outline-dark rounded-circle p-0 d-flex align-items-center justify-content-center" for="color_{{ $color->id }}" style="width: 35px; height: 35px; background-color: {{ $color->hex_code ?? '#fff' }};" title="{{ $color->name }}">
                                            @if(!isset($color->hex_code)) <span style="font-size: 10px;">{{ $color->name }}</span> @endif
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Nút Xóa Lọc & Giữ lại biến Sort --}}
                        <input type="hidden" name="sort" value="{{ request('sort', 'latest') }}">
                        <a href="{{ route('category.show', $category->slug) }}" class="btn btn-outline-danger w-100 fw-bold">Xóa Lọc</a>
                    </form>
                </div>
            </div>
        </div>

        <!-- Cột phải: Khung hiển thị Sản Phẩm -->
        <div class="col-md-9">
            <!-- Tiêu đề và Sắp xếp -->
            <div class="d-flex justify-content-between align-items-center mb-4 bg-white p-3 rounded shadow-sm">
                <h2 class="h4 mb-0 fw-bold text-uppercase">{{ $category->name }}</h2>
                
                <div class="d-flex align-items-center">
                    <span class="me-2 text-muted fw-bold">Sắp xếp:</span>
                    <select class="form-select form-select-sm w-auto shadow-none border-dark" form="filter-form" name="sort" onchange="document.getElementById('filter-form').submit()">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Mới nhất</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá: Thấp tới Cao</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá: Cao tới Thấp</option>
                        <option value="best_selling" {{ request('sort') == 'best_selling' ? 'selected' : '' }}>Bán chạy nhất</option>
                    </select>
                </div>
            </div>

            <!-- Lưới Sản Phẩm -->
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
                @forelse($products as $product)
                    <div class="col">
                        <div class="card h-100 shadow-sm border-0 product-card overflow-hidden">
                            <a href="#"> {{-- Thay bằng route chi tiết sản phẩm --}}
                                <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 250px; object-fit: cover;">
                            </a>
                            <div class="card-body text-center d-flex flex-column">
                                <h6 class="card-title text-truncate fw-bold mb-2">{{ $product->name }}</h6>
                                <p class="card-text text-danger fw-bold fs-5 mt-auto mb-3">{{ number_format($product->price, 0, ',', '.') }}₫</p>
                                <a href="#" class="btn btn-dark w-100 mt-auto">Xem Chi Tiết</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 w-100">
                        <div class="alert alert-warning text-center p-5" role="alert">
                            <h5 class="fw-bold mb-0">Rất tiếc! Không tìm thấy sản phẩm nào phù hợp với bộ lọc.</h5>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Phân trang -->
            <div class="d-flex justify-content-center mt-5">
                {{ $products->links('pagination::bootstrap-5') }} 
            </div>
        </div>
    </div>
</div>

<style>
    /* Thêm chút CSS cho mượt mà */
    .product-card:hover img {
        transform: scale(1.05);
        transition: 0.3s ease-in-out;
    }
    .product-card img {
        transition: 0.3s ease-in-out;
    }
</style>
@endsection
