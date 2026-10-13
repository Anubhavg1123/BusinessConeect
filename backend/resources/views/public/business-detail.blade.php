@extends('layouts.app')

@section('title', $business->name . ' – Business Connect')

@section('content')
<!-- Business Banner Header -->
<div class="bg-dark text-white py-5 position-relative">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-md-2 text-center text-md-start">
                <div class="bg-primary text-white rounded-4 p-4 d-inline-flex align-items-center justify-content-center fw-bold display-4 shadow" style="width: 110px; height: 110px;">
                    {{ substr($business->name, 0, 1) }}
                </div>
            </div>
            <div class="col-md-7">
                <span class="badge bg-success rounded-pill px-3 py-2 mb-2"><i class="bi bi-patch-check-fill me-1"></i> Verified Business</span>
                <h1 class="fw-bold mb-2">{{ $business->name }}</h1>
                <p class="text-white-50 mb-2"><i class="bi bi-geo-alt-fill text-danger me-1"></i> {{ $business->address }}, {{ $business->city }}, {{ $business->state }} {{ $business->pin_code }}</p>
                <div class="d-flex align-items-center gap-3">
                    <div class="rating-stars">
                        <i class="bi bi-star-fill text-warning"></i>
                        <span class="fw-bold text-white fs-5 ms-1">{{ $business->averageRating() }}</span>
                        <span class="text-white-50">({{ $business->reviews->count() }} customer reviews)</span>
                    </div>
                    <span class="text-white-50">|</span>
                    <span class="text-white-50"><i class="bi bi-eye me-1"></i> {{ $business->views_count }} views</span>
                </div>
            </div>
            <div class="col-md-3 text-center text-md-end">
                @auth
                    <form action="{{ route('customer.favorite.toggle', $business->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button class="btn btn-outline-light rounded-pill px-4 mb-2 w-100"><i class="bi bi-heart me-1"></i> Save to Favorites</button>
                    </form>
                @endauth
                <button class="btn btn-primary rounded-pill px-4 w-100" data-bs-toggle="modal" data-bs-target="#inquiryModal"><i class="bi bi-chat-dots me-1"></i> Send Inquiry</button>
            </div>
        </div>
    </div>
</div>

