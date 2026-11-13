<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Category;
use App\Models\Size;
use App\Models\Color;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    /**
     * Danh sách sản phẩm
     */
    public function index(Request $request)
    {
        $query = Product::with(['category', 'variants.size', 'variants.color']);

        // 1. Tìm kiếm theo tên, slug, hoặc mã SKU của biến thể
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhereHas('variants', function ($v) use ($search) {
                      $v->where('sku', 'like', "%{$search}%");
                  });
            });
        }

        // 2. Lọc theo danh mục
        if ($catId = $request->input('category_id')) {
            $query->where('category_id', $catId);
        }

        // 3. Lọc theo trạng thái nổi bật
        if ($request->filled('is_featured')) {
            $query->where('is_featured', (bool) $request->is_featured);
        }

        // 4. Lọc theo tình trạng tồn kho
        if ($stockStatus = $request->input('stock_status')) {
            if ($stockStatus === 'out_of_stock') {
                $query->where('stock', '<=', 0);
            } elseif ($stockStatus === 'low_stock') {
                $query->where('stock', '>', 0)->where('stock', '<=', 5);
            } elseif ($stockStatus === 'in_stock') {
                $query->where('stock', '>', 5);
            }
        }

        $products = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    /**
     * Form thêm mới sản phẩm
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $sizes = Size::orderBy('sort_order', 'asc')->orderBy('id', 'asc')->get();
        $colors = Color::orderBy('name')->get();

        return view('admin.products.create', compact('categories', 'sizes', 'colors'));
    }

    /**
     * Lưu sản phẩm mới kèm Ma trận Biến thể
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price'       => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image_file'  => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'image_url'   => 'nullable|url',
            'is_featured' => 'nullable|boolean',
            'variants'    => 'nullable|array',
            'variants.*.size_id'  => 'required_with:variants|exists:sizes,id',
            'variants.*.color_id' => 'required_with:variants|exists:colors,id',
            'variants.*.sku'      => 'required_with:variants|string|max:100',
            'variants.*.price'    => 'nullable|numeric|min:0',
            'variants.*.stock'    => 'required_with:variants|integer|min:0',
        ], [
            'name.required'        => 'Vui lòng nhập tên sản phẩm.',
            'category_id.required' => 'Vui lòng chọn danh mục.',
            'price.required'       => 'Vui lòng nhập giá gốc của sản phẩm.',
            'image_file.image'     => 'Tệp tải lên phải là hình ảnh (jpg, png, webp, gif).',
            'image_file.max'       => 'Ảnh tải lên không được vượt quá 5MB.',
        ]);

        DB::beginTransaction();
        try {
            // 1. Xử lý ảnh đại diện (Ưu tiên File Upload -> URL)
            $imagePath = null;
            if ($request->hasFile('image_file')) {
                $file = $request->file('image_file');
                $filename = 'prod_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/products'), $filename);
                $imagePath = 'uploads/products/' . $filename;
            } elseif ($request->filled('image_url')) {
                $imagePath = trim($request->image_url);
            }

            // 2. Tạo slug duy nhất
            $slug = Str::slug($request->name);
            $originalSlug = $slug;
            $counter = 1;
            while (Product::where('slug', $slug)->exists()) {
                $slug = "{$originalSlug}-{$counter}";
                $counter++;
            }

            // 3. Tạo bản ghi sản phẩm
            $product = Product::create([
                'category_id' => $request->category_id,
                'name'        => trim($request->name),
                'slug'        => $slug,
                'price'       => $request->price,
                'description' => $request->description,
                'image'       => $imagePath,
                'stock'       => 0, // Sẽ tính tổng từ biến thể hoặc nhập
                'is_featured' => $request->boolean('is_featured'),
            ]);

            // 4. Tạo biến thể nếu có
            $totalStock = 0;
            if ($request->has('variants') && is_array($request->variants)) {
                $usedCombinations = [];
                foreach ($request->variants as $varData) {
                    $comboKey = $varData['size_id'] . '_' . $varData['color_id'];
                    if (in_array($comboKey, $usedCombinations)) {
                        continue; // Tránh trùng cặp size-màu
                    }
                    $usedCombinations[] = $comboKey;

                    // Đảm bảo SKU duy nhất
                    $sku = trim($varData['sku']);
                    if (ProductVariant::where('sku', $sku)->exists()) {
                        $sku = $sku . '-' . Str::upper(Str::random(3));
                    }

                    $variantStock = (int) ($varData['stock'] ?? 0);
                    $totalStock += $variantStock;

                    ProductVariant::create([
                        'product_id' => $product->id,
                        'size_id'    => $varData['size_id'],
                        'color_id'   => $varData['color_id'],
                        'sku'        => $sku,
                        'price'      => !empty($varData['price']) ? $varData['price'] : null,
                        'stock'      => $variantStock,
                    ]);
                }
            } else {
                $totalStock = (int) $request->input('stock', 0);
            }

            // Cập nhật tổng tồn kho cho sản phẩm
            $product->update(['stock' => $totalStock]);

            DB::commit();

            return redirect()->route('admin.products.index')
                ->with('success', "Thêm sản phẩm '{$product->name}' thành công với {$product->variants()->count()} biến thể!");

        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Form chỉnh sửa sản phẩm & quản lý biến thể
     */
    public function edit(Product $product)
    {
        $product->load(['variants.size', 'variants.color']);
        $categories = Category::orderBy('name')->get();
        $sizes = Size::orderBy('sort_order', 'asc')->orderBy('id', 'asc')->get();
        $colors = Color::orderBy('name')->get();

        return view('admin.products.edit', compact('product', 'categories', 'sizes', 'colors'));
    }

    /**
     * Cập nhật thông tin sản phẩm và danh sách biến thể
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price'       => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image_file'  => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'image_url'   => 'nullable|url',
            'is_featured' => 'nullable|boolean',
            'existing_variants' => 'nullable|array',
            'existing_variants.*.sku'   => 'required|string|max:100',
            'existing_variants.*.price' => 'nullable|numeric|min:0',
            'existing_variants.*.stock' => 'required|integer|min:0',
            'existing_variants.*.image' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // 1. Cập nhật ảnh đại diện nếu có
            if ($request->hasFile('image_file')) {
                // Xóa ảnh cũ nếu là file cục bộ
                if ($product->image && File::exists(public_path($product->image))) {
                    File::delete(public_path($product->image));
                }
                $file = $request->file('image_file');
                $filename = 'prod_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/products'), $filename);
                $product->image = 'uploads/products/' . $filename;
            } elseif ($request->filled('image_url')) {
                $product->image = trim($request->image_url);
            }

            // 2. Cập nhật slug nếu tên thay đổi
            if ($request->name !== $product->name) {
                $slug = Str::slug($request->name);
                $originalSlug = $slug;
                $counter = 1;
                while (Product::where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
                    $slug = "{$originalSlug}-{$counter}";
                    $counter++;
                }
                $product->slug = $slug;
            }

            // 3. Cập nhật thông tin cơ bản
            $product->category_id = $request->category_id;
            $product->name        = trim($request->name);
            $product->price       = $request->price;
            $product->description = $request->description;
            $product->is_featured = $request->boolean('is_featured');
            $product->save();

            // 4. Cập nhật các biến thể hiện có (SKU, Giá riêng, Tồn kho)
            $totalStock = 0;
            if ($request->has('existing_variants') && is_array($request->existing_variants)) {
                foreach ($request->existing_variants as $varId => $varData) {
                    $variant = ProductVariant::where('id', $varId)->where('product_id', $product->id)->first();
                    if ($variant) {
                        $variantStock = (int) ($varData['stock'] ?? 0);
                        $totalStock += $variantStock;

                        $variant->update([
                            'sku'   => trim($varData['sku']),
                            'price' => !empty($varData['price']) ? $varData['price'] : null,
                            'stock' => $variantStock,
                            'image' => !empty($varData['image']) ? trim($varData['image']) : $variant->image,
                        ]);
                    }
                }
            } else {
                $totalStock = $product->variants()->sum('stock');
            }

            // Đồng bộ lại tổng tồn kho sản phẩm
            $product->update(['stock' => $totalStock]);

            DB::commit();

            return redirect()->route('admin.products.edit', $product->id)
                ->with('success', "Cập nhật sản phẩm '{$product->name}' thành công!");

        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Lỗi cập nhật: ' . $e->getMessage());
        }
    }

    /**
     * Thêm 1 biến thể mới vào sản phẩm đã có
     */
    public function storeVariant(Request $request, Product $product)
    {
        $request->validate([
            'size_id'  => 'required|exists:sizes,id',
            'color_id' => 'required|exists:colors,id',
            'sku'      => 'required|string|max:100|unique:product_variants,sku',
            'price'    => 'nullable|numeric|min:0',
            'stock'    => 'required|integer|min:0',
            'image'    => 'nullable|string',
        ], [
            'sku.unique' => 'Mã SKU này đã tồn tại, vui lòng chọn mã khác.',
        ]);

        // Kiểm tra cặp size + color đã tồn tại ở sản phẩm này chưa
        $exists = ProductVariant::where('product_id', $product->id)
            ->where('size_id', $request->size_id)
            ->where('color_id', $request->color_id)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Biến thể với Size và Màu này đã tồn tại trong sản phẩm!');
        }

        ProductVariant::create([
            'product_id' => $product->id,
            'size_id'    => $request->size_id,
            'color_id'   => $request->color_id,
            'sku'        => trim($request->sku),
            'price'      => $request->price ?: null,
            'stock'      => (int) $request->stock,
            'image'      => $request->filled('image') ? trim($request->image) : $product->image,
        ]);

        // Tính lại tổng tồn kho sản phẩm
        $product->update(['stock' => $product->variants()->sum('stock')]);

        return redirect()->route('admin.products.edit', $product->id)
            ->with('success', 'Đã thêm biến thể mới thành công!');
    }

    /**
     * Xóa 1 biến thể của sản phẩm
     */
    public function destroyVariant(ProductVariant $variant)
    {
        $productId = $variant->product_id;
        $product = Product::findOrFail($productId);

        // Kiểm tra biến thể đã có đơn hàng chưa
        if ($variant->orderItems()->count() > 0) {
            return redirect()->back()
                ->with('error', "Không thể xóa biến thể SKU '{$variant->sku}' vì đã phát sinh trong đơn hàng của khách hàng!");
        }

        $variant->delete();

        // Đồng bộ lại tổng tồn kho
        $product->update(['stock' => $product->variants()->sum('stock')]);

        return redirect()->route('admin.products.edit', $productId)
            ->with('success', 'Đã xóa biến thể khỏi sản phẩm thành công!');
    }

    /**
     * Xóa toàn bộ sản phẩm
     */
    public function destroy(Product $product)
    {
        // 1. Kiểm tra an toàn: Sản phẩm đã có trong đơn hàng chưa
        $orderItemsCount = $product->orderItems()->count();
        if ($orderItemsCount > 0) {
            return redirect()->back()
                ->with('error', "Không thể xóa sản phẩm '{$product->name}' vì đã có {$orderItemsCount} lượt đặt hàng! Bạn nên đặt số lượng tồn kho về 0 để tạm ngừng kinh doanh thay vì xóa.");
        }

        // 2. Xóa ảnh nếu là file tải lên cục bộ
        if ($product->image && File::exists(public_path($product->image))) {
            File::delete(public_path($product->image));
        }

        $name = $product->name;
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', "Đã xóa sản phẩm '{$name}' thành công!");
    }
}
