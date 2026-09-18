@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Manajemen Stok</h4>
            <p class="text-muted small mb-0">Perbarui dan pantau ketersediaan stok fisik.</p>
        </div>
        <!-- No add button here typically, stocks are updated inline or via stock-ins -->
    </div>

    @if(session('success'))
        <div class="alert alert-success rounded-pill px-4 shadow-sm border-0">{{ session('success') }}</div>
    @endif

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('stocks.index', ['filter' => 'all']) }}" class="filter-pill {{ $filter == 'all' ? 'active' : '' }}">
                Semua Produk <span class="badge bg-secondary-subtle text-secondary rounded-pill ms-1">{{ $filter == 'all' ? $items->total() : '' }}</span>
            </a>
            <a href="{{ route('stocks.index', ['filter' => 'low']) }}" class="filter-pill {{ $filter == 'low' ? 'active text-warning border-warning' : '' }}">
                Menipis
            </a>
            <a href="{{ route('stocks.index', ['filter' => 'empty']) }}" class="filter-pill {{ $filter == 'empty' ? 'active text-danger border-danger' : '' }}">
                Habis
            </a>
        </div>

        <div class="search-wrapper">
            <i class="bi bi-search"></i>
            <input type="text" class="form-control search-pill" placeholder="Cari barang...">
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table custom-table mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;" class="text-center">
                            <div class="form-check custom-checkbox d-flex justify-content-center m-0">
                                <input class="form-check-input" type="checkbox" id="selectAllStocks">
                            </div>
                        </th>
                        <th><div class="sortable-header">Produk <i class="bi bi-chevron-expand"></i></div></th>
                        <th><div class="sortable-header">Kategori <i class="bi bi-chevron-expand"></i></div></th>
                        <th><div class="sortable-header">Stok Saat Ini <i class="bi bi-chevron-expand"></i></div></th>
                        <th class="text-center"><div class="sortable-header">Status <i class="bi bi-chevron-expand"></i></div></th>
                        @if(Auth::user()->role != 'manager')
                            <th class="text-end px-4">Update Stok Cepat</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                    <tr>
                        <td class="text-center">
                            <div class="form-check custom-checkbox d-flex justify-content-center m-0">
                                <input class="form-check-input row-checkbox" type="checkbox" value="{{ $item->id }}">
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-box text-primary fs-5"></i>
                                <span class="fw-bold text-dark">{{ $item->name }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-secondary-subtle text-secondary rounded-pill px-2 py-1 border border-secondary-subtle">
                                {{ $item->category->name ?? '-' }}
                            </span>
                        </td>
                        <td>
                            <span class="fw-bolder text-dark" style="font-size: 1.1rem;">{{ $item->total }}</span>
                        </td>
                        <td class="text-center">
                            @if($item->total > 5)
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1"><i class="bi bi-check-circle me-1"></i>Aman</span>
                            @elseif($item->total > 0)
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-2 py-1"><i class="bi bi-exclamation-circle me-1"></i>Menipis</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2 py-1"><i class="bi bi-x-circle me-1"></i>Habis</span>
                            @endif
                        </td>
                        @if(Auth::user()->role != 'manager')
                            <td class="px-4">
                                <form action="{{ route('stocks.update', $item->id) }}" method="POST" class="d-flex justify-content-end gap-2 align-items-center m-0">
                                    @csrf
                                    @method('PATCH')
                                    <input type="number" name="total" value="{{ $item->total }}" min="0" class="form-control form-control-sm text-center shadow-sm" style="width: 80px; border-radius: 0.75rem;">
                                    <button type="submit" class="btn btn-sm btn-primary rounded-pill shadow-sm"><i class="bi bi-check2"></i> Simpan</button>
                                </form>
                            </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ Auth::user()->role != 'manager' ? '6' : '5' }}" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                            Tidak ada data stok.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="d-flex justify-content-between align-items-center p-3 border-top">
                <div class="text-muted small d-none d-md-block">
                    <button class="btn btn-sm btn-light border rounded-pill px-3" disabled id="bulkActionBtnStocks">
                        <i class="bi bi-trash me-1"></i> Hapus Terpilih
                    </button>
                </div>
                <div>
                    {{ $items->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAll = document.getElementById('selectAllStocks');
        if (selectAll) {
            const checkboxes = document.querySelectorAll('.row-checkbox');
            const bulkBtn = document.getElementById('bulkActionBtnStocks');

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
