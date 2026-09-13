@extends('layouts.app')

@section('title', 'Tìm Kiếm: ' . ($keyword ?: 'Tất cả sản phẩm') . ' - WebLocalBrand')

@section('content')
<div class="container py-4 py-lg-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none text-muted">Trang chủ</a></li>
            <li class="breadcrumb-item active fw-semibold text-dark" aria-current="page">Tìm kiếm</li>
        </ol>
    </nav>

    <!-- Tiêu đề & Thông số kết quả -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 pb-3 border-bottom gap-3">
        <div>
            <h4 class="fw-bold mb-1">
                @if(!empty($keyword))
                    Kết quả tìm kiếm cho: "<span class="text-danger">{{ $keyword }}</span>"
                @else
                    Tất cả sản phẩm
                @endif
            </h4>
            <p class="text-muted small mb-0">Tìm thấy <strong>{{ $products->total() }}</strong> sản phẩm phù hợp</p>
        </div>

        <!-- Bộ lọc và sắp xếp -->
        <form action="{{ route('products.search') }}" method="GET" class="d-flex flex-wrap align-items-center gap-2" id="searchFilterForm">
            <input type="hidden" name="query" value="{{ $keyword }}">

            <!-- Lọc Danh mục -->
            <select name="category_id" class="form-select form-select-sm" style="width: auto;" onchange="this.form.submit()">
                <option value="">Tất cả danh mục</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }} ({{ $cat->products_count }})
                    </option>
                @endforeach
            </select>

            <!-- Sắp xếp theo giá / mới nhất -->
            <select name="sort" class="form-select form-select-sm" style="width: auto;" onchange="this.form.submit()">
                <option value="newest" {{ $sort == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                <option value="price_asc" {{ $sort == 'price_asc' ? 'selected' : '' }}>Giá: Thấp đến Cao</option>
                <option value="price_desc" {{ $sort == 'price_desc' ? 'selected' : '' }}>Giá: Cao đến Thấp</option>
                <option value="name_asc" {{ $sort == 'name_asc' ? 'selected' : '' }}>Tên: A - Z</option>
            </select>
        </form>
    </div>

    @if($products->isEmpty())
        <div class="text-center py-5 my-4 bg-light rounded-4 border">
            <i class="fas fa-search display-3 text-muted mb-3"></i>
            <h5 class="fw-bold text-dark">Không tìm thấy sản phẩm phù hợp!</h5>
            <p class="text-muted mb-4">Vui lòng thử lại với từ khóa khác như "Áo sơ mi", "Polo", "Quần tây" hoặc xóa bớt bộ lọc.</p>
            <a href="{{ route('products.search') }}" class="btn btn-outline-dark me-2">Xem Tất Cả Sản Phẩm</a>
            <a href="{{ url('/') }}" class="btn btn-dark">Về Trang Chủ</a>
        </div>
    @else
        <div class="row g-4">
            @foreach($products as $prod)
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card h-100 border-0 shadow-sm product-card transition-hover rounded-3 overflow-hidden">
                        <div class="position-relative">
                            <a href="{{ route('products.show', $prod->id) }}">
                                <img src="{{ $prod->image ?? 'https://via.placeholder.com/300x400' }}" 
                                     class="card-img-top" 
                                     alt="{{ $prod->name }}" 
                                     style="height: 280px; object-fit: cover;">
                            </a>
                            @if($prod->is_featured)
                                <span class="position-absolute top-0 start-0 m-2 badge bg-danger text-uppercase" style="font-size: 0.7rem;">Hot</span>
                            @endif
                        </div>
                        <div class="card-body text-center d-flex flex-column p-3">
                            <small class="text-muted text-uppercase mb-1" style="font-size: 0.75rem;">{{ $prod->category->name ?? 'Local Brand' }}</small>
                            <h6 class="card-title mb-2">
                                <a href="{{ route('products.show', $prod->id) }}" class="text-dark text-decoration-none fw-semibold line-clamp-2" title="{{ $prod->name }}">
                                    {{ $prod->name }}
                                </a>
                            </h6>
                            <p class="text-danger fw-bold fs-5 mb-3">{{ number_format($prod->price, 0, ',', '.') }} ₫</p>
                            <a href="{{ route('products.show', $prod->id) }}" class="btn btn-outline-dark btn-sm mt-auto w-100 fw-semibold">
                                <i class="fas fa-eye me-1"></i> Xem Chi Tiết
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Phân trang chuẩn tương thích Laravel 8 & Bootstrap 4/5 -->
        <div class="d-flex justify-content-center mt-5">
            {{ $products->links('pagination::bootstrap-4') }}
        </div>
    @endif
</div>

<style>
    .transition-hover {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .transition-hover:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.08) !important;
    }
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 2.5rem;
    }
</style>
@endsection
