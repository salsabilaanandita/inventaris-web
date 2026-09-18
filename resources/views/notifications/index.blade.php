@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm border-0 mx-auto" style="max-width: 800px;">
        <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0"><i class="bi bi-bell me-2 text-primary"></i> Notifikasi Operasional</h5>
            <form action="{{ route('notifications.markAllAsRead') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-light btn-sm text-muted">Tandai Semua Sudah Dibaca</button>
            </form>
        </div>
        
        <div class="card-body p-0">
            <div class="list-group list-group-flush mt-2">
                @forelse($notifications as $notif)
                    <div class="list-group-item list-group-item-action p-4 {{ !$notif->is_read ? 'bg-primary-subtle' : '' }}">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="fw-bold mb-1 {{ !$notif->is_read ? 'text-body' : 'text-muted' }}">
                                    @if($notif->type == 'low_stock')
                                        <i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>
                                    @elseif($notif->type == 'pending_order')
                                        <i class="bi bi-cart-fill text-info me-2"></i>
                                    @else
                                        <i class="bi bi-info-circle-fill text-primary me-2"></i>
                                    @endif
                                    {{ $notif->title }}
                                </h6>
                                <p class="small mb-2 {{ !$notif->is_read ? 'text-body' : 'text-muted' }}">{{ $notif->message }}</p>
                                <small class="text-muted">{{ $notif->created_at->diffForHumans() }}</small>
                            </div>
                            @if(!$notif->is_read)
                                <form action="{{ route('notifications.markAsRead', $notif->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-primary">Buka</button>
                                </form>
                            @else
                                @if($notif->link)
                                    <a href="{{ $notif->link }}" class="btn btn-sm btn-light border">Buka</a>
                                @endif
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <i class="bi bi-bell text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-3">Tidak ada notifikasi saat ini.</p>
                    </div>
                @endforelse
            </div>
        </div>
        <div class="card-footer bg-white border-0 py-3">
            {{ $notifications->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
