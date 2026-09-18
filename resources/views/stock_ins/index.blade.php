@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Stok Masuk</h4>
            <p class="text-muted small mb-0">Catat penambahan stok barang.</p>
        </div>
        <a href="{{ route('stock-ins.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
            <i class="bi bi-plus-lg me-1"></i> Tambah Stok Masuk
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success rounded-pill px-4 shadow-sm border-0">{{ session('success') }}</div>
    @endif

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div class="d-flex flex-wrap gap-2">
            <a href="#" class="filter-pill active">
                Semua Data <span class="badge bg-secondary-subtle text-secondary rounded-pill ms-1">{{ $stockIns->total() }}</span>
            </a>
        </div>
        <div class="search-wrapper">
            <i class="bi bi-search"></i>
            <input type="text" class="form-control search-pill" placeholder="Cari stok masuk...">
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table custom-table mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;" class="text-center">
                            <div class="form-check custom-checkbox d-flex justify-content-center m-0">
                                <input class="form-check-input" type="checkbox" id="selectAllStockIns">
                            </div>
                        </th>
                        <th><div class="sortable-header">Tanggal <i class="bi bi-chevron-expand"></i></div></th>
                        <th><div class="sortable-header">Barang <i class="bi bi-chevron-expand"></i></div></th>
                        <th><div class="sortable-header">Jumlah <i class="bi bi-chevron-expand"></i></div></th>
                        <th><div class="sortable-header">Keterangan <i class="bi bi-chevron-expand"></i></div></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stockIns as $stockIn)
                    <tr>
                        <td class="text-center">
                            <div class="form-check custom-checkbox d-flex justify-content-center m-0">
                                <input class="form-check-input row-checkbox" type="checkbox" value="{{ $stockIn->id }}">
                            </div>
                        </td>
                        <td class="text-muted small">
                            <i class="bi bi-calendar me-1"></i>{{ $stockIn->received_at->format('d/m/Y') }}
                        </td>
                        <td class="fw-bold text-dark">
                            {{ $stockIn->item->name }}
                        </td>
                        <td>
                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1">
                                +{{ $stockIn->quantity }}
                            </span>
                        </td>
                        <td class="text-muted small">{{ $stockIn->notes ?: '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                            Belum ada stok masuk.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="d-flex justify-content-between align-items-center p-3 border-top">
                <div class="text-muted small d-none d-md-block">
                    <button class="btn btn-sm btn-light border rounded-pill px-3" disabled id="bulkActionBtnStockIns">
                        <i class="bi bi-trash me-1"></i> Hapus Terpilih
                    </button>
                </div>
                <div>
                    {{ $stockIns->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAll = document.getElementById('selectAllStockIns');
        if (selectAll) {
            const checkboxes = document.querySelectorAll('.row-checkbox');
            const bulkBtn = document.getElementById('bulkActionBtnStockIns');

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