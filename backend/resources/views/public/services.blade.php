@extends('layouts.app')

@section('title', 'Services Directory – Business Connect')

@section('content')
<div class="bg-primary-subtle py-4 border-bottom">
    <div class="container">
        <h2 class="fw-bold mb-1">Professional Services</h2>
        <p class="text-muted mb-0">Book expert services offered by accredited companies</p>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4">
        @forelse($services as $service)
            <div class="col-md-6 col-lg-4">
                <div class="glass-card h-100 p-4 d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge bg-info-subtle text-info rounded-pill"><i class="bi bi-clock me-1"></i> {{ $service->duration }}</span>
                        <span class="badge bg-success-subtle text-success rounded-pill">{{ $service->availability }}</span>
                    </div>
                    <h5 class="fw-bold mb-2">{{ $service->name }}</h5>
                    <p class="text-muted small flex-grow-1">{{ Str::limit($service->description, 100) }}</p>
                    <div class="mb-3">
                        <small class="text-muted"><i class="bi bi-building me-1"></i> Provider: <strong>{{ $service->business->name }}</strong></small>
                    </div>
                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                        <span class="fs-4 fw-bold text-success">${{ number_format($service->price, 2) }}</span>
                        <a href="{{ route('businesses.show', $service->business_id) }}" class="btn btn-primary rounded-pill btn-sm">Book Service</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 py-5 text-center text-muted">No services listed yet.</div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $services->links() }}
    </div>
</div>
@endsection
