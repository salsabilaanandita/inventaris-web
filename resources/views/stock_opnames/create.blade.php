@extends('layouts.app')
@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-transparent border-0 p-4 border-bottom">
                    <h5 class="fw-bold mb-1">Tambah Stok Opname</h5>
                    <p class="text-muted small mb-0">Lakukan penyesuaian stok fisik yang ada di gudang.</p>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('stock-opnames.store') }}">
                        @csrf
                        <div class="row g-4">
                            <div class="col-md-12">
                                <label class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.05em;">Barang <span class="text-danger">*</span></label>
                                <select name="item_id" class="form-select @error('item_id') is-invalid @enderror" required>
                                    <option value="" disabled selected>Pilih Barang...</option>
                                    @foreach($items as $item)
                                        <option value="{{ $item->id }}" @selected(old('item_id') == $item->id)>{{ $item->name }} (Sistem: {{ $item->total }})</option>
                                    @endforeach
                                </select>
                                @error('item_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.05em;">Stok Aktual (Fisik) <span class="text-danger">*</span></label>
                                <input type="number" name="actual_stock" min="0" value="{{ old('actual_stock') }}" class="form-control @error('actual_stock') is-invalid @enderror" required placeholder="0">
                                @error('actual_stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.05em;">Tanggal Opname <span class="text-danger">*</span></label>
                                <input type="date" name="opname_date" value="{{ old('opname_date', now()->format('Y-m-d')) }}" class="form-control @error('opname_date') is-invalid @enderror" required>
                                @error('opname_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.05em;">Catatan Penyesuaian</label>
                                <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3" placeholder="Alasan perbedaan stok (opsional)">{{ old('notes') }}</textarea>
                                @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                            <a href="{{ route('stock-opnames.index') }}" class="btn btn-light border rounded-pill px-4 shadow-sm text-muted">Batal</a>
                            <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm"><i class="bi bi-check-lg me-1"></i> Simpan Opname</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection