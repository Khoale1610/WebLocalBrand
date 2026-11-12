@extends('admin.layouts.app')

@section('title', 'Quản lý Khách hàng & Thành viên')
@section('page_title', 'Khách hàng')

@section('content')
<div class="container-fluid px-0">

    <!-- Header Actions -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold text-dark mb-1">Quản lý Khách hàng & Thành viên</h3>
            <p class="text-muted small mb-0">Theo dõi hồ sơ khách hàng, lịch sử mua sắm và tổng chi tiêu tích lũy.</p>
        </div>
    </div>

    <!-- Filters Bar -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.users.index') }}" method="GET" class="row g-2 align-items-center">
                <!-- Search Input -->
                <div class="col-12 col-md-5 col-lg-4">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                        <input type="text" name="search" class="form-control bg-light border-start-0" placeholder="Tìm kiếm tên, email, SĐT..." value="{{ request('search') }}">
                    </div>
                </div>

                <!-- Role Filter -->
                <div class="col-6 col-md-3 col-lg-3">
                    <select name="role" class="form-select bg-light">
                        <option value="">-- Tất cả vai trò --</option>
                        <option value="customer" {{ request('role') === 'customer' ? 'selected' : '' }}>Khách hàng (Customer)</option>
                        <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Quản trị viên (Admin)</option>
                    </select>
                </div>

                <!-- Sort Filter -->
                <div class="col-6 col-md-4 col-lg-3">
                    <select name="sort" class="form-select bg-light">
                        <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Đăng ký mới nhất</option>
                        <option value="spending_desc" {{ request('sort') === 'spending_desc' ? 'selected' : '' }}>Chi tiêu nhiều nhất (VIP)</option>
                        <option value="orders_desc" {{ request('sort') === 'orders_desc' ? 'selected' : '' }}>Số lượng đơn nhiều nhất</option>
                    </select>
                </div>

                <!-- Submit & Clear -->
                <div class="col-12 col-lg-2 d-flex gap-1">
                    <button type="submit" class="btn btn-dark w-100" title="Lọc">
                        <i class="fa-solid fa-filter me-1"></i>Lọc
                    </button>
                    @if(request()->hasAny(['search', 'role', 'sort']))
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary" title="Xóa lọc">
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Users Data Table -->
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size: 0.875rem;">
                <thead class="table-light text-muted small text-uppercase">
                    <tr>
                        <th style="width: 60px;">ID</th>
                        <th>Khách hàng</th>
                        <th>Số điện thoại</th>
                        <th>Địa chỉ</th>
                        <th class="text-center">Vai trò</th>
                        <th class="text-center">Số đơn</th>
                        <th>Tổng chi tiêu</th>
                        <th>Ngày tham gia</th>
                        <th class="text-end" style="width: 80px;">Hồ sơ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td class="text-muted fw-bold">#{{ $user->id }}</td>
                            
                            <!-- Khách hàng -->
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold shadow-xs" style="width: 36px; height: 36px; font-size: 14px;">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $user->name }}</div>
                                        <div class="small text-muted">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>

                            <!-- SĐT -->
                            <td>
                                @if($user->phone)
                                    <a href="tel:{{ $user->phone }}" class="text-decoration-none text-dark">
                                        <i class="fa-solid fa-phone me-1 small text-muted"></i>{{ $user->phone }}
                                    </a>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>

                            <!-- Địa chỉ -->
                            <td>
                                <div class="text-muted small text-truncate" style="max-width: 220px;" title="{{ $user->address }}">
                                    {{ $user->address ?: 'Chưa cập nhật' }}
                                </div>
                            </td>

                            <!-- Vai trò -->
                            <td class="text-center">
                                @if($user->role === 'admin')
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 rounded-pill">
                                        <i class="fa-solid fa-shield-halved me-1"></i>Admin
                                    </span>
                                @else
                                    <span class="badge bg-light text-dark border px-2 py-1 rounded-pill">
                                        Khách hàng
                                    </span>
                                @endif
                            </td>

                            <!-- Số đơn đặt -->
                            <td class="text-center">
                                <span class="badge {{ $user->orders_count > 0 ? 'bg-primary-subtle text-primary border border-primary-subtle' : 'bg-light text-muted' }} px-3 py-1 rounded-pill">
                                    {{ $user->orders_count }} đơn
                                </span>
                            </td>

                            <!-- Tổng chi tiêu -->
                            <td>
                                <span class="fw-bold {{ ($user->orders_sum_total_amount ?? 0) > 0 ? 'text-danger' : 'text-muted' }}">
                                    {{ number_format($user->orders_sum_total_amount ?? 0, 0, ',', '.') }} ₫
                                </span>
                            </td>

                            <!-- Ngày đăng ký -->
                            <td class="text-muted small">
                                {{ $user->created_at ? $user->created_at->format('d/m/Y') : '—' }}
                            </td>

                            <!-- Action -->
                            <td class="text-end">
                                <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-outline-primary rounded-circle" title="Xem hồ sơ chi tiết">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-users fs-1 text-secondary opacity-50 d-block mb-3"></i>
                                Không tìm thấy khách hàng nào phù hợp với bộ lọc.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="card-footer bg-white py-3">
                <div class="d-flex justify-content-center">
                    {{ $users->links() }}
                </div>
            </div>
        @endif
    </div>

</div>
@endsection
