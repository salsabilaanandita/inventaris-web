@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="p-4 border-bottom">
                <h5 class="fw-bold mb-1">Laporan Stok</h5>
                <p class="text-muted small mb-3">Ringkasan stok barang dan jumlah yang sedang dipinjam.</p>
                <form method="GET" class="row g-2">
                    <div class="col-md-4"><input type="search" name="search" class="form-control" value="{{ request('search') }}" placeholder="Cari nama barang..."></div>
                    <div class="col-md-3"><select name="category_id" class="form-select"><option value="">Semua kategori</option>@foreach($categories as $category)<option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>{{ $category->name }}</option>@endforeach</select></div>
                    <div class="col-auto"><button class="btn btn-dark"><i class="bi bi-search"></i> Filter</button></div>
                    <div class="col-auto"><a href="{{ route('reports.stock') }}" class="btn btn-outline-secondary">Reset</a></div>
                </form>
            </div>
            <div class="table-responsive"><table class="table table-hover align-middle mb-0"><thead class="bg-light"><tr><th class="px-4 py-3 text-muted small">#</th><th class="py-3 text-muted small">Barang</th><th class="py-3 text-muted small">Kategori</th><th class="py-3 text-muted small text-end">Total</th><th class="py-3 text-muted small text-end">Dipinjam</th><th class="py-3 text-muted small text-end">Rusak</th><th class="py-3 text-muted small text-end">Tersedia</th></tr></thead><tbody>
                @forelse($items as $item)
                    @php $borrowed = $item->active_lending_quantity ?? 0; $available = $item->total - $item->repair - $borrowed; @endphp
                    <tr><td class="px-4 small text-muted">{{ $items->firstItem() + $loop->index }}</td><td class="small fw-semibold">{{ $item->name }}</td><td class="small">{{ $item->category->name ?? '-' }}</td><td class="small text-end">{{ $item->total }}</td><td class="small text-end">{{ $borrowed }}</td><td class="small text-end">{{ $item->repair }}</td><td class="small text-end fw-semibold {{ $available < 1 ? 'text-danger' : 'text-success' }}">{{ $available }}</td></tr>
                @empty<tr><td colspan="7" class="text-center py-4 text-muted small">Data stok tidak ditemukan.</td></tr>@endforelse
            </tbody></table><div class="d-flex justify-content-end p-3">{{ $items->links('pagination::bootstrap-5') }}</div></div>
        </div>
    </div>
</div>
@endsection