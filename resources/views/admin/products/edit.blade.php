@extends('admin.layouts.app')

@section('title', 'Chỉnh sửa Sản phẩm: ' . $product->name)
@section('page_title', 'Chỉnh sửa sản phẩm')

@section('content')
<div class="container-fluid px-0">

    <!-- Top Navigation Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-light rounded-circle text-muted" title="Quay lại danh sách">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <h3 class="fw-bold text-dark mb-0">Chỉnh sửa: {{ $product->name }}</h3>
            </div>
            <p class="text-muted small mb-0 ps-4 ms-2">Mã sản phẩm: #{{ $product->id }} | Slug: <code>{{ $product->slug }}</code></p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('products.show', $product->id) }}" target="_blank" class="btn btn-outline-dark rounded-pill px-3">
                <i class="fa-solid fa-arrow-up-right-from-square me-1"></i>Xem trên shop
            </a>
            <button type="submit" form="editProductForm" class="btn btn-primary rounded-pill px-4 shadow-sm fw-semibold">
                <i class="fa-solid fa-floppy-disk me-2"></i>Lưu thay đổi
            </button>
        </div>
    </div>

    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" id="editProductForm">
        @csrf
        @method('PUT')

        <div class="row g-4">
            <!-- LEFT COLUMN: Thông tin cơ bản & Quản lý Biến thể (8 Cột) -->
            <div class="col-12 col-lg-8">
                
                <!-- Card 1: Thông tin cơ bản -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3 fw-bold text-dark">
                        <i class="fa-solid fa-circle-info text-primary me-2"></i>Thông tin sản phẩm
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tên sản phẩm thời trang <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control form-control-lg" value="{{ old('name', $product->name) }}" required>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold">Danh mục <span class="text-danger">*</span></label>
                                <select name="category_id" class="form-select" required>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold">Giá bán gốc (VNĐ) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" name="price" class="form-control" value="{{ old('price', (int)$product->price) }}" min="0" step="1000" required>
                                    <span class="input-group-text bg-light text-muted">₫</span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-0">
                            <label class="form-label fw-semibold">Mô tả sản phẩm</label>
                            <textarea name="description" rows="5" class="form-control">{{ old('description', $product->description) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Quản lý Biến thể hiện tại (Variants Management) -->
                <div class="card border-0 shadow-sm mb-4" id="variants-section">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <div class="fw-bold text-dark">
                            <i class="fa-solid fa-table-cells text-primary me-2"></i>Danh sách biến thể ({{ $product->variants->count() }} biến thể)
                        </div>
                        <button type="button" class="btn btn-sm btn-dark rounded-pill px-3 shadow-xs" data-bs-toggle="modal" data-bs-target="#addNewVariantModal">
                            <i class="fa-solid fa-plus me-1"></i>Thêm biến thể mới
                        </button>
                    </div>
                    <div class="card-body p-0">
                        @if($product->variants->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0" style="font-size: 0.875rem;">
                                    <thead class="table-light text-muted small text-uppercase">
                                        <tr>
                                            <th>Size</th>
                                            <th>Màu sắc</th>
                                            <th style="min-width: 170px;">Mã SKU</th>
                                            <th style="min-width: 130px;">Giá riêng (₫)</th>
                                            <th style="min-width: 100px;">Tồn kho</th>
                                            <th class="text-end" style="width: 60px;">Xóa</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($product->variants as $var)
                                            <tr>
                                                <td>
                                                    <span class="badge bg-light text-dark border px-2 py-1 fw-bold fs-6">
                                                        {{ $var->size->name ?? 'N/A' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="rounded-circle border" style="width: 16px; height: 16px; background-color: {{ $var->color->hex_code ?? '#000' }};"></span>
                                                        <span class="fw-medium">{{ $var->color->name ?? 'N/A' }}</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <input type="text" name="existing_variants[{{ $var->id }}][sku]" class="form-control form-control-sm" value="{{ $var->sku }}" required>
                                                </td>
                                                <td>
                                                    <input type="number" name="existing_variants[{{ $var->id }}][price]" class="form-control form-control-sm" value="{{ $var->price ? (int)$var->price : '' }}" placeholder="Dùng giá gốc">
                                                </td>
                                                <td>
                                                    <input type="number" name="existing_variants[{{ $var->id }}][stock]" class="form-control form-control-sm {{ $var->stock <= 5 ? 'border-warning fw-bold text-danger' : '' }}" value="{{ $var->stock }}" min="0" required>
                                                </td>
                                                <td class="text-end">
                                                    <button type="button" class="btn btn-sm btn-outline-danger border-0 p-1 rounded-circle" 
                                                            onclick="if(confirm('Bạn có chắc chắn muốn xóa biến thể này?')) { document.getElementById('delete-variant-form-{{ $var->id }}').submit(); }" 
                                                            title="Xóa biến thể">
                                                        <i class="fa-solid fa-trash-can"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5 text-muted">
                                <i class="fa-solid fa-layer-group fs-2 text-secondary opacity-50 d-block mb-2"></i>
                                Sản phẩm này chưa có biến thể nào. Bấm <strong>"Thêm biến thể mới"</strong> ở góc phải để tạo.
                            </div>
                        @endif
                    </div>
                </div>

            </div>

            <!-- RIGHT COLUMN: Ảnh & Cài đặt (4 Cột) -->
            <div class="col-12 col-lg-4">

                <!-- Card: Trạng thái & Tổng kho -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3 fw-bold text-dark">
                        <i class="fa-solid fa-boxes-stacked text-primary me-2"></i>Tổng quan tồn kho
                    </div>
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center mb-3 p-3 bg-light rounded-3">
                            <div>
                                <div class="text-muted small">Tổng số lượng kho:</div>
                                <h4 class="fw-bold mb-0 text-dark">{{ $product->stock }} chiếc</h4>
                            </div>
                            <span class="badge {{ $product->stock > 5 ? 'bg-success' : ($product->stock > 0 ? 'bg-warning' : 'bg-danger') }} rounded-pill px-3 py-2">
                                {{ $product->stock > 5 ? 'Còn hàng' : ($product->stock > 0 ? 'Sắp hết' : 'Hết hàng') }}
                            </span>
                        </div>

                        <!-- Checkbox Nổi bật -->
                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" type="checkbox" role="switch" id="is_featured" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="is_featured">
                                Đánh dấu sản phẩm HOT / Nổi bật
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Card: Ảnh đại diện sản phẩm -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3 fw-bold text-dark">
                        <i class="fa-regular fa-image text-primary me-2"></i>Ảnh sản phẩm
                    </div>
                    <div class="card-body p-3">
                        <!-- Preview ảnh hiện tại -->
                        <div class="border rounded-3 p-2 text-center bg-light mb-3">
                            <img id="image_preview" src="{{ $product->image_url }}" 
                                 alt="{{ $product->name }}" 
                                 class="rounded object-fit-cover w-100" 
                                 style="max-height: 320px;">
                        </div>

                        <!-- Đổi ảnh -->
                        <ul class="nav nav-pills nav-fill mb-2" id="imageTabs" role="tablist">
                            <li class="nav-item">
                                <button class="nav-link active py-1 small fw-semibold" id="upload-tab" data-bs-toggle="pill" data-bs-target="#upload-pane" type="button">Tải file mới</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link py-1 small fw-semibold" id="url-tab" data-bs-toggle="pill" data-bs-target="#url-pane" type="button">Đổi URL ảnh</button>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="upload-pane">
                                <input type="file" name="image_file" id="image_file_input" class="form-control" accept="image/*">
                                <div class="form-text small">Chọn file để thay thế ảnh hiện tại.</div>
                            </div>
                            <div class="tab-pane fade" id="url-pane">
                                <input type="url" name="image_url" id="image_url_input" class="form-control" placeholder="https://example.com/photo.jpg" value="{{ str_starts_with($product->image, 'http') ? $product->image : '' }}">
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </form>

    <!-- Hidden Forms for deleting individual variants -->
    @foreach($product->variants as $var)
        <form id="delete-variant-form-{{ $var->id }}" action="{{ route('admin.variants.destroy', $var->id) }}" method="POST" class="d-none">
            @csrf
            @method('DELETE')
        </form>
    @endforeach

</div>

<!-- Modal: Thêm biến thể mới cho sản phẩm này -->
<div class="modal fade" id="addNewVariantModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('admin.products.variants.store', $product->id) }}" method="POST">
                @csrf
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold">Thêm biến thể mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-3">
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold">Kích thước (Size) <span class="text-danger">*</span></label>
                            <select name="size_id" class="form-select" required>
                                <option value="">-- Chọn Size --</option>
                                @foreach($sizes as $s)
                                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Màu sắc (Color) <span class="text-danger">*</span></label>
                            <select name="color_id" class="form-select" required>
                                <option value="">-- Chọn Màu --</option>
                                @foreach($colors as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Mã SKU <span class="text-danger">*</span></label>
                        <input type="text" name="sku" class="form-control" placeholder="Ví dụ: {{ strtoupper(substr($product->slug, 0, 8)) }}-M-DEN" required>
                        <div class="form-text small">Mã quản lý kho duy nhất cho từng biến thể.</div>
                    </div>

                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold">Giá riêng (₫)</label>
                            <input type="number" name="price" class="form-control" placeholder="Để trống nếu bằng giá gốc ({{ number_format($product->price, 0, ',', '.') }}₫)">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Tồn kho <span class="text-danger">*</span></label>
                            <input type="number" name="stock" class="form-control" value="20" min="0" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Tạo biến thể</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const fileInput = document.getElementById('image_file_input');
    const urlInput = document.getElementById('image_url_input');
    const previewImg = document.getElementById('image_preview');

    if (fileInput) {
        fileInput.addEventListener('change', function () {
            if (fileInput.files && fileInput.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    previewImg.src = e.target.result;
                };
                reader.readAsDataURL(fileInput.files[0]);
                if (urlInput) urlInput.value = '';
            }
        });
    }

    if (urlInput) {
        urlInput.addEventListener('input', function () {
            if (urlInput.value.trim() !== '') {
                previewImg.src = urlInput.value.trim();
                if (fileInput) fileInput.value = '';
            }
        });
    }
});
</script>
@endpush
