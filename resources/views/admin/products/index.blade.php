@extends('admin.layouts.app')

@section('title', 'Quản lý Sản phẩm')
@section('page_title', 'Sản phẩm & Biến thể')

@section('content')
<div class="container-fluid px-0">

    <!-- Header Actions -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold text-dark mb-1">Danh sách Sản phẩm</h3>
            <p class="text-muted small mb-0">Quản lý kho hàng thời trang, giá bán và cấu hình biến thể (Size & Màu).</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-2 rounded-pill px-4 shadow-sm">
            <i class="fa-solid fa-plus"></i>
            <span>Thêm sản phẩm mới</span>
        </a>
    </div>

    <!-- Filters Bar -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.products.index') }}" method="GET" class="row g-2 align-items-center">
                <!-- Search Input -->
                <div class="col-12 col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                        <input type="text" name="search" class="form-control bg-light border-start-0" placeholder="Tìm tên, slug hoặc mã SKU..." value="{{ request('search') }}">
                    </div>
                </div>

                <!-- Category Filter -->
                <div class="col-6 col-md-3">
                    <select name="category_id" class="form-select bg-light">
                        <option value="">-- Tất cả danh mục --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Stock Status Filter -->
                <div class="col-6 col-md-2">
                    <select name="stock_status" class="form-select bg-light">
                        <option value="">-- Tồn kho --</option>
                        <option value="in_stock" {{ request('stock_status') == 'in_stock' ? 'selected' : '' }}>Còn hàng (>5)</option>
                        <option value="low_stock" {{ request('stock_status') == 'low_stock' ? 'selected' : '' }}>Sắp hết (1-5)</option>
                        <option value="out_of_stock" {{ request('stock_status') == 'out_of_stock' ? 'selected' : '' }}>Hết hàng (0)</option>
                    </select>
                </div>

                <!-- Featured Filter -->
                <div class="col-6 col-md-2">
                    <select name="is_featured" class="form-select bg-light">
                        <option value="">-- Nổi bật --</option>
                        <option value="1" {{ request('is_featured') === '1' ? 'selected' : '' }}>Sản phẩm HOT</option>
                        <option value="0" {{ request('is_featured') === '0' ? 'selected' : '' }}>Bình thường</option>
                    </select>
                </div>

                <!-- Submit & Clear Button -->
                <div class="col-6 col-md-1 d-flex gap-1">
                    <button type="submit" class="btn btn-dark w-100" title="Áp dụng bộ lọc">
                        <i class="fa-solid fa-filter"></i>
                    </button>
                    @if(request()->hasAny(['search', 'category_id', 'stock_status', 'is_featured']))
                        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary" title="Xóa lọc">
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Products Data Table -->
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-muted small text-uppercase">
                    <tr>
                        <th style="width: 80px;">Ảnh</th>
                        <th>Sản phẩm</th>
                        <th>Danh mục</th>
                        <th>Giá bán</th>
                        <th class="text-center">Biến thể</th>
                        <th class="text-center">Tồn kho</th>
                        <th class="text-center">Nổi bật</th>
                        <th class="text-end" style="width: 130px;">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <!-- Product Image -->
                            <td>
                                <img src="{{ $product->image_url }}" 
                                     alt="{{ $product->name }}" 
                                     class="rounded object-fit-cover shadow-xs border" 
                                     style="width: 56px; height: 68px;">
                            </td>

                            <!-- Name & Slug -->
                            <td>
                                <div class="fw-bold text-dark fs-6">{{ $product->name }}</div>
                                <div class="text-muted small">
                                    <code class="text-muted">{{ $product->slug }}</code>
                                </div>
                            </td>

                            <!-- Category -->
                            <td>
                                @if($product->category)
                                    <span class="badge bg-light text-dark border">{{ $product->category->name }}</span>
                                @else
                                    <span class="text-muted small">Chưa phân loại</span>
                                @endif
                            </td>

                            <!-- Price -->
                            <td>
                                <span class="fw-bold text-dark">{{ $product->formatted_price }}</span>
                            </td>

                            <!-- Variants count & overview -->
                            <td class="text-center">
                                @if($product->variants->count() > 0)
                                    <a href="{{ route('admin.products.edit', $product->id) }}#variants-section" class="badge bg-primary-subtle text-primary border border-primary-subtle text-decoration-none px-3 py-2 rounded-pill" title="Bấm để xem và sửa biến thể">
                                        {{ $product->variants->count() }} biến thể
                                    </a>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-pill">Đơn thể</span>
                                @endif
                            </td>

                            <!-- Stock -->
                            <td class="text-center">
                                @if($product->stock > 5)
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill">
                                        {{ $product->stock }} chiếc
                                    </span>
                                @elseif($product->stock > 0)
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-3 py-2 rounded-pill">
                                        Còn {{ $product->stock }} (Ít)
                                    </span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2 rounded-pill">
                                        Hết hàng
                                    </span>
                                @endif
                            </td>

                            <!-- Is Featured -->
                            <td class="text-center">
                                @if($product->is_featured)
                                    <span class="badge bg-danger rounded-pill px-2 py-1 small">
                                        <i class="fa-solid fa-fire me-1"></i>HOT
                                    </span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="text-end">
                                <!-- Xem ngoài web -->
                                <a href="{{ route('products.show', $product->id) }}" target="_blank" class="btn btn-sm btn-outline-secondary rounded-circle me-1" title="Xem trên cửa hàng">
                                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                </a>

                                <!-- Sửa -->
                                <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-outline-primary rounded-circle me-1" title="Chỉnh sửa sản phẩm & biến thể">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>

                                <!-- Xóa -->
                                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này cùng toàn bộ biến thể?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle" title="Xóa">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-box-open fs-1 text-secondary opacity-50 d-block mb-3"></i>
                                Không tìm thấy sản phẩm nào phù hợp với bộ lọc.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
            <div class="card-footer bg-white py-3">
                <div class="d-flex justify-content-center">
                    {{ $products->links() }}
                </div>
            </div>
        @endif
    </div>

</div>
@endsection
