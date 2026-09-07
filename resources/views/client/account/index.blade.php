@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row">
        <!-- Sidebar tài khoản -->
        <div class="col-md-3 mb-4">
            <div class="list-group shadow-sm">
                <a href="{{ route('account.index') }}" class="list-group-item list-group-item-action active">Tổng quan tài khoản</a>
                <a href="{{ route('account.orders') }}" class="list-group-item list-group-item-action">Lịch sử đơn hàng</a>
                <a href="{{ route('account.wishlist.index') }}" class="list-group-item list-group-item-action">Sản phẩm yêu thích</a>
                <a href="{{ route('account.addresses') }}" class="list-group-item list-group-item-action">Sổ địa chỉ</a>
            </div>
        </div>

        <!-- Nội dung chính -->
        <div class="col-md-9">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h3 class="mb-4">Thông Tin Tài Khoản</h3>
                    
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Họ và tên:</div>
                        <div class="col-md-8">{{ $user->name }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Email:</div>
                        <div class="col-md-8">{{ $user->email }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Số điện thoại:</div>
                        <div class="col-md-8">{{ $user->phone ?? 'Chưa cập nhật' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Địa chỉ:</div>
                        <div class="col-md-8">{{ $user->address ?? 'Chưa cập nhật' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection