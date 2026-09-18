@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Daftar Produk</h4>
            <p class="text-muted small mb-0">Kelola data inventaris dan pantau stok barang.</p>
        </div>
        <div class="d-flex gap-2">
            @if(in_array(Auth::user()->role, ['super_admin', 'admin_gudang']))
                <a href="{{ route('items.export') }}" class="btn btn-light rounded-pill px-4 shadow-sm border text-success fw-bold"><i class="bi bi-file-earmark-excel me-1"></i> Export</a>
                <a href="{{ route('items.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm"><i class="bi bi-plus-lg me-1"></i> Tambah Barang</a>
            @endif
        </div>
    </div>

    <!-- Toolbar: Filters & Search -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div class="d-flex flex-wrap gap-2">
            <!-- Filter Pills -->
            <a href="{{ route('items.index', array_merge(request()->query(), ['filter' => 'all'])) }}" class="filter-pill {{ $filter === 'all' ? 'active' : '' }}">
                Semua Produk <span class="badge bg-secondary-subtle text-secondary rounded-pill ms-1">{{ $totalItems }}</span>
            </a>
            <a href="{{ route('items.index', array_merge(request()->query(), ['filter' => 'available'])) }}" class="filter-pill {{ $filter === 'available' ? 'active' : '' }}">
                Tersedia
            </a>
            <a href="{{ route('items.index', array_merge(request()->query(), ['filter' => 'low_stock'])) }}" class="filter-pill {{ $filter === 'low_stock' ? 'active' : '' }}">
                Stok Menipis
            </a>
            <a href="{{ route('items.index', array_merge(request()->query(), ['filter' => 'repair'])) }}" class="filter-pill {{ $filter === 'repair' ? 'active' : '' }}">
                Dalam Perbaikan
            </a>
        </div>

        <div class="search-wrapper">
            <form method="GET" action="{{ route('items.index') }}" class="m-0">
                @if(request('filter'))
                    <input type="hidden" name="filter" value="{{ request('filter') }}">
                @endif
                <i class="bi bi-search"></i>
                <input type="text" name="search" class="form-control search-pill" placeholder="Cari nama barang..." value="{{ request('search') }}">
            </form>
        </div>
    </div>

    <!-- Data Table Card -->
    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table custom-table mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;" class="text-center">
                            <div class="form-check custom-checkbox d-flex justify-content-center m-0">
                                <input class="form-check-input" type="checkbox" id="selectAllItems">
                            </div>
                        </th>
                        <th><div class="sortable-header">Barang <i class="bi bi-chevron-expand"></i></div></th>
                        <th><div class="sortable-header">Kategori <i class="bi bi-chevron-expand"></i></div></th>
                        <th><div class="sortable-header">Lokasi <i class="bi bi-chevron-expand"></i></div></th>
                        <th><div class="sortable-header">Harga <i class="bi bi-chevron-expand"></i></div></th>
                        <th><div class="sortable-header">Total Stok <i class="bi bi-chevron-expand"></i></div></th>
                        <th><div class="sortable-header">Tersedia <i class="bi bi-chevron-expand"></i></div></th>
                        <th><div class="sortable-header">Perbaikan <i class="bi bi-chevron-expand"></i></div></th>
                        <th><div class="sortable-header">Dipinjam <i class="bi bi-chevron-expand"></i></div></th>
                        @if(in_array(Auth::user()->role, ['super_admin', 'admin_gudang']))
                            <th class="text-center">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        @php 
                            $totalLending = $item->lendings->sum('total');
                            $available = $item->total - $item->repair - $totalLending;
                        @endphp
                        <tr>
                            <td class="text-center">
                                <div class="form-check custom-checkbox d-flex justify-content-center m-0">
                                    <input class="form-check-input item-checkbox" type="checkbox" value="{{ $item->id }}">
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-box-seam text-primary fs-5"></i>
                                    <span class="fw-bold text-dark">{{ $item->name }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-secondary-subtle text-secondary rounded-pill px-2 py-1 border border-secondary-subtle">
                                    {{ $item->category->name ?? '-' }}
                                </span>
                            </td>
                            <td class="text-muted small">
                                <i class="bi bi-geo-alt me-1"></i>{{ $item->location->name ?? '-' }}
                            </td>
                            <td class="fw-medium text-dark">
                                Rp {{ number_format($item->price, 0, ',', '.') }}
                            </td>
                            <td>
                                <div class="d-flex align-items-baseline gap-1">
                                    <span class="fw-bolder text-dark" style="font-size: 1.1rem;">{{ $item->total }}</span>
                                    <span class="badge bg-light text-secondary border px-2 py-1 rounded-2" style="font-size: 0.65rem;">{{ $item->unit->symbol ?? 'pcs' }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1 fw-semibold">
                                    <i class="bi bi-check-circle-fill me-1"></i>{{ $available }}
                                </span>
                            </td>
                            <td>
                                @if($item->repair > 0)
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2 py-1 fw-semibold">
                                        <i class="bi bi-wrench-adjustable-circle-fill me-1"></i>{{ $item->repair }}
                                    </span>
                                @else
                                    <span class="text-muted small px-2">-</span>
                                @endif
                            </td>
                            <td>
                                @if($totalLending > 0)
                                    <a href="{{ route('items.lending.detail', $item->id) }}" class="badge bg-warning-subtle text-dark border border-warning-subtle rounded-pill px-2 py-1 text-decoration-none fw-semibold" data-bs-toggle="tooltip" title="Lihat Peminjam">
                                        <i class="bi bi-people-fill me-1 text-warning"></i>{{ $totalLending }}
                                    </a>
                                @else
                                    <span class="text-muted small px-2">-</span>
                                @endif
                            </td>
                            @if(in_array(Auth::user()->role, ['super_admin', 'admin_gudang']))
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        <a href="{{ route('items.edit', $item->id) }}" class="btn btn-sm btn-light border rounded-pill text-primary" data-bs-toggle="tooltip" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('items.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus barang ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-light border rounded-pill text-danger" data-bs-toggle="tooltip" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ in_array(Auth::user()->role, ['super_admin', 'admin_gudang']) ? '10' : '9' }}" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                Belum ada data barang.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            
            <div class="d-flex justify-content-between align-items-center p-3 border-top">
                <div class="text-muted small d-none d-md-block">
                    <button class="btn btn-sm btn-light border rounded-pill px-3" disabled id="bulkActionBtnItems">
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
        const selectAll = document.getElementById('selectAllItems');
        if (selectAll) {
            const checkboxes = document.querySelectorAll('.item-checkbox');
            const bulkBtn = document.getElementById('bulkActionBtnItems');

            function updateBulkBtn() {
                const checkedCount = document.querySelectorAll('.item-checkbox:checked').length;
                bulkBtn.disabled = checkedCount === 0;
            }

            selectAll.addEventListener('change', function() {
                checkboxes.forEach(cb => cb.checked = selectAll.checked);
                updateBulkBtn();
            });

            checkboxes.forEach(cb => {
                cb.addEventListener('change', function() {
                    const allChecked = document.querySelectorAll('.item-checkbox:checked').length === checkboxes.length;
                    selectAll.checked = allChecked;
                    updateBulkBtn();
                });
            });
        }
    });
</script>
@endpush
@endsection