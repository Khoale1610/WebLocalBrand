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
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ], [
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không đúng định dạng.',
            'password.required' => 'Vui lòng nhập mật khẩu.'
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            // Nếu là admin, chuyển hướng vào dashboard quản trị
            if (Auth::user()->isAdmin()) {
                // Tạm thời redirect về trang chủ hoặc route admin.dashboard (nếu đã tạo)
                return redirect()->intended('/')->with('success', 'Đăng nhập quản trị viên thành công!');
            }
            
            return redirect()->intended(route('account.index'))->with('success', 'Đăng nhập thành công!');
        }

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
     * Xử lý đăng ký tài khoản mới (Với chuẩn hóa dữ liệu siêu nghiêm ngặt)
     */
    public function register(Request $request)
    {
        // Xác thực dữ liệu đầu vào chặt chẽ
        $request->validate([
            'name' => 'required|string|min:2|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            // Kiểm tra SĐT: Bắt buộc, đúng 10 số, đầu số VN (03, 05, 07, 08, 09)
            'phone' => ['required', 'regex:/^(03|05|07|08|09)[0-9]{8}$/'],
            'address' => 'nullable|string|min:5|max:255',
        ], [
            'name.required' => 'Vui lòng nhập họ tên.',
            'name.min' => 'Họ tên phải có ít nhất 2 ký tự.',
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không đúng định dạng hợp lệ.',
            'email.unique' => 'Email này đã được đăng ký trên hệ thống.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
            'phone.required' => 'Vui lòng nhập số điện thoại.',
            'phone.regex' => 'Số điện thoại không hợp lệ (Đúng 10 chữ số).',
            'address.min' => 'Địa chỉ quá ngắn, vui lòng nhập rõ ràng hơn.',
        ]);

        // Tạo tài khoản
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
            'role' => 'user', 
        ]);

        // Tự động đăng nhập
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
