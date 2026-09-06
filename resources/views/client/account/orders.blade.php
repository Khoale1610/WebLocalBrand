@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="list-group shadow-sm">
                <a href="{{ route('account.index') }}" class="list-group-item list-group-item-action">Tổng quan tài khoản</a>
                <a href="{{ route('account.orders') }}" class="list-group-item list-group-item-action active">Lịch sử đơn hàng</a>
                <a href="{{ route('account.wishlist.index') }}" class="list-group-item list-group-item-action">Sản phẩm yêu thích</a>
                <a href="{{ route('account.addresses') }}" class="list-group-item list-group-item-action">Sổ địa chỉ</a>
            </div>
        </div>

        <div class="col-md-9">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h3 class="mb-4">Lịch Sử Mua Hàng</h3>
                    
                    @forelse($orders as $order)
                        <div class="border rounded p-3 mb-3">
                            <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                                <span>Mã đơn: <strong>#{{ $order->id }}</strong></span>
                                <span class="badge bg-secondary">{{ $order->status ?? 'Đang xử lý' }}</span>
                            </div>
                            <p class="mb-1 text-muted small">Ngày đặt: {{ $order->created_at->format('d/m/Y H:i') }}</p>
                            <p class="mb-0 fw-bold text-danger">Tổng tiền: {{ number_format($order->total_price ?? 0, 0, ',', '.') }} VNĐ</p>
                        </div>
                    @empty
                        <div class="alert alert-info">Bạn chưa có đơn hàng nào.</div>
                    @endforelse

                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection