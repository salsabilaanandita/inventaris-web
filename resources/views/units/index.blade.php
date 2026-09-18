@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Satuan</h4>
            <p class="text-muted small mb-0">Kelola satuan barang inventaris.</p>
        </div>
        <a href="{{ route('units.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
            <i class="bi bi-plus-lg me-1"></i> Tambah Satuan
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success rounded-pill px-4 shadow-sm border-0">{{ session('success') }}</div>
    @endif

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div class="d-flex flex-wrap gap-2">
            <a href="#" class="filter-pill active">
                Semua Satuan <span class="badge bg-secondary-subtle text-secondary rounded-pill ms-1">{{ $units->total() }}</span>
            </a>
        </div>
        <div class="search-wrapper">
            <i class="bi bi-search"></i>
            <input type="text" class="form-control search-pill" placeholder="Cari satuan...">
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table custom-table mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;" class="text-center">
                            <div class="form-check custom-checkbox d-flex justify-content-center m-0">
                                <input class="form-check-input" type="checkbox" id="selectAllUnits">
                            </div>
                        </th>
                        <th><div class="sortable-header"># <i class="bi bi-chevron-expand"></i></div></th>
                        <th><div class="sortable-header">Nama Satuan <i class="bi bi-chevron-expand"></i></div></th>
                        <th><div class="sortable-header">Simbol <i class="bi bi-chevron-expand"></i></div></th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($units as $unit)
                    <tr>
                        <td class="text-center">
                            <div class="form-check custom-checkbox d-flex justify-content-center m-0">
                                <input class="form-check-input row-checkbox" type="checkbox" value="{{ $unit->id }}">
                            </div>
                        </td>
                        <td class="text-muted small">{{ $units->firstItem() + $loop->index }}</td>
                        <td class="fw-bold text-dark">
                            <i class="bi bi-rulers text-muted me-2"></i>{{ $unit->name }}
                        </td>
                        <td>
                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-2 py-1">
                                {{ $unit->symbol }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('units.edit', $unit) }}" class="btn btn-sm btn-light border rounded-pill text-primary" data-bs-toggle="tooltip" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('units.destroy', $unit) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus satuan ini?')">
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
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                            Belum ada satuan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="d-flex justify-content-between align-items-center p-3 border-top">
                <div class="text-muted small d-none d-md-block">
                    <button class="btn btn-sm btn-light border rounded-pill px-3" disabled id="bulkActionBtnUnits">
                        <i class="bi bi-trash me-1"></i> Hapus Terpilih
                    </button>
                </div>
                <div>
                    {{ $units->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAll = document.getElementById('selectAllUnits');
        if (selectAll) {
            const checkboxes = document.querySelectorAll('.row-checkbox');
            const bulkBtn = document.getElementById('bulkActionBtnUnits');

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