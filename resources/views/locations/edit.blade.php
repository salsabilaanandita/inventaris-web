@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-transparent border-0 p-4 border-bottom">
                    <h5 class="fw-bold mb-1">Edit Lokasi / Ruangan</h5>
                    <p class="text-muted small mb-0">Perbarui informasi lokasi penyimpanan barang.</p>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('locations.update', $location) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row g-4">
                            <div class="col-12">
                                <label class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.05em;">Nama Lokasi</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $location->name) }}" placeholder="Contoh: Gudang Utama, Ruang IT" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.05em;">Deskripsi</label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4" placeholder="Detail lokasi (opsional)">{{ old('description', $location->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex gap-2 justify-content-end mt-4 pt-3 border-top">
                            <a href="{{ route('locations.index') }}" class="btn btn-light border rounded-pill px-4 shadow-sm text-muted">Batal</a>
                            <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm"><i class="bi bi-save me-1"></i> Perbarui</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection