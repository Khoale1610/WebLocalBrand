@extends('layouts.app')

@section('title', 'Trang Chủ - Thời Trang Nam Owen')

@section('content')
<!-- Banner Slider Section -->
<div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <div class="bg-secondary text-white text-center py-5" style="height: 400px; background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('https://via.placeholder.com/1200x400') center/cover;">
                <div class="d-flex h-100 align-items-center justify-content-center flex-column">
                    <h1 class="display-4 fw-bold">NEW ARRIVALS 2026</h1>
                    <p class="lead">Bộ Sưu Tập Áo Sơ Mi Kháng Khuẩn Cao Cấp</p>
                    <a href="#" class="btn btn-danger btn-lg mt-2">MUA NGAY</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Featured Products Section -->
<div class="container my-5">
    <h3 class="text-center text-uppercase fw-bold mb-4">Sản Phẩm Nổi Bật</h3>
    
    <div class="row g-4">
        <!-- Card Sản Phẩm 1 -->
        <div class="col-md-3">
            <div class="card h-100 border-0 shadow-sm">
                <img src="https://lados.vn/wp-content/uploads/2024/11/3-den-ld8160-jpg.webp" class="card-img-top" alt="Áo Sơ Mi">
                <div class="card-body text-center">
                    <span class="badge bg-light text-dark border mb-2">Áo Sơ Mi</span>
                    <h6 class="card-title"><a href="#" class="text-dark text-decoration-none">Áo Sơ Mi Nam Dài Tay Bamboo</a></h6>
                    <p class="text-danger fw-bold">499.000 ₫</p>
                    <a href="#" class="btn btn-outline-dark btn-sm w-100">Xem Chi Tiết</a>
                </div>
            </div>
        </div>
        
        <!-- Card Sản Phẩm 2 -->
        <div class="col-md-3">
            <div class="card h-100 border-0 shadow-sm">
                <img src="https://content.pancake.vn/web-media-262/s1080x1080/fwebp90/57/48/52/82/ca33663e431dc0f75318a612dac54a142c55e04e789481b8196ec657-w:1080-h:1080-l:505576-t:image/jpeg.jpeg" class="card-img-top" alt="Áo Polo">
                <div class="card-body text-center">
                    <span class="badge bg-light text-dark border mb-2">Áo Polo</span>
                    <h6 class="card-title"><a href="#" class="text-dark text-decoration-none">Áo Polo Nam Bo Cổ Phối Màu</a></h6>
                    <p class="text-danger fw-bold">350.000 ₫</p>
                    <a href="#" class="btn btn-outline-dark btn-sm w-100">Xem Chi Tiết</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection