<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Wishlist;
use App\Models\Product;

class WishlistController extends Controller
{
    /**
     * Hiển thị danh sách sản phẩm yêu thích của người dùng
     */
    public function index()
    {
        // Ghi chú: Lấy trực tiếp các bản ghi trong bảng wishlists kèm theo thông tin sản phẩm để an toàn tuyệt đối khi phân trang
        $user = Auth::user();
        
        // Lấy danh sách wishlist có phân trang và kèm theo model product
        $wishlists = Wishlist::with('product')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('client.account.wishlist', compact('wishlists'));
    }

    /**
     * Thêm sản phẩm vào danh sách yêu thích
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $userId = Auth::id();
        $productId = $request->product_id;

        // Kiểm tra xem đã thích sản phẩm này trước đó chưa để tránh trùng lặp
        $exists = Wishlist::where('user_id', $userId)->where('product_id', $productId)->exists();

        if (!$exists) {
            Wishlist::create([
                'user_id' => $userId,
                'product_id' => $productId
            ]);
            return back()->with('success', 'Đã thêm sản phẩm vào danh sách yêu thích!');
        }

        return back()->with('info', 'Sản phẩm này đã có sẵn trong danh sách yêu thích của bạn.');
    }

    /**
     * Xóa sản phẩm khỏi danh sách yêu thích
     */
    public function remove($id)
    {
        // $id ở đây là product_id truyền từ nút xóa
        $wishlist = Wishlist::where('user_id', Auth::id())->where('product_id', $id)->first();

        if ($wishlist) {
            $wishlist->delete();
            return back()->with('success', 'Đã xóa sản phẩm khỏi danh sách yêu thích.');
        }

        return back()->with('error', 'Không tìm thấy sản phẩm trong danh sách yêu thích.');
    }
}