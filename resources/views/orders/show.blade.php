@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Detail Pesanan</h4>
        <a href="{{ route('orders.index') }}" class="btn btn-light border"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>

    <div class="row g-4">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3 border-bottom pb-2">Daftar Produk</h6>
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th class="small text-muted">Produk</th>
                                    <th class="small text-muted text-center">Harga</th>
                                    <th class="small text-muted text-center">Qty</th>
                                    <th class="small text-muted text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderItems as $item)
                                <tr>
                                    <td class="fw-bold small">{{ $item->item->name ?? 'Produk Dihapus' }}</td>
                                    <td class="text-center small">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td class="text-center small">{{ $item->quantity }}</td>
                                    <td class="text-end fw-bold small">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                                <tr class="border-top">
                                    <td colspan="3" class="text-end fw-bold">Total Pembayaran</td>
                                    <td class="text-end fw-bold text-primary fs-5">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3 border-bottom pb-2">Informasi Pesanan</h6>
                    <div class="mb-3">
                        <p class="text-muted small mb-1">No. Pesanan</p>
                        <p class="fw-bold mb-0">{{ $order->order_number }}</p>
                    </div>
                    <div class="mb-3">
                        <p class="text-muted small mb-1">Status Saat Ini</p>
                        @if($order->status == 'Menunggu Pembayaran')
                            <span class="badge bg-warning text-dark">{{ $order->status }}</span>
                        @elseif($order->status == 'Pesanan Diproses')
                            <span class="badge bg-info text-white">{{ $order->status }}</span>
                        @elseif($order->status == 'Pesanan Dikirim')
                            <span class="badge bg-primary">{{ $order->status }}</span>
                        @elseif($order->status == 'Pesanan Selesai')
                            <span class="badge bg-success">{{ $order->status }}</span>
                        @else
                            <span class="badge bg-secondary">{{ $order->status }}</span>
                        @endif
                    </div>
                    <div class="mb-3">
                        <p class="text-muted small mb-1">Pelanggan</p>
                        <p class="fw-bold mb-0">{{ $order->customer_name }}</p>
                        <p class="small text-muted mb-0">{{ $order->customer_phone }}</p>
                    </div>
                    <div class="mb-3">
                        <p class="text-muted small mb-1">Tanggal Dibuat</p>
                        <p class="small mb-0">{{ $order->created_at->format('d M Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-muted small mb-1">Catatan</p>
                        <p class="small mb-0">{{ $order->notes ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body p-4 text-center">
                    <h6 class="fw-bold mb-3">Aksi Status</h6>
                    @if($order->status == 'Menunggu Pembayaran')
                        <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="Pesanan Diproses">
                            <button class="btn btn-info text-white w-100 mb-2">Proses Pesanan</button>
                        </form>
                    @elseif($order->status == 'Pesanan Diproses')
                        <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="Pesanan Dikirim">
                            <button class="btn btn-primary w-100 mb-2">Kirim Pesanan</button>
                        </form>
                    @elseif($order->status == 'Pesanan Dikirim')
                        <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="Pesanan Selesai">
                            <button class="btn btn-success w-100 mb-2">Selesaikan Pesanan</button>
                        </form>
                    @endif
                    @if($order->status != 'Pesanan Selesai' && $order->status != 'Dibatalkan')
                    <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="Dibatalkan">
                        <button class="btn btn-outline-danger w-100" onclick="return confirm('Yakin batalkan pesanan ini?')">Batalkan Pesanan</button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
