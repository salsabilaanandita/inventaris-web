@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Manajemen User</h4>
            <p class="text-muted small mb-0">Kelola akun pengguna dan hak akses sistem.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('users.export') }}" class="btn btn-light rounded-pill px-4 shadow-sm border text-success fw-bold"><i class="bi bi-file-earmark-excel me-1"></i> Export Excel</a>
            <a href="{{ route('users.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
                <i class="bi bi-person-plus-fill me-1"></i> Tambah User
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success rounded-pill px-4 shadow-sm border-0">{{ session('success') }}</div>
    @endif

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div class="d-flex flex-wrap gap-2">
            <a href="#" class="filter-pill active">
                Semua User <span class="badge bg-secondary-subtle text-secondary rounded-pill ms-1">{{ $users->total() ?? 0 }}</span>
            </a>
        </div>
        <div class="search-wrapper">
            <i class="bi bi-search"></i>
            <input type="text" class="form-control search-pill" placeholder="Cari user...">
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table custom-table mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;" class="text-center">
                            <div class="form-check custom-checkbox d-flex justify-content-center m-0">
                                <input class="form-check-input" type="checkbox" id="selectAllUsers">
                            </div>
                        </th>
                        <th><div class="sortable-header"># <i class="bi bi-chevron-expand"></i></div></th>
                        <th><div class="sortable-header">Nama <i class="bi bi-chevron-expand"></i></div></th>
                        <th><div class="sortable-header">Email <i class="bi bi-chevron-expand"></i></div></th>
                        <th><div class="sortable-header">Role <i class="bi bi-chevron-expand"></i></div></th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        @php
                            $defaultPass = substr($user->email, 0, 4) . $user->id;
                            $isDefault = Hash::check($defaultPass, $user->password);
                        @endphp
                    <tr>
                        <td class="text-center">
                            <div class="form-check custom-checkbox d-flex justify-content-center m-0">
                                <input class="form-check-input row-checkbox" type="checkbox" value="{{ $user->id }}">
                            </div>
                        </td>
                        <td class="text-muted small">{{ $loop->iteration }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-person-circle text-primary fs-5"></i>
                                <span class="fw-bold text-dark">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td class="text-muted small"><i class="bi bi-envelope me-1"></i>{{ $user->email }}</td>
                        <td>
                            <span class="badge bg-secondary-subtle text-secondary rounded-pill px-2 py-1 border border-secondary-subtle">
                                {{ strtoupper($user->role ?? 'User') }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-1">
                                @if($isDefault)
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-light border rounded-pill text-primary" data-bs-toggle="tooltip" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                @else
                                    <form action="{{ route('users.reset', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Reset password user ini?')">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-sm btn-light border rounded-pill text-warning" data-bs-toggle="tooltip" title="Reset Password">
                                            <i class="bi bi-key"></i>
                                        </button>
                                    </form>
                                @endif
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus user ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light border rounded-pill text-danger" data-bs-toggle="tooltip" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-people fs-1 d-block mb-3"></i>
                            Belum ada data user.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="d-flex justify-content-between align-items-center p-3 border-top">
                <div class="text-muted small d-none d-md-block">
                    <button class="btn btn-sm btn-light border rounded-pill px-3" disabled id="bulkActionBtnUsers">
                        <i class="bi bi-trash me-1"></i> Hapus Terpilih
                    </button>
                </div>
                <div>
                    {{-- Pagination (if paginated) --}}
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAll = document.getElementById('selectAllUsers');
        if (selectAll) {
            const checkboxes = document.querySelectorAll('.row-checkbox');
            const bulkBtn = document.getElementById('bulkActionBtnUsers');

            function updateBulkBtn() {
                const checkedCount = document.querySelectorAll('.row-checkbox:checked').length;
                bulkBtn.disabled = checkedCount === 0;
            }

            selectAll.addEventListener('change', function() {
                checkboxes.forEach(cb => cb.checked = selectAll.checked);
                updateBulkBtn();
            });

            checkboxes.forEach(cb => {
                cb.addEventListener('change', function() {
                    const allChecked = document.querySelectorAll('.row-checkbox:checked').length === checkboxes.length;
                    selectAll.checked = allChecked;
                    updateBulkBtn();
                });
            });
        }
    });
</script>
@endpush
@endsection