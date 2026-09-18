@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Riwayat Stok</h4>
            <p class="text-muted small mb-0">Seluruh perubahan stok yang tercatat.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success rounded-pill px-4 shadow-sm border-0">{{ session('success') }}</div>
    @endif

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div class="d-flex flex-wrap gap-2">
            <a href="#" class="filter-pill active">
                Semua Data <span class="badge bg-secondary-subtle text-secondary rounded-pill ms-1">{{ $histories->total() }}</span>
            </a>
        </div>
        <div class="search-wrapper">
            <i class="bi bi-search"></i>
            <input type="text" class="form-control search-pill" placeholder="Cari riwayat...">
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table custom-table mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;" class="text-center">
                            <div class="form-check custom-checkbox d-flex justify-content-center m-0">
                                <input class="form-check-input" type="checkbox" id="selectAllHistories">
                            </div>
                        </th>
                        <th><div class="sortable-header">Waktu <i class="bi bi-chevron-expand"></i></div></th>
                        <th><div class="sortable-header">Barang <i class="bi bi-chevron-expand"></i></div></th>
                        <th><div class="sortable-header">Jenis <i class="bi bi-chevron-expand"></i></div></th>
                        <th><div class="sortable-header">Jumlah <i class="bi bi-chevron-expand"></i></div></th>
                        <th><div class="sortable-header">Catatan <i class="bi bi-chevron-expand"></i></div></th>
                        <th><div class="sortable-header">Oleh <i class="bi bi-chevron-expand"></i></div></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($histories as $history)
                    <tr>
                        <td class="text-center">
                            <div class="form-check custom-checkbox d-flex justify-content-center m-0">
                                <input class="form-check-input row-checkbox" type="checkbox" value="{{ $history->id }}">
                            </div>
                        </td>
                        <td class="text-muted small">
                            <i class="bi bi-clock me-1"></i>{{ $history->transaction_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="fw-bold text-dark">
                            {{ $history->item->name }}
                        </td>
                        <td>
                            <span class="badge bg-light text-secondary border rounded-pill px-2 py-1">
                                {{ str_replace('_', ' ', ucfirst($history->type)) }}
                            </span>
                        </td>
                        <td>
                            @if($history->quantity > 0)
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1">+{{ $history->quantity }}</span>
                            @elseif($history->quantity < 0)
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2 py-1">{{ $history->quantity }}</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-2 py-1">0</span>
                            @endif
                        </td>
                        <td class="text-muted small">{{ $history->notes ?: '-' }}</td>
                        <td class="text-muted small fw-medium">
                            <i class="bi bi-person me-1"></i>{{ $history->user->name ?? '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                            Belum ada riwayat stok.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="d-flex justify-content-between align-items-center p-3 border-top">
                <div class="text-muted small d-none d-md-block">
                    <button class="btn btn-sm btn-light border rounded-pill px-3" disabled id="bulkActionBtnHistories">
                        <i class="bi bi-trash me-1"></i> Hapus Terpilih
                    </button>
                </div>
                <div>
                    {{ $histories->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAll = document.getElementById('selectAllHistories');
        if (selectAll) {
            const checkboxes = document.querySelectorAll('.row-checkbox');
            const bulkBtn = document.getElementById('bulkActionBtnHistories');

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