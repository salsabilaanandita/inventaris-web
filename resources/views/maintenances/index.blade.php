@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Maintenance</h4>
            <p class="text-muted small mb-0">Kelola perawatan barang inventaris.</p>
        </div>
        <a href="{{ route('maintenances.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
            <i class="bi bi-tools me-1"></i> Buat Laporan Maintenance
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success rounded-pill px-4 shadow-sm border-0">{{ session('success') }}</div>
    @endif

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div class="d-flex flex-wrap gap-2">
            <a href="#" class="filter-pill active">
                Semua Data <span class="badge bg-secondary-subtle text-secondary rounded-pill ms-1">{{ $maintenances->total() }}</span>
            </a>
            <a href="#" class="filter-pill text-warning border-warning">Proses</a>
            <a href="#" class="filter-pill text-success border-success">Selesai</a>
        </div>
        <div class="search-wrapper">
            <i class="bi bi-search"></i>
            <input type="text" class="form-control search-pill" placeholder="Cari maintenance...">
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table custom-table mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;" class="text-center">
                            <div class="form-check custom-checkbox d-flex justify-content-center m-0">
                                <input class="form-check-input" type="checkbox" id="selectAllMaintenances">
                            </div>
                        </th>
                        <th><div class="sortable-header">Mulai <i class="bi bi-chevron-expand"></i></div></th>
                        <th><div class="sortable-header">Barang <i class="bi bi-chevron-expand"></i></div></th>
                        <th><div class="sortable-header">Perbaikan <i class="bi bi-chevron-expand"></i></div></th>
                        <th><div class="sortable-header">Status <i class="bi bi-chevron-expand"></i></div></th>
                        <th><div class="sortable-header">Biaya <i class="bi bi-chevron-expand"></i></div></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($maintenances as $maintenance)
                    <tr>
                        <td class="text-center">
                            <div class="form-check custom-checkbox d-flex justify-content-center m-0">
                                <input class="form-check-input row-checkbox" type="checkbox" value="{{ $maintenance->id }}">
                            </div>
                        </td>
                        <td class="text-muted small">
                            <i class="bi bi-calendar me-1"></i>{{ $maintenance->started_at->format('d/m/Y') }}
                        </td>
                        <td class="fw-bold text-dark">{{ $maintenance->item->name }}</td>
                        <td class="text-muted small">{{ $maintenance->title }}</td>
                        <td>
                            @if($maintenance->status === 'done')
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1"><i class="bi bi-check-circle me-1"></i>Selesai</span>
                            @elseif($maintenance->status === 'process')
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-2 py-1"><i class="bi bi-tools me-1"></i>Proses</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-2 py-1">{{ ucfirst($maintenance->status) }}</span>
                            @endif
                        </td>
                        <td class="fw-medium text-dark">
                            Rp {{ number_format($maintenance->cost, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                            Belum ada maintenance.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="d-flex justify-content-between align-items-center p-3 border-top">
                <div class="text-muted small d-none d-md-block">
                    <button class="btn btn-sm btn-light border rounded-pill px-3" disabled id="bulkActionBtnMaintenances">
                        <i class="bi bi-trash me-1"></i> Hapus Terpilih
                    </button>
                </div>
                <div>
                    {{ $maintenances->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAll = document.getElementById('selectAllMaintenances');
        if (selectAll) {
            const checkboxes = document.querySelectorAll('.row-checkbox');
            const bulkBtn = document.getElementById('bulkActionBtnMaintenances');

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