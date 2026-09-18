@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Peminjaman</h4>
            <p class="text-muted small mb-0">Kelola data peminjaman inventaris.</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-light rounded-pill px-4 shadow-sm border text-success fw-bold"><i class="bi bi-file-earmark-excel me-1"></i> Export Excel</button>
            @if(in_array(auth()->user()->role, ['super_admin', 'admin_gudang', 'staff_gudang']))
            <a href="{{ route('lendings.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
                <i class="bi bi-plus-lg me-1"></i> Pinjam Barang
            </a>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success rounded-pill px-4 shadow-sm border-0">{{ session('success') }}</div>
    @endif

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('lendings.index', ['search' => request('search')]) }}" class="filter-pill {{ !request('status') ? 'active' : '' }}">
                Semua Data <span class="badge bg-secondary-subtle text-secondary rounded-pill ms-1">{{ $totalLendings }}</span>
            </a>
            <a href="{{ route('lendings.index', ['status' => 'not_returned', 'search' => request('search')]) }}" class="filter-pill text-warning border-warning {{ request('status') == 'not_returned' ? 'active' : '' }}">Belum Dikembalikan</a>
            <a href="{{ route('lendings.index', ['status' => 'returned', 'search' => request('search')]) }}" class="filter-pill text-success border-success {{ request('status') == 'returned' ? 'active' : '' }}">Sudah Dikembalikan</a>
        </div>
        <form action="{{ route('lendings.index') }}" method="GET" class="search-wrapper m-0">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            <i class="bi bi-search"></i>
            <input type="text" name="search" class="form-control search-pill" placeholder="Cari peminjam..." value="{{ request('search') }}">
        </form>
    </div>

    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table custom-table mb-0">
                <thead>
                    <tr>
                        @if(in_array(auth()->user()->role, ['super_admin', 'admin_gudang', 'staff_gudang']))
                        <th style="width: 50px;" class="text-center">
                            <div class="form-check custom-checkbox d-flex justify-content-center m-0">
                                <input class="form-check-input" type="checkbox" id="selectAllLendings">
                            </div>
                        </th>
                        @endif
                        <th><div class="sortable-header"># <i class="bi bi-chevron-expand"></i></div></th>
                        <th><div class="sortable-header">Barang <i class="bi bi-chevron-expand"></i></div></th>
                        <th><div class="sortable-header">Peminjam <i class="bi bi-chevron-expand"></i></div></th>
                        <th><div class="sortable-header">Tanggal <i class="bi bi-chevron-expand"></i></div></th>
                        <th class="text-center"><div class="sortable-header">Status <i class="bi bi-chevron-expand"></i></div></th>
                        <th><div class="sortable-header">Admin <i class="bi bi-chevron-expand"></i></div></th>
                        @if(in_array(auth()->user()->role, ['super_admin', 'admin_gudang', 'staff_gudang']))
                        <th class="text-center">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($lendings as $lending)
                    <tr>
                        @if(in_array(auth()->user()->role, ['super_admin', 'admin_gudang', 'staff_gudang']))
                        <td class="text-center">
                            <div class="form-check custom-checkbox d-flex justify-content-center m-0">
                                <input class="form-check-input row-checkbox" type="checkbox" value="{{ $lending->id }}">
                            </div>
                        </td>
                        @endif
                        <td class="text-muted small">{{ $lendings->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="fw-bold text-dark">{{ $lending->item->name }}</div>
                            <span class="badge bg-secondary-subtle text-secondary rounded-pill px-2 py-1 mt-1">{{ $lending->total }} unit</span>
                        </td>
                        <td>
                            <div class="fw-medium text-dark">{{ $lending->name }}</div>
                            <div class="text-muted small">{{ $lending->notes }}</div>
                        </td>
                        <td class="text-muted small">
                            <i class="bi bi-calendar me-1"></i>{{ $lending->created_at->format('d M Y') }}
                        </td>
                        <td class="text-center">
                            @if($lending->return_date)
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1">
                                    <i class="bi bi-check-circle me-1"></i>Dikembalikan
                                </span><br>
                                <small class="text-muted" style="font-size: 0.65rem;">{{ \Carbon\Carbon::parse($lending->return_date)->format('d M Y') }}</small>
                            @else
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-2 py-1">
                                    <i class="bi bi-clock me-1"></i>Belum Kembali
                                </span>
                            @endif
                        </td>
                        <td class="fw-medium text-dark small"><i class="bi bi-person me-1"></i>{{ $lending->user->name }}</td>
                        @if(in_array(auth()->user()->role, ['super_admin', 'admin_gudang', 'staff_gudang']))
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-1">
                                @if(!$lending->return_date)
                                <form action="{{ route('lendings.return', $lending->id) }}" method="POST" class="d-inline">
                                    @csrf @method('PATCH')
                                    <button class="btn btn-sm btn-success rounded-pill shadow-sm" data-bs-toggle="tooltip" title="Tandai Kembali"><i class="bi bi-check2-all"></i></button>
                                </form>
                                @endif
                                <form action="{{ route('lendings.destroy', $lending->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus peminjaman ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-light border rounded-pill text-danger" data-bs-toggle="tooltip" title="Hapus"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                            Belum ada data peminjaman.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="d-flex justify-content-between align-items-center p-3 border-top">
                @if(in_array(auth()->user()->role, ['super_admin', 'admin_gudang', 'staff_gudang']))
                <div class="text-muted small d-none d-md-block">
                    <button class="btn btn-sm btn-light border rounded-pill px-3" disabled id="bulkActionBtnLendings">
                        <i class="bi bi-trash me-1"></i> Hapus Terpilih
                    </button>
                </div>
                @else
                <div></div>
                @endif
                <div>
                    {{ $lendings->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAll = document.getElementById('selectAllLendings');
        if (selectAll) {
            const checkboxes = document.querySelectorAll('.row-checkbox');
            const bulkBtn = document.getElementById('bulkActionBtnLendings');

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