@extends('layouts.app')
@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-transparent border-0 p-4 border-bottom">
                    <h5 class="fw-bold mb-1">Tambah Maintenance</h5>
                    <p class="text-muted small mb-0">Catat perbaikan atau servis untuk barang inventaris.</p>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('maintenances.store') }}">
                        @csrf
                        <div class="row g-4">
                            <div class="col-md-12">
                                <label class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.05em;">Pilih Barang <span class="text-danger">*</span></label>
                                <select name="item_id" class="form-select @error('item_id') is-invalid @enderror" required>
                                    <option value="" disabled selected>Pilih Barang yang Diservis...</option>
                                    @foreach($items as $item)
                                        <option value="{{ $item->id }}" @selected(old('item_id') == $item->id)>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                                @error('item_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.05em;">Judul Perbaikan <span class="text-danger">*</span></label>
                                <input name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" placeholder="Contoh: Servis Rutin AC, Ganti Komponen" required>
                                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.05em;">Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                    <option value="open" @selected(old('status') == 'open')>Open (Baru)</option>
                                    <option value="process" @selected(old('status') == 'process')>Process (Dikerjakan)</option>
                                    <option value="done" @selected(old('status') == 'done')>Done (Selesai)</option>
                                </select>
                                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.05em;">Biaya Servis</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">Rp</span>
                                    <input type="number" name="cost" min="0" value="{{ old('cost', 0) }}" class="form-control border-start-0 ps-0 @error('cost') is-invalid @enderror">
                                </div>
                                @error('cost')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.05em;">Tanggal Mulai <span class="text-danger">*</span></label>
                                <input type="date" name="started_at" value="{{ old('started_at', now()->format('Y-m-d')) }}" class="form-control @error('started_at') is-invalid @enderror" required>
                                @error('started_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.05em;">Tanggal Selesai</label>
                                <input type="date" name="completed_at" value="{{ old('completed_at') }}" class="form-control @error('completed_at') is-invalid @enderror" placeholder="Biarkan kosong jika belum selesai">
                                <small class="text-muted" style="font-size: 0.7rem;">Kosongkan jika perbaikan masih berlangsung.</small>
                                @error('completed_at')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.05em;">Catatan / Hasil</label>
                                <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3" placeholder="Detail hasil servis atau perbaikan yang dilakukan">{{ old('notes') }}</textarea>
                                @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                            <a href="{{ route('maintenances.index') }}" class="btn btn-light border rounded-pill px-4 shadow-sm text-muted">Batal</a>
                            <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm"><i class="bi bi-check-lg me-1"></i> Simpan Maintenance</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection