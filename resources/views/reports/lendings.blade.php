@extends('layouts.app')

@section('content')
<div class="container-fluid py-4"><div class="card shadow-sm border-0"><div class="card-body p-0">
    <div class="p-4 border-bottom">
        <h5 class="fw-bold mb-1">Laporan Peminjaman</h5>
        <p class="text-muted small mb-3">Riwayat peminjaman dan status pengembalian barang.</p>
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-2">
                <label class="small text-muted mb-1">Tanggal Mulai</label>
                <input type="date" name="from" class="form-control" value="{{ request('from') }}">
            </div>
            <div class="col-md-2">
                <label class="small text-muted mb-1">Tanggal Akhir</label>
                <input type="date" name="to" class="form-control" value="{{ request('to') }}">
            </div>
            <div class="col-md-3">
                <label class="small text-muted mb-1">Status Pengembalian</label>
                <select name="status" class="form-select">
                    <option value="">Semua status</option>
                    <option value="active" @selected(request('status') === 'active')>Belum kembali</option>
                    <option value="returned" @selected(request('status') === 'returned')>Sudah kembali</option>
                </select>
            </div>
            <div class="col-auto">
                <button class="btn btn-dark"><i class="bi bi-search"></i> Filter</button>
            </div>
            <div class="col-auto">
                <a href="{{ route('reports.lendings') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
    <div class="table-responsive"><table class="table table-hover align-middle mb-0"><thead class="bg-light"><tr><th class="px-4 py-3 text-muted small">Tanggal</th><th class="py-3 text-muted small">Peminjam</th><th class="py-3 text-muted small">Barang</th><th class="py-3 text-muted small text-end">Jumlah</th><th class="py-3 text-muted small">Status</th><th class="py-3 text-muted small">Tanggal Kembali</th></tr></thead><tbody>
        @forelse($lendings as $lending)<tr><td class="px-4 small">{{ $lending->created_at->format('d/m/Y H:i') }}</td><td class="small">{{ $lending->name }}</td><td class="small fw-semibold">{{ $lending->item->name ?? '-' }}</td><td class="small text-end">{{ $lending->total }}</td><td><span class="badge {{ $lending->return_date ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning-emphasis' }}">{{ $lending->return_date ? 'Sudah kembali' : 'Belum kembali' }}</span></td><td class="small">{{ $lending->return_date?->format('d/m/Y H:i') ?? '-' }}</td></tr>
        @empty<tr><td colspan="6" class="text-center py-4 text-muted small">Data peminjaman tidak ditemukan.</td></tr>@endforelse
    </tbody></table><div class="d-flex justify-content-end p-3">{{ $lendings->links('pagination::bootstrap-5') }}</div></div>
</div></div></div>
@endsection