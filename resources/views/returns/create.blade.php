@extends('layouts.app')
@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-transparent border-0 p-4 border-bottom">
                    <h5 class="fw-bold mb-1">Catat Pengembalian Barang</h5>
                    <p class="text-muted small mb-0">Proses pengembalian dari peminjaman yang sedang aktif.</p>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('returns.store') }}">
                        @csrf
                        <div class="row g-4">
                            <div class="col-md-12">
                                <label class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.05em;">Pilih Peminjaman Aktif <span class="text-danger">*</span></label>
                                <select name="lending_id" class="form-select @error('lending_id') is-invalid @enderror" required>
                                    <option value="" disabled selected>Pilih Transaksi Peminjaman...</option>
                                    @foreach($lendings as $lending)
                                        <option value="{{ $lending->id }}">{{ $lending->name }} - {{ $lending->item->name }} ({{ $lending->total }} dipinjam)</option>
                                    @endforeach
                                </select>
                                @error('lending_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.05em;">Tanggal & Waktu Pengembalian <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="returned_at" value="{{ old('returned_at', now()->format('Y-m-d\TH:i')) }}" class="form-control @error('returned_at') is-invalid @enderror" required>
                                @error('returned_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.05em;">Catatan Pengembalian</label>
                                <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3" placeholder="Misal: Dikembalikan dalam keadaan baik">{{ old('notes') }}</textarea>
                                @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                            <a href="{{ route('returns.index') }}" class="btn btn-light border rounded-pill px-4 shadow-sm text-muted">Batal</a>
                            <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm"><i class="bi bi-check-lg me-1"></i> Proses Pengembalian</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection