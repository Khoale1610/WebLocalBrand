@extends('admin.layouts.app')

@section('title', 'Quản lý Thuộc tính (Size & Màu sắc)')
@section('page_title', 'Thuộc tính sản phẩm')

@section('content')
<div class="container-fluid px-0">

    <!-- Header Actions -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold text-dark mb-1">Thuộc tính sản phẩm</h3>
            <p class="text-muted small mb-0">Quản lý kích cỡ (Size) và bảng màu sắc (Color) phục vụ tạo biến thể hàng hóa.</p>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-primary d-inline-flex align-items-center gap-2 rounded-pill px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#createSizeModal">
                <i class="fa-solid fa-plus"></i>
                <span>Thêm Size</span>
            </button>
            <button type="button" class="btn btn-dark d-inline-flex align-items-center gap-2 rounded-pill px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#createColorModal">
                <i class="fa-solid fa-palette"></i>
                <span>Thêm Màu</span>
            </button>
        </div>
    </div>

    <!-- Navigation Tabs -->
    @php
        $activeTab = request('tab', 'sizes');
    @endphp
    <ul class="nav nav-pills mb-4 gap-2" id="attributeTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link rounded-pill px-4 fw-semibold {{ $activeTab === 'sizes' ? 'active' : '' }}" 
                    id="sizes-tab" data-bs-toggle="pill" data-bs-target="#sizes-content" type="button" role="tab">
                <i class="fa-solid fa-ruler me-2"></i>Kích thước (Sizes)
                <span class="badge bg-white text-dark ms-2">{{ $sizes->count() }}</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link rounded-pill px-4 fw-semibold {{ $activeTab === 'colors' ? 'active' : '' }}" 
                    id="colors-tab" data-bs-toggle="pill" data-bs-target="#colors-content" type="button" role="tab">
                <i class="fa-solid fa-palette me-2"></i>Màu sắc (Colors)
                <span class="badge bg-white text-dark ms-2">{{ $colors->count() }}</span>
            </button>
        </li>
    </ul>

    <!-- Tab Contents -->
    <div class="tab-content" id="attributeTabsContent">
        
        <!-- TAB 1: KÍCH THƯỚC (SIZES) -->
        <div class="tab-pane fade {{ $activeTab === 'sizes' ? 'show active' : '' }}" id="sizes-content" role="tabpanel">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <span class="fw-bold text-dark">Danh sách Kích cỡ thời trang</span>
                    <span class="text-muted small">Dùng để tạo biến thể như Áo S, M, L hoặc Quần 29, 30, 31...</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-muted small text-uppercase">
                            <tr>
                                <th style="width: 70px;">ID</th>
                                <th>Kích thước</th>
                                <th class="text-center">Thứ tự hiển thị</th>
                                <th class="text-center">Số biến thể đang dùng</th>
                                <th class="text-end" style="width: 140px;">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sizes as $size)
                                <tr>
                                    <td class="text-muted fw-bold">#{{ $size->id }}</td>
                                    <td>
                                        <span class="badge bg-light text-dark border px-3 py-2 fs-6 fw-bold">
                                            {{ $size->name }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="text-muted">{{ $size->sort_order }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge {{ $size->variants_count > 0 ? 'bg-info-subtle text-info border border-info-subtle' : 'bg-light text-secondary border' }} px-3 py-2 rounded-pill">
                                            {{ $size->variants_count }} biến thể
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <!-- Nút Sửa Size -->
                                        <button type="button" class="btn btn-sm btn-outline-primary rounded-circle me-1" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editSizeModal"
                                                data-id="{{ $size->id }}"
                                                data-name="{{ $size->name }}"
                                                data-sort="{{ $size->sort_order }}"
                                                data-url="{{ route('admin.attributes.sizes.update', $size->id) }}"
                                                title="Chỉnh sửa">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>

                                        <!-- Nút Xóa Size -->
                                        @if($size->variants_count > 0)
                                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-circle" 
                                                    onclick="alert('Không thể xóa Size {{ $size->name }} vì có {{ $size->variants_count }} biến thể sản phẩm đang sử dụng!')"
                                                    title="Đang có sản phẩm sử dụng">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        @else
                                            <form action="{{ route('admin.attributes.sizes.destroy', $size->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa kích thước này?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle" title="Xóa">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        Chưa có kích thước nào trong hệ thống.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- TAB 2: MÀU SẮC (COLORS) -->
        <div class="tab-pane fade {{ $activeTab === 'colors' ? 'show active' : '' }}" id="colors-content" role="tabpanel">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <span class="fw-bold text-dark">Danh sách Bảng màu thời trang</span>
                    <span class="text-muted small">Mã màu HEX hiển thị trực quan dạng chấm tròn trên web</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-muted small text-uppercase">
                            <tr>
                                <th style="width: 70px;">ID</th>
                                <th>Màu sắc</th>
                                <th>Xem trước màu & Mã HEX</th>
                                <th class="text-center">Số biến thể đang dùng</th>
                                <th class="text-end" style="width: 140px;">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($colors as $color)
                                <tr>
                                    <td class="text-muted fw-bold">#{{ $color->id }}</td>
                                    <td>
                                        <span class="fw-bold text-dark fs-6">{{ $color->name }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="d-inline-block rounded-circle border shadow-xs" 
                                                  style="width: 26px; height: 26px; background-color: {{ $color->hex_code ?? '#ffffff' }};">
                                            </span>
                                            <code class="text-dark bg-light px-2 py-1 rounded small">
                                                {{ strtoupper($color->hex_code ?? '#000000') }}
                                            </code>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge {{ $color->variants_count > 0 ? 'bg-info-subtle text-info border border-info-subtle' : 'bg-light text-secondary border' }} px-3 py-2 rounded-pill">
                                            {{ $color->variants_count }} biến thể
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <!-- Nút Sửa Màu -->
                                        <button type="button" class="btn btn-sm btn-outline-primary rounded-circle me-1" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editColorModal"
                                                data-id="{{ $color->id }}"
                                                data-name="{{ $color->name }}"
                                                data-hex="{{ $color->hex_code }}"
                                                data-url="{{ route('admin.attributes.colors.update', $color->id) }}"
                                                title="Chỉnh sửa">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>

                                        <!-- Nút Xóa Màu -->
                                        @if($color->variants_count > 0)
                                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-circle" 
                                                    onclick="alert('Không thể xóa Màu {{ $color->name }} vì có {{ $color->variants_count }} biến thể sản phẩm đang sử dụng!')"
                                                    title="Đang có sản phẩm sử dụng">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        @else
                                            <form action="{{ route('admin.attributes.colors.destroy', $color->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa màu này?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle" title="Xóa">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        Chưa có màu sắc nào trong hệ thống.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

</div>

<!-- Modal 1: Thêm mới Size -->
<div class="modal fade" id="createSizeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('admin.attributes.sizes.store') }}" method="POST">
                @csrf
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold">Thêm kích thước mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-3">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tên kích cỡ <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="Ví dụ: S, M, L, XL, XXL, Oversize, 39, 40..." required autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Thứ tự hiển thị</label>
                        <input type="number" name="sort_order" class="form-control" value="0" min="0">
                        <div class="form-text small">Số nhỏ hơn sẽ hiển thị trước (VD: 1 cho S, 2 cho M, 3 cho L).</div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Lưu kích thước</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal 2: Chỉnh sửa Size -->
<div class="modal fade" id="editSizeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form id="editSizeForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold">Chỉnh sửa kích thước</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-3">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tên kích cỡ <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="edit_size_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Thứ tự hiển thị</label>
                        <input type="number" name="sort_order" id="edit_size_sort" class="form-control" min="0">
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal 3: Thêm mới Color -->
<div class="modal fade" id="createColorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('admin.attributes.colors.store') }}" method="POST">
                @csrf
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold">Thêm màu sắc mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-3">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tên màu <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="Ví dụ: Đen Jet Black, Trắng Kem, Xanh Rêu..." required autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Mã màu HEX</label>
                        <div class="input-group">
                            <input type="color" class="form-control form-control-color" id="create_color_picker" value="#000000" title="Chọn màu trực quan">
                            <input type="text" name="hex_code" id="create_hex_code" class="form-control" value="#000000" placeholder="#000000" maxlength="10">
                        </div>
                        <div class="form-text small">Bấm vào ô màu để chọn nhanh hoặc gõ trực tiếp mã màu HEX.</div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-dark rounded-pill px-4">Lưu màu sắc</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal 4: Chỉnh sửa Color -->
<div class="modal fade" id="editColorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form id="editColorForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold">Chỉnh sửa màu sắc</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-3">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tên màu <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="edit_color_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Mã màu HEX</label>
                        <div class="input-group">
                            <input type="color" class="form-control form-control-color" id="edit_color_picker" value="#000000" title="Chọn màu trực quan">
                            <input type="text" name="hex_code" id="edit_color_hex" class="form-control" maxlength="10">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-dark rounded-pill px-4">Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Đồng bộ Color Picker <-> Mã Hex (Thêm mới)
    const createPicker = document.getElementById('create_color_picker');
    const createHex = document.getElementById('create_hex_code');
    if (createPicker && createHex) {
        createPicker.addEventListener('input', function() {
            createHex.value = createPicker.value.toUpperCase();
        });
        createHex.addEventListener('input', function() {
            if (/^#[0-9A-F]{6}$/i.test(createHex.value)) {
                createPicker.value = createHex.value;
            }
        });
    }

    // 2. Modal Sửa Size: Đổ dữ liệu
    const editSizeModal = document.getElementById('editSizeModal');
    if (editSizeModal) {
        editSizeModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            document.getElementById('editSizeForm').action = button.getAttribute('data-url');
            document.getElementById('edit_size_name').value = button.getAttribute('data-name');
            document.getElementById('edit_size_sort').value = button.getAttribute('data-sort');
        });
    }

    // 3. Modal Sửa Color: Đổ dữ liệu & Đồng bộ Color Picker
    const editColorModal = document.getElementById('editColorModal');
    const editPicker = document.getElementById('edit_color_picker');
    const editHex = document.getElementById('edit_color_hex');

    if (editColorModal) {
        editColorModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const hex = button.getAttribute('data-hex') || '#000000';
            document.getElementById('editColorForm').action = button.getAttribute('data-url');
            document.getElementById('edit_color_name').value = button.getAttribute('data-name');
            editHex.value = hex.toUpperCase();
            if (/^#[0-9A-F]{6}$/i.test(hex)) {
                editPicker.value = hex;
            }
        });
    }

    if (editPicker && editHex) {
        editPicker.addEventListener('input', function() {
            editHex.value = editPicker.value.toUpperCase();
        });
        editHex.addEventListener('input', function() {
            if (/^#[0-9A-F]{6}$/i.test(editHex.value)) {
                editPicker.value = editHex.value;
            }
        });
    }
});
</script>
@endpush
