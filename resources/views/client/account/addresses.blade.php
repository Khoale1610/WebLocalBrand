@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row">
        <!-- Sidebar tài khoản -->
        <div class="col-md-3 mb-4">
            <div class="list-group shadow-sm">
                <a href="{{ route('account.index') }}" class="list-group-item list-group-item-action">Tổng quan tài khoản</a>
                <a href="{{ route('account.orders') }}" class="list-group-item list-group-item-action">Lịch sử đơn hàng</a>
                <a href="{{ route('account.wishlist.index') }}" class="list-group-item list-group-item-action">Sản phẩm yêu thích</a>
                <a href="{{ route('account.addresses') }}" class="list-group-item list-group-item-action active">Sổ địa chỉ</a>
            </div>
        </div>

        <!-- Nội dung sổ địa chỉ -->
        <div class="col-md-9">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h3 class="mb-4">Sổ Địa Chỉ Nhận Hàng</h3>
                    <div class="border p-3 rounded bg-light">
                        <p class="mb-1"><strong>Họ tên:</strong> {{ $user->name }}</p>
                        <p class="mb-1"><strong>Số điện thoại:</strong> {{ $user->phone ?? 'Chưa cập nhật' }}</p>
                        <p class="mb-0"><strong>Địa chỉ:</strong> {{ $user->address ?? 'Chưa có địa chỉ nào được lưu.' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection