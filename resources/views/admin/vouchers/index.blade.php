@extends('admin.layouts.app')

@section('title', 'Quản lý Mã giảm giá (Vouchers)')
@section('page_title', 'Mã giảm giá')

@section('content')
<div class="container-fluid px-0">

    <!-- Header Actions -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold text-dark mb-1">Quản lý Voucher & Khuyến mãi</h3>
            <p class="text-muted small mb-0">Thiết lập các mã coupon giảm giá theo % hoặc tiền mặt kích thích mua sắm.</p>
        </div>
        <button type="button" class="btn btn-primary d-inline-flex align-items-center gap-2 rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#createVoucherModal">
            <i class="fa-solid fa-plus"></i>
            <span>Tạo Voucher mới</span>
        </button>
    </div>

    <!-- Filters Bar -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.vouchers.index') }}" method="GET" class="row g-2 align-items-center">
                <!-- Search -->
                <div class="col-12 col-md-5 col-lg-4">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                        <input type="text" name="search" class="form-control bg-light border-start-0" placeholder="Tìm theo mã hoặc tên voucher..." value="{{ request('search') }}">
                    </div>
                </div>

                <!-- Type Filter -->
                <div class="col-6 col-md-3 col-lg-3">
                    <select name="type" class="form-select bg-light">
                        <option value="">-- Tất cả hình thức giảm --</option>
                        <option value="percent" {{ request('type') === 'percent' ? 'selected' : '' }}>Giảm theo phần trăm (%)</option>
                        <option value="fixed" {{ request('type') === 'fixed' ? 'selected' : '' }}>Giảm số tiền cố định (VNĐ)</option>
                    </select>
                </div>

                <!-- Status Filter -->
                <div class="col-6 col-md-3 col-lg-3">
                    <select name="status" class="form-select bg-light">
                        <option value="">-- Tất cả trạng thái --</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Đang hoạt động</option>
                        <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Đã hết hạn</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Đã tắt / Tạm dừng</option>
                    </select>
                </div>

                <!-- Submit & Clear -->
                <div class="col-12 col-lg-2 d-flex gap-1">
                    <button type="submit" class="btn btn-dark w-100" title="Lọc">
                        <i class="fa-solid fa-filter me-1"></i>Lọc
                    </button>
                    @if(request()->hasAny(['search', 'type', 'status']))
                        <a href="{{ route('admin.vouchers.index') }}" class="btn btn-outline-secondary" title="Xóa lọc">
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Vouchers Data Table -->
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size: 0.875rem;">
                <thead class="table-light text-muted small text-uppercase">
                    <tr>
                        <th style="min-width: 140px;">Mã Voucher</th>
                        <th>Chương trình / Mô tả</th>
                        <th>Mức giảm</th>
                        <th>Đơn tối thiểu</th>
                        <th>Đã dùng</th>
                        <th>Thời hạn</th>
                        <th class="text-center">Trạng thái</th>
                        <th class="text-end" style="width: 120px;">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vouchers as $voucher)
                        <tr>
                            <!-- Code -->
                            <td>
                                <div class="d-flex align-items-center gap-1">
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle font-monospace fs-6 px-2 py-1">
                                        {{ $voucher->code }}
                                    </span>
                                    <button type="button" class="btn btn-sm btn-link text-muted p-0" onclick="navigator.clipboard.writeText('{{ $voucher->code }}'); alert('Đã sao chép mã {{ $voucher->code }}');" title="Sao chép mã">
                                        <i class="fa-regular fa-copy"></i>
                                    </button>
                                </div>
                            </td>

                            <!-- Name -->
                            <td>
                                <div class="fw-semibold text-dark">{{ $voucher->name }}</div>
                            </td>

                            <!-- Value -->
                            <td>
                                <span class="fw-bold text-danger fs-6">{{ $voucher->formatted_value }}</span>
                            </td>

                            <!-- Min Order -->
                            <td>
                                @if($voucher->min_order_amount > 0)
                                    <span>{{ number_format($voucher->min_order_amount, 0, ',', '.') }} ₫</span>
                                @else
                                    <span class="text-muted small">Không giới hạn</span>
                                @endif
                            </td>

                            <!-- Usage count / limit -->
                            <td>
                                <div>
                                    <span class="fw-bold text-dark">{{ $voucher->used_count }}</span>
                                    <span class="text-muted">/ {{ $voucher->usage_limit ?? '∞' }}</span>
                                </div>
                                @if($voucher->usage_limit)
                                    @php
                                        $percent = min(100, round(($voucher->used_count / $voucher->usage_limit) * 100));
                                    @endphp
                                    <div class="progress mt-1" style="height: 4px; width: 80px;">
                                        <div class="progress-bar {{ $percent >= 100 ? 'bg-danger' : 'bg-primary' }}" style="width: {{ $percent }}%;"></div>
                                    </div>
                                @endif
                            </td>

                            <!-- Date range -->
                            <td class="small">
                                @if($voucher->start_date || $voucher->end_date)
                                    <div><i class="fa-regular fa-calendar-check me-1 text-muted"></i>{{ $voucher->start_date ? $voucher->start_date->format('d/m/Y') : 'Từ đầu' }}</div>
                                    <div><i class="fa-regular fa-calendar-xmark me-1 text-muted"></i>{{ $voucher->end_date ? $voucher->end_date->format('d/m/Y') : 'Vô thời hạn' }}</div>
                                @else
                                    <span class="text-muted">Vô thời hạn</span>
                                @endif
                            </td>

                            <!-- Status -->
                            <td class="text-center">
                                @php
                                    $st = $voucher->status_info;
                                @endphp
                                <form action="{{ route('admin.vouchers.toggle', $voucher->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm p-0 border-0" title="Bấm để bật / tắt">
                                        <span class="badge {{ $st['class'] }} rounded-pill px-3 py-1">
                                            {{ $st['label'] }}
                                        </span>
                                    </button>
                                </form>
                            </td>

                            <!-- Actions -->
                            <td class="text-end">
                                <!-- Sửa -->
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-circle me-1" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editVoucherModal"
                                        data-id="{{ $voucher->id }}"
                                        data-code="{{ $voucher->code }}"
                                        data-name="{{ $voucher->name }}"
                                        data-type="{{ $voucher->type }}"
                                        data-value="{{ (int)$voucher->value }}"
                                        data-max-discount="{{ $voucher->max_discount ? (int)$voucher->max_discount : '' }}"
                                        data-min-order="{{ (int)$voucher->min_order_amount }}"
                                        data-usage-limit="{{ $voucher->usage_limit ?? '' }}"
                                        data-start-date="{{ $voucher->start_date ? $voucher->start_date->format('Y-m-d\TH:i') : '' }}"
                                        data-end-date="{{ $voucher->end_date ? $voucher->end_date->format('Y-m-d\TH:i') : '' }}"
                                        data-is-active="{{ $voucher->is_active ? '1' : '0' }}"
                                        data-url="{{ route('admin.vouchers.update', $voucher->id) }}"
                                        title="Chỉnh sửa">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>

                                <!-- Xóa -->
                                <form action="{{ route('admin.vouchers.destroy', $voucher->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa mã giảm giá này?');">
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
                                <i class="fa-solid fa-ticket-simple fs-1 text-secondary opacity-50 d-block mb-3"></i>
                                Không tìm thấy mã giảm giá nào phù hợp.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($vouchers->hasPages())
            <div class="card-footer bg-white py-3">
                <div class="d-flex justify-content-center">
                    {{ $vouchers->links() }}
                </div>
            </div>
        @endif
    </div>

