@extends('layouts.app')

@section('title', 'Đăng Ký Tài Khoản — OWEN STUDIO')

@section('content')
<div class="container py-5 account-page">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7">
            <div class="acc-content-box p-4 p-md-5">
                <div class="text-center mb-4 pb-3 border-bottom" style="border-color: #E5E7EB !important;">
                    <span class="acc-kicker">JOIN OWEN STUDIO</span>
                    <h1 class="acc-title" style="font-size: 22px;">Đăng ký tài khoản thành viên</h1>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger rounded-0 mb-4" style="font-size: 13.5px; border-color: #C83E3B; color: #C83E3B; background-color: #FFF5F5;">
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('register.post') }}" method="POST">
                    @csrf
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label fw-medium" style="font-size: 14px; color: #1F2937;">Họ và tên <span style="color: #C83E3B;">*</span></label>
                            <input type="text" class="form-control acc-input" id="name" name="name" value="{{ old('name') }}" required autofocus placeholder="Ví dụ: Nguyễn Văn An">
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label fw-medium" style="font-size: 14px; color: #1F2937;">Địa chỉ Email <span style="color: #C83E3B;">*</span></label>
                            <input type="email" class="form-control acc-input" id="email" name="email" value="{{ old('email') }}" required placeholder="Ví dụ: email@example.com">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="phone" class="form-label fw-medium" style="font-size: 14px; color: #1F2937;">Số điện thoại <span style="color: #C83E3B;">*</span></label>
                            <input type="tel" class="form-control acc-input" id="phone" name="phone" value="{{ old('phone') }}" required placeholder="Ví dụ: 0901234567">
                        </div>
                        <div class="col-md-6">
                            <label for="address" class="form-label fw-medium" style="font-size: 14px; color: #1F2937;">Địa chỉ nhận hàng</label>
                            <input type="text" class="form-control acc-input" id="address" name="address" value="{{ old('address') }}" placeholder="Ví dụ: 123 Lê Lợi, Quận 1, TP.HCM">
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="password" class="form-label fw-medium" style="font-size: 14px; color: #1F2937;">Mật khẩu <span style="color: #C83E3B;">*</span></label>
                            <input type="password" class="form-control acc-input" id="password" name="password" required placeholder="Tối thiểu 6 ký tự">
                        </div>
                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label fw-medium" style="font-size: 14px; color: #1F2937;">Xác nhận mật khẩu <span style="color: #C83E3B;">*</span></label>
                            <input type="password" class="form-control acc-input" id="password_confirmation" name="password_confirmation" required placeholder="Nhập lại mật khẩu">
                        </div>
                    </div>

                    <button type="submit" class="btn-acc-primary w-100">TẠO TÀI KHOẢN</button>
                </form>

                <div class="text-center mt-4 pt-3 border-top" style="border-color: #F3F4F6 !important; font-size: 13.5px; color: #6B7280;">
                    Đã có tài khoản? <a href="{{ route('login') }}" class="fw-semibold text-dark text-decoration-underline ms-1">Đăng nhập tại đây</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
