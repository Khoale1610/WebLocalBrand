@extends('layouts.app')

@section('title', 'Tìm Kiếm: ' . ($keyword ?? 'Tất cả') . ' - Thời Trang Local Brand')

@section('content')
<div class="container py-4 py-lg-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none text-muted">Trang chủ</a></li>
            <li class="breadcrumb-item active fw-semibold text-dark" aria-current="page">Kết quả tìm kiếm</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <div>
            <h4 class="fw-bold mb-1">Kết quả tìm kiếm cho: "<span class="text-danger">{{ $keyword }}</span>"</h4>
            <p class="text-muted small mb-0">Tìm thấy <strong>{{ $products->total() }}</strong> sản phẩm phù hợp</p>
        </div>
    </div>

    @if($products->isEmpty())
        <div class="text-center py-5 my-4 bg-light rounded-4 border">
            <i class="fas fa-search display-3 text-muted mb-3"></i>
            <h5 class="fw-bold text-dark">Không tìm thấy sản phẩm nào!</h5>
            <p class="text-muted">Vui lòng thử lại với từ khóa khác như "Áo sơ mi", "Polo", "Quần tây"...</p>
            <a href="{{ url('/') }}" class="btn btn-dark mt-2">Về Trang Chủ</a>
        </div>
    @else
        <div class="row g-4">
            @foreach($products as $prod)
                <div class="col-6 col-md-3">
                    <div class="card h-100 border-0 shadow-sm">
                        <a href="{{ route('products.show', $prod->id) }}">
                            <img src="{{ $prod->image ?? 'https://via.placeholder.com/300x400' }}" 
                                 class="card-img-top" 
                                 alt="{{ $prod->name }}" 
                                 style="height: 280px; object-fit: cover;">
                        </a>
                        <div class="card-body text-center d-flex flex-column p-3">
                            <h6 class="card-title mb-2">
                                <a href="{{ route('products.show', $prod->id) }}" class="text-dark text-decoration-none fw-semibold">
                                    {{ $prod->name }}
                                </a>
                            </h6>
                            <p class="text-danger fw-bold fs-5 mb-3">{{ number_format($prod->price, 0, ',', '.') }} ₫</p>
                            <a href="{{ route('products.show', $prod->id) }}" class="btn btn-outline-dark btn-sm mt-auto">
                                Xem Chi Tiết
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Phân trang -->
        <div class="d-flex justify-content-center mt-5">
            {{ $products->appends(['query' => $keyword])->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