</div>

<!-- Modal 1: Tạo mới Voucher -->
<div class="modal fade" id="createVoucherModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('admin.vouchers.store') }}" method="POST">
                @csrf
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold">Tạo Mã Giảm Giá Mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-3">
                    <div class="row g-3 mb-3">
                        <div class="col-md-5">
                            <label class="form-label fw-semibold">Mã Voucher (Code) <span class="text-danger">*</span></label>
                            <input type="text" name="code" class="form-control text-uppercase font-monospace fw-bold" placeholder="VD: SUMMER30, OWEN100K" required autofocus>
                            <div class="form-text small">Viết liền không dấu, hệ thống tự in hoa.</div>
                        </div>
                        <div class="col-md-7">
                            <label class="form-label fw-semibold">Tên chương trình khuyến mãi <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="VD: Khai xuân giảm 50K cho đơn từ 400K" required>
                        </div>
                    </div>

                    <div class="row g-3 mb-3 p-3 bg-light rounded-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Hình thức giảm <span class="text-danger">*</span></label>
                            <select name="type" id="create_type" class="form-select" required onchange="toggleDiscountFields('create')">
                                <option value="fixed">Giảm số tiền cố định (₫)</option>
                                <option value="percent">Giảm theo tỷ lệ phần trăm (%)</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold" id="create_value_label">Mức giảm (VNĐ) <span class="text-danger">*</span></label>
                            <input type="number" name="value" id="create_value" class="form-control" placeholder="50000" min="1" required>
                        </div>
                        <div class="col-md-4" id="create_max_discount_wrapper" style="display: none;">
                            <label class="form-label fw-semibold">Giảm tối đa (₫)</label>
                            <input type="number" name="max_discount" class="form-control" placeholder="100000" min="0">
                            <div class="form-text small">Chặn mức giảm trần khi dùng %.</div>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Giá trị đơn hàng tối thiểu (₫)</label>
                            <input type="number" name="min_order_amount" class="form-control" value="0" min="0" step="1000">
                            <div class="form-text small">Để 0 nếu áp dụng cho mọi giá trị đơn.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Giới hạn số lượt dùng</label>
                            <input type="number" name="usage_limit" class="form-control" placeholder="Không giới hạn" min="1">
                            <div class="form-text small">Để trống nếu không giới hạn lượt dùng.</div>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Thời gian bắt đầu</label>
                            <input type="datetime-local" name="start_date" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Thời gian kết thúc (Hết hạn)</label>
                            <input type="datetime-local" name="end_date" class="form-control">
                        </div>
                    </div>

                    <div class="form-check form-switch mt-2">
                        <input class="form-check-input" type="checkbox" role="switch" id="create_is_active" name="is_active" value="1" checked>
                        <label class="form-check-label fw-semibold" for="create_is_active">
                            Kích hoạt sử dụng ngay
                        </label>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Lưu voucher</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal 2: Chỉnh sửa Voucher -->
