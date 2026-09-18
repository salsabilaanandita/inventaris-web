@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-transparent border-0 p-4 border-bottom">
                    <h5 class="fw-bold mb-1">Tambah Satuan</h5>
                    <p class="text-muted small mb-0">Tambahkan satuan baru untuk barang inventaris.</p>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('units.store') }}" method="POST">
                        @csrf
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.05em;">Nama Satuan</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Contoh: Pieces, Box" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.05em;">Simbol</label>
                                <input type="text" name="symbol" class="form-control @error('symbol') is-invalid @enderror" value="{{ old('symbol') }}" placeholder="Contoh: pcs, box" required>
                                @error('symbol')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex gap-2 justify-content-end mt-4 pt-3 border-top">
                            <a href="{{ route('units.index') }}" class="btn btn-light border rounded-pill px-4 shadow-sm text-muted">Batal</a>
                            <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm"><i class="bi bi-check-lg me-1"></i> Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection