<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Danh sách danh mục sản phẩm
     */
    public function index(Request $request)
    {
        $query = Category::withCount('products');

        // Tìm kiếm theo tên hoặc slug
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        $categories = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Thêm mới danh mục
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'nullable|string|max:120|unique:categories,slug',
        ], [
            'name.required' => 'Vui lòng nhập tên danh mục.',
            'name.max'      => 'Tên danh mục không quá 100 ký tự.',
            'slug.unique'   => 'Đường dẫn (slug) này đã tồn tại, vui lòng chọn đường dẫn khác.',
        ]);

        $slug = $request->filled('slug') 
            ? Str::slug($request->slug) 
            : Str::slug($request->name);

        // Đảm bảo tính duy nhất nếu slug tự sinh trùng lặp
        $originalSlug = $slug;
        $counter = 1;
        while (Category::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$counter}";
            $counter++;
        }

        Category::create([
            'name' => trim($request->name),
            'slug' => $slug,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Thêm danh mục sản phẩm mới thành công!');
    }

    /**
     * Cập nhật danh mục
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'slug' => [
                'nullable',
                'string',
                'max:120',
                Rule::unique('categories', 'slug')->ignore($category->id),
            ],
        ], [
            'name.required' => 'Vui lòng nhập tên danh mục.',
            'name.max'      => 'Tên danh mục không quá 100 ký tự.',
            'slug.unique'   => 'Đường dẫn (slug) này đã tồn tại, vui lòng chọn đường dẫn khác.',
        ]);

        $slug = $request->filled('slug') 
            ? Str::slug($request->slug) 
            : Str::slug($request->name);

        // Nếu thay đổi slug, đảm bảo duy nhất
        if ($slug !== $category->slug) {
            $originalSlug = $slug;
            $counter = 1;
            while (Category::where('slug', $slug)->where('id', '!=', $category->id)->exists()) {
                $slug = "{$originalSlug}-{$counter}";
                $counter++;
            }
        }

        $category->update([
            'name' => trim($request->name),
            'slug' => $slug,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Cập nhật danh mục thành công!');
    }

    /**
     * Xóa danh mục
     */
    public function destroy(Category $category)
    {
        // 1. Kiểm tra an toàn: Nếu đang có sản phẩm thuộc danh mục này thì chặn
        $productsCount = $category->products()->count();
        if ($productsCount > 0) {
            return redirect()->back()->with('error', "Không thể xóa danh mục '{$category->name}' vì đang có {$productsCount} sản phẩm trực thuộc. Vui lòng chuyển hoặc xóa sản phẩm trước!");
        }

        $name = $category->name;
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', "Đã xóa danh mục '{$name}' thành công!");
    }
}