<!-- Tabs Navigation -->
<div class="container py-5">
    <ul class="nav nav-pills custom-pills mb-4 gap-2" id="businessTab" role="tablist">
        <li class="nav-item"><button class="nav-link active rounded-pill px-4" data-bs-toggle="tab" data-bs-target="#overview"><i class="bi bi-info-circle me-1"></i> Overview</button></li>
        <li class="nav-item"><button class="nav-link rounded-pill px-4" data-bs-toggle="tab" data-bs-target="#products"><i class="bi bi-box-seam me-1"></i> Products ({{ $business->products->count() }})</button></li>
        <li class="nav-item"><button class="nav-link rounded-pill px-4" data-bs-toggle="tab" data-bs-target="#services"><i class="bi bi-tools me-1"></i> Services ({{ $business->services->count() }})</button></li>
        <li class="nav-item"><button class="nav-link rounded-pill px-4" data-bs-toggle="tab" data-bs-target="#reviews"><i class="bi bi-star me-1"></i> Reviews ({{ $business->reviews->count() }})</button></li>
    </ul>

    <div class="tab-content" id="businessTabContent">
        <!-- Overview Tab -->
        <div class="tab-pane fade show active" id="overview">
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="glass-card p-4 mb-4">
                        <h4 class="fw-bold mb-3">About {{ $business->name }}</h4>
                        <p class="text-secondary leading-relaxed">{{ $business->description }}</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="glass-card p-4">
                        <h5 class="fw-bold mb-3"><i class="bi bi-telephone me-2 text-primary"></i>Contact Information</h5>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-3"><i class="bi bi-person me-2 text-muted"></i> <strong>Owner:</strong> {{ $business->owner_name }}</li>
                            <li class="mb-3"><i class="bi bi-envelope me-2 text-muted"></i> <strong>Email:</strong> {{ $business->email }}</li>
                            <li class="mb-3"><i class="bi bi-phone me-2 text-muted"></i> <strong>Phone:</strong> {{ $business->phone }}</li>
                            <li class="mb-3"><i class="bi bi-clock me-2 text-muted"></i> <strong>Hours:</strong> {{ $business->opening_time ?? '09:00 AM' }} - {{ $business->closing_time ?? '06:00 PM' }}</li>
                            @if($business->gst_number)
                                <li class="mb-3"><i class="bi bi-receipt me-2 text-muted"></i> <strong>GST No:</strong> {{ $business->gst_number }}</li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products Tab -->
        <div class="tab-pane fade" id="products">
            <div class="row g-4">
                @forelse($business->products as $product)
                    <div class="col-md-4">
                        <div class="glass-card p-4 h-100">
                            <h5 class="fw-bold mb-2">{{ $product->name }}</h5>
                            <p class="text-muted small">{{ $product->description }}</p>
                            <div class="d-flex justify-content-between align-items-center pt-3 border-top mt-auto">
                                <span class="fs-4 fw-bold text-primary">${{ number_format($product->price, 2) }}</span>
                                <span class="badge bg-success-subtle text-success">{{ $product->availability }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-4 text-muted">No products cataloged yet.</div>
                @endforelse
            </div>
        </div>

        <!-- Services Tab -->
        <div class="tab-pane fade" id="services">
            <div class="row g-4">
                @forelse($business->services as $service)
                    <div class="col-md-4">
                        <div class="glass-card p-4 h-100">
                            <h5 class="fw-bold mb-2">{{ $service->name }}</h5>
                            <p class="text-muted small">{{ $service->description }}</p>
                            <div class="d-flex justify-content-between align-items-center pt-3 border-top mt-auto">
                                <span class="fs-4 fw-bold text-success">${{ number_format($service->price, 2) }}</span>
                                <span class="badge bg-info-subtle text-info"><i class="bi bi-stopwatch me-1"></i>{{ $service->duration }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-4 text-muted">No services listed yet.</div>
                @endforelse
            </div>
        </div>

        <!-- Reviews Tab -->
        <div class="tab-pane fade" id="reviews">
            <div class="row g-4">
                <div class="col-lg-8">
                    @forelse($business->reviews as $rev)
                        <div class="glass-card p-4 mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <strong class="fs-6">{{ $rev->customer->name ?? 'Verified Customer' }}</strong>
                                <div class="rating-stars small">
                                    @for($i=1; $i<=5; $i++)
                                        <i class="bi bi-star-fill {{ $i <= $rev->rating ? 'text-warning' : 'text-muted' }}"></i>
                                    @endfor
                                </div>
                            </div>
                            <p class="text-secondary mb-2">{{ $rev->review_text }}</p>
                            <small class="text-muted">{{ $rev->created_at->diffForHumans() }}</small>
                        </div>
                    @empty
                        <div class="text-muted py-4">Be the first to review this business!</div>
                    @endforelse

                    @auth
                        <div class="glass-card p-4 mt-4">
                            <h5 class="fw-bold mb-3">Leave a Review</h5>
                            <form action="{{ route('review.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="business_id" value="{{ $business->id }}">
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Rating</label>
                                    <select name="rating" class="form-select rounded-3">
                                        <option value="5">5 Stars - Excellent</option>
                                        <option value="4">4 Stars - Very Good</option>
                                        <option value="3">3 Stars - Average</option>
                                        <option value="2">2 Stars - Poor</option>
                                        <option value="1">1 Star - Terrible</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">Your Feedback</label>
                                    <textarea name="review_text" rows="3" class="form-control rounded-3" placeholder="Share your experience..." required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary rounded-pill px-4">Submit Review</button>
                            </form>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Inquiry Modal -->
<div class="modal fade" id="inquiryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-card border-0 p-3">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Inquire with {{ $business->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('inquiry.store') }}" method="POST">
                @csrf
                <input type="hidden" name="business_id" value="{{ $business->id }}">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Your Name</label>
                        <input type="text" name="name" class="form-control rounded-3" value="{{ Auth::user()->name ?? '' }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Email Address</label>
                        <input type="email" name="email" class="form-control rounded-3" value="{{ Auth::user()->email ?? '' }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Phone Number</label>
                        <input type="text" name="phone" class="form-control rounded-3" value="{{ Auth::user()->phone ?? '' }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Subject</label>
                        <input type="text" name="subject" class="form-control rounded-3" placeholder="e.g. Product Pricing Inquiry" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Message</label>
                        <textarea name="message" rows="3" class="form-control rounded-3" placeholder="State your requirements clearly..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Submit Inquiry</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
