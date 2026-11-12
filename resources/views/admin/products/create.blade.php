@extends('admin.layouts.app')

@section('title', 'Thêm mới Sản phẩm')
@section('page_title', 'Thêm sản phẩm')

@section('content')
<div class="container-fluid px-0">

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" id="productForm">
        @csrf

        <!-- Top Header Actions -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-light rounded-circle text-muted" title="Quay lại danh sách">
                        <i class="fa-solid fa-arrow-left"></i>
                    </a>
                    <h3 class="fw-bold text-dark mb-0">Thêm sản phẩm mới</h3>
                </div>
                <p class="text-muted small mb-0 ps-4 ms-2">Nhập thông tin sản phẩm và thiết lập các biến thể kích cỡ / màu sắc.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.products.index') }}" class="btn btn-light rounded-pill px-4">Hủy</a>
                <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm fw-semibold">
                    <i class="fa-solid fa-floppy-disk me-2"></i>Lưu sản phẩm
                </button>
            </div>
        </div>

        <div class="row g-4">
            <!-- LEFT COLUMN: Thông tin chính & Biến thể (8 Cột) -->
            <div class="col-12 col-lg-8">
                
                <!-- Card 1: Thông tin cơ bản -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3 fw-bold text-dark">
                        <i class="fa-solid fa-circle-info text-primary me-2"></i>Thông tin cơ bản
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tên sản phẩm thời trang <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="product_name" class="form-control form-control-lg" placeholder="Ví dụ: Áo Sơ Mi Nam Dài Tay Bamboo Kháng Khuẩn..." value="{{ old('name') }}" required autofocus>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold">Danh mục <span class="text-danger">*</span></label>
                                <select name="category_id" class="form-select" required>
                                    <option value="">-- Chọn danh mục --</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold">Giá bán gốc (VNĐ) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" name="price" id="product_price" class="form-control" placeholder="450000" value="{{ old('price') }}" min="0" step="1000" required>
                                    <span class="input-group-text bg-light text-muted">₫</span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-0">
                            <label class="form-label fw-semibold">Mô tả sản phẩm</label>
                            <textarea name="description" rows="5" class="form-control" placeholder="Chất liệu vải, kiểu dáng form, hướng dẫn bảo quản giặt ủi...">{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Ma trận Biến thể (Size x Color Matrix Generator) -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <div class="fw-bold text-dark">
                            <i class="fa-solid fa-table-cells text-primary me-2"></i>Thiết lập Biến thể sản phẩm (Size & Màu)
                        </div>
                        <span class="badge bg-light text-muted border">Tùy chọn tạo ma trận</span>
                    </div>
                    <div class="card-body p-4">
                        <p class="text-muted small mb-3">
                            Chọn các Size và Màu sắc áp dụng cho sản phẩm này. Hệ thống sẽ tự động ghép cặp và sinh danh sách biến thể tương ứng.
                        </p>

                        <!-- Chọn Sizes -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-uppercase text-muted">1. Chọn các kích cỡ (Sizes):</label>
                            <div class="d-flex flex-wrap gap-2">
                                @forelse($sizes as $size)
                                    <div class="form-check form-check-inline m-0">
                                        <input class="btn-check variant-size-checkbox" type="checkbox" id="size_{{ $size->id }}" value="{{ $size->id }}" data-name="{{ $size->name }}">
                                        <label class="btn btn-outline-dark btn-sm rounded-pill px-3 py-1" for="size_{{ $size->id }}">
                                            {{ $size->name }}
                                        </label>
                                    </div>
                                @empty
                                    <span class="text-muted small">Chưa có Size nào. Hãy thêm Size trong mục Thuộc tính.</span>
                                @endforelse
                            </div>
                        </div>

                        <!-- Chọn Colors -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold small text-uppercase text-muted">2. Chọn các màu sắc (Colors):</label>
                            <div class="d-flex flex-wrap gap-2">
                                @forelse($colors as $color)
                                    <div class="form-check form-check-inline m-0">
                                        <input class="btn-check variant-color-checkbox" type="checkbox" id="color_{{ $color->id }}" value="{{ $color->id }}" data-name="{{ $color->name }}" data-hex="{{ $color->hex_code }}">
                                        <label class="btn btn-outline-secondary btn-sm rounded-pill px-3 py-1 d-inline-flex align-items-center gap-2" for="color_{{ $color->id }}">
                                            <span class="rounded-circle border" style="width: 14px; height: 14px; background-color: {{ $color->hex_code ?? '#000' }}; display: inline-block;"></span>
                                            <span>{{ $color->name }}</span>
                                        </label>
                                    </div>
                                @empty
                                    <span class="text-muted small">Chưa có Màu nào. Hãy thêm Màu trong mục Thuộc tính.</span>
                                @endforelse
                            </div>
                        </div>

                        <!-- Nút Sinh Ma Trận -->
                        <div class="d-flex align-items-center gap-3 mb-4 p-3 bg-light rounded-3">
                            <button type="button" class="btn btn-dark rounded-pill px-4 shadow-sm" id="btn-generate-matrix">
                                <i class="fa-solid fa-wand-magic-sparkles me-2 text-warning"></i>Tạo ma trận biến thể
                            </button>
                            <span class="text-muted small">Bấm để tự động tạo danh sách kết hợp Màu x Size bên dưới.</span>
                        </div>

                        <!-- Bảng Kết quả Ma trận Biến thể -->
                        <div id="variants-table-wrapper" class="d-none">
                            <h6 class="fw-bold text-dark mb-2">Danh sách biến thể phát sinh (<span id="variant-count-badge">0</span> biến thể):</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover align-middle mb-0" style="font-size: 0.875rem;">
                                    <thead class="table-light text-muted small text-uppercase">
                                        <tr>
                                            <th>Size</th>
                                            <th>Màu sắc</th>
                                            <th style="min-width: 180px;">Mã SKU <span class="text-danger">*</span></th>
                                            <th style="min-width: 140px;">Giá riêng (₫)</th>
                                            <th style="min-width: 110px;">Tồn kho <span class="text-danger">*</span></th>
                                            <th class="text-center" style="width: 50px;">Xóa</th>
                                        </tr>
                                    </thead>
                                    <tbody id="variants-table-body">
                                        <!-- JS will dynamically inject rows here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Gợi ý nếu không tạo biến thể -->
                        <div id="no-variants-notice" class="text-center py-4 text-muted small border rounded-3">
                            <i class="fa-solid fa-cubes-stacked fs-2 text-secondary opacity-50 d-block mb-2"></i>
                            Nếu sản phẩm không có phân loại Màu/Size, bạn chỉ cần nhập số lượng tồn kho tổng ở cột bên phải.
                        </div>

                    </div>
                </div>

            </div>

            <!-- RIGHT COLUMN: Ảnh & Cài đặt phụ (4 Cột) -->
            <div class="col-12 col-lg-4">

                <!-- Card: Cài đặt xuất bản -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3 fw-bold text-dark">
                        <i class="fa-solid fa-sliders text-primary me-2"></i>Cài đặt bán hàng
                    </div>
                    <div class="card-body p-3">
                        <!-- Tồn kho đơn thể -->
                        <div class="mb-3" id="single-stock-field">
                            <label class="form-label fw-semibold">Tồn kho ban đầu</label>
                            <input type="number" name="stock" id="base_stock" class="form-control" value="50" min="0">
                            <div class="form-text small">Nếu tạo biến thể ở bên cạnh, tồn kho tổng sẽ tự động tính theo tổng các biến thể.</div>
                        </div>

                        <!-- Checkbox Nổi bật -->
                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" type="checkbox" role="switch" id="is_featured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="is_featured">
                                Đánh dấu sản phẩm HOT / Nổi bật
                            </label>
                            <div class="form-text small">Sản phẩm sẽ hiển thị tại mục "Sản phẩm nổi bật" trang chủ.</div>
                        </div>
                    </div>
                </div>

                <!-- Card: Ảnh đại diện sản phẩm -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3 fw-bold text-dark">
                        <i class="fa-regular fa-image text-primary me-2"></i>Ảnh đại diện sản phẩm
                    </div>
                    <div class="card-body p-3">
                        <!-- Nav tabs chọn Upload hoặc URL -->
                        <ul class="nav nav-pills nav-fill mb-3" id="imageTabs" role="tablist">
                            <li class="nav-item">
                                <button class="nav-link active py-1 small fw-semibold" id="upload-tab" data-bs-toggle="pill" data-bs-target="#upload-pane" type="button">Tải file lên</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link py-1 small fw-semibold" id="url-tab" data-bs-toggle="pill" data-bs-target="#url-pane" type="button">Nhập URL ảnh</button>
                            </li>
                        </ul>

                        <div class="tab-content mb-3">
                            <div class="tab-pane fade show active" id="upload-pane">
                                <input type="file" name="image_file" id="image_file_input" class="form-control" accept="image/*">
                                <div class="form-text small">Hỗ trợ JPG, PNG, WEBP tối đa 5MB.</div>
                            </div>
                            <div class="tab-pane fade" id="url-pane">
                                <input type="url" name="image_url" id="image_url_input" class="form-control" placeholder="https://example.com/photo.jpg">
                                <div class="form-text small">Dán liên kết ảnh từ web ngoài.</div>
                            </div>
                        </div>

                        <!-- Live Image Preview -->
                        <div class="border rounded-3 p-2 text-center bg-light">
                            <img id="image_preview" src="https://via.placeholder.com/300x400?text=Xem+Trước+Ảnh" 
                                 alt="Xem trước ảnh" 
                                 class="rounded object-fit-cover w-100" 
                                 style="max-height: 320px;">
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </form>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Live Preview Ảnh
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

    // 2. Bộ sinh Ma trận Biến thể (Variant Matrix Generator)
    const btnGenerate = document.getElementById('btn-generate-matrix');
    const tableWrapper = document.getElementById('variants-table-wrapper');
    const tableBody = document.getElementById('variants-table-body');
    const notice = document.getElementById('no-variants-notice');
    const countBadge = document.getElementById('variant-count-badge');
    const productNameInput = document.getElementById('product_name');
    const productPriceInput = document.getElementById('product_price');

    if (btnGenerate) {
        btnGenerate.addEventListener('click', function () {
            const selectedSizes = Array.from(document.querySelectorAll('.variant-size-checkbox:checked')).map(cb => ({
                id: cb.value,
                name: cb.dataset.name
            }));

            const selectedColors = Array.from(document.querySelectorAll('.variant-color-checkbox:checked')).map(cb => ({
                id: cb.value,
                name: cb.dataset.name,
                hex: cb.dataset.hex
            }));

            if (selectedSizes.length === 0 || selectedColors.length === 0) {
                alert('Vui lòng chọn ít nhất 1 Size và 1 Màu sắc để tạo ma trận biến thể!');
                return;
            }

            // Tạo mã SKU Prefix từ tên sản phẩm
            let prefix = 'OWEN';
            const nameVal = productNameInput.value.trim();
            if (nameVal) {
                const words = nameVal.normalize('NFD').replace(/[\u0300-\u036f]/g, '').replace(/[^\w\s]/gi, '').split(/\s+/);
                prefix = words.map(w => w.charAt(0).toUpperCase()).join('').substring(0, 5) || 'OWEN';
            }

            const basePrice = productPriceInput.value || '';
            tableBody.innerHTML = '';
            let index = 0;

            selectedSizes.forEach(size => {
                selectedColors.forEach(color => {
                    const rowId = `var_${size.id}_${color.id}`;
                    const skuVal = `${prefix}-${size.name.toUpperCase()}-${slugify(color.name).toUpperCase()}`;

                    const tr = document.createElement('tr');
                    tr.id = rowId;
                    tr.innerHTML = `
                        <td>
                            <input type="hidden" name="variants[${index}][size_id]" value="${size.id}">
                            <span class="badge bg-light text-dark border px-2 py-1 fw-bold">${size.name}</span>
                        </td>
                        <td>
                            <input type="hidden" name="variants[${index}][color_id]" value="${color.id}">
                            <div class="d-flex align-items-center gap-2">
                                <span class="rounded-circle border" style="width: 14px; height: 14px; background-color: ${color.hex || '#000'};"></span>
                                <span>${color.name}</span>
                            </div>
                        </td>
                        <td>
                            <input type="text" name="variants[${index}][sku]" class="form-control form-control-sm" value="${skuVal}" required>
                        </td>
                        <td>
                            <input type="number" name="variants[${index}][price]" class="form-control form-control-sm" value="${basePrice}" placeholder="Mặc định giá gốc">
                        </td>
                        <td>
                            <input type="number" name="variants[${index}][stock]" class="form-control form-control-sm" value="20" min="0" required>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-outline-danger border-0 p-1 rounded-circle" onclick="this.closest('tr').remove(); updateVariantCount();" title="Bỏ dòng này">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </td>
                    `;
                    tableBody.appendChild(tr);
                    index++;
                });
            });

            tableWrapper.classList.remove('d-none');
            notice.classList.add('d-none');
            updateVariantCount();
        });
    }

    window.updateVariantCount = function() {
        const rows = tableBody.querySelectorAll('tr').length;
        if (countBadge) countBadge.innerText = rows;
        if (rows === 0) {
            tableWrapper.classList.add('d-none');
            notice.classList.remove('d-none');
        }
    };

    function slugify(text) {
        return text.toString().toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .replace(/[đĐ]/g, 'd')
            .replace(/\s+/g, '-')
            .replace(/[^\w\-]+/g, '')
            .replace(/\-\-+/g, '-')
            .replace(/^-+/, '')
            .replace(/-+$/, '');
    }
});
</script>
@endpush
