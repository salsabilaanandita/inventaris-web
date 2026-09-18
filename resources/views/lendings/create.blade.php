@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-transparent border-0 p-4 border-bottom">
                    <h5 class="fw-bold mb-1">Catat Peminjaman</h5>
                    <p class="text-muted small mb-0">Form untuk mencatat barang yang dipinjam oleh pihak eksternal/internal.</p>
                </div>
                
                <div class="card-body p-4">
                    @if($errors->has('total'))
                        <div class="alert alert-danger border-0 small rounded-3 mb-4 shadow-sm">
                            <i class="bi bi-exclamation-circle me-1"></i> {{ $errors->first('total') }}
                        </div>
                    @endif

                    <form action="{{ route('lendings.store') }}" method="POST">
                        @csrf
                        <div class="row g-4">
                            <div class="col-12">
                                <label class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.05em;">Nama Peminjam <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Contoh: Budi, Guru Olahraga, dll." required>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold small text-muted text-uppercase border-bottom pb-2 w-100" style="letter-spacing: 0.05em;">Daftar Barang yang Dipinjam</label>
                                
                                @for ($i = 0; $i < $count; $i++)
                                    <div class="item-row p-3 mb-3 bg-light rounded-3 border" style="position: relative;">
                                        @if($count > 1)
                                            <a href="{{ route('lendings.create', ['count' => $count - 1]) }}" class="btn btn-sm btn-outline-danger px-2 py-0" style="position: absolute; top: -10px; right: -10px; background: var(--bg-main); border-radius: 50%; width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; z-index: 10;"><i class="bi bi-x"></i></a>
                                        @endif

                                        <div class="row g-3">
                                            <div class="col-md-8">
                                                <label class="form-label fw-semibold small text-muted mb-1">Pilih Barang</label>
                                                <select name="item_id[]" class="form-select border-0 shadow-sm" required>
                                                    <option value="" selected disabled>Pilih Barang...</option>
                                                    @foreach($items as $item)
                                                        <option value="{{ $item->id }}">{{ $item->name }} (Tersedia: {{ $item->available }})</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold small text-muted mb-1">Jumlah</label>
                                                <input type="number" name="total[]" class="form-control border-0 shadow-sm" placeholder="Qty" required>
                                            </div>
                                        </div>
                                    </div>
                                @endfor
                                
                                <div class="text-end">
                                    <a href="{{ route('lendings.create', ['count' => $count + 1]) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 shadow-sm">
                                        <i class="bi bi-plus-lg me-1"></i> Tambah Barang Lain
                                    </a>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.05em;">Keterangan / Tujuan Peminjaman</label>
                                <textarea name="notes" class="form-control" rows="3" placeholder="Tujuan atau detail tambahan peminjaman">{{ old('notes') }}</textarea>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                            <a href="{{ route('lendings.index') }}" class="btn btn-light border rounded-pill px-4 shadow-sm text-muted">Batal</a>
                            <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm"><i class="bi bi-check-lg me-1"></i> Proses Peminjaman</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection