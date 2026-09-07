<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AccountController extends Controller
{
    /**
     * Hiển thị form đăng nhập
     */
    public function showLoginForm()
    {
        return view('client.account.login');
    }

    /**
     * Xử lý đăng nhập
     */
    public function login(Request $request)
    {
        // Kiểm tra dữ liệu đầu vào
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ], [
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không đúng định dạng.',
            'password.required' => 'Vui lòng nhập mật khẩu.'
        ]);

        // Thực hiện đăng nhập, hỗ trợ ghi nhớ tài khoản (remember me)
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            // Nếu người dùng là admin thì chuyển hướng vào dashboard, ngược lại về trang tài khoản
            if (Auth::user()->isAdmin()) {
                // Tạm thời redirect về trang chủ nếu chưa có route admin
                return redirect()->intended(route('home'))->with('success', 'Đăng nhập quản trị viên thành công!');
            }
            
            return redirect()->intended(route('account.index'))->with('success', 'Đăng nhập thành công!');
        }

        // Trả về lỗi nếu sai thông tin
        return back()->withErrors([
            'email' => 'Email hoặc mật khẩu không chính xác.',
        ])->onlyInput('email');
    }

    /**
     * Hiển thị form đăng ký
     */
    public function showRegisterForm()
    {
        return view('client.account.register');
    }

    /**
     * Xử lý đăng ký tài khoản mới
     */
    public function register(Request $request)
    {
        // Xác thực dữ liệu
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed', // Cần trường password_confirmation ở form
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ], [
            'name.required' => 'Vui lòng nhập họ tên.',
            'email.required' => 'Vui lòng nhập email.',
            'email.unique' => 'Email này đã được sử dụng.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.'
        ]);

        // Tạo tài khoản
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
            'role' => 'user', // Mặc định tài khoản đăng ký mới là khách hàng thông thường
        ]);

        // Tự động đăng nhập sau khi đăng ký thành công
        Auth::login($user);

        return redirect()->route('account.index')->with('success', 'Đăng ký tài khoản thành công!');
    }

    /**
     * Xử lý đăng xuất
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('home')->with('success', 'Bạn đã đăng xuất thành công.');
    }

    /**
     * Trang tổng quan tài khoản
     */
    public function index()
    {
        $user = Auth::user();
        return view('client.account.index', compact('user'));
    }

    /**
     * Lịch sử mua hàng
     */
    public function orders()
    {
        // Lấy danh sách đơn hàng của user đang đăng nhập, kèm chi tiết sản phẩm
        $orders = Auth::user()->orders()->with('orderItems.product')->orderBy('created_at', 'desc')->paginate(10);
        return view('client.account.orders', compact('orders'));
    }

    /**
     * Sổ địa chỉ
     */
    public function addresses()
    {
        $user = Auth::user();
        return view('client.account.addresses', compact('user'));
    }
}