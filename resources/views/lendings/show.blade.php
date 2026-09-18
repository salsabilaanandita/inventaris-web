@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="d-flex justify-content-between align-items-center p-4">
                <div>
                    <h5 class="fw-bold mb-0">Lending Table</h5>
                    <p class="text-muted small mb-0">History of transactions for <span class="text-primary">{{ $item->name }}</span></p>
                </div>
                <a href="{{ route('items.index') }}" class="btn btn-secondary btn-sm px-3">Back</a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr class="text-muted small">
                            <th class="px-4 py-3">No</th>
                            <th class="py-3">Item</th>
                            <th class="py-3">Total</th>
                            <th class="py-3">Name</th>
                            <th class="py-3">Ket.</th>
                            <th class="py-3">Date</th>
                            <th class="py-3">Returned</th>
                            <th class="py-3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($item->lendings as $lending)
                        <tr>
                            <td class="px-4 py-3 small text-muted">{{ $loop->iteration }}</td>
                            <td class="py-3 fw-bold small">{{ $item->name }}</td>
                            <td class="py-3 small">{{ $lending->total }}</td>
                            <td class="py-3 small">{{ $lending->name }}</td>
                            <td class="py-3 small text-muted">{{ $lending->notes ?? '-' }}</td>
                            <td class="py-3 small">{{ $lending->created_at->format('d F, Y') }}</td>
                            <td class="py-3">
                                @if($lending->status == 'not returned')
                                    <span class="badge bg-warning-subtle text-warning border border-warning px-3 py-2">
                                        not returned
                                    </span>
                                @else
                                    <span class="badge bg-success-subtle text-success border border-success px-3 py-2">
                                        {{ $lending->updated_at->format('d F, Y') }}
                                    </span>
                                @endif
                            </td>
                            <td class="py-3">
                                <div class="d-flex gap-1">
                                    @if($lending->status == 'not returned')
                                        <!-- <form action="{{ route('lending.update', $lending->id) }}" method="POST"> -->
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-warning btn-sm fw-bold px-3">Returned</button>
                                        </form>
                                    @endif
                                    <form action="{{ route('lending.destroy', $lending->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm px-3" onclick="return confirm('Delete this record?')">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted small">No data available for this item.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection