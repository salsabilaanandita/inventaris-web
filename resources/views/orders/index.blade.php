@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Pesanan Masuk</h4>
            <p class="text-muted small mb-0">Kelola pesanan dan perbarui status pengiriman.</p>
        </div>
        <a href="{{ route('orders.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm"><i class="bi bi-plus-lg me-1"></i> Buat Pesanan Baru</a>
    </div>

    <!-- Toolbar: Tabs/Pills & Search -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('orders.index', ['status' => 'all']) }}" class="filter-pill {{ $status == 'all' ? 'active' : '' }}">
                Semua
            </a>
            <a href="{{ route('orders.index', ['status' => 'Menunggu Pembayaran']) }}" class="filter-pill {{ $status == 'Menunggu Pembayaran' ? 'active' : '' }}">
                Menunggu <span class="badge bg-secondary-subtle text-secondary rounded-pill ms-1">3</span>
            </a>
            <a href="{{ route('orders.index', ['status' => 'Pesanan Diproses']) }}" class="filter-pill {{ $status == 'Pesanan Diproses' ? 'active' : '' }}">
                Diproses
            </a>
            <a href="{{ route('orders.index', ['status' => 'Pesanan Dikirim']) }}" class="filter-pill {{ $status == 'Pesanan Dikirim' ? 'active' : '' }}">
                Dikirim
            </a>
            <a href="{{ route('orders.index', ['status' => 'Pesanan Selesai']) }}" class="filter-pill {{ $status == 'Pesanan Selesai' ? 'active' : '' }}">
                Selesai
            </a>
        </div>

        <div class="search-wrapper">
            <i class="bi bi-search"></i>
            <input type="text" class="form-control search-pill" placeholder="Cari pesanan...">
        </div>
    </div>

    <!-- Data Table Card -->
    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table custom-table mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;" class="text-center">
                            <div class="form-check custom-checkbox d-flex justify-content-center m-0">
                                <input class="form-check-input" type="checkbox" id="selectAll">
                            </div>
                        </th>
                        <th>
                            <div class="sortable-header">No. Pesanan <i class="bi bi-chevron-expand"></i></div>
                        </th>
                        <th>
                            <div class="sortable-header">Pelanggan <i class="bi bi-chevron-expand"></i></div>
                        </th>
                        <th>
                            <div class="sortable-header">Tanggal <i class="bi bi-chevron-expand"></i></div>
                        </th>
                        <th>
                            <div class="sortable-header">Total <i class="bi bi-chevron-expand"></i></div>
                        </th>
                        <th>
                            <div class="sortable-header">Status <i class="bi bi-chevron-expand"></i></div>
                        </th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td class="text-center">
                            <div class="form-check custom-checkbox d-flex justify-content-center m-0">
                                <input class="form-check-input row-checkbox" type="checkbox" value="{{ $order->id }}">
                            </div>
                        </td>
                        <td>
                            <a href="{{ route('orders.show', $order->id) }}" class="text-decoration-none fw-bold text-dark">
                                <i class="bi bi-receipt text-muted me-2"></i>{{ $order->order_number }}
                            </a>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar rounded-circle bg-light d-flex justify-content-center align-items-center text-muted fw-bold" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                    {{ substr($order->customer_name, 0, 1) }}
                                </div>
                                <span class="fw-medium">{{ $order->customer_name }}</span>
                            </div>
                        </td>
                        <td class="text-muted small">
                            <i class="bi bi-calendar-event me-1"></i>{{ $order->created_at->format('d M Y') }}<br>
                            <span style="font-size: 0.75rem;"><i class="bi bi-clock me-1"></i>{{ $order->created_at->format('H:i') }}</span>
                        </td>
                        <td class="fw-bold text-dark">
                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                        </td>
                        <td>
                            @if($order->status == 'Menunggu Pembayaran')
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-2 py-1"><i class="bi bi-clock me-1"></i>{{ $order->status }}</span>
                            @elseif($order->status == 'Pesanan Diproses')
                                <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill px-2 py-1"><i class="bi bi-box-seam me-1"></i>{{ $order->status }}</span>
                            @elseif($order->status == 'Pesanan Dikirim')
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2 py-1"><i class="bi bi-truck me-1"></i>{{ $order->status }}</span>
                            @elseif($order->status == 'Pesanan Selesai')
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1"><i class="bi bi-check-circle me-1"></i>{{ $order->status }}</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-2 py-1">{{ $order->status }}</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-1">
                                @if($order->status == 'Menunggu Pembayaran')
                                    <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST" class="d-inline">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="Pesanan Diproses">
                                        <button class="btn btn-sm btn-info text-white rounded-pill shadow-sm" data-bs-toggle="tooltip" title="Proses"><i class="bi bi-play-fill"></i></button>
                                    </form>
                                @elseif($order->status == 'Pesanan Diproses')
                                    <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST" class="d-inline">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="Pesanan Dikirim">
                                        <button class="btn btn-sm btn-primary rounded-pill shadow-sm" data-bs-toggle="tooltip" title="Kirim"><i class="bi bi-truck"></i></button>
                                    </form>
                                @elseif($order->status == 'Pesanan Dikirim')
                                    <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST" class="d-inline">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="Pesanan Selesai">
                                        <button class="btn btn-sm btn-success rounded-pill shadow-sm" data-bs-toggle="tooltip" title="Selesai"><i class="bi bi-check-lg"></i></button>
                                    </form>
                                @endif
                                <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-light border rounded-pill text-primary" data-bs-toggle="tooltip" title="Detail"><i class="bi bi-eye"></i></a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                            Belum ada pesanan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            
            <div class="d-flex justify-content-between align-items-center p-3 border-top">
                <div class="text-muted small d-none d-md-block">
                    <!-- Bulk actions dropdown could go here later -->
                    <button class="btn btn-sm btn-light border rounded-pill px-3" disabled id="bulkActionBtn">
                        <i class="bi bi-trash me-1"></i> Hapus Terpilih
                    </button>
                </div>
                <div>
                    {{ $orders->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.row-checkbox');
        const bulkBtn = document.getElementById('bulkActionBtn');

        function updateBulkBtn() {
            const checkedCount = document.querySelectorAll('.row-checkbox:checked').length;
            bulkBtn.disabled = checkedCount === 0;
        }

        selectAll.addEventListener('change', function() {
            checkboxes.forEach(cb => cb.checked = selectAll.checked);
            updateBulkBtn();
        });

        checkboxes.forEach(cb => {
            cb.addEventListener('change', function() {
                const allChecked = document.querySelectorAll('.row-checkbox:checked').length === checkboxes.length;
                selectAll.checked = allChecked;
                updateBulkBtn();
            });
        });
    });
</script>
@endpush
@endsection
