<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Size;
use App\Models\Color;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AttributeController extends Controller
{
    /**
     * Giao diện quản lý chung Thuộc tính (Kích thước & Màu sắc)
     */
    public function index()
    {
        $sizes = Size::withCount('variants')->orderBy('sort_order', 'asc')->orderBy('id', 'asc')->get();
        $colors = Color::withCount('variants')->orderBy('id', 'desc')->get();

        return view('admin.attributes.index', compact('sizes', 'colors'));
    }

    // ==========================================
    // 1. QUẢN LÝ KÍCH THƯỚC (SIZE)
    // ==========================================

    public function storeSize(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:20|unique:sizes,name',
            'sort_order' => 'nullable|integer|min:0',
        ], [
            'name.required' => 'Vui lòng nhập tên kích thước (ví dụ: S, M, L, XL, FreeSize).',
            'name.unique'   => 'Kích thước này đã tồn tại trong danh sách.',
        ]);

        Size::create([
            'name'       => strtoupper(trim($request->name)),
            'sort_order' => $request->filled('sort_order') ? (int) $request->sort_order : 0,
        ]);

        return redirect()->route('admin.attributes.index', ['tab' => 'sizes'])
            ->with('success', 'Thêm kích thước mới thành công!');
    }

    public function updateSize(Request $request, Size $size)
    {
        $request->validate([
            'name'       => ['required', 'string', 'max:20', Rule::unique('sizes', 'name')->ignore($size->id)],
            'sort_order' => 'nullable|integer|min:0',
        ], [
            'name.required' => 'Vui lòng nhập tên kích thước.',
            'name.unique'   => 'Kích thước này đã tồn tại.',
        ]);

        $size->update([
            'name'       => strtoupper(trim($request->name)),
            'sort_order' => $request->filled('sort_order') ? (int) $request->sort_order : 0,
        ]);

        return redirect()->route('admin.attributes.index', ['tab' => 'sizes'])
            ->with('success', 'Cập nhật kích thước thành công!');
    }

    public function destroySize(Size $size)
    {
        $variantsCount = $size->variants()->count();
        if ($variantsCount > 0) {
            return redirect()->back()
                ->with('error', "Không thể xóa Size '{$size->name}' vì đang được sử dụng ở {$variantsCount} biến thể sản phẩm!");
        }

        $name = $size->name;
        $size->delete();

        return redirect()->route('admin.attributes.index', ['tab' => 'sizes'])
            ->with('success', "Đã xóa kích thước '{$name}' thành công!");
    }

    // ==========================================
    // 2. QUẢN LÝ MÀU SẮC (COLOR)
    // ==========================================

    public function storeColor(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:50|unique:colors,name',
            'hex_code' => 'nullable|string|max:10',
        ], [
            'name.required' => 'Vui lòng nhập tên màu sắc (ví dụ: Đen, Trắng, Be, Xanh Navy).',
            'name.unique'   => 'Tên màu sắc này đã tồn tại.',
        ]);

        Color::create([
            'name'     => trim($request->name),
            'hex_code' => $request->filled('hex_code') ? strtolower(trim($request->hex_code)) : '#000000',
        ]);

        return redirect()->route('admin.attributes.index', ['tab' => 'colors'])
            ->with('success', 'Thêm màu sắc mới thành công!');
    }

    public function updateColor(Request $request, Color $color)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:50', Rule::unique('colors', 'name')->ignore($color->id)],
            'hex_code' => 'nullable|string|max:10',
        ], [
            'name.required' => 'Vui lòng nhập tên màu sắc.',
            'name.unique'   => 'Tên màu sắc này đã tồn tại.',
        ]);

        $color->update([
            'name'     => trim($request->name),
            'hex_code' => $request->filled('hex_code') ? strtolower(trim($request->hex_code)) : '#000000',
        ]);

        return redirect()->route('admin.attributes.index', ['tab' => 'colors'])
            ->with('success', 'Cập nhật màu sắc thành công!');
    }

    public function destroyColor(Color $color)
    {
        $variantsCount = $color->variants()->count();
        if ($variantsCount > 0) {
            return redirect()->back()
                ->with('error', "Không thể xóa Màu '{$color->name}' vì đang được sử dụng ở {$variantsCount} biến thể sản phẩm!");
        }

        $name = $color->name;
        $color->delete();

        return redirect()->route('admin.attributes.index', ['tab' => 'colors'])
            ->with('success', "Đã xóa màu sắc '{$name}' thành công!");
    }
}
