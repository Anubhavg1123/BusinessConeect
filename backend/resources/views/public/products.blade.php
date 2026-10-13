@extends('layouts.app')

@section('title', 'Products Directory – Business Connect')

@section('content')
<div class="bg-primary-subtle py-4 border-bottom">
    <div class="container">
        <h2 class="fw-bold mb-1">Products Showcase</h2>
        <p class="text-muted mb-0">Discover top quality products promoted directly by verified businesses</p>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4">
        @forelse($products as $product)
            <div class="col-md-6 col-lg-4">
                <div class="glass-card h-100 p-4 d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge bg-primary-subtle text-primary rounded-pill">{{ $product->availability }}</span>
                        @if($product->discount > 0)
                            <span class="badge bg-danger rounded-pill">{{ $product->discount }}% OFF</span>
                        @endif
                    </div>
                    <h5 class="fw-bold mb-2">{{ $product->name }}</h5>
                    <p class="text-muted small flex-grow-1">{{ Str::limit($product->description, 90) }}</p>
                    <div class="mb-3">
                        <small class="text-muted"><i class="bi bi-shop me-1"></i> Offered by: <strong>{{ $product->business->name }}</strong></small>
                    </div>
                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                        <div>
                            <span class="fs-4 fw-bold text-primary">${{ number_format($product->finalPrice(), 2) }}</span>
                            @if($product->discount > 0)
                                <span class="text-muted text-decoration-line-through small me-1">${{ number_format($product->price, 2) }}</span>
                            @endif
                        </div>
                        <a href="{{ route('businesses.show', $product->business_id) }}" class="btn btn-outline-primary rounded-pill btn-sm">Contact Seller</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 py-5 text-center text-muted">No products available at the moment.</div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $products->links() }}
    </div>
</div>
@endsection
