@extends('layouts.app')

@section('title', 'Đăng Nhập — OWEN STUDIO')

@section('content')
<div class="container py-5 account-page">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="acc-content-box p-4 p-md-5">
                <div class="text-center mb-4 pb-3 border-bottom" style="border-color: #E5E7EB !important;">
                    <span class="acc-kicker">WELCOME BACK</span>
                    <h1 class="acc-title" style="font-size: 22px;">Đăng nhập tài khoản</h1>
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

                <form action="{{ route('login.post') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label fw-medium" style="font-size: 14px; color: #1F2937;">Địa chỉ Email</label>
                        <input type="email" class="form-control acc-input" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="Nhập địa chỉ email...">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label fw-medium" style="font-size: 14px; color: #1F2937;">Mật khẩu</label>
                        <input type="password" class="form-control acc-input" id="password" name="password" required placeholder="Nhập mật khẩu...">
                    </div>
                    <div class="mb-4 form-check">
                        <input type="checkbox" class="form-check-input rounded-0 shadow-none" id="remember" name="remember" style="accent-color: #111111;">
                        <label class="form-check-label" for="remember" style="font-size: 13.5px; color: #4B5563;">Ghi nhớ đăng nhập</label>
                    </div>
                    <button type="submit" class="btn-acc-primary w-100">ĐĂNG NHẬP</button>
                </form>

                <div class="text-center mt-4 pt-3 border-top" style="border-color: #F3F4F6 !important; font-size: 13.5px; color: #6B7280;">
                    Chưa có tài khoản? <a href="{{ route('register') }}" class="fw-semibold text-dark text-decoration-underline ms-1">Đăng ký ngay</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
