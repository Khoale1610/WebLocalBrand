@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row">
        <!-- Sidebar tài khoản -->
        <div class="col-md-3 mb-4">
            <div class="list-group shadow-sm">
                <a href="{{ route('account.index') }}" class="list-group-item list-group-item-action">Tổng quan tài khoản</a>
                <a href="{{ route('account.orders') }}" class="list-group-item list-group-item-action">Lịch sử đơn hàng</a>
                <a href="{{ route('account.wishlist.index') }}" class="list-group-item list-group-item-action active">Sản phẩm yêu thích</a>
                <a href="{{ route('account.addresses') }}" class="list-group-item list-group-item-action">Sổ địa chỉ</a>
            </div>
        </div>

        <!-- Nội dung danh sách yêu thích -->
        <div class="col-md-9">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h3 class="mb-4">Sản Phẩm Yêu Thích</h3>
                    
                    <!-- Ghi chú: Hiển thị thông báo trạng thái -->
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('info'))
                        <div class="alert alert-info">{{ session('info') }}</div>
                    @endif

                    <div class="row">
                        @forelse($wishlists as $item)
                            @if($item->product)
                                <div class="col-md-4 mb-4">
                                    <div class="card h-100 shadow-sm border-0">
                                        <!-- Ghi chú: Xử lý hiển thị hình ảnh an toàn, tự động nhận diện link ngoài hoặc link storage, có ảnh dự phòng khi lỗi -->
                                        <a href="{{ route('products.show', $item->product->id) }}">
                                            <img src="{{ Str::startsWith($item->product->image, ['http://', 'https://']) ? $item->product->image : asset('storage/' . $item->product->image) }}" 
                                                 class="card-img-top" 
                                                 alt="{{ $item->product->name }}" 
                                                 style="height: 200px; object-fit: cover;"
                                                 onerror="this.onerror=null; this.src='https://via.placeholder.com/300x200?text=No+Image';">
                                        </a>
                                        
                                        <div class="card-body text-center d-flex flex-column justify-content-between">
                                            <h5 class="card-title h6">
                                                <a href="{{ route('products.show', $item->product->id) }}" class="text-decoration-none text-dark">{{ $item->product->name }}</a>
                                            </h5>
                                            <p class="card-text text-danger fw-bold mb-2">{{ number_format($item->product->price, 0, ',', '.') }} VNĐ</p>
                                            
                                            <!-- Form xóa khỏi wishlist -->
                                            <form action="{{ route('account.wishlist.remove', $item->product->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm w-100"><i class="fas fa-trash-alt me-1"></i> Xóa khỏi yêu thích</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <!-- Ghi chú: Hiển thị khi danh sách trống -->
                            <div class="col-12">
                                <div class="alert alert-warning text-center">Bạn chưa có sản phẩm yêu thích nào.</div>
                            </div>
                        @endforelse
                    </div>

                    <!-- Ghi chú: Phân trang -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $wishlists->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection