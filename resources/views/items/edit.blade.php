@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-transparent border-0 p-4 border-bottom">
                    <h5 class="fw-bold mb-1">Edit Produk</h5>
                    <p class="text-muted small mb-0">Perbarui informasi barang di bawah ini.</p>
                </div>
                
                <div class="card-body p-4">
                    <form action="{{ route('items.update', $item->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.05em;">Nama Barang <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="{{ $item->name }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.05em;">Kategori <span class="text-danger">*</span></label>
                                <select name="category_id" class="form-select select2" required>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ $item->category_id == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.05em;">Lokasi / Ruangan</label>
                                <select name="location_id" class="form-select">
                                    <option value="" disabled {{ is_null($item->location_id) ? 'selected' : '' }}>Pilih Lokasi... (Opsional)</option>
                                    @foreach($locations as $loc)
                                        <option value="{{ $loc->id }}" {{ $item->location_id == $loc->id ? 'selected' : '' }}>
                                            {{ $loc->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.05em;">Satuan</label>
                                <select name="unit_id" class="form-select">
                                    <option value="" disabled {{ is_null($item->unit_id) ? 'selected' : '' }}>Pilih Satuan... (Opsional)</option>
                                    @foreach($units as $unit)
                                        <option value="{{ $unit->id }}" {{ $item->unit_id == $unit->id ? 'selected' : '' }}>
                                            {{ $unit->name }} ({{ $unit->symbol }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.05em;">Supplier Default</label>
                                <select name="supplier_id" class="form-select">
                                    <option value="" disabled {{ is_null($item->supplier_id) ? 'selected' : '' }}>Pilih Supplier... (Opsional)</option>
                                    @foreach($suppliers as $sup)
                                        <option value="{{ $sup->id }}" {{ $item->supplier_id == $sup->id ? 'selected' : '' }}>
                                            {{ $sup->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.05em;">Harga Jual <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">Rp</span>
                                    <input type="text" id="price_display" class="form-control border-start-0 ps-0" value="{{ number_format($item->price, 0, ',', '.') }}" required>
                                    <input type="hidden" name="price" id="price_actual" value="{{ $item->price }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.05em;">Total Stok Fisik <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" name="total" class="form-control border-end-0" value="{{ $item->total }}" required>
                                    <span class="input-group-text bg-light border-start-0 text-muted">pcs</span>
                                </div>
                            </div>

                            <div class="col-md-12 pt-3 border-top mt-4">
                                <label class="form-label fw-semibold text-warning small text-uppercase" style="letter-spacing: 0.05em;">
                                    Laporkan Kerusakan Baru <small class="text-muted text-lowercase text-capitalize">(saat ini: {{ $item->repair }} rusak)</small>
                                </label>
                                <div class="input-group">
                                    <input type="number" name="new_broke_item" class="form-control border-end-0" placeholder="0">
                                    <span class="input-group-text bg-light border-start-0 text-muted">pcs</span>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                            <a href="{{ route('items.index') }}" class="btn btn-light border rounded-pill px-4 shadow-sm text-muted">Batal</a>
                            <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm"><i class="bi bi-save me-1"></i> Update Produk</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const priceDisplay = document.getElementById('price_display');
    const priceActual = document.getElementById('price_actual');

    function formatRupiah(angka) {
        let number_string = angka.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            let separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        return split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
    }

    if(priceDisplay) {
        priceDisplay.addEventListener('keyup', function(e) {
            this.value = formatRupiah(this.value);
            priceActual.value = this.value.replace(/\./g, '');
        });
    }
</script>
@endpush
@endsection