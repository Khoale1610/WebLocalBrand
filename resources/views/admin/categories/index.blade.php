@extends('admin.layouts.app')

@section('title', 'Quản lý Danh mục sản phẩm')
@section('page_title', 'Danh mục sản phẩm')

@section('content')
<div class="container-fluid px-0">

    <!-- Header Actions -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold text-dark mb-1">Danh mục sản phẩm</h3>
            <p class="text-muted small mb-0">Quản lý và phân loại các dòng sản phẩm thời trang trong cửa hàng.</p>
        </div>
        <button type="button" class="btn btn-primary d-inline-flex align-items-center gap-2 rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
            <i class="fa-solid fa-plus"></i>
            <span>Thêm danh mục mới</span>
        </button>
    </div>

    <!-- Main Card Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <form action="{{ route('admin.categories.index') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-12 col-md-5 col-lg-4">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                        <input type="text" name="search" class="form-control bg-light border-start-0" placeholder="Tìm kiếm theo tên hoặc slug..." value="{{ request('search') }}">
                        @if(request('search'))
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">Xóa lọc</a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-muted small text-uppercase">
                    <tr>
                        <th style="width: 70px;">ID</th>
                        <th>Tên danh mục</th>
                        <th>Đường dẫn tĩnh (Slug)</th>
                        <th class="text-center">Số sản phẩm</th>
                        <th>Ngày tạo</th>
                        <th class="text-end" style="width: 140px;">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td class="text-muted fw-bold">#{{ $category->id }}</td>
                            <td>
                                <span class="fw-bold text-dark fs-6">{{ $category->name }}</span>
                            </td>
                            <td>
                                <code class="text-primary bg-primary-subtle px-2 py-1 rounded small">
                                    {{ $category->slug }}
                                </code>
                                <a href="{{ route('category.show', $category->id) }}" target="_blank" class="text-muted ms-2" title="Xem ngoài web">
                                    <i class="fa-solid fa-arrow-up-right-from-square small"></i>
                                </a>
                            </td>
                            <td class="text-center">
                                <span class="badge {{ $category->products_count > 0 ? 'bg-info-subtle text-info border border-info-subtle' : 'bg-light text-secondary border' }} px-3 py-2 rounded-pill">
                                    {{ $category->products_count }} sản phẩm
                                </span>
                            </td>
                            <td class="text-muted small">
                                {{ $category->created_at ? $category->created_at->format('d/m/Y H:i') : '—' }}
                            </td>
                            <td class="text-end">
                                <!-- Nút Sửa -->
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-circle me-1" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editCategoryModal"
                                        data-id="{{ $category->id }}"
                                        data-name="{{ $category->name }}"
                                        data-slug="{{ $category->slug }}"
                                        data-url="{{ route('admin.categories.update', $category->id) }}"
                                        title="Chỉnh sửa">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>

                                <!-- Nút Xóa -->
                                @if($category->products_count > 0)
                                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-circle" 
                                            onclick="alert('Không thể xóa vì danh mục đang chứa {{ $category->products_count }} sản phẩm! Vui lòng xóa hoặc di chuyển sản phẩm trước.')" 
                                            title="Đang có sản phẩm, không thể xóa">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                @else
                                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa danh mục này?');">
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
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-layer-group fs-1 text-secondary opacity-50 d-block mb-3"></i>
                                Không tìm thấy danh mục sản phẩm nào.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($categories->hasPages())
            <div class="card-footer bg-white py-3">
                <div class="d-flex justify-content-center">
                    {{ $categories->links() }}
                </div>
            </div>
        @endif
    </div>

</div>

<!-- Modal 1: Thêm mới Danh mục -->
<div class="modal fade" id="createCategoryModal" tabindex="-1" aria-labelledby="createCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold" id="createCategoryModalLabel">Thêm danh mục mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-3">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tên danh mục <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="create_name" class="form-control" placeholder="Ví dụ: Áo Sơ Mi, Quần Jean, Áo Thun Oversize..." required autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Đường dẫn Slug (URL)</label>
                        <input type="text" name="slug" id="create_slug" class="form-control" placeholder="Tự sinh nếu bỏ trống (vd: ao-so-mi)">
                        <div class="form-text small">Dùng cho đường dẫn SEO website. Hệ thống sẽ tự động tạo từ tên nếu bạn không nhập.</div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Lưu danh mục</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal 2: Chỉnh sửa Danh mục -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form id="editCategoryForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold" id="editCategoryModalLabel">Chỉnh sửa danh mục</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-3">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tên danh mục <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Đường dẫn Slug (URL)</label>
                        <input type="text" name="slug" id="edit_slug" class="form-control">
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
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Tự sinh slug cho form tạo mới
    const createName = document.getElementById('create_name');
    const createSlug = document.getElementById('create_slug');
    if (createName && createSlug) {
        createName.addEventListener('input', function() {
            if (!createSlug.dataset.customized) {
                createSlug.value = slugify(createName.value);
            }
        });
        createSlug.addEventListener('input', function() {
            createSlug.dataset.customized = 'true';
        });
    }

    // Modal Sửa danh mục: Đổ dữ liệu vào form
    const editModal = document.getElementById('editCategoryModal');
    if (editModal) {
        editModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const name = button.getAttribute('data-name');
            const slug = button.getAttribute('data-slug');
            const url = button.getAttribute('data-url');

            document.getElementById('editCategoryForm').action = url;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_slug').value = slug;
        });
    }

    function slugify(text) {
        return text.toString().toLowerCase()
            .normalize('NFD') // chuẩn hóa tiếng Việt
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
