@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Log Aktivitas</h4>
            <p class="text-muted small mb-0">Riwayat aktivitas administrator.</p>
        </div>
    </div>

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div class="d-flex flex-wrap gap-2">
            <a href="#" class="filter-pill active">
                Semua Log <span class="badge bg-secondary-subtle text-secondary rounded-pill ms-1">{{ $logs->total() }}</span>
            </a>
        </div>
        <div class="search-wrapper">
            <i class="bi bi-search"></i>
            <input type="text" class="form-control search-pill" placeholder="Cari aktivitas...">
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table custom-table mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;" class="text-center">
                            <div class="form-check custom-checkbox d-flex justify-content-center m-0">
                                <input class="form-check-input" type="checkbox" id="selectAllLogs">
                            </div>
                        </th>
                        <th><div class="sortable-header">Waktu <i class="bi bi-chevron-expand"></i></div></th>
                        <th><div class="sortable-header">Admin <i class="bi bi-chevron-expand"></i></div></th>
                        <th><div class="sortable-header">Aksi <i class="bi bi-chevron-expand"></i></div></th>
                        <th><div class="sortable-header">Deskripsi <i class="bi bi-chevron-expand"></i></div></th>
                        <th><div class="sortable-header">IP Address <i class="bi bi-chevron-expand"></i></div></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td class="text-center">
                            <div class="form-check custom-checkbox d-flex justify-content-center m-0">
                                <input class="form-check-input row-checkbox" type="checkbox" value="{{ $log->id }}">
                            </div>
                        </td>
                        <td class="text-muted small">
                            <i class="bi bi-clock me-1"></i>{{ $log->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="fw-bold text-dark">
                            <i class="bi bi-person me-1 text-muted"></i>{{ $log->user->name ?? 'System' }}
                        </td>
                        <td>
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2 py-1">
                                {{ $log->action }}
                            </span>
                        </td>
                        <td class="text-muted small">{{ $log->description ?: '-' }}</td>
                        <td class="text-muted small font-monospace">{{ $log->ip_address ?: '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-journal-x fs-1 d-block mb-3"></i>
                            Belum ada aktivitas tercatat.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="d-flex justify-content-between align-items-center p-3 border-top">
                <div class="text-muted small d-none d-md-block">
                    <!-- Readonly Table -->
                </div>
                <div>
                    {{ $logs->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAll = document.getElementById('selectAllLogs');
        if (selectAll) {
            const checkboxes = document.querySelectorAll('.row-checkbox');
            
            selectAll.addEventListener('change', function() {
                checkboxes.forEach(cb => cb.checked = selectAll.checked);
            });

            checkboxes.forEach(cb => {
                cb.addEventListener('change', function() {
                    const allChecked = document.querySelectorAll('.row-checkbox:checked').length === checkboxes.length;
                    selectAll.checked = allChecked;
                });
            });
        }
    });
</script>
@endpush
@endsection