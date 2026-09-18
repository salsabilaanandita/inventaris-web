@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm border-0 mx-auto" style="max-width: 600px;">
        <div class="card-header bg-white border-0 p-4 border-bottom border-light text-center position-relative mt-4">
            <div class="position-absolute top-0 start-50 translate-middle">
                <div class="rounded-circle d-flex align-items-center justify-content-center shadow" style="width: 80px; height: 80px; background: var(--accent); font-size: 2rem; color: white;">
                    {{ substr($user->name, 0, 1) }}
                </div>
            </div>
            <div class="mt-4 pt-3">
                <h5 class="fw-bold mb-1">Profil Saya</h5>
                <p class="text-muted small mb-0">Perbarui informasi akun Anda.</p>
            </div>
        </div>

        <div class="card-body p-4">
            <form action="{{ route('staff.users.update') }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.05em;"><i class="bi bi-person me-1"></i> Nama Lengkap</label>
                    <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.05em;"><i class="bi bi-envelope me-1"></i> Alamat Email</label>
                    <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.05em;">
                        <i class="bi bi-key me-1"></i> Password Baru <span class="text-warning text-lowercase fw-normal">(Opsional)</span>
                    </label>
                    <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah password">
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm w-100"><i class="bi bi-check-lg me-1"></i> Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection