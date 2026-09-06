@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card shadow-sm border-0">
                <div class="card-body p-5">
                    <h2 class="text-center mb-4 font-weight-bold">Đăng Ký Tài Khoản</h2>

                    <!-- Hiển thị thông báo lỗi (nếu trùng email, pass quá ngắn...) -->
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('register.post') }}" method="POST">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Họ và Tên <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required autofocus placeholder="Vd: Nguyễn Văn A">
                            </div>
                            <div class="col-md-6 mt-3 mt-md-0">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required placeholder="Vd: email@example.com">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Số điện thoại</label>
                                <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}" placeholder="Vd: 0901234567">
                            </div>
                            <div class="col-md-6 mt-3 mt-md-0">
                                <label for="address" class="form-label">Địa chỉ nhận hàng</label>
                                <input type="text" class="form-control" id="address" name="address" value="{{ old('address') }}" placeholder="Vd: 123 Lê Lợi, Quận 1, TP.HCM">
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="password" class="form-label">Mật khẩu <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="password" name="password" required placeholder="Tối thiểu 6 ký tự">
                            </div>
                            <div class="col-md-6 mt-3 mt-md-0">
                                <label for="password_confirmation" class="form-label">Xác nhận mật khẩu <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required placeholder="Nhập lại mật khẩu">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-dark w-100 py-2">Tạo Tài Khoản</button>
                    </form>
                    
                    <div class="text-center mt-4">
                        <p class="mb-0">Đã có tài khoản? <a href="{{ route('login') }}" class="text-decoration-none text-primary fw-bold">Đăng nhập tại đây</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection