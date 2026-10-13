@extends('layouts.app')

@section('title', 'Customer Portal – Business Connect')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0">Customer Portal</h3>
            <p class="text-muted small mb-0">Welcome back, {{ Auth::user()->name }}</p>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="glass-card p-4 text-center">
                <i class="bi bi-heart-fill fs-1 text-danger mb-2"></i>
                <h4 class="fw-bold mb-0">{{ $favorites->count() }}</h4>
                <small class="text-muted">Saved Favorite Businesses</small>
            </div>
        </div>
        <div class="col-md-4">
            <div class="glass-card p-4 text-center">
                <i class="bi bi-send-check-fill fs-1 text-primary mb-2"></i>
                <h4 class="fw-bold mb-0">{{ $inquiries->count() }}</h4>
                <small class="text-muted">Inquiries Submitted</small>
            </div>
        </div>
        <div class="col-md-4">
            <div class="glass-card p-4 text-center">
                <i class="bi bi-bell-fill fs-1 text-warning mb-2"></i>
                <h4 class="fw-bold mb-0">{{ $notifications->count() }}</h4>
                <small class="text-muted">Active Notifications</small>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Favorites Grid -->
        <div class="col-lg-6">
            <div class="glass-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0">Favorite Businesses</h5>
                    <a href="{{ route('customer.favorites') }}" class="btn btn-sm btn-outline-primary rounded-pill">View All</a>
                </div>
                <div class="row g-3">
                    @forelse($favorites as $fav)
                        <div class="col-12">
                            <div class="p-3 bg-body-tertiary rounded-3 d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="fw-bold mb-0">{{ $fav->business->name }}</h6>
                                    <small class="text-muted"><i class="bi bi-geo-alt me-1"></i>{{ $fav->business->city }}</small>
                                </div>
                                <a href="{{ route('businesses.show', $fav->business->id) }}" class="btn btn-sm btn-primary rounded-pill">View</a>
                            </div>
                        </div>
                    @empty
                        <div class="text-muted py-4 text-center">No favorites saved yet.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Inquiries Log -->
        <div class="col-lg-6">
            <div class="glass-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0">Recent Inquiries</h5>
                    <a href="{{ route('customer.inquiries') }}" class="btn btn-sm btn-outline-primary rounded-pill">History</a>
                </div>
                <div class="list-group list-group-flush">
                    @forelse($inquiries as $inq)
                        <div class="list-group-item bg-transparent border-0 px-0 py-2">
                            <div class="d-flex justify-content-between">
                                <strong class="small">{{ $inq->business->name }}</strong>
                                <span class="badge {{ $inq->status === 'replied' ? 'bg-success' : 'bg-warning text-dark' }}">{{ $inq->status }}</span>
                            </div>
                            <small class="text-muted d-block text-truncate">{{ $inq->subject }}</small>
                        </div>
                    @empty
                        <div class="text-muted py-4 text-center">No inquiries submitted yet.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