<div class="modal fade" id="editVoucherModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <form id="editVoucherForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold">Chỉnh sửa Voucher</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-3">
                    <div class="row g-3 mb-3">
                        <div class="col-md-5">
                            <label class="form-label fw-semibold">Mã Voucher (Code) <span class="text-danger">*</span></label>
                            <input type="text" name="code" id="edit_code" class="form-control text-uppercase font-monospace fw-bold" required>
                        </div>
                        <div class="col-md-7">
                            <label class="form-label fw-semibold">Tên chương trình khuyến mãi <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="edit_name" class="form-control" required>
                        </div>
                    </div>

                    <div class="row g-3 mb-3 p-3 bg-light rounded-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Hình thức giảm <span class="text-danger">*</span></label>
                            <select name="type" id="edit_type" class="form-select" required onchange="toggleDiscountFields('edit')">
                                <option value="fixed">Giảm số tiền cố định (₫)</option>
                                <option value="percent">Giảm theo tỷ lệ phần trăm (%)</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold" id="edit_value_label">Mức giảm <span class="text-danger">*</span></label>
                            <input type="number" name="value" id="edit_value" class="form-control" min="1" required>
                        </div>
                        <div class="col-md-4" id="edit_max_discount_wrapper" style="display: none;">
                            <label class="form-label fw-semibold">Giảm tối đa (₫)</label>
                            <input type="number" name="max_discount" id="edit_max_discount" class="form-control" min="0">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Đơn hàng tối thiểu (₫)</label>
                            <input type="number" name="min_order_amount" id="edit_min_order" class="form-control" min="0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Giới hạn số lượt dùng</label>
                            <input type="number" name="usage_limit" id="edit_usage_limit" class="form-control" min="1">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Thời gian bắt đầu</label>
                            <input type="datetime-local" name="start_date" id="edit_start_date" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Thời gian kết thúc</label>
                            <input type="datetime-local" name="end_date" id="edit_end_date" class="form-control">
                        </div>
                    </div>

                    <div class="form-check form-switch mt-2">
                        <input class="form-check-input" type="checkbox" role="switch" id="edit_is_active" name="is_active" value="1">
                        <label class="form-check-label fw-semibold" for="edit_is_active">
                            Kích hoạt sử dụng
                        </label>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleDiscountFields(prefix) {
    const typeSelect = document.getElementById(prefix + '_type');
    const valueLabel = document.getElementById(prefix + '_value_label');
    const maxWrapper = document.getElementById(prefix + '_max_discount_wrapper');
    const valueInput = document.getElementById(prefix + '_value');

    if (typeSelect.value === 'percent') {
        valueLabel.innerHTML = 'Tỷ lệ giảm (%) <span class="text-danger">*</span>';
        maxWrapper.style.display = 'block';
        valueInput.max = '100';
    } else {
        valueLabel.innerHTML = 'Mức giảm (VNĐ) <span class="text-danger">*</span>';
        maxWrapper.style.display = 'none';
        valueInput.removeAttribute('max');
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const editModal = document.getElementById('editVoucherModal');
    if (editModal) {
        editModal.addEventListener('show.bs.modal', function (event) {
            const btn = event.relatedTarget;
            document.getElementById('editVoucherForm').action = btn.getAttribute('data-url');
            document.getElementById('edit_code').value = btn.getAttribute('data-code');
            document.getElementById('edit_name').value = btn.getAttribute('data-name');
            document.getElementById('edit_type').value = btn.getAttribute('data-type');
            document.getElementById('edit_value').value = btn.getAttribute('data-value');
            document.getElementById('edit_max_discount').value = btn.getAttribute('data-max-discount');
            document.getElementById('edit_min_order').value = btn.getAttribute('data-min-order');
            document.getElementById('edit_usage_limit').value = btn.getAttribute('data-usage-limit');
            document.getElementById('edit_start_date').value = btn.getAttribute('data-start-date');
            document.getElementById('edit_end_date').value = btn.getAttribute('data-end-date');
            document.getElementById('edit_is_active').checked = (btn.getAttribute('data-is-active') === '1');

            toggleDiscountFields('edit');
        });
    }
});
</script>
@endpush
