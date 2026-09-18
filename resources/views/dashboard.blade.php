@extends('layouts.app')

@section('content')
<style>
    /* Vizora-Inspired Dashboard Styles */
    .btn-vizora {
        background: #0f4032; /* Match sidebar */
        border: none;
        color: white;
        border-radius: 8px;
        transition: all 0.2s ease;
    }
    .btn-vizora:hover {
        background: #115e59;
        color: white;
    }

    .vizora-card {
        background: var(--bg-sidebar);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.02);
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    
    .vizora-card-body {
        padding: 1.25rem;
        flex: 1;
    }

    .vizora-icon-box {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        border: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        justify-content: center;
        background: transparent;
    }
    .vizora-icon-box.green { color: #10b981; border-color: #a7f3d0; }
    .vizora-icon-box.red { color: #ef4444; border-color: #fecaca; }
    .vizora-icon-box.blue { color: #3b82f6; border-color: #bfdbfe; }
    .vizora-icon-box.orange { color: #f59e0b; border-color: #fde68a; }

    .vizora-badge {
        font-size: 0.7rem;
        padding: 3px 8px;
        border-radius: 50px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 3px;
    }
    .vizora-badge.green { background: #ecfdf5; color: #059669; }
    .vizora-badge.red { background: #fef2f2; color: #dc2626; }
    .vizora-badge.gray { background: #f1f5f9; color: #64748b; }

    .vizora-footer {
        padding: 0.75rem 1.25rem;
        border-top: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .vizora-footer-text {
        font-size: 0.75rem;
        color: var(--text-muted);
        font-weight: 500;
    }

    /* Chart specific */
    .chart-container {
        position: relative;
        height: 300px;
        width: 100%;
    }

    /* Custom Table for Dashboard */
    .vizora-table th {
        font-size: 0.75rem;
        text-transform: capitalize;
        color: var(--text-muted);
        font-weight: 500;
        border-bottom: 1px solid var(--border-color);
        padding: 1rem 1.25rem;
    }
    .vizora-table td {
        font-size: 0.85rem;
        color: var(--text-dark);
        border-bottom: 1px solid var(--border-color);
        padding: 1rem 1.25rem;
        vertical-align: middle;
    }
    .vizora-table tr:last-child td {
        border-bottom: none;
    }
</style>

<div class="container-fluid py-4 px-2 px-md-4">
    @php
        $hour = now()->format('H');
        $greeting = 'Selamat Pagi';
        if ($hour >= 12 && $hour < 15) $greeting = 'Selamat Siang';
        elseif ($hour >= 15 && $hour < 18) $greeting = 'Selamat Sore';
        elseif ($hour >= 18) $greeting = 'Selamat Malam';
    @endphp

    {{-- Header Section --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 pb-2">
        <div>
            <h4 class="fw-bold text-dark mb-1">Dashboard</h4>
            <p class="text-muted small mb-0">{{ $greeting }}, {{ auth()->user()->name }}</p>
        </div>
        <div class="d-flex align-items-center gap-3 mt-3 mt-md-0">
            @if(in_array(auth()->user()->role, ['super_admin', 'admin_gudang']))
                <a href="{{ route('items.create') }}" class="btn btn-vizora fw-medium d-flex align-items-center gap-2 px-4 py-2">
                    <i class="bi bi-box-arrow-in-down"></i> Barang Masuk
                </a>
            @endif
        </div>
    </div>

    {{-- Statistic Cards --}}
    <div class="row g-3 mb-4">
        {{-- Card 1: Total Barang --}}
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
            <div class="vizora-card">
                <div class="vizora-card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="text-muted text-uppercase fw-semibold" style="font-size: 0.65rem; letter-spacing: 0.5px;">Total Varian Barang</span>
                        <div class="vizora-icon-box green">
                            <i class="bi bi-box-seam"></i>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2 mt-2">
                        <h3 class="fw-bold text-dark mb-0" style="letter-spacing: -0.5px;">{{ number_format($totalBarang) }}</h3>
                        <span class="vizora-badge green"><i class="bi bi-arrow-up-short"></i> Active</span>
                    </div>
                </div>
                <div class="vizora-footer">
                    <span class="vizora-footer-text">Total jenis item di gudang</span>
                    <i class="bi bi-arrow-right text-muted small"></i>
                </div>
            </div>
        </div>
        
        {{-- Card 2: Total Nilai Aset --}}
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
            <div class="vizora-card">
                <div class="vizora-card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="text-muted text-uppercase fw-semibold" style="font-size: 0.65rem; letter-spacing: 0.5px;">Estimasi Aset</span>
                        <div class="vizora-icon-box blue">
                            <i class="bi bi-cash"></i>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2 mt-2">
                        <h4 class="fw-bold text-dark mb-0" style="letter-spacing: -0.5px;">Rp {{ number_format($totalAset, 0, ',', '.') }}</h4>
                    </div>
                </div>
                <div class="vizora-footer">
                    <span class="vizora-footer-text">Nilai total stok saat ini</span>
                    <i class="bi bi-arrow-right text-muted small"></i>
                </div>
            </div>
        </div>

        {{-- Card 3: Stok Kritis --}}
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
            <div class="vizora-card">
                <div class="vizora-card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="text-muted text-uppercase fw-semibold" style="font-size: 0.65rem; letter-spacing: 0.5px;">Stok Menipis</span>
                        <div class="vizora-icon-box red">
                            <i class="bi bi-exclamation-triangle"></i>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2 mt-2">
                        <h3 class="fw-bold text-dark mb-0" style="letter-spacing: -0.5px;">{{ number_format($stokKritis) }}</h3>
                        @if($stokKritis > 0)
                            <span class="vizora-badge red"><i class="bi bi-arrow-down-short"></i> Urgent</span>
                        @else
                            <span class="vizora-badge gray">Aman</span>
                        @endif
                    </div>
                </div>
                <div class="vizora-footer">
                    <span class="vizora-footer-text">Barang perlu restock segera</span>
                    <i class="bi bi-arrow-right text-muted small"></i>
                </div>
            </div>
        </div>

        {{-- Card 4: Dipinjam --}}
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
            <div class="vizora-card">
                <div class="vizora-card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="text-muted text-uppercase fw-semibold" style="font-size: 0.65rem; letter-spacing: 0.5px;">Barang Dipinjam</span>
                        <div class="vizora-icon-box orange">
                            <i class="bi bi-people"></i>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2 mt-2">
                        <h3 class="fw-bold text-dark mb-0" style="letter-spacing: -0.5px;">{{ number_format($sedangDipinjam) }}</h3>
                    </div>
                </div>
                <div class="vizora-footer">
                    <span class="vizora-footer-text">Belum dikembalikan</span>
                    <i class="bi bi-arrow-right text-muted small"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        {{-- Area Grafik & Tren --}}
        <div class="col-lg-8">
            <div class="vizora-card p-0">
                <div class="d-flex flex-wrap justify-content-between align-items-center p-4 border-bottom" style="border-color: var(--border-color) !important;">
                    <div>
                        <span class="text-muted text-uppercase fw-semibold d-block mb-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">Aktivitas Transaksi</span>
                        <h5 class="fw-bold text-dark mb-0">Tren 7 Hari Terakhir</h5>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center gap-2">
                            <span style="width: 8px; height: 8px; background: #10b981; border-radius: 2px;"></span>
                            <span class="small text-muted" style="font-size: 0.75rem;">Barang Masuk</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span style="width: 8px; height: 8px; background: #f59e0b; border-radius: 2px;"></span>
                            <span class="small text-muted" style="font-size: 0.75rem;">Barang Keluar</span>
                        </div>
                    </div>
                </div>
                <div class="p-4">
                    <div class="chart-container">
                        <canvas id="inventoryChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Panel Kanan: Stok Kritis --}}
        <div class="col-lg-4 d-flex flex-column">
            <div class="vizora-card p-0 h-100">
                <div class="d-flex justify-content-between align-items-center p-4 border-bottom" style="border-color: var(--border-color) !important;">
                    <div>
                        <span class="text-muted text-uppercase fw-semibold d-block mb-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">Alerts</span>
                        <h6 class="fw-bold text-dark mb-0">Perlu Restock</h6>
                    </div>
                    <div class="vizora-icon-box" style="border-color: transparent; width: auto; height: auto;">
                        <i class="bi bi-bar-chart text-muted"></i>
                    </div>
                </div>
                <div class="p-0">
                    <table class="table table-borderless vizora-table mb-0">
                        @forelse($itemsKritis as $item)
                        <tr>
                            <td class="py-3 px-4">
                                <div class="fw-semibold text-dark">{{ $item->name }}</div>
                                <div class="text-muted" style="font-size: 0.7rem;">{{ $item->category->name ?? '-' }}</div>
                            </td>
                            <td class="py-3 px-4 text-end">
                                <span class="fw-bold text-dark">{{ $item->total }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td class="text-center py-5 text-muted">Stok aman.</td>
                        </tr>
                        @endforelse
                    </table>
                </div>
            </div>
        </div>

        {{-- Tabel Activity Terbaru (Aktivitas Sistem) --}}
        <div class="col-12 mt-3">
            <div class="vizora-card p-0">
                <div class="d-flex flex-wrap justify-content-between align-items-center p-4 border-bottom" style="border-color: var(--border-color) !important;">
                    <div class="d-flex align-items-center gap-3">
                        <div>
                            <span class="text-muted text-uppercase fw-semibold d-block mb-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">Recent</span>
                            <h6 class="fw-bold text-dark mb-0">Log Aktivitas Sistem</h6>
                        </div>
                        <span class="vizora-badge gray"><i class="bi bi-clock"></i> Live</span>
                    </div>
                    
                    <a href="{{ route('activity-logs.index') ?? '#' }}" class="btn btn-light btn-sm border bg-transparent d-flex align-items-center gap-2 px-3">
                        <i class="bi bi-arrow-clockwise"></i> Detail
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-borderless vizora-table mb-0 align-middle">
                        <thead style="background: var(--bg-main);">
                            <tr>
                                <th>Pengguna</th>
                                <th>Tindakan</th>
                                <th>Keterangan</th>
                                <th class="text-end">Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($aktivitas as $log)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded bg-primary-subtle d-flex align-items-center justify-content-center text-primary fw-bold" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                            {{ substr($log->user->name ?? '?', 0, 1) }}
                                        </div>
                                        <span class="fw-medium text-dark">{{ $log->user->name ?? 'Sistem' }}</span>
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $actionColor = 'gray';
                                        if(str_contains($log->action, 'created') || str_contains($log->action, 'in')) $actionColor = 'green';
                                        if(str_contains($log->action, 'deleted') || str_contains($log->action, 'out')) $actionColor = 'red';
                                        if(str_contains($log->action, 'updated')) $actionColor = 'blue';
                                    @endphp
                                    <span class="vizora-badge {{ $actionColor }} px-2" style="border-radius: 4px;">
                                        {{ str_replace('_', ' ', $log->action) }}
                                    </span>
                                </td>
                                <td class="text-muted" style="max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    {{ $log->description }}
                                </td>
                                <td class="text-end text-muted">{{ $log->created_at->diffForHumans() }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center py-5 text-muted">Belum ada aktivitas tercatat.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const ctx = document.getElementById('inventoryChart').getContext('2d');
    
    // Warna Fill Solid Transparan
    let gradientIn = 'rgba(16, 185, 129, 0.1)';
    let gradientOut = 'rgba(245, 158, 11, 0.1)';

    // Data dari Controller
    const dbLabels = {!! json_encode($chartLabels) !!};
    const dbMasuk = {!! json_encode($chartMasuk) !!};
    const dbKeluar = {!! json_encode($chartKeluar) !!};

    let chartData = {
        labels: dbLabels,
        datasets: [
            {
                label: 'Barang Masuk',
                data: dbMasuk, 
                borderColor: '#10b981',
                backgroundColor: gradientIn,
                borderWidth: 2,
                fill: true,
                tension: 0.3,
                pointRadius: 0,
                pointHoverRadius: 5,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#10b981',
                pointBorderWidth: 2,
            },
            {
                label: 'Barang Keluar',
                data: dbKeluar, 
                borderColor: '#f59e0b',
                backgroundColor: gradientOut,
                borderWidth: 2,
                fill: true,
                tension: 0.3,
                pointRadius: 0,
                pointHoverRadius: 5,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#f59e0b',
                pointBorderWidth: 2,
            }
        ]
    };

    let inventoryChart = new Chart(ctx, {
        type: 'line',
        data: chartData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: { 
                    display: false // We use custom HTML legend above
                },
                tooltip: {
                    backgroundColor: '#fff',
                    titleColor: '#1e293b',
                    bodyColor: '#475569',
                    padding: 12,
                    cornerRadius: 8,
                    titleFont: { size: 13, family: "'Plus Jakarta Sans', sans-serif", weight: 'bold' },
                    bodyFont: { size: 12, family: "'Plus Jakarta Sans', sans-serif" },
                    borderColor: '#e2e8f0',
                    borderWidth: 1,
                    displayColors: true,
                    boxPadding: 4,
                    usePointStyle: true,
                    titleSpacing: 4,
                    callbacks: {
                        labelColor: function(context) {
                            return {
                                borderColor: context.dataset.borderColor,
                                backgroundColor: context.dataset.borderColor,
                                borderWidth: 2,
                                borderRadius: 2
                            };
                        }
                    }
                }
            },
            scales: {
                y: { 
                    beginAtZero: true, 
                    grid: { 
                        color: 'rgba(148, 163, 184, 0.1)',
                        drawBorder: false,
                    },
                    ticks: { color: '#94a3b8', font: { family: "'Plus Jakarta Sans', sans-serif", size: 11 }, padding: 10, maxTicksLimit: 6 },
                    border: { display: false }
                },
                x: { 
                    grid: { display: false },
                    ticks: { color: '#94a3b8', font: { family: "'Plus Jakarta Sans', sans-serif", size: 11 }, padding: 10 },
                    border: { display: false }
                }
            }
        }
    });

    // Handle Theme Change for Chart
    window.addEventListener('themeChanged', () => {
        const isDark = document.body.getAttribute('data-bs-theme') === 'dark';
        const gridColor = isDark ? 'rgba(255, 255, 255, 0.05)' : 'rgba(148, 163, 184, 0.1)';
        const textColor = isDark ? '#94a3b8' : '#94a3b8';
        const tooltipBg = isDark ? '#1e293b' : '#fff';
        const tooltipText = isDark ? '#fff' : '#1e293b';
        const tooltipBorder = isDark ? '#334155' : '#e2e8f0';

        inventoryChart.options.scales.y.grid.color = gridColor;
        inventoryChart.options.scales.y.ticks.color = textColor;
        inventoryChart.options.scales.x.ticks.color = textColor;
        
        inventoryChart.options.plugins.tooltip.backgroundColor = tooltipBg;
        inventoryChart.options.plugins.tooltip.titleColor = tooltipText;
        inventoryChart.options.plugins.tooltip.bodyColor = isDark ? '#cbd5e1' : '#475569';
        inventoryChart.options.plugins.tooltip.borderColor = tooltipBorder;
        
        inventoryChart.update();
    });
</script>
@endpush
@endsection