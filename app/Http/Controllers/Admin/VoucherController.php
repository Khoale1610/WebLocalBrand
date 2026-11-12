<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class VoucherController extends Controller
{
    /**
     * Danh sách mã giảm giá
     */
    public function index(Request $request)
    {
        $query = Voucher::query();

        // 1. Tìm kiếm theo mã hoặc tên voucher
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }

        // 2. Lọc theo loại giảm giá
        if ($type = $request->input('type')) {
            $query->where('type', $type);
        }

        // 3. Lọc theo trạng thái
        if ($status = $request->input('status')) {
            $now = Carbon::now();
            if ($status === 'active') {
                $query->where('is_active', true)
                      ->where(function ($q) use ($now) {
                          $q->whereNull('end_date')->orWhere('end_date', '>=', $now);
                      });
            } elseif ($status === 'expired') {
                $query->where('end_date', '<', $now);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $vouchers = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();

        return view('admin.vouchers.index', compact('vouchers'));
    }

    /**
     * Thêm mới Voucher
     */
    public function store(Request $request)
    {
        $request->validate([
            'code'             => 'required|string|max:50|unique:vouchers,code',
            'name'             => 'required|string|max:255',
            'type'             => 'required|in:percent,fixed',
            'value'            => 'required|numeric|min:1',
            'max_discount'     => 'nullable|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'usage_limit'      => 'nullable|integer|min:1',
            'start_date'       => 'nullable|date',
            'end_date'         => 'nullable|date|after_or_equal:start_date',
            'is_active'        => 'nullable|boolean',
        ], [
            'code.required'      => 'Vui lòng nhập mã giảm giá (VD: SALE20, FREESHIP).',
            'code.unique'        => 'Mã giảm giá này đã tồn tại trong hệ thống.',
            'name.required'      => 'Vui lòng nhập tên chương trình khuyến mãi.',
            'value.required'     => 'Vui lòng nhập mức giảm giá.',
            'end_date.after_or_equal' => 'Ngày hết hạn phải sau hoặc bằng ngày bắt đầu.',
        ]);

        // Nếu là giảm % thì value không được vượt quá 100
        if ($request->type === 'percent' && $request->value > 100) {
            return redirect()->back()->withInput()->with('error', 'Giảm giá theo % không được vượt quá 100%!');
        }

        Voucher::create([
            'code'             => strtoupper(trim($request->code)),
            'name'             => trim($request->name),
            'type'             => $request->type,
            'value'            => $request->value,
            'max_discount'     => $request->filled('max_discount') ? $request->max_discount : null,
            'min_order_amount' => $request->filled('min_order_amount') ? $request->min_order_amount : 0,
            'usage_limit'      => $request->filled('usage_limit') ? $request->usage_limit : null,
            'used_count'       => 0,
            'start_date'       => $request->start_date ? Carbon::parse($request->start_date) : null,
            'end_date'         => $request->end_date ? Carbon::parse($request->end_date) : null,
            'is_active'        => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.vouchers.index')->with('success', 'Tạo mã voucher mới thành công!');
    }

    /**
     * Cập nhật Voucher
     */
    public function update(Request $request, Voucher $voucher)
    {
        $request->validate([
            'code'             => ['required', 'string', 'max:50', Rule::unique('vouchers', 'code')->ignore($voucher->id)],
            'name'             => 'required|string|max:255',
            'type'             => 'required|in:percent,fixed',
            'value'            => 'required|numeric|min:1',
            'max_discount'     => 'nullable|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'usage_limit'      => 'nullable|integer|min:1',
            'start_date'       => 'nullable|date',
            'end_date'         => 'nullable|date|after_or_equal:start_date',
            'is_active'        => 'nullable|boolean',
        ]);

        if ($request->type === 'percent' && $request->value > 100) {
            return redirect()->back()->withInput()->with('error', 'Giảm giá theo % không được vượt quá 100%!');
        }

        $voucher->update([
            'code'             => strtoupper(trim($request->code)),
            'name'             => trim($request->name),
            'type'             => $request->type,
            'value'            => $request->value,
            'max_discount'     => $request->filled('max_discount') ? $request->max_discount : null,
            'min_order_amount' => $request->filled('min_order_amount') ? $request->min_order_amount : 0,
            'usage_limit'      => $request->filled('usage_limit') ? $request->usage_limit : null,
            'start_date'       => $request->start_date ? Carbon::parse($request->start_date) : null,
            'end_date'         => $request->end_date ? Carbon::parse($request->end_date) : null,
            'is_active'        => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.vouchers.index')->with('success', 'Cập nhật voucher thành công!');
    }

    /**
     * Bật / Tắt nhanh trạng thái voucher
     */
    public function toggle(Voucher $voucher)
    {
        $voucher->update(['is_active' => !$voucher->is_active]);
        $statusText = $voucher->is_active ? 'Đã kích hoạt' : 'Đã tạm dừng';
        return redirect()->back()->with('success', "{$statusText} mã voucher {$voucher->code}!");
    }

    /**
     * Xóa Voucher
     */
    public function destroy(Voucher $voucher)
    {
        $code = $voucher->code;
        $voucher->delete();
        return redirect()->route('admin.vouchers.index')->with('success', "Đã xóa voucher '{$code}' thành công!");
    }
}
