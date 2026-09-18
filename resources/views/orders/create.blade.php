@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-transparent border-0 p-4 border-bottom">
                    <h5 class="fw-bold mb-1">Buat Pesanan Baru</h5>
                    <p class="text-muted small mb-0">Catat transaksi penjualan atau pemesanan barang dari pelanggan.</p>
                </div>
                
                <div class="card-body p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger rounded-3 shadow-sm mb-4 border-0">
                            <ul class="mb-0 small">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('orders.store') }}" method="POST">
                        @csrf
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.05em;">Pelanggan Terdaftar</label>
                                <select name="customer_id" class="form-select border-0 shadow-sm bg-light">
                                    <option value="" selected>-- Bukan pelanggan member --</option>
                                    @foreach($customers as $c)
                                        <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->phone }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.05em;">Atau Nama Pelanggan Umum <span class="text-danger">*</span></label>
                                <input type="text" name="customer_name" class="form-control" placeholder="Tulis nama jika tidak ada di pilihan kiri">
                                <small class="text-muted" style="font-size: 0.7rem;">Diutamakan jika pelanggan tidak terdaftar di sistem.</small>
                            </div>
                        </div>
                        
                        <div class="mt-4 pt-3 border-top">
                            <h6 class="fw-bold mb-3 small text-uppercase text-muted" style="letter-spacing: 0.05em;">Daftar Produk yang Dipesan</h6>
                            <div id="product-list">
                                <div class="row g-3 mb-3 product-row p-3 bg-light rounded-3 border">
                                    <div class="col-md-7">
                                        <label class="form-label fw-semibold small text-muted">Pilih Produk</label>
                                        <select name="items[0][id]" class="form-select border-0 shadow-sm" required>
                                            <option value="" disabled selected>Pilih Produk...</option>
                                            @foreach($items as $item)
                                                <option value="{{ $item->id }}" data-price="{{ $item->price }}">
                                                    {{ $item->name }} (Rp {{ number_format($item->price, 0, ',', '.') }}) - Stok: {{ $item->total }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold small text-muted">Kuantitas</label>
                                        <input type="number" name="items[0][quantity]" class="form-control border-0 shadow-sm" placeholder="Qty" min="1" value="1" required>
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="button" class="btn btn-outline-danger w-100 remove-btn shadow-sm" disabled><i class="bi bi-trash"></i> Hapus</button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-4 text-end mt-2">
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 shadow-sm" id="add-product-btn">
                                    <i class="bi bi-plus-lg me-1"></i> Tambah Produk Lain
                                </button>
                            </div>
                        </div>

                        <div class="mb-4 pt-3 border-top">
                            <label class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.05em;">Catatan Pesanan</label>
                            <textarea name="notes" class="form-control" rows="3" placeholder="Catatan opsional (misal: bungkus kado, warna, dll.)"></textarea>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                            <a href="{{ route('orders.index') }}" class="btn btn-light border rounded-pill px-4 shadow-sm text-muted">Batal</a>
                            <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm"><i class="bi bi-cart-check me-1"></i> Buat Pesanan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let productIndex = 1;
        const productList = document.getElementById('product-list');
        const addBtn = document.getElementById('add-product-btn');

        addBtn.addEventListener('click', function() {
            const row = document.querySelector('.product-row').cloneNode(true);
            
            // Update input attributes for the cloned row
            row.querySelector('select').name = `items[${productIndex}][id]`;
            row.querySelector('select').value = '';
            row.querySelector('input').name = `items[${productIndex}][quantity]`;
            row.querySelector('input').value = '1';
            
            // Reset state
            const removeBtn = row.querySelector('.remove-btn');
            removeBtn.disabled = false;
            
            // Add remove event listener
            removeBtn.addEventListener('click', function() {
                row.remove();
            });

            productList.appendChild(row);
            productIndex++;
        });
    });
</script>
@endpush
@endsection
